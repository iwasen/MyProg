<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員データ入力状況一覧
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
include("$inc/decode.php");
include("$inc/format.php");

//メイン処理
set_global('member', '会員情報管理', '会員データ入力状況', '');

$sql = "select * from t_member left join l_member_login on mb_seq_no=ml_member_id where mb_seq_no=$seq_no order by ml_date desc";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
	$name = "$fetch->mb_name1 $fetch->mb_name2";
}
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
</head>
<body>
<? page_header() ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<b><?="$fetch->mb_name1 $fetch->mb_name2"?></b> 様のログイン状況</td>
		<td class="lb">
			<input type="button" value="　閉じる　" onclick="window.close()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="200">
	<tr bgcolor="#FFDAB9">
		<td align="center">日時</td>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	if ($fetch->ml_member_id) {
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->ml_date)?></td>
	</tr>
<?
	}
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
