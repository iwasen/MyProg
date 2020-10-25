<?php
include("../inc/com_db_func.inc");
include( "../sub/head.inc");
include("../sub/csub.inc");

/****************************************************************

****************************************************************/
function sel_catg_img( $cd ){

	$res = "" ;

	if 		( $cd == 01 ):	{$res = "<td width=30><img src=\"img/news.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 11 ):	{$res = "<td width=30><img src=\"img/business.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 02 ):	{$res = "<td width=30><img src=\"img/enter.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 12 ):	{$res = "<td width=30><img src=\"img/recruit.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 03 ):	{$res = "<td width=30><img src=\"img/ball.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 13 ):	{$res = "<td width=30><img src=\"img/com.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 04 ):	{$res = "<td width=30><img src=\"img/paint.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 14 ):	{$res = "<td width=30><img src=\"img/pen.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 05 ):	{$res = "<td width=30><img src=\"img/beauty.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 15 ):	{$res = "<td width=30><img src=\"img/info.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 06 ):	{$res = "<td width=30><img src=\"img/shopping.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 16 ):	{$res = "<td width=30><img src=\"img/town.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 07 ):	{$res = "<td width=30><img src=\"img/heart.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 17 ):	{$res = "<td width=30><img src=\"img/school.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 08 ):	{$res = "<td width=30><img src=\"img/travel.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 18 ):	{$res = "<td width=30><img src=\"img/meeting.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 09 ):	{$res = "<td width=30><img src=\"img/grume.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 19 ):	{$res = "<td width=30><img src=\"img/science.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 10 ):	{$res = "<td width=30><img src=\"img/hobby.gif\" width=30 height=26></td>\n"; }
	elseif 	( $cd == 20 ):	{$res = "<td width=30><img src=\"img/govorment.gif\" width=30 height=26></td>\n"; }
	else:					{$res = "<td width=30><img src=\"grume.gif\" width=30 height=26></td>\n"; }
	endif;

	return $res;


}
/****************************************************************
 Main
****************************************************************/
//$gConn = db_conxxxt();

if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><!-- #BeginTemplate "/Templates/index.dwt" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
</head>

<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">
<?php draw_head("..");	?>
<table width="753" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"> 
      <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td width="21"><!-- #BeginEditable "1" --><img src="img/g1.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21"><!-- #BeginEditable "3" --><img src="img/g3.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557"><!-- #BeginEditable "title1" --><!-- #EndEditable --></td>
          <td width="21">　</td>
        </tr>
        <tr> 
          <td width="21"><!-- #BeginEditable "2" --><img src="img/g2.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21" bgcolor="#90c84b"><!-- #BeginEditable "4" --><img src="img/g4.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/category.gif" width="89" height="21"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" --><br>
            <!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" --><span class="text01">シンプルにジャンルで検索できるページです。カテゴリは全部で20種類。全部あわせて273項目あります。<br>
            </span><br>
            <table border="0" cellspacing="0" cellpadding="1" bgcolor="#91c741" width="557">
              <tr> 
                <td class="text9ptnoColor"> 
                  <table border="0" cellpadding="0" cellspacing="1" align="center" width="555">
<?php

$sql = "select * from M_MAG_CATG order by catg_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);

if ($nrow == 0) {
	$error_msg = $cibi_error_msg_6;
	exit();
}

$catg_tr0 = "<TR align=left valign=top bgcolor=\"#91c741\">\n";
$catg_tr0x = "<td bgcolor=\"#e2f0c9\" width=\"276\">\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>\n";
$catg_td0 = "<td bgcolor=\"#e2f0c9\">\n";  // (大見出し：マガジン数)
//$catg_td0 = "<td bgcolor=\"#e2f0c9\"><font class=\"ub\">　</font>\n";   1文字スペース有り(大見出し：マガジン数)
$catg_td1 = "</TD>\n";
$catg_tr1x = "</tr>\n</table>\n</td>\n";
$catg_tr1 = "</tr>\n";


$catg_tr2 = "<TR align=left valign=top bgcolor=\"#FFFFFF\">\n";       // 小見出し

//$catg_td2 = "<TD width=365><P class=sFontH><FONT size=\"2\">\n";
//$catg_td3 = "</FONT></P></TD>\n";
$catg_td2 = "<TD width=276><table width=276 border=0 cellspacing=0 cellpadding=5><TR><TD><FONT size=2>\n";
$catg_td3 = "</FONT></TD></TR></TABLE></TD>\n";

$catg_tr3 = "</TR>\n";

$catg = array();

for ($i = 0; $i < $nrow; $i++) {
 $resarray= pg_fetch_array ($result, $i);

	if ( ereg("00$",$resarray[0]) ) {
/* **
		$catg_title[substr($resarray[0],0,2)] = "<font class=\"ub\"><b><A HREF=\"../melonpa/search_result.php?mode=catg&catg_id=$resarray[0]&reader_id=$reader_id&reader_pswd=".trim($reader_pswd)."&mail_addr=".trim($mail_addr)."\">".trim($resarray[1])."</A></b></font><font size=\"1\" color=\"black\">($resarray[2])</font>\n";
*/
		$catg_title[substr($resarray[0],0,2)] = "<font class=\"ub\"><b><A HREF=\"../melonpa/search_result.php?mode=catg&catg_id=$resarray[0]\">".trim($resarray[1])."</A></b></font><font size=\"1\" color=\"black\">($resarray[2])</font>\n";
	} else {
		if ($resarray[2] != 0) {
/* **
			$catg[substr($resarray[0],0,2)] .= "<A HREF=\"../melonpa/search_result.php?mode=catg&catg_id=$resarray[0]&reader_id=$reader_id&reader_pswd=".trim($reader_pswd)."&mail_addr=".trim($mail_addr)."\">".trim($resarray[1])."</A>(".$resarray[2].")| \n";
*/
			$catg[substr($resarray[0],0,2)] .= "<A HREF=\"../melonpa/search_result.php?mode=catg&catg_id=$resarray[0]\">".trim($resarray[1])."</A>(".$resarray[2].")| \n";

		}
	}
}

echo $catg_tr0;
echo $catg_tr0x; echo sel_catg_img(01); echo $catg_td0; echo $catg_title["01"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(11); echo $catg_td0; echo $catg_title["11"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
/*
<tr align="left" valign="top" bgcolor="#91c741"> 
  <td bgcolor="#e2f0c9" width="276"> 
    <table border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td>
		  <img src="img/news.gif" width="30" height="26">
		</td>
        <td bgcolor="#e2f0c9">
		  <font class="ub"><b><a href="#">ニュース</a></b></font>
		  <font size="1" color="black">(126)</font>
		</td>
      </tr>
    </table>
  </td>
  <td width="276" bgcolor="#e2f0c9"> 
    <table border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td>
		  <img src="img/business.gif" width="30" height="26">
		</td>
        <td bgcolor="#e2f0c9">
		  <font class="ub"><b><a href="#">企業・ビジネス</a></b></font>
		  <font color="black" size="1">(52)</font>
		</td>
      </tr>
    </table>
  </td>
</tr>
*/
echo $catg_tr2.$catg_td2.$catg["01"].$catg_td3.$catg_td2.$catg["11"].$catg_td3.$catg_tr3;

echo $catg_tr0;
echo $catg_tr0x; echo sel_catg_img(02); echo $catg_td0; echo $catg_title["02"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(12); echo $catg_td0; echo $catg_title["12"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
echo $catg_tr2.$catg_td2.$catg["02"].$catg_td3.$catg_td2.$catg["12"].$catg_td3.$catg_tr3;

echo $catg_tr0;
echo $catg_tr0x; echo sel_catg_img(03); echo $catg_td0; echo $catg_title["03"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(13); echo $catg_td0; echo $catg_title["13"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
echo $catg_tr2.$catg_td2.$catg["03"].$catg_td3.$catg_td2.$catg["13"].$catg_td3.$catg_tr3;

echo $catg_tr0;
echo $catg_tr0x; echo sel_catg_img(04); echo $catg_td0; echo $catg_title["04"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(14); echo $catg_td0; echo $catg_title["14"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
echo $catg_tr2.$catg_td2.$catg["04"].$catg_td3.$catg_td2.$catg["14"].$catg_td3.$catg_tr3;

echo $catg_tr0;
echo $catg_tr0x; echo sel_catg_img(05); echo $catg_td0; echo $catg_title["05"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(15); echo $catg_td0; echo $catg_title["15"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
echo $catg_tr2.$catg_td2.$catg["05"].$catg_td3.$catg_td2.$catg["15"].$catg_td3.$catg_tr3;

echo $catg_tr0;
echo $catg_tr0x; echo sel_catg_img(06); echo $catg_td0; echo $catg_title["06"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(16); echo $catg_td0; echo $catg_title["16"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
echo $catg_tr2.$catg_td2.$catg["06"].$catg_td3.$catg_td2.$catg["16"].$catg_td3.$catg_tr3;

echo $catg_tr0;
echo $catg_tr0x; echo sel_catg_img(07); echo $catg_td0; echo $catg_title["07"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(17); echo $catg_td0; echo $catg_title["17"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
echo $catg_tr2.$catg_td2.$catg["07"].$catg_td3.$catg_td2.$catg["17"].$catg_td3.$catg_tr3;

echo $catg_tr0;
echo $catg_tr0x; echo sel_catg_img(08); echo $catg_td0; echo $catg_title["08"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(18); echo $catg_td0; echo $catg_title["18"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
echo $catg_tr2.$catg_td2.$catg["08"].$catg_td3.$catg_td2.$catg["18"].$catg_td3.$catg_tr3;

echo $catg_tr0;
echo $catg_tr0x;
echo "<td width=30><img src=\"img/grume.gif\" width=30 height=26></td>\n";
echo $catg_td0; echo $catg_title["09"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(19); echo $catg_td0; echo $catg_title["19"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
echo $catg_tr2.$catg_td2.$catg["09"].$catg_td3.$catg_td2.$catg["19"].$catg_td3.$catg_tr3;

echo $catg_tr0;
echo $catg_tr0x; echo sel_catg_img(10); echo $catg_td0; echo $catg_title["10"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr0x; echo sel_catg_img(20); echo $catg_td0; echo $catg_title["20"]; echo $catg_td1; echo $catg_tr1x;
echo $catg_tr1;
echo $catg_tr2.$catg_td2.$catg["10"].$catg_td3.$catg_td2.$catg["20"].$catg_td3.$catg_tr3;

/*
ocifreestatement($stmt);

if (!$ret) {
	ocifreestatement($stmt);
	$error_msg = $cibi_error_msg_8;
	exit();
}
*/
?>
                  </table>
                </td>
              </tr>
            </table>
            <br>
            <br>
            <br>
            <!-- #EndEditable --></td>
          <td width="21" bgcolor="#ffffd3">　</td>
        </tr>
        <tr> 
          <td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
          <td width="21" bgcolor="#90c84b">　</td>
          <td width="557" bgcolor="#90c84b">　</td>
          <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
        </tr>
<?php draw_footer( ".." ); ?>
      </table>
    </td>
  </tr>
</table>
</body>
<!-- #EndTemplate --></html>
