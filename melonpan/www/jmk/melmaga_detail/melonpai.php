<?
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���ޥ��ʥӾ���ɽ������
'******************************************************/

$title_text = "���ޥ��ʥӾ���ɽ��";
$title_color = $TC_MASTER;

$sql ="SELECT MELONPAI_ID, MELONPAI_PSWD, MELONPAI_NAME, MELONPAI_NIC, MELONPAI_MAIL_ADDR, MELONPAI_ZIP1, MELONPAI_ZIP2, MELONPAI_ADD, MELONPAI_TEL, MELONPAI_M_FLG, MELONPAI_POINT";
$sql = $sql . ", TO_CHAR(modify_dt, 'YYYY') as modi_year";
$sql = $sql . ", TO_CHAR(modify_dt, 'MM') as modi_month";
$sql = $sql . ", TO_CHAR(modify_dt, 'DD') as modi_day"; 
$sql = $sql . " FROM M_MELONPAI";
$sql = $sql . " WHERE MELONPAI_ID = $melonpai_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_pswd = $fetch->melonpai_pswd;
	$melonpai_name = $fetch->melonpai_name;
	$melonpai_nic = $fetch->melonpai_nic;
	$melonpai_mail_addr = $fetch->melonpai_mail_addr;
	$melonpai_zip1 = $fetch->melonpai_zip1;
	$melonpai_zip2 = $fetch->melonpai_zip2;
	$melonpai_add = $fetch->melonpai_add;
	$melonpai_tel = $fetch->melonpai_tel;
	$melonpai_m_flg = $fetch->melonpai_m_flg;
	$melonpai_point = $fetch->melonpai_point;
	$modi_year = $fetch->modi_year;
	$modi_month = $fetch->modi_month;
	$modi_day = $fetch->modi_day;

	if ($melonpai_nic == "") {
		$melonpai_nic = "<br>";
	}
	if ($melonpai_add == "") {
		$melonpai_add = "<br>";
	}
	if ($melonpai_tel == "") {
		$melonpai_tel = "<br>";
	}
	if ($melonpai_m_flg == "0") {
		$melonpai_m_flg = "��ư��";
	}
	if ($melonpai_m_flg == "1") {
		$melonpai_m_flg = "�ٻ���";
	}
	if ($melonpai_m_flg == "2") {
		$melonpai_m_flg = "���";
	}

}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title>���ޥ��ʥӾ���</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>
<form method="post" name="form1" action="kanri_suuji2.php">
	<center>
		<table width='550'>
			<tr>
				<td align=right valign=bottom norap><small><font color=blue>��</font><A href='JavaScript:window.close()'>�Ĥ���</A></small></td>
				</td>
			</tr>
			<tr>
				<td>�����ޥ��ʥ���Ͽ����</td>
			</tr>
		</table>
		<table width='550' border="1" CELLSPACING="0" CELLPADDING="3">
			<tr>
				<td width="35%" class="h6">�ᡡ��̾</td>
				<td><?= htmlspecialchars($melonpai_name) ?></td>
			</tr>
			<tr>
				<td class="h6"><nobr>�᡼�륢�ɥ쥹</nobr></td>
				<td><?= replace_br(htmlspecialchars($melonpai_mail_addr)) ?></td>
			</tr>
			<tr>
				<td class="h6"><nobr>�ѥ����</nobr></td>
				<td><?= htmlspecialchars($melonpai_pswd) ?></td>
			</tr>
			<tr>
				<td class="h6"><nobr>�˥å��͡���</nobr></td>
				<td><?= replace_br(htmlspecialchars($melonpai_nic)) ?></td>
			</tr>
			<tr>
				<td class="h6">͹���ֹ�</td>
				<td><?= $melonpai_zip1 ?>-<?= $melonpai_zip2 ?></td>
			</tr>
			<tr>
				<td class="h6">��������</td>
				<td><?= replace_br(htmlspecialchars($melonpai_add)) ?></td>
			</tr>
			<tr>
				<td class="h6">�����ֹ�</td>
				<td><?= replace_br($melonpai_tel) ?></td>
			</tr>
			<tr>
				<td class="h6">��ư����</td>
				<td><?= $melonpai_m_flg ?></td>
			</tr>
			<tr>
				<td class="h6">�ݥ����</td>
				<td><?= number_format($melonpai_point) ?> �ݥ����</td>
			</tr>
			<tr>
				<td class="h6">��Ͽ��</td>
				<td name="modify_dt"><? print $modi_year . "ǯ" . $modi_month . "��" . $modi_day . "��"; ?></td>
			</tr>
		</table><br>
	<input type="button" value="���Ĥ��롡" onclick="window.close()"></form>
	</center>
</form>
</body>
</html>
