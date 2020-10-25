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
include("$inc/select.php");
include("$inc/thread.php");

// Ÿ�������å�
function open_checked($comm_id) {
	global $open;

	if (is_array($open))
		return in_array($comm_id, $open);
	return false;
}

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'comm_list3', 'contents', 'name_mail', 'title', 'staff_id', 'open', 'displine', 'sort_col', 'sort_dir', 'page');

// �ե졼�����
if ($contents && !$_GET['frame']) {
		include('frame.php');
		exit;
}

$target = $_GET['frame'] ? 'parent' : 'self';

$where = "WHERE cmt_status=9 AND cmt_thread_type=1";

// �桼��ID���
if ($name_mail != '') {
	and_join($where, "(ups_name_kana LIKE '%$name_mail%' OR ups_name_kanji LIKE '%$name_mail%' OR ups_mail_addr LIKE '%$name_mail%')");
}

// �����ȥ���
if ($subject != '')
	and_join($where, "cmc_subject LIKE '%$subject%'");

// �б�Staff���
if ($staff_id != '')
	and_join($where, "cmt_staff_id='$staff_id'");

// �����Ⱦ��
$order_by = order_by(1, 1, 'cml_date', 'cmc_comm_type', 'cml_comm_id', 'cmc_subject', 'ups_name_kana', 'dlr_dealer_cd', 'tts_tantousha_id', 'cmt_staff_id');

// ɽ���Կ����
$limit = disp_limit();

// ��λ�Ѥ��䤤��碌�������
$sql = "SELECT cml_comm_id,cmc_comm_type,cml_user_id,cmc_subject,cml_date,cml_send_recv,cmt_staff_id,ups_name_kanji,dlr_short_name,tts_tantousha_name,stf_name"
		. " FROM t_comm_thread"
		. " JOIN t_comm_log ON cml_comm_id=cmt_comm_id"
		. " JOIN t_comm_contents ON cml_contents_id=cmc_contents_id"
		. " JOIN t_user_personal ON cml_user_id=ups_user_id"
		. " JOIN t_user_manage ON umg_user_id=cml_user_id"
		. " JOIN t_dealer ON dlr_dealer_cd=umg_dealer_cd"
		. " JOIN t_tantousha ON tts_tantousha_id=dlr_tantousha_id"
		. " LEFT JOIN t_staff ON stf_staff_id=cmt_staff_id"
		. " $where $order_by $limit";
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
		top.main.user.location.href = "user_info.php?user_id=" + user_id;
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
	thread_sel("");
	disp_user_info(user_id);
<? if ($contents) { ?>
	parent.contents.location.href = "contents.php?comm_id=" + comm_id + "&all=1";
<? } else { ?>
	location.href = "show.php?comm_id=" + comm_id;
<? } ?>
}
function onclick_taiouchuu(comm_id) {
	if (confirm("���Υ���åɤ��б�����ᤷ�ޤ���������Ǥ�����"))
		location.href = "status.php?comm_id=" + comm_id + "&status=1&url=list3.php?frame=<?=$_GET['frame']?>";
}
function open_thread() {
	var f = document.form1;
	f.scroll.value = document.body.scrollTop;
	f.submit();
}
function scrool_top() {
<? if ($_GET['scroll']) { ?>
	var f = document.form1;
	scrollTo(0, <?=$_GET['scroll']?>);
<? } ?>
}
//-->
</script>
<? thread_script('', $contents) ?>
<? list_script() ?>
</head>
<body onload="scrool_top()">

<? center_header('OneToOne�᡼��') ?>

<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���᡼���б�����</td>
		<td style="color:navy;font-size:85%" align="center">
			<nobr><input type="checkbox" name="contents" onclick="check_contents(checked)" <?=value_checked('1', $contents)?>>�ʰ�����ɽ��</nobr>
		</td>
		<td class="lb" style="color:navy;font-size:85%">
			<input type="radio" onclick="change_mode('list1.php')">̤�б�
			<input type="radio" onclick="change_mode('list2.php')">�б���
			<input type="radio" checked>�б�����
			<input type="radio" onclick="change_mode('list4.php')">̵ȿ��
			<input type="radio" onclick="change_mode('list5.php')">�����
		</td>
	</tr>
</table>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>��̾<input type="text" name="subject" <?=value($subject)?>><input type="submit" value="����">&nbsp</nobr>
			<nobr>�桼��̾/Mail<input class="alpha" type="text" name="name_mail" <?=value($name_mail)?>><input type="submit" value="����">&nbsp</nobr>
			<nobr>���ڥ졼��<select name="staff_id" onchange="submit()"><? select_staff('- ���� -', $staff_id)?></select>&nbsp</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="frame" <?=value($_GET['frame'])?>>
<input type="hidden" name="sel_id">
<input type="hidden" name="scroll" value="0">

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(0, '���ڎ��Ď�');
		sort_header(0, '�б�');
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
	$comm_id = $fetch->cml_comm_id;
	$user_id = $fetch->cml_user_id;

	if ($contents)
		$title = '����å����Τ����Ƥ�ɽ�����ޤ�';
	else
		$title = '�᡼������Ƥ�ɽ�����ޤ�';
?>
	<tr class="tc0">
		<td align="center"><input type="checkbox" name="open[]" value="<?=$comm_id?>" <?=checked(open_checked($comm_id))?> style="height:15" onclick="open_thread()"></td>
		<td align="center"><input type="button" value="�б���" style="height:20" onclick="onclick_taiouchuu(<?=$comm_id?>)"></td>
		<td align="center"><?=format_datetime($fetch->cml_date)?></td>
		<td><?=decode_comm_type2($fetch->cmc_comm_type)?><?=decode_send_recv($fetch->cml_send_recv)?></td>
		<td align="center"><?=$comm_id?></td>
		<td><a href="javascript:onclick_subject(<?=$comm_id?>,<?=$user_id?>)" title="<?=$title?>"><?=$fetch->cmc_subject != '' ? htmlspecialchars($fetch->cmc_subject) : '�ʷ�̵̾����'?></a></td>
		<td><?=htmlspecialchars($fetch->ups_name_kanji)?></td>
		<td><?=htmlspecialchars($fetch->dlr_short_name)?></td>
		<td><?=htmlspecialchars($fetch->tts_tantousha_name)?></td>
		<td><?=htmlspecialchars($fetch->stf_name)?></td>
	</tr>
<?
	if (open_checked($comm_id)) {
?>
	<tr>
		<td colspan=9><pre><? output_thread($fetch->cml_comm_id) ?></pre></td>
	</tr>
<?
	}
}
?>
</table>
</form>

<? center_footer() ?>

</body>
</html>
