<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:ȯ��Ͽɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/crypt.php");
include("$inc/define.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");
include("$inc/record.php");

// ���¥����å�
if (!check_staff_auth('ȯ��Ͽ�������ݡ���'))
	redirect("$top/room/top.php");

// ���ϥѥ�᡼��
$job_id = $_SESSION[SCD]['job_id'];
$remark_id = decrypt_id($_GET['id']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#D3AA07" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="4" bgcolor="#fefae9" width="100%">
		<tr>
			<td width="50%" class="text12b">ȯ��Ͽ����</td>
			<td width="50%" align="right"><input type="button" value="���������" class="button" onclick="location.href='download2.php?remark_id=<?=$remark_id?>'"></td>
		</tr>
		</table></td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#D3AA07" align="center">
<tr>
	<td>
		<table border="1" cellspacing="0" cellpadding="5" bgcolor="#ffffff" align="center" class="text12" frame="box">
		<tr bgcolor="#fefae9">
<?
// �˥å��͡���ɽ��
$nickname_ary = get_nickname_ary($job_id);
foreach ($nickname_ary as $nickname) {
?>
			<td width="400"><nobr><?=htmlspecialchars($nickname)?></nobr></td>
<?
}
?>
		</tr>
<?
// ȯ��Ͽ�ǡ���ɽ��
$remark_ary = get_record_ary($job_id, $remark_id);
$member_id_ary = array_keys($nickname_ary);
foreach ($remark_ary as $remark) {
?>
		<tr valign="top">
<?
	foreach ($member_id_ary as $member_id) {
?>
			<td>
<?
		if ($remark[$member_id]->seq_no) {
?>
<pre>[<?=$remark[$member_id]->seq_no?>] <?=htmlspecialchars($remark[$member_id]->subject)?><?="\n"?><?=htmlspecialchars($remark[$member_id]->content)?>
</pre>
<?
		}
?>
</td>
<?
	}
?>
		</tr>
<?
}
?>
		</table></td>
</tr>
</table>


<table border="0" cellspacing="0" cellpadding="10" width="100%" id="backtotop">
<tr>
	<td align="right"><input type="button" value="���������" class="button" onclick="location.href='download2.php?remark_id=<?=$remark_id?>'"></td>
</tr>
<tr>
	<td align="right"><a href="#top" title="���Υڡ����Υȥåפ����">�����Υڡ����Υȥåפ����</a></td>
</tr>
</table>

</body>

</html>
