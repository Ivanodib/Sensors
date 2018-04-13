<?php

  $pw="";
  $connessione = mysqli_connect('localhost','root',$pw);
  
 	mysqli_select_db($connessione, 'my_hitechstudios') or die('errore');


?>

