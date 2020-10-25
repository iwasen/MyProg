<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�ץ������Ⱦ��󹹿�
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//�ᥤ�����
set_global2('project', '�ץ������ȴ���', '�ץ������Ⱦ��󹹿�', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT pj_project_id FROM t_project WHERE pj_project_name='$project_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = sprintf("INSERT INTO t_project (pj_project_name, pj_tantousha_id, pj_regist_date, pj_summary)"
			. "VALUES (%s,%s,'now',%s)",
			sql_char($project_name),
			sql_char($tantousha_id),
			sql_char($summary));
		db_exec($sql);
		$msg = '�ץ������Ⱦ������Ͽ���ޤ�����';
		$back = "location.href='list.php'";
	} else {
		$msg = '���Ϥ����ץ�������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$back = 'history.back()';
	}
	$title = "�ץ������Ⱦ��� ������Ͽ";
	break;
case 'update':
	$sql = "SELECT pj_project_id FROM t_project WHERE pj_project_name='$project_name' AND pj_project_id<>$pjt_id";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE t_project SET"
			. " pj_project_name=" . sql_char($project_name)
			. ",pj_summary=" . sql_char($summary)
			. " WHERE pj_project_id=$pjt_id";
		db_exec($sql);
		$title = "�ץ���������Ͽ���󹹿�";
		$msg = '�ץ������Ⱦ���򹹿����ޤ�����';
		$back = "location.href='list.php'";
	} else {
		$title = "�ץ������Ⱦ��� ����";
		$msg = '���Ϥ����ץ�������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$back = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM t_project WHERE pj_project_id=$pjt_id";
	db_exec($sql);
	$title = "�ץ������Ⱦ��� ����";
	$msg = '�ץ������Ⱦ���������ޤ�����';
	$back = "location.href='list.php'";
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
