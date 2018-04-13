<?php
include('connessione.php');
session_start();

if(isset($_POST['Login'])){
if(isset($_POST['EmailUtente']) && isset($_POST['PasswordUtente'])){
$emailutente = $_POST['EmailUtente'];
$passwordutente = $_POST['PasswordUtente'];


$controlloLogin = mysqli_prepare($connessione, "SELECT * FROM Utenti WHERE EmailUtente = ?
				AND PasswordUtente = ? ");
	mysqli_stmt_bind_param($controlloLogin, "ss",$emailutente, $passwordutente);
	mysqli_stmt_execute($controlloLogin);
	

if(mysqli_num_rows($risultatoEmail)>0){


$row = mysqli_fetch_array($risultatoEmail,MYSQLI_ASSOC);



//login
$_SESSION['NomeUtente'] = $row['NomeUtente'];
$_SESSION['id'] = $row['IdUtente'];

//se c'è il cognome vai in admin
if(  $row['CognomeUtente']!=0 || $row['CognomeUtente']!= '0'   ){
header('location: admin.php');
}

//se non c'è il cognome vai in cliente
else {

header('location: profilo.php');
	}
    }
    
    
//se sono errati visualizza il messaggio di errore    
    else{
echo '<script> alert("Email o password errati");</script>';

    
    }
    

}
}

//LOGIN APPLICAZIONI ESTERNE @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
if(isset($_POST['LoginApp']) && isset($_POST['Codice'])){

$cod = $_POST['Codice'];

$controlloLoginApp =mysqli_prepare($connessione, "SELECT * FROM ApplicazioneEsterna WHERE Codice = ?  ");
	mysqli_stmt_bind_param($controlloLoginApp, "i", $cod);
mysqli_stmt_execute($controlloLoginApp);


if(mysqli_num_rows($risultatoApp)>0){

$row = mysqli_fetch_array($risultatoApp,MYSQLI_ASSOC);
$_SESSION['idapp'] = $row['Codice'];
$_SESSION['nomeapp'] = $row['Nome'];

header('location: App_Loggata.php');
}

else{
echo '<script> alert("Applicazione esterna non esistente");</script>';

}
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
<title>LOGIN</title>
</head>

<body style="background-color:#d6ffa8 !important;">
<div class="container" align="center">
<h2> Login </h2>

<form action="login.php" method="post">
<input type="text" class="form-control" placeholder="Inserisci email" name="EmailUtente"><br>
<input type="password" class="form-control" placeholder="Inserisci password" name="PasswordUtente"><br>
<input type="submit" class="btn btn-default" value="Login" name="Login">
</form>

<br><br><br>
<h2> Login epplicazione esterna</h2>
<form action="login.php" method="post">
<input type="password" class="form-control" placeholder="Inserisci codice" name="Codice"><br>
<input type="submit" class="btn btn-default" value="Login" name="LoginApp">
</form>


</div>
</body>

<html>
