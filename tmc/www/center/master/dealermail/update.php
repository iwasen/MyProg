<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����Ź�᡼��ƥ�ץ졼�ȹ�������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳���ɹ�������
function rec_update() {
	db_begin_trans();

	// t_dealer_mail����
	$rec['dmt_header'] = sql_char($_POST["header"]);
	$rec['dmt_footer'] = sql_char($_POST["footer"]);
	db_update('t_dealer_mail_template', $rec, "dmt_template_id=1");

	db_commit_trans();
}

// �쥳���ɹ���
rec_update();

$msg = '����Ź�᡼��ƥ�ץ졼�Ȥ򹹿����ޤ�����';
$back = "location.href='../menu.php'";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('����Ź�᡼���ۿ��ù�������') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
