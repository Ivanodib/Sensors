<?php
include("connessione.php");

if(isset($_POST['Stringa'])){
$stringa = $_POST['Stringa'];


$Fk_Sensore = substr($stringa, 0, 10);
$Data = substr($stringa, 10, 19); 
$Rilevazione = substr($stringa, 29, 3);
$Dettagli = substr($stringa, 33, 50);
$stringaSplitted = " $Fk_Sensore, $Data, $Rilevazione, $Dettagli ";	

echo $stringaSplitted;
//"00000000092018-03-17 12:39:4429°RilevazioneSenzaErrori"




$inserisciRiv ="INSERT INTO RaccoltaDati (Fk_Sensore, Data, Rilevazione, Dettagli)
				VALUES ('$Fk_Sensore',NOW(),'$Rilevazione', '$Dettagli') ";
                
  $risultatoInserimento = mysqli_query($connessione, $inserisciRiv);              

if($risultatoInserimento){
echo '<script> alert("Inserimento riuscito") </script>';
}
else{
echo '<script> alert("Errore di rete") </script>';
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
<title>Rilevazione</title>
</head>
<body style="background-color:#d6ffa8 !important;">

<div class="container">
<h3>Decodifica stringa del sensore</h3>
<br><br>
<form action="rilevazione.php" method="post">
<label for="usr">Stringa di esempio:</label>
<input type="text" id="usr" class="form-control" value="00000000092018-03-17 12:39:4429°DettagliRilevazione" readonly>	<br>	
<input type="text" class="form-control" name="Stringa"><br>
<input type="submit" class="btn btn-default" value="Invia Stringa">
</form>

</div>

</body>
</html>
