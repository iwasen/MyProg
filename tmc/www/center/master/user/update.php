<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ޥ������ƥʥ󥹡��桼�����󹹿�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �᡼�륢�ɥ쥹��ʣ�����å�
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_user_personal WHERE ups_mail_addr=%s AND ups_user_id<>%s",
			sql_char($_POST['mail_addr']),
			sql_number($_POST['user_id']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �쥳���ɹ�������
function rec_update() {
	db_begin_trans();

	$rec['ups_name_kanji'] =	sql_char($_POST['name_kanji']);
	$rec['ups_name_kana'] =	sql_char($_POST['name_kana']);
	$rec['ups_zip_cd'] =	sql_char($_POST['zip_cd']);
	$rec['ups_address0'] =	sql_char($_POST['address0']);
	$rec['ups_address1'] =	sql_char($_POST['address1']);
	$rec['ups_address2'] =	sql_char($_POST['address2']);
	$rec['ups_tel_no'] =	sql_char($_POST['tel_no']);
	$rec['ups_keitai_no'] =	sql_char($_POST['keitai_no']);
	$rec['ups_mail_addr'] =	sql_char($_POST['mail_addr']);
	$rec['ups_sex_cd'] =	sql_char($_POST['sex_cd']);
	$rec['ups_age'] =	sql_number($_POST['age']);
	db_update('t_user_personal', $rec, "ups_user_id={$_POST['user_id']}");

	$rec['ust_status'] =	sql_number($_POST['status']);
	db_update('t_user_status', $rec, "ust_user_id={$_POST['user_id']}");

	db_commit_trans();
}

// �쥳���ɺ������
function rec_delete() {
	$user_id = $_POST['user_id'];

	db_begin_trans();

	db_delete('t_user_regist', "urg_user_id=$user_id");
	db_delete('t_user_data', "udt_user_id=$user_id");
	db_delete('t_user_login', "ulg_user_id=$user_id");
	db_delete('t_user_personal', "ups_user_id=$user_id");
	db_delete('t_user_manage', "umg_user_id=$user_id");
	db_delete('t_user_car', "ucr_user_id=$user_id");
	db_delete('t_user_campaign', "ucm_user_id=$user_id");
	db_delete('t_user_status', "ust_user_id=$user_id");
	db_delete('t_user_result', "urs_user_id=$user_id");
	db_delete('t_mail_reply', "mrp_user_id=$user_id");
	db_delete('t_user_errmail', "uem_user_id=$user_id");

	db_commit_trans();
}

// �ᥤ�����
switch ($_POST['next_action']) {
case 'update':
	if (rec_check()) {
		$msg = '���Ϥ����᡼�륢�ɥ쥹�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_update();
		$msg = '�桼������򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'delete':
	rec_delete();
	$msg = '�桼���������ޤ�����';
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

<? center_header('�ޥ������ƥʥ󥹡å桼�������ѹ��ù���') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
