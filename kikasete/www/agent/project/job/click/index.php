<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:����å�������Ⱦ���
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/decode.php");
include("../sub.php");

//�ᥤ�����

set_global2('project', '�ץ������ȴ���', '����å�������Ⱦ���', BACK_TOP);

$sql = "SELECT jb_job_name,jb_job_type,jb_mail_format,jb_parent_job_id,jb_enquete_id,pj_project_id,pj_project_name,en_enq_type,en_status"
	. " FROM t_job LEFT JOIN t_project ON jb_project_id=pj_project_id LEFT JOIN t_enquete ON jb_enquete_id=en_enquete_id"
	. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
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
<body>

<? page_header() ?>
<? menu_start() ?>

<form>
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m0" colspan=2 align="left">���ץ������Ⱦ���</td>
				</tr>
				<tr>
					<td class="m1" width=140>�ץ�������ID</td>
					<td class="n1"><?=$fetch->pj_project_id?></td>
				</tr>
				<tr>
					<td class="m1">�ץ�������̾</td>
					<td class="n1"><?=htmlspecialchars($fetch->pj_project_name)?></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m0" colspan=2 align="left">������־���</td>
				</tr>
				<tr>
					<td class="m1" width=140>�����ID</td>
					<td class="n1"><?=htmlspecialchars($job_id)?></td>
				</tr>
				<tr>
					<td class="m1" width=140>�����̾</td>
					<td class="n1"><?=htmlspecialchars($fetch->jb_job_name)?></td>
				</tr>
				<tr>
					<td class="m1">�᡼�����</td>
					<td class="n1"><?=decode_mail_format($fetch->jb_mail_format)?></td>
				</tr>
				<tr>
					<td class="m1">���󥱡��ȼ���</td>
					<td class="n1"><?=decode_enq_type($fetch->en_enq_type)?></td>
				</tr>
				<tr>
					<td class="m1">����ּ���</td>
					<td class="n1"><?=decode_job_type($fetch->jb_job_type)?></td>
				</tr>
			<?
			if ($fetch->jb_parent_job_id) {
			?>
				<tr>
					<td class="m1">��JOB</td>
					<td class="n1"><?=htmlspecialchars(parent_job_name_decode($fetch->jb_parent_job_id))?></td>
				</tr>
			<?
			}
			?>
				<tr>
					<td class="m1">����</td>
					<td class="n1"><?=decode_enq_status($fetch->en_status)?></td>
				</tr>
			</table>
			<br>
			<div align="center">
			<input type="button" value="CSV�����ʽ����ѡ�" onclick="location.href='csv_sum.php?job_id=<?=$job_id?>'">
			<input type="button" value="��CSV������" onclick="location.href='csv.php?job_id=<?=$job_id?>'">
			<input type="button" value="����롡" onclick="location.href='../list.php'">
			</div>
		</td>
	</tr>
</table>

</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
