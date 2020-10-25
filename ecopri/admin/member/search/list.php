<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員数検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/list.php");
include("$inc/decode.php");
include("$inc/format.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_search']))
	system_error("セッションが不正", __FILE__);
$search = &$_SESSION['ss_search'];

if ($search->age_from || $search->age_to)
	$item = ",'mb_birthday'";
	$select = ",AGE(mb_birthday)";

//メイン処理
set_global('member', '会員情報管理', '会員数検索', BACK_CLOSE);

$order_by = order_by(6, 1, 'mb_id', 'mb_name1 || mb_name2', 'mb_mail_addr','mb_zip','mb_area_cd','mb_regist_date');
$sql = "SELECT mb_seq_no,mb_id,mb_name1,mb_name2,mb_mail_addr,mb_zip,mb_area_cd,mb_regist_date FROM t_member WHERE $where $order_by";
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

function open_detail(seq_no) {
	window.open("../../inc/detail.php?seq_no=" + seq_no, "_blank", "width=450,scrollbars=yes,resizable=yes,status=no,menubar=no,toolbar=no");
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
		<td class="lt">■該当会員一覧　<font size=-1 color="blue">（総数:&nbsp;<?=$nrow?>名）</font></td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="where" <?=value($where)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, 'ID');
		sort_header(2, '氏名');
		sort_header(3, 'メールアドレス');
		sort_header(4, '郵便番号');
		sort_header(5, '住所');
		sort_header(6, '登録日時');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="javascript:open_detail(<?=$fetch->mb_seq_no?>)" title="会員の登録情報を表示します"><?=htmlspecialchars($fetch->mb_id)?></a></td>
		<td><?=htmlspecialchars("$fetch->mb_name1 $fetch->mb_name2")?></td>
		<td><?=htmlspecialchars($fetch->mb_mail_addr)?></td>
		<td><?=substr($fetch->mb_zip,0,3)?>-<?=substr($fetch->mb_zip,3,4)?></td>
		<td><?=htmlspecialchars(decode_area($fetch->mb_area_cd)).$fetch->mb_address?></td>
		<td align="center"><?=htmlspecialchars(format_datetime($fetch->mb_regist_date))?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
