<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ե��ꥨ���ȥݥ���Ⱥ������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/point.php");
include("$inc/resign.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global('monitor', '��˥�������', '���ե��ꥨ���ȴ���', BACK_TOP);

if (is_array($check)) {
	db_begin_trans();

	foreach ($check as $id) {
		list($affiliate_id2, $monitor_id2) = explode(':', $id);

		// ��˥���ID����
		$sql = "SELECT af_monitor_id FROM t_affiliate WHERE af_affiliate_id=$affiliate_id2";
		$monitor_id = db_fetch1($sql);

		// ��Ϳ�������ե��ꥨ���ȥݥ���ȼ���
		$sql = "SELECT afr_point FROM t_affiliate_reg WHERE afr_affiliate_id=$affiliate_id2 AND afr_monitor_id=$monitor_id2";
		$point = db_fetch1($sql);

		if ($point > 0) {
			// ���ե��ꥨ���ȿ����ԤΥݥ���Ȥ���
			monitor_point($monitor_id, PT_AFFILIATE, -$point);

			// ���ե��ꥨ������Ͽ�ԤΥݥ���Ȥ���
			monitor_point($monitor_id2, PT_AFFILIATE, -$point);

			// ��Ϳ�ݥ���ȿ���0�˥��å�
			$rec['afr_point'] = sql_number(0);
			db_update('t_affiliate_reg', $rec, "afr_affiliate_id=$affiliate_id2 AND afr_monitor_id=$monitor_id2");
		}

		// ���ե��ꥨ����̵��
		$sql = "UPDATE t_affiliate SET af_status=2 WHERE af_monitor_id=$monitor_id2 AND af_affiliate_mgr_id=$affiliate_mgr_id AND af_status<>2";
		db_exec($sql);

		// ���ե��ꥨ������Ͽ�Ԥ�����ʬ
		resign_monitor($monitor_id2);
	}

	// ��˥������ꥻ�å�
	$sql = "DELETE FROM t_sum_data WHERE su_sum_id=1";
	db_exec($sql);

	db_commit_trans();
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
<p class="msg">���ե��ꥨ���ȥݥ���Ȥ�̵���ˤ��ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='reg_list.php?affiliate_id=<?=$affiliate_id?>'"></p>
</div>

<? page_footer() ?>
</body>
</html>
