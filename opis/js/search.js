      var map = {
          'q' : 'й', 'w' : 'ц', 'e' : 'у', 'r' : 'к', 't' : 'е', 'y' : 'н', 'u' : 'г', 'i' : 'ш', 'o' : 'щ', 'p' : 'з', '[' : 'х', ']' : 'ъ', 'a' : 'ф', 's' : 'ы', 'd' : 'в', 'f' : 'а', 'g' : 'п', 'h' : 'р', 'j' : 'о', 'k' : 'л', 'l' : 'д', ';' : 'ж', '\'' : 'э', 'z' : 'я', 'x' : 'ч', 'c' : 'с', 'v' : 'м', 'b' : 'и', 'n' : 'т', 'm' : 'ь', ',' : 'б', '.' : 'ю','Q' : 'Й', 'W' : 'Ц', 'E' : 'У', 'R' : 'К', 'T' : 'Е', 'Y' : 'Н', 'U' : 'Г', 'I' : 'Ш', 'O' : 'Щ', 'P' : 'З', '[' : 'Х', ']' : 'Ъ', 'A' : 'Ф', 'S' : 'Ы', 'D' : 'В', 'F' : 'А', 'G' : 'П', 'H' : 'Р', 'J' : 'О', 'K' : 'Л', 'L' : 'Д', ';' : 'Ж', '\'' : 'Э', 'Z' : '?', 'X' : 'ч', 'C' : 'С', 'V' : 'М', 'B' : 'И', 'N' : 'Т', 'M' : 'Ь', ',' : 'Б', '.' : 'Ю',

      };

      $("#searchs").on('keyup', function () {
          var str = $("#searchs").val();
          var r = '';
          for (var i = 0; i < str.length; i++) {
              r += map[str.charAt(i)] || str.charAt(i);
          }
          $("#searchs").val(r);
      });




      $(document).ready(function(){
									$("#searchs").on("input", function (){
										var searchs = $("#searchs").val();
										$("#serchog").empty();	
										$.ajax({
											type: "GET",
											url: "/opis/serch.php",
											data: "naim=&name=" + searchs,
											success: function(msg){
													var s = document.getElementById("serchog");
													s.innerHTML = msg;
											}
										});
										var c = document.getElementById("serchog");
										var t = document.createTextNode("");
										c.appendChild(t);
										document.getElementById("serchog").className = "contaidivserch";
									});
							
										$("#schetsubmit").click(function(){
											$("#serchog").empty();
											document.getElementById("serchog").className = "";
										}); 
										
									});
