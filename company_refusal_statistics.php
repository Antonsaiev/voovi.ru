<?php
$statistics = array();
$totalCount = 0; $totalPrice = 0; $totalKontur = 0;
$statisticsQuery = mysql_query(company_refusal_statistics_sql($_GET['ogr'], $_GET['tipi'], $dataproms, $datapromf));
if (!$statisticsQuery) { throw new RuntimeException('Не удалось загрузить статистику отказов'); }
while ($item = mysql_fetch_assoc($statisticsQuery)) {
    $statistics[] = $item;
    $totalCount += (int)$item['coun'];
    $totalPrice += (float)$item['kolsum'];
    $totalKontur += (float)$item['kolsumka'];
}
function company_refusal_percent($value, $total) { return $total > 0 ? round($value * 100 / $total, 1) : 0; }
?>
<div class="company-refusal-statistics" style="overflow-x:auto;margin-bottom:20px">
<table class="table table-bordered table-striped">
<thead><tr><th>Тип отказа</th><th>Счетов</th><th>Доля, %</th><th>С, руб.</th><th>Доля С, %</th><th>К, руб.</th><th>Доля К, %</th></tr></thead>
<tbody>
<?php foreach ($statistics as $item) { ?>
<tr>
<td><?php echo htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo (int)$item['coun']; ?></td>
<td><progress max="100" value="<?php echo company_refusal_percent($item['coun'], $totalCount); ?>" style="width:70px"></progress> <?php echo company_refusal_percent($item['coun'], $totalCount); ?></td>
<td><?php echo number_format((float)$item['kolsum'], 2, '.', ' '); ?></td>
<td><?php echo company_refusal_percent($item['kolsum'], $totalPrice); ?></td>
<td><?php echo number_format((float)$item['kolsumka'], 2, '.', ' '); ?></td>
<td><?php echo company_refusal_percent($item['kolsumka'], $totalKontur); ?></td>
</tr>
<?php } ?>
</tbody>
<tfoot><tr><th>Всего</th><th><?php echo $totalCount; ?></th><td><?php echo $totalCount ? 100 : 0; ?></td><th><?php echo number_format($totalPrice, 2, '.', ' '); ?></th><td><?php echo $totalPrice > 0 ? 100 : 0; ?></td><th><?php echo number_format($totalKontur, 2, '.', ' '); ?></th><td><?php echo $totalKontur > 0 ? 100 : 0; ?></td></tr></tfoot>
</table>
</div>
