<?php
include ( "inc/com_db_func2.inc" );

/*==========================================================*/
/*	カウンター取得（TOPページ）								*/
/*==========================================================*/
function sel_counter(){

	$m_counter = array();
	$conn = db_conxxxt();

	$sql = "select SUM from L_MAG_SUM where SUM_ID >= '1' and SUM_ID <= '3' order by SUM_ID";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "SUM", &$result);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	
	if (OCIFetch($stmt, &$result)) 
		$m_counter["週発行部数"] = $result;
	
	if (OCIFetch($stmt, &$result)) 
		$m_counter["メルマガ数"] = $result;

	if (OCIFetch($stmt, &$result)) 
		$m_counter["総読者数"] = $result;

	db_logoff();
	return $m_counter;

}

/*==========================================================*/
/*	What's New （TOPページ）								*/
/*==========================================================*/
function w_new(){

	$error_msg = "";

	$conn = db_conxxxt();
	$sql = "select * from T_WHATS_NEW order by modify_dt desc";
	$stmt = OCIParse($conn, $sql);
	OCIExecute($stmt, OCI_DEFAULT);
	$cnt = 0;
	for ($i = 0; OCIFetchinto($stmt, &$resarray); $i++) {
		if ($resarray[2] == "" || $resarray[2] == null) {
			print "<FONT color=\"#FF9900\">■</FONT>$resarray[1]<BR>\n";
		} else {
			print "<FONT color=\"#FF9900\">■</FONT>";
			print "<a href=\"$resarray[2]\" target=\"$resarray[4]\">$resarray[1]</a><BR>\n";
		}
	}
	print "<BR>\n";
	db_logoff();

}

/*==========================================================*/
/*	今日のひとりごと（TOPページ）							*/
/*==========================================================*/
function hitorigoto(){

	$conn = db_conxxxt();
	$today = date("Ymd");

	$sql = "select admin_msg from T_ADMIN_MSG where access_day = '$today'";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "ADMIN_MSG", &$admin_msg);
	OCIExecute($stmt, OCI_DEFAULT);
	$ret = OCIFetchStatement($stmt,&$resarray);
	if ($ret == 0) {
		OCIFreeStatement($stmt);
		$sql = "select admin_msg from T_ADMIN_MSG where access_day <= '$today' order by access_day desc";
		$stmt = OCIParse($conn, $sql);
		OCIDefinebyname($stmt, "ADMIN_MSG", &$admin_msg);
		OCIExecute($stmt, OCI_DEFAULT);
		OCIFetch($stmt);
		print $admin_msg;
	} else {
		print $admin_msg;
	}
	OCIFreeStatement($stmt);
	db_logoff();

}

/*==========================================================*/
/*	メルマガラインアップ（TOPページ）						*/
/*==========================================================*/
function mag_listup($top_id, $conn){

	$error_msg = "";
	$mark_html = "<img src=\"img/@gif_image@\" width=14 height=14 vspace=3 hspace=3 align=\"absmiddle\">" ;

	if ( $top_id == 5 ) { // どんどん読者が増えてるよ！
		$sql = "select T.mag_nm, T.new_flg, T.mag_id, T.osusume_flg, T.mag_pub_status_flg, T.mag_riba_status_flg,"
			 . "       T.mag_pub_status_dt, T.mag_birthday_flg, T.osusume_kako_flg, M.mag_original_flg, M.mag_type, "
			 . "       (D.mag_count - D.send_volume) as dondon_num "
			 . "  from T_MAG_TOP T, M_MAGAZINE M, T_MAG_DONDON D "
			 . " where TOP_ID = '5' and T.mag_id = D.mag_id and T.mag_id = M.mag_id"
			 . " order by dondon_num desc";
	}else{
		$sql = "select T.mag_nm, T.new_flg, T.mag_id, T.osusume_flg, T.mag_pub_status_flg, T.mag_riba_status_flg,"
			 . "       T.mag_pub_status_dt, T.mag_birthday_flg, T.osusume_kako_flg, M.mag_original_flg, M.mag_type "
			 . "  from T_MAG_TOP T, M_MAGAZINE M "
			 . " where TOP_ID = '$top_id' and T.mag_id = M.mag_id "
			 . " order by T.cnt" ;
	}

	$stmt = OCIParse($conn, $sql);
	OCIExecute($stmt, OCI_DEFAULT);
	$cnt = 0;
	$bgcolor = "<td bgcolor = \"#ffffff\"";
	for ($i = 0 ; OCIFetchinto($stmt, &$resarray) ; $i++) {
	
		if ($cnt == 20) { break;}
		if ( $i % 2 )
			print "<tr bgcolor='#e2f0c9'>";
		else
			print "<tr bgcolor='#ffffff'>";

/* マーク2個へ変更 */
//		print "<td width=61><font size=2>\n";
		print "<td width=41><font size=2>\n";
		$mark = "";
		if ($resarray[1] == 1)	  { $mark .= str_replace("@gif_image@", "shincyaku.gif", $mark_html); }
		if ($resarray[4] == "01") { $mark .= str_replace("@gif_image@", "kari.gif", $mark_html); }
		if ($resarray[3] == 1)	  { $mark .= str_replace("@gif_image@", "osusume.gif", $mark_html); }
		if ($resarray[8] == 1)	  { $mark .= str_replace("@gif_image@", "osusumekako.gif", $mark_html); }
		if ($resarray[7] == 1)	  { $mark .= str_replace("@gif_image@", "birth.gif", $mark_html); }
		if ($resarray[9] == 1)	  { $mark .= str_replace("@gif_image@", "meron.gif", $mark_html); }
		if ($resarray[10] == "02"){ $mark .= str_replace("@gif_image@", "imode.gif", $mark_html); }
		if ($resarray[5] == 1)	  { $mark .= str_replace("@gif_image@", "point.gif", $mark_html); }
		if ($mark == "")		  { $mark = "<br>"; }
		print $mark ;
		print "</font></td>\n";

/* マーク2個へ変更 */
//		$titl = "<td width=212><font size=2><a href='letter/mag-detail.php?mag_id=" .$resarray[2] ;
		$titl = "<td width=232><font size=2><a href='melonpa/mag-detail.php?mag_id=" .$resarray[2] ;
		if ($resarray[5] == 1) { $titl .= "&riba=1"; }
		$titl .= "'>";
		if ($top_id == 5){ // どんどん
			$titl .= $resarray[0] ."(".$resarray[11].")</a></font></td>\n";
		}else{
			$titl .= $resarray[0];
		}
		print $titl;

		print "</tr>\n";
		$cnt++;
	}

	if ( $cnt == 0 && $top_id == 2 ) { // おすすめがなかった場合
		print "<tr>"
			 ."<td bgcolor=\"#ffffff\"><img src=\"common/spacer.gif\" width=5 height=9></td>"
			 ."<td bgcolor=\"#ffffff\"><font size=2>　めろんぱんオススメのメルマガです。<br>"
			 ."　ただいま準備中です。<br>"
			 ."　現在めろんパイ（めろんぱんパイロット）が<br>"
			 ."　一生懸命メルマガを読んでいるところです。<br>"
			 ."　今しばらくお待ちください。<br></font></td>\n"
			 ."</tr>";
	}
	if ( $cnt == 0 && $top_id == 4 ) { // リバッチャがなかった場合
		print "<td bgcolor=\"#ffffff\"><font size=2>企業発のメルマガです。<br>"
			 ."ただいま準備中です。<br>"
			 ."読むだけでポイントが貯まります。<br>"
			 ."企業側が希望する属性の方しか読んでいただくことはできません。<br>"
			 ."</font></td>";
	}
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
/*	フッタ用社内バナーの選択								*/
/*==========================================================*/
function sel_banner( $c_pass ){

	mt_srand((double)microtime()*1000000);
	$num = mt_rand( 1, 4 );
	if      ( $num == 1 ): { 
	//ひとびと.net ３枚
		$banner = "<a href=\"./hitobito.html\" target=\"_blank\"><img src=\"";
		$num = mt_rand( 1, 3 );
    	if 		( $num == 1 ): $banner .= $c_pass ."/banner/w468a.gif";
    	elseif 	( $num == 2 ): $banner .= $c_pass ."/banner/w468b.gif";
    	elseif	( $num == 3 ): $banner .= $c_pass ."/banner/w468c.gif";
		endif;
		$banner .= "\" width=468 height=60 border=0></a>\n";
	}elseif ( $num == 2 ): {
	//ecHelp.net ３枚
		$banner = "<a href=\"./echelp.html\" target=\"_blank\"><img src=\"";
		$num = mt_rand( 1, 3 );
    	if 		( $num == 1 ): $banner .= $c_pass ."/banner/ecHelp.gif";
    	elseif 	( $num == 2 ): $banner .= $c_pass ."/banner/ecHelp_af.gif";
    	elseif	( $num == 3 ): $banner .= $c_pass ."/banner/ecHelp2.gif";
		endif;
		$banner .= "\" width=468 height=60 border=0></a>\n";
	}elseif ( $num == 3 ): {
	//きかせて.net １枚
		$banner = "<a href=\"./kikasete.html\" target=\"_blank\">";
		$banner .= "<img src=\"" .$c_pass ."/banner/kkstbanner.gif\" width=468 height=60 border=0>";
		$banner .= "</a>\n";
	}elseif ( $num == 4 ): {
	//はいめーる.net １枚
		$banner = "<a href=\"http://www.haimail.net/pr/00003476/\" target=\"_blank\">";
		$banner .= "<img src=\"" .$c_pass ."/banner/468=60.gif\" width=468 height=60 border=0>";
		$banner .= "</a>\n";
	}endif;
	echo $banner;
}

/*==========================================================*/
/*	プレゼント応募受付有無判定								*/
/*==========================================================*/
function present_oubo(&$link_text) {
	$conn = db_conxxxt();
	$sql = "select PD_LINK_TEXT from M_PRESENT_DATA where PD_status='0' and trunc(SYSDATE) between PD_campaign_start and PD_campaign_end";
	$stmt = OCIParse($conn, $sql);
	OCIDefineByName($stmt, "PD_LINK_TEXT", &$link_text);
	OCIExecute($stmt, OCI_DEFAULT);
	if (OCIFetch($stmt)) {
		return true;
	}
	return false;
}

/*********************************************************************************
			Main
*********************************************************************************/
	$mae = strftime("%Y.%m.%d ",time() - (14*24*60*60));
	$now_time = strftime("%Y年%m月%d日 ",time());
	$now_time2 = strftime("%Y/%m/%d %T",time());
	$mae2 = strftime("%Y/%m/%d %T",time() - (7*24*60*60));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="keyword" content="めろん,めろんぱん,メロンパン,よりすぐり,メールマガジン,めーるまがじん,メルマガ,めるまが,メルマガサイト,マイページ,マイメロンパン,マイめろんぱん,Ｍｙめろんぱん,カテゴリ,私向け,気分,みつくろって,みつくろい,サーチ,推薦文,ポイント,おすすめ,オススメ,新着,創刊,本登録,仮登録,登録,ナビ,メルマガナビ,読者,発行,発行者,ひとりごと,お気に入り,おきにいり,りばっチャ,りばっちゃ,リバッチャ,逆有料,レター,めろんぱんレター,メロンパンレター,バ・キューン,バキューン,企業,生活者,問い合わせ,（株）○○○○○○,○○○○○,○○○○○○,発信,応援マガジン,ニュース,企業,ビジネス,エンターテイメント,就職,転職,スポーツ,レジャー,コンピュータ,コンピューター,インターネット,アート,文芸,学習,資格,ファッション,美容,情報源,情報,ショッピング,地域,タウン,タウン情報,暮らし,健康,学校,旅行,出会い,グルメ,レストラン,科学,技術,趣味,政治,行政,ジャンル,属性,性別,年代,職業,エリア,無料,バナー,広告,５行広告,クリック,ASP,メールアドレスクリーニング,アドレスクリーニング,BtoC,ランキング,読者数,読者が増えてる,主婦,主夫,専門職,ビジネスマン,学生,女性,読者プレゼント,プレゼント">
<meta name="author" content="株式会社○○○○○○">
<meta name="description" content="『めろんぱん』はよりすぐりのメルマガをみなさまにお届けする、メルマガ配信スタンドです。">
<meta name="keywords" content=" メルマガ,メールマガジン,懸賞,プレゼント,芸能,インターネット,めろんぱん,ニュース">
<title>めろんぱん || よりすぐりメルマガサイト</title>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width="758" border="0" cellspacing="0" cellpadding="0" height="175">
    <tr> 
      <td width="758"> 
        <table width="757" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="151"><img src="img/toprogo1.gif" width="151" height="77" alt="よりすぐりメルマガめろんぱん"></td>
            <td width="116"><img src="img/toprogo2.gif" width="115" height="77" alt="よりすぐりメルマガめろんぱん"></td>
            <td width="490"> 
              <div align="right">

<a href="http://actionclick.net/cgi-bin/drms/find/T7fff88dcX/" target="_blank"> 
<img src="img/findjob3.gif" border="0"></a>


			  </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td width="758"> 
        <table border="0" cellspacing="0" cellpadding="0" width="757">
          <tr> 
            <td width="151"><img src="img/toprogo3.gif" width="151" height="20" alt="よりすぐりメルマガめろんぱん"></td>
            <td width="115"><img src="img/toprogo4.gif" width="115" height="20" alt="よりすぐりメルマガめろんぱん"></td>
            <td width="491"><img src="img/topbar.gif" width="491" height="20"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td width="758"> 
        <table width="757" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="206"><img src="img/toprogo5.gif" width="206" height="78" alt="よりすぐりメルマガめろんぱん"></td>
            <td bgcolor="#99cc66" width="551"> 
              <table width="543" border="0" cellspacing="0" cellpadding="0" height="66" bgcolor="#99cc66">
                <tr> 
                  <td> 
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td colspan="5"> 
                          <table width="364" border="0" cellspacing="0" cellpadding="0" height="33">
                            <tr> 
                              <td width="147" valign="middle" height="33"><img src="common/melmagasearch.gif" width="147" height="33" alt="メルマガサーチ"></td>
                              <td bgcolor="#b8e185" width="216" height="33"> 
                                <table width="216" border="0" cellspacing="0" cellpadding="0" height="33">
                                  <tr> 
                                    <td height="1" bgcolor="#58a01e"><img src="common/spacer.gif" width="1" height="1"></td>
                                  </tr>
<tr>
<form action="melonpa/search_result.php" method="post">
<td height="32" valign="middle" align="center">
  <INPUT type="hidden" name="mode" value="word">
  <INPUT type="text" name="keyword" size="<? browser("30", "19") ?>">
  <INPUT type="submit" name="Submit22" value="検索">
</td>
</form>
</tr>
                                </table>
                              </td>
                              <td width="1" bgcolor="#68a01e" height="33"><img src="common/head_bargif.gif" width="2" height="33"></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr> 
<td><a href="search/kensaku-category.php"><img src="common/category.gif" width="79" height="26" border="0" alt="カテゴリ"></a></td>
<td><a href="search/kensaku-muke.php"><img src="common/watashimuke.gif" width="68" height="26" border="0" alt="私向け"></a></td>
<td><a href="search/kensaku-kibun.php"><img src="common/kibun.gif" width="67" height="26" border="0" alt="気分"></a></td>
<td><a href="mitsukuroi/mitsukuroi_1.php"><img src="common/mitsukuroi.gif" width="68" height="26" border="0" alt="みつくろい"></a></td>
<td><a href="search/kensaku-ranking.php"><img src="common/ranking.gif" width="83" height="26" border="0" alt="Ranking"></a></td>
                      </tr>
                    </table>
                  </td>
<td width="51"><a href="melonpa/MelonpanRef.php"><img src="common/mypage.gif" width="51" height="66" border="0" alt="My Page"></a></td>
<td width="51"><a href="help/index.html"><img src="common/help.gif" width="51" height="66" border="0" alt="Help"></a></td>
<td width="63"><a href="./index.html"><img src="common/home2.gif" width="63" height="66" border="0" alt="TOP"></a></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<table width="758" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="151" bgcolor="#99cc66">　 </td>
    <td width="22" valign="top"><img src="img/r.gif" width="21" height="22"></td>
    <td>　</td>
  </tr>
  <tr> 
    <td width="151" bgcolor="#99cc66" align="center" valign="top"> 
      <table width="132" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center"><img src="img/hitorigoto.gif" width="132" height="22"></td>
        </tr>
<!-- 今日の独り言(s) -->
<tr> 
<td align="center" height="50"><font size="2">
<?php hitorigoto(); ?>
</font><P></td>
</tr>
<!-- 今日の独り言(e) -->
        <tr> 
          <td><img src="img/meronpantoha.gif" width="131" height="17"></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"> 
			<table width="131" border="0" cellspacing="2" cellpadding="0">
<!-- めろんぱんとは(s) -->
<tr><td><a href="forreaders.html"><img src="img/dokusya1.gif" width="48" height="15" border="0"></a>
        <a href="trait.html"><img src="img/hakousya1.gif" width="61" height="15" border="0"></a></td></tr>
<tr><td align="center"><img src="img/line.gif" width="117" height="2"></td></tr><tr> 
<td><a href="faq.html"><img src="img/nani1.gif" width="105" height="15" border="0"></a></td></tr><tr>
<td><a href="dream.html"><img src="img/yume1.gif" width="94" height="15" border="0"></a></td></tr><tr>
<td><a href="rule_pesonal.html"><img src="img/kojinjoho1.gif" width="123" height="15" border="0"></a></td></tr><tr>
<td><a href="pressrelease.php"><img src="img/press1.gif" width="90" height="15" border="0"></a></td></tr>
<!-- めろんぱんとは(e) -->
            </table>
          </td>
        </tr>
<!-- プレゼント応募(s) -->
<? if (present_oubo($link_text)) { ?>
		<tr>
			<td align="center"><br><table width="100%" border="0" height="41%" cellpadding="0" cellspacing="0">
              <tr valign="bottom">
                <td>
                  <div align="center"><a href="melonpa/mag-present.php"><img src="img/present.gif" width="131" height="30" border="0"></a></div>
                </td>
              </tr>
              <tr>
                <td>
                  <div align="center"><font size="2"><?php echo $link_text;?>
                  </font></div>
                </td>
              </tr>
            </table></td>
		</tr><tr></tr>
<? } ?>
<!-- プレゼント応募(e) -->
<tr><td><br></td></tr>
        <tr> 
          <td align="center">
<a href="http://actionclick.net/cgi-bin/drms/happy/r7fff88dbW/" target="_blank"> 
<img src="img/100_60_ver01.gif" border="0"></a>
          </td>
        </tr>
<tr><td><br></td></tr>
        <tr> 
          <td><img src="img/serch.gif" width="131" height="17"></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"> 
		    <table width="131" border="0" cellspacing="2" cellpadding="0">
<!-- メルマガサーチ(s) -->
<tr><td><a href="search/kensaku-category.php"><img src="img/category1.gif" width="90" height="15" border="0"></a></td></tr>
<tr><td><a href="search/kensaku-muke.php"><img src="img/watashimuke1.gif" width="79" height="15" border="0"></a></td></tr>
<tr><td><a href="search/kensaku-kibun.php"><img src="img/kibundesagasu1.gif" width="67" height="15" border="0"></a></td></tr>
<tr><td><a href="mitsukuroi/mitsukuroi_1.php"><img src="img/melmaganavi1.gif" width="113" height="15" border="0"></a></td></tr>
<tr><td><a href="search/kensaku-ranking.php"><img src="img/ranking1.gif" width="102" height="15" border="0"></a></td></tr>
<!-- メルマガサーチ(e) -->
            </table>
          </td>
        </tr>
<!-- マイメロンパン登録(s) -->
<tr>
<td height="60"><br>
<a href="melonpan/melonpatoha.php"><img src="img/meronpantoroku.gif" width="131" height="20" border="0"></a>
<br></td>
</tr>
<!-- マイメロンパン登録(e) -->
        <tr> 
          <td><img src="img/hashinkyoku.gif" width="131" height="17"></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"> 
		    <table width="131" border="0" cellspacing="2" cellpadding="0">
<!-- メルマガ発信局(s) -->
<tr><td><a href="publisher/kiyaku.html"><img src="img/hakou1.gif" width="123" height="15" border="0"></a></td></tr>
<tr><td><a href="publisher/hakkou-login.html"><img src="img/kanripage1.gif" width="113" height="15" border="0"></a></td></tr>
<!-- メルマガ発信局(e) -->
            </table>
          </td>
        </tr>
<!-- レター解除(s) -->
<tr> 
<td><font size="2"><br>
<font color="#009900">●</font>『バ・キューン』『めろんぱんレター〜新着・おすすめメルマガ紹介メルマガ』の解除・再購読は
    <a href="letter/letterbaqn.php"><font color="#FF0000">こちら</font></a>
<br>
<br>
</font></td>
</tr>
<!-- レター解除(e) -->
        <tr> 
          <td><font size="2" color="#FF0000">『めろんぱん』のメルマガの読者の方には、（株）○○○○○○の事業理念の一環として「発信する生活者応援マガジン『バ・キューン』」、めろんぱんでの新着・おすすめ紹介メルマガ紹介マガジン『めろんぱんレター』が自動的に送信されます。元気な生活者たちを一緒に応援して下さい。配信の頻度は２回／週程度です。<br>
            どうぞ、ご了承ください。</font><font size="2"><br>
            </font></td>
        </tr>
      </table>

<br>
<table border=0 cellspacing=0 cellpadding=0>
 <tr>
  <td>
   <font size="2"><font color="#009900">●</font>めろんぱんは<br>　リンクフリーです。<br></font>
   <img src="img/banner.gif" border="0">
  </td>
 </tr>
 <tr><td><br></td></tr>
 <tr>
  <td>
   <font size="2"><font color="#009900">●</font>お問い合わせは<br>　<A href="mailto:info@melonpan.net">info@melonpan.net</A><br>　で受け付けております。</font>
  </td>
 </tr>
  <tr><td><br><br><br></td></tr>
 <tr>
  <td>
<a href="http://www.bizmails.jp" target="_blank"> 
<img src="http://www.bizmails.jp/image/bm1.gif" border="0" alt="bizmails"></a> 
  </td>
 </tr>
 <tr><td><br></td></tr>
 <tr>
  <td>
<a href="http://www.h2o-soft.com" target="_blank"> 
<img src="http://www.h2o-soft.com/h2o_banner.gif" border="0" alt="H2O Software."></a> 
  </td>
 </tr>
</table>

    </td>
    <td width="22">　</td>
    <td valign="top"> 
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr> 
          <td width="100%"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#99cc66">
              <tr> 
                <td width="21"><img src="img/r_left.gif" width="21" height="21"></td>
<td valign="bottom"> 
<div align="right"><font size="2"><img src="img/whatsnew.gif" width="104" height="17" align="left">
<?php
//  echo $now_time2;
  $m_counter = sel_counter();
?>
   本登録メルマガ数&gt;<?php echo number_format($m_counter["メルマガ数"]); ?>誌
 | 総読者数&gt;<?php echo number_format($m_counter["総読者数"]); ?>人 
 | 週発行部数&gt;<?php echo number_format($m_counter["週発行部数"]); ?>部
</font></div>
</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td width="100%"> 
            <table width="580" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="21">　</td>

<!-- What's New(s) -->
<td width="558"> 
<div align="left"><br>
<?php
	w_new();
?>
</div>
</td>
<!-- What's New(e) -->
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td width="100%"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#99cc66">
              <tr> 
                <td width="21"><img src="img/r_left.gif" width="21" height="21"></td>
                <td align="left"> <font size="1"><img src="img/magazinemenu.gif" width="142" height="17">　</font></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td bgcolor="#ffffc0" width="100%"> 
            <table width="571" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="16">　</td>
                <td width="555"><br>
                  <table width="555" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><img src="img/bar_1.gif" width="555" height="2"></td>
                    </tr>
                    <tr>
                      <td>
                        <table border="0" cellspacing="0" cellpadding="0" width="555">
<tr> 
  <td bgcolor="#e2f0c9" width="80" height="17" align="center"><font size="1"> マークの見方：</font></td>
  <td bgcolor="#e2f0c9" width="475" height="17"> 
    <p><img src="common/spacer.gif" width="2" height="16"><img src="img/shincyaku1.gif" width="91" height="16" vspace="3" hspace="3"><img src="img/kari1.gif" width="98" height="16" vspace="3" hspace="3"><img src="img/osusume1.gif" width="110" height="16" vspace="3" hspace="3"><img src="img/osusumekako1.gif" width="102" height="16" vspace="3" hspace="3"><img src="img/otanjobi1.gif" width="134" height="16" vspace="3" hspace="3"><img src="img/original1.gif" width="132" height="16" vspace="3" hspace="3"><img src="img/imode1.gif" width="59" height="16" vspace="3" hspace="3"><img src="img/ribacya1.gif" width="119" height="16" vspace="3" hspace="3">
  </p></td>
</tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td><img src="img/bar_2.gif" width="555" height="2"></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td width="16"><img src="common/spacer.gif" width="16" height="9"></td>
                <td width="555"> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td width="48%" valign="top"> 
                        <table width="273" border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td width="273"><br>
                              <img src="img/osusumetitle.gif" width="273" height="28"></td>
                          </tr>
                          <tr> 
                            <td bgcolor="#94cd3e" width="273"> 
                              <table border="0" cellspacing="0" cellpadding="0" width="273">
<!-- オススメ一覧(s) -->
<?php
	$conn = db_conxxxt();
	mag_listup( "2", $conn );
?>
<!-- オススメ一覧(e) -->
                              </table>
                            </td>
                          </tr>
                        </table>
                        <table width="273" border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td width="273"><br>
                              <img src="img/dondon.gif" width="273" height="31"></td>
                          </tr>
                          <tr> 
                            <td bgcolor="#94cd3e" width="273"> 
                              <table border="0" cellspacing="0" cellpadding="0" width="273">
<?php
	// どんどん
	mag_listup( "5", $conn );
?>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <table width="273" border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td width="273"><br>
                              <img src="img/yomebatamaru.gif" width="273" height="47"></td>
                          </tr>
                          <tr> 
                            <td bgcolor="#94cd3e" width="273" valign="top"> 
                              <table border="0" cellspacing="0" cellpadding="5" width="273" height="17">
                                <tr> 
                                  <td bgcolor="#ffffff"><img src="common/spacer.gif" width="5" height="9"></td>
                                  <td bgcolor="#ffffff"><font size="2">企業発のメルマガです。<br>
                                    ただいま準備中です。<br>
                                    読むだけでポイントが貯まります。<br>
                                    企業側が希望する属性の方しか読んでいただくことはできません。<br>
                                    </font></td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <br>
                      </td>
                      <td width="5"><img src="common/spacer.gif" width="5" height="9"></td>
                      <td width="49%" valign="top"> 
                        <table width="273" border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td width="273"><br>
                              <img src="img/shincyaku_hon.gif" width="273" height="28"></td>
                          </tr>
                          <tr> 
                            <td bgcolor="#94cd3e" width="273"> 
                              <table border="0" cellspacing="0" cellpadding="0" width="273">
<?php
	// 新着・本登録
	mag_listup( "3", $conn );
?>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <table width="273" border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td width="273"><br>
                              <img src="img/shincyaku_kari.gif" width="273" height="28"></td>
                          </tr>
                          <tr> 
                            <td bgcolor="#94cd3e" width="273"> 
                              <table border="0" cellspacing="0" cellpadding="0" width="273">
<?php
	// 新着・仮登録
	mag_listup( "1", $conn );
	db_logoff();
?>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <br>
                        <br>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
		<tr> 
		  <td width="100%"> 
		    <div align="center"><br>
		      <!-- VC Running -->
<A HREF="http://kansas.valueclick.ne.jp/redirect?host=hs0046967&size=468x24&b=runningtext&v=0" TARGET="_blank"><IMG BORDER="0" WIDTH="468" HEIGHT="24" ALT="Click here to visit our sponsor"
SRC="http://kansas.valueclick.ne.jp/cycle?host=hs0046967&size=468x24&b=runningtext&noscript=1"></A>
<!-- vc running --><br><br>
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
echo sel_banner(".");
?>
		      <br>
		      <font size="1">※めろんぱんは、（株）○○○○○○の事業理念「発信する生活者を支援する」に基づいて運営されています。</font> 
		      <br>
		      <hr width="85%" size="1">
		      <font size="2"><br>
		      <br>
		      Copyright(c)2001 xxxxxxx, Co., Ltd. All rights reserved.<br>
		      <a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" target="_blank"><img src="common/do_rogo.gif" width="40" height="40" border="0"></a></font><br>
		      <br>
		      <br>
		    </div>
		  </td>
		</tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
