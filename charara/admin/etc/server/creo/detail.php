<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:その他管理｜サーバ別応募状況｜クレオ
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/select.php");

// 週選択
function select_week_no($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 9; $i++)
		echo '<option ', value_selected($i, $selected), ">第" . $i . "週</option>\n";
}

// 状態
function decode_df_status($code) {
	switch($code) {
	case 0:
		return '受付済';
		break;
	case 1:
		return '当選';
		break;
	case 2:
		return '当選';
		break;
	case 3:
		return '通知済';
		break;
	default:
		return '−';
	}
}
// キャンペーンコード
$campaign_cd = $cp_cd;

// データテーブル
$table = "d_".$cp_cd;

// セッション登録
get_session_vars($pset, 'creo_list', 'df_week_no', 'displine', 'sort_col', 'sort_dir', 'page');

// where条件
if ($week_no != '')
	and_join($where, "df_week_no=" . $week_no);

if ($where != '') {
	$where = "($where)";
	$where = "WHERE $where";
} else
	$where = '';

// 総件数
$sql = "SELECT count(*) AS count FROM $table $where";
$count = db_fetch1($sql);

//メイン処理
$limit = disp_limit();
set_global('etc', 'その他管理｜サーバ別応募状況', 'クレオ', BACK_TOP);

$order_by = order_by(5, 1, 'df_week_no', 'df_creo_id', 'df_name1||df_name2', 'df_mail_addr', 'df_regist_date', 'df_status');
$sql = "SELECT * FROM $table $where $order_by $limit";
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
		<td class="lt">■応募状況（<?=$cp_cd?>）　<font size=-1 color="blue">（総数:&nbsp;<?=$count?>名）</font></td>
		<td class="lb" align="right">
			<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
			<nobr>週<select name="week_no"><?=select_week_no('全て', $week_no)?></select>&nbsp;</nobr>
			<input type="button" value="検索" onclick="submit()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="cp_cd" <?=value($cp_cd)?>>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '応募');
		sort_header(2, '会員番号');
		sort_header(3, '氏名');
		sort_header(4, 'メールアドレス');
		sort_header(5, '登録日時');
		sort_header(6, '状況');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center">第<?=$fetch->df_week_no?>週</a></td>
		<td align="center"><?=$fetch->df_creo_id?></a></td>
		<td><?="$fetch->df_name1 $fetch->df_name2"?></td>
		<td><a href='mailto:<?=$fetch->df_mail_addr?>' title="会員にメールします"><?=$fetch->df_mail_addr?></a></td>
		<td align="center"><?=format_datetime($fetch->df_regist_date)?></td>
		<td align="center"><?=decode_df_status($fetch->df_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
