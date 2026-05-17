<?php
# Подключаем конфиг
include 'conf.php';

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id']))
    {
        setcookie('id', '', time() - 60*24*30*12, '/');
        setcookie('hash', '', time() - 60*24*30*12, '/');
        setcookie('errors', '1', time() + 60*24*30*12, '/');
        header('Location: index.php'); exit();
    }
}
else
{
    setcookie('errors', '2', time() + 60*24*30*12, '/');
    header('Location: index.php'); exit();
}
$ogrn=$_COOKIE['ogrnl'];
$users=$_COOKIE['usersi'];
$usersdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".$users."' LIMIT 1"));
if($ogrn!="0")
{
    $udosrpod = "SELECT uslugi.id,uslugi.name,users_access.users from uslugi left join users_access on uslugi.id=users_access.uslugi where users_access.users='".$userdata['users_id']."' and uslugi.id='".$ogrn."'";
    $udosresultrpod = mysql_query($udosrpod);
    $udospersonrpod = mysql_fetch_array($udosresultrpod);
    $idogrn=$udospersonrpod['id'];
    $nameogrn=$udospersonrpod['name'];
}
else
{
    $idogrn="0";
    $nameogrn="Все организации";
}
?>

<?php
session_start();
echo $_SESSION["er"];?>
<div class="row">
<div class="by amt" style="float: left;
 width: 40%;
">
    <h3 class="text-secondary display-2">Инфо карточка<?echo $_GET['to'];?></h3>
    <div class="col-xs-4" style="float: left;    width: 70%;">
    <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
            <label style="padding-top: 6px;"><img src="/img/icons8name.png"></label>
            </div>
            <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
                <label class="form-control">Личные данные</label>
            </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
            <label class="" style="font-size: 15pt;font-weight: normal;padding-top: 8px;">ФИО</label>
        </div>
        <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
            <label class="form-control"><?echo $userdata['f_name'].' '.$userdata['l_name'].' '.$userdata['o_name'].'';?></label>
       </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
            <label class="" style="font-size: 15pt;font-weight: normal;padding-top:8px;">Адрес</label>
            </div>
            <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
                <label class="form-control"><??></label>
            </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
            <label class="" style="font-size: 15pt;font-weight: normal;padding-top: 8px;">ИНН ФЛ</label>
            </div>
            <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
                <label class="form-control"><??></label>
            </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
            <label class="" style="font-size: 15pt;font-weight: normal;padding-top: 8px;">Снилс</label>
            </div>
            <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
                <label class="form-control"><??></label>
            </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
            <label class="" style="font-size: 15pt;font-weight: normal;padding-top: 8px;">Личный</label>
            </div>
            <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
                <label class="form-control"><?echo $userdata['tel'];?></label>
            </div>
    </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
                <label class="" style="font-size: 15pt;font-weight: normal;padding-top: 8px;">ДР</label>
            </div>
            <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
                <label class="form-control"><?echo $userdata['dr'];?></label>
            </div>
        </div>
    <div class="row"style="margin-bottom: 10px;">
            <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
            <label class="" style="font-size: 15pt;font-weight: normal;padding-top: 8px;">Еmail</label>
            </div>
            <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
                <label class="form-control"><?echo $userdata['mail'];?></label>
            </div>
    </div>
    <div class="row"style="margin-bottom: 10px;">
            <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
            <label class="" style="font-size: 15pt;font-weight: normal;padding-top: 8px;">Приняли</label>
            </div>
            <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
                <label class="form-control"><?echo $userdata['prinyat'];?></label>
            </div>
        </div>
        <div class="row"style="margin-bottom: 10px;">
            <div class="col-xs-2"style="text-align: right;padding-top: 5px;">
                <label class="" style="font-size: 15pt;font-weight: normal;padding-top: 8px;">Внутрений телефон</label>
            </div>
            <div class="col-xs-10"style="text-align: left;padding-top: 5px;float: left;">
                <input class="form-control" id="iptel" style="margin-left: 20px;width: 70%;float: left;" value="<?echo $userdata['iptel'];?>"/>
                <label style="float: left;padding: 10px;border: 1px solid #d3d3d3;margin-left: 10px;font-size: 10pt;" onclick="ipt();">Привязать</label>
            </div>
        </div>
    </div>
    <div class="col-xs-2" style="float: left;    width: 30%;">
        <div class="row" style="border: 1px solid #d3d3d3;width: 100%;height: 155px;float: left;padding-top: 0px;margin-top: 5px;border-radius: 5px;margin-bottom: 7px;">
            <div style="position: relative;margin: 0 auto;width: 90%;">
                <div class="aoh" style="background: url(img/<?php echo $userdata['img']; ?>); width: 100px;height: 100px;background-size: cover;margin: 0 auto;margin-top: 20px;"></div>
            </div>
        </div>
        <div class="row" style="border: 1px solid #d3d3d3;width: 100%;height: 152px;float: left;padding-top: 0px;margin-top: 5px;border-radius: 5px;margin-bottom: 7px;">
            <div style="position: relative;margin: 0 auto;width: 70%;padding-top: 15px;">
            <div class="row">
                <label class="" style="font-size: 13pt;font-weight: normal;padding-top: 8px;margin-bottom: 0px;">Рэйтинг</label>
                <label class="" style="font-size: 13pt;font-weight: normal;padding-top: 8px;margin-bottom: 0px;float: right;"><?echo $userdata['raiting'];?></label>
            </div>
            <div class="row">
                <label class="" style="font-size: 13pt;font-weight: normal;padding-top: 8px;margin-bottom: 0px;">Место</label>
                <label class="" style="font-size: 13pt;font-weight: normal;padding-top: 8px;margin-bottom: 0px;float: right;">1</label>
            </div>
            <div class="row">
                <label class="" style="font-size: 13pt;font-weight: normal;padding-top: 8px;margin-bottom: 0px;">Замечания</label>
                <label class="" style="font-size: 13pt;font-weight: normal;padding-top: 8px;margin-bottom: 0px;float: right;">1</label>
            </div>
            <div class="row">
                <label class="" style="font-size: 13pt;font-weight: normal;padding-top: 8px;margin-bottom: 0px;">Поощрения</label>
                <label class="" style="font-size: 13pt;font-weight: normal;padding-top: 8px;margin-bottom: 0px;float: right;">1</label>
            </div>
        </div>
        </div>
        <div class="row" style="border: 1px solid #d3d3d3;width: 100%;height: 152px;float: left;padding-top: 0px;margin-top: 5px;border-radius: 5px;margin-bottom: 7px;">
            <ul class="eb tb">
                <!-- <li style=" font-size: 12pt;"><?php if ($userdata['adm']==0){ }?><?php if ($userdata['adm']==1){?><div class="reg"><a style="color: #78AFD8;" href="/dob_org.php">Изменить права пользователя</a></div><?php }?>
            <li><a class="pass">Сменить пароль</a></li>!-->
                <li style=" font-size: 12pt;"><?php if ($userdata['adm']==0){ }?><?php if ($userdata['adm']==1){?><div class="reg"><a style="color: #78AFD8;" href="/licmex.php">Лицензии мех опт </a></div><?php }?>
                <li style=" font-size: 12pt;"><?php if ($userdata['adm']==0){ }?><?php if ($userdata['adm']==1 ){?><div class="reg"><a style="color: #78AFD8;" href="/srock.php">Срок действия</a></div><?php }?>
                <li style=" font-size: 12pt;"><?php if ($userdata['adm']==0){ }?><?php if ($userdata['adm']==1 ){?><div class="reg"><a style="color: #78AFD8;" href="/klimex.php">Выгрузка (по меху и тд)</a></div><?php }?>
                <li style=" font-size: 12pt;"><?php if ($userdata['adm']==0){ }?><?php if ($userdata['adm']==1 ){?><div class="reg"><a style="color: #78AFD8;" href="/test.php?date=<?$y=date("y");$m=date("m");$dat=$y.$m;echo $dat;?>">Тест(новый отчет по конт.</a></div><?php }?>
                <li style=" font-size: 12pt;"><?php if ($userdata['adm']==0){ }?><?php if ($userdata['adm']==1 ){?><div class="reg"><a style="color: #78AFD8;" href="/ot_leter.php">Тест письмо</a></div><?php }?>
                <li style=" font-size: 12pt;"><?php if ($userdata['adm']==0){ }?><?php if ($userdata['adm']==1 ){?><div class="reg"><a style="color: #78AFD8;" href="/iptel.php">Тест atc</a></div><?php }?>
                <li style=" font-size: 12pt;"><?php if ($userdata['adm']==0){ }?><?php if ($userdata['adm']==1){?><div class="reg"><a style="color: #78AFD8;" href="/register.php">Регистрация  нового пользователя</a></div><?php }?>
            </ul>
        </div>
    </div>
   <!-- <div class="col-xs-12" style="float: left;width: 55%;margin-top: 5px;height: 482px;border: 1px solid #d3d3d3;border-radius: 5px;margin-left: 15px;">
    </div>!-->
</div>
<div class="by amt" style="float: left;width: 60%;">
    <h3 class="text-secondary display-2">Отпуска и посещения</h3>
    <div>
        <?php if ($userdata['adm']==1){?>
        <div class="col-xs-2" style="">
            <div class="row" style="width: 100%;">
                <label class="response">Переод:</label>
                <div class="row"><span class="response_time"> С </span>
                <div class='statdate' style="width: 100%;float: left;">
                    <input class='form-control firstDatei' id="datestarti" type="date" value="">
                </div><span class="response_time"> По </span>
                <div class='statdate' style="width: 100%;float: left;">
                    <input class='form-control secondDatei' id="datefinishi" type="date" value="">
                </div>
                </div>
            </div>
        <div class="row" style="margin-top: 10px;">
            <label class="response" style="  float: left;padding-top: 7px;">Тип</label>
            <select class="form-control" style="width: 100%;" id="selebration">
            <?$rep=mysql_query("SELECT * from reasons ");
            while($resep = mysql_fetch_assoc($rep)) :?>
                <option value ="<?echo $resep['id'];?>"><?echo $resep['reasons_value'];?></option>
            <?php endwhile; ?>
            </select>
            </div>
        </div>
        <?}?>
<div id="daysgone">

</div>
    </div>
</div>
</div>
<div class="row" style="background: #d3d3d3;height: 5px;" ></div>
    <div class="row">
        <h3 class="text-secondary display-2">Личная мотивация по продлению </h3>
        <div class="col-xs-2">
            <select class="form-control" style="width: 100%;" id="ogrn">
                <option value =<?echo $idogrn;?> selected><? echo $nameogrn;?></option>
                <option value="0">Все организации</option>
                <?$repu=mysql_query("SELECT uslugi.id,uslugi.name,users_access.users from uslugi left join users_access on uslugi.id=users_access.uslugi where users_access.users='".$userdata['users_id']."' and uslugi.del!='1'");
                while($resepu = mysql_fetch_assoc($repu)) :?>
                    <option value ="<?echo $resepu['id'];?>"><?echo $resepu['name'];?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-xs-2">

                <?
$_monthsListi = array(
"01"=>"Январь","02"=>"Февраль","03"=>"Март",
"04"=>"Апрель","05"=>"Май", "06"=>"Июнь",
"07"=>"Июль","08"=>"Август","09"=>"Сентябрь",
"10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь");
?>
            <select class="form-control" style="width: 100%;" id="monf">
                    <option selected value="<?echo $mon=date('m');?>"><?$mon=date('m');echo $_monthsListi[$mon];?></option>
                    <?for($i = 1;$i<=count($_monthsListi);$i++){
                        if($i<10)
                        {
                            $month="0".$i;
                        }
                        else
                        {
                            $month=$i;
                        }
                        ?>
                        <option value="<?echo $month;?>"><?echo $_monthsListi[$month];?></option>
                    <?}?>
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" style="width: 100%;" id="use">
                <option selected><?echo $usersdata['f_name'].' '.mb_substr($usersdata['l_name'],0,1,'UTF-8'),'. '.mb_substr($usersdata['o_name'],0,1,'UTF-8').'.';?></option>
                <?$repus=mysql_query("select * FROM users");
                while($resepus = mysql_fetch_assoc($repus)) :?>
                    <option value ="<?echo $resepus['users_id'];?>"><?echo $resepus['f_name'].' '.mb_substr($resepus['l_name'],0,1,'UTF-8'),'. '.mb_substr($resepus['o_name'],0,1,'UTF-8').'.';?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div id="lkmotiv">

        </div>
    </div>
<script>
    <?php if ($userdata['adm']==1){?>
    var date = new Date();
    y = date.getFullYear();
    m = date.getMonth();
    var firstDay = new Date(y, m, 2);
    var lastDay = new Date(y, m + 1, 1);
    document.getElementById('datestarti').valueAsDate = firstDay;
    document.getElementById('datefinishi').valueAsDate = lastDay;
    datastart=document.getElementsByClassName("firstDatei")[0].value;
    datafinish=document.getElementsByClassName("secondDatei")[0].value;
    selebration=document.getElementById('selebration').value;
    <?}?>
    document.getElementById('lkmotiv').innerHTML = "";
    ogrn=document.getElementById('ogrn').value;
    monf=document.getElementById('monf').value;
    $.ajax({
        type: "GET",
        url: "lkmotiv.php",
        data: "users=11&ogrnl="+ogrn+"&mon="+monf+"",
        success: function(html){
            $("#lkmotiv").html(html);
        }
    });
  $.ajax({
        url: "daysgone.php",
        cache: false,
        data: "us=<?echo $userdata['users_id'];?>",
        success: function(html){
            $("#daysgone").html(html);
        }
    });
    $( "#ogrn" ).change(function () {
        document.getElementById('lkmotiv').innerHTML = "";
        ogrn=document.getElementById('ogrn').value;
        monf=document.getElementById('monf').value;
        $.ajax({
            type: "GET",
            url: "lkmotiv.php",
            data: "users=11&ogrnl="+ogrn+"&mon="+monf+"",
            success: function(html){
                $("#lkmotiv").html(html);
            }
        });
    });
    $( "#monf" ).change(function () {
        document.getElementById('lkmotiv').innerHTML = "";
        ogrn=document.getElementById('ogrn').value;
        monf=document.getElementById('monf').value;
        $.ajax({
            type: "GET",
            url: "lkmotiv.php",
            data: "users=11&ogrnl="+ogrn+"&mon="+monf+"",
            success: function(html){
                $("#lkmotiv").html(html);
            }
        });
    });
    $( "#use" ).change(function () {
        document.getElementById('lkmotiv').innerHTML = "";
        ogrn=document.getElementById('ogrn').value;
        monf=document.getElementById('monf').value;
        use=document.getElementById('use').value;
        $.ajax({
            type: "GET",
            url: "lkmotiv.php",
            data: "users="+use+"&ogrnl="+ogrn+"&mon="+monf+"",
            success: function(html){
                $("#lkmotiv").html(html);
            }
        });
    });
    function  ipt(){
        let tel=document.getElementById("iptel").value;
        $.ajax({
            type: "GET",
            url: "iptel.php",
            data: "users="+ <? echo $userdata['users_id'];?>+"&telip="+ tel+"",
            success: function(html){
                $("#iptel").html(html);
            }
        });
    }
</script>
