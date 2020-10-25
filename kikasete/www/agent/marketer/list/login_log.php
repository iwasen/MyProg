<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:メッセー表示・履歴の閲覧・削除
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/list.php");
include('list_func.php');

//状態表示
function decode_status($code) {
	if ($code != "") {
		return '成功';
	} else {
		return '<font color="red">失敗</font>';
	}
}

//メイン処理

set_global('マーケターのログイン履歴', BACK_TOP, 1);

$sql = "SELECT mr_name1, mr_name2 FROM t_marketer WHERE mr_marketer_id=$id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mr_name1 = $fetch->mr_name1;
	$mr_name2 = $fetch->mr_name2;
} else {
	redirect("$top/mypage.php");
}

// 表示行数条件付加
$limit = disp_limit(20);

$order_by = order_by(1, 1, 'lg_date','lg_login_id','lg_ip_addr');
$sql = "SELECT lg_login_id,lg_date,lg_ip_addr FROM l_login"
	. " WHERE lg_member_kind=2 AND lg_member_id=$id $order_by $limit";
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
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="95%">
	<tr>
		<td>
			<table width=700>
				<tr>
					<td>■<?=htmlspecialchars("$mr_name1 $mr_name2")?> さんのログイン履歴</td>
					<td align="right"><input type="button" value="　戻る　" onclick="history.back()"></td>
				</tr>
				<tr>
					<td colspan=2 class="lc"><nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select>
						<input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
					</td>
				</tr>
			</table>
			<input type="hidden" name="sort_col" <?=value($sort_col)?>>
			<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
			<input type="hidden" name="page" value=0>
			<input type="hidden" name="id" <?=value($id)?>>
			<table <?=LIST_TABLE?> width=700>
				<tr class="tch">
			<?
					sort_header(1, 'ログイン日時');
					sort_header(2, 'ログインID');
					sort_header(3, 'IPアドレス');
			?>
				</tr>
			<?
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
			?>
				<tr class="tc<?=$i % 2?>">
					<td align="center"><?=format_datetime($fetch->lg_date)?></td>
					<td align="left"><?=htmlspecialchars($fetch->lg_login_id)?></td>
					<td align="center"><?=$fetch->lg_ip_addr?></td>
				</tr>
			<?
			}
			?>
			</table>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
