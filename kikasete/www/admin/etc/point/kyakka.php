<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ݥ���������Ѳ�����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/point.php");

// �ᥤ�����
set_global('etc', '����¾����', '�ݥ��������', BACK_TOP);

db_begin_trans();

$seq_no = get_multi_data($seq_no);
$sql = "SELECT si_seq_no,si_member_id,si_point,si_req_date,si_item FROM t_seisan WHERE si_seq_no IN ($seq_no)";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$seq_no = $fetch->si_seq_no;
	$monitor_id = $fetch->si_member_id;
	$point = $fetch->si_point + get_charge_point($fetch->si_item);

	// ���֤�Ѳ��˥��å�
	$sql = "UPDATE t_seisan SET si_status=2 WHERE si_seq_no=$seq_no";
	db_exec($sql);

	// ���ݥ���Ȥ��᤹
	monitor_point($monitor_id, PT_SEISAN_CANCEL, $point);

	// �ݥ�������򤫤���
//	$date = "timestamp " . sql_date(substr($fetch->si_req_date, 0, 19));
//	$sql = "DELETE FROM l_point WHERE lp_member_kind=1 AND lp_member_id=$monitor_id AND lp_point_kind=" . PT_SEISAN . " AND lp_point=-$point AND lp_date BETWEEN $date AND $date + interval '3 seconds'";
//	db_exec($sql);
}

db_commit_trans();

$msg = '�ݥ���ȵѲ�������λ���ޤ�����';
$back = "location.href='list.php'";
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
<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
