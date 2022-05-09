<?php
/*Connection to DB*/
header('Content-Type: application/json');
$bdd = new PDO('mysql:host=db5006933829.hosting-data.io;dbname=dbs5724752;', 'dbu1579079', '#Axelou2002');

/*Get the Parameters via the URL*/
$api_key = $_GET["key"];
$cp = $_GET["cp"];
$carbu = $_GET["ca"];
$conso = $_GET["co"];

/*Selects and Returns the Appropriate Data*/
$sql = 'SELECT `key` FROM `api_keys`';
$retire = $bdd->query($sql);
$keys = $retire->fetch();
if(in_array($api_key, $keys)){
    $sql = "SELECT `id`, `cp`, `city`, `latitude`, `longitude`, `adress`, ".$carbu." FROM `station` WHERE `cp` LIKE '".$cp."%' ORDER BY ".$carbu." ASC";
    $retire = $bdd->query($sql);
    $data = $retire->fetchAll(PDO::FETCH_ASSOC);
    $data = array_values($data);
    $json =  json_encode($data, JSON_FORCE_OBJECT, JSON_PRETTY_PRINT);
    echo $json;
}
?>