<link href="css/toolkit.css" rel="stylesheet">
<div class="by amt"style="
    width: 100%;
    padding-left: 0px;
">
<div id="stat" class="statistic">Я в компании</div>
<div id="tablschet"class="statistic">Таблица счетов</div>
<div id="procschet"class="statistic">Процесс  в счетах</div>
</div>
<div id="content">
</div>

<script>  

        $(document).ready(function(){  
            $.ajax({  
                    url: "statistics.php",
                    cache: false, 
                    data: "id=<?echo $userdata['users_id'];?>",					
                    success: function(html){  
                        $("#content").html(html); 
	        document.getElementById('stat').style.backgroundColor="#6FCF97";
			document.getElementById('stat').style.color="white";
                    }  
                });
            $('#stat').click(function(){  
                $.ajax({  
                    url: "statistics.php",  
                    cache: false,  
					data: "id=<?echo $userdata['users_id'];?>",	
                    success: function(html){  
                        $("#content").html(html); 
            document.getElementById('procschet').style.backgroundColor="white";
			document.getElementById('procschet').style.color="#d3d3d3";
			document.getElementById('stat').style.backgroundColor="#6FCF97";
			document.getElementById('stat').style.color="white";
			document.getElementById('tablschet').style.backgroundColor="white";
			document.getElementById('tablschet').style.color="#d3d3d3";
                    }  
                });  
            });  
              
            $('#tablschet').click(function(){  
                $.ajax({  
                    url: "tablschet.php",  
                    cache: false,  
					data: "id=<?echo $userdata['users_id'];?>",	
                    success: function(html){  
                        $("#content").html(html);  
			document.getElementById('procschet').style.backgroundColor="white";
			document.getElementById('procschet').style.color="#d3d3d3";
			document.getElementById('stat').style.backgroundColor="white";
			document.getElementById('stat').style.color="#d3d3d3";
			document.getElementById('tablschet').style.backgroundColor="#6FCF97";
			document.getElementById('tablschet').style.color="white";
                    }  
                });  
            }); 
            $('#procschet').click(function(){  
                $.ajax({  
                    url: "procinschet.php",  
                    cache: false,  
					data: "id=<?echo $userdata['users_id'];?>",	
                    success: function(html){  
                        $("#content").html(html); 
            document.getElementById('procschet').style.backgroundColor="#6FCF97";
			document.getElementById('procschet').style.color="white";
			document.getElementById('stat').style.backgroundColor="white";
			document.getElementById('stat').style.color="#d3d3d3";
			document.getElementById('tablschet').style.backgroundColor="white";
			document.getElementById('tablschet').style.color="#d3d3d3";
                    }  
                });  
            });		
              
        });  
    </script>
