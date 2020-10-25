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
include("$inc/list.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list_item.php");
include("$inc/check.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_litem']))
	$_SESSION['ss_litem'] = new list_item_class;
$litem = &$_SESSION['ss_litem'];

//メイン処理
set_global('member', '会員情報管理', '会員数検索', BACK_TOP);

// フォームからの情報取得
if ((!$litem->column && !$litem->order && !$litem->sort_header) || $litem->renew_flag)
	$litem->get_form();
$litem->renew_flag = '';

// ヘッダ情報生成
$litem->make_header();

// 抽出
$order_by = $litem->order;
eval ("\$order_by = order_by(1, 1, $order_by);");

// 情報出力の場合は、完了者のみ
if ($litem->inp_y != '')
	$and_where = 'AND bd_auto_commit IS NOT NULL';

// リミット指定
if ($line_num != '')
	$limit = " LIMIT $line_num";
$sql = "SELECT $litem->column FROM t_member {$litem->from_join} WHERE {$litem->where} {$and_where} $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);

$condition = "【検索条件】" . join(' ', explode('・', $litem->condition));

// 入力結果情報ありの場合
if ($litem->inp_y != '')
	$ym_info = "【{$litem->inp_y}年{$litem->inp_m}月の情報】";

// 情報更新日時取得
$sql = "SELECT MAX(lb_date) AS lb_date FROM l_batch WHERE lb_file_name='co2_update.php'";
$lb_date = db_fetch1($sql);
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
	window.open("../../inc/detail.php?seq_no=" + seq_no, "_blank", "width=450,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no");
}

function check_number(s) {
	var okStr = "0123456789 ";
	var ok = true;
	for (var i = 0; i < s.length; i++) {
		if (okStr.indexOf(s.charAt(i)) == -1) {
			ok = false;
			break;
		}
	}
	return ok;
}

function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}

function csv_download() {
		var f1 = document.form1;
		var f2 = document.form2;
		if (f1.csv_filename.value == "") {
			alert("ファイル名を入力してください。");
			f1.csv_filename.focus();
			return false;
		}
		if (!isA(f1.csv_filename.value)) {
			alert("ファイル名は半角英数で入力してください。");
			f1.csv_filename.focus();
			return false;
		}
		if (confirm("CSVファイル( " + f1.csv_filename.value + ".csv )をダウンロードします。よろしいですか？")) {
		f2.action = "csv.php?csv_filename=" + f1.csv_filename.value;
		f2.submit();
	}
}

function line_info() {
	var f1 = document.form1;
	if (!check_number(f1.line_num.value)) {
		alert("表示行数は半角数字で入力して下さい。");
		f1.line_num.value='';
		f1.line_num.focus();
		return false;
	}
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form name="form1" method="post">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■該当会員一覧　<font size=-1 color="blue">（総数:&nbsp;<?=$nrow?>名）&nbsp;</font>
		<font size=-1><input type="text" size=4 maxlength=4 name='line_num' <?=value($line_num)?>>件
		<input type='submit' value="表示" onclick="line_info()">&nbsp;<input type='submit' value='解除' onclick="document.form1.line_num.value=''"></font>
		</td>
		<td align="right">
			<span class="note">csvファイル名</span>
			<input class="alpha" type="text" name="csv_filename" <?=value(date('Ymd'))?>>
			<input type="button" value="CSV出力" onclick="csv_download()">
			<input type="button" value=" 戻る " onclick="javascript:location.href='result.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2><font color="midnightblue" size=-1><?=$condition?></font>&nbsp;<font size=-1><?=$ym_info?></font></td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
$sh = split(',', $litem->sort_header);
$i = 1;
while ($th = array_shift($sh)) {
	sort_header($i, $th);
	$i++;
}
?>
	</tr>
<?
// データ出力
$litem->make_data($result, $nrow, $str, $csv_data);
echo $str;
?>
<form name="form2" method="post">
	<input type="hidden" name="sql" <?=value($sql)?>>
</form>
</table>
<table border=0 cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="center"><input type="button" value=" 戻る " onclick="javascript:location.href='result.php'"></td>
	</tr>
	<tr>
		<td align="right"><font color="red" size=-1>最終更新日時：<?=date('Y/m/d H:i:s', sql_time($lb_date))?></font></td>
	</tr>
</table>
</div>

<? page_footer() ?>
</body>
</html>
