<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:�ޥ������ƥʥ󥹡û��鳨��Ϣ�ޥ����ò����ޥ���
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// �ᥤ�����
set_global('master_st', '�ޥ��������û��鳨��Ϣ�ޥ���', '�����ޥ���', BACK_TOP);

$sql = "SELECT * FROM m_brushwork WHERE bw_brushwork_cd = $bw_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("����ID������", __FILE__);
$fetch = pg_fetch_object($result, 0);

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
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}

function update_check(f) {
	if (f.bw_name.value == "") {
		alert("����̾�����Ϥ��Ƥ���������");
		f.bw_name.focus();
		return false;
	}
	return confirm("��������򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("��������������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ���������������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">����������<?=MUST_ITEM?></td>
		<td class="n1">
			<?=$fetch->bw_brushwork_cd?>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="bw_name" size=60 maxlength=50 <?=value($fetch->bw_name)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">�����ܺ�</td>
		<td class="n1">
			<textarea class="kanji" name="bw_explain" cols=50 rows=5><?=$fetch->bw_explain?></textarea>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="bw_cd" <?=value($bw_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
