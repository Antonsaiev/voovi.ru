

/*=======получение значение дял поля тариф=======*/
function f(el) {
    var n=el.id; 
	document.getElementById('login').value=n;
}
/*=============================*/
$(function(){
    $('.lab').click(function () {
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
});

/*=============================*/
$(document).ready(function(){
 $('.spoiler_links').click(function(){
  $(this).next('.spoiler_body').toggle('normal');
  return false;
 });
});

window.onload = function () {
    var elements = document.getElementsByClassName('test');
    for (i = 0; i < elements.length; i++) {
        elements[i].style.display = 'block';
    }
}
/*==============================*/





