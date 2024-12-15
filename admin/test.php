<?php 

$id = isset($_GET["id"]) ? $_GET["id"] : 1;
$currentData = query("SELECT * FROM anggota WHERE id_anggota = $id")[0];
?>