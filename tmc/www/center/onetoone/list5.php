<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦̤�б�����ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list.php");

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'comm_list5', 'sort_col', 'sort_dir');

// �����Υ桼������
$order_by = order_by(1, 0, 'ust_user_id', 'umg_customer_cd', 'ups_name_kana', 'umg_dealer_cd', 'cmc_comm_type;cml_send_recv', 'cml_date', 'day_num', 'ust_follow_end_date');
$sb1 = "SELECT ust_user_id,MAX(cml_comm_id) AS sb1_comm_id,ust_follow_end_date"
		. " FROM t_user_status"
		. " LEFT JOIN t_comm_log ON cml_user_id=ust_user_id"
		. " WHERE ust_rel_stop_flag=true AND ust_status IN (1,2)"
		. " GROUP BY ust_user_id,ust_follow_end_date";
$sql = "SELECT ust_user_id,ups_name_kanji,umg_customer_cd,cml_date,cml_send_recv,cmc_comm_type,dlr_short_name,sb1_comm_id,ust_follow_end_date,COALESCE(date_part('day',current_date-urg_follow_date),0) AS day_num"
		. " FROM ($sb1) sb1"
		. " JOIN t_user_regist ON urg_user_id=ust_user_id"
		. " JOIN t_user_personal ON ups_user_id=ust_user_id"
		. " JOIN t_user_manage ON umg_user_id=ust_user_id"
		. " JOIN t_dealer ON dlr_dealer_cd=umg_dealer_cd"
		. " LEFT JOIN t_comm_log ON cml_comm_id=sb1_comm_id"
		. " LEFT JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
var g_user_id;
function disp_user_info(user_id) {
	if (user_id != g_user_id) {
		g_user_id = user_id;
		top.main.user.location.href = "user_info.php?user_id=" + user_id + "&setting=1";
	}
}
function change_mode(url) {
	location.href = url;
	disp_user_info("");
}
function onclick_user_name(user_id) {
	setTimeout("disp_user_info(" + user_id + ")", 0);
}
//-->
</script>
<? list_script() ?>
</head>
<body>

<? center_header('OneToOne�᡼��') ?>

<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���᡼���ۿ������桼������</td>
		<td class="lb" style="color:navy;font-size:85%">
			<input type="radio" onclick="change_mode('list1.php')">̤�б�
			<input type="radio" onclick="change_mode('list2.php')">�б���
			<input type="radio" onclick="change_mode('list3.php')">�б�����
			<input type="radio" onclick="change_mode('list4.php')">̵ȿ��
			<input type="radio" checked>�����
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '�桼��ID');
		sort_header(2, '������CD');
		sort_header(3, '�桼��̾');
		sort_header(4, '����Ź̾');
		sort_header(5, '�Ǹ��<br>�᡼�����');
		sort_header(6, '�Ǹ��<br>����������');
		sort_header(7, '���<br>�ۿ���');
		sort_header(8, '��λ����<br>�ۿ���');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$user_id = $fetch->ust_user_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="../user/info.php?user_id=<?=$user_id?>" target="_blank" title="�̥�����ɥ��˥桼�������ɽ�����ޤ�"><?=$fetch->ust_user_id?></a></td>
		<td align="center"><?=$fetch->umg_customer_cd?></td>
		<td><a href="javascript:onclick_user_name(<?=$user_id?>)" title="���ե졼��˥桼�������ɽ�����ޤ�"><?=htmlspecialchars($fetch->ups_name_kanji)?></a></td>
		<td><?=htmlspecialchars($fetch->dlr_short_name)?></td>
<?
	if ($fetch->sb1_comm_id != 0) {
?>
		<td align="center"><?=decode_comm_type2($fetch->cmc_comm_type)?><?=decode_send_recv($fetch->cml_send_recv)?></td>
		<td align="center"><?=format_datetime($fetch->cml_date)?></td>
<?
	} else {
?>
		<td align="center">�ʥ᡼��̵����</td>
		<td align="center">�ʥ᡼��̵����</td>
<?
	}
?>
		<td align="right"><?=(int)($fetch->day_num / 7)?>��</td>
		<td align="center"><?=$fetch->ust_follow_end_date ? format_date($fetch->ust_follow_end_date) : '-'?></td>
	</tr>
<?
}
?>
</table>

<? center_footer() ?>

</body>
</html>
