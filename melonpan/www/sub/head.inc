<?php
/*==========================================================*/
/*	広告バナーの選択								*/
/*==========================================================*/
function sel_banner2($kind, $c_pass) {
	if (($host = $_ENV['MELONPAN_DB']) == '')
		$host = 'localhost';
	$con = pg_Conxxxt ("host=$host port=5432 dbname=melonpan user=postgres");
	$sql = "SELECT * FROM T_BANNER WHERE date_trunc('hour', timestamp 'now') between BN_start_dt and BN_end_dt and BN_kind = '$kind'";
	$result = pg_exec ($con, $sql);
	$nrow = pg_numrows($result);
	for ($bn_cnt = 0; $bn_cnt < $nrow; $bn_cnt++) {
		$fetch = pg_fetch_object($result, $bn_cnt);
		$abn_send_url[$bn_cnt] = $fetch->bn_send_url;
		$abn_picture[$bn_cnt] = $fetch->bn_picture;
	}
	if ($bn_cnt > 0) {
		if ($bn_cnt == 1) {
			$bn_send_url = $abn_send_url[0];
			$bn_picture = $abn_picture[0];
		} else {
			mt_srand((double)microtime()*1000000);
			$j = mt_rand(0, $bn_cnt - 1);
			$bn_send_url = $abn_send_url[$j];
			$bn_picture = $abn_picture[$j];
		}
		print "<a href=\"" . $bn_send_url . "\" target=\"_blank\">";
		print "<img src=\"" . $c_pass . "/sub/banner_pict.php?bn_picture=" . $bn_picture . "\" id=\"pict\" alt=\"banner\" border=\"0\"></a>";
	}
}
/*==========================================================*/
/*	ヘッダ表示（TOPページ以外）								*/
/*==========================================================*/
function draw_head( $c_pass ){
	draw_header($c_pass, 1);
}

/*==========================================================*/
/*	ヘッダ表示（めろんパイ用）								*/
/*==========================================================*/
function draw_head2( $c_pass ){
	draw_header($c_pass, 0);
}

function draw_header( $c_pass, $banner ){
?>
<table width=753 border=0 cellspacing=0 cellpadding=0 height=140>
<tr>
<td rowspan=2 width=210 valign=bottom><a href="<?=$c_pass?>/index.html" onfocus="blur()"><img src="<?=$c_pass?>/common/tm1.gif" border="0" width=210 height=71 alt="よりすぐりメルマガサイト　めろんぱん"></a></td>
<td width=543 height=65 align="right">
<? if ($banner == 1) { ?>

<!-- <a href="http://actionclick.net/cgi-bin/drms/find/T7fff88dcX/" target="_blank"> -->
<!-- <img src="../img/findjob3.gif" border="0"></a> -->
<!-- トップ広告バナーランダム表示 -->
<?= sel_banner2(1, $c_pass); ?>
<? } ?>
</td>
 </tr>
  <tr>
    <td width="543" height="6"><img src="<?=$c_pass?>/common/line.gif" width="543" height="6"></td>
  </tr>
  <tr>
    <td width="210" height="66"><a href="<?=$c_pass?>/index.html" onfocus="blur()"><img src="<?=$c_pass?>/common/tm2.gif" border="0" width="210" height="66" alt="よりすぐりメルマガサイト　めろんぱん"></a></td>
    <td width="543" height="66">
      <table width="543" border="0" cellspacing="0" cellpadding="0" height="66" bgcolor="#99cc66">
        <tr>
          <td>
            <table width="365" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="5">
                  <table width="364" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="147" valign="middle" height="33"><img src="<?=$c_pass?>/common/melmagasearch.gif" width="147" height="33" alt="メルマガサーチ"></td>
                      <td bgcolor="#b8e185" width="216" height="33">
                        <table width="216" border="0" cellspacing="0" cellpadding="0" height="33">
                          <tr>
                            <td height="1" bgcolor="#58a01e"><img src="<?=$c_pass?>/common/spacer.gif" width="216" height="1"></td>
                          </tr>
                          <tr>
<form action="<?=$c_pass?>/melonpa/search_result.php" method="post">
                            <td height="32" valign="middle" align="center">
<INPUT type="hidden" name="mode" value="word">
<INPUT type="text" name="keyword" size="<? browser("30", "19") ?>">
<INPUT type="submit" name="Submit22" value="検索">
                            </td>
</form>
                          </tr>
                        </table>
                      </td>
                      <td width="1" bgcolor="#68a01e" height="33"><img src="<?=$c_pass?>/common/head_bargif.gif" width="2" height="33"></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
<td width="79"><a href="<?=$c_pass?>/search/kensaku-category.php" onfocus="blur()"><img src="<?=$c_pass?>/common/category.gif" width="79" height="26" border="0" alt="カテゴリ"></a></td>
<td width="68"><a href="<?=$c_pass?>/search/kensaku-muke.php" onfocus="blur()"><img src="<?=$c_pass?>/common/watashimuke.gif" width="68" height="26" border="0" alt="私向け"></a></td>
<td width="67"><a href="<?=$c_pass?>/search/kensaku-kibun.php" onfocus="blur()"><img src="<?=$c_pass?>/common/kibun.gif" width="67" height="26" border="0" alt="気分"></a></td>
<td width="68"><a href="<?=$c_pass?>/mitsukuroi/mitsukuroi_1.php" onfocus="blur()"><img src="<?=$c_pass?>/common/mitsukuroi.gif" width="68" height="26" border="0" alt="みつくろい"></a></td>
<td width="82"><a href="<?=$c_pass?>/search/kensaku-ranking.php" onfocus="blur()"><img src="<?=$c_pass?>/common/ranking.gif" width="83" height="26" border="0" alt="Ranking"></a></td>
              </tr>
            </table>
          </td>
<td width="51"><a href="<?=$c_pass?>/melonpa/MelonpanRef.php" onfocus="blur()"><img src="<?=$c_pass?>/common/mypage.gif" width="51" height="66" border="0" alt="My Page"></a></td>
<td width="51"><a href="<?=$c_pass?>/help/index.html" onfocus="blur()"><img src="<?=$c_pass?>/common/help.gif" width="51" height="66" border="0" alt="Help"></a></td>
<td width="63"><a href="<?=$c_pass?>/index.html" onfocus="blur()"><img src="<?=$c_pass?>/common/home2.gif" width="63" height="66" border="0" alt="TOP"></a></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#61a31b" height="3"><img src="<?=$c_pass?>/common/spacer.gif" width="1" height="3"></td>
  </tr>
</table>
<br>
<?php
}
?>
<?php
/*==========================================================*/
/*	フッタ表示（ＴＯＰ以外の読者ページ）					*/
/*==========================================================*/
function draw_footer( $c_pass ){
?>
<tr>
  <td colspan="4">
    <div align="center"><br>
<br>
<?php
// <!-- VC active -->
// <SCRIPT LANGUAGE="JavaScript">
// <!--
// // ValueParameters
// ValueHost = "hs0037310";
// ValueID = Math.random();
// ValueLoaded = false;
// ValueVersion = "1.0";
// ValueWidth = 468;
// ValueHeight = 61;
// //-->
// </SCRIPT>
// <SCRIPT
// LANGUAGE="Javascript" SRC="http://oz.valueclick.ne.jp/jsmaster"></SCRIPT>
// <SCRIPT LANGUAGE="JavaScript">
// <!--
// if (ValueLoaded) ValueShowAd();
// //-->
// </SCRIPT>
// <!-- vc active -->
echo sel_banner($c_pass);
?>
      <br>
      <br>
      <font size="1">※<a href="<?php echo $c_pass; ?>/index.html">めろんぱん</a>は、（株）○○○○○○の事業理念「発信する生活者を支援する」に基づいて運営されています。</font><br>
<table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td> 
      <hr noshade size="1">
    </td>
  </tr>
  <tr> 
    <td valign="top"> 
      <div align="center"><span style="font-size: 9pt; font-weight: normal;">Copyright 
        2006 xx xxxxx Inc. All Rights Reserved. 本サイトは、<a href="http://www.xxxxxxx.co.jp/" target="_blank">株式会社○○○○○○</a>により運営されております。</span></div>
    </td>
  </tr>
  <tr> 
    <td valign="top"> 
      <div align="right"><span style="font-size: 9pt; font-weight: normal;"><img src="<?php echo $c_pass; ?>/img/space.gif" width="220" height="10" border="0"></span><br>
        <table width="220" border="0" cellpadding="1" cellspacing="0">
          <tr> 
            <td> 
              <div align="right"><img src="<?php echo $c_pass; ?>/img/f_arrow.gif" width="12" height="12"></div>
            </td>
            <td width="190"> 
              <div align="left"><span style="font-size: 9pt; font-weight: normal;"><a href="http://www.xxxxxxx.co.jp/" target="_blank">会社概要（マーケターの皆様へ）</a></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"><img src="<?php echo $c_pass; ?>/img/f_arrow.gif" width="12" height="12"></div>
            </td>
            <td width="190"> 
              <div align="left"><span style="font-size: 9pt; font-weight: normal;"><a href="http://www.xxxxxxx.co.jp/monitor/" target="_blank">生活者のみなさんへ（モニター登録）</a></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"><img src="<?php echo $c_pass; ?>/img/f_arrow.gif" width="12" height="12"></div>
            </td>
            <td width="190"> 
              <div align="left"><span style="font-size: 9pt; font-weight: normal;"><a href="http://www.xxxxxxx.co.jp/info/privacypolicy.html" target="_blank">個人情報保護方針</a></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"><img src="<?php echo $c_pass; ?>/img/f_arrow.gif" width="12" height="12"></div>
            </td>
            <td width="190"> 
              <div align="left"><span style="font-size: 9pt; font-weight: normal;"><a href="http://www.melonpan.net/rule_pesonal.html">ウェブサイトプライバシーポリシー</a></span></div>
            </td>
          </tr>
          <tr> 
            <td colspan="2">
              <div align="right"><span style="font-size: 9pt; font-weight: normal;"><img src="<?php echo $c_pass; ?>/img/space.gif" width="210" height="5" border="0"><a href="http://privacymark.jp" target="_blank"><br>
                <img src="<?php echo $c_pass; ?>/img/A820926_01_75.gif" width="75" height="75" border="0"></a></span></div>
            </td>
          </tr>
        </table>
        <span style="font-size: 9pt; font-weight: normal;"><br>
        <a href="http://www.xxxxxxx.co.jp/monitor/" target="_blank"><br>
        </a><a href="http://www.xxxxxxx.co.jp/info/privacypolicy.html" target="_blank"><br>
        </a><br>
        <br>
        </span></div>
    </td>
  </tr>
</table>	
      </div>
  </td>
</tr>
<?
}

/*==========================================================*/
/*	フッタ表示（発行者、めろんパイ用ページ）				*/
/*==========================================================*/
function draw_footer2( $c_pass ){
?>
<TR>
<TD valign="top" colspan="3" align="right">
<HR size="1" width="750">
</TD>
</TR>
<TR>
<TD valign="top" width="200"> <IMG src="<?php echo $c_pass; ?>/img/shim.gif" width="200" height="1">
</TD>
<TD valign="middle" align="center" width="350">
<div align="center"><FONT size="2"><IMG src="<?php echo $c_pass; ?>/img/shim.gif" width="350" height="1"><BR>
      Copyright &copy; 2001-2005 xx xxxxx Inc. All rights reserved. <br>
<a href="http://www.xxxxxxx.co.jp/monitor/index.html" target="_blank"><img src="<?php echo $c_pass; ?>/common/do_rogo.gif" width="40" height="40" border="0"></a>
</FONT></div>
</TD>
<TD valign="top" width="200" align="right"><IMG src="<?php echo $c_pass; ?>/img/shim.gif" width="200" height="1"></TD>
</TR>
<?
}

/*==========================================================*/
/*	ブラウザの種類を取得									*/
/*==========================================================*/
function get_browser_type() {
	global $HTTP_USER_AGENT;

	if (strstr($HTTP_USER_AGENT, "MSIE")) {
		return "IE";
	} elseif (strstr($HTTP_USER_AGENT, "Mozilla")) {
		return "NS";
	} else {
		return "";
	}
}

/*==========================================================*/
/*	ブラウザの種類により出力を分ける						*/
/*==========================================================*/
function browser($ie, $ns) {
	echo (get_browser_type() == "NS") ? $ns : $ie;
}

/*==========================================================*/
/*	フッタ用社内バナーの選択（ＴＯＰページ含む）			*/
/*==========================================================*/
function sel_banner( $c_pass ){

	mt_srand((double)microtime()*1000000);
	$num = mt_rand( 1, 3 );
	if      ( $num == 1 ): {
	//ひとびと.net ３枚
		$banner = "<a href=\"http://www.melonpan.net/hitobito.html\" target=\"_blank\"><img src=\"";
		$num = mt_rand( 1, 3 );
    	if 		( $num == 1 ): $banner .= $c_pass ."/banner/w468a.gif";
    	elseif 	( $num == 2 ): $banner .= $c_pass ."/banner/w468b.gif";
    	elseif	( $num == 3 ): $banner .= $c_pass ."/banner/w468c.gif";
		endif;
		$banner .= "\" width=468 height=60 border=0></a>\n";
	}elseif ( $num == 2 ): {
	//きかせて.net １枚
//		$banner = "<a href=\"http://www.kikasete.ne.jp\" target=\"_blank\">";
		$banner = "<a href=\"http://www.melonpan.net/kikasete.html\" target=\"_blank\">";
		$banner .= "<img src=\"" .$c_pass ."/banner/kkstbanner.gif\" width=468 height=60 border=0>";
		$banner .= "</a>\n";
	}elseif ( $num == 3 ): {
	//はいめーる.net １枚
//		$banner = "<a href=\"http://www.haimail.net/\" target=\"_blank\">";
		$banner = "<a href=\"http://www.haimail.net/pr/00003476/\" target=\"_blank\">";
		$banner .= "<img src=\"" .$c_pass ."/banner/468=60.gif\" width=468 height=60 border=0>";
		$banner .= "</a>\n";
	}endif;
	echo $banner;
}

?>
