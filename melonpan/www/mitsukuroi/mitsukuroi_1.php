<?php
include ( "../sub/head.inc");
/* from mitsukuroi_0---------------------------------------------------------- */
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");
include ("$compath/sql_mpai.inc");
/* -------------------------------------------------------------------------- */
/* from mitsukuroi_1---------------------------------------------------------- */
function botdisp ($melonpai_id) {
	if (strlen ($melonpai_id) != 0) {
		print "<INPUT TYPE=\"submit\" NAME=\"TaiBot3\" VALUE=\"この隊員にお願いする\">\n";
	}
}

function botdisp1 ($max_taicnt,$taiinno) {
	if ($taiinno > 5) {
		print "<INPUT TYPE=\"submit\" NAME=\"BeforeBot\" VALUE=\"前頁\">\n";
	}
}

function botdisp2 ($max_taicnt,$taiinno) {
	if ($max_taicnt < $taiinno) {
		print "<INPUT TYPE=\"submit\" NAME=\"AfterBot\" VALUE=\"次頁\">\n";
	}
}

/* -------------------------------------------------------------------------- */
/****************************************************************
	Main (from melonpa/mitsukuroi_0)
****************************************************************/
//echo $max_taicnt."::";
//echo $taiinno;

if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}

if ($BeforeBot) {
	if (($taiinno - 6) < 0) {
		$taiinno = 0;
	} else {
		$taiinno = $taiinno - 6;

	}
} elseif ($AfterBot) {

	if (($taiinno + 1) > $max_taicnt) {
		$taiinno = $max_taicnt - 5;
	}
} else {
	$sql = "SELECT count(*) as MAXCNT from m_melonpai where melonpai_m_flg = '0' "; /* 要件No30 */
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$max_taicnt = $fetch->maxcnt;
	} else {
		$max_taicnt = 0;
	}
	$taiinno = 0;
}
//include ("mitsukuroi_1.php");
/****************************************************************
	Main (from mitsukuroi_1)
****************************************************************/
$sql = "SELECT * from m_melonpai where melonpai_m_flg = '0' order by melonpai_id";  /* 要件No30 */
$result = db_exec($sql);
$nrow = pg_numrows($result);
$localtai = 0;
$localcnt = 0;

$m_wk = array();
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$melonpai_nic = $fetch->melonpai_nic;
	$melonpai_pro = $fetch->melonpai_pro;
	$melonpai_id = $fetch->melonpai_id;
	$localtai = $localtai + 1;
	#if ($localtai > $taiinno) {
	#$taiinno = $taiinno + 1;
	#$localcnt = $localcnt + 1;

	if ($melonpai_nic == "")
		$melonpai_nic = "<br>";

	if ($melonpai_pro == "")
		$melonpai_pro = "<br>";

	$m_wk[$localtai] = "<tr bgcolor=\"#FFFFFF\"> \n";
	$m_wk[$localtai] .= "<td width=\"150\" class=\"text9pt\"> \n";
	$m_wk[$localtai] .= "<div align=\"center\">$melonpai_nic</div>\n";
	$m_wk[$localtai] .= "</td>\n";
	$m_wk[$localtai] .= "<td width=\"336\" class=\"text9pt\"><span class=\"text01\">$melonpai_pro</span></td>\n";
	$m_wk[$localtai] .= "<td width=\"100\">\n";
	$m_wk[$localtai] .= "<form name = \"TaiForm5\" method=\"post\" action = \"mitsukuroi_2.php\">\n";
	$m_wk[$localtai] .= "<input type=\"hidden\" value=\"". trim($mail_addr) ."\" name=\"mail_addr\">\n";
	$m_wk[$localtai] .= "<input type=\"hidden\" value=\"". trim($reader_id) ."\" name=\"reader_id\">\n";
	$m_wk[$localtai] .= "<input type=\"hidden\" value=\"". trim($reader_pswd)."\" name=\"reader_pswd\">\n";
	$m_wk[$localtai] .= "<input type=\"hidden\" value=\"". $melonpai_id ."\" name=\"melonpai_id\">\n";
	$m_wk[$localtai] .= "<input type=\"image\" src=\"img/mitsurotte_b.gif\" width=\"96\" height=\"24\" border=0>\n";
	$m_wk[$localtai] .= "</form>\n";
	$m_wk[$localtai] .= "</td>\n";
	$m_wk[$localtai] .= "</tr>\n";
	$m_wk[$localtai] .= "<tr bgcolor=\"#FFFFFF\"> \n";
	$m_wk[$localtai] .= "<td colspan=\"3\" class=\"text9pt\">\n";
	$m_wk[$localtai] .= "<hr size=\"2\">\n";
	$m_wk[$localtai] .= "</td>\n";
	$m_wk[$localtai] .= "</tr>\n";
	if ($localcnt == 5) {
		break;
	}
}

/* 並べ変え --------------------------------- */
$list = "<table width=541 border=0 cellspacing=0 cellpadding=4 bgcolor=\"#FFFFFF\" height=77>";
mt_srand((double)microtime()*1000000);
$st = mt_rand( 1, $localtai );
for ( $i=$st; $i < $localtai + $st ; $i++){
	if ($i > $localtai)
		$col = $i - $localtai;
	else
		$col = $i;
	$list .= $m_wk[$col];
}
$list .= "</table>\n";
/* ------------------------------------------ */

//include("tmpl_html/mitsukuroi_1.html");
/* -------------------------------------------------------------------------- */

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
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/pitari.gif" width="271" height="21" alt="私にぴったりのメルマガをみつくろって"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" --><br>
            <!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" -->
      <table width="550" border="0" cellspacing="1" cellpadding="5">
        <tr> 
          <td ><span class="text01">メルマガを探すのに悩んだとき、もっといいメルマガを見つけたいとき、<br>
            そんな時、「<b><font color="336600">メルマガナビ</font></b>」がお手伝いします。</span> 
            <p>
            <font color="#99ccff"><span class="text01"><br>
            </span></font>
            <table border="1" cellpadding="2" cellspacing="0" width="541" bgcolor="#91c741">
              <tr> 
                <td><font color="#99ccff"><span class="text01"><font color="#ff9900">■</font><font color="#000000"><b>メルマガナビとは？</b></font></span></font></td>
              </tr>
            </table>
            <p></p>
            <p>「こんなメルマガが読みたい！」ってメールを送ると、<br>
              このメルマガナビたちが読みたいメルマガを適当にチョイスしてくれます。<br>
               現在３人のメルマガナビが、あなたからの依頼を待っています。<br>あなたに合い
              そうなタイプのナビを選ぶのがコツ。<br>それぞれ違うタイプの３人から、１人選んで
              くださいね。</p>
            <p>
            <table border="1" cellpadding="2" cellspacing="0" width="541" bgcolor="#91c741">
              <tr> 
                <td><font color="#99ccff"><span class="text01"><font color="#ff9900">■</font><font color="#000000"><b>あなたのメルマガナビたち 
                  </b></font></span></font></td>
              </tr>
            </table>
            <p></p>
            <p><span class="text01">私たちがあなたのメルマガをお選びします！<br>たくさんのメルマガを一生懸命読んで待っていますので、<br>どうぞ遠慮なくご相談ください。            <p></p>
              <font size="2">※みつくろい依頼（メルマガを探す目的）以外のお問い合わせはご遠慮ください。<br>
              　 お問い合わせは<a href="http://www.melonpan.net/help/">ＨＥＬＰページ</a>にてお待ちしております。</font></span></p>
<!--
            <table border="0" cellpadding="1" cellspacing="0" bgcolor="#91c741" width="541">
              <tr> 
                <td>
                  <table border="0" cellspacing="1" cellpadding="2" height="100%" bgcolor="#FFFFFF" width="100%">
                    <tr>
                      <td><font size="2" color="#006699"><img src="img/i1.gif" width="12" height="12">朝倉　司</font> 
                        <br>
                        <br>
                        <font size="2"> 主に創作文芸メルマガが得意分野です！<br>
                        </font></td>
                      <td><font size="2" color="#990033"><img src="img/i2.gif" width="12" height="12">プリンスタ帳</font> 
                        <br>
                        <br>
                        <font size="2">明日できることは今日しない正義の味方<br>
                        </font></td>
                      <td><font size="2" color="#006699"><img src="img/i1.gif" width="12" height="12" border="0">権藤くじら</font> 
                        <br>
                        <br>
                        <font size="2">社会問題や自然環境の話題は私におまかせ<br>
                        </font></td>
                      <td><font size="2" color="#006699"><img src="img/i1.gif" width="12" height="12">ヨウカン</font> 
                        <br>
                        <br>
                        <font size="2">ワイン、グルメ、旅行などが好きです。<br>
                        </font></td>
                      <td><font size="2" color="#990033"><img src="img/i2.gif" width="12" height="12">ルウルウ</font> 
                        <br>
                        <br>
                        <font size="2">面白いものを厳しく貪欲に探求いたします。</font></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
-->
            <p><br>
            <table border="0" cellpadding="0" cellspacing="0" bgcolor="#91c741" width="541">
              <tr> 
                <td align="center" valign="middle"> 
                  <table width="539" border="0" cellspacing="1" cellpadding="2">
                    <tr> 
                      <td width="150" class="text9pt" bgcolor="#e2f0c9"> 
                        <center>
                          <font color="#336600">メルマガナビ</font> 
                        </center>
                      </td>
                      <td class="text9pt" width="427" bgcolor="#e2f0c9"> 
                        <center>
                          <font color="#336600">プロフィール</font>
                        </center>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
<?php echo $list; ?>
            <br>
<a href="javascript:history.back()">
<img src="../common/back.gif" width="41" height="40" alt="Back" border="0"></a> <br>
            <br>
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
