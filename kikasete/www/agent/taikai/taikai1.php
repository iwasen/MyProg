<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:������
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/check.php");

//�ᥤ�����
set_global('������', BACK_TOP);

$msg = "��������¹Ԥ��ޤ�����";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.password.value == "") {
		alert("�ѥ���ɤ����Ϥ��Ʋ�������");
		f.password.focus();
		return false;
	}
	return confirm("��������¹Ԥ��ޤ���������Ǥ�����");
}
//-->
</script>
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="taikai2.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=1 width="95%">
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
				<tr>
					<td align="center"><br><br>
						<p class="msg"><?=$msg?></p>
						<p>�ѥ���ɡ�<input type="password" name="password" maxlength=20></p>
						<p><input type="submit" id="ok" value="���¹ԡ�">
							<input type="button" value="����󥻥�" onclick='location.href="../mypage.php"'></p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
