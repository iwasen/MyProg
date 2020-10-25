<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ������ü��������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/decode.php");
include("$inc/mail.php");
include("$inc/icp_db.php");
include("$inc/icp_regist.php");
include("$inc/icp_func.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ�������������', BACK_TOP);

db_begin_trans();

$sql = "SELECT pmn_monitor_id,pmn_admin_choice FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($fetch->pmn_admin_choice === null || $fetch->pmn_admin_choice != $admin_choice[$fetch->pmn_monitor_id]) {
		$rec['pmn_admin_choice'] = sql_number((int)$admin_choice[$fetch->pmn_monitor_id]);
		db_update('t_mp_monitor', $rec, "pmn_pjt_id=$pjt_id AND pmn_monitor_id=$fetch->pmn_monitor_id");
	}
}

switch ($next_action) {
case 'save':
	$msg = '���üԤη���ե饰������¸���ޤ�����';
	break;
case 'apply':
	// ICP�˻��üԤ���Ͽ
	icp_regist($pjt_id);

	// ���ơ��������ļ�������˥��å�
	$rec['ppj_status'] = sql_number(MPS_ROOM_JUNBI);
	db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id AND ppj_status<" . MPS_ROOM_JUNBI);

	// ICP����Ͽ���줿���С������å�
	$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id AND pmn_admin_choice=2";
	$member_num = db_fetch1($sql);
	$rec['ppj_icp_member_num'] = sql_number($member_num);
	db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id AND (ppj_icp_member_num IS NULL OR ppj_icp_member_num<>$member_num)");

	$msg = '��ǧ���ƻ��üԤ��ļ�����Ͽ���ޤ�����';
	break;
}

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg"><?=$msg?></p>
			<p><input type="button" value="����롡" onclick="location.href='<?=get_return_page()?>'"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
