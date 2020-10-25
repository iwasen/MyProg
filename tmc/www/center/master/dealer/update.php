<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����Ź�ޥ�����������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳����¸�ߥ����å�
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_dealer WHERE dlr_dealer_cd=%s",
			sql_char($_POST['dealer_cd']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �쥳������������
function rec_insert() {
	db_begin_trans();

	$rec['dlr_dealer_cd'] = sql_char($_POST['dealer_cd']);
	$rec['dlr_dealer_name'] = sql_char($_POST['dealer_name']);
	$rec['dlr_short_name'] = sql_char($_POST['short_name']);
	$rec['dlr_sales_channel_cd'] = sql_number($_POST['sales_channel_cd']);
	$rec['dlr_tantousha_id'] = sql_number($_POST['tantousha_id']);
	db_insert('t_dealer', $rec);

	dealer_car();

	db_commit_trans();
}

// �쥳���ɹ�������
function rec_update() {
	db_begin_trans();

	$rec['dlr_dealer_name'] = sql_char($_POST['dealer_name']);
	$rec['dlr_short_name'] = sql_char($_POST['short_name']);
	$rec['dlr_sales_channel_cd'] = sql_number($_POST['sales_channel_cd']);
	$rec['dlr_tantousha_id'] = sql_number($_POST['tantousha_id']);
	db_update('t_dealer', $rec, "dlr_dealer_cd='{$_POST['dealer_cd']}'");

	dealer_car();

	db_commit_trans();
}

// �쥳���ɺ������
function rec_delete() {
	db_begin_trans();

	db_delete('t_dealer', "dlr_dealer_cd='{$_POST['dealer_cd']}'");
	db_delete('t_dealer_car', "dlc_dealer_cd='{$_POST['dealer_cd']}'");

	db_commit_trans();
}

// ��갷���ּ���Ͽ
function dealer_car() {
	$dealer_car = $_POST['dealer_car'];

	if (is_array($dealer_car)) {
		$sql = "SELECT dlc_car_model_cd FROM t_dealer_car WHERE dlc_dealer_cd='{$_POST['dealer_cd']}'";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$car_model_cd = pg_fetch_result($result, $i, 0);
			$key = array_search($car_model_cd, $dealer_car);
			if ($key === false)
				db_delete('t_dealer_car', "dlc_dealer_cd='{$_POST['dealer_cd']}' AND dlc_car_model_cd=$car_model_cd");
			else
				unset($dealer_car[$key]);
		}

		foreach ($dealer_car as $car_model_cd) {
			$rec['dlc_dealer_cd'] = sql_char($_POST['dealer_cd']);
			$rec['dlc_car_model_cd'] = sql_number($car_model_cd);
			db_insert('t_dealer_car', $rec);
		}
	} else
		db_delete('t_dealer_car', "dlc_dealer_cd='{$_POST['dealer_cd']}'");
}

// �ᥤ�����
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '���Ϥ�������Ź�����ɤϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = '����Ź����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	rec_update();
	$msg = '����Ź����򹹿����ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	rec_delete();
	$msg = '����Ź�������ޤ�����';
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

<? center_header('�ޥ������ƥʥ󥹡�����Ź�ޥ����ù���') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
