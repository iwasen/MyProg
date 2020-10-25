<?php
include("inc/com_db_func2.inc");
include("sub/head.inc");

/* -------------------------------------------------------- */
/*  購読解除												*/
/* -------------------------------------------------------- */
function unregist($conn, $r_id, $pass, &$mag_nm){

	$sql = "select MM.MAG_ID, MM.MAG_NM from T_MAILADDR MA, M_MAGAZINE MM "
		 . " where MA.MAG_ID = MM.MAG_ID and MA.MAIL_ID = '$r_id' and to_char(MA.MODIFY_DT, 'HH24MI') = '$pass'";
	$result = db_exec($sql);
	$rows = pg_numrows($result);
	if ($rows == 1) {
		$fetch = pg_fetch_object($result, 0);
		$wk_mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		if ($wk_mag_id != "000013" && $wk_mag_id != "000049" && $wk_mag_id != "S00001") {	return 0;}
	}else{
		return 0;
	}
	
	$sql = "delete from T_MAILADDR "
		 . " where MAIL_ID = '$r_id' and to_char(MODIFY_DT, 'HH24MI') = '$pass' and MAG_ID = '$wk_mag_id'";

	if (db_exec($sql)){
		return true;
	}else{
		return 0;
	}
}
/* -------------------------------------------------------- */
/*  おすすめ取得											*/
/* -------------------------------------------------------- */
/* 2002.01.25削除
function osusume_list($conn, &$cnt, &$list){
	$cnt = 0;
	$sql = "select M.MAG_ID, M.MAG_NM, MAG_HP, M.MAG_INTRO_SHORT from T_OSUSUME_LIST O, M_MAGAZINE M "
		 . " where O.DATE_TO is NULL and O.MAG_ID = M.MAG_ID "
		 . " order by sel_date";
	$stmt = OCIParse($conn, $sql);
	$ret = OCIExecute($stmt, OCI_DEFAULT);
	if (!$ret) {
		OCIFreestatement($stmt);
		return 0;
	}
	$cnt = OCIFetchStatement ($stmt, &$list);
	OCIFreestatement($stmt);

	$cnt = 3;	//３誌まで
	return;
}
*/

/* -------------------------------------------------------- */
/*  main													*/
/* -------------------------------------------------------- */

	//引数チェック(id = reader_id + HHMI)
	$emsg = "";
	if (strlen($id) < 5)	{ $emsg = "このページは現在使用できません。<BR>";}

	$r_id = substr($id, 0, strlen($id)-4);
	$pass = substr($id, -4);

//	$conn = db_conxxxt();
	//購読解除
	if ($emsg == "") {
		if ( !unregist($conn, $r_id, $pass, $mag_nm) ){
			$emsg = "すでに解除されているようです。";
		}
	}
	//今週のおすすめ取得(最新順)
/* 2002.1.25削除
	if ($emsg == "") {
		$o_list = array();
		osusume_list($conn, &$o_cnt, &$o_list);
	}
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<TITLE>購読解除</TITLE>
<link rel="stylesheet" href="css/melonpan.css">
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
if ($emsg != "") {
	echo "<P><font color=\"#ff0000\">●$emsg";
	echo "</font></P>\n";
}else{
	echo "<P>●" . $mag_nm . "の購読登録を解除しました。</P>\n";
/* 2002.1.25削除
	echo "<hr width=95% size=1><BR>";
	
	if ($o_cnt!=0) {
		echo  "<p align=center>最新おすすめマガジンのご紹介</p>";
		for ( $i = 0 ; $i < $o_cnt ; $i++ ) {
			reset ($o_list);
			$column = each ($o_list);	$mag_id = $column['value'];
			$column = each ($o_list);	$mag_nm = $column['value'];
			$column = each ($o_list);	$mag_hp = $column['value'];
			$column = each ($o_list);	$intros = $column['value'];

			echo  "★<a href=melonpa/mag-detail.php?mag_id=".$mag_id[$i].">".trim($mag_nm[$i])."<BR>" ;
			if (trim($mag_hp[$i]) != "http://") {
				echo "<a href=".trim($mag_hp[$i])." target=_blank>".trim($mag_hp[$i])."</a><BR>" ;
			}
			echo  trim($intros[$i])."<BR><BR>" ;
		}
	}
*/
}
?>
<p align="center">　</p>
<p> 
<a href=index.html target=_top>&lt;&lt;&lt;　めろんぱんトップへ</a><br>
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
