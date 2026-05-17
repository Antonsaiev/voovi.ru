<?
include 'conf.php';
?>
<div id="tablosns">
    <table style="position:relative;width: 25%;float: left; margin-left: 10px;">
        <thead>
        <tr style="height: 37.78px;">
            <th style="width: 70%;">Статус</th>
            <th style="">дней</th>
            <th style="">%</th>
        </tr>
        <tr>
            <td>Всего дней</td>
            <td><?echo $days = 365 + date("L");?></td>
            <td>100%</td>
        </tr>
        <tr>
            <td>В работе</td>
            <td>248</td>
            <td><?$prod=(248*100)/$days;echo round($prod, 1)."%";?></td>
        </tr>
        <?$reps=mysql_query("SELECT * from reasons ");
        while($reseps = mysql_fetch_assoc($reps)) :$allcol[]=array("'".$reseps['col']."',");?>
            <tr>
                <td><?echo $reseps['reasons_value'];?></td>
                <td><?$daysgone = mysql_fetch_assoc(mysql_query("SELECT *,TO_DAYS(max(date_finish))-TO_DAYS(min(date_start)) as diff from daysgone where 	prich_id='".$reseps['id']."' and users_id='".$_GET['us']."'")); if($daysgone['diff']==""){$days="0";echo $days;} else{$days= $daysgone['diff'];echo $days;}$alldaysgone[]=array($days.",")?></td>
                <td><?$proc=($daysgone['diff']*100)/248;echo round($proc, 1)."%";?></td>
            </tr>
        <?php endwhile; ?>
        </thead>
    </table>
       <div style="position: relative;
       float: left;
    height: 345px;
    width: 20%;overflow: hidden;">
                <label style="
    width: 82%;
    text-align: center;
    font-size: 12pt;
    position: relative;
    padding-top: 10px;
    padding-bottom: 10px;
    background-color: #f2f2f2;
    z-index: 999;
    border: 1px solid #d3d3d3;
    height: 39px;
">График</label>
                <canvas id="densityCharti" width="10" height="16.55555" style="position: relative;right: 32px;height: auto;bottom: 42px;"></canvas>
            </div>
    <div style="width: 30%;
    float: left;">
        <canvas id="myChart<?echo $_GET['us'];?>" width="30" height="30"style="position: relative;bottom: 30px;"></canvas>
    </div>
            <script>


                var chartData = {
                    labels: [<?echo $days = 365 + date("L");?>,248,<?
    for($i=0;$i<count($alldaysgone);$i++){
        $e=implode(",", $alldaysgone[$i]);print_r($e);}?>],
                    datasets: [
                        {
                            label:'',

                            backgroundColor: ['#6FCF97','#90BEA3',<?
    for($i=0;$i<count($allcol);$i++){
        $e=implode(",", $allcol[$i]);print_r($e);}?>],
                            data: [<?echo $days = 365 + date("L");?>,248,<?
    for($i=0;$i<count($alldaysgone);$i++){
        $e=implode(",", $alldaysgone[$i]);print_r($e);}?>]
                        }
                    ]
                };

                var opt = {
                    events: false,
                    tooltips: {
                        enabled: false
                    },
                    hover: {
                        animationDuration: 0,
                        enabled: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                min:999999
                            }
                        }]
                    },
                };
                var ctx = document.getElementById("densityCharti"),
                    myLineChart = new Chart(ctx, {
                        type: 'horizontalBar',
                        data: chartData,
                        options: opt
                    });
                var ctx = document.getElementById('myChart<?echo $_GET['us'];?>').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        datasets: [{
                            data: [<?echo $days = 365 + date("L");?>,248,<?
                                for($i=0;$i<count($alldaysgone);$i++){
                                    $e=implode(",", $alldaysgone[$i]);print_r($e);}?>],
                            backgroundColor: ['#6FCF97','#90BEA3',<?
                                for($i=0;$i<count($allcol);$i++){
                                    $e=implode(",", $allcol[$i]);print_r($e);}?>],
                            borderWidth: 0.5 ,
                            borderColor: '#ddd'
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            position: 'top',
                            fontSize: 16,
                            fontColor: '#111',
                            padding: 20
                        },
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                boxWidth: 20,
                                fontColor: '#111',
                                padding: 15
                            }
                        },
                        tooltips: {
                            enabled: false
                        },
                        plugins: {
                            datalabels: {
                                color: '#111',
                                textAlign: 'center',
                                font: {
                                    lineHeight: 1.6
                                },
                                formatter: function(value, ctx) {
                                    return ctx.chart.data.labels[ctx.dataIndex] + '\n' + value + '%';
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
        </div>
    </div>
</div>
