<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����²��������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// IME����JavaScript�¹�
function check_browser($func) {
	if ($func == 'onload') {
		if ( ereg ("MSIE", $_SERVER["HTTP_USER_AGENT"]))
			echo "onload='onload()'";
	} elseif ($func == 'onclick') {
		if ( ereg ("MSIE", $_SERVER["HTTP_USER_AGENT"]))
			echo "onclick=\"show_hide('add','on')\"";
	}
}

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_input_exp.php');
$reg = &$_SESSION['ss_regist2'];
?>
<html>
<head>
<title>������xxx��������Club������</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<script type="text/javascript">
<!--
function onload() {
	show_hide('add',document.form1.family_add.value);
}

function show_hide(id, show) {
	if (document.all)
		ida = document.all(id);
	else if (document.getElementById)
		ida = document.getElementById(id);
	else
		return;
	for (i = 0; i < ida.length; i++) {
		ida[i].style.display = show ? "" : "none";
	}
	if (show)
		document.all('text').style.display = "none";
}
//-->
</script>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8" <?=check_browser('onload')?>>
<form name="form1" method="post" action="member_input03_check.php">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/title/m_input03_01.gif" width="269" height="37" alt="�椬�Ȥβ�²��������2"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">
		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left">
					<div align="center">
						<table width = "550" border="1" cellspacing="3" bordercolor="#00CCFF">
							<tr>
								<td align="center">
									<table border=0 cellspacing=1 cellpadding=5 width=98%>
										<tr>
											<td>
												<table>
													<tr>
														<td></td>
														<td><font size=2>��²�����ϡ����ʤ���Ʊ����²������ʿ�ѥ��ͥ륮�������̡ʿ�������̡ˤ򻻽Ф��뤿��˽��פʾ���Ǥ���</font></td>
													</tr>
													<tr>
														<td valign="top">��</td>
														<td>
														<font size=2>
														�������Ԥ�Ʊ�����˥᡼�����Τ�Ȥǡ��ŵ�����������ƻ����Ѥ��Ƥ����²�����ˤĤ��ơ�<font color="red">ǯ��</font>��<font color="red">�������ԤȤ�³��</font>�򶵤��Ƥ���������
														</font>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						</div>
						<br>
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#00CCFF">
							<tr>
								<td><img src="img/spacer.gif" width="644" height="4"></td>
							</tr>
						</table>
						<br>
						<table border="0" width="644">
							<tr>
								<td nowrap colspan=5 height=40><font size=2>&lt;&lt;���ʤ�����ߤ���²�����򶵤��Ƥ�������&gt;&gt;</font></td>
								<td rowspan="10"><img src="img/nyuukai/zou_img/m_input03_01.gif" alt="Ʊ���²�����Ϥ��Ƥ�������"></td>
							</tr>
							<tr>
<?
	$family = &$reg->family[1];
?>
								<td width=100 height=30><font size=2>Ʊ����۶�����</font></td>
								<td width=110 colspan=2><font size=2>
									<input type="radio" name="family_rel[]" value="1" <?=checked($family->family_rel == '1')?>>����
									<input type="radio" name="family_rel[]" value="" <?=checked($family->family_rel != '1')?>>�ʤ�
								</font></td>
								<td width=30><font size=2> ǯ�� </td>
								<td width=50><font size=2><input type="text" size=3 maxlength=2 name="family_age[]" <?=value($family->family_age)?>>��</font></td>
							</tr>
							<tr>
<?
	$family = &$reg->family[2];
?>
								<td height=30><font size=2>Ʊ��Τ���²</font></td>
								<td><font size=2><nobr>³��</nobr></font></td>
								<td><select name="family_rel[]"><?=select_family_rel('--³��--', $family->family_rel)?></select></td>
								<td><font size=2></font></td>
								<td><font size=2><input type="text" size=3 maxlength=2 name="family_age[]"  <?=value($family->family_age)?>>��</font></td>
							</tr>
<?
for ($i = 3; $i < 7; $i++) {
	$family = &$reg->family[$i];
?>
							<tr>
								<td height=30></td>
								<td></td>
								<td><select name="family_rel[]"><?=select_family_rel('--³��--', $family->family_rel)?></select></td>
								<td></td>
								<td><font size=2><input type="text" size=3 maxlength=2 name="family_age[]" <?=value($family->family_age)?>>��</font></td>
							</tr>
<?
}
$family = &$reg->family[7];
?>
							<input type="hidden" name="family_add" <?=value($family->family_rel)?>>
<?
for ($i = 7; $i < 12; $i++) {
	$family = &$reg->family[$i];
?>
							<tr id='add'>
								<td height=30></td>
								<td></td>
								<td><select name="family_rel[]"><?=select_family_rel('--³��--', $family->family_rel)?></select></td>
								<td></td>
								<td><font size=2><input type="text" size=3 maxlength=2 name="family_age[]" <?=value($family->family_age)?>>��</font></td>
							</tr>
<?
}
?>
						<tr>
							<td><br></td>
							<td><br></td>
							<td>
							<span id="text">
							<font color="blue" size=2><a href='#' <?=check_browser('onclick')?>><nobr>����������ɲä����</nobr></a></font><br>
							</span>
							<!-- �֡������5���ɲá��פ򲡤��ȡ����ϥե����ब������ɽ���������ͤˤ��Ƥ������� -->
							</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<table border="0" cellspacing="0" cellpadding="0" bgcolor="#00CCFF">
				<tr>
					<td><img src="img/spacer.gif" width="644" height="4"></td>
				</tr>
			</table>
			<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/page/02.gif" width="44" height="44"></td>
				</tr>
			</table>
		<hr width="95%" size="1"></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">

			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
<?
if (!$reg->back_page) {
?>

					<td><a href="member_input02.php"><img src="img/nyuukai/button/back.gif" width="99" height="40" border="0" alt="���"></a></td>
<!--
					<td width="10"></td>
					<td><a href="member_input03.php"><img src="img/nyuukai/button/cancell.gif" width="99" height="40" border="0" alt="����󥻥�"></a></td>
-->
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/next.gif" width="99" height="40" alt="����" border="0"></td>
<?
} else {
?>
					<td><a href="javascript:history.back()"><img src="img/nyuukai/button/back.gif" width="99" height="40" border="0" alt="���"></a></td>
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/syuusei.gif" width="99" height="40" alt="����" border="0"></td>
<?
}
?>
				</tr>
				<tr>
					<td height="7" colspan="7"><img src="img/spacer.gif" height="7"></td>
				</tr>
			</table>

		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

<br>
</div>
</form>
</body>
</html>
