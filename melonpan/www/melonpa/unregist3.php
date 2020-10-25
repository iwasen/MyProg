<?php
include("inc/pass_check.inc");

/****************************************************************
 Main
****************************************************************/

pass_check($conn,trim($mail_addr),trim($reader_pswd));
include ("tmpl_html/unregist3.html");

?>
