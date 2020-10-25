<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");
include ("inc/get_kensuu.inc");

/****************************************************************
 Main
****************************************************************/

mex_passchk($conn, $melonpai_id, $melonpai_pswd);
get_shori_kensuu($melonpai_id, $mitsukuroi, $suisenbun);

include("mypage.html");
?>