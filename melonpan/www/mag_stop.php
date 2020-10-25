<?php
include("inc/com_db_func2.inc");
include("sub/head.inc");

/* ---------------------------------------------------- */
/*  解除&解除理由登録																		*/
/* ---------------------------------------------------- */
function mag_unregist($mag_id, $mag_nm, $mail_addr, $reader_id){
global $mag_datas, $error_datas;

	$delete_id = update_squ_key("T_DELREASON");
	if ($delete_id != "-1") {
		//理由登録
		$sql = "INSERT INTO T_DELREASON (delete_id, mag_id, reader_id, reason, modify_dt) "
			 	 . "VALUES"
			 	 . "  (". $delete_id.",'".$mag_id."',".$reader_id.",'その他','now')";
		db_exec($sql);
		//解除
		$sql =  "delete from t_mailaddr where mail_addr='$mail_addr' and mag_id='$mag_id'";
		db_exec($sql);

		$mag_datas = "<br><FONT color=#990000><B>[ID".$mag_id."：".trim($mag_nm)."]</B></FONT>"
							 . "<br>購読解除を完了しました。";
	}else{
		$error_datas = "<br><FONT color=#990000><B>[ID".$mag_id."：".trim($mag_nm)."]</B></FONT>"
								 . "<br>".trim($mag_nm)."の解除に失敗しました。";
	}
}

/* ---------------------------------------------------- */
/*  main 																								*/
/* ---------------------------------------------------- */

$mail_id = $id;
if ($mail_id<>"" and $key<>""){
	$sql = "select M.MAG_ID as mag_id, M.MAG_NM as mag_nm, A.MAIL_ADDR as mail_addr, A.READER_ID as reader_id "
		 . "  from t_mailaddr A, m_magazine M "
		 . " where A.mail_id = $mail_id and to_char(A.modify_dt, 'HH24MISS') = '$key' and A.mag_id = M.mag_id ";
	//print $sql;
	$result = db_exec($sql);
	if (pg_numrows($result)==1) {
		$fetch = pg_fetch_object($result, 0);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$mail_addr = $fetch->mail_addr;
		$reader_id = $fetch->reader_id;
		mag_unregist($mag_id, $mag_nm, $mail_addr, $reader_id);
	}else{
		$error_datas = "<br>すでに解除されているようです。";
	}
}else{
	$error_datas = "<br>購読されていないようです。";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<TITLE>購読解除</TITLE>
<link rel="stylesheet" href="../css/melonpan.css">
</head>
<?php draw_head( "." ); ?>
<BODY bgcolor="#FFFFFF" text="#666666" link="#336600" vlink="#666600">
<TABLE width="750" border="0" cellspacing="0" cellpadding="0">
<TR>
<TD valign="top" colspan="3" align="center">
<TABLE width="513" border="0" cellspacing="0" cellpadding="0" bgcolor="#6AB05D">
<TR>
<TD>
<TABLE width="513" border="0" cellspacing="1" cellpadding="5">
<TR bgcolor="#FFFFFF">
<TD>
<p align="center"><IMG src="img/t_touroku.gif" width="510" height="27"></P>
<BR>
<?php
	if ($mag_datas != "") {
		print "<P>●正常処理\n";
		print $mag_datas;
		print "</P>\n";
	}

	if ($error_datas != "") {
		print "<P><font color=\"#ff0000\">●解除未完\n";
 		print $error_datas;
		print "</font></P>\n";
	}
?>
<p align="center">　</p>
<p> 
<a href="index.html" target="_top">&lt;&lt;&lt;　めろんぱんトップへ</a>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</TD>
</TR>
<?php draw_footer( "." ); ?>
</TABLE>
</body>
</html>
