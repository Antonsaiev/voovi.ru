<div style="display: flex;background: white;z-index: 99999999;"  >
    <div id="allt" class="allcalltablezag">
        <label >Все звонки</label>
    </div>
    <div id="alltp" class="allcalltablezag">
        <label>Привязаные</label>
    </div>
    <div  id="allnp"class="allcalltablezag">
        <label>Не привязаные</label>
    </div>
</div>
<table class="table" style=";z-index: 99999999;">
    <thead>
    <tr>
        <th style="width: 30px;text-align: center;padding-bottom: 5px"">№</th>
        <th style="width: 125px;text-align: center;padding-bottom: 5px"">Вид звонка</th>
        <th style="width: 155px;text-align: center;padding-bottom: 5px"">Кто звонил</th>
        <th style="width: 60px;text-align: center;">С какого номера</th>
        <th style="width: 135px;text-align: center;padding-bottom: 5px"">Кому звонили</th>
        <th style="width: 335px;text-align: center;padding-bottom: 5px"">Звонок</th>
        <th style="width: 200px;text-align: center;padding-bottom: 5px"">Дата звонка</th>
        <th style="width: 300px;text-align: center;padding-bottom: 5px"">Организация</th>
        <th style="text-align: center;padding-bottom: 5px;">Номер счета</th>
    </tr>
    </thead>
</table>
<div id="content" style="position: relative;">

</div>
<script>
    $(document).ready(function() {
        $.ajax({
            url: "allcall.php",
            cache: false,
            data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["lico"];?>&type=schet",
            success: function (html) {
                $("#content").html(html);
                document.getElementById("allt").style.color = 'grey';
                document.getElementById("alltp").style.color = '#d3d3d3';
                document.getElementById("allnp").style.color = '#d3d3d3';
            }
        })
    });
    $('#allt').click(function(){
        $.ajax({
            url: "allcall.php",
            cache: false,
            data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["lico"];?>&type=schet",
            success: function(html){
                $("#content").html(html);
                document.getElementById("allt").style.color='grey';
                document.getElementById("alltp").style.color='#d3d3d3';
                document.getElementById("allnp").style.color='#d3d3d3';
            }
        });
    });

    $('#alltp').click(function(){
        $.ajax({
            url: "allcallp.php",
            cache: false,
            data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["lico"];?>&type=schet",
            success: function(html){
                $("#content").html(html);
                document.getElementById("allt").style.color='#d3d3d3';
                document.getElementById("alltp").style.color='grey';
                document.getElementById("allnp").style.color='#d3d3d3';
            }
        });
    });
    $('#allnp').click(function(){
        $.ajax({
            url: "allcallnp.php",
            cache: false,
            data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["lico"];?>&type=schet",
            success: function(html){
                $("#content").html(html);
                document.getElementById("allt").style.color='#d3d3d3';
                document.getElementById("alltp").style.color='#d3d3d3';
                document.getElementById("allnp").style.color='grey';
            }
        });
    });
</script>