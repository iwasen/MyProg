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
set_global('member', '会員情報管理', '会員データ入力状況', BACK_TOP);

$fetch = get_system_info('sy_batch_month');
$month = $fetch->sy_batch_month;

$sql = "select count(*) from t_base_data where bd_auto_commit=3 and bd_month=" . sql_date($month);
$commit = db_fetch1($sql);

$order_by = order_by(6, 1, 'mb_id', 'mb_name1 || mb_name2', 'mb_mail_addr','mb_zip','mb_area_cd','mb_regist_date', 'bd_auto_commit');
$sql = "select mb_seq_no,mb_id,mb_name1,mb_name2,mb_mail_addr,mb_zip,mb_area_cd,mb_address,mb_regist_date, bd_auto_commit"
	. " from t_member left join t_base_data on mb_seq_no=bd_mb_seq_no and bd_month=" . sql_date($month) . " where mb_step=3 $order_by";
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
</head>
<body>
<? page_header() ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■本登録会員一覧　<font size=-1 color="blue">（総数:&nbsp;<?=$nrow?>名/当月完了者:&nbsp;<?=$commit?>名）</font></td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='../../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'ID');
		sort_header(2, '氏名');
		sort_header(3, 'メールアドレス');
		sort_header(4, '郵便番号');
		sort_header(5, '住所');
		sort_header(6, '登録日時');
		sort_header(7, '完了');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="detail.php?seq_no=<?=$fetch->mb_seq_no?>" title="入力状況を表示します"><?=htmlspecialchars($fetch->mb_id)?></a></td>
		<td><?=htmlspecialchars("$fetch->mb_name1 $fetch->mb_name2")?></td>
		<td><a href='mailto:<?=$fetch->mb_mail_addr?>' title="会員にメールします"><?=htmlspecialchars($fetch->mb_mail_addr)?></a></td>
		<td><?=substr($fetch->mb_zip,0,3)?>-<?=substr($fetch->mb_zip,3,4)?></td>
		<td><?=htmlspecialchars(decode_area($fetch->mb_area_cd))?></td>
		<td align="center"><?=htmlspecialchars(format_datetime($fetch->mb_regist_date))?></td>
		<td align="center"><?=$fetch->bd_auto_commit==3?'*':''?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
