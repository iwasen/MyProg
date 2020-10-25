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

// ����ɽ����24���ְʾ�в���֡�
function cml_date($date) {
	if (sql_time($date) < time() - (60 * 60 * 24))
		return '<font color="red">' . format_datetime($date) . '</font>';
	else
		return format_datetime($date);
}

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'comm_list1', 'contents', 'sort_col', 'sort_dir');

// �ե졼�����
if ($contents && !$_GET['frame']) {
		include('frame.php');
		exit;
}

$target = $_GET['frame'] ? 'parent' : 'self';

// ̤�б����䤤��碌����
$order_by = order_by(1, 0, 'cml_date', 'cmc_comm_type', 'cml_comm_id', 'cmc_subject', 'ups_name_kana', 'dlr_dealer_cd', 'tts_tantousha_id', 'stf_staff_id');
$sql = "SELECT cml_comm_id,cml_date,cml_send_recv,cmc_comm_type,cml_user_id,cmc_subject,ups_name_kanji,dlr_short_name,tts_tantousha_name,stf_staff_id,stf_name"
		. " FROM t_comm_log"
		. " JOIN t_comm_thread ON cmt_comm_id=cml_thread_comm_id"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_personal ON ups_user_id=cml_user_id"
		. " JOIN t_user_manage ON umg_user_id=cml_user_id"
		. " JOIN t_dealer ON dlr_dealer_cd=umg_dealer_cd"
		. " JOIN t_tantousha ON tts_tantousha_id=dlr_tantousha_id"
		. " LEFT JOIN t_staff ON stf_staff_id=cmt_staff_id"
		. " WHERE cml_answer_flag=true AND cmt_status<>9 AND cmt_thread_type=1 $order_by";
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
function check_contents(c) {
	var f = document.form1;
	if (!c) {
		f.frame.value = "";
		f.target = "_parent";
	}
	f.submit();
}
function change_mode(url) {
	<?=$target?>.location.href = url;
	disp_user_info("");
}
function onclick_subject(comm_id, user_id) {
	setTimeout("disp_comm(" + comm_id + "," + user_id + ")", 0);
}
function disp_comm(comm_id, user_id) {
	disp_user_info(user_id);
<? if ($contents) { ?>
	parent.contents.location.href = "contents.php?comm_id=" + comm_id + "&url=list1.php";
<? } else { ?>
	location.href = "show.php?comm_id=" + comm_id + "&url=list1.php&del=1";
<? } ?>
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
		<td class="lt">��̤�б��᡼�����</td>
		<td style="color:navy;font-size:85%" align="center">
			<nobr><input type="checkbox" name="contents" onclick="check_contents(checked)" <?=value_checked('1', $contents)?>>�ʰ�����ɽ��</nobr>
		</td>
		<td class="lb" style="color:navy;font-size:85%">
			<input type="radio" checked>̤�б�
			<input type="radio" onclick="change_mode('list2.php')">�б���
			<input type="radio" onclick="change_mode('list3.php')">�б�����
			<input type="radio" onclick="change_mode('list4.php')">̵ȿ��
			<input type="radio" onclick="change_mode('list5.php')">�����
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="frame" <?=value($_GET['frame'])?>>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '����');
		sort_header(2, '����');
		sort_header(3, '����');
		sort_header(4, '��̾');
		sort_header(5, '�桼��̾');
		sort_header(6, '����Ź̾');
		sort_header(7, 'ô����');
		sort_header(8, '���ڥ졼��');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($contents) {
		$href = 'contents.php';
		$target = 'contents';
	} else {
		$href = 'show.php';
		$target = '_self';
	}
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=cml_date($fetch->cml_date)?></td>
		<td><?=decode_comm_type2($fetch->cmc_comm_type)?></td>
		<td align="center"><?=$fetch->cml_comm_id?></td>
		<td><a href="javascript:onclick_subject(<?=$fetch->cml_comm_id?>,<?=$fetch->cml_user_id?>)" title="�᡼������Ƥ�ɽ�����ޤ�"><?=$fetch->cmc_subject != '' ? htmlspecialchars($fetch->cmc_subject) : '�ʷ�̵̾����'?></a></td>
		<td><?=htmlspecialchars($fetch->ups_name_kanji)?></td>
		<td><?=htmlspecialchars($fetch->dlr_short_name)?></td>
		<td><?=htmlspecialchars($fetch->tts_tantousha_name)?></td>
		<td><?=htmlspecialchars($fetch->stf_staff_id ? $fetch->stf_name : '̤�б�')?></td>
	</tr>
<?
}
?>
</table>

<? center_footer() ?>

</body>
</html>
