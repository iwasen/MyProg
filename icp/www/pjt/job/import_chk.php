<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:����֥��Х���ݡ��ȥ����å��ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/check.php");
include("$inc/format.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/import.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");

// ���ϥѥ�᡼��
$job_id = $_POST['job_id'];

// �����̾�ʥ���֥����ɡ˼���
$job_name_cd = get_job_name_cd($job_id);

read_import_file($_FILES['import_file']['tmp_name'], $ok_ary, $ng_ary, $_SESSION[SCD]['pjt_id'], $job_id);
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
	if (f.import_data.value == "") {
		alert("����ݡ��ȥ���ݡ��Ȥ���ǡ���������ޤ���");
		return false;
	}
	return confirm("���С��򥤥�ݡ��Ȥ��ޤ���������Ǥ�����");
}
//-->
	</script>
</head>

<body>
<div align="center">

<? pjt_header('close') ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td>�ۡ���
		&nbsp;&nbsp;��&nbsp;&nbsp;
		<?=htmlspecialchars($job_name_cd)?>����
		&nbsp;&nbsp;��&nbsp;&nbsp;
		���С��ꥹ��
		&nbsp;&nbsp;��&nbsp;&nbsp;
		����ݡ��ȳ�ǧ
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
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="2" width="760" class="contents">
		<tr>
			<td>�ޤ�����ݡ��Ȥϴ�λ���Ƥ��ޤ��󡣤��Υڡ����ǥ���ݡ��ȥܥ���򲡤��ȡ�����ݡ��ȴ�λ�Ȥʤ�ޤ���<br>
			����������ϡ�����󥻥�ܥ���򲡤������Υڡ�������ꡢ�������ե�����򥢥åץ��ɤ��Ƥ���������<br><br>
			</td>
		</tr>
		</table>

		<table border="0" cellspacing="0" cellpadding="2" width="760" class="contents">
		<tr>
			<td>������ݡ��Ȳ�ǽ</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="list" frame="box">
		<tr>
			<th width="20" rowspan="2">ID</th>
			<th width="40" rowspan="2" style="font-size:10px;">�ȿ�ID</th>
			<th width="40" rowspan="2" style="font-size:10px;">���С�ID</th>
			<th width="75" rowspan="2">��̾</th>
			<th width="75" rowspan="2">�˥å��͡���</th>
			<th width="150" rowspan="2">�᡼�륢�ɥ쥹</th>
			<th width="210" colspan="8">����</th>
		</tr>
		<tr style="font-size:10px;">
			<th width="42">�ʲ��</th>
			<th width="42">WEB<br>ȯ��</th>
			<th width="42">�᡼��<br>����</th>
			<th width="42">�᡼��<br>����</th>
			<th width="42">ȯ��<br>ON/OFF</th>
		</tr>
<?
$n = count($ok_ary);
for ($i = 0; $i < $n; $i++) {
	$data = &$ok_ary[$i];
?>
		<tr class="line<?=$i % 2?>">
			<td align="center"><?=format_text($data['member_id'], '-')?></td>
			<td align="center"><?=$data['org_id']?></td>
			<td align="center"><?=htmlspecialchars($data['org_member_id'])?></td>
			<td><?=htmlspecialchars($data['name1'])?><?=htmlspecialchars($data['name2'])?></td>
			<td><?=htmlspecialchars($data['nickname'])?></td>
			<td><?=htmlspecialchars($data['mail_addr'])?></td>
			<td align="center"><?=check_participant_auth('�ʲ��', $data['participant_auth']) ? '��' : '-'?></td>
			<td align="center"><?=check_participant_auth('Webȯ��', $data['participant_auth']) ? '��' : '-'?></td>
			<td align="center"><?=check_participant_auth('�᡼������', $data['participant_auth']) ? '��' : '-'?></td>
			<td align="center"><?=check_participant_auth('�᡼�����', $data['participant_auth']) ? '��' : '-'?></td>
			<td align="center"><?=check_participant_auth('ȯ��ON/OFF', $data['participant_auth']) ? '��' : '-'?></td>
		</tr>
<?
}
?>
		</table>

		<table border="0" cellspacing="0" cellpadding="2" width="760" class="contents">
		<tr>
			<td><br>������ݡ����Բ�</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="list" frame="box">
		<tr>
			<th width="20">ID</th>
			<th width="40" style="font-size:10px;">�ȿ�ID</th>
			<th width="40" style="font-size:10px;">���С�ID</th>
			<th width="75">��̾</th>
			<th width="75">�˥å��͡���</th>
			<th width="150">�᡼�륢�ɥ쥹</th>
			<th width="210">��ͳ</th>
		</tr>
<?
$n = count($ng_ary);
for ($i = 0; $i < $n; $i++) {
	$data = &$ng_ary[$i];
?>
		<tr class="line<?=$i % 2?>">
			<td align="center"><?=1?></td>
			<td align="center"><?=$data['org_id']?></td>
			<td align="center"><?=htmlspecialchars($data['org_member_id'])?></td>
			<td><?=htmlspecialchars($data['name1'])?><?=htmlspecialchars($data['name2'])?></td>
			<td><?=htmlspecialchars($data['nickname'])?></td>
			<td><?=htmlspecialchars($data['mail_addr'])?></td>
			<td class="warning">
<?
	foreach ($data['err'] as $err) {
?>
				��<?=htmlspecialchars($err)?><br>
<?
	}
?>
			</td>
<?
}
?>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- ��� -->
<form method="post" name="form1" action="import_comp.php" onsubmit="return onsubmit_form1(this)">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="import_data" <?=value(serialize($ok_ary))?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="����ݡ���" class="form">
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
