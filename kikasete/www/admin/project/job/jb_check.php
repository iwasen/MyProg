<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:����־��󹹿�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//�ᥤ�����
set_global2('project', '�ץ������ȴ���', '����־��󹹿�', BACK_TOP);

switch ($next_action) {
case 'apply':
	$today = time();
	$en_start = sql_time($en_start_date);
	$jb_start = sql_time($jb_start_date);
/*
	if (SERVICE == 'agent') {
		if ($en_start_date != "" && $en_start < $today) {
			$msg = "���󥱡��Ȥγ��ϻ��֤�᤮�Ƥ���褦�Ǥ���<br>";
		}
		if ($jb_start < $today) {
			$msg .= "�ۿ����ϻ��֤�᤮�Ƥ���褦�Ǥ���<br>";
		}
	}
*/
	if ($msg == "") {
		$sql = "UPDATE t_enquete SET en_status=2 WHERE en_enquete_id=$en_id";
		db_exec($sql);
		$title = "����ֿ���";
		$msg = "����֤ο�����λ���ޤ�����";
		$back = "location.href='jb_contents.php?job_id=$job_id'";
	} else {
		$back = "history.back()";
	}
	break;
case 'cancel_apply':
	$sql = "SELECT en_status FROM t_enquete WHERE en_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
	}
	if ($fetch->en_status == 2) {
		$sql = "UPDATE t_enquete SET en_status=1 WHERE en_job_id=$job_id";
		db_exec($sql);
		$title = "����ֿ������ä�";
		$msg = "����֤ο�������ä��ޤ�����";
	} elseif ($fetch->en_status == 3) {
		$msg = "��̳�ɤǥ����å���Τ��ᡢ�����Ǥ��ޤ���Ǥ�����";
	} else {
		$msg = "���顼��ȯ���������ᡢ�����Ǥ��ޤ���Ǥ�����";
	}
	$back = "location.href='jb_contents.php?job_id=$job_id'";
	break;
case 'delete':
	db_begin_trans();
	$sql = "SELECT jb_enquete_id,jb_search_id FROM t_job WHERE jb_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		if ($fetch->jb_enquete_id) {
			$sql= "DELETE FROM t_enquete WHERE en_enquete_id=$fetch->jb_enquete_id";
			db_exec($sql);
		}

		if ($fetch->jb_search_id) {
			$sql= "DELETE FROM t_search WHERE sr_search_id=$fetch->jb_search_id";
			db_exec($sql);
		}
	}
	$sql = "DELETE FROM t_send_notice WHERE sn_job_id=$job_id";
	db_exec($sql);
	$sql = "DELETE FROM t_job WHERE jb_job_id=$job_id";
	db_exec($sql);
	db_commit_trans();
	$title = "����־��󡡹���";
	$msg = '����־���������ޤ�����';
	$back = "location.href='list.php'";
	break;
case 'pause':
	$sql = "UPDATE t_enquete SET en_status=6 WHERE en_job_id=$job_id AND en_status=5";
	db_exec($sql);
	$title = "����־��֡�����";
	$msg = '����֤�����ߤ��ޤ�����';
	$back = "location.href='jb_contents.php?job_id=$job_id'";
	break;
case 'restart':
	$sql = "UPDATE t_enquete SET en_status=5 WHERE en_job_id=$job_id AND en_status=6";
	db_exec($sql);
	$title = "����־��֡�����";
	$msg = '����֤�Ƴ����ޤ�����';
	$back = "location.href='jb_contents.php?job_id=$job_id'";
	break;
case 'remake':
	db_begin_trans();
	$sql = "UPDATE t_job SET jb_send_status=0,jb_notice_flag=FALSE WHERE jb_job_id=$job_id";
	db_exec($sql);
	$sql = "UPDATE t_enquete SET en_status=1 WHERE en_enquete_id=(SELECT jb_enquete_id FROM t_job WHERE jb_job_id=$job_id)";
	db_exec($sql);
	db_commit_trans();
	$title = "����־��֡�����";
	$msg = '����֤�̤��ǧ���֤��ᤷ�ޤ�����';
	$back = "location.href='jb_contents.php?job_id=$job_id'";
	break;
case 'end_date':
	$sql = sprintf("UPDATE t_enquete SET en_end_date=%s WHERE en_job_id=$job_id", sql_date2($en_end_year,$en_end_month,$en_end_day,$en_end_hour));
	db_exec($sql);
	$title = "��������������";
	$msg = '���������򹹿����ޤ�����';
	$back = "location.href='jb_contents.php?job_id=$job_id'";
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

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$title?></td>
	</tr>
	<tr>
		<td align="center"><br><br>
			<p class="msg"><?=$msg?></p>
			<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
