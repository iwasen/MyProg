<?
$top = '.';
$inc = "$top/inc";
include("$inc/database.php");

if (($c + $s * 3 + 4240) % 10000 == $k) {
	$sql = "SELECT * FROM t_mail_data WHERE md_send_mail_id=$c AND (md_limit_date IS NULL OR TO_CHAR(now(), 'YYYYMMDD')<=TO_CHAR(md_limit_date, 'YYYYMMDD'))";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$sql = "SELECT ml_status FROM t_mail_list WHERE ml_send_mail_id=$c AND ml_sub_seq=$s";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$status = $fetch->ml_status;

			if ($status == 2 || $status == 3) {
				db_begin_trans();
				$sql = "UPDATE t_mail_list SET ml_status=4 WHERE ml_send_mail_id=$c AND ml_sub_seq=$s";
				db_exec($sql);
				$sql = "UPDATE t_mail_count SET mc_count$status=mc_count$status-1,mc_count4=mc_count4+1 WHERE mc_send_mail_id=$c";
				db_exec($sql);
				db_commit_trans();
				$msg = "���夳���餫��Υ᡼�뤬�Ϥ��ޤ���褦�ˡ�<br>�ꥹ�Ȥ��餢�ʤ����ޤΥ᡼�륢�ɥ쥹���������Ƥ��������ޤ�����";
			} elseif ($status == "4") {
				$msg = "���ʤ����ޤΥ᡼�륢�ɥ쥹�Ϥ��Ǥ˺������Ƥ���ޤ���";
			} else
				$msg = "�ɣĤ�����������ޤ���";
		} else
			$msg = "�ɣĤ���Ͽ����Ƥ��ޤ���";
	} else
		$msg = "��������դ��δ��¤�᤮�Ƥ��뤿�ᡢ�᡼�륢�ɥ쥹�κ���ϤǤ��ޤ���Ǥ�����";
} else
	$msg = "�ѥ�᡼��������������ޤ���";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000">
<p align="center">��</p>
<p align="center">��</p>
<p align="center">��</p>
<p align="center">
  <font color="#333333"><? echo $msg ?></font></p>
  <p align="center"><font color="#333333">�ޤ��Τ����Ѥ��Ԥ��������Ƥ���ޤ���</font></p>
<table border="0" cellpadding="3" cellspacing="5" align="center" width="400">
  <tr class="text">
		<td  ALIGN="center">
    	<form><INPUT TYPE="button" value="���β��̤��Ĥ���" onClick="window.close()"></form>
    </td>
  </tr>
</table>
</body>
</html>