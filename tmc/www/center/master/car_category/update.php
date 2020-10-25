<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ּ參�ƥ���ޥ�����������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳����¸�ߥ����å�
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_car_category WHERE crc_car_category_cd<>%s AND crc_car_category_name=%s",
			sql_number($_POST['car_category_cd'], 0),
			sql_char($_POST['car_category_name']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �쥳������������
function rec_insert() {
	$rec['crc_car_category_name'] = sql_char($_POST['car_category_name']);
	db_insert('t_car_category', $rec);
}

// �쥳���ɹ�������
function rec_update() {
	$rec['crc_car_category_name'] = sql_char($_POST['car_category_name']);
	db_update('t_car_category', $rec, "crc_car_category_cd={$_POST['car_category_cd']}");
}

// �쥳���ɺ������
function rec_delete() {
	db_delete('t_car_category', "crc_car_category_cd={$_POST['car_category_cd']}");
}

// �ᥤ�����
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '���Ϥ����ּ參�ƥ���̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = '�ּ參�ƥ������Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	if (rec_check()) {
		$msg = '���Ϥ����ּ參�ƥ���̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_update();
		$msg = '�ּ參�ƥ������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'delete':
	rec_delete();
	$msg = '�ּ參�ƥ���������ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('�ޥ������ƥʥ󥹡üּ參�ƥ���ޥ����ù���') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
