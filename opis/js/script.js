
/*=======получение значение для действия=======*/
function f(el) {
    var n=el.id;
	var name;
    var up_names = document.getElementById("numbers");
    var text = $("#numbers").val();

	if(n=="ar")
	{

	}
	if(n=="plus")
	{
	name="Добавить";
	}
	if(n=="excel")
	{
	name="Документ EXCEL";
	}
	if(n=="edit")
	{

	}
    if(n=="add")
    {
        $(document).ready(function(){
            $.ajax({
                url: '/opis/dob.php',
                type: "POST",
                data:
                    {
                        id_mov:'1'
                    },
                success: function(response){

                    $(document).ready(function()
                    {
                        $('#best').load( "/opis/conf2.php"); // 1000 это 1 секунда

                    })

                }
            });
        })
    }
	if(n=="copy")
	{
	name="Копировать";
	}
	if(n=="trash")
	{
	name="Удалить";
	}
}
function e(el) {
	var  id=el.id;
	var edit='1';
	var number='number'+id;
    var vid_o='vid'+id;
    var man='pol'+id;
    var arti='art'+id;
    var size='razm'+id;
    var counti='count'+id;
    var brendi='brend'+id;
    var mat_verhi='mat_verh'+id;
    var mat_podi='mat_pod'+id;
    var mat_nizi='mat_niz'+id;
    var tnvedi='tnved'+id;
    var contryi='contry'+id;
    var colori='color'+id;
    var  num=document.getElementById(number).value;
    var  vid=document.getElementById(vid_o).value;
    var  pol=document.getElementById(man).value;
    var  art=document.getElementById(arti).value;
    var  razm=document.getElementById(size).value;
    var  count=document.getElementById(counti).value;
    var  brend=document.getElementById(brendi).value;
    var  mat_verh=document.getElementById(mat_verhi).value;
    var  mat_pod=document.getElementById(mat_podi).value;
    var  mat_niz=document.getElementById(mat_nizi).value;
    var  tnved=document.getElementById(tnvedi).value;
    var  contry=document.getElementById(contryi).value;
    var  color=document.getElementById(colori).value;
     $(document).ready(function(){
         $.ajax({
             url: '/opis/dob.php',
             type: "POST",

             data:
                 {
                     id_edit:edit,
                     id_t:id,
                     id_vid:vid,
                     id_num:num,
                     id_pol:pol,
                     id_art:art,
                     id_razm:razm,
                     id_count:count,
                     id_brend:brend,
                     id_mat_verh:mat_verh,
                     id_mat_pod:mat_pod,
                     id_mat_niz:mat_niz,
                     id_tnved:tnved,
                     id_contry:contry,
                     id_color:color
                 },
             cache: false,
             success: function(response){
                 $(document).ready(function()
                 {

                     $('#best').load( "/opis/conf2.php"); // 1000 это 1 секунда

                 })
             }
         });
     })

}
function tr(el) {
    var  id=el.id;
    var del='1';
    $(document).ready(function(){
        $.ajax({
            url: '/opis/dob.php',
            type: "POST",
            data:
                {
                    id_t:id,
                    id_del:del

                },
            success: function(response){

                $(document).ready(function()
                {
                    $('#best').load( "/opis/conf2.php"); // 1000 это 1 секунда

                })

            }
        });
    })
}
function co(el) {
    var  id=el.id;
    var cop='1';
    $(document).ready(function(){
        $.ajax({
            url: '/opis/dob.php',
            type: "POST",
            data:
                {
                    id_t:id,
                    id_cop:cop
                },
            success: function(response){

                $(document).ready(function()
                {
                    $('#best').load( "/opis/conf2.php"); // 1000 это 1 секунда

                })

            }
        });
    })
}
function save(el)
{
	
}
function ar(el) {
    var n=el.id;
    var  arr=document.getElementById(n).innerHTML;
	
    if (arr=='Архив')
    {
        var del='1';
		var ar='1';
        $(document).ready(function(){
            $.ajax({
                url: '/opis/dob.php',
                type: "POST",
                data:
                    {
                        id_del:del,
						id_ar:ar
                    },
                success: function(response){

                    $(document).ready(function()
                    {
                        $('#best').load( "/opis/conf2.php"); // 1000 это 1 секунда

                    })

                }
            });
        })
        document.getElementById(n).innerHTML='Назад';
    }
    if (arr=='Назад')
    {
        var del='0';
		var ar='0';
        $(document).ready(function(){
            $.ajax({
                url: '/opis/dob.php',
                type: "POST",
                data:
                    {
                        id_del:del,
						id_ar:ar
                    },
                success: function(response){

                    $(document).ready(function()
                    {
                        $('#best').load( "/opis/conf2.php"); // 1000 это 1 секунда

                    })

                }
            });
        })
        document.getElementById(n).innerHTML='Архив';
    }
   /* .value;*/
}
$(document).ready(function() {

   $('input[type="file"]').change(function(){
        var value = $("input[type='file']")[0].files[0];
        var excel='1';
		var form_data = new FormData();
        form_data.append('file', value);
		alert(form_data);
        $(document).ready(function(){
            $.ajax({
                url: '/opis/dob.php',
                 dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(php_script_response){
                    alert(php_script_response);
                        $('#best').load( "/opis/conf2.php"); // 1000 это 1 секунда
                }

            });
        })
    });

});
var count=1;
function din(el) {
var picHolder = document.getElementById("dobinp");
var input = document.createElement("input");
input.id = 'qr'+count;
picHolder.appendChild(input);
document.getElementById("qr"+count).focus();
var idi="qr"+count;
count++;
$('#'+idi+'').change(function(){
        var idkm=$(this).val();
		var dobkm='1';
            $.ajax({
                url: '/opis/dob.php',
                type: "POST",
                data:
                    {
					
                        id_idkm:idkm,
						id_dobkm:dobkm
                    },
                success: function(response){

                    $(document).ready(function()
                    {
                        $('#best').load( "/opis/conf2.php"); // 1000 это 1 секунда

                    })

                }
            });
		
    });
}
/*
	$(function(){
    $('.fa-plus').click(function () {
      $('.modal-shadow').show();
    $('.modal-window').show();
    });
 
    $('.modal-shadow').click(function () {
      $('.modal-shadow').hide();
    $('.modal-window').hide();
    });
 
    $('.close').click(function () {
      $('.modal-shadow').hide();
    $('.modal-window').hide();
    });
    });*/
/*=============================*/





