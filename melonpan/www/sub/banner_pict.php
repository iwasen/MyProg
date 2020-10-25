<?
header("Cache-control: no-cache");
header("Content-type: application/octet-stream");

if (($host = $_ENV['MELONPAN_DB']) == '')
	$host = 'localhost';
$con = pg_Conxxxt ("host=$host port=5432 dbname=melonpan user=postgres");
pg_exec ($con, "begin");
$fd = pg_loopen($con, $bn_picture, "r");
pg_loreadall($fd);
pg_loclose($fd);
pg_exec ($con, "commit");
papg_close();
?>
