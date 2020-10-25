<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:��˥塼�ե졼��ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/define.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// PIR���С�����
function select_member($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT pm_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname"
			. " FROM t_job_member"
			. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
			. " WHERE jm_job_id={$_SESSION[SCD]['job_id']} AND jm_status=1 AND NOT " . cond_participant_auth('�ʲ��')
			. " ORDER BY pm_nickname";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->pm_member_id, $selected), '>', htmlspecialchars($fetch->pm_nickname), "</option>\n";
	}
}

// ��ļ�̾�ȥ���־ܺ٤����
$sql = "SELECT jb_room_name,jb_description,jb_status FROM t_job WHERE jb_job_id={$_SESSION[SCD]['job_id']}";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<script language="JavaScript" type="text/javascript" src="js/default.js"></script>
	<title>��˥塼��ʬ</title>
<script type="text/javascript">
<!--
function on_edit() {
	var f = top.current_data;
	if (f.id.value != "")
		parent.main.contents.location.href = "edit/edit.php?id=" + f.id.value;
}
function on_delete() {
	var f = top.current_data;
	if (f.id.value != "")
		parent.main.contents.location.href = "edit/delete.php?id=" + f.id.value;
}
//-->
</script>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<div align="center">
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#faeaaa" width="220">
<tr>
	<td width="38" valign="top" background="img/title_icon.gif"><img src="img/spacer.gif" width="38" height="1" border="0"></td>
	<td width="172" background="img/title_bg.gif" class="text16b"><?=htmlspecialchars($fetch->jb_room_name)?></td>
	<td valign="top" width="10" background="img/title_edge.gif"><img src="img/spacer.gif" width="10" height="1" border="0"></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="1" bgcolor="#D3AA07" width="220">
<tr>
	<td>
		<table border="0" cellspacing="3" cellpadding="0" bgcolor="#fefae9" width="100%" class="text14">
		<tr>
			<td><?=nl2br(htmlspecialchars($fetch->jb_description))?></td>
		</tr>
		</table>
	</td>
</tr>
</table>

<hr size="1" width="100%" align="center" color="#c0c0c0" noshade>

<?
if ($_SESSION[SCD]['room_type'] == ROOM_PIR && check_participant_auth('�ʲ��')) {
?>
<form method="get" name="pir" action="main.php" target="main">
<table border="0" cellspacing="1" cellpadding="0" bgcolor="#808080" align="center" width="95%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="5" bgcolor="#e6e6e6" align="center" width="100%" class="text10">
		<tr>
			<td>������Υ��С���
			<select name="disp_member_id" class="textbox10" onchange="submit()"><? select_member('�����Ӥ�������', $_SESSION[SCD]['disp_member_id']) ?></select>
			</td>
		</tr>
		</table></td>
</tr>
</table>
</form>

<br>
<?
}
?>
<br>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FAEAAA" width="220">
<tr>
	<td><img src="img/hatsugenkanri.gif" alt="ȯ������" width="220" height="30" border="0"></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="1" bgcolor="#D3AA07" width="220">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" bgcolor="#fefae9" width="100%">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="text12">
<?
$line_flag = false;
if (check_participant_auth('Webȯ��') && check_participant_auth('ȯ��ON/OFF') && $fetch->jb_status == 5) {
?>
				<tr>
					<td align="right" width="15%"><img src="img/hatsugenkanri_menu.gif" width="10" height="10" border="0"></td>
					<td width="85%"><a href="javascript:Open('send/new.php','send_new','680','700')" title="�������">�������</a></td>
				</tr>
<?
	$line_flag = true;
}
if (check_participant_auth('�ʲ��')) {
	if ($line_flag) {
?>
				<tr>
					<td colspan="2" align="center"><img src="img/menu_line.gif" width="200" height="1" border="0" vspace="1"></td>
				</tr>
<?
	}
?>
				<tr>
					<td align="right"><img src="img/hatsugenkanri_menu.gif" width="10" height="10" border="0"></td>
					<td><a href="javascript:void(0)" title="ȯ���Խ�" onclick="on_edit()">ȯ���Խ�</a></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><img src="img/menu_line.gif" width="200" height="1" border="0" vspace="1"></td>
				</tr>
				<tr>
					<td align="right"><img src="img/hatsugenkanri_menu.gif" width="10" height="10" border="0"></td>
					<td><a href="javascript:void(0)" title="ȯ�����" onclick="on_delete()">ȯ�����</a></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><img src="img/menu_line.gif" width="200" height="1" border="0" vspace="1"></td>
				</tr>
				<tr>
					<td align="right"><img src="img/hatsugenkanri_menu.gif" width="10" height="10" border="0"></td>
					<td><a href="thread/move.php" target="contents" title="����åɰ�ư">����åɰ�ư</a></td>
				</tr>
<?
	$line_flag = true;
}
if (check_participant_auth('�ʲ��')) {
	if ($line_flag) {
?>
				<tr>
					<td colspan="2" align="center"><img src="img/menu_line.gif" width="200" height="1" border="0" vspace="1"></td>
				</tr>
<?
	}
?>
				<tr>
					<td align="right"><img src="img/hatsugenkanri_menu.gif" width="10" height="10" border="0"></td>
					<td><a href="helplist.php" target="subject" title="�䤤��碌">�䤤��碌</a></td>
				</tr>
<?
}
?>
				</table>

			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?
if (check_staff_auth('ȯ�����������ݡ���') || check_staff_auth('ȯ��Ͽ�������ݡ���') || check_staff_auth('ȯ�����ץ������ݡ���') || check_participant_auth('�ʲ��')) {
?>
<br>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#D3AA07" width="220">
<tr>
	<td><img src="img/dl.gif" alt="���������" width="220" height="30" border="0"></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="1" bgcolor="#D3AA07" width="220">
<tr>
	<td>
		<table border="0" cellspacing="3" cellpadding="0" bgcolor="#fefae9" width="100%" class="text12">
<?
	$line_flag = false;
	if (check_staff_auth('ȯ�����������ݡ���')) {
?>
				<tr>
					<td align="right" width="15%"><img src="img/hatsugenkanri_menu.gif" width="10" height="10" border="0"></td>
					<td width="85%"><a href="log/choice.php" target="contents" title="ȯ�����������">ȯ�����������</a></td>
				</tr>
<?
		$line_flag = true;
	}
	if (check_staff_auth('ȯ��Ͽ�������ݡ���')) {
		if ($line_flag) {
?>
				<tr>
					<td colspan="2" align="center"><img src="img/menu_line.gif" width="200" height="1" border="0" vspace="1"></td>
				</tr>
<?
		}
?>
				<tr>
					<td align="right"><img src="img/hatsugenkanri_menu.gif" width="10" height="10" border="0"></td>
					<td><a href="log/hatugenroku.php" target="subject" title="ȯ��Ͽ���������">ȯ��Ͽ���������</a></td>
				</tr>
<?
		$line_flag = true;
	}
	if (check_staff_auth('ȯ�����ץ������ݡ���')) {
		if ($line_flag) {
?>
				<tr>
					<td colspan="2" align="center"><img src="img/menu_line.gif" width="200" height="1" border="0" vspace="1"></td>
				</tr>
<?
		}
?>
				<tr>
					<td align="right"><img src="img/hatsugenkanri_menu.gif" width="10" height="10" border="0"></td>
					<td><a href="count/index.php" target="contents" title="ȯ������">ȯ������</a></td>
				</tr>
<?
		$line_flag = true;
	}
	if (check_participant_auth('�ʲ��')) {
		if ($line_flag) {
?>
				<tr>
					<td colspan="2" align="center"><img src="img/menu_line.gif" width="200" height="1" border="0" vspace="1"></td>
				</tr>
<?
		}
?>
				<tr>
					<td align="right"><img src="img/hatsugenkanri_menu.gif" width="10" height="10" border="0"></td>
					<td><a href="log/choice_help.php" target="contents" title="�䤤��碌���������">�䤤��碌���������</a></td>
				</tr>
<?
	}
?>
		</table>

	</td>
</tr>
</table>
<?
}
?>

<br>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#D3AA07" width="220">
<tr>
	<td><img src="img/search.gif" alt="����" width="220" height="30" border="0"></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="1" bgcolor="#D3AA07" width="220">
<tr>
	<td>
		<form method="get" name="search" action="subject_search.php" target="subject">
		<input type="hidden" name="search_subject" value="1">
		<input type="hidden" name="search_content" value="1">
		<table border="0" cellspacing="3" cellpadding="0" bgcolor="#fefae9" width="100%" class="text12">
		<tr>
			<td>���֥������Ȥ���ʸ���鸡��������ޤ���ʣ����Ǹ����������Ⱦ�ѥ��ڡ����Ƕ��ڤäƸ������Ƥ���������<br>
			����˾ܤ�������������ϡ־ܺٸ����ץڡ������鸡�����Ƥ���������</td>
		</tr>
		<tr>
			<td align="center"><input type="text" name="search_keyword" value="" size="30" class="textbox"></td>
		</tr>
		<tr>
			<td align="center"><input type="submit" value="����"></td>
		</tr>
		<tr>
			<td align="center"><a href="search.php" target="contents">�ܺٸ���</a></td>
		</tr>
		</table>
		</form>
	</td>
</tr>
</table>

</div>

<table border="0" cellspacing="0" cellpadding="10" width="100%" id="backtotop">
<tr>
	<td align="right"><a href="index.php" target="_top" title="�ȥåפ����">���ȥåפ����</a></td>
</tr>
</table>

</body>

</html>
