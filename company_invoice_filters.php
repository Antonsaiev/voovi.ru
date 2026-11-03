<?php
// Общие правила выборки для счётчика, списка и статистики. Без записи в БД.
function company_invoice_date($value, $fallback)
{
    if (!is_string($value) || !preg_match('/^(\d{4})-(\d{2})-(\d{2})$/D', $value, $parts) ||
        !checkdate((int)$parts[2], (int)$parts[3], (int)$parts[1])) { return $fallback; }
    return $value;
}

function company_invoice_period($from, $to)
{
    $from = company_invoice_date($from, date('Y-m-01'));
    $to = company_invoice_date($to, date('Y-m-t'));
    return $from <= $to ? array($from, $to) : array(date('Y-m-01'), date('Y-m-t'));
}

function company_pending_renewals_sql($from, $to)
{
    list($from, $to) = company_invoice_period($from, $to);
    return "schet.del='0' AND schet.cher='0' AND schet.otk='0' AND schet.prodlens='0' AND schet.prodlenks='0' AND " .
        "(schet.dateotkaz IS NULL OR schet.dateotkaz IN ('','0000-00-00')) AND " .
        "((schet.tipprod IN ('Сертификат','Сер/Пос') AND schet.prodlens='0' AND schet.dataprod BETWEEN '$from' AND '$to') OR " .
        "(schet.tipprod IN ('Поставка','Сер/Пос') AND schet.prodlenks='0' AND schet.datasert BETWEEN '$from' AND '$to')) AND " .
        // A new bill already closes the source's waiting-for-renewal stage,
        // regardless of payment or the date of that new bill. Refusal clones
        // also carry shetold. Drafts and deleted bills do not close this stage.
        "NOT EXISTS (SELECT 1 FROM schet AS renewal WHERE renewal.idkli=schet.idkli " .
        "AND renewal.del='0' AND renewal.otk='0' AND renewal.shetold=schet.ns AND renewal.rand<>schet.rand)";
}

function company_pending_parts($row, $from, $to)
{
    // Current business rule for all three types: either completed flag closes
    // the whole source invoice, even if the other component is still pending.
    if (!isset($row['prodlens'], $row['prodlenks']) ||
        (string)$row['prodlens'] !== '0' || (string)$row['prodlenks'] !== '0') { return array(); }
    list($from, $to) = company_invoice_period($from, $to);
    $parts = array();
    foreach (array('dataprod'=>array('prodlens','Сертификат'), 'datasert'=>array('prodlenks','Поставка')) as $date=>$part) {
        $value = isset($row[$date]) ? $row[$date] : '';
        $type = isset($row['tipprod']) ? $row['tipprod'] : '';
        if (($type === $part[1] || $type === 'Сер/Пос') && isset($row[$part[0]]) &&
            (string)$row[$part[0]] === '0' && $value >= $from && $value <= $to) {
            $parts[$part[1]] = $value;
        }
    }
    return $parts;
}

function company_open_renewals_sql($from, $to)
{
    list($from, $to) = company_invoice_period($from, $to);
    $pending = company_pending_renewals_sql($from, $to);
    return "(($pending) OR (schet.del='0' AND schet.shetold<>'' AND schet.akt<>'1' " .
        "AND schet.cher='0' AND schet.otk='0' AND CONCAT(schet.y,'-',schet.m,'-',schet.d) BETWEEN '$from' AND '$to'))";
}

function company_refusal_statistics_sql($organization, $type, $from, $to)
{
    list($from, $to) = company_invoice_period($from, $to);
    $org = (int)$organization;
    $organizationSql = $org ? " AND produkti.parent=$org" : '';
    $old = (string)$type === '2' ? "<>''" : "=''";
    // price/priceks содержат итог счёта, повторённый в строках его тарифов.
    return "SELECT prichotk.id, prichotk.value, prichotk.col, COUNT(invoices.rand) AS coun, " .
        "COALESCE(SUM(invoices.price),0) AS kolsum, COALESCE(SUM(invoices.priceks),0) AS kolsumka " .
        "FROM prichotk LEFT JOIN (SELECT schet.rand, MAX(schet.prichotk) AS reason, " .
        "MAX(CAST(NULLIF(schet.price,'') AS DECIMAL(18,2))) AS price, " .
        "MAX(CAST(NULLIF(schet.priceks,'') AS DECIMAL(18,2))) AS priceks " .
        "FROM schet JOIN produkti ON produkti.id=schet.produkt WHERE schet.del='0' AND schet.cher='1' " .
        "AND schet.prichotk<>'0' AND schet.shetold$old AND " .
        "CONCAT(schet.y,'-',schet.m,'-',schet.d) BETWEEN '$from' AND '$to'$organizationSql " .
        "GROUP BY schet.rand) invoices ON invoices.reason=prichotk.id " .
        "GROUP BY prichotk.id, prichotk.value, prichotk.col ORDER BY prichotk.id";
}
