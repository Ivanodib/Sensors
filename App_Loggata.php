<?php
include('connessione.php');
session_start();
$codice = $_SESSION['idapp'];



$numerorighe = mysqli_query($connessione,"SELECT * FROM Trasferimento");
$num = mysqli_num_rows($numerorighe);

if(!isset($_SESSION['prec'])){
$_SESSION['prec']= $num;
}

if($num != $_SESSION['prec']){
$_SESSION['prec'] = $n;
 //} //CANCELLA 

//BLOCCO PER AVERE LE PREFERENZE
$sql = mysqli_query($connessione,"SELECT * FROM ApplicazioneEsterna WHERE Codice = '".$codice."' ");

$row = mysqli_fetch_assoc($sql);
$nomefile = "Rilevazioni.csv";
$datiRilevazione = $row['Dati_trasferiti'];


//BLOCCO PER CREARE I CAMPI NELLA SELECT DEI DATI 
$query;
$result = mysqli_query($connessione, "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` IN ('RaccoltaDati', 'Sensore') AND COLUMN_NAME NOT LIKE 'Id_%'  
 AND COLUMN_NAME NOT LIKE 'Fk_%'") ;
$i=0;

    while ($row = mysqli_fetch_assoc($result)) {
    $campo = $row['COLUMN_NAME'];
    if($datiRilevazione[$i] == "1"){
	    $query = $query.$row['COLUMN_NAME'].",";
      
    }
   
$i++;    
}


 $query=substr($query, 0, -1);
	

  $querytot = "SELECT ".mysql_real_escape_string($query)." FROM Sensore
							INNER JOIN RaccoltaDati ON `RaccoltaDati`.`Fk_Sensore` = `Sensore`.`Id_Sensore`
                            INNER JOIN Utenti ON `Sensore`.`Fk_Utente` = `Utenti`.`IdUtente`
							INNER JOIN ApplicazioneEsterna ON `Utenti`.`IdUtente` = `ApplicazioneEsterna`.`Fk_Utente`
                            WHERE `ApplicazioneEsterna`.`Codice` = ".mysql_real_escape_string($codice)."";
                            
                           
//QUERY PER AVERE I DATI
$totale = mysqli_query($connessione, $querytot);

$file = fopen( $nomefile , "w");


while($rigafile = mysqli_fetch_assoc($totale)){

	fputcsv($file, $rigafile = chr(0xEF) . chr(0xBB) . chr(0xBF));
  }
  
fclose($file);

//inizio download

 header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream'); //provato a rimuovere
        header('Content-Disposition: attachment; filename="'.basename($nomefile).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public'); //provato a rimuovere
    header('Content-Length: ' . filesize($nomefile));
    readfile($nomefile);
    
   
    } 
unlink($nomefile);

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
<meta http-equiv="refresh" content="3">
<title>PROFILO</title>
</head>

<body style="background-color:#d6ffa8 !important;">
<div class="container">
<h2> Applicazione esterna: <?=$_SESSION['nomeapp'] ?>
 <a href="http://hitechstudios.altervista.org/logout.php" class="btn btn-default" align="center">Logout</a>
</h2>
<br><br>
<h4>Rimani su questa pagina, il cliente farà partire il trasferimento dati.</h4>
</div>
</body>

<html>
