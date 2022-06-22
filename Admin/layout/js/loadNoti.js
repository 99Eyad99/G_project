
 function loadNote() {
  
  setInterval(function(){

   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) { 
	     document.querySelector(".notes").innerHTML =  xhttp.responseText;
    }
   };
   xhttp.open("GET", 'ajax/loadNote.php', true);
   xhttp.send();

  },1000);


 }
 loadNote();

