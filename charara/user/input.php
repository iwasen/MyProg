<?
/******************************************************
' System :�֤�����Factory�ץ桼���ڡ���
' Content:���鳨����������������
'******************************************************/

$inc = "../inc";
include_once("$inc/common.php");
include_once("$inc/database.php");
include_once("$inc/status.php");

// ID���ϥ����å�
if ($id == '') {
	$msg = '��������ID�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	include('error1.php');
	exit;
}

// ID����ʸ�����Ѵ�
$id = strtoupper($id);

// ID¸�ߥ����å�
$sql = "SELECT nd_status FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
$status = db_fetch1($sql, 0);
if ($status == 0) {
	$msg = '��������ID���������ʤ��褦�Ǥ�������ǧ�ξ塢�⤦�������Ϥ��Ƥ���������';
	include('error1.php');
	exit;
}

// ���պѤߥ����å�
if ($status > STATUS_USER_RECEIVED) {
	$msg = '���������ߤϤ��Ǥ˼����դ����Ƥ��ޤ���';
	include('error1.php');
	exit;
}

// �ǥե���ȤΥɥᥤ��̾����
if ($mail_addr == '') {
	switch (substr($id, 0, 2)) {
	case 'DS':
		$mail_addr = '@docomo.ne.jp';
		break;
	}
}

// �ǥե���Ȥ�����
if ($sex_code == '')
	$sex_code = 2;

// Shift_JIS�ǽ���
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>������Factory</title>
</head>
<body>
<form method="post" name="form1" action="accept.php">
<h2 align="center"><font color="#FFCC00">��</font><font color="#dd0000">������</font><font color="#FFCC00">��</font></h2>
<hr color="#5555dd">
<font color="red">��</font>�᡼�륢�ɥ쥹<br>
<center>
	<input type="text" name="mail_addr" <?=value($mail_addr)?> istyle="3"><br>
</center>
<font color="red">��</font>����<br>
<center>
	<input type="radio" name="sex_code" <?=value_checked('2', $sex_code)?>>����
	<input type="radio" name="sex_code" <?=value_checked('1', $sex_code)?>>����
</center>
<font color="red">��</font>ǯ���Ǥ�ա�<br>
<center>
	<input type="text" name="age" size="3" <?=value($age)?> istyle="4">��
</center>
<hr color="#5555dd">
<center>
	<input type="submit" value="��������">
</center>
<input type="hidden" name="id" value="<?=$id?>">
</form>
</body>
</html>
