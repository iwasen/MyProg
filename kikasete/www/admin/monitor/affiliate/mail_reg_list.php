<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アフィリエイト登録者一覧（メール）
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// モニター状態選択肢
function select_monitor_status2($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('0', $selected), ">有効</option>";
	echo "<option ", value_selected('2', $selected), ">不達</option>";
	echo "<option ", value_selected('9', $selected), ">退会</option>";
}

// アフィリエイト状態
function decode_affiliate_status($code) {
	switch ($code) {
	case 0:
		return '-';
	case 1:
		return '有効';
	case 2:
		return '無効';
	case 3:
		return '差戻し';
	}
}

//メイン処理
set_global('monitor', 'モニター管理', 'アフィリエイト登録者一覧', BACK_TOP);

// セッション登録
get_session_vars($pset, 'affiliate_reg', 'search', 'status', 'month', 'displine', 'sort_col', 'sort_dir', 'page');

// 本登録総数取得
if ($affiliate_id)
	$sql = "SELECT afc_hon_count FROM t_affiliate_cnt WHERE afc_affiliate_id=$affiliate_id";
else
	$sql = $sql = "SELECT afm_regist_count FROM t_affiliate_mgr WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
$reg_sum = db_fetch1($sql);

// 戻り先
if ($affiliate_id) {
	$sql = "SELECT af_parent_affiliate_id FROM t_affiliate WHERE af_affiliate_id=$affiliate_id";
	$parent_affiliate_id = db_fetch1($sql);
	if ($parent_affiliate_id)
		$ret_url = "mail_reg_list.php?affiliate_id=$parent_affiliate_id";
	else
		$ret_url = 'mail_list.php';
} else
	$ret_url = 'list.php';

// where条件
if ($affiliate_id)
	and_join($where, "afr_affiliate_id=$affiliate_id");
else
	and_join($where, "afr_affiliate_id IN (SELECT af_affiliate_id FROM t_affiliate WHERE af_affiliate_mgr_id=$affiliate_mgr_id)");

// 検索テキスト
if ($search != '') {
	if (is_numeric($search))
		and_join($where, "mn_monitor_id=$search");
	else
		and_join($where, "(mn_mail_addr LIKE '%$search%' OR mn_name1 LIKE '%$search%' OR mn_name2 LIKE '%$search%')");
}

if ($status != '')
	and_join($where, "mn_status=$status");

if ($month != '')
	and_join($where, "date_part('month',mn_regist_date)=$month");

$where = "WHERE $where";

// ソート条件
$order_by = order_by(1, 1, 'mn_monitor_id', 'mn_mail_addr', 'mn_name1_kana||mn_name2_kana', 'mn_age', 'mn_jitaku_area', 'mn_regist_date', 'mn_status', 'afr_point', 'afc_mail_count', 'afc_kari_count', 'afc_hon_count', 'af_status');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT afr_affiliate_id,mn_monitor_id,mn_mail_addr,mn_name1,mn_name2,ar_area_name,mn_regist_date,mn_status,afr_point,age(mn_birthday) AS mn_age,af_affiliate_id,af_status,afc_kari_count,afc_hon_count,afc_mail_count"
		. " FROM t_affiliate_reg"
		. " JOIN t_monitor ON mn_monitor_id=afr_monitor_id"
		. " JOIN m_area ON ar_area_cd=mn_jitaku_area"
		. " JOIN t_monitor_point ON mp_monitor_id=afr_monitor_id"
		. " LEFT JOIN t_affiliate ON af_monitor_id=afr_monitor_id AND af_affiliate_mgr_id=$affiliate_mgr_id"
		. " LEFT JOIN t_affiliate_cnt ON afc_affiliate_id=af_affiliate_id"
		. " $where $order_by $limit";
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
function all_set() {
	set_all_check(true);
}
function all_clear() {
	set_all_check(false);
}
function set_all_check(c) {
	var e = document.form2["check[]"];
	if (e) {
		if (e.length) {
			for (var i = 0; i < e.length; i++)
				e[i].checked = c;
		} else
			e.checked = c;
	}
}
function submit_form2() {
	var e = document.form2["check[]"];
	var c = false;
	if (e) {
		if (e.length) {
			for (var i = 0; i < e.length; i++) {
				if (e[i].checked) {
					c = true;
					break;
				}
			}
		} else
			c = e.checked;
	}
	if (!c)
		alert("ポイント削除対象がチェックされていません。");
	else if (confirm("選択したモニターのアフィリエイトポイントを無効にします。よろしいですか？"))
		document.form2.submit();
}
function change_status(affiliate_id, status) {
	if (status == 1) {
		if (confirm("アフィリエイトを無効にします。よろしいですか？"))
			location.href = "status.php?affiliate_id=" + affiliate_id + "&status=2&parent_affiliate_id=<?=$affiliate_id?>";
	} else if (status == 2) {
		if (confirm("アフィリエイトを有効にします。よろしいですか？"))
			location.href = "status.php?affiliate_id=" + affiliate_id + "&status=1&parent_affiliate_id=<?=$affiliate_id?>";
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
		<td class="lt">■アフィリエイト登録モニター一覧</td>
		<td class="lb">
			<nobr>本登録数：<?=number_format($reg_sum)?>　</nobr>
			<input type="button" value="CSV取得）" onclick="location.href='check_csv.php?affiliate_id=<?=$affiliate_id?>'">
			<input type="button" value="全選択" onclick="all_set()">
			<input type="button" value="クリア" onclick="all_clear()">
			<input type="button" value="ポイント失効処理" onclick="submit_form2()">
			<input type="button" value="　戻る　" onclick="location.href='<?=$ret_url?>'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>　</nobr>
			<nobr>検索<input type="text" name="search" <?=value($search)?>><input type="button" value="検索" onclick="submit()">　</nobr>
			<nobr>状態<select name="status" onchange="submit()"><? select_monitor_status2('- 指定なし -', $status)?></select>　</nobr>
			<nobr>月<select name="month" onchange="submit()"><? select_month('- 指定なし -', $month)?></select>　</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<form method="post" name="form2" action="point_del.php" onsubmit="return onsubmit_form2(this)">
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'モニターID');
		sort_header(2, 'メールアドレス');
		sort_header(3, '名前');
		sort_header(4, '年代');
		sort_header(5, '都道府県');
		sort_header(6, '本登録日時');
		sort_header(7, '登録者<br>状態');
		sort_header(8, 'ポイント<br>状態');
		sort_header(0, 'チェック');
		sort_header(9, 'ﾒｰﾙ<br>内容');
		sort_header(10, '仮');
		sort_header(11, '本');
		sort_header(12, '状態');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$monitor_id = $fetch->mn_monitor_id;

	// メール内容のリンク
	$mail_count = number_format($fetch->afc_mail_count);
	if ($fetch->afc_mail_count)
		$mail_count = "<a href='mail_send.php?affiliate_id=$fetch->af_affiliate_id' target='_blank'>$mail_count</a>";

	// 本登録数のリンク
	$hon_count = number_format($fetch->afc_hon_count);
	if ($fetch->afc_hon_count)
		$hon_count = "<a href='reg_list.php?affiliate_id=$fetch->af_affiliate_id'>$hon_count</a>";

	// 状態変更へのリンク
	$status = decode_affiliate_status($fetch->af_status);
	if ($fetch->af_status == 1 || $fetch->af_status == 2)
		$status = "<a href='javascript:change_status($fetch->af_affiliate_id,$fetch->af_status)'>$status</a>";
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="<?=$top?>/common/monitor_info.php?monitor_id=<?=$monitor_id?>" title="モニター登録情報を表示します" target="_blank"><?=$monitor_id?></a></td>
		<td><?=htmlspecialchars($fetch->mn_mail_addr)?></td>
		<td><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
		<td align="right"><?=(int)($fetch->mn_age / 10) * 10?></td>
		<td><?=htmlspecialchars($fetch->ar_area_name)?></td>
		<td align="center"><?=format_datetime($fetch->mn_regist_date, '-')?></td>
		<td align="center"><?=decode_monitor_status($fetch->mn_status)?></td>
		<td align="center"><?=$fetch->afr_point ? '有' : '無'?></td>
		<td align="center">
<?
	if ($fetch->afr_point != 0) {
?>
			<input type="checkbox" name="check[]" <?=value("$fetch->afr_affiliate_id:$monitor_id")?>>
<?
	}
?>
		<td align="right"><?=$mail_count?></td>
		<td align="right"><?=number_format($fetch->afc_kari_count)?></td>
		<td align="right"><?=$hon_count?></td>
		<td align="center"><?=$status?></td>
		</td>
	</tr>
<?
}
?>
</table>
<input type="hidden" name="affiliate_id" <?=value($affiliate_id)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
