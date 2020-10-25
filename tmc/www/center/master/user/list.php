<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ޥ������ƥʥ󥹡��桼�������ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/select.php");

// ���å������Ͽ
get_session_vars($_GET['pset'], 'master_user', 'user_id', 'name', 'date', 'status', 'displine', 'sort_col', 'sort_dir', 'page');

// �ᥤ�����
$where = '';

// �桼��ID����ղ�
if ($user_id != '') {
	if (is_numeric($user_id))
		and_join($where, "ups_user_id=$user_id");
	else
		and_join($where, "ups_mail_addr LIKE '%$user_id%'");
}

// ̾������ղ�
if ($name != '')
	and_join($where, "(ups_name_kanji LIKE '%$name%' OR ups_name_kana LIKE '%$name%')");

// ���վ���ղ�
if ($date != '')
	and_join($where, "date_trunc('month', urg_regist_date)='$date/1'");

// ���־���ղ�
if ($status != '')
	and_join($where, "ust_status=$status");
else
	and_join($where, "ust_status<>9");

if ($where)
	$where = "WHERE $where";

// ɽ���Կ�����ղ�
$limit = disp_limit();

// �����Ⱦ��
$order_by = order_by(1, 0, 'ups_user_id', 'ups_name_kana', 'ups_mail_addr', 'urg_regist_date', 'ust_status');

$sql = "SELECT ups_user_id,ups_mail_addr,ups_name_kanji,urg_regist_date,ust_status"
		. " FROM t_user_personal"
		. " JOIN t_user_status ON ust_user_id=ups_user_id"
		. " JOIN t_user_regist ON urg_user_id=ups_user_id"
		. " $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡å桼������') ?>

<div align="center">
<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����Ͽ�桼������</td>
		<td class="lb">
			<input type="button" value=" ��� " onclick="location.href='../menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>�桼��ID/Adr<input class="alpha" type="text" name="user_id" <?=value($user_id)?>><input type="submit" value="����"></nobr>
			<nobr>̾��<input class="kanji" type="text" name="name" size=12 <?=value($name)?>><input type="submit" value="����"></nobr>
			<nobr>��Ͽ��<select name="date" onchange="submit()"><? select_ym('- ���� -', $date)?></select></nobr>
			<nobr>����<select name="status" onchange="submit()"><? select_user_status('��λ�ʳ�����', $status)?></select>
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><? select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1, '�桼��ID');
sort_header(2, '̾��');
sort_header(3, '�᡼�륢�ɥ쥹');
sort_header(4, '��Ͽ��');
sort_header(5, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?user_id=<?=$fetch->ups_user_id?>" title="�桼�������ɽ�����ѹ����ޤ�"><?=$fetch->ups_user_id?></a></td>
		<td><?=htmlspecialchars($fetch->ups_name_kanji)?></td>
		<td><?=htmlspecialchars($fetch->ups_mail_addr)?></td>
		<td align="center"><?=format_date($fetch->urg_regist_date)?></td>
		<td align="center"><?=decode_user_status($fetch->ust_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
