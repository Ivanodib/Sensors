<?php

require("connessione.php");
session_start();

if(session_unset ()){ 
print("ok");
}
else {
print("no");
}

if(session_destroy ()){ 
print("ok");
}
else {
print("no");
}

header("location:index.php");
?>
