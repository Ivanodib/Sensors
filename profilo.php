<?php
include "connessione.php";
session_start();

$idutente = $_SESSION['id'];
$flag_vis=0;
$flag_dati=0;

if(isset($_POST['Trasferisci'])){
//mysqli_query($connessione, "INSERT INTO Trasferimento (Flag) VALUES (1) ");
}


if(isset($_POST['Visualizza'])){
$flag_vis=1;
}

if(isset($_POST['VisualizzaDati'])){
$flag_dati=1;
}



if(isset($_POST['Cancella'])){
$idapp = $_POST['CancellaApp'];


$cerca = mysqli_prepare($connessione,"SELECT * FROM ApplicazioneEsterna WHERE Codice = ?  ");
	mysqli_stmt_bind_param($cerca, "i", $idapp);
	mysqli_stmt_execute($cerca);
	
if(mysqli_num_rows($cerca)>0){

//$elimina = mysqli_prepare($connessione,"DELETE FROM ApplicazioneEsterna WHERE Codice = ?  ");
	mysqli_stmt_bind_param($elimina, "i", $idapp);
	mysqli_stmt_execute($elimina);

if( isset($elimina)) {
	if($elimina==1){
	$r1="<script> alert( 'Applicazione esterna rimossa'); </script>";
	echo $r1;  
	}
}
else {
	$r1="<script> alert( 'Errore di rete durante la cancellazione'); </script>";
	echo $r1;
		 }
}

else{

	$r1="<script> alert( 'Codice inesistente'); </script>";
	echo $r1;
}
}


if(isset($_POST['Autorizza'])){

$codice=0;
$stringa ="00000";

$checkbox = mysql_query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` IN ('RaccoltaDati', 'Sensore') AND COLUMN_NAME NOT LIKE 'Id_%' AND COLUMN_NAME NOT LIKE 'Fk_%'"); 

$i=0;

if(isset($_POST['NomeApp'])){
$nome = $_POST['NomeApp'];

}
while($row = mysql_fetch_assoc($checkbox)){
$temp = $row['COLUMN_NAME'];

 if(isset($_POST['test'.$temp])){
 
 $stringa[$i]='1';
 } else{
 $stringa[$i]='0';
 }
 
$i++;
}
//echo $stringa;
$codice = rand(1,1000000000);
$r1 = "<script> alert( 'CODICE APPLICAZIONE ESTERNA: $codice'); </script>";
	echo $r1;
$stmt =  mysqli_prepare($connessione,"INSERT INTO ApplicazioneEsterna (Fk_Utente,Codice,Nome,Dati_trasferiti) 
			values ('?','?','?','?' )" );
	mysqli_stmt_bind_param($stmt, "isss", $idutente, $codice, $nome, $stringa);
	mysqli_stmt_execute($stmt);
           
            

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

<?php
$query = mysqli_query($connessione, "SELECT Data,Rilevazione FROM RaccoltaDati INNER JOIN  Sensore ON Fk_Sensore = Id_Sensore
								INNER JOIN Utenti ON Fk_Utente = IdUtente WHERE IdUtente = '".$idutente."' ");
																		
$dataPoints = array();

while ($row = mysqli_fetch_assoc($query)) {

$val = $row['Rilevazione'];
$int = (int)filter_var($val, FILTER_SANITIZE_NUMBER_INT);

$dataPoints[] = array("label" => $row['Data'], "y" =>$int );
}
?>


<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title: {
		text: "Varianza temperatura per data"
	},
	axisY: {
		title: "Temperatura",
		includeZero: true
	},
	data: [{
		type: "column",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>





<title>PROFILO</title>
</head>

<body style="background-color:#d6ffa8 !important;">
<div class="container">
<h2 align="center"> Benvenuto, <?=$_SESSION['NomeUtente'] ?>!
<a href="http://hitechstudios.altervista.org/logout.php" class="btn btn-default" align="center">Logout</a>
</h2> 


<div class="row">
  <div class="col-sm-4">
<h3>Autorizza Applicazione Esterna </h3>

<form align="center" action="profilo.php" method="post">

<input type="text" class="form-control" placeholder="Inserisci nome applicazione" name="NomeApp"><br>
<!--
rilevazione <input type='checkbox'  name='testRilevazione' value = 'Rilevazione'><br>
data <input type='checkbox'  name='testData' value = 'Data'><br>
dettagli <input type='checkbox'  name='testDettagli' value = 'Dettagli'><br>
marca <input type='checkbox'  name='testMarca' value = 'Marca'><br>
tipo <input type='checkbox'  name='testTipologia' value = 'Tipologia'><br>
!-->
<?php


$checkbox = mysql_query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` IN ('RaccoltaDati', 'Sensore') AND COLUMN_NAME NOT LIKE 'Id_%' AND COLUMN_NAME NOT LIKE 'Fk_%'"); 

while($row = mysql_fetch_assoc($checkbox)){

$temp = $row['COLUMN_NAME'];
	$stringa = $temp."      "."<input type='checkbox' name='test$temp' value = '$temp'><br>";
  echo htmlspecialchars($stringa, ENT_QUOTES, 'UTF-8');
 
/*echo" <div class='checkbox-inline'>
  <label><input type='checkbox' value='$temp' name'test$temp'>$temp</label>
</div>";*/

}

?><br>
<input type="submit" class="btn btn-default" value="Autorizza" name="Autorizza"> <br><br>
</form>
<br><br>

<h3>Elimina Applicazione Esterna </h3>
<form align="center" action="profilo.php" method="post">
<input type="number" class="form-control" placeholder="Inserisci id applicazione esterna" name="CancellaApp"><br>
<input type="submit" class="btn btn-default" value="Cancella" name="Cancella"><br><br>
</form>

<form align="center" action="profilo.php" method="post">
<input type="submit" class="btn btn-default" value="Visualizza applicazioni esterne" name="Visualizza">

<table class="table">

<?php
global $flag_vis;
global $idutente;
if(isset($flag_vis)){

	if($flag_vis==1){
$vis= mysqli_query($connessione, "SELECT * FROM ApplicazioneEsterna WHERE Fk_Utente = '".$idutente."' ");

	$r1 = 	"<tr>";
	$r2 = 	"<th class='th'>CODICE</th>";
		$r3 = "<th class='th'>NOME APPLICAZIONE</th>";
		$r4 = "</tr>";
echo $r1;
echo $r2;
echo $r3;
echo $r4;


}
while ($row = mysqli_fetch_assoc($vis)) {
	$tr = "<tr>";
		echo $tr;
	$r1 = "<td class='td'> ". $row['Codice']."</td> ";
	$r2 = "<td class='td'> ". $row['Nome']." </td>";
	
        echo htmlspecialchars($r1, ENT_QUOTES, 'UTF-8');
        echo htmlspecialchars($r2, ENT_QUOTES, 'UTF-8');
	$rt = "</tr>"; 
        echo $rt;
        }
}

?>
</table>
<input type="submit" class="btn btn-default" value="Visualizza Dati" name="VisualizzaDati">
<table class="table">

<?php
global $flag_dati;
global $idutente;
if(isset($flag_dati)){

	if($flag_dati==1){
$d= mysqli_query($connessione, "SELECT * FROM RaccoltaDati INNER JOIN  Sensore ON Fk_Sensore = Id_Sensore
								INNER JOIN Utenti ON Fk_Utente = IdUtente WHERE IdUtente = '".$idutente."' ");

$tr = "<tr>"; 
        echo $tr; 
	$h1 = "<th class='th'>CODICE SENSORE</th>";
echo $h1;
$h2 = "<th class='th'>DATA RILEVAZIONE</th>";
echo $h2;
$h3 = "<th class='th'>RILEVAZIONE</th>";
echo $h3;
$h4 = "<th class='th'>DETTAGLI</th>";
echo $h4;
$rt = "</tr>"; 
        echo $rt;

}
while ($row = mysqli_fetch_assoc($d)) {
		$tr = "<tr>";
		echo $tr;
		$r1 = "<td class='td'> ". $row['Fk_Sensore']."</td> ";
		$r2 = "<td class='td'> ". $row['Data']." </td>";
		$r3 = "<td class='td'> ". $row['Rilevazione']." </td>";
		$r4 = "<td class='td'> ". $row['Dettagli']." </td>";
        echo htmlspecialchars($r1, ENT_QUOTES, 'UTF-8');
       echo htmlspecialchars($r2, ENT_QUOTES, 'UTF-8');
        echo htmlspecialchars($r3, ENT_QUOTES, 'UTF-8');
        echo htmlspecialchars($r4, ENT_QUOTES, 'UTF-8');
        $rt = "</tr>"; 
        echo $rt;
        }
}
?>
</table>
<br>
<input type="submit" class="btn btn-default" value="Trasferisci dati" name="Trasferisci">
</form>

</div>

<div class="col-sm-2">
</div>

<div class="col-sm-6">
<h3>Dashboard</h3>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</div>


</div>
</div>
</body>
</html>
