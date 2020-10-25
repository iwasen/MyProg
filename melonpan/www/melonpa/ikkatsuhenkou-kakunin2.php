<?php
include("inc/pass_check.inc");

/****************************************************************
 Main
****************************************************************/

if ($mail_addr != "") {pass_check($conn,trim($mail_addr),trim($reader_pswd));}

$sql1 = "UPDATE M_MELONPA SET ";
$sql1 = $sql1 . " mail_addr = '" . $chg_mail_addr . "'";
$sql1 = $sql1 .  " where mail_addr = ";
$sql =  $sql1 . "'". $mail_addr . "'";
db_exec($sql);

$sql1 = "UPDATE T_CHOICE SET ";
$sql1 = $sql1 . " SEND_MAIL_ADDR = '" . $chg_mail_addr . "'";
$sql1 = $sql1 .  " where SEND_MAIL_ADDR = ";
$sql = $sql1 . "'" .   $mail_addr . "'";
db_exec($sql);

$sql1 = "UPDATE T_MAILADDR SET ";
$sql1 = $sql1 . " MAIL_ADDR = '" . $chg_mail_addr . "'";
$sql1 = $sql1 .  " where MAIL_ADDR = ";
$sql = $sql1 . "'" .   $mail_addr . "'";
db_exec($sql);

setcookie("cookie_reader_id", $reader_id, time()+604800);
setcookie("cookie_mail_addr", trim($chg_mail_addr), time()+604800);
setcookie("cookie_reader_pswd", trim($reader_pswd), time()+604800);

include ("tmpl_html/ikkatsuhenkou-kakunin2.html");
?>
