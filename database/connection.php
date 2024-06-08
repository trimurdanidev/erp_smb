<?php
$host='localhost';
$user='root';
$pass='';
$db='db_sperepart_bekasi';
try {
$dbh = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
echo "Connection failed: " . $e->getMessage();
}
?>
