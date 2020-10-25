<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ポイント精算
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

// 申請者種類選択
function select_member_kind($sel) {
	echo "<option ", value_selected('', $sel), ">- 指定なし -</option>";
	echo "<option ", value_selected('1', $sel), ">モニター</option>";
	echo "<option ", value_selected('2', $sel), ">マーケター</option>";
}

// 状態選択
function select_seisan_status($sel) {
	echo "<option ", value_selected('', $sel), ">- 指定なし -</option>";
	echo "<option ", value_selected('0', $sel), ">未精算</option>";
	echo "<option ", value_selected('1', $sel), ">精算済</option>";
	echo "<option ", value_selected('2', $sel), ">却下</option>";
}
//jeon_start seq=ad1
function select_shouhin($sel){
	echo "<option ", value_selected('',$sel), ">CSVファイルダウンロード</option>";
	echo "<option ", value_selected('1',$sel), ">VISAギフト券</option>";
	echo "<option ", value_selected('2',$sel), ">図書カード</option>";
	echo "<option ", value_selected('4',$sel), ">楽天銀行</option>";
	echo "<option ", value_selected('3',$sel), ">Web Money</option>";
	echo "<option ", value_selected('5',$sel), ">義援金</option>";
}
//jeon_end seq=ad1

// 申請者種類表示
function decode_member_kind($code) {
	switch ($code) {
	case 1:
		return 'モニター';
	case 2:
		return 'マーケター';
	}
}

// 状態表示
function decode_seisan_status($code) {
	switch ($code) {
	case 0:
		return '未精算';
	case 1:
		return '精算済';
	case 2:
		return '却下';
	}
}

//メイン処理
set_global('etc', 'その他管理', 'ポイント精算', BACK_TOP);

// セッション登録
get_session_vars($pset, 'point', 'displine', 'sort_col', 'sort_dir', 'page', 'member_kind', 'status=0');

// 検索条件

if ($member_kind != '')
	and_join($where, "si_member_kind=$member_kind");

if (!isset($status))
	$status = '0';
if ($status != '')
	and_join($where, "si_status=$status");

//jeon_start seq=ad1
if($shouhin != '')
	and_join($where, "si_item=$shouhin");

//if($year != '' && $start_month !='' && $end_month !=''){	
	$start = sql_date2($year,$start_month,21);	
	$end = sql_date2($end_year,$end_month,21);
	if ($start=='null'){
		$start = sql_date2(1800,01,21);
	}
	if ($end=='null'){
		$end = 'now()';
	}
	and_join($where, "si_req_date between $start and $end");
//}
//jeon_end seq=ad1  
if ($where != '')
	$where = "WHERE $where";

// ソート条件
$order_by = order_by(1, 1, 'si_seq_no', 'mn_monitor_id', 'si_name1||si_name2', 'si_addr', 'si_zip', 'si_tel', 'si_req_date', 'si_point', 'si_item', 'si_status');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT mn_monitor_id,mn_name1,mn_name2,si_member_id,si_seq_no,si_name1,si_name2,si_addr,si_zip,si_tel,si_req_date,si_point,si_item,si_status"
		. " FROM t_seisan JOIN t_monitor ON si_member_id=mn_monitor_id $where $order_by $limit";
	
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
function csv_download() {
	if (check_select()) {
		if (confirm("CSVファイルをダウンロードします。よろしいですか？")) {
			var f = document.form2;
			f.action = "csv.php";
			f.submit();
		}
	} else
		alert("データが選択されていません。");
}
function seisan() {
	if (check_select()) {
		if (confirm("選択した精算申請を処理済にします。よろしいですか？")) {
			var f = document.form2;
			f.action = "update.php";
			f.submit();
		}
	} else
		alert("データが選択されていません。");
}
function kyakka() {
	if (check_select()) {
		if (confirm("選択した精算申請を却下します。よろしいですか？")) {
			var f = document.form2;
			f.action = "kyakka.php";
			f.submit();
		}
	} else
		alert("データが選択されていません。");
}
function check_select() {
	var f = document.form2;
	var ok = false;
	if (f["seq_no[]"]) {
		if (f["seq_no[]"].length) {
			for (var i = 0; i < f["seq_no[]"].length; i++) {
				if (f["seq_no[]"][i].checked) {
					ok = true;
					break;
				}
			}
		} else {
			if (f["seq_no[]"].checked)
				ok = true;
		}
	}
	return ok;
}
function all_select(flag) {
	var f = document.form2;
	if (f["seq_no[]"]) {
		if (f["seq_no[]"].length) {
			for (var i = 0; i < f["seq_no[]"].length; i++)
				f["seq_no[]"][i].checked = flag;
		} else
			f["seq_no[]"].checked = flag;
	}
}
//-->
</script>
</head>
<body>
<? page_header() ?>
<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■精算申請一覧
			<nobr><select name="shouhin" onchange="submit()"><?select_shouhin($shouhin)?></select>
			  申請日<select name="year" onchange="submit()"><?select_year(2002,"----",$year)?></select>年
				  <select name="start_month" onchange="submit()"><?select_month("--",$start_month)?></select>月〜
				  <select name="end_year" onchange="submit()"><?select_year(2002,"----",$end_year)?></select>年
				  <select name="end_month" onchange="submit()"><?select_month("--",$end_month)?></select>月</nobr>						
		</td>
		<td class="lb">
			<input type="button" value="CSVダウンロード" onclick="csv_download()">
			<input type="button" value="却下処理" onclick="kyakka()">
			<input type="button" value="精算処理" onclick="seisan()">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>　</nobr>
			<nobr>申請者種類<select name="member_kind" onchange="submit()"><? select_member_kind($member_kind) ?></select>　</nobr>
			<nobr>状態<select name="status" onchange="submit()"><? select_seisan_status($status) ?></select>　</nobr>
		</td>
		<td class="lc" align="right">
			<input type="button" value="全選択" onclick="all_select(true)">
			<input type="button" value="全解除" onclick="all_select(false)">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<form method="post" name="form2">
<input type="hidden" name="shouhin" value=<?=$shouhin?>>
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '番号');
		sort_header(2, 'モニターID');
		sort_header(3, '申請者名');
		sort_header(5, '郵便番号');
		sort_header(4, '住所');
		sort_header(6, '電話番号');
		sort_header(7, '申請日');
		sort_header(8, '申請ポイント');
		sort_header(9, '交換商品');
		sort_header(10, '状態');
		sort_header(0, 'CHK');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$status = decode_seisan_status($fetch->si_status);
	if ($fetch->si_status == 2)
		$status  = "<a href='cancel.php?seq_no=$fetch->si_seq_no'>$status</a>";

	$seisan = ($fetch->si_status == 0) ? '<input type="checkbox" name="seq_no[]" value="' . $fetch->si_seq_no . '" checked>' : '';
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->si_seq_no?></td>
		<td align="center"><?=$fetch->mn_monitor_id?></td>
		<!--jeon_start seq=ad1-->
		<? if($fetch->si_item=='4' || $fetch->si_item=='3') {?>
		<td><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
		<?}else{?><!--jeon_end seq=ad1-->
		<td><?=htmlspecialchars("$fetch->si_name1 $fetch->si_name2")?></td>
		<?}?>
		<td><?=format_zip("$fetch->si_zip")?></td>
		<td><?=htmlspecialchars("$fetch->si_addr")?></td>
		<td><?=htmlspecialchars("$fetch->si_tel")?></td>
		<td align="center"><?=format_datetime($fetch->si_req_date)?></td>
		<td align="right"><?=number_format($fetch->si_point)?></td>
		<td align="center"><?=decode_seisan_item($fetch->si_item)?></td>
		<td align="center"><?=$status?></td>
		<td align="center"><?=$seisan?></td>
	</tr>
<?
}
?>
</table>
</form>
</div>

<? page_footer() ?>
</body>
</html>
