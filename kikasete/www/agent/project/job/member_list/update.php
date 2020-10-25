<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���С��ꥹ��
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// ���ID����
function get_client_id() {
	$sql = "SELECT ts_client_id FROM t_project JOIN m_tantousha ON ts_tantousha_id=pj_tantousha_id WHERE pj_project_id={$_SESSION['ss_pjt_id']}";
	return db_fetch1($sql);
}

set_global2('project', '�ץ������ȴ����å��С��ꥹ��', '���С��ꥹ�ȹ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = sprintf("INSERT INTO t_member_grp (mg_group_name,mg_summary,mg_client_id) VALUES (%s,%s,%s)",
			sql_char($group_name),
			sql_char($summary),
			sql_number(get_client_id()));
	db_exec($sql);
	$msg = '���С��ꥹ�Ȥ���Ͽ���ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'update':
	$sql = sprintf("UPDATE t_member_grp SET mg_group_name=%s,mg_summary=%s,mg_update_date=CURRENT_TIMESTAMP WHERE mg_group_id=$group_id",
			sql_char($group_name),
			sql_char($summary));
	db_exec($sql);
	$msg = '���С��ꥹ�Ȥ򹹿����ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	db_begin_trans();
	$sql = "DELETE FROM t_member_grp WHERE mg_group_id=$group_id";
	db_exec($sql);
	$sql = "DELETE FROM t_member_list WHERE ml_group_id=$group_id";
	db_exec($sql);
	db_commit_trans();
	$msg = '���С��ꥹ�Ȥ������ޤ�����';
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
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>
<? menu_start() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
