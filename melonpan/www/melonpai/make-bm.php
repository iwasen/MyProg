<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

/****************************************************************
 Main
****************************************************************/

mex_passchk($conn, $melonpai_id, $melonpai_pswd);

include("tmpl_html/make-bm.html");

?>