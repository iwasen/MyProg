<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��³��졼�����᡼���ۿ���������Ź����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$date = $_POST['date'];
$dealer_ary = $_POST['dealer_cd'];

db_begin_trans();

if (is_array($dealer_ary)) {
	$sql = "SELECT rlj_dealer_cd FROM t_relation_jogai WHERE rlj_send_date=" . sql_date($date);
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$dealer_cd = pg_fetch_result($result, $i, 0);
		$key = array_search($dealer_cd, $dealer_ary);
		if ($key === false)
			db_delete('t_relation_jogai', "rlj_send_date=" . sql_date($date) . " AND rlj_dealer_cd=" . sql_char($dealer_cd));
		else
			unset($dealer_ary[$key]);
	}

	foreach ($dealer_ary as $dealer_cd) {
		$rec['rlj_send_date'] = sql_date($date);
		$rec['rlj_dealer_cd'] = sql_char($dealer_cd);
		db_insert('t_relation_jogai', $rec);
	}
} else
	db_delete('t_relation_jogai', "rlj_send_date=" . sql_date($date));

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('�ޥ������ƥʥ󥹡÷�³��졼�����᡼����ۿ���������ۿ���������Ź����') ?>

<div align="center">
<p class="msg">��³��졼�����᡼����ۿ���������Ź�����ꤷ�ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='schedule.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
