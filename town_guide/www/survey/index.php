<?
$inc = '../inc';
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");

// ��λ����
define('END_DATE', '2005-02-14 09:00:00');

// �ѥ�᡼������
$user_id = $_GET['id'];
$group_id = $_GET['gkey'];

// �ѥ�᡼��¸�ߥ����å�
if ($user_id == "" || $group_id == "")
	redirect('end.html');

// ��λ���������å�
if (time() > strtotime(END_DATE))
	redirect('end.html');

// �����Ѥߥ����å�
$sql = "SELECT COUNT(*) FROM t_ans_user WHERE au_user_id=" . sql_char($user_id);
if (db_fetch1($sql) != 0)
	redirect('end.html');

// �����Կ������å���ǯ�塦�ϰ����
$sql = "SELECT gr_mokuhyo_num,ar_todofuken,gr_nendai"
		. " FROM m_group"
		. " JOIN m_area ON ar_area_id=gr_area_id"
		. " WHERE gr_group_id=" . sql_char($group_id);
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	// Ʊ�쥰�롼�פβ����Կ�����
	$sql = "SELECT COUNT(*) FROM t_ans_user WHERE au_ans_group_id=" . sql_char($group_id);
	$answer_num = db_fetch1($sql);

	// �����Կ������å�
	if ($answer_num >= $fetch->gr_mokuhyo_num)
		redirect('end.html');

	// �ϰ�̾��ǯ������
	$todofuken = $fetch->ar_todofuken;
	$nendai = mb_convert_kana($fetch->gr_nendai, 'N');
} else
	redirect('end.html');

// URL�ѥ�᡼���򥯥å�������¸
setcookie('c_user_id', $user_id);
setcookie('c_group_id', $group_id);

// ���å��������
session_start();
$_SESSION['s_enquete'] = new enquete_class($user_id, $group_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta name="enqweb" CONTENT="Ver3.0">
<title>���󥱡��ȥȥåץڡ���</title>
<script type="text/javascript">
<!--
function go_enq(pm) {
var win, opt;
	opt = 'menubar=no, scrollbars=yes, location=no, resizable=yes, status=yes, toolbar=no';
  win = window.open('ctl.php', 'enq', opt);
  win.focus();
	document.all.go.style.display = "none";
}
// faq �����ץ�
function openfaq() {
	window.open('faq.html','','height=500,width=585,scrollbars=1,resizable=1')
}
//-->
</script>
<style type="text/css">
<!--
	.enqtitle{
		font-family:  "Osaka";
		font-size: 160%;
		font-weight: bold;
		color: #ffffff;
		line-height: 200%;
	}
	
	.t100{
		font-family:  "Osaka";
		font-size: 90%;
		line-height: 140%;
	}
	
	a:link{
		color : #0000ff;
		text-decoration: underline;
		}
	a:visited{
		text-decoration: none;
		color: #0000a0;
	}
	a:hover{
		color : #ff0000;
		text-decoration: underline;
		}
	a:active{
		color : #ff0000;
		text-decoration: underline;
		}
	}
-->
</style>
</head>
<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">
<div align="center"> 
<table border="0" cellspacing="1" cellpadding="0" width="750" bgcolor="#0b06ae">
	<tr> 
			<td> <table border="0" cellspacing="0" cellpadding="0" width="750" bgcolor="#ffffff">
				<tr> 
					<td> 
						<!-- title -->
						<table bgcolor="#0b06ae" align="center" width="100%">
							<tr> 
								<td class="enqtitle" align="center">�����󥬥��ɤ˴ؤ�����祢�󥱡���</td>
							</tr>
						</table>
						<!-- title -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- explain -->
						<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td align="center" class="t100"> 
								</td>
							</tr>
						</table>
						<!-- explain -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 1 -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
							<tr> 
								<td><img src="images/midashi1.gif" alt="���󥱡��Ȥˤ��������������ݤν����" width="500" height="35" border="0" hspace="0" vspace="0"></td>
							</tr>
						</table>
						<!-- 1 -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 1_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100"><b>�ڣ������֡�</b></td>
							</tr>
							<tr> 
								<td class="t100"> ͭ���������֤�<b><font color="#FF0000">��������ǯ��������ʷ�� ����</font></b>�ޤǤǤ��� </td>
							</tr>
						</table>
						<!-- 1_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 1_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100"><b>�ڣ���ͭ��������</b></td>
							</tr>
							<tr> 
								<td class="t100">ͭ�������ϡ������������ƤϤޤꡢ���Υ��󥱡��Ȥ�URL�����ܤ��줿�᡼��������ä����ܿͤ��ޡ�<br>
								����¤�Ȥ����Ƥ��������ޤ���</td>
							</tr>
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
							<tr> 
									<td class="t100"> <table align="center" width="80%" border="0" cellspacing="0" cellpadding="0">
										<tr> 
											<td class="t100" colspan="2">����������������</td>
										</tr>
										<tr> 
											<td width="10%" class="t100">&nbsp;</td>
											<td width="90%" class="t100" valign="top"> 
												<br>
												&nbsp;&nbsp;&nbsp;��<?=$todofuken?>�ˤ����ޤ�����
												<br>
												&nbsp;&nbsp;&nbsp;��<?=$nendai?>�����
												<br>
												&nbsp;&nbsp;&nbsp;�����Υ᡼��򡢥��󥿡��ޤ��ϻ�̳�ɤ���ľ�ܼ���ä��� <br>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���嵭�����������������Ǥ���Уϣˡ��� 
											<br>                        </td>
										</tr>
									</table></td>
							</tr>
						</table>
						<!-- 1_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 1_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100"><b>�ڣ��������</b></td>
							</tr>
							<tr> 
								<td class="t100"> 
									<font color=red>ͭ�������Ԥˤ����⤷�Ƥ���ޤ��������</font>�򺹤��夲�ޤ���
									<br>
									<br>�����μ���ϡ�����ϳ��ʤ��������������������Ȥ��ǧ�����庹���夲�ޤ���<br>
									�������䡢�������Ƥ����������ä����ʤɤϼ���򤪻�ʧ�����뤳�Ȥ��Ǥ��ʤ��ʤ�ޤ���<br>
									�������κݤϡ�����դ��������ޤ��褦���ꤤ�������ޤ���<br>
								<br>                  </td>
							</tr>
						</table>
						<!-- 1_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 2 -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
							<tr> 
								<td><img src="images/midashi2.gif" alt="�Ŀ;���ˤĤ��Ƥμ�갷��" width="500" height="35" border="0" hspace="0" vspace="0"></td>
							</tr>
						</table>
						<!-- 2 -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 2_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100"><b>�ڸĿ;����</b></td>
							</tr>
							<tr> 
								<td class="t100"> 
									<br>�ףţ¥��󥱡�����Ĵ����Ǥ�ʹ������Ŀ;���ϡ����С������ؤΥݥ������Ϳ��Ȱʳ��ˤ�<br>���Ѥ��ʤ����Ȥ���«�������ޤ���<br>
								�������������������Ƥϡ�������Ȥ��ä����׿����Ȥ��ƽ��פ������ޤ��Τǡ�<br>�����Ǥ򤪤������뤳�Ȥϰ��ڤ������ޤ���<br>                  </td>
							</tr>
						</table>
						<!-- 2_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 3 -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
							<tr> 
								<td><img src="images/midashi3.gif" alt="���Υ��󥱡��ȥ����ƥ�ˤĤ���" width="500" height="35" border="0" hspace="0" vspace="0"></td>
							</tr>
						</table>
						<!-- 3 -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
									<td class="t100"><table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
										<tr>	
											<td class="t100">���󡢤����⤵���Ƥ��������ޤ��ף�⥢�󥱡��ȤǤ�JavaScript�ˤ����������å���ԤäƤ���ޤ���<br>
												���Τ���֥饦���˥С������ˤ�äƤ��Զ�礬�Ǥ���⤴�����ޤ��ΤǤ��餫���ᤴλ������������<br>
												���������������ݤˡ��֥饦���ΥС������򤴳�ǧ�ξ塢�������򤪴ꤤ�������ޤ���<br>
												(���ʤ��Τ��Ȥ��δĶ��ˤĤ��Ƥϲ��Ρ֤��ʤ��Υѥ�����Ķ��פ򤴻��Ȥ���������)  
											</td>
										</tr>

										<tr>
											<td class="t100">
											</td>
										</tr>
										<tr>
											<td class="t100">
												<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0">
													<tr> 
														<td class="t100" colspan="2">�ڿ侩�֥饦����</td>
													</tr>
													<tr> 
														<td class="t100" width="10%"> </td>
														<td class="t100" width="90%">��Netscape��Navigator��7.0�ʹ�</td>
													</tr>
													<tr> 
														<td class="t100"> </td>
														<td class="t100">��Microsoft��Internet��Explorer��5.5�ʹ�</td>
													</tr>
													<tr> 
														<td class="t100" colspan="2">���Զ�礬ȯ�������ǽ��������֥饦����</td>
													</tr>
													<tr> 
														<td class="t100"> </td>
														<td class="t100">��Netscape Communicator4.x</td>
													</tr>
													<tr> 
														<td class="t100"> </td>
														<td class="t100">��Netscape��Naviator��6.X</td>
													</tr>
													<tr> 
														<td class="t100"> </td>
														<td class="t100">����MacOS��ǡ�Microsoft��Internet��Explorer���򤪻Ȥ�����</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td class="t100"> </td>
										</tr>
										<tr>
											<td valign="middle" class="t100">���ʤ������Υ��󥱡��Ȥ򤴲������������ˤ����ä�
												<img src="images/next.gif" alt="���μ���ܥ���" width="69" height="21" border="0" hspace="0" vspace="0">
											�ܥ����ɬ��<font color="#FF0000">�ڣ��������</font>����å����Ƥ���������</td>
										</tr>
										<tr>
											<td valign="middle" class="t100">������ʾ�Ϣ³�ǥ���å�����ȡ��ǡ�������������ʤ���礬����ޤ��� </td>
										</tr>

										<tr>
											<td class="t100">���ޤ����������������椷�Ƥ������ư��٤��ʤ뤳�Ȥ�����ޤ������餫���ᤴλ����������</td>
										</tr>
										<tr>
											<td class="t100" align="right"><a href="javascript:openfaq()" title="����¾�Τ褯�������Ϥ�����">��䤽��¾�Τ褯�������Ϥ�����</a></td>
										</tr>
										<tr>
											<td class="t100"> </td>
										</tr>
										<tr>
											<td class="t100"><hr size="1" width="80%" align="center" color="#0b06ae" noshade></td>
										</tr>
										<tr>
											<td class="t100"> </td>
										</tr>
										<tr>
											<td class="t100">�嵭�Τ������֤���¾���ɤ��������װʳ��ǡ������������������Թ�����ʤɤ���ޤ����顢�����᡼�륢�ɥ쥹�ޤǤ��䤤��碌���������ޤ��褦�����ꤤ�����夲�ޤ��� 
											<br></td>
										</tr>
										<tr>
											<td class="t100" align="right"><a href="mailto:e3info@xxxxxxx.co.jp?subject=%81w%83%5e%83E%83%93%83K%83C%83h%81x%82%c9%8a%d6%82%b7%82%e9%92%b2%8d%b8%8e%96%96%b1%8b%c7">�إ����󥬥��ɡ٤˴ؤ���Ĵ����̳��</td>
										</tr>
										<tr>
											<td class="t100"> </td>
										</tr>
										<tr>
											<td class="t100"><hr size="1" width="80%" align="center" color="#0b06ae" noshade></td>
										</tr>
									</table></td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 3_detail -->
						<!-- 3_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 4 -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
							<tr> 
								<td><img src="images/midashi4.gif" alt="���ʤ��Υѥ�����Ķ�" width="500" height="35" border="0" hspace="0" vspace="0"></td>
							</tr>
						</table>
						<!-- 4 -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 4_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">���Ȥ���OS��<b><font color="#0066CC"> 
                    <Script language="JavaScript">
<!--
var agent = navigator.userAgent ;
if ( agent.indexOf('Win') != -1 ){
userOs = "Windows";
}
else if ( agent.indexOf('Mac') != -1 ){
userOs = "MacOS" ;
}
else{
userOs = "����¾��OS" ;
}
document.write(userOs); 
//-->
</Script>
								</font></b> �Ǥ���</td>
							</tr>
							<tr> 
								<td class="t100">���Ȥ��Υ֥饦����<b><font color="#0066CC"> 
                    <Script language="JavaScript">
<!--
var ua = navigator.userAgent;

if (ua.match(/Opera[\/\s](\d\.\d+)/)) {				//(\d\.\d+)��RegExp.$1�ǻ���
	document.write("Opera "+RegExp.$1);			//Opera�ν���
}
else if (ua.match(/MSIE (\d\.\d+)/)) {
	document.write("Internet Explorer"+RegExp.$1);		//IE�ν���
}
else if (ua.match(/Mozilla\/(4\.[5678]\d?)/)) {
	document.write("Netscape Communicator"+RegExp.$1);	//Netscape Communicator4.x�ν���
}
else if (ua.match(/Netscape6\/(\d\.\d(\.\d)?)/)) {
	document.write("Netscape"+RegExp.$1);			//Netscape6�ν���
}
else if (ua.match(/Netscape\/(7[\.\d]+)/)) {
	document.write("Netscape"+RegExp.$1);			//Netscape7�ν���
}
else if (ua.match(/Gecko/)) {
	document.write("Mozilla");				//Mozilla�ν���
}
else {
	document.write("����¾�Υ֥饦��");				//�嵭�ʳ��Υ֥饦���ν���
}
// -->
</script>
								</font></b><font size="-1">�Ǥ���(Mac�ǤΣɣŤ�ɽ������ʤ���礬����ޤ���</font></td>
							</tr>
							<tr> 
								<td class="t100">�֥饦���Υ��å�����<b><font color="#0066CC"> 
                    <script language="JavaScript">
<!--
value1="ok";
setCookie("COOKIE",value1);
function setCookie(key,val){
tmp=key+"="+escape(value1)+";";
document.cookie=tmp;
}
// -->

<!--
value1=getCookie("COOKIE");
if(value1==""){value="<FONT COLOR='#cc0000'>̵���ˤʤäƤ��ޤ���</FONT>";}
if(value1=="ok"){value="ͭ���ˤʤäƤ��ޤ���";}
function getCookie(key){
tmp=document.cookie+";";
tmp1=tmp.indexOf(key,0);
if(tmp1!=-1){
tmp=tmp.substring(tmp1,tmp.length);
start=tmp.indexOf("=",0) + 1;
end = tmp.indexOf(";",start);
return(unescape(tmp.substring(start,end)));
}
return("");
}
document.write(value);
// -->
</script>
								</font></b></td>
							</tr>
							<tr> 
								<td class="t100">���Υ��󥱡��Ȥˤ������ˤʤ뤿��ˤϡ�JavaScript�ȥ��å�����ͭ���Ǥ��뤳�Ȥ�ɬ�פǤ���</td>
							</tr>
						</table>
						<!-- 4_detail -->
						<!-- submit -->
						<form name="form1">
						<table width="100%" align="center">
							<tr id="go"> 
								<td align="center"><input TYPE="button" VALUE="���󥱡��ȳ���" onclick="javascript:go_enq()"></td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
			</table></td>
	</tr>
</table>
</div>
</body>
</html>
