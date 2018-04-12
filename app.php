<?php
require('connessione.php');
session_start();
$codice = $_SESSION['codiceapp'];

$numerorighe = mysql_query("SELECT * FROM rilevazione");
$num = mysql_num_rows($numerorighe);

if(!isset($_SESSION['prec'])){
$_SESSION['prec']= $num;
}


//SCRIVI PREFERENZE 
if($_POST['salva']){
if(isset($_POST['radio']))
{
$radio = $_POST['radio'];
//value radio
$sql = mysql_query("UPDATE applicazione_esterna
SET formato = '".$radio."'
WHERE codice = $codice "); 

}}



if($num != $_SESSION['prec']){
$_SESSION['prec'] = $n;


//BLOCCO PER AVERE LE PREFERENZE
$sql = mysql_query("SELECT * FROM applicazione_esterna WHERE codice = '".$codice."' ");
$row = mysql_fetch_assoc($sql);
$nomefile = "Rilevazioni.".$row['formato'];
//echo "<script> alert($nomefile); </script>";
$datiRilevazione = $row['preferenze'];


//BLOCCO PER CREARE I CAMPI NELLA SELECT DEI DATI 
$query;
$result = mysql_query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` IN ('rilevazione', 'sensore') AND COLUMN_NAME NOT LIKE 'id_%' ");
$i=0;

    while ($row = mysql_fetch_assoc($result)) {
    $campo = $row["COLUMN_NAME"];
    if($datiRilevazione[$i] == "1"){
	    $query = $query.$row["COLUMN_NAME"].",";
      
    }
   
$i++;    
}

 $query=substr($query, 0, -1);
  $querytot = "SELECT ".mysql_real_escape_string($query)." FROM sensore
							INNER JOIN rilevazione ON `rilevazione`.`id_sensoreFK` = `sensore`.`id_sensore`
                            INNER JOIN utente ON `sensore`.`id_clienteFK` = `utente`.`id_cliente`
							INNER JOIN applicazione_esterna ON `utente`.`id_cliente` = `applicazione_esterna`.`id_clienteFK`
                            WHERE `applicazione_esterna`.`codice` = ".mysql_real_escape_string($codice)."";
                           
//QUERY PER AVERE I DATI
$totale = mysql_query($querytot) or die(mysql_error());

$file = fopen( $nomefile , "w");

while($rigafile = mysql_fetch_assoc($totale))
  {
  fputcsv($file,$rigafile);
  //fputcsv($file, explode($rigafile));
  }
  
fclose($file);

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
<html>
<head>
	<link href="stile.css" rel="stylesheet" type="text/css">
	<meta charset="utf-8" />
    <meta http-equiv="refresh" content="4">
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body>
<div id="menu" align="center">
<h2> Applicazione esterna <?=$_SESSION['nomeapp']?> </h2>
<h3> I dati verranno scaricati automaticamente </h3>



<form action="app.php" method="post">
<input type="radio" name="radio" value="txt">TXT
<input type="radio" name="radio" value="csv">CSV
<input class="bottone" type="submit" name="salva" value="Salva formato">
</form>
</div>


</body>
</html>