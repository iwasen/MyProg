<?php
include ("inc/com_db_func2.inc");
$mae = strftime("%Y.%m.%d ",time() - (14*24*60*60));
$now_time = strftime("%Yǯ%m��%d�� ",time());
$now_time2 = strftime("%Y/%m/%d %T",time());
$mae2 = strftime("%Y/%m/%d %T",time() - (7*24*60*60));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=X-EUC">
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
<TD WIDTH="590" align="center" valign="top"> <!----����ѥ�˥塼����������-----> 
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
			print "<FONT color=\"#FF9900\">��</FONT>$resarray[1]<BR>\n";
		}else{
			print "<FONT color=\"#FF9900\">��</FONT>";
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
<!----����ѥ�˥塼�������ޤ�-----><BR>
<TABLE width="573" border="0" cellspacing="2" cellpadding="0">
<TR> 
<TD><IMG src="img/t_search.gif" width="565" height="25" alt="���ޥ�������"></TD>
</TR>
<TR align="center"> 
<TD width="490"> 
<TABLE width="450" border="0" cellspacing="0" cellpadding="0" align="center">
<TR> 
<TD><a href="melonpa/category-list.php"><IMG src="img/search_kategory.gif" width="144" height="21" border="0" alt="���ƥ����õ������"></a><BR>
</TD>
<TD><a href="melonpa/feeling-list.php"><IMG src="img/search_kibun.gif" width="144" height="21" border="0" alt="��ʬ�Ǹ��Ĥ�����"></a></TD>
</TR>
<TR> 
<TD><a href="melonpa/muke-list.php"><IMG src="img/search_watashi.gif" width="164" height="21" border="0" alt="������Υ��ޥ���õ������"></a></TD>
<TD><a href="melonpa/mitsukuroi_0.php"><IMG width="270" height="20" src="img/search_melonpai.gif" border="0" alt="����ɤߤ������ޥ���ߤĤ���äơʤ���ѥ���"></a></TD>
</TR>
<TR> 
<TD colspan="2"><IMG width="130" height="20" src="img/search_keyword.gif" alt="������ɥ�����"></TD>
</TR>
<TR> 
<TD colspan="2"> 
<FORM method="post" action="melonpa/search_result.php">
<INPUT type="hidden" name="mode" value="word">
<INPUT type="text" name="keyword" size="30">
<INPUT type="submit" name="kensaku" value="����">
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
<TD WIDTH="290"><img src="img/t_osusume.gif" width="162" height="28" alt="�������ޥ������ᡪ"> 
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
			print "<br><font size=\"2\">����Ѥ󥪥�����Υ��ޥ��Ǥ���<br>�������޽�����Ǥ���<br>���ߤ���ѥ��ʤ���Ѥ�ѥ���åȡˤ�������̿���ޥ����ɤ�Ǥ���Ȥ���Ǥ��������Ф餯���Ԥ�����������<br></font>\n";
		}
?>
</font>
</TD>

<TD WIDTH="290"><FONT SIZE="2"><IMG SRC="img/t_new_hontouroku.gif" WIDTH="165" HEIGHT="28" ALT="������ޥ�/����Ͽ���ǿ���"><BR>
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
<TD WIDTH="290"><img src="img/t_dokusha.gif" width="166" height="27" alt="�ɤ�ɤ��ɼԤ������Ƥ�衪"><br>
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
<TD WIDTH="290"><FONT SIZE="2"><IMG SRC="img/t_new_kari.gif" WIDTH="165" HEIGHT="28" ALT="������ޥ�/����Ͽ���ǿ���"> 
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
<TD WIDTH="290"><img src="img/t_ribaccha.gif" width="162" height="30" alt="�ɤ�Хݥ���Ȥ����ޤ��ͭ�����ޥ��֤�Фå����"></TD>
<TD WIDTH="290"> </TD>
</TR>
<TR VALIGN="TOP"> 
<TD WIDTH="290"><font size="2">���ȯ�Υ��ޥ��Ǥ���<br>
�������޽�����Ǥ���<br>
�ɤ�����ǥݥ���Ȥ����ޤ�ޤ���<br>
���¦����˾����°�����������ɤ�Ǥ����������ȤϤǤ��ޤ���</font><BR>
</TD>
<TD WIDTH="290"> <BR>

</TD>
</TR>
</TABLE>
<TABLE WIDTH="590" BORDER="0" CELLSPACING="0" CELLPADDING="1">
<TR> 
<TD WIDTH="90" hight="14" BGCOLOR="#339900"> 
<DIV ALIGN="center"><FONT COLOR="#FFCC00" CLASS="text9ptnoColor"><B>�ޡ����θ���</B></FONT></DIV>
</TD>
<TD WIDTH="510" CLASS="text9ptnoColor">
<IMG SRC="img/mark-kari.gif" WIDTH="13" HEIGHT="13">����Ͽ���ޥ���
<IMG SRC="img/mark-new.gif" WIDTH="13" HEIGHT="13">����ʿ����ϴ��ˡ�
<IMG SRC="img/mark-rec.gif" WIDTH="13" HEIGHT="13">����ѥ����������
<IMG SRC="img/mark-rib.gif" WIDTH="13" HEIGHT="13">��Фå�����ޥ���</TD>
</TR>
</TABLE>
<P><IMG SRC="img/line.gif" WIDTH="560" HEIGHT="8"></P>
<P><SPAN CLASS="text8ptnoColor">��melonpan.net�ϡ�<A HREF="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" TARGET="_top">�ʳ��ˡ�����������</A>�λ�����ǰ��ȯ����������Ԥ�ٱ礹��פ˴�Ť��Ʊ��Ĥ���Ƥ��ޤ���</SPAN></P>
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
		print "[���ޥ�����".$m_magcount." ��] <BR>\n";
		print "[���ɼԿ���".$m_mailcount." ��]<BR>\n";
		print "[��ȯ��������".$sum_volume." ��]<BR>\n";
	db_logoff () ;

 ?> </span> </p>
<P><FONT size="2" color="#006600">-<B>�����ΤҤȤꤴ��</B>-<BR>
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
<IMG src="img/t_mypagelogin.gif" width="139" height="29" alt="�ޥ��ڡ���������"><BR>
<FORM method="post" action="melonpa/MelonpanRef.php">
<span class="text9ptnoColor">�᡼�륢�ɥ쥹 </span> 
<INPUT type="text" name="mail_addr">
<BR>
<span class="text9ptnoColor">�ѥ���� </span><BR>
<INPUT type="password" name="reader_pswd" size="8">
<input type="image" src="img/login.gif" border="0" alt="������">
<BR>
<input type="hidden" value="login1" name="PROCESS_FLG">
</FORM>
<p><IMG width="137" height="27" src="img/t_melonshinkitouroku.gif" alt="My����Ѥ󿷵���Ͽ"><BR>
<a href="melonpa/regist.html"><IMG src="img/m_melonshinkitouroku.gif" width="139" height="32" border="0" alt="������Ͽ"></a> 
<BR>
<IMG width="137" height="27" src="img/t_mlhasshinkyoku.gif" alt="���ޥ�ȯ����"><BR>
<a href="publisher/kiyaku.html"><IMG src="img/m_melmagahakkou.gif" width="139" height="31" border="0" alt="���ޥ���ȯ�Ԥ��롪"></a><BR>
<a href="publisher/hakkou-login.html"><IMG src="img/m_hakkoushakanri.gif" width="139" height="30" border="0" alt="ȯ�Լ��Ѵ����ڡ���"></a> 
<BR>
<IMG src="img/t_pointget.gif" width="139" height="28" alt="����Ѥ�ݥ����GET��"><BR>
<a href="melonpa/MelonpanRef.php" target="contents"><IMG width="139" height="43" src="img/m_pointget.gif" alt="����������Υ��ޥ��ο���ʸ��񤯡�" border="0"></a><BR>
<a href="melonpa/MelonpanRef.php?rmbott20401=����äȸ��Ƥߤ�"><IMG src="img/m_pointcheck.gif" width="139" height="40" alt="�ɤΤ��餤�ݥ�������ޤä����ʡ�" border="0"></a><BR>
</p>
<p align="left"> <img src="img/t_melopantoha.gif" width="100" height="14" alt="����Ѥ�Ȥϡ�"><br>
<span class="text9ptnoColor">��<a href="forreaders.html">���ɼԤˤȤäƤ�</a><br>
�� ����<a href="forreaders.html">����Ѥ����ħ</a><br>
��<a href="trait.html">��ȯ�ԼԤˤȤäƤ�</a><br>
�� ����<a href="trait.html">����Ѥ����ħ</a><br>
��<a href="faq.html">�����ޥ��äƲ���</a><br>
��<font color="#666666" class="text8ptnoColor">�Ĥ�����󽼼¤����ޤ�</font><br>
��<a href="dream.html">������Ѥ��̴</a><br>
��<a href="rule_pesonal.html">���Ŀ;����ݸ�˴ؤ��� </a></span><BR>
</p>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
<tr> 
<td><span class="text8ptnoColor"><font color="#009900">��</font>�إС����塼��١ؤ���Ѥ�쥿�������塦����������ޥ��Ҳ���ޥ��٤β�����ƹ��ɤ�<a href="melonpaletter.php">������</a><br>
</span><br>
<font color="#990000" class="text8ptnoColor">�ؤ���Ѥ�٤Υ��ޥ����ɼԤ����ˤϡ��ʳ��ˡ������������λ�����ǰ�ΰ�ĤȤ��ơ�ȯ����������Ա���ޥ�����إС����塼��١ס�����Ѥ�Ǥο��塦��������Ҳ���ޥ��Ҳ�ޥ�����ؤ���Ѥ�쥿���٤���ưŪ����������ޤ�������������Ԥ�������˱��礷�Ƥ����������ۿ������٤ϣ��󡿽����٤Ǥ����ɤ�������λ������������</font> 
</td>
</tr>


<tr>
<td>
<br>
<span class="text8ptnoColor"><font color="#009900">��</font>����Ѥ��<br>����󥯥ե꡼�Ǥ���<br>
<img src="img/banner.gif" border="0">
</td>
</tr>

<br>

<tr>
<td>
<br>
<span class="text8ptnoColor"><font color="#009900">��</font>���䤤��碌��<br>��<A href="mailto:info@melonpan.net">info@melonpan.net</A><br>���Ǽ����դ��Ƥ���ޤ���
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
