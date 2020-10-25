<?php
include("../sub/csub.inc");
include("../sub/head.inc");
include("../inc/com_db_func.inc");

//db_conxxxt();

/* 読者ページ共通 ------ */
if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}else{
	pass_check($gConn,trim($mail_addr),trim($reader_pswd));
}

/* ------ */
$rank_msg = "オススメRanking";
$cmnt = "";
if ( strftime("%M", time()) > 15){
	$mm = round(strftime("%m",time()),0);
	$dd = round(strftime("%d",time()),0);
	$hh = round(strftime("%H",time()),0);
}else{
	$mm = round(strftime("%m",time() - (60*60)),0);
	$dd = round(strftime("%d",time() - (60*60)),0);
	$hh = round(strftime("%H",time() - (60*60)),0);
}
$koushinbi = $mm."月".$dd."日 ".$hh."時現在";

/* ページと表示行 */
if ($p_index == '') {
	$p_index = 0;
}
$sta = $p_index * 50;

/* データ取得（全件）*/
/*
$sql = "select R.ranking ,M.mag_id, M.mag_nm, R.r_point from T_RANKING_LIST R, M_MAGAZINE M "
	 . " where rank_id='12' and R.mag_id = M.mag_id "
	 . " order by R.ranking, M.mag_id";
ソート順変更 */
$sql = "select R.RANKING ,M.mag_id, M.mag_nm, R.r_point "
	 . "  from T_RANKING_LIST R, M_MAGAZINE M "
	 . " where rank_id='12' "
	 . "   and R.mag_id = M.mag_id "
	 . " order by R.RANKING, M.LAST_SEND_DT desc";
$ret = db_exec($sql);
$nrows = pg_numrows($ret);
/* **
$stmt = ociparse($gConn,$sql);
$ret = ociexecute($stmt);
*/
if (!$ret) {
//	ocifreestatement($stmt);
	$error_msg = $cibi_error_msg_6;
//	db_logoff();
	exit();
}
/* **
$nrows = OCIFetchStatement ($stmt, &$results);
ocifreestatement($stmt);
if (!$ret) {
	ocifreestatement($stmt);
	$error_msg = $cibi_error_msg_8;
	db_logoff();
	exit();
}
*/
?>
<html>
<!-- #BeginTemplate "/Templates/index.dwt" --> 
<head>
<!-- #BeginEditable "doctitle" --> 
<title>めろんぱん || よりすぐりメルマガサイト</title>
<!-- #EndEditable --> 
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>
<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">
<?php	draw_head( ".." );	?>
<TABLE width="753" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD align="center"> 
      <TABLE width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <TR> 
          <TD width="21"><IMG src="../search/img/g1.gif" width="21" height="21"></TD>
          <TD width="21"><IMG src="../search/img/g3.gif" width="21" height="21"></TD>
          <TD width="557"></TD>
          <TD width="21">　</TD>
        </TR>
        <TR> 
          <TD width="21"><IMG src="../search/img/g2.gif" width="21" height="21"></TD>
          <TD width="21" bgcolor="#90c84b"><IMG src="../search/img/g4.gif" width="21" height="21"></TD>
<TD>
<TABLE width="557" border="0" cellspacing="0" cellpadding="0" align="center">
<TR>
<TD width="157" bgcolor="#94cd3e"><IMG src="../search/img/rankingtitle.gif" width="135" height="21" alt="いろいろRanking"></TD>
<TD width="400" bgcolor="#94cd3e"><div align="right" ><font size=2 color="000000">
<?php
echo $koushinbi;
?>
</font></div></TD>
</TR>
</TABLE>
</TD>
          <TD width="21"><IMG src="../common/c_rihgt_top.gif" width="21" height="21"></TD>
        </TR>
        <TR bgcolor="#ffffd3"> 
          <TD colspan="4" bgcolor="#ffffd3" height="21"> <P>　</P></TD>
        </TR>
        <TR> 
          <TD width="21" bgcolor="#ffffd3">　</TD>
          <TD width="21" bgcolor="#ffffd3">　</TD>
          <TD width="557" bgcolor="#ffffd3">
            <TABLE width="557" border="0" cellspacing="0" cellpadding="0">
              <TR>
                <TD valign="top"> 
                  <TABLE border="0" cellspacing="0" cellpadding="5" width="540">

<TR>
<TD align="right" colspan="2"><font size="2" style="font size=8pt"><?php echo $cmnt; ?></font></TD>
</TR>
<TR>
<TD width=28  bgcolor="#e2f0c9" align="center">  <IMG src="../search/img/kanmuri.gif" width=18 height=20></TD><TD width=502 bgcolor="#e2f0c9" ><b>  <?php echo $rank_msg; ?> </b></TD>
</TR>

                  </TABLE>
                </td>
              </tr>

              <TR><TD height=6 width="557" ></td></tr>

<TR>
<TD valign="top"> 
<TABLE border="0" cellspacing="0" cellpadding="5" width="540">
<?php
if ( $nrows == 0 ){
		echo "<BR>";
		echo "ただいまランキングを更新中です。";
}else{
	for ( $i = $sta ; $i < $nrows && $i < $sta+50 ; $i++ ) {
		$resarray= pg_fetch_array ($ret, $i);
/* **
		reset ($results);
		$column = each ($results);	$ranking = $column['value'];
		$column = each ($results);	$mag_id = $column['value'];
		$column = each ($results);	$mag_nm = $column['value'];
		$column = each ($results);	$point = $column['value'];
*/
		$ranking[$i] = $resarray[0];
		$mag_id[$i] = $resarray[1];
		$mag_nm[$i] = $resarray[2];
		$point[$i] = $resarray[3];
		$next_mag = $i ;
		if ( $ranking[$i] > 10 ){	break;	}
		$next_mag++;

		$point = number_format($point[$i])." 回";
		if ( $i % 2 ) $bgcolor = "\"#FFFFFF\""; else $bgcolor = "\"#FFFF99\"" ;

		echo "<TR>";
		echo "<TD width=30 align=\"right\" bgcolor=$bgcolor><b><font color=\"#FF3300\">$ranking[$i]</font></b></TD>";
		echo "<TD width=400 align=\"left\" bgcolor=$bgcolor nowrap><b><font color=\"#336600\">";
		echo "<a href=\"../melonpa/mag-detail.php?mag_id=".$mag_id[$i]."\">".$mag_nm[$i]."</a></font></b></TD>";
		echo "<TD width=70 align=\"right\" bgcolor=$bgcolor><b><font color=\"#336600\">$point</font></b></TD>";
		echo "</TR>";
		
	}
}
?>
</TABLE>
</td>
</tr>

<TR><TD height=10 width="557" ></td></tr>
<TR>
<TD valign="top"> 
<TABLE border="0" cellspacing="0" cellpadding="5" width="540">

<?php
for ( $i = $next_mag; ($i < $nrows && $i < $sta+50) ; $i++ ) {
	$resarray= pg_fetch_array ($ret, $i);
/* **
	reset ($results);
	$column = each ($results);	$ranking = $column['value'];
	$column = each ($results);	$mag_id = $column['value'];
	$column = each ($results);	$mag_nm = $column['value'];
	$column = each ($results);	$point = $column['value'];
*/
	$ranking[$i] = $resarray[0];
	$mag_id[$i] = $resarray[1];
	$mag_nm[$i] = $resarray[2];
	$point[$i] = $resarray[3];

	$point = number_format($point[$i])." 回";
	echo "<TR>";
	echo "<TD width=30 align=\"right\">$ranking[$i]</TD>";
	echo "<TD width=395 align=\"left\" nowrap>";
	echo "<a href=\"../melonpa/mag-detail.php?mag_id=".$mag_id[$i]."\">".$mag_nm[$i] ."</a></TD>";
	echo "<TD width=75 align=\"right\">$point</TD>";
	echo "</TR>";
	if ( (($i+1) % 5) == 0 ){
		echo "</TABLE>";
           echo "</td>";
		echo "</tr>";
		echo "<TR><TD height=10 width=557 ></td></tr>";
		echo "<TR>";
		echo "<TD valign=\"top\">"; 
		echo "<TABLE border=0 cellspacing=0 cellpadding=5 width=540>";
	}
}

echo "<form action=\"kensaku-list_ranking2.php\" method=\"post\">";
echo "<tr><td align=\"center\">";
if ($p_index != 0){
	echo "<input type=\"button\" name=\"action\" value=\" 前の50件 \" onClick=\"history.back()\">　";
}
if ( $nrows > ($p_index + 1) * 50 ){
	echo "<input type=\"submit\" value=\" 次の50件 \">";
}
echo "<input type=\"hidden\" value=".$rank_id." name=\"rank_id\">";
echo "<input type=\"hidden\" value=".($p_index+1)." name=\"p_index\">";
echo "</td></tr>";
echo "</form>";

?>
</TABLE>
</td>
</tr>
            </TABLE>
            </TD>
          <TD width="21" bgcolor="#ffffd3">　</TD>
        </TR>
        <TR bgcolor="#ffffd3"> 
          <TD colspan="4" bgcolor="#ffffd3" height="21"> <P>　</P></TD>
        </TR>
        <TR> 
          <TD width="21"><IMG src="../common/c_left_bottom.gif" width="21" height="21"></TD>
          <TD width="21" bgcolor="#90c84b">　</TD>
          <TD width="557" bgcolor="#90c84b">　</TD>
          <TD width="21"><IMG src="../common/c_right_bottom.gif" width="21" height="21"></TD>
        </TR>
<?php draw_footer( ".." ); ?>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</BODY>
</HTML>

