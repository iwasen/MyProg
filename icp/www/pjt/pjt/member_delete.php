<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:�ץ������ȥ��к���ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���ϥѥ�᡼��
$member_id = $_POST['member_id'];

// ���о������
$sql = "SELECT pm_name1,pm_name2,pm_name1_kana,pm_name2_kana,pm_mail_addr,pm_password,pm_org_id,pm_staff_auth,or_name"
		. " FROM t_pjt_member"
		. " JOIN m_org ON or_org_id=pm_org_id"
		. " WHERE pm_member_id=" . sql_number($member_id);
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	$auth = $fetch->pm_staff_auth;
} else
	program_err(__FILE__, __LINE__);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>��icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	return confirm("���Υ��С��������ޤ���������Ǥ�����");
}
//-->
	</script>
</head>

<body>
<div align="center">

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="�ۡ���">�ۡ���</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		�ץ������Ⱦ���
		&nbsp;&nbsp;��&nbsp;&nbsp;
		<a href="list.php" title="���С��ꥹ��">���С��ꥹ��</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		�����ǧ
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- ��������� -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- ��������� -->

<!-- main -->
<form method="post" name="form1" action="member_delete_comp.php" onsubmit="return onsubmit_form1(this)">
<input type="hidden" name="member_id" <?=value($member_id)?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="0" width="760" class="contents">
		<tr>
			<td>�����Υ��С��ξ���������ޤ����������к���ܥ���򲡤��Ƥ���������</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">ID</th>
			<td width="560"><?=$member_id?></td>
		</tr>
		<tr>
			<th>��̾</th>
			<td><?=htmlspecialchars($fetch->pm_name1)?>��<?=htmlspecialchars($fetch->pm_name2)?></td>
		</tr>
		<tr>
			<th>��̾�ʥեꥬ�ʡ�</th>
			<td><?=htmlspecialchars($fetch->pm_name1_kana)?>��<?=htmlspecialchars($fetch->pm_name2_kana)?></td>
		</tr>
		<tr>
			<th>�᡼�륢�ɥ쥹</th>
			<td><?=htmlspecialchars($fetch->pm_mail_addr)?></td>
		</tr>
		<tr>
			<th>������ѥ����</th>
			<td><?=htmlspecialchars($fetch->pm_password)?></td>
		</tr>
		<tr>
			<th>��ȡ��ȿ�</th>
			<td><?=htmlspecialchars($fetch->or_name)?></td>
		</tr>
		<tr>
			<th>����</th>
			<td>
<?
foreach ($g_staff_auth as $auth_key => $auth_cd) {
	if (check_staff_auth($auth_key, $auth)) {
?>
			<?=$auth_key?><br>
<?
	}
}
?>
			</td>
		</tr>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- ��� -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="�������" class="form">
	<input type="button" value="����󥻥�" class="form" onclick="history.back()"></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
</form>
<!-- ��� -->

<? pjt_footer() ?>

</div>
</body>

</html>
