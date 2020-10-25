<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ǿ�����ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/format.php");

// Ϣ�����
function renraku_jikou($privilege) {
	global $g_staff_id;

	$sql = "SELECT msg_message_id,msg_regist_date,msg_title,msg_message,msr_message_id" .
				" FROM t_message LEFT JOIN t_message_read ON msr_message_id=msg_message_id AND msr_staff_id=$g_staff_id" .
				" WHERE msg_privilege='$privilege' AND (msg_to_staff_id=$g_staff_id OR msg_to_staff_id is null) AND (msg_start_date is null OR msg_start_date<=CURRENT_TIMESTAMP) AND (msg_end_date is null OR msg_end_date>CURRENT_TIMESTAMP)" .
				" ORDER BY msg_message_id desc";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow == 0)
		echo "<span class='disabled'>Ϣ�����Ϥ���ޤ���</span>\n";
	else {
		echo "<table  border=0 cellspacing=0 cellpadding=1 width='100%'>\n";
		for ($i = 0; $i < 10 && $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			echo "<tr bgcolor='#ffcccc'>\n";
			echo "<td>��", htmlspecialchars($fetch->msg_title), "</td>\n";
			echo "<td align='right'><nobr>", format_datetime($fetch->msg_regist_date), "</nobr></td>\n";
			echo "<td align='right' width=45>\n";
			if (!$fetch->msr_message_id)
				echo "<input type='button' value='��ǧ' onclick='location.href=\"msg_read.php?message_id=$fetch->msg_message_id\"' title='Ϣ�������ǧ�����饯��å����Ƥ�������'>\n";
			echo "</td>\n";
			echo "</tr><tr>\n";
			echo "<td colspan=2><pre>$fetch->msg_message</pre></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}

// ̤�����䤤��碌��
function toiawase1($thread_type, $link) {
	$sql = "SELECT count(*) AS total,count(case when cml_date+interval '1 day'<current_timestamp then 1 end) AS over24h"
			. " FROM t_comm_log"
			. " JOIN t_comm_thread ON cmt_comm_id=cml_thread_comm_id"
			. " WHERE cml_answer_flag=true AND cmt_thread_type=$thread_type AND cmt_status=1 AND cmt_staff_id is null";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$total = 0;
		$over24h = 0;
	} else {
		$fetch = pg_fetch_object($result, 0);
		$total = $fetch->total;
		$over24h = $fetch->over24h;
	}

	echo '������', show_num($total, $link), '��24���ְʾ�в�η����', show_num($over24h);
}

// �б����䤤��碌��
function toiawase2($thread_type, $link) {
	global $g_staff_id;

	$sql = "SELECT count(*) AS total,count(case when cmt_staff_id=$g_staff_id then 1 end) AS tantou"
			. " FROM t_comm_log"
			. " JOIN t_comm_thread ON cmt_comm_id=cml_thread_comm_id"
			. " WHERE cml_answer_flag=true AND cmt_thread_type=$thread_type AND cmt_status=1 AND cmt_staff_id is not null";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$total = 0;
		$tantou = 0;
	} else {
		$fetch = pg_fetch_object($result, 0);
		$total = $fetch->total;
		$tantou = $fetch->tantou;
	}

	echo '������', show_num($total, $link), '��ô��ʬ�����', show_num($tantou);

	echo '��<input type="button" value="���ڥ졼���̷��"', $total ? '' : ' disabled', ' onclick="onClick_response(' . $thread_type . ')">';
}

// 1���ְʾ�ȿ���Τʤ��桼����
function toiawase3($mail_type, $link) {
	$sql = "SELECT COUNT(*) FROM t_mail_reply WHERE mrp_date<current_timestamp";
	$num = db_fetch1($sql);

	echo '�桼������', show_num($num, $link);
}

// ����ɽ���ʣ��ʳ����֤�ɽ����
function show_num($num, $link = '') {
	if ($num == 0)
		return number_format($num);
	else {
		$s = '<font color="red"><b>' . number_format($num) . '</b></font>';
		if ($link != '')
			$s = "<a href='$link'>$s</a>";
		return $s;
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onClick_response(thread_type) {
	window.open("response.php?thread_type=" + thread_type, "response", "width=500,height=500,resizable=yes,scrollbars=yes");
}
//-->
</script>
</head>
<body>

<? center_header('�ǿ�����ɽ��') ?>

<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0">��Ϣ�����</td>
		<td class="m0" align="right"><input type="button" value="���Υ�å�����" onclick="location.href='msg_list.php'"></td>
	</tr>
	<tr>
		<td class="m1" width="22%">DH�����åդ����Ϣ�����</td>
		<td class="n1"><? renraku_jikou(PRIV_DHSTAFF) ?></td>
	</tr>
	<tr>
		<td class="m1">���ڥ졼�������Ϣ�����</td>
		<td class="n1"><? renraku_jikou(PRIV_OPERATOR) ?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>��OneToOne�᡼���б�����</td>
	</tr>
	<tr>
		<td class="m1">̤�б��᡼���</td>
		<td class="n1"><? toiawase1(1, "$top/onetoone/frame1.php?list=1") ?></td>
	</tr>
	<tr>
		<td class="m1">�б���᡼���</td>
		<td class="n1"><? toiawase2(1, "$top/onetoone/frame1.php?list=2") ?></td>
	</tr>
	<tr>
		<td class="m1">1���ְʾ�ȿ���Τʤ��桼����</td>
		<td class="n1"><? toiawase3(1, "$top/onetoone/frame1.php?list=4") ?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���䤤��碌�б�����</td>
	</tr>
	<tr>
		<td class="m1">̤�б��䤤��碌��</td>
		<td class="n1"><? toiawase1(2, "$top/inquiry/list1.php") ?></td>
	</tr>
	<tr>
		<td class="m1">�б����䤤��碌��</td>
		<td class="n1"><? toiawase2(2, "$top/inquiry/list2.php") ?></td>
	</tr>
</table>

<br>
<div align="center">
<input type="button" value="�ǿ�ɽ��" onclick="location.reload()">
</div>

<? center_footer() ?>

</body>
</html>
