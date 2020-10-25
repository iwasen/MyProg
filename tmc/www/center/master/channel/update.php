<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�������ͥ�ޥ�����������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// �쥳����¸�ߥ����å�
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM t_sales_channel WHERE sch_sales_channel_cd<>%s AND sch_sales_channel_name=%s",
			sql_number($_POST['sales_channel_cd'], 0),
			sql_char($_POST['sales_channel_name']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �쥳������������
function rec_insert() {
	db_begin_trans();

	$sql = "SELECT COALESCE(max(sch_sales_channel_cd),0)+1 FROM t_sales_channel";
	$sales_channel_cd = db_fetch1($sql);

	$rec['sch_sales_channel_cd'] = sql_number($sales_channel_cd);
	$rec['sch_sales_channel_name'] = sql_char($_POST['sales_channel_name']);
	$rec['sch_sales_channel_mark'] = sql_char($_POST['sales_channel_mark']);
	db_insert('t_sales_channel', $rec);

	car_lineup($sales_channel_cd);

	db_commit_trans();
}

// �쥳���ɹ�������
function rec_update() {
	$sales_channel_cd = $_POST['sales_channel_cd'];

	db_begin_trans();

	$rec['sch_sales_channel_name'] = sql_char($_POST['sales_channel_name']);
	$rec['sch_sales_channel_mark'] = sql_char($_POST['sales_channel_mark']);
	db_update('t_sales_channel', $rec, "sch_sales_channel_cd=$sales_channel_cd");

	car_lineup($sales_channel_cd);

	db_commit_trans();
}

// �쥳���ɺ������
function rec_delete() {
	db_delete('t_sales_channel', "sch_sales_channel_cd={$_POST['sales_channel_cd']}");
	db_delete('t_car_lineup', "clu_sales_channel_cd={$_POST['sales_channel_cd']}");
}

// �ּ�饤��ʥå���Ͽ
function car_lineup($sales_channel_cd) {
	$car_category_cd = $_POST['car_category_cd'];

	if (is_array($car_category_cd)) {
		foreach ($car_category_cd as $key => $val) {
			$sql = "SELECT COUNT(*) FROM t_car_lineup WHERE clu_sales_channel_cd=$sales_channel_cd AND clu_car_category_cd=$key";
			$result = db_exec($sql);
			if (pg_fetch_result($result, 0, 0) == 0) {
				if ($val != '') {
					$rec['clu_sales_channel_cd'] = sql_number($sales_channel_cd);
					$rec['clu_car_category_cd'] = sql_char($key);
					$rec['clu_lineup'] = sql_char($val);
					db_insert('t_car_lineup', $rec);
				}
			} else {
				if ($val != '') {
					$rec['clu_lineup'] = sql_char($val);
					db_update('t_car_lineup', $rec, "clu_sales_channel_cd=$sales_channel_cd AND clu_car_category_cd=$key AND clu_lineup<>" . sql_char($val));
				} else {
					db_delete('t_car_lineup', "clu_sales_channel_cd=$sales_channel_cd AND clu_car_category_cd=$key");
				}
			}
		}
	}
}

// �ᥤ�����
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '���Ϥ����������ͥ�̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_insert();
		$msg = '�������ͥ����Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'update':
	if (rec_check()) {
		$msg = '���Ϥ����������ͥ�̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	} else {
		rec_update();
		$msg = '�������ͥ����򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	}
	break;
case 'delete':
	rec_delete();
	$msg = '�������ͥ�������ޤ�����';
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

<? center_header('�ޥ������ƥʥ󥹡��������ͥ�ޥ����ù���') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
