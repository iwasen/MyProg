<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦�桼������ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$user_id = $_GET['user_id'];
$setting = $_GET['setting'];

// ����
switch ($_GET['action']) {
case 1:		// �ۿ��������ơ������ѹ�
	$rec['ust_rel_mail_vol'] = "overlay(COALESCE(ust_rel_mail_vol,repeat('0'," . MAX_RELATION_MAIL . ")) placing '{$_GET['status']}' from {$_GET['vol_no']} for 1)";
	db_update('t_user_status', $rec, "ust_user_id=$user_id");
	break;
case 2:		// �ۿ��Ƴ�
	$rec['ust_rel_stop_flag'] = 'false';
	db_update('t_user_status', $rec, "ust_user_id=$user_id");
	break;
case 3:		// �ۿ����
	$rec['ust_rel_stop_flag'] = 'true';
	db_update('t_user_status', $rec, "ust_user_id=$user_id");
	break;
}

// �桼��ID��̵����в���ɽ�����ʤ�
if ($user_id) {
	$sql = "SELECT ups_name_kanji,umg_car_model_cd,ust_status,ust_follow_mail_vol,ust_rel_mail_vol,ust_rel_stop_flag,urg_handover_date,date_part('day',current_date-urg_follow_date) AS day_num"
			. " FROM t_user_personal"
			. " JOIN t_user_manage ON umg_user_id=ups_user_id"
			. " JOIN t_user_status ON ust_user_id=ups_user_id"
			. " JOIN t_user_regist ON urg_user_id=ups_user_id"
			. " WHERE ups_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$name_kanji = $fetch->ups_name_kanji;
		$car_model_cd = $fetch->umg_car_model_cd;
		$status = $fetch->ust_status;
		$follow_mail_vol = $fetch->ust_follow_mail_vol;
		$rel_mail_vol = $fetch->ust_rel_mail_vol;
		$rel_stop_flag = $fetch->ust_rel_stop_flag;
		$handover_date = $fetch->urg_handover_date;
		$day_num = $fetch->day_num;
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
function onclick_follow(vol_no) {
	window.open("show_mail.php?mail=follow&user_id=<?=$user_id?>&vol_no=" + vol_no, "_blank", "width=600,scrollbars=yes,resizable=yes");
}
function onclick_theme(vol_no) {
	window.open("show_mail.php?mail=relation&user_id=<?=$user_id?>&vol_no=" + vol_no, "_blank", "width=600,scrollbars=yes,resizable=yes");
}
function onclick_change0(vol_no) {
	if (confirm("Vol." + vol_no + "���ۿ�������̤�ۿ����ѹ����ޤ�����"))
		location.href = "user_info.php?user_id=<?=$user_id?>&action=1&vol_no=" + vol_no + "&status=0" + "&setting=1";
}
function onclick_change2(vol_no) {
	if (confirm("Vol." + vol_no + "���ۿ��������ۿ��Ѥ�(OneToOne)���ѹ����ޤ�����"))
		location.href = "user_info.php?user_id=<?=$user_id?>&action=1&vol_no=" + vol_no + "&status=2" + "&setting=1";
}
function onclick_restart() {
	if (confirm("�᡼����ۿ��Ƴ����ޤ�����"))
		location.href = "user_info.php?user_id=<?=$user_id?>&action=2" + "&setting=1";
}
function onclick_stop() {
	if (confirm("�᡼����ۿ���ߤ��ޤ�����"))
		location.href = "user_info.php?user_id=<?=$user_id?>&action=3" + "&setting=1";
}
function onclick_hot() {
	if (confirm("���Υ桼���ΰ��Ѥ�������Ԥ��ޤ�����"))
		window.open("result_input.php?user_id=<?=$user_id?>");
}
function onload_body() {
<?
if ($_GET['action'] == 2 || $_GET['action'] == 3) {
?>
	parent.mail.location.reload();
<?
}
?>
}
//-->
</script>
</head>
<body bgcolor="aliceblue" class="small" style="margin:5" onload="onload_body()">
<?
if ($user_id) {
?>
<table class="small" width="100%" style="border:outset;font-weight:bold;background-color:lightgrey">
	<tr>
		<td align="center"><?=htmlspecialchars($name_kanji)?>����ξ���</td>
	</tr>
</table>
<br>
<a href="../user/info.php?user_id=<?=$user_id?>" target="_blank">�ܺ���Ͽ����ɽ��</a><br>
<span class="note">���̥�����ɥ���ɽ������ޤ���</span>
<br>
<?
	if ($day_num && !$handover_date) {
?>
<br>
<font class="small" color="#444444">�ڽ���ۿ���<?=(int)($day_num / 7)?>���в��</font><br>
<?
	}
	if ($setting) {
?>
<br>
<font color="darkblue">�����Ѥ�</font><br>
<?
		if (!$handover_date) {
?>
<span class="note">����������HOT�ץ��ơ������ˤʤä���硢���Ρְ��Ѥ��ץܥ���򲡤��Ƥ���������</span>
<?
	 } else {
?>
<span class="note">�������Ϥ��Ǥ˰��Ѥ���ԤäƤ��ޤ���</span>
<?
		}
?>
<table border=0 cellspacing=0 cellpadding=1 width="100%" class="small">
	<tr>
		<td align="center"><input type="button" name="hot" value="�����Ѥ���" onclick="onclick_hot()" <?=disabled($handover_date)?>></td>
	</tr>
</table>
<?
	}
?>
<br><br>
<font color="darkblue">������ե����᡼���ۿ�����</font><br>
<span class="note">��Subject�Υ�󥯤򥯥�å�������ۿ������᡼�륳��ƥ�Ĥ�ɽ������ޤ���</span>
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr bgcolor="lightblue">
		<th>Vol</th>
		<th>Subject</th>
		<th>�ۿ�����</th>
	</tr>
<?
	// ����ե����᡼��η�̾����
	$sql = "SELECT fls_vol_no,fls_subject FROM t_follow_subject WHERE fls_car_model_cd=$car_model_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$follow_subject[(int)$fetch->fls_vol_no] = $fetch->fls_subject;
	}

	// ����ե����᡼���ۿ�����ɽ��
	for ($i = 0; $i < 4; $i++) {
		$vol_no = $i + 1;

		// ����ե����᡼���ۿ���������
		if ($i < $follow_mail_vol)
			$st = '<font color="blue">��</font>';
		else
			$st = '<font color="red">̤</font>';
?>
	<tr>
		<td align="right"><?=$vol_no?></td>
		<td><a href="javascript:onclick_follow(<?=$vol_no?>)"><?=htmlspecialchars($follow_subject[$vol_no])?></a></td>
		<td align="center"><?=$st?></td>
	</tr>
<?
	}
?>
</table>
<br><br>
<font color="darkblue">����³��졼�����᡼���ۿ�����</font><br>
<span class="note">�ƥơ��ޤΥ�󥯤򥯥�å�������ۿ������᡼�륳��ƥ�Ĥ�ɽ������ޤ���</span>
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr bgcolor="lightgreen">
		<th>Vol</th>
		<th>�ơ���</th>
		<th>�ۿ�����</th>
	</tr>
<?
	$sql = "SELECT rlt_vol_no,rlt_theme"
			. " FROM t_relation_theme"
			. " WHERE rlt_car_category_cd=(SELECT crm_car_category_cd FROM t_car_model WHERE crm_car_model_cd=$car_model_cd)"
			. " ORDER BY rlt_vol_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$vol_no = $fetch->rlt_vol_no;

		// ��³��졼�����᡼���ۿ���������
		switch ($rel_mail_vol{$vol_no - 1}) {
		case 1:
			$st = '<font color="blue">��</font>';
			break;
		case 2:
			$st = '<font color="green"><nobr>��(1to1)</nobr></font>';
			if ($setting)
				$st .= '<br><input type="button" value="�ѹ�" style="height:19" onclick="onclick_change0(' . $vol_no . ')">';
			break;
		default:
			$st = '<font color="red">̤</font>';
			if ($setting)
				$st .= '<input type="button" value="�ѹ�" style="height:19" onclick="onclick_change2(' . $vol_no . ')">';
			break;
		}
?>
	<tr>
		<td align="right"><?=$vol_no?></td>
		<td><a href="javascript:onclick_theme(<?=$vol_no?>)"><?=htmlspecialchars($fetch->rlt_theme)?></a></td>
		<td align="center"><?=$st?></td>
	</tr>
<?
	}
?>
</table>
<br><br>
<?
	switch ($status) {
	case 1:
		if ($rel_stop_flag == DBFALSE)
			$st = '����ե����᡼���ۿ���';
		else
			$st = '����ե����᡼���������';
		break;
	case 2:
		if ($rel_stop_flag == DBFALSE)
			$st = '��³��졼�����᡼���ۿ���';
		else
			$st = '��³��졼�����᡼���������';
		break;
	case 3:
		$st = '���Ѥ��Ѥ�';
		break;
	case 7:
		$st = '�᡼����ã';
		break;
	case 8:
		$st = '�᡼����ɲ��';
		break;
	case 9:
		$st = '��λ�Ѥ�';
		break;
	default:
		$st = '����';
		break;
	}
?>
<font color="darkblue">���᡼���ۿ����ơ�����</font>
<table border=0 cellspacing=0 cellpadding=1 width="100%" class="small">
	<tr>
		<td><font color="darkred">��<?=$st?>��</font></td>
	</tr>
	<tr>
<?
	if ($setting && ($status == 1 || $status == 2)) {
		if ($rel_stop_flag == DBTRUE) {
?>
		<td align="center"><input type="button" value="�ۿ��Ƴ�" onclick="onclick_restart()"></td>
<?
		} else {
?>
		<td align="center"><input type="button" value="�ۿ����" onclick="onclick_stop()"></td>
<?
		}
	}
?>
	</tr>
</table>
<?
}
?>
</body>
</html>
