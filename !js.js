// ------------  Изменение настроийки name="" если используется другой <select> ------------ //
$(document).ready(function () {
    $('#otherFiel').change(function(){
        if($(this).find("option:selected").val() == "inn"){
			document.getElementById("texts").name="inn";
		}if($(this).find("option:selected").val() == "name"){
			document.getElementById("texts").name="name";
		}
    });
});
// ----------------------------------------  Конец ---------------------------------------- //