<?php

include("../sub/head.inc");
include("../inc/com_db_func.inc");
include("../sub/csub.inc");

function sel_bg_img( $cd ) {

	if ( $cd == 01 ):
		$res = "img/chi.gif";
	elseif ( $cd == 02 ):
		$res = "img/gaku.gif";
	elseif ( $cd == 03 ):
		$res = "img/kai.gif";
	elseif ( $cd == 04 ):
		$res = "img/toku.gif";
	elseif ( $cd == 05 ):
		$res = "img/wa.gif";
	elseif ( $cd == 06 ):
		$res = "img/en.gif";
	elseif ( $cd == 07 ):
		$res = "img/kurashi.gif";
	else:
		$res = "img/chi.gif";
	endif;

	return $res;
}

/****************************************************************
 Main
****************************************************************/

if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}

if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><!-- #BeginTemplate "/Templates/index.dwt" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
</head>

<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">
<?php	draw_head( ".." );	?>
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
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/kibun.gif" width="118" height="21"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" -->
      <p>　</p>
      <!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" --> 
      <table width="660" border="0" cellspacing="1" cellpadding="5">
        <tr> 
          <td align="left" valign="top"><span class="text01">どんな気持ちで読みたいのか、あなたの気分で検索できるページです。 
            全部で40種類の気分を用意しています。あなたの今のフィーリングは？ あてはまる気分をチェックし、最後にサーチボタンを押してください。 
            各カテゴリに当てはまるメルマガが一覧で表示されます。 </span><br>
            <form action="../melonpa/search_result.php" method="post">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="320" valign="top"> 
                    <table border="0" cellpadding="1" cellspacing="1" width="334">

<?php
$sql = "select * from M_MAG_BG order by bg_id";
$ret = db_exec($sql);
$nrow = pg_numrows($ret);

if ($nrow == 0) {
	$error_msg = $cibi_error_msg_6;
	exit();
}

$title_color[0] = $title_color[1] = "#91c741";
$bg_color[0] = "#e2f0c9";
$bg_color[1] = "#FFFFFF";

$tcnt=0;
$bgcnt=0;

for ($i = 0; $i < $nrow; $i++) {
	$resarray= pg_fetch_array ($ret, $i);
	if (ereg("000$",$resarray[0])) {
		$skey = sprintf("%05s",substr($resarray[0],0,2)*1000+1);
		$ekey = sprintf("%05s",$skey+998);
		$sql = "select count(*) from M_MAGAZINE where (mag_bg_1 between '$skey' and '$ekey' or mag_bg_2 between '$skey' and '$ekey')";
		$sql .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
		$sql .= " and ( mag_pub_stop_flg <> '02' )";
		$sql .= " and ( publisher_id <> 0 )";
		$ret_num = db_exec($sql);
		$resnum= pg_fetch_array ($ret_num, 0);
		if ($tcnt == 4) {
			echo "</table>\n";
			echo "</td>\n";
			echo "<td width=\"320\" valign=\"top\">\n";

			echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"334\">\n";
			echo "<tr>\n";
		}

		echo "<tr height=\"25\">"; 
		echo "<td colspan=\"2\" bgcolor=\"".$title_color[$tcnt % 2]."\" valign=\"middle\" height=\"25\"> \n";
		echo "<a href=\"../melonpa/search_result.php?mode=feeling&mag_bg=".substr($resarray[0],0,2)."000\">\n";
		echo "<img src=\"";
		echo sel_bg_img( substr($resarray[0],0,2) );
		echo "\"width=\"34\" height=\"27\" border=\"0\">\n";
		echo "</a>\n";
		echo "</td>\n";
		echo "<td width=\"301\" height=\"25\" bgcolor=\"#91c741\">"; 
		echo "<div align=\"left\"><font size=\"2\" color=\"#FFFFFF\">\n";
		echo "(".$resnum[0].")";
		echo "</font></div>\n";
		echo "</td>\n";
		echo "</tr>\n";
		$tcnt++;
		$bgcnt = 0;

	} else {
		$sql = "select count(mag_id) from M_MAGAZINE where (mag_bg_1='$resarray[0]' or mag_bg_2='$resarray[0]')";
		$sql .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
		$sql .= " and ( mag_pub_stop_flg <> '02' )";
		$sql .= " and ( publisher_id <> 0 )";
		$ret_num = db_exec($sql);
		$resnum= pg_fetch_array ($ret_num, 0);
		echo "<tr bgcolor=\"".$bg_color[$bgcnt % 2]."\">\n";
		echo "<td width=\"18\" valign=\"top\">\n";
		echo "<input type=\"checkbox\" name=\"mag_bg_chk[]\" value=\"".$resarray[0]."\">\n";
		echo "</td>\n";
		echo "<td colspan=\"2\">";
		echo ;
		echo "<font size=\"2\">" .trim($resarray[1])." (".$resnum[0].")</font>\n";
		echo "</td>\n</tr>\n";
		$bgcnt++;
	}
}
if (!$ret) {
	$error_msg = $cibi_error_msg_8;
	exit();
}

echo "<INPUT TYPE=\"hidden\" NAME=\"mode\" VALUE=\"feeling\">\n";

?>
                      <tr> 
                        <td class="catcont" colspan="3">　</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <hr>
              <input type="hidden" name="mode" value="feeling">
              <!-- 一覧表ここまで-->
              <input type="hidden" name="mail_addr">
              <input type="hidden" name="reader_id">
              <input type="hidden" name="reader_pswd">
              <input type="submit" value="　検索　" name="submit">
            </form>
          </td>
        </tr>
      </table>
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
