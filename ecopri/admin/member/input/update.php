<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:����ǡ������Ͼ�������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

function check_input($seq_no, $eng, $ym, $use_org, $use, $sum_org=0, $sum) {
	$edit_flag = '';
	if ($eng != 'gm') {
		if (($use_org != $use) || ($sum_org != $sum)) {
			$sql = "UPDATE t_base_data SET bd_{$eng}_use=$use,bd_{$eng}_use_flag=6,bd_{$eng}_sum=$sum,bd_{$eng}_sum_flag=6"
					. " WHERE bd_mb_seq_no=$seq_no AND bd_month='$ym'";
			db_exec($sql);
			$edit_flag = 1;
		}
	} else {
		if (($use_org != $use)) {
			$sql = "UPDATE t_base_data SET bd_{$eng}_use=$use,bd_{$eng}_use_flag=6 WHERE bd_mb_seq_no=$seq_no AND bd_month='$ym'";
			db_exec($sql);
			$edit_flag = 1;
		}
	}
	if ($edit_flag) {
		// �������񤭽Ф�
		$sql = "INSERT INTO t_edit_data (ed_mb_seq_no,ed_month,ed_eng_cd,ed_use_sum,ed_old_volume,ed_new_volume,ed_reason,ed_old_sum,ed_new_sum)"
			 . " VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s);";
		$sql = sprintf($sql,
			sql_number($seq_no),
			sql_date($ym),
			sql_char($eng),
			0,
			sql_number($use_org != '' ? $use_org : 0),
			sql_number($use != '' ? $use : 0),
			sql_char('��̳�ɤˤ�뽤������'),
			sql_number($sum_org != '' ? $sum_org : 0),
			sql_number($sum != '' ? $sum : 0));
		db_exec($sql);
	}
}

set_global('member', '����������', '����ǡ������Ͼ���', BACK_TOP);

$i = 0;
while ($ym[$i]) {
	check_input($seq_no, $eng, $ym[$i], $use_org[$i], $use[$i], $sum_org[$i], $sum[$i]);
	$i++;
}

$msg = '�����ͤ������ޤ�����';
$ret = 'location.href=\'detail.php?seq_no=' . $seq_no . '\'';

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
