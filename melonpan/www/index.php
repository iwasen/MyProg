<?php
include ( "inc/com_db_func2.inc" );

/*==========================================================*/
/*	�����󥿡�������TOP�ڡ�����								*/
/*==========================================================*/
function sel_counter(){

	$m_counter = array();

	$sql = "select SUM from L_MAG_SUM where SUM_ID >= 1 and SUM_ID <= 3 order by SUM_ID";
	$result = db_exec($sql);

	if ($fetch = pg_fetch_object($result, 0)) 
		$m_counter["��ȯ������"] = $fetch->sum;

	if ($fetch = pg_fetch_object($result, 1)) 
		$m_counter["���ޥ���"] = $fetch->sum;

	if ($fetch = pg_fetch_object($result, 2)) 
		$m_counter["���ɼԿ�"] = $fetch->sum;

	return $m_counter;

}

/*==========================================================*/
/*	What's New ��TOP�ڡ�����								*/
/*==========================================================*/
function w_new(){
	$error_msg = "";
	$sql = "select * from T_WHATS_NEW order by modify_dt desc";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$cnt = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$resarray = pg_fetch_array ($result, $i);
		if ($resarray[2] == "" || $resarray[2] == null) {
			print "<FONT color=\"#FF9900\">��</FONT>$resarray[1]<BR>\n";
		} else {
			print "<FONT color=\"#FF9900\">��</FONT>";
			print "<a href=\"$resarray[2]\" target=\"$resarray[4]\">$resarray[1]</a><BR>\n";
		}
	}
	print "<BR>\n";
}

/*==========================================================*/
/*	�����ΤҤȤꤴ�ȡ�TOP�ڡ�����							*/
/*==========================================================*/
function hitorigoto(){

	$today = date("Ymd");

	$sql = "select admin_msg from T_ADMIN_MSG where access_day = '$today'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$admin_msg = $fetch->admin_msg;
		print $admin_msg;
	}else {
		$sql = "select admin_msg from T_ADMIN_MSG where access_day <= '$today' order by access_day desc";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$admin_msg = $fetch->admin_msg;
			print $admin_msg;
		} else {
			print $admin_msg;
		}
	}
}

/*==========================================================*/
/*	���ޥ��饤�󥢥åס�TOP�ڡ�����						*/
/*==========================================================*/
function mag_listup($top_id){

	$error_msg = "";
	$mark_html = "<img src=\"img/@gif_image@\" width=14 height=14 vspace=3 hspace=3 align=\"absmiddle\">" ;

	if ( $top_id == 5 ) { // �ɤ�ɤ��ɼԤ������Ƥ�衪
		$sql = "select T.mag_nm, T.new_flg, T.mag_id, T.osusume_flg, T.mag_pub_status_flg, T.mag_riba_status_flg,"
			 . "       T.mag_pub_status_dt, T.mag_birthday_flg, T.osusume_kako_flg, M.mag_original_flg, M.mag_type, "
			 . "       (D.mag_count - D.send_volume) as dondon_num "
			 . "  from T_MAG_TOP T, M_MAGAZINE M, T_MAG_DONDON D "
			 . " where TOP_ID = 5 and T.mag_id = D.mag_id and T.mag_id = M.mag_id"
			 . " order by dondon_num desc";
	}else{
		$sql = "select T.mag_nm, T.new_flg, T.mag_id, T.osusume_flg, T.mag_pub_status_flg, T.mag_riba_status_flg,"
			 . "       T.mag_pub_status_dt, T.mag_birthday_flg, T.osusume_kako_flg, M.mag_original_flg, M.mag_type "
			 . "  from T_MAG_TOP T, M_MAGAZINE M "
			 . " where TOP_ID = $top_id and T.mag_id = M.mag_id "
			 . " order by T.cnt" ;
	}

	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$cnt = 0;
	$bgcolor = "<td bgcolor = \"#ffffff\"";
	for ($i = 0; $i < $nrow; $i++) {
		$resarray = pg_fetch_array ($result, $i);
		if ($cnt == 20) { break;}
		if ( $i % 2 )
			print "<tr bgcolor='#e2f0c9'>";
		else
			print "<tr bgcolor='#ffffff'>";

/* �ޡ���2�Ĥ��ѹ� */
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

/* �ޡ���2�Ĥ��ѹ� */
//		$titl = "<td width=212><font size=2><a href='letter/mag-detail.php?mag_id=" .$resarray[2] ;
		$titl = "<td width=232><font size=2><a href='melonpa/mag-detail.php?mag_id=" .$resarray[2] ;
		if ($resarray[5] == 1) { $titl .= "&riba=1"; }
		$titl .= "'>";
		if ($top_id == 5){ // �ɤ�ɤ�
			$titl .= $resarray[0] ."(".$resarray[11].")</a></font></td>\n";
		}else{
			$titl .= $resarray[0];
		}
		print $titl;

		print "</tr>\n";
		$cnt++;
	}

	if ( $cnt == 0 && $top_id == 2 ) { // �������᤬�ʤ��ä����
		print "<tr>"
			 ."<td bgcolor=\"#ffffff\"><img src=\"common/spacer.gif\" width=5 height=9></td>"
			 ."<td bgcolor=\"#ffffff\"><font size=2>������Ѥ󥪥�����Υ��ޥ��Ǥ���<br>"
			 ."���������޽�����Ǥ���<br>"
			 ."�����ߤ���ѥ��ʤ���Ѥ�ѥ���åȡˤ�<br>"
			 ."��������̿���ޥ����ɤ�Ǥ���Ȥ���Ǥ���<br>"
			 ."�������Ф餯���Ԥ�����������<br></font></td>\n"
			 ."</tr>";
	}
	if ( $cnt == 0 && $top_id == 4 ) { // ��Хå��㤬�ʤ��ä����
		print "<td bgcolor=\"#ffffff\"><font size=2>���ȯ�Υ��ޥ��Ǥ���<br>"
			 ."�������޽�����Ǥ���<br>"
			 ."�ɤ�����ǥݥ���Ȥ����ޤ�ޤ���<br>"
			 ."���¦����˾����°�����������ɤ�Ǥ����������ȤϤǤ��ޤ���<br>"
			 ."</font></td>";
	}
}

/*==========================================================*/
/*	�֥饦���μ�������									*/
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
/*	�֥饦���μ���ˤ����Ϥ�ʬ����						*/
/*==========================================================*/
function browser($ie, $ns) {
	echo (get_browser_type() == "NS") ? $ns : $ie;
}


/*==========================================================*/
/*	����Хʡ�������								*/
/*==========================================================*/
function sel_banner2($kind) {
	$sql = "SELECT * FROM T_BANNER WHERE date_trunc('hour', timestamp 'now') between BN_start_dt and BN_end_dt and BN_kind = '$kind'";
	$result = db_exec ($sql);
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
		print "<img src=\"./sub/banner_pict.php?bn_picture=" . $bn_picture . "\" id=\"pict\" alt=\"banner\" border=\"0\"></a>";
	}
}

/*==========================================================*/
/*	�եå��Ѽ���Хʡ�������								*/
/*==========================================================*/
function sel_banner( $c_pass ){

	mt_srand((double)microtime()*1000000);
	$num = mt_rand( 1, 3 );
	if      ( $num == 1 ): { 
	//�ҤȤӤ�.net ����
		$banner = "<a href=\"./hitobito.html\" target=\"_blank\"><img src=\"";
		$num = mt_rand( 1, 3 );
    	if 		( $num == 1 ): $banner .= $c_pass ."/banner/w468a.gif";
    	elseif 	( $num == 2 ): $banner .= $c_pass ."/banner/w468b.gif";
    	elseif	( $num == 3 ): $banner .= $c_pass ."/banner/w468c.gif";
		endif;
		$banner .= "\" width=468 height=60 border=0></a>\n";
	}elseif ( $num == 2 ): {
	//��������.net ����
		$banner = "<a href=\"./kikasete.html\" target=\"_blank\">";
		$banner .= "<img src=\"" .$c_pass ."/banner/kkstbanner.gif\" width=468 height=60 border=0>";
		$banner .= "</a>\n";
	}elseif ( $num == 3 ): {
	//�Ϥ��᡼��.net ����
		$banner = "<a href=\"http://www.haimail.net/pr/00003476/\" target=\"_blank\">";
		$banner .= "<img src=\"" .$c_pass ."/banner/468=60.gif\" width=468 height=60 border=0>";
		$banner .= "</a>\n";
	}endif;
	echo $banner;
}

/*==========================================================*/
/*	�ץ쥼��ȱ������̵ͭȽ��								*/
/*==========================================================*/
function present_oubo(&$link_text) {
	$sql = "select PD_LINK_TEXT from M_PRESENT_DATA where PD_status='0' and date_trunc('day', timestamp 'now') between PD_campaign_start and PD_campaign_end";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$link_text = $fetch->pd_link_text;
		return true;
	} else {
		return false;
	}
}

/*********************************************************************************
			Main
*********************************************************************************/
	$mae = strftime("%Y.%m.%d ",time() - (14*24*60*60));
	$now_time = strftime("%Yǯ%m��%d�� ",time());
	$now_time2 = strftime("%Y/%m/%d %T",time());
	$mae2 = strftime("%Y/%m/%d %T",time() - (7*24*60*60));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="keyword" content="����,����Ѥ�,����ѥ�,��ꤹ����,�᡼��ޥ�����,�᡼��ޤ�����,���ޥ�,���ޤ�,���ޥ�������,�ޥ��ڡ���,�ޥ�����ѥ�,�ޥ�����Ѥ�,�ͣ�����Ѥ�,���ƥ���,�����,��ʬ,�ߤĤ���ä�,�ߤĤ���,������,����ʸ,�ݥ����,��������,��������,����,�ϴ�,����Ͽ,����Ͽ,��Ͽ,�ʥ�,���ޥ��ʥ�,�ɼ�,ȯ��,ȯ�Լ�,�ҤȤꤴ��,����������,�����ˤ���,�쥿��,����Ѥ�쥿��,����ѥ�쥿��,�С����塼��,�Х��塼��,���,�����,�䤤��碌,�ʳ��ˡ�����������,����������,������������,ȯ��,����ޥ�����,�˥塼��,���,�ӥ��ͥ�,���󥿡��ƥ�����,����,ž��,���ݡ���,�쥸�㡼,����ԥ塼��,����ԥ塼����,���󥿡��ͥå�,������,ʸ��,�ؽ�,���,�ե��å����,����,����,����,����åԥ�,�ϰ�,������,���������,��餷,��,�ع�,ι��,�в�,�����,�쥹�ȥ��,�ʳ�,����,��̣,����,����,������,°��,����,ǯ��,����,���ꥢ,̵��,�Хʡ�,����,���Թ���,����å�,ASP,�᡼�륢�ɥ쥹���꡼�˥�,���ɥ쥹���꡼�˥�,BtoC,��󥭥�,�ɼԿ�,�ɼԤ������Ƥ�,����,����,���翦,�ӥ��ͥ��ޥ�,����,����,�ɼԥץ쥼���,�ץ쥼���">
<meta name="author" content="������ҡ�����������">
<meta name="description" content="�ؤ���Ѥ�٤Ϥ�ꤹ����Υ��ޥ���ߤʤ��ޤˤ��Ϥ����롢���ޥ��ۿ�������ɤǤ���">
<meta name="keywords" content=" ���ޥ�,�᡼��ޥ�����,����,�ץ쥼���,��ǽ,���󥿡��ͥå�,����Ѥ�,�˥塼��">
<title>����Ѥ� || ��ꤹ������ޥ�������</title>
<style type="text/css">
<!--
.mark {  font: 9px/125% "�ͣ� �����å�", "Osaka������"; color: #000000; text-decoration: none}
-->
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="758" border="0" cellspacing="0" cellpadding="0" height="175">
<tr>
<td width="758">
<table width="757" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="151"><img src="img/toprogo1.gif" width="151" height="77" alt="��ꤹ������ޥ�����Ѥ�"></td>
<td width="116"><img src="img/toprogo2.gif" width="115" height="77" alt="��ꤹ������ޥ�����Ѥ�"></td>
<td width="490"> 
<div align="right">
<!-- �ȥå׹���Хʡ�������ɽ�� -->
<?= sel_banner2(1); ?>
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
<td width="151"><img src="img/toprogo3.gif" width="151" height="20" alt="��ꤹ������ޥ�����Ѥ�"></td>
<td width="115"><img src="img/toprogo4.gif" width="115" height="20" alt="��ꤹ������ޥ�����Ѥ�"></td>
<td width="491"><img src="img/topbar.gif" width="491" height="20"></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td width="758"> 
<table width="757" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="206"><img src="img/toprogo5.gif" width="206" height="78" alt="��ꤹ������ޥ�����Ѥ�"></td>
<td bgcolor="#99cc66" width="551"> 
<table width="543" border="0" cellspacing="0" cellpadding="0" height="66" bgcolor="#99cc66">
<tr> 
<td> 
<table border="0" cellspacing="0" cellpadding="0">
<tr> 
<td colspan="5"> 
<table width="364" border="0" cellspacing="0" cellpadding="0" height="33">
<tr> 
<td width="147" valign="middle" height="33"><img src="common/melmagasearch.gif" width="147" height="33" alt="���ޥ�������"></td>
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
<INPUT type="submit" name="Submit22" value="����">
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
<td><a href="search/kensaku-category.php" onfocus="blur()"><img src="common/category.gif" width="79" height="26" border="0" alt="���ƥ���"></a></td>
<td><a href="search/kensaku-muke.php" onfocus="blur()"><img src="common/watashimuke.gif" width="68" height="26" border="0" alt="�����"></a></td>
<td><a href="search/kensaku-kibun.php" onfocus="blur()"><img src="common/kibun.gif" width="67" height="26" border="0" alt="��ʬ"></a></td>
<td><a href="mitsukuroi/mitsukuroi_1.php" onfocus="blur()"><img src="common/mitsukuroi.gif" width="68" height="26" border="0" alt="�ߤĤ���"></a></td>
<td><a href="search/kensaku-ranking.php" onfocus="blur()"><img src="common/ranking.gif" width="83" height="26" border="0" alt="Ranking"></a></td>
</tr>
</table>
</td>
<td width="51"><a href="melonpa/MelonpanRef.php" onfocus="blur()"><img src="common/mypage.gif" width="51" height="66" border="0" alt="My Page"></a></td>
<td width="51"><a href="help/index.html" onfocus="blur()"><img src="common/help.gif" width="51" height="66" border="0" alt="Help"></a></td>
<td width="63"><a href="./index.html" onfocus="blur()"><img src="common/home2.gif" width="63" height="66" border="0" alt="TOP"></a></td>
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
<td width="151" bgcolor="#99cc66">�� </td>
<td width="22" valign="top"><img src="img/r.gif" width="21" height="22"></td>
<td>��</td>
</tr>
<tr> 
<td width="151" bgcolor="#99cc66" align="center" valign="top"> 
      <table width="132" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="center"><img src="img/hitorigoto.gif" width="132" height="22"></td>
        </tr>
        <!-- �������Ȥ��(s) -->
        <tr> 
          <td align="center" height="50"><font size="2"> 
            <?php hitorigoto(); ?>
            </font>
            <P>
          </td>
        </tr>
        <!-- �������Ȥ��(e) -->
        <tr> 
          <td><img src="img/meronpantoha.gif" width="131" height="17"></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"> 
            <table width="131" border="0" cellspacing="2" cellpadding="0">
              <!-- ����Ѥ�Ȥ�(s) -->
              <tr>
                <td><a href="forreaders.html"><img src="img/dokusya1.gif" width="48" height="15" border="0"></a> 
                  <a href="trait.html"><img src="img/hakousya1.gif" width="61" height="15" border="0"></a></td>
              </tr>
              <tr>
                <td align="center"><img src="img/line.gif" width="117" height="2"></td>
              </tr>
              <tr> 
                <td><a href="faq.html"><img src="img/nani1.gif" width="105" height="15" border="0"></a></td>
              </tr>
              <tr> 
                <td><a href="dream.html"><img src="img/yume1.gif" width="94" height="15" border="0"></a></td>
              </tr>
              <tr> 
                <td><a href="rule_pesonal.html"><img src="img/kojinjoho1.gif" width="123" height="15" border="0"></a></td>
              </tr>
              <tr> 
                <td><a href="pressrelease.php"><img src="img/press1.gif" width="90" height="15" border="0"></a></td>
              </tr>
              <!-- ����Ѥ�Ȥ�(e) -->
            </table>
          </td>
        </tr>
        <!-- �ץ쥼��ȱ���(s) -->
        <? if (present_oubo($link_text)) { ?>
        <tr> 
          <td align="center"><br>
            <table width="100%" border="0" height="41%" cellpadding="0" cellspacing="0">
              <tr valign="bottom"> 
                <td> 
                  <div align="center"><a href="melonpa/mag-present.php"><img src="img/present.gif" width="131" height="30" border="0"></a></div>
                </td>
              </tr>
              <tr> 
                <td> 
                  <div align="center"><font size="2">
                    <?php echo $link_text;?>
                    </font></div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr></tr>
        <? } ?>
        <!-- �ץ쥼��ȱ���(e) -->
        <tr>
          <td><br>
          </td>
        </tr>
        <tr> 
          <td align="center"> 
            <!-- �����ɹ���Хʡ�������ɽ�� -->
            <?= sel_banner2(2); ?>
          </td>
        </tr>
        <tr>
          <td><br>
          </td>
        </tr>
        <tr> 
          <td><img src="img/serch.gif" width="131" height="17"></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"> 
            <table width="131" border="0" cellspacing="2" cellpadding="0">
              <!-- ���ޥ�������(s) -->
              <tr>
                <td><a href="search/kensaku-category.php"><img src="img/category1.gif" width="90" height="15" border="0"></a></td>
              </tr>
              <tr>
                <td><a href="search/kensaku-muke.php"><img src="img/watashimuke1.gif" width="79" height="15" border="0"></a></td>
              </tr>
              <tr>
                <td><a href="search/kensaku-kibun.php"><img src="img/kibundesagasu1.gif" width="67" height="15" border="0"></a></td>
              </tr>
              <tr>
                <td><a href="mitsukuroi/mitsukuroi_1.php"><img src="img/melmaganavi1.gif" width="113" height="15" border="0"></a></td>
              </tr>
              <tr>
                <td><a href="search/kensaku-ranking.php"><img src="img/ranking1.gif" width="102" height="15" border="0"></a></td>
              </tr>
              <!-- ���ޥ�������(e) -->
            </table>
          </td>
        </tr>
        <!-- �ޥ�����ѥ���Ͽ(s) -->
        <tr> 
          <td height="60"><br>
            <a href="melonpan/melonpatoha.php"><img src="img/meronpantoroku.gif" width="131" height="20" border="0"></a> 
            <br>
          </td>
        </tr>
        <!-- �ޥ�����ѥ���Ͽ(e) -->
        <tr> 
          <td><img src="img/hashinkyoku.gif" width="131" height="17"></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"> 
            <table width="131" border="0" cellspacing="2" cellpadding="0">
              <!-- ���ޥ�ȯ����(s) -->
              <tr>
                <td><a href="publisher/kiyaku.html"><img src="img/hakou1.gif" width="123" height="15" border="0"></a></td>
              </tr>
              <tr>
                <td><a href="publisher/hakkou-login.html"><img src="img/kanripage1.gif" width="113" height="15" border="0"></a></td>
              </tr>
              <!-- ���ޥ�ȯ����(e) -->
            </table>
          </td>
        </tr>
        <tr> 
          <td><font size="2"><br><br>
            <font color="#009900">��</font>�ؤ���Ѥ�٤Υ��ޥ��ϡ����٤�̵���Ǥ����ɤ��������ޤ��� 
            <br><br>
            </font></td>
        </tr>
        <!-- �쥿�����(s) -->
        <tr> 
          <td><font size="2"> <font color="#009900">��</font>�ؤ���Ѥ�쥿�������塦����������ޥ��Ҳ���ޥ��١ؤ���Ѥ󥨡���٤β�����ƹ��ɤ� 
            <a href="letter/letterbaqn.php"><font color="#CC0000">������</font></a> 
            <br>
            <br>
            </font></td>
        </tr>
        <!-- �쥿�����(e) -->
        <tr> 
          <td><font size="2" color="#CC0000">�ؤ���Ѥ�٤Υ��ޥ����ɼԤ����ˤϡ��ʳ��ˡ������������λ�����ǰ�ΰ�ĤȤ��ơ�ȯ����������Ա���ޥ�����ؤ���Ѥ󥨡���١ס�����Ѥ�Ǥο��塦��������Ҳ���ޥ��Ҳ�ޥ�����ؤ���Ѥ�쥿���٤���ưŪ����������ޤ�������������Ԥ�������˱��礷�Ʋ��������ۿ������٤ϣ��󡿽����٤Ǥ���<br>
            �ɤ�������λ������������</font><font size="2"><br>
            </font></td>
        </tr>
      </table>
<br>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>
<font size="2"><font color="#009900">��</font>����Ѥ��<br>����󥯥ե꡼�Ǥ���<br></font>
<img src="img/banner.gif" border="0">
</td>
</tr>
<tr><td><br></td></tr>
<tr>
<td>
<font size="2"><font color="#009900">��</font>���䤤��碌��<br>��<A href="mailto:info@melonpan.net">info@melonpan.net</A><br>�Ǽ����դ��Ƥ���ޤ���</font>
</td>
</tr>
<tr><td><br><br><br></td></tr>
<tr>
</tr>
</table>
</td>
<td width="22">��</td>
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
   ����Ͽ���ޥ���&gt;<?php echo number_format($m_counter["���ޥ���"]); ?>��
 | ���ɼԿ�&gt;<?php echo number_format($m_counter["���ɼԿ�"]); ?>�� 
 | ��ȯ������&gt;<?php echo number_format($m_counter["��ȯ������"]); ?>��
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
<td width="21">��</td>
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
<td align="left"> <font size="1"><img src="img/magazinemenu.gif" width="142" height="17">��</font></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td bgcolor="#ffffc0" width="100%"> 
<table width="571" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="16">��</td>
<td width="555"><br>
<table width="555" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><img src="img/bar_1.gif" width="555" height="2"></td>
</tr>
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="0" width="555">
<tr> 
<td bgcolor="#e2f0c9" width="80" height="17" align="center"><font size="1"> �ޡ����θ�����</font></td>
<td bgcolor="#e2f0c9" width="475" height="17" valign="middle"> 
<table width="475" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="150"><img src="img/shincyaku2.gif" width="75" height="13" vspace="3" hspace="3"></td>
<td width="150"><img src="img/osusume2.gif" width="97" height="14" vspace="3" hspace="3"></td>
<td width="175"><img src="img/otanjobi2.gif" width="122" height="15" vspace="3" hspace="3"></td>
</tr>
<tr>
<td width="150"><img src="img/kari2.gif" width="84" height="13" vspace="3" hspace="3"></td>
<td width="150"><img src="img/osusumekako2.gif" width="97" height="14" vspace="3" hspace="3"></td>
<td width="175"><img src="img/original2.gif" width="115" height="15" vspace="3" hspace="4"></td>
</tr>
</table>
</td>
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
<!-- �����������(s) -->
<?php
	mag_listup( "2");
?>
<!-- �����������(e) -->
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
	// �ɤ�ɤ�
	mag_listup( "5");
?>
</table>
</td>
</tr>
</table>
<!--����Фå���
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
<td bgcolor="#ffffff"><font size="2">���ȯ�Υ��ޥ��Ǥ���<br>
�������޽�����Ǥ���<br>
�ɤ�����ǥݥ���Ȥ����ޤ�ޤ���<br>
���¦����˾����°�����������ɤ�Ǥ����������ȤϤǤ��ޤ���<br>
</font></td>
</tr>
</table>
</td>
</tr>
</table>
��Фå��㡡-->
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
	// ���塦����Ͽ
	mag_listup( "3");
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
	// ���塦����Ͽ
	mag_listup( "1");
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
echo sel_banner(".");
?>
<br>
<font size="1">������Ѥ�ϡ��ʳ��ˡ������������λ�����ǰ��ȯ����������Ԥ�ٱ礹��פ˴�Ť��Ʊ��Ĥ���Ƥ��ޤ���</font> 
<br>
<br>
<table width="600" border="0" cellpadding="0" cellspacing="3" bordercolor="#CCCCCC" bgcolor="#CCCCCC">
  <tr> 
    <td>
      <table width="594" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
        <tr valign="bottom"> 
          <td colspan="2"> <span style="font-size: 9pt; font-weight: normal;"><img src="image/space.gif" width="396" height="1"></span><span style="font-size: 8pt; font-weight: normal;"><a href="http://privacymark.jp" target="_blank"><br>
            <img src="img/pmark_s.gif" width="50" height="50" border="0" align="left"><br>
            </a><br>
            ������ҡ������������ϥץ饤�Х����ޡ�����������Ƥ��ޤ���<br>
          ���ҤΡָĿ;����ݸ����ˡפ�<a href="http://www.xxxxxxx.co.jp/info/privacypolicy.html" target="_blank">������</a>�Ǥ���</span></td>
          <td width="198"><span style="font-size: 9pt; font-weight: normal;"><img src="image/space.gif" width="198" height="5"><br>
            <img src="image/f_arrow.gif" width="12" height="12"><span style="font-size: 9pt; font-weight: normal;"><a href="http://www.xxxxxxx.co.jp/" target="_blank">�ޡ��������γ��ͤءʲ�ҳ��ס�</a></span><br>
            <img src="image/f_arrow.gif" width="12" height="12"><span style="font-size: 9pt; font-weight: normal;"><a href="http://www.xxxxxxx.co.jp/monitor/" target="_blank">����Ԥγ��ͤءʥ�˥�����Ͽ��</a></span><br>
            <img src="image/f_arrow.gif" width="12" height="12"><span style="font-size: 9pt; font-weight: normal;"><a href="http://www.melonpan.net/rule_pesonal.html">�����֥����ȥץ饤�Х����ݥꥷ��</a></span></span></td>
        </tr>
        <tr> 
          <td colspan="3"> 
            <hr size="1" noshade>
          </td>
        </tr>
        <tr> 
          <td valign="top" colspan="3"> 
            <div align="center"><span style="font-size: 9pt; font-weight: normal;">Copyright 
              2006 xx xxxxx Inc. All Rights Reserved. �ܥ����Ȥϡ�<a href="http://www.xxxxxxx.co.jp/" target="_blank">������ҡ�����������</a>�ˤ�걿�Ĥ���Ƥ���ޤ���</span></div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>	
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
