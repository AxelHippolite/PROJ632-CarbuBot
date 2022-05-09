<?php
function update(){
    /*Download XML ZIP + UNZIP*/
    $url = "https://donnees.roulez-eco.fr/opendata/instantane";
    $bdd = new PDO('mysql:host=db5006933829.hosting-data.io;dbname=dbs5724752;', 'dbu1579079', '#Axelou2002');
    $zipFile = "data.zip";
    $zipRessource = fopen($zipFile, "w");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
    curl_setopt($ch, CURLOPT_FILE, $zipRessource);
    curl_exec($ch);
    curl_close($ch);
    $zip = new ZipArchive;
    $zip->open($zipFile);
    $zip->extractTo('assets/');
    $zip->close();
    unlink($zipFile);

    /*Reset TABLE*/
    $sql = 'TRUNCATE TABLE `station`';
    $insert = $bdd->prepare($sql);
    $insert->execute();

    /*Data Dump + Insertion In DB*/
    $xml = simplexml_load_file("assets/PrixCarburants_instantane.xml") or die("Error: Cannot create object");
    $norm = array("é", "è", "ë");
    foreach($xml->children() as $row){
        $cp = $row->attributes()->cp;
      	$latitude = $row->attributes()->latitude;
      	$longitude = $row->attributes()->longitude;
        $city = $row->ville;
        $adress = $row->adresse;
        $count = $row->prix->count();
        $gazole = $sp95 = $e85 = $gplc = $e10 = $sp98 = NULL;
        for($i=0; $i<$count; $i++){
            if($row->prix[$i]->attributes()->id == 1){
                $gazole = $row->prix[$i]->attributes()->valeur;
            } elseif($row->prix[$i]->attributes()->id == 2){
                $sp95 = $row->prix[$i]->attributes()->valeur;
            } elseif($row->prix[$i]->attributes()->id == 3){
                $e85 = $row->prix[$i]->attributes()->valeur;
            } elseif($row->prix[$i]->attributes()->id == 4){
                $gplc = $row->prix[$i]->attributes()->valeur;
            } elseif($row->prix[$i]->attributes()->id == 5){
                $e10 = $row->prix[$i]->attributes()->valeur;
            } elseif($row->prix[$i]->attributes()->id == 6){
                $sp98 = $row->prix[$i]->attributes()->valeur;
            } else{
                die("Error: ID not found");
            }
        }
        
        $sql = 'INSERT INTO station(cp,city,latitude,longitude,adress,gazole,sp95,sp98,e85,e10,gplc) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $insert = $bdd->prepare($sql);
        $insert->execute(array($cp, str_replace($norm, "E", strtoupper($city)), $latitude*pow(10,-5), $longitude*pow(10,-5), str_replace($norm, "E", strtoupper($adress)), $gazole, $sp95, $sp98, $e85, $e10, $gplc));
    }
}
update();
?>