<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メンバーリスト
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");

//メイン処理
set_global2('project', 'プロジェクト管理｜メンバーリスト', 'メンバー一覧', BACK_TOP);

$sql = "SELECT mg_group_name FROM t_member_grp WHERE mg_group_id=$group_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$group_name = $fetch->mg_group_name;
} else
	redirect('list.php');

$order_by = order_by(1, 0, 'mn_monitor_id', 'mn_mail_addr', 'mn_name1_kana||mn_name2_kana');

$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2"
		. " FROM t_member_list JOIN t_monitor ON mn_monitor_id=ml_monitor_id"
		. " WHERE ml_group_id=$group_id $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function delete_member() {
	if (confirm("選択したメンバーをこのリストから削除します。よろしいですか？"))
		document.form2.submit();
}
//-->
</script>
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($group_name)?></td>
		<td class="lb">
			<input type="button" value="　追加　" onclick="location.href='m_new.php?group_id=<?=$group_id?>'">
			<input type="button" value="　削除　" onclick="delete_member()">
			<input type="button" value="　戻る　" onclick="location.href='list.php'">
		</td>
	</tr>
</table>
</form>

<form method="post" name="form2" action="delete.php">
<input type="hidden" name="group_id" <?=value($group_id)?>>
<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr class="tch">
<?
		sort_header(1, 'モニターID');
		sort_header(2, 'メールアドレス');
		sort_header(3, '名前');
		sort_header(0, '削除');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->mn_monitor_id?></td>
		<td><a href="<?=$top?>/common/monitor_info.php?monitor_id=<?=$fetch->mn_monitor_id?>" title="モニター登録情報を表示します" target="_blank"><?=htmlspecialchars($fetch->mn_mail_addr)?></a></td>
		<td><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
		<td align="center"><input type="checkbox" name="delete[]" value="<?=$fetch->mn_monitor_id?>"></td>
	</tr>
<?
}
?>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
