<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��ã�᡼�����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/point.php");
include("$inc/resign.php");

//�ᥤ�����
set_global('monitor', '��˥�������', '��ã�᡼�����', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'monitor_undeliver', 'mail_addr', 'displine', 'sort_col', 'sort_dir', 'page');

// where���
$where = 'mn_status=2';
if ($mail_addr != '')
	and_join($where, "mn_mail_addr LIKE '%$mail_addr%'");

if ($where != '')
	$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(1, 0, 'mn_mail_addr', 'mn_name1_kana||mn_name2_kana', 'mn_regist_date', 'mn_type', 'er_err_count');

// ɽ���Կ����
$limit = disp_limit();
//jeon_start seq=ad2
if($next_action=='delete_all'){
	$sql = "SELECT mn_monitor_id FROM t_monitor WHERE mn_status=2 AND now()-COALESCE(mn_update_date,mn_regist_date)>=interval '182 days'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		// ������
		resign_monitor($fetch->mn_monitor_id);
	}

    $sql = "DELETE FROM t_err_mail WHERE er_member_id IN(SELECT mn_monitor_id from t_monitor WHERE mn_status=9) AND er_member_kind = 1";
	db_exec($sql);
}
//jeon_end seq=ad2

$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2,mn_regist_date,mn_type,er_err_count"
		. " FROM t_err_mail JOIN t_monitor ON er_member_kind=1 AND er_member_id=mn_monitor_id"
		. " $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
<!--jeon_start seq=ad2-->
function onSubmit_form1(f){
	switch (f.next_action.value) {
	case "delete_all":
	return confirm("��ã�ե饰�Υ�˥������������ե饰���ѹ����ޤ�����������Ǥ�����");
	}
	return false;
}
<!--jeon_end seq=ad2-->
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���᡼����ã��˥�������</td>
		<td class="lb">
		<!--jeon_start seq=ad2-->
			<input type="hidden" name="next_action">
			<input type="submit" value=" ��ã�ե饰��������" onclick="document.form1.next_action.value='delete_all'">
		<!--jeon_end seq=ad2-->		
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr>�᡼�륢�ɥ쥹<input type="text" name="mail_addr" <?=value($mail_addr)?>>��</nobr>
			<input type="button" value="����" onclick="submit()">
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
		sort_header(1, '�᡼�륢�ɥ쥹');
		sort_header(2, '̾��');
		sort_header(3, '��Ͽ����');
		sort_header(4, '����');
		sort_header(5, '��ã���');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?monitor_id=<?=$fetch->mn_monitor_id?>" title="�᡼�륢�ɥ쥹�ѹ��ޤ�����������Ԥ��ޤ�"><?=htmlspecialchars($fetch->mn_mail_addr)?></a></td>
		<td><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
		<td align="center"><?=format_date($fetch->mn_regist_date)?></td>
		<td align="center"><?=decode_monitor_type($fetch->mn_type)?></td>
		<td align="right"><?=number_format($fetch->er_err_count)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
