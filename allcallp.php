<?php
# Подключаем конфиг
include 'conf.php';

?>
<form class="allcalltable">
    <div style="height: 700px;overflow-y: scroll;">
    <table class="table" style="font-size: 12pt">
        <tbody>
        <?php
        if($_GET["type"]=="schet")
        {
            $all="where telefonia.idkli='".$_GET["idkli"]."'";
        }
        else
        {
            $all="";
        }
        $nom=0;
        $r = mysql_query("SELECT *,vidcall.name_call,users.f_name,users.l_name,users.o_name,ogrn.naim from telefonia left join vidcall on telefonia.vid_call=vidcall.id left join users on telefonia.idkto=users.users_id left join ogrn on ogrn.id=telefonia.idkli  $all order by telefonia.id desc Limit 50");
        while($res = mysql_fetch_assoc($r))  :
            $nom++;?>

            <tr>
                <td><?echo $nom;?></td>
                <td><?echo $res["name_call"];?></td>
                <td><?echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?></td>
                <td style="width: 60px;"><?echo $res['kto'];?></td>
                <td><?echo $res['towhom'];?></td>
                <td><audio controls><source src="/voicecatalog/<?echo $res["callmessage"];?>"></audio></td>
                <td><?echo $res['date_answer'];?></td>
                <td><?if($res['naim']!=""){echo $res['naim'];}else{?><input id="searchinn<?echo $res["id"];?>" placeholder="Введите инн или название организации"/><div id="serchoginn<?echo $res["id"];?>"style="position: absolute;background: white;box-shadow: 0 6px 12px rgb(0 0 0 / 18%);"></div><?}?></td>
                <td id="nsall"><?if($res['ns']!=""){echo $res['ns'];}else{?><input id="searchns<?echo $res["id"];?>" placeholder="Введите номер счета"/><div id="serchogns<?echo $res["id"];?>"style="position: absolute;background: white;box-shadow: 0 6px 12px rgb(0 0 0 / 18%);"></div><?}?></td>
                <script>
                    $(document).ready(function(){
                        $("#searchns<?echo $res["id"];?>").on("input", function (){
                            var searchs = $("#searchns<?echo $res["id"];?>").val();
                            $("#serchogns<?echo $res["id"];?>").empty();
                            $.ajax({
                                type: "GET",
                                url: "./headserch.php",
                                data: "naim=&ns=" + searchs+"&idkli=<?echo $_GET['idkli'];?>&id=<?echo $res["id"];?>",
                                success: function(msg){
                                    var s = document.getElementById("serchogns<?echo $res["id"];?>");
                                    s.innerHTML = msg;
                                    $('#schet<?echo $res["id"];?>').click(function () {
                                        let stat = "zviazns";
                                        let ns=document.getElementById("schet<?echo $res["id"];?>").value;
                                        $.ajax({
                                            type: "GET",
                                            url: "iptel.php",
                                            data: "stat=" + stat + "&idkli=<?echo $_GET["idkli"];?>&ns="+ns+"&idchannel=<?echo $res["id"];?>",
                                            success: function (msg) {
                                                $.ajax({
                                                    url: "allcall.php",
                                                    cache: false,
                                                    data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["idkli"];?>",
                                                    success: function (html) {
                                                        $("#content").html(html);
                                                        document.getElementById("allt").style.color = 'grey';
                                                        document.getElementById("alltp").style.color = '#d3d3d3';
                                                        document.getElementById("allnp").style.color = '#d3d3d3';
                                                    }
                                                })
                                            }
                                        });
                                    });
                                }
                            });
                            var c = document.getElementById("serchogns<?echo $res["id"];?>");
                            var t = document.createTextNode("");
                            c.appendChild(t);
                            document.getElementById("serchogns<?echo $res["id"];?>").className = "contaidivserchns";
                            $("#serchogns<?echo $res["id"];?>").empty();
                            document.getElementById("serchogns<?echo $res["id"];?>").className = "";
                        });
                        $("#searchinn<?echo $res["id"];?>").on("input", function (){
                            var searchs = $("#searchinn<?echo $res["id"];?>").val();
                            $("#serchoginn<?echo $res["id"];?>").empty();
                            $.ajax({
                                type: "GET",
                                url: "./headserch.php",
                                data: "naim=&nsinn=" + searchs+"&idkli=<?echo $_GET['idkli'];?>&id=<?echo $res["id"];?>",
                                success: function(msg){
                                    var s = document.getElementById("serchoginn<?echo $res["id"];?>");
                                    s.innerHTML = msg;
                                    $('#schet<?echo $res["id"];?>').click(function () {
                                        let stat = "zviaznsinn";
                                        let ns=document.getElementById("schet<?echo $res["id"];?>").value;
                                        $.ajax({
                                            type: "GET",
                                            url: "iptel.php",
                                            data: "stat=" + stat + "&idkli=<?echo $_GET["idkli"];?>&ns="+ns+"&idchannel=<?echo $res["id"];?>",
                                            success: function (msg) {
                                                $.ajax({
                                                    url: "allcall.php",
                                                    cache: false,
                                                    data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["idkli"];?>",
                                                    success: function (html) {
                                                        $("#content").html(html);
                                                        document.getElementById("allt").style.color = 'grey';
                                                        document.getElementById("alltp").style.color = '#d3d3d3';
                                                        document.getElementById("allnp").style.color = '#d3d3d3';
                                                    }
                                                })
                                            }
                                        });
                                    });
                                }
                            });
                            var c = document.getElementById("serchoginn<?echo $res["id"];?>");
                            var t = document.createTextNode("");
                            c.appendChild(t);
                            document.getElementById("serchoginn<?echo $res["id"];?>").className = "contaidivserchns";
                            $("#serchoginn<?echo $res["id"];?>").empty();
                            document.getElementById("serchoginn<?echo $res["id"];?>").className = "";
                        });
                    });


                </script>
            </tr>
        <?endwhile;?>
        </tbody>
    </table>
    </div>
</form>