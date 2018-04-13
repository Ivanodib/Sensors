<?php
include('connessione.php');
session_start();

$flag_sens =0;
$flag_uten =0;

function aggiungiZeri($marcaTipo){
return  str_pad($marcaTipo, 20, "0", STR_PAD_LEFT); 
}

if(isset($_POST['VisualizzaSensori'])){
$flag_sens =1;
}

if(isset($_POST['VisualizzaUtenti'])){
$flag_uten =1;
}

//AGGIUNGI SENSORE @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

if(isset($_POST['Inserisci'])){
if(isset($_POST['Marca']) && isset($_POST['Tipologia']) && isset($_POST['Fk_Utente'])){

$marca = $_POST['Marca'];
$tipologia = $_POST['Tipologia'];
$fkutente = $_POST['Fk_Utente'];

$m2 = aggiungiZeri($marca);
//cc
$t2 = aggiungiZeri($tipologia);

	/*
	
	$stmt = mysqli_prepare($link, "INSERT INTO CountryLanguage VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sssd', $code, $language, $official, $percent);

$code = 'DEU';
$language = 'Bavarian';
$official = "F";
$percent = 11.2;

mysqli_stmt_execute($stmt);
	
	*/

//originale
/*$controlloSensore = "SELECT Id_Sensore FROM Sensore WHERE Marca = '".$m2."'  AND Tipologia = '".$t2."' 
						AND Fk_Utente = '".$fkutente."'  ";
$risultatoSensore = mysqli_query($connessione, $controlloSensore);*/
	
	//modificato
	
	
	$controlloSensore = mysqli_prepare($connessione,"SELECT Id_Sensore FROM Sensore WHERE Marca = ?  AND Tipologia = ? 
						AND Fk_Utente = ? ");						
		mysqli_stmt_bind_param($controlloSensore, 'ssi', $m2, $t2, $fkutente);						
mysqli_stmt_execute ($controlloSensore);
	

	

//esiste
if(mysqli_num_rows($risultatoSensore)>0){
	$r1 = '<script> alert("Questo utente ha già un sensore con la stessa marca e tipologia");</script>';
echo $r1;
}

//non esiste
else{
$queryInserisci = mysqli_prepare($connesione, "INSERT INTO Sensore (Fk_Utente, Marca, Tipologia)
						VALUES('?','?','?') " );
	mysqli_stmt_bind_param($queryInserisci, 'iss', $fkutente, $m2, $t2);
        mysqli_stmt_execute ($queryInserisci);
	
}

if($risultatoInserimento){
	$r1 = '<script> alert("Sensore inserito correttamente");</script>';
echo $r1;
}
else{
	$r2 = '<script> alert("Errore di rete");</script>';
echo $r2;
}


}//fine isset inputs
}//fine inserisci button.


//ELIMINA SENSORE @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

if(isset($_POST['Elimina'])){
if(isset($_POST['Fk_Sensore'])){
$fksensore = $_POST['Fk_Sensore'];



$controlloSensore = mysqli_prepare($connessione, "SELECT Id_Sensore FROM Sensore WHERE Id_Sensore = ? ");
	mysqli_stmt_bind_param($controlloSensore, "i", $fksensore);
	mysqli_stmt_execute($controlloSensore);

//sensore esistente, quindi elimino.
if(mysqli_num_rows($risultatoSensore)>0){

$queryElimina = mysqli_prepare($connessione, "DELETE FROM Sensore WHERE Id_Sensore = '?'  ");                        
	mysqli_stmt_bind_param($queryElimina, "i", $fksensore);
	mysqli_stmt_execute($queryElimina);



if($risultatoEliminazione){
	$r1 = '<script> alert("Sensore eliminato correttamente");</script>';
echo $r1;
}
else{
	$r2 = '<script> alert("Errore di rete");</script>';
echo $r2;

}
}
else {
	$r3 = '<script> alert("Sensore inesistente");</script>';
echo $r3;

}


}
}


//ELIMINA UTENTE @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

if(isset($_POST['EliminaUtente'])){
if(isset($_POST['Id_Utente'])){
$idutente = $_POST['Id_Utente'];



$controlloUtente = mysqli_prepare($connessione, "SELECT IdUtente FROM Utenti WHERE IdUtente = ? ");                      
	mysqli_stmt_bind_param($controlloUtente, "i", $idutente);
	mysqli_stmt_execute($controlloUtente);


//utente esistente, quindi elimino.
if(mysqli_num_rows($risultatoUtente)>0){

$queryElimina = mysqli_prepare($connessione, "DELETE FROM Utenti WHERE IdUtente = ?  ");                                             
	mysqli_stmt_bind_param($queryElimina, "i", $idutente);
	mysqli_stmt_execute($queryElimina);

$queryEliminaS = mysqli_prepare($connessione, "DELETE FROM Sensore WHERE Fk_Utente = ?  ");                        
	mysqli_stmt_bind_param($queryEliminaS, "i", $idutente);
	mysqli_stmt_execute($queryEliminaS);


if($risultatoEliminazione){
	$r1 = '<script> alert("Utente eliminato correttamente");</script>';
echo $1;
}
else{
	$r2 = '<script> alert("Errore di rete");</script>';
echo $r2;

}
}
else {
	$r3 = '<script> alert("Utente inesistente");</script>';
echo $r3;

}


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
<title>ADMIN</title>
</head>
<body style="background-color:#d6ffa8 !important;">

<div class="container">


 <div class="row">
  <div class="col-sm-4">
  <h2> GESTIONE SENSORE </h2><br>

<h3> Inserisci sensore </h3>
<form action="admin.php" method="post">
<input type="number" class="form-control" placeholder="Inserisci Id cliente" name="Fk_Utente" required><br>
<input type="text" class="form-control" placeholder="Inserisci marca" name="Marca" required><br>
<input type="text" class="form-control" placeholder="Inserisci tipologia" name="Tipologia" required><br>
<input type="submit" class="btn btn-default" value="Inserisci" name="Inserisci">
</form>

<br><br>
<h3> Elimina sensore </h3>
<form action="admin.php" method="post">
<input type="number" class="form-control" placeholder="Inserisci Id sensore" name="Fk_Sensore" required><br>
<input type="submit" class="btn btn-default" value="Elimina" name="Elimina">
</form><br><br>


<form action="admin.php" method="post">
<input type="submit" class="btn btn-default" value="Visualizza sensori" name="VisualizzaSensori">
<table class="table">

<?php
global $flag_sens;
global $idutente;

if($flag_sens){
$vis= mysqli_query($connessione, "SELECT * FROM Sensore ORDER BY Fk_Utente ");

echo"<tr>";
echo"<th class='th'>CODICE SENSORE</th>";
echo"<th class='th'>CODICE UTENTE</th>";
echo"<th class='th'>MARCA</th>";
echo"<th class='th'>TIPOLOGIA</th>";

echo"</tr>";

}
while ($row = mysqli_fetch_assoc($vis)) {
		echo"<tr>";
	$r1 = "<td class='td'>". $row['Id_Sensore']."</td>";
	$r2 = "<td class='td'>". $row['Fk_Utente']."  </td>";
	$r3 = "<td class='td'>". $row['Marca']." </td>";
	$r4 =  "<td class='td'>". $row['Tipologia']." </td>";
        echo htmlspecialchars($r1, ENT_QUOTES, 'UTF-8');
        echo htmlspecialchars($r2, ENT_QUOTES, 'UTF-8');
	echo htmlspecialchars($r3, ENT_QUOTES, 'UTF-8');
	echo htmlspecialchars($r4, ENT_QUOTES, 'UTF-8');
         
          
        echo "</tr>";
        }

?>
</table>
</form>

  
  </div>
<div class="col-sm-4" ><br><br><br><br>
</div>
  
  <div class="col-sm-4" >
  <h2> GESTIONE CLIENTI </h2><br>
  
  <h3> Inserisci cliente </h3>
  <form  action="registrazione.php" method="post">
  <input type="text"  class="form-control" placeholder="Inserisci nome" name="NomeUtente" required><br>
<input type="email" class="form-control" placeholder="Inserisci email" name="EmailUtente" required><br>
<input type="password" class="form-control" placeholder="Inserisci password" name="PasswordUtente" required><br>
<input type="password" class="form-control" placeholder="Ripeti password" name="RipetiPassword" required><br>
<input type="submit" class="btn btn-default" value="Aggiungi utente" name="AdminReg">
<input type="hidden" class="form-control" placeholder="Inserisci cognome" name="CognomeUtente" ><br>
</form><br><br>

<h3> Elimina cliente </h3>
<form action="admin.php" method="post">
<input type="number" class="form-control" placeholder="Inserisci Id cliente" name="Id_Utente" required><br>
<input type="submit" class="btn btn-default" value="Elimina" name="EliminaUtente">
</form><br><br>

<form action="admin.php" method="post">
<input type="submit" class="btn btn-default" value="Visualizza clienti" name="VisualizzaUtenti">
<table class="table">

<?php 
global $flag_uten;


if($flag_uten){
$vis= mysqli_query($connessione, "SELECT * FROM Utenti WHERE CognomeUtente = '0' ");

echo"<tr>";
echo"<th class='th'>CODICE CLIENTE</th>";
echo"<th class='th'>NOME CLIENTE</th>";
echo"<th class='th'>EMAIL</th>";
echo"<th class='th'>PASSWORD</th>";

echo"</tr>";

}
while ($row = mysqli_fetch_assoc($vis)) {
		echo"<tr>";
	$r1 = "<td class='td'>". $row['IdUtente']."</td>";
	$r2 = "<td class='td'>". $row['NomeUtente']."</td>";
	$r3 = "<td class='td'>". $row['EmailUtente']."</td>";
	$r4 = "<td class='td'>". $row['PasswordUtente']."</td>";
	
         echo htmlspecialchars($r1, ENT_QUOTES, 'UTF-8');
       echo htmlspecialchars($r2, ENT_QUOTES, 'UTF-8');
         echo htmlspecialchars($r3, ENT_QUOTES, 'UTF-8');
          echo htmlspecialchars($r4, ENT_QUOTES, 'UTF-8');
        echo "</tr>";
        }

?>
</table>
</form>

  </div>
  
  
</div> 
</div>


</body>
</html>
