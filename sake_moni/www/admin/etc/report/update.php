<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:Ĵ����̹�������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �Х�������ǡ�������
function get_bytea($name) {
	return file_get_contents($_FILES[$name]['tmp_name']);
}

set_global('etc', '����¾����', 'Ĵ����̥��åץ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$rec['rp_chain_cd'] = $_POST['chain_or_gyoutai'] == '1' ? sql_number($_POST['chain_cd']) : 'null';
	$rec['rp_gyoutai_cd'] = $_POST['chain_or_gyoutai'] == '2' ? sql_number($_POST['gyoutai_cd']) : 'null';
	$rec['rp_title'] = sql_char($_POST['title']);
	$rec['rp_from_date'] = sql_date("$from_year/$from_month/$from_day");
	$rec['rp_to_date'] = sql_date("$to_year/$to_month/$to_day");
	$rec['rp_report_file'] = sql_bytea(get_bytea('report_file'));
	$rec['rp_report_file_name'] = sql_char($_FILES['report_file']['name']);
	$rec['rp_report_file_type'] = sql_char($_FILES['report_file']['type']);
	$rec['rp_sum_file'] = sql_bytea(get_bytea('sum_file'));
	$rec['rp_sum_file_name'] = sql_char($_FILES['sum_file']['name']);
	$rec['rp_sum_file_type'] = sql_char($_FILES['sum_file']['type']);
	$rec['rp_enquete_file'] = sql_bytea(get_bytea('enquete_file'));
	$rec['rp_enquete_file_name'] = sql_char($_FILES['enquete_file']['name']);
	$rec['rp_enquete_file_type'] = sql_char($_FILES['enquete_file']['type']);
	db_insert('t_report', $rec);

	$msg = 'Ĵ����̤���Ͽ���ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'update':
	$rec['rp_chain_cd'] = $_POST['chain_or_gyoutai'] == '1' ? sql_number($_POST['chain_cd']) : 'null';
	$rec['rp_gyoutai_cd'] = $_POST['chain_or_gyoutai'] == '2' ? sql_number($_POST['gyoutai_cd']) : 'null';
	$rec['rp_title'] = sql_char($_POST['title']);
	$rec['rp_from_date'] = sql_date("$from_year/$from_month/$from_day");
	$rec['rp_to_date'] = sql_date("$to_year/$to_month/$to_day");
	if ($_FILES['report_file']['tmp_name'] != '') {
		$rec['rp_report_file'] = sql_bytea(get_bytea('report_file'));
		$rec['rp_report_file_name'] = sql_char($_FILES['report_file']['name']);
		$rec['rp_report_file_type'] = sql_char($_FILES['report_file']['type']);
	}
	if ($_FILES['sum_file']['tmp_name'] != '') {
		$rec['rp_sum_file'] = sql_bytea(get_bytea('sum_file'));
		$rec['rp_sum_file_name'] = sql_char($_FILES['sum_file']['name']);
		$rec['rp_sum_file_type'] = sql_char($_FILES['sum_file']['type']);
	}
	if ($_FILES['enquete_file']['tmp_name'] != '') {
		$rec['rp_enquete_file'] = sql_bytea(get_bytea('enquete_file'));
		$rec['rp_enquete_file_name'] = sql_char($_FILES['enquete_file']['name']);
		$rec['rp_enquete_file_type'] = sql_char($_FILES['enquete_file']['type']);
	}
	db_update('t_report', $rec, "rp_report_id={$_POST['report_id']}");

	$msg = 'Ĵ����̤򹹿����ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
        $sql = "DELETE FROM t_report WHERE rp_report_id={$_POST['report_id']}";
	//$sql = "DELETE FROM m_area WHERE ar_area_cd=$ar_cd";
	db_exec($sql);
	$msg = 'Ĵ����̤������ޤ�����';
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
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
