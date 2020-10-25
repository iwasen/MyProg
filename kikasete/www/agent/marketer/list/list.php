<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:担当マーケター一覧リスト表示
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
include("$inc/list.php");
include('list_func.php');

//メイン処理

set_global('担当マーケター一覧', BACK_TOP);

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
function mr_detail(mr_id, top) {
  var win;
  win = window.open(top + "/sub/mr_detail.php?mr_id=" + mr_id, "mr_detail", "scrollbars=yes,resizable=yes,width=500,height=510");
  win.focus();
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<? marketer_list($top, $sort_col, $sort_dir); ?>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
