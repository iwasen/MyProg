<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ֻ�̳�ɤ���Τ��Τ餻������
'******************************************************/

$title_text = $TT_pub_msg;
$title_color = $TC_HAKKOUSHA;

$sql = "SELECT MSG_ID, ADMIN_MSG FROM T_ADMIN_MSG WHERE MSG_ID='1'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$msg_id = $fetch->msg_id;
	$admin_msg = htmlspecialchars(trim($fetch->admin_msg));
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.admin_msg.focus();
}
function OnSubmit_form1() {
	str=document.form1.admin_msg.value;
	if (str.length > 100) {
		alert("�����Ȥ�Ĺ�����ޤ���");
		document.form1.admin_msg.focus();
		return(false);
	}
return confirm("�������Ƥ���Ͽ���ޤ���������Ǥ�����");
  return confirm("��Ͽ���ޤ���������Ǥ�����");
}

//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="pub_msg2.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0" colspan="2">���ֻ�̳�ɤ���Τ��Τ餻������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m5" width="30%">������</td>
						<td class="n5" width="70%">
							<textarea name="admin_msg" cols="60" rows="4"><?= $admin_msg ?></textarea>
							<br><font class="note">��100ʸ����������Ϥ��Ƥ�����������</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" ��Ͽ ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
