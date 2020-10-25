<?php
include ("inc/com_db_func2.inc");
$mae = strftime("%Y.%m.%d ",time() - (14*24*60*60));
$now_time = strftime("%Y年%m月%d日 ",time());
$now_time2 = strftime("%Y/%m/%d %T",time());
$mae2 = strftime("%Y/%m/%d %T",time() - (7*24*60*60));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<TITLE></TITLE>
<link rel="stylesheet" href="css/melonpan.css">
<SCRIPT Language="JavaScript">
</SCRIPT>
<style type="text/css">
<!--
body {  text-decoration: none}
a:link {  color: #000099}
a:visited {  color: #009966}
-->
</style>
</HEAD>

<BODY bgcolor="#FFFFFF">
<TABLE width="750" border="0" cellspacing="0" cellpadding="0">
<TR> 
<TD WIDTH="590" align="center" valign="top"> <!----メロンパンニュースここから-----> 
<TABLE width="573" border="0" cellspacing="2" cellpadding="0">
<TR> 
<TD><img width="565" height="24" src="img/t_news.gif" alt="What's New"></TD>
</TR>
<TR align="center"> 
<TD width="490"> 
<TABLE width="450" border="0" cellspacing="2" cellpadding="0">
<TR> 
<TD> <?php
	$error_msg = "";

	$conn = db_conxxxt();
	$sql = "select * from T_WHATS_NEW order by modify_dt desc";
	$stmt = OCIParse ($conn, $sql);
	OCIExecute ($stmt, OCI_DEFAULT);
		$cnt = 0;
		for ($i=0; OCIFetchinto($stmt, &$resarray); $i++){
		if($resarray[2] == "" || $resarray[2] == null){
			print "<FONT color=\"#FF9900\">■</FONT>$resarray[1]<BR>\n";
		}else{
			print "<FONT color=\"#FF9900\">■</FONT>";
			print "<a href=\"$resarray[2]\" target=\"$resarray[4]\">$resarray[1]</a><BR>\n";
		}
		}
		db_logoff () ;
?> </TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
<!----メロンパンニュースここまで-----><BR>
<TABLE width="573" border="0" cellspacing="2" cellpadding="0">
<TR> 
<TD><IMG src="img/t_search.gif" width="565" height="25" alt="メルマガサーチ"></TD>
</TR>
<TR align="center"> 
<TD width="490"> 
<TABLE width="450" border="0" cellspacing="0" cellpadding="0" align="center">
<TR> 
<TD><a href="melonpa/category-list.php"><IMG src="img/search_kategory.gif" width="144" height="21" border="0" alt="カテゴリで探したい"></a><BR>
</TD>
<TD><a href="melonpa/feeling-list.php"><IMG src="img/search_kibun.gif" width="144" height="21" border="0" alt="気分で見つけたい"></a></TD>
</TR>
<TR> 
<TD><a href="melonpa/muke-list.php"><IMG src="img/search_watashi.gif" width="164" height="21" border="0" alt="私向けのメルマガを探したい"></a></TD>
<TD><a href="melonpa/mitsukuroi_0.php"><IMG width="270" height="20" src="img/search_melonpai.gif" border="0" alt="私の読みたいメルマガをみつくろって（めろんパイ）"></a></TD>
</TR>
<TR> 
<TD colspan="2"><IMG width="130" height="20" src="img/search_keyword.gif" alt="キーワードサーチ"></TD>
</TR>
<TR> 
<TD colspan="2"> 
<FORM method="post" action="melonpa/search_result.php">
<INPUT type="hidden" name="mode" value="word">
<INPUT type="text" name="keyword" size="30">
<INPUT type="submit" name="kensaku" value="検索">
</FORM>
</TD>
</TR>
</TABLE>
</TD>
</TR>
<TR align="center"> 
<TD><IMG src="img/line.gif" width="560" height="8"></TD>
</TR>
</TABLE>
<TABLE WIDTH="580" BORDER="0" CELLSPACING="5" CELLPADDING="0">
<TR VALIGN="TOP"> 
<TD WIDTH="290"><img src="img/t_osusume.gif" width="162" height="28" alt="ただいまオススメ！"> 
<br>
<?php
	$error_msg = "";

	$conn = db_conxxxt();
	$sql = "select mag_nm,new_flg,mag_id ,osusume_flg,mag_pub_status_flg,mag_riba_status_flg,mag_pub_status_dt from T_MAG_TOP WHERE TOP_ID = 2 order by mag_id desc";
	$stmt = OCIParse ($conn, $sql);
	OCIExecute ($stmt, OCI_DEFAULT);
		$cnt = 0;
		for ($i=0; OCIFetchinto($stmt, &$resarray); $i++){

		if($cnt == 20){ break;}
		$mark = "";
		if($resarray[3] == 1){ $mark = "<img src=\"img/mark-rec.gif\">";}
		if($resarray[1] == 1) { $mark .= "<img src=\"img/mark-new.gif\">";}
		if($resarray[4] == "01"){ $mark .= "<img src=\"img/mark-kari.gif\">";}
		if($resarray[5] == 1){ $mark .= "<img src=\"img/mark-rib.gif\">";}
			print $mark."<FONT size=2>";
			print "<a href=\"melonpa/mag-detail.php?mag_id=".$resarray[2];
			if($resarray[5] == 1){
				print "&riba=1\">";
			}else{
				print "\">";
			}
			print $resarray[0]."(".$resarray[6].")</a></FONT><BR>\n";
		$cnt++;
		}
		db_logoff () ;
		if($cnt == 0){
			print "<br><font size=\"2\">めろんぱんオススメのメルマガです。<br>ただいま準備中です。<br>現在めろんパイ（めろんぱんパイロット）が一生懸命メルマガを読んでいるところです。今しばらくお待ちください。<br></font>\n";
		}
?>
</font>
</TD>

<TD WIDTH="290"><FONT SIZE="2"><IMG SRC="img/t_new_hontouroku.gif" WIDTH="165" HEIGHT="28" ALT="新着メルマガ/本登録・最新順"><BR>
<?php
	$error_msg = "";

	$conn = db_conxxxt();
	$sql = "select mag_nm,new_flg,mag_id ,osusume_flg,mag_pub_status_flg,mag_riba_status_flg,mag_pub_status_dt from T_MAG_TOP WHERE TOP_ID = 3 order by CNT";
	$stmt = OCIParse ($conn, $sql);
	OCIExecute ($stmt, OCI_DEFAULT);
		$cnt = 0;
		for ($i=0; OCIFetchinto($stmt, &$resarray); $i++){

		if($cnt == 20){ break;}
		$mark = "";
		if($resarray[3] == 1){ $mark = "<img src=\"img/mark-rec.gif\">";}
		if($resarray[1] == 1) { $mark .= "<img src=\"img/mark-new.gif\">";}
		if($resarray[4] == "01"){ $mark .= "<img src=\"img/mark-kari.gif\">";}
		if($resarray[5] == 1){ $mark .= "<img src=\"img/mark-rib.gif\">";}
			print $mark."<FONT size=2>";
			print "<a href=\"melonpa/mag-detail.php?mag_id=".$resarray[2];
			if($resarray[5] == 1){
				print "&riba=1\">";
			}else{
				print "\">";
			}
			print $resarray[0]."(".$resarray[6].")</a></FONT><BR>\n";
		$cnt++;
		}
		db_logoff () ;
?> </FONT></TD>
</TR>
<TR VALIGN="TOP">
<TD WIDTH="290"><img src="img/t_dokusha.gif" width="166" height="27" alt="どんどん読者が増えてるよ！"><br>
<?php
	$error_msg = "";

	$conn = db_conxxxt();
	$sql = "select mag_nm,new_flg,mag_id ,osusume_flg,mag_pub_status_flg,mag_riba_status_flg,mag_pub_status_dt,(mag_count - send_volume) as dondon_num from T_MAG_DONDON where send_volume <> 0 order by dondon_num desc";
	$stmt = OCIParse ($conn, $sql);
	OCIExecute ($stmt, OCI_DEFAULT);
		$cnt = 0;
		for ($i=0; OCIFetchinto($stmt, &$resarray); $i++){
		if($cnt == 20){ break;}
		$mark = ""; $flg = 0;
		if($resarray[3] == 1){ $mark = "<img src=\"img/mark-rec.gif\">"; $flg = 1;}
		if($resarray[1] == 1) { $mark .= "<img src=\"img/mark-new.gif\">"; $flg = 1;}
		if($resarray[4] == "01"){ $mark .= "<img src=\"img/mark-kari.gif\">"; $flg = 1;}
		if($resarray[5] == 1){ $mark .= "<img src=\"img/mark-rib.gif\">"; $flg = 1;}
		 if($flg == 0){$mark .= "<img src=\"img/white.gif\">";}
			print $mark."<FONT size=2>";
			print "<a href=\"melonpa/mag-detail.php?mag_id=".$resarray[2];
			if($resarray[5] == 1){
				print "&riba=1\">";
			}else{
				print "\">";
			}
			print $resarray[0]."(".$resarray[7].")</a></FONT><BR>\n";
		$cnt++;
		}
		db_logoff () ;
?></TD>
<TD WIDTH="290"><FONT SIZE="2"><IMG SRC="img/t_new_kari.gif" WIDTH="165" HEIGHT="28" ALT="新着メルマガ/仮登録・最新順"> 
<BR>
<?php
	$error_msg = "";

	$conn = db_conxxxt();
	$sql = "select mag_nm,new_flg,mag_id ,osusume_flg,mag_pub_status_flg,mag_riba_status_flg,mag_pub_status_dt from T_MAG_TOP WHERE TOP_ID = 1 order by CNT";
	$stmt = OCIParse ($conn, $sql);
	OCIExecute ($stmt, OCI_DEFAULT);
		$cnt = 0;
		for ($i=0; OCIFetchinto($stmt, &$resarray); $i++){

		if($cnt == 20){ break;}
		$mark = "";
		if($resarray[3] == 1){ $mark = "<img src=\"img/mark-rec.gif\">";}
		if($resarray[1] == 1) { $mark .= "<img src=\"img/mark-new.gif\">";}
		if($resarray[4] == "01"){ $mark .= "<img src=\"img/mark-kari.gif\">";}
		if($resarray[5] == 1){ $mark .= "<img src=\"img/mark-rib.gif\">";}
			print $mark."<FONT size=2>";
			print "<a href=\"melonpa/mag-detail.php?mag_id=".$resarray[2];
			if($resarray[5] == 1){
				print "&riba=1\">";
			}else{
				print "\">";
			}
			print $resarray[0]."(".$resarray[6].")</a></FONT><BR>\n";
		$cnt++;
		}
		db_logoff () ;
?> </FONT></TD>
</TR>
<TR> 
<TD WIDTH="290"><img src="img/t_ribaccha.gif" width="162" height="30" alt="読めばポイントが貯まる逆有料メルマガ「りばっチャ」"></TD>
<TD WIDTH="290"> </TD>
</TR>
<TR VALIGN="TOP"> 
<TD WIDTH="290"><font size="2">企業発のメルマガです。<br>
ただいま準備中です。<br>
読むだけでポイントが貯まります。<br>
企業側が希望する属性の方しか読んでいただくことはできません。</font><BR>
</TD>
<TD WIDTH="290"> <BR>

</TD>
</TR>
</TABLE>
<TABLE WIDTH="590" BORDER="0" CELLSPACING="0" CELLPADDING="1">
<TR> 
<TD WIDTH="90" hight="14" BGCOLOR="#339900"> 
<DIV ALIGN="center"><FONT COLOR="#FFCC00" CLASS="text9ptnoColor"><B>マークの見方</B></FONT></DIV>
</TD>
<TD WIDTH="510" CLASS="text9ptnoColor">
<IMG SRC="img/mark-kari.gif" WIDTH="13" HEIGHT="13">仮登録メルマガ｜
<IMG SRC="img/mark-new.gif" WIDTH="13" HEIGHT="13">新着（新規創刊）｜
<IMG SRC="img/mark-rec.gif" WIDTH="13" HEIGHT="13">めろんパイおすすめ｜
<IMG SRC="img/mark-rib.gif" WIDTH="13" HEIGHT="13">りばっチャメルマガ｜</TD>
</TR>
</TABLE>
<P><IMG SRC="img/line.gif" WIDTH="560" HEIGHT="8"></P>
<P><SPAN CLASS="text8ptnoColor">※melonpan.netは、<A HREF="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" TARGET="_top">（株）○○○○○○</A>の事業理念「発信する生活者を支援する」に基づいて運営されています。</SPAN></P>
</TD>
<TD WIDTH="10" align="center" valign="top"><IMG SRC="img/shim.gif" WIDTH="10" HEIGHT="1"></TD>
<TD align="center" valign="top" width="150" bgcolor="#DEEBBE"> 
<p> <?php echo $now_time; ?> </p>
<p><span class="text9ptnoColor"> <?php
	$conn = db_conxxxt();
		$sql = "SELECT SUM FROM L_MAG_SUM WHERE SUM_ID = 1";
		$stmt = OCIParse ($conn, $sql);
		OCIDefinebyname ($stmt, "SUM", &$sum_volume);
		$ncols = OCIExecute ($stmt, OCI_DEFAULT);
		if (OCIFetch($stmt)) {
		}
		$sql = "SELECT SUM FROM L_MAG_SUM WHERE SUM_ID = 2";
		$stmt = OCIParse ($conn, $sql);
		OCIDefinebyname ($stmt, "SUM", &$m_magcount);
		$ncols = OCIExecute ($stmt, OCI_DEFAULT);
		if (OCIFetch($stmt)) {
		}
		$sql = "SELECT SUM FROM L_MAG_SUM WHERE SUM_ID = 3";
		$stmt = OCIParse ($conn, $sql);
		OCIDefinebyname ($stmt, "SUM", &$m_mailcount);
		$ncols = OCIExecute ($stmt, OCI_DEFAULT);
		if (OCIFetch($stmt)) {
		}
		print "[メルマガ数：".$m_magcount." 誌] <BR>\n";
		print "[総読者数：".$m_mailcount." 人]<BR>\n";
		print "[週発行部数：".$sum_volume." 部]<BR>\n";
	db_logoff () ;

 ?> </span> </p>
<P><FONT size="2" color="#006600">-<B>今日のひとりごと</B>-<BR>
<?php
	$conn = db_conxxxt();
	$today = date("Ymd");

	$sql = "select admin_msg from T_ADMIN_MSG where access_day = '$today'";
	$stmt = OCIParse ($conn, $sql);
	OCIDefinebyname ($stmt, "ADMIN_MSG", &$admin_msg);
	OCIExecute ($stmt, OCI_DEFAULT);
	$ret = OCIFetchStatement($stmt,&$resarray);
	if($ret == 0){
		OCIFreeStatement($stmt);
		$sql = "select admin_msg from T_ADMIN_MSG where access_day <= '$today' order by access_day desc";
		$stmt = OCIParse ($conn, $sql);
		OCIDefinebyname ($stmt, "ADMIN_MSG", &$admin_msg);
		OCIExecute ($stmt, OCI_DEFAULT);
		OCIFetch($stmt);
		print $admin_msg;
	}else{
		print $admin_msg;
	}
	OCIFreeStatement($stmt);
	db_logoff () ;

 ?> </FONT></P>

<!-- VC square -->
<A HREF="http://kansas.valueclick.ne.jp/redirect?host=hs0037310&size=125x125&b=indexpage&v=0" TARGET="_top"><IMG BORDER="0" WIDTH="125" HEIGHT="125" ALT="Click here to visit our sponsor" SRC="http://kansas.valueclick.ne.jp/cycle?host=hs0037310&size=125x125&b=indexpage&noscript=1"></A>
<!-- vc square -->

<HR width="50%" size="1">
<IMG src="img/t_mypagelogin.gif" width="139" height="29" alt="マイページログイン"><BR>
<FORM method="post" action="melonpa/MelonpanRef.php">
<span class="text9ptnoColor">メールアドレス </span> 
<INPUT type="text" name="mail_addr">
<BR>
<span class="text9ptnoColor">パスワード </span><BR>
<INPUT type="password" name="reader_pswd" size="8">
<input type="image" src="img/login.gif" border="0" alt="ログイン">
<BR>
<input type="hidden" value="login1" name="PROCESS_FLG">
</FORM>
<p><IMG width="137" height="27" src="img/t_melonshinkitouroku.gif" alt="Myめろんぱん新規登録"><BR>
<a href="melonpa/regist.html"><IMG src="img/m_melonshinkitouroku.gif" width="139" height="32" border="0" alt="新規登録"></a> 
<BR>
<IMG width="137" height="27" src="img/t_mlhasshinkyoku.gif" alt="メルマガ発信局"><BR>
<a href="publisher/kiyaku.html"><IMG src="img/m_melmagahakkou.gif" width="139" height="31" border="0" alt="メルマガを発行する！"></a><BR>
<a href="publisher/hakkou-login.html"><IMG src="img/m_hakkoushakanri.gif" width="139" height="30" border="0" alt="発行者用管理ページ"></a> 
<BR>
<IMG src="img/t_pointget.gif" width="139" height="28" alt="めろんぱんポイントGET！"><BR>
<a href="melonpa/MelonpanRef.php" target="contents"><IMG width="139" height="43" src="img/m_pointget.gif" alt="お気に入りのメルマガの推薦文を書く！" border="0"></a><BR>
<a href="melonpa/MelonpanRef.php?rmbott20401=ちょっと見てみる"><IMG src="img/m_pointcheck.gif" width="139" height="40" alt="どのくらいポイント貯まったかな？" border="0"></a><BR>
</p>
<p align="left"> <img src="img/t_melopantoha.gif" width="100" height="14" alt="めろんぱんとは？"><br>
<span class="text9ptnoColor">　<a href="forreaders.html">・読者にとっての</a><br>
　 　　<a href="forreaders.html">めろんぱんの特徴</a><br>
　<a href="trait.html">・発行者にとっての</a><br>
　 　　<a href="trait.html">めろんぱんの特徴</a><br>
　<a href="faq.html">・メルマガって何？</a><br>
　<font color="#666666" class="text8ptnoColor">…だんだん充実させます</font><br>
　<a href="dream.html">・めろんぱんの夢</a><br>
　<a href="rule_pesonal.html">・個人情報保護に関して </a></span><BR>
</p>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
<tr> 
<td><span class="text8ptnoColor"><font color="#009900">●</font>『バ・キューン』『めろんぱんレター～新着・おすすめメルマガ紹介メルマガ』の解除・再購読は<a href="melonpaletter.php">こちら</a><br>
</span><br>
<font color="#990000" class="text8ptnoColor">『めろんぱん』のメルマガの読者の方には、（株）○○○○○○の事業理念の一環として「発信する生活者応援マガジン『バ・キューン』」、めろんぱんでの新着・おすすめ紹介メルマガ紹介マガジン『めろんぱんレター』が自動的に送信されます。元気な生活者たちを一緒に応援してください。配信の頻度は１回／週程度です。どうぞ、ご了承ください。</font> 
</td>
</tr>


<tr>
<td>
<br>
<span class="text8ptnoColor"><font color="#009900">●</font>めろんぱんは<br>　リンクフリーです。<br>
<img src="img/banner.gif" border="0">
</td>
</tr>

<br>

<tr>
<td>
<br>
<span class="text8ptnoColor"><font color="#009900">●</font>お問い合わせは<br>　<A href="mailto:info@melonpan.net">info@melonpan.net</A><br>　で受け付けております。
</td>
</tr>

</table>
</TD>
</TR>
<TR> 
<TD colspan="3" valign="top"> 
<HR SIZE="1">
<P ALIGN="center"><FONT SIZE="2">Copyright &copy; 2000 xxxxxxx, Co.,Ltd. All rights 
reserved. <BR>
<A HREF="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" TARGET="_top"><IMG SRC="img/logo_do.gif" WIDTH="50" HEIGHT="45" ALIGN="middle" BORDER="0"></A> 
</FONT></P>
</TD>
</TR>
</TABLE>
<p align="center"><font size="2"> </font></p>

</BODY>
</HTML>
