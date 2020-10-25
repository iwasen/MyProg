<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:���åץ���ô���ޥ�����������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳����¸�ߥ����å�
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_upload_tantou WHERE upt_upload_cd=%s",
			sql_char($_POST['upload_cd']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �쥳������������
function rec_insert() {
	db_begin_trans();

	$rec['upt_upload_cd'] = sql_char($_POST['upload_cd']);
	$rec['upt_name'] = sql_char($_POST['name']);
	$rec['upt_active'] = sql_bool($_POST['active']);
	$rec['upt_bgcolor'] = sql_char($_POST['bgcolor']);
	db_insert('t_upload_tantou', $rec);

	tantou_dealer();

	db_commit_trans();
}

// �쥳���ɹ�������
function rec_update() {
	db_begin_trans();

	$rec['upt_name'] = sql_char($_POST['name']);
	$rec['upt_active'] = sql_bool($_POST['active']);
	$rec['upt_bgcolor'] = sql_char($_POST['bgcolor']);
	db_update('t_upload_tantou', $rec, "upt_upload_cd='{$_POST['upload_cd']}'");

	tantou_dealer();

	db_commit_trans();
}

// �쥳���ɺ������
function rec_delete() {
	db_begin_trans();

	db_delete('t_upload_tantou', "upt_upload_cd='{$_POST['upload_cd']}'");
	db_delete('t_upload_dealer', "upd_upload_cd='{$_POST['upload_cd']}'");

	db_commit_trans();
}

// ô������Ź
function tantou_dealer() {
	$dealer_ary = $_POST['dealer_cd'];

	if (is_array($dealer_ary)) {
		$sql = "SELECT upd_dealer_cd FROM t_upload_dealer WHERE upd_upload_cd='{$_POST['upload_cd']}'";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$dealer_cd = pg_fetch_result($result, $i, 0);
			$key = array_search($dealer_cd, $dealer_ary);
			if ($key === false)
				db_delete('t_upload_dealer', "upd_upload_cd='{$_POST['upload_cd']}' AND upd_dealer_cd='$dealer_cd'");
			else
				unset($dealer_ary[$key]);
		}

		foreach ($dealer_ary as $dealer_cd) {
			$rec['upd_dealer_cd'] = sql_char($dealer_cd);
			$rec['upd_upload_cd'] = sql_char($_POST['upload_cd']);
			db_insert('t_upload_dealer', $rec);
		}
	} else
		db_delete('t_upload_dealer', "upd_upload_cd='{$_POST['upload_cd']}'");
}

// �ᥤ�����
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '���Ϥ������åץ���ô�������ɤϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = '���åץ���ô������Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	rec_update();
	$msg = '���åץ���ô������򹹿����ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	rec_delete();
	$msg = '���åץ���ô���������ޤ�����';
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

<? center_header('�ޥ������ƥʥ󥹡å��åץ���ô���ޥ����ù���') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
