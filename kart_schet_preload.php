<?php
if (!function_exists('kart_schet_sql_in')) {
    function kart_schet_sql_in($values) {
        $result = array();
        foreach ($values as $value) {
            $value = trim((string)$value);
            if ($value === '') {
                continue;
            }
            $result[$value] = "'".mysql_real_escape_string($value)."'";
        }
        return implode(',', $result);
    }
}

if (!function_exists('kart_schet_preload')) {
    function kart_schet_preload($rows, $userdata) {
        global $kartSchetProductMap, $kartSchetUserMap, $kartSchetAgentMap;
        global $kartSchetCommentCounts, $kartSchetLatestComments, $kartSchetLatestStatuses, $kartSchetStatusMap;
        global $kartSchetTurboSum, $kartSchetGenSum, $kartSchetKvobopSum, $kartSchetFavoriteMap, $kartSchetDocumentDataMap;
        global $kartSchetPrichotkMap, $kartSchetExecutors, $kartSchetUserAccess;

        $kartSchetProductMap = array();
        $kartSchetUserMap = array();
        $kartSchetAgentMap = array();
        $kartSchetCommentCounts = array();
        $kartSchetLatestComments = array();
        $kartSchetLatestStatuses = array();
        $kartSchetStatusMap = array();
        $kartSchetTurboSum = array();
        $kartSchetGenSum = array();
        $kartSchetKvobopSum = array();
        $kartSchetFavoriteMap = array();
        $kartSchetDocumentDataMap = array();
        $kartSchetPrichotkMap = array();
        $kartSchetExecutors = array();
        $kartSchetUserAccess = array();

        $rands = array();
        $productIds = array();
        $userIds = array();
        $agentIds = array();
        $prichotkIds = array();

        foreach ($rows as $row) {
            if (!empty($row['rand'])) {
                $rands[$row['rand']] = $row['rand'];
            }
            if (!empty($row['produkt'])) {
                $productIds[$row['produkt']] = $row['produkt'];
            }
            if (!empty($row['kto'])) {
                $userIds[$row['kto']] = $row['kto'];
            }
            if (!empty($row['generac'])) {
                $userIds[$row['generac']] = $row['generac'];
            }
            if (!empty($row['agent'])) {
                $agentIds[$row['agent']] = $row['agent'];
            }
            if (!empty($row['prichotk'])) {
                $prichotkIds[$row['prichotk']] = $row['prichotk'];
            }
        }

        $productIn = kart_schet_sql_in($productIds);
        if ($productIn !== '') {
            $query = mysql_query("SELECT * FROM produkti WHERE id IN (".$productIn.")");
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetProductMap[$item['id']] = $item;
            }
        }

        $accessQuery = mysql_query("SELECT uslugi FROM users_access WHERE users = '".(int)$userdata['users_id']."'");
        while ($item = mysql_fetch_assoc($accessQuery)) {
            $kartSchetUserAccess[$item['uslugi']] = true;
        }

        $randIn = kart_schet_sql_in($rands);
        if ($randIn !== '') {
            $query = mysql_query("SELECT rand, SUM(CASE WHEN turbo = '1' THEN turbo ELSE 0 END) AS turbo_sum, SUM(CASE WHEN gen = '1' THEN kvo ELSE 0 END) AS gen_sum FROM schet WHERE del = '0' AND rand IN (".$randIn.") GROUP BY rand");
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetTurboSum[$item['rand']] = (int)$item['turbo_sum'];
                $kartSchetGenSum[$item['rand']] = (int)$item['gen_sum'];
            }

            $query = mysql_query("SELECT schet, COUNT(*) AS comment_count, MAX(id) AS max_id FROM schetoldkomment WHERE schet IN (".$randIn.") GROUP BY schet");
            $commentIds = array();
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetCommentCounts[$item['schet']] = (int)$item['comment_count'];
                if (!empty($item['max_id'])) {
                    $commentIds[$item['max_id']] = $item['max_id'];
                }
            }
            $commentIn = kart_schet_sql_in($commentIds);
            if ($commentIn !== '') {
                $query = mysql_query("SELECT * FROM schetoldkomment WHERE id IN (".$commentIn.")");
                while ($item = mysql_fetch_assoc($query)) {
                    $kartSchetLatestComments[$item['schet']] = $item;
                    if (!empty($item['kto'])) {
                        $userIds[$item['kto']] = $item['kto'];
                    }
                }
            }

            $query = mysql_query("SELECT ss.* FROM schet_status ss INNER JOIN (SELECT schet, MAX(id) AS max_id FROM schet_status WHERE schet IN (".$randIn.") GROUP BY schet) last_status ON last_status.max_id = ss.id");
            $statusIds = array();
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetLatestStatuses[$item['schet']] = $item;
                if (!empty($item['status'])) {
                    $statusIds[$item['status']] = $item['status'];
                }
            }
            $statusIn = kart_schet_sql_in($statusIds);
            if ($statusIn !== '') {
                $query = mysql_query("SELECT * FROM status WHERE id IN (".$statusIn.")");
                while ($item = mysql_fetch_assoc($query)) {
                    $kartSchetStatusMap[$item['id']] = $item;
                }
            }

            $query = mysql_query("SELECT schet, SUM(summa) AS summa FROM kvobop WHERE schet IN (".$randIn.") GROUP BY schet");
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetKvobopSum[$item['schet']] = (float)$item['summa'];
            }

            $query = mysql_query("SELECT schet FROM schetizbran WHERE kto = '".(int)$userdata['users_id']."' AND schet IN (".$randIn.")");
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetFavoriteMap[$item['schet']] = true;
            }

            $query = mysql_query("SELECT * FROM document_data WHERE rand IN (".$randIn.")");
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetDocumentDataMap[$item['rand']] = $item;
            }
        }

        $userIn = kart_schet_sql_in($userIds);
        if ($userIn !== '') {
            $query = mysql_query("SELECT * FROM users WHERE users_id IN (".$userIn.")");
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetUserMap[$item['users_id']] = $item;
            }
        }

        $agentIn = kart_schet_sql_in($agentIds);
        if ($agentIn !== '') {
            $query = mysql_query("SELECT * FROM agent WHERE id IN (".$agentIn.")");
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetAgentMap[$item['id']] = $item;
            }
        }

        $prichotkIn = kart_schet_sql_in($prichotkIds);
        if ($prichotkIn !== '') {
            $query = mysql_query("SELECT * FROM prichotk WHERE id IN (".$prichotkIn.")");
            while ($item = mysql_fetch_assoc($query)) {
                $kartSchetPrichotkMap[$item['id']] = $item;
            }
        }

        $query = mysql_query("SELECT * FROM users WHERE del_users = 0 and show_executor = 1 ORDER BY users_id DESC");
        while ($item = mysql_fetch_assoc($query)) {
            $kartSchetExecutors[] = $item;
        }
    }
}
?>
