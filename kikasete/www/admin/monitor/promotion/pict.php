<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/database.php");

header("Cache-control: no-cache");
header("Content-type: application/octet-stream");

db_begin_trans();

$fd = pg_loopen($gConn, $id, "r");
pg_loreadall($fd);
pg_loclose($fd);

db_begin_trans();
exit;
?>