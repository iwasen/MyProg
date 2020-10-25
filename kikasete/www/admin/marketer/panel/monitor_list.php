<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:スペシャルパネル管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");

//メイン処理
set_global('myenquete', 'Ｍｙアンケート管理', 'パネルモニター一覧', BACK_TOP);

$sql = sprintf("SELECT pnl_panel_name FROM t_panel WHERE pnl_panel_id=%s", sql_number($_REQUEST['panel_id']));
$panel_name = db_fetch1($sql);

$order_by = order_by(1, 0, 'mn_monitor_id', 'mn_mail_addr', 'mn_name1_kana||mn_name2_kana');

$sql = sprintf("SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2"
		. " FROM t_panel_monitor_list JOIN t_monitor ON mn_monitor_id=pnm_monitor_id"
		. " WHERE mn_status<>9 AND pnm_panel_id=%s $order_by", $_REQUEST['panel_id']);
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
	var f = document.form2;
	var sel = false;
	if (f["delete[]"]) {
		if (f["delete[]"].length) {
			for (var i = 0; i < f["delete[]"].length; i++) {
				if (f["delete[]"][i].checked) {
					sel = true;
					break;
				}
			}
		} else {
			if (f["delete[]"].checked)
				sel = true;
		}
	}
	if (!sel) {
		alert("モニターが選択されていません。");
		return false;
	}
	if (confirm("選択したモニターをこのパネルから削除します。よろしいですか？"))
		document.form2.submit();
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<form method="post" name="form1">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($panel_name)?></td>
		<td class="lb">
			<input type="button" value="　追加　" onclick="location.href='monitor_new.php?category_id=<?=$_REQUEST['category_id']?>&panel_id=<?=$_REQUEST['panel_id']?>'">
			<input type="button" value="　削除　" onclick="delete_member()">
			<input type="button" value="　戻る　" onclick="location.href='panel_list.php?category_id=<?=$_REQUEST['category_id']?>'">
		</td>
	</tr>
</table>
</form>

<form method="post" name="form2" action="monitor_update.php">
<input type="hidden" name="next_action" value="delete">
<input type="hidden" name="category_id" <?=value($_REQUEST['category_id'])?>>
<input type="hidden" name="panel_id" <?=value($_REQUEST['panel_id'])?>>
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

<? page_footer() ?>
</body>
</html>
