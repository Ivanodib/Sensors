<?php
include("connessione.php");

if(isset($_POST['Login'])){
// login

header("location: login.php");

}

if(isset($_POST['Registrazione'])){
// registrazione

header("location: registrazione.php");

}


?>


<!DOCTYPE html>
<html lang="en">

<head>

 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 


<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PAGINA PRINCIPALE</title>
</head>

<body style="background-color:#d6ffa8 !important;">

<div class="container">
<h2 align="center"> HITECHSTUDIOS </h2><br><br>
<h2 align="center"> Accedi o registrati </h2>
<div align="center">
<form action="index.php"  method="post">

<input type="submit"  class="btn btn-default" value="Login" name="Login">
<input type="submit" class="btn btn-default" value="Registrazione" name="Registrazione">


</form>
</div>
</div>

</body>

<html>
