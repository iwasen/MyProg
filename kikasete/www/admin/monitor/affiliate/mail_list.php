<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アフィリエイト申請者一覧（メール）
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

define('MAX_INTORO_LEN', 50);

// アフィリエイト状態選択肢
function select_affiliate_status($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('0', $selected), ">申請中</option>";
	echo "<option ", value_selected('1', $selected), ">有効</option>";
	echo "<option ", value_selected('2', $selected), ">無効</option>";
}

// アフィリエイト状態
function decode_affiliate_status($code) {
	switch ($code) {
	case 0:
		return '申請中';
	case 1:
		return '有効';
	case 2:
		return '無効';
	case 3:
		return '差戻し';
	}
}

//メイン処理
set_global('monitor', 'モニター管理', 'アフィリエイト申請者一覧', BACK_TOP);

// セッション登録
get_session_vars($pset, 'affiliate_mem', 'search', 'status', 'month', 'displine', 'sort_col', 'sort_dir', 'page');

// 本登録総数取得
$sql = "SELECT afm_appl_count,afm_regist_count"
		. " FROM t_affiliate_mgr"
		. " WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$shinsei_count = $fetch->afm_appl_count;
	$hon_count = $fetch->afm_regist_count;
}

// 検索テキスト
if ($search != '') {
	if (is_numeric($search))
		and_join($where, "mn_monitor_id=$search");
	else
		and_join($where, "(mn_mail_addr LIKE '%$search%' OR mn_name1 LIKE '%$search%' OR mn_name2 LIKE '%$search%')");
}

// where条件
and_join($where, "af_affiliate_mgr_id=$affiliate_mgr_id AND af_parent_affiliate_id IS NULL AND mn_status<>9");

if ($status != '')
	and_join($where, "af_status=$status");
else
	and_join($where, "af_status<>3");

if ($month != '')
	and_join($where, "date_part('month',af_appl_date)=$month");

$where = "WHERE $where";

// ソート条件
$order_by = order_by(1, 1, 'af_affiliate_id', 'mn_monitor_id', 'mn_name1_kana||mn_name2_kana', 'af_intro_num', 'ans_count', 'mn_jitaku_area', 'mn_age', 'af_appl_date', 'afc_mail_count', 'afc_kari_count', 'afc_hon_count', 'af_status', 'af_child_tree');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT af_affiliate_id,mn_monitor_id,mn_name1,mn_name2,af_intro_num,af_appl_date,afc_mail_count,afc_kari_count,afc_hon_count,af_status,af_child_tree,char_length(translate(mp_enq_history, '0', '')) AS ans_count,ar_area_name,age(mn_birthday) AS mn_age"
		. " FROM t_affiliate"
		. " JOIN t_affiliate_cnt ON afc_affiliate_id=af_affiliate_id"
		. " JOIN t_monitor ON mn_monitor_id=af_monitor_id"
		. " JOIN t_monitor_point ON mp_monitor_id=af_monitor_id"
		. " JOIN m_area ON ar_area_cd=mn_jitaku_area"
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
function open_intro(affiliate_id) {
	window.open("intro.php?affiliate_id=" + affiliate_id, "intro", "width=580,height=600,resizable=yes,scrollbars=yes");
}
function all_set() {
	set_all_check(document.form2["accept[]"], false);
	set_all_check(document.form2["cancel[]"], true);
}
function all_clear() {
	set_all_check(document.form2["accept[]"], false);
	set_all_check(document.form2["cancel[]"], false);
}
function set_all_check(e, c) {
	if (e) {
		if (e.length) {
			for (var i = 0; i < e.length; i++)
				e[i].checked = c;
		} else
			e.checked = c;
	}
}
function submit_form2() {
	var e1 = document.form2["accept[]"];
	var e2 = document.form2["cancel[]"];
	var e3 = null;
	var c = false;
	if (e1) {
		if (e1.length) {
			for (var i = 0; i < e1.length; i++) {
				if (e1[i].checked && e2[i].checked) {
					e3 = e1[i];
					break;
				}
				if (e1[i].checked || e2[i].checked)
					c = true;
			}
		} else {
			if (e1.checked && e2.checked)
				e3 = e1;
			if (e1.checked || e2.checked)
				c = true;
		}
	}
	if (!c)
		alert("承認対象がチェックされていません。");
	else if (e3) {
		alert("有効と無効の両方がチェックされています。");
		e3.focus();
	} else if (confirm("選択したアフィリエイトを承認します。よろしいですか？"))
		document.form2.submit();
}
function change_status(affiliate_id, status) {
	if (status == 1) {
		if (confirm("アフィリエイトを無効にします。よろしいですか？"))
			location.href = "status.php?affiliate_id=" + affiliate_id + "&status=2";
	} else if (status == 2) {
		if (confirm("アフィリエイトを有効にします。よろしいですか？"))
			location.href = "status.php?affiliate_id=" + affiliate_id + "&status=1";
	}
}
function onclick_sashimodoshi() {
	if (confirm("無効の申請者を全て差戻し状態に変更します。よろしいですか？"))
		location.href = "sashimodoshi.php";
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
		<td class="lt">■アフィリエイト一覧</td>
		<td class="lb">
			<nobr>申請者数：<?=number_format($shinsei_count)?>　本登録総数：<?=number_format($hon_count)?>　</nobr>
			<input type="button" value="申請期間設定" onclick="location.href='date_set.php?affiliate_mgr_id=<?=$affiliate_mgr_id?>'">
			<input type="button" value="全選択" onclick="all_set()">
			<input type="button" value="クリア" onclick="all_clear()">
			<input type="button" value="一括差戻し処理" onclick="onclick_sashimodoshi()">
			<input type="button" value="一括承認処理" onclick="submit_form2()">
			<input type="button" value="　戻る　" onclick="location.href='list.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>　</nobr>
			<nobr>検索<input type="text" name="search" <?=value($search)?>><input type="button" value="検索" onclick="submit()">　</nobr>
			<nobr>状態<select name="status" onchange="submit()"><? select_affiliate_status('- 指定なし -', $status)?></select>　</nobr>
			<nobr>月<select name="month" onchange="submit()"><? select_month('- 指定なし -', $month)?></select>　</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<form method="post" name="form2" action="accept.php">
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'ID');
		sort_header(2, 'モニターID');
		sort_header(3, '名前');
		sort_header(4, '予定紹介人数');
		sort_header(5, 'ｱﾝｹｰﾄ<br>回数');
		sort_header(6, '都道府県');
		sort_header(7, '年代');
		sort_header(8, '申請日時');
		sort_header(9, 'ﾒｰﾙ<br>内容');
		sort_header(10, '仮');
		sort_header(11, '本');
		sort_header(12, '状態');
		sort_header(13, '子以下<br>の有無');
		sort_header(0, 'チェック');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$affiliate_id = $fetch->af_affiliate_id;

	// メール内容のリンク
	$mail_count = number_format($fetch->afc_mail_count);
	if ($fetch->afc_mail_count)
		$mail_count = "<a href='mail_send.php?affiliate_id=$affiliate_id' target='_blank'>$mail_count</a>";

	// 本登録数のリンク
	$hon_count = number_format($fetch->afc_hon_count);
	if ($fetch->afc_hon_count)
		$hon_count = "<a href='reg_list.php?affiliate_id=$affiliate_id'>$hon_count</a>";

	// 状態変更へのリンク
	$status = decode_affiliate_status($fetch->af_status);
	if ($fetch->af_status == 1 || $fetch->af_status == 2)
		$status = "<a href='javascript:change_status($affiliate_id,$fetch->af_status)'>$status</a>";

	// 子以下の有無
	switch ($fetch->af_child_tree) {
	case 0:
		$child = '無し';
		break;
	case 1:
		$child = '子';
		break;
	case 2:
		$child = '孫';
		break;
	default:
		$child = '孫以下';
		break;
	}
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$affiliate_id?></td>
		<td align="center"><a href="<?=$top?>/common/monitor_info.php?monitor_id=<?=$fetch->mn_monitor_id?>" title="モニター登録情報を表示します" target="_blank"><?=$fetch->mn_monitor_id?></a></td>
		<td><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
		<td align="right"><?=number_format($fetch->af_intro_num)?></td>
		<td align="right"><?=number_format($fetch->ans_count)?></td>
		<td align="center"><?=$fetch->ar_area_name?></td>
		<td align="right"><?=(int)($fetch->mn_age / 10) * 10?></td>
		<td align="center"><?=format_datetime($fetch->af_appl_date)?></td>
		<td align="right"><?=$mail_count?></td>
		<td align="right"><?=number_format($fetch->afc_kari_count)?></td>
		<td align="right"><?=$hon_count?></td>
		<td align="center"><?=$status?></td>
		<td align="center"><?=$child?></td>
		<td align="center">
<?
	if ($fetch->af_status == 0) {
?>
			<nobr><input type="checkbox" name="accept[]" <?=value($affiliate_id)?>>有効</nobr>
			<nobr><input type="checkbox" name="cancel[]" <?=value($affiliate_id)?>>無効</nobr>
<?
	}
?>
		</td>
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
