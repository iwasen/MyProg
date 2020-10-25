<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニタープロファイル表示処理
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
include("$inc/format.php");

// 集計日時表示
function profile_date() {
	$sql = "SELECT pd_date FROM t_profile_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow != 0) {
		$fetch = pg_fetch_object($result, 0);
		echo format_datetime($fetch->pd_date);
	}
}

// 全体表示
function list_all($profile_id) {
	table_header('全体');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw"
			. " FROM t_profile_data"
			. " WHERE pf_profile_id=$profile_id ORDER BY pf_code";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow != 0) {
		$fetch = pg_fetch_object($result, 0);
		table_data(0, '全体', $fetch);
	}

	table_footer();
}

// 年代別表示
function list_age($profile_id) {
	table_header('年代別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw"
			. " FROM t_profile_data"
			. " WHERE pf_profile_id=$profile_id ORDER BY pf_code";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$text = $fetch->pf_code . '代';
		switch ($fetch->pf_code) {
		case 10:
			$text .= '以下';
			break;
		case 60:
			$text .= '以上';
			break;
		}

		table_data($i, $text, $fetch);
	}

	table_footer();
}

// 未既婚別表示
function list_mikikon($profile_id) {
	table_header('未既婚別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw"
			. " FROM t_profile_data"
			. " WHERE pf_profile_id=$profile_id ORDER BY pf_code";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, decode_mikikon($fetch->pf_code), $fetch);
	}

	table_footer();
}

// 居住地域別表示
function list_area($profile_id) {
	table_header('居住地域別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw,ar_area_name"
			. " FROM t_profile_data JOIN m_area ON pf_code=ar_area_cd"
			. " WHERE pf_profile_id=$profile_id ORDER BY pf_code";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, $fetch->ar_area_name, $fetch);
	}

	table_footer();
}

// 職業別表示
function list_shokugyou($profile_id) {
	table_header('職業別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw,sg_shokugyou_name"
			. " FROM t_profile_data JOIN m_shokugyou ON pf_code=sg_shokugyou_cd"
			. " WHERE pf_profile_id=$profile_id ORDER BY sg_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, $fetch->sg_shokugyou_name, $fetch);
	}

	table_footer();
}

// 業種別表示
function list_gyoushu($profile_id) {
	table_header('業種別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw,gs_gyoushu_name"
			. " FROM t_profile_data JOIN m_gyoushu ON pf_code=gs_gyoushu_cd"
			. " WHERE pf_profile_id=$profile_id AND gs_type_cd=1 ORDER BY gs_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, $fetch->gs_gyoushu_name, $fetch);
	}

	table_footer();
}

// 職種別表示
function list_shokushu($profile_id) {
	table_header('職種別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw,ss_shokushu_name"
			. " FROM t_profile_data JOIN m_shokushu ON pf_code=ss_shokushu_cd"
			. " WHERE pf_profile_id=$profile_id ORDER BY ss_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, $fetch->ss_shokushu_name, $fetch);
	}

	table_footer();
}

// 子供の有無表示
function list_have_child($profile_id) {
	table_header('子供の有無別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw"
			. " FROM t_profile_data"
			. " WHERE pf_profile_id=$profile_id ORDER BY pf_code";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, decode_have_child($fetch->pf_code), $fetch);
	}

	table_footer();
}

// 住居形態表示
function list_housing_form($profile_id) {
	table_header('住居形態別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw"
			. " FROM t_profile_data"
			. " WHERE pf_profile_id=$profile_id ORDER BY pf_code";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, decode_housing_form($fetch->pf_code), $fetch);
	}

	table_footer();
}

// 車の所有表示
function list_have_car($profile_id) {
	table_header('車の所有別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw"
			. " FROM t_profile_data"
			. " WHERE pf_profile_id=$profile_id ORDER BY pf_code";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, decode_have_car($fetch->pf_code), $fetch);
	}

	table_footer();
}

// よく行くコンビニ別表示
function list_conveni($profile_id) {
	table_header('よく行くコンビニ別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw,cv_name"
			. " FROM t_profile_data JOIN m_conveni ON pf_code=cv_conveni_cd"
			. " WHERE pf_profile_id=$profile_id ORDER BY cv_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, $fetch->cv_name, $fetch);
	}

	table_footer();
}

// よく行くスーパー別表示
function list_super($profile_id) {
	table_header('よく行くスーパー別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw,sp_name"
			. " FROM t_profile_data JOIN m_super ON pf_code=sp_super_cd"
			. " WHERE pf_profile_id=$profile_id ORDER BY sp_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, $fetch->sp_name, $fetch);
	}

	table_footer();
}

// 興味ジャンル別表示
function list_genre($profile_id) {
	table_header('興味ジャンル別');

	$sql = "SELECT pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw,kg_name"
			. " FROM t_profile_data JOIN m_genre ON pf_code=kg_genre_cd"
			. " WHERE pf_profile_id=$profile_id ORDER BY kg_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		table_data($i, $fetch->kg_name, $fetch);
	}

	table_footer();
}

function table_header($title) {
?>
<br>
<table border=0 cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td bgcolor="#4682B4" style="color=#ffffff;font-weight:bolder">&nbsp;<?=$title?></td>
	</tr>
	<tr>
		<td>
			<table <?=LIST_TABLE?> width="100%">
				<tr>
					<th rowspan=2 bgcolor="#eeeeee"></th>
					<th colspan=3 bgcolor="#eeffdd">モニター</th>
					<th colspan=3 bgcolor="#ffffdd">リサーチパートナー</th>
					<th rowspan=2 bgcolor="#eeeeee">合計</th>
				</tr>
				<tr>
					<th bgcolor="#ddddff">男</th>
					<th bgcolor="#ffdddd">女</th>
					<th bgcolor="#ddffff">計</th>
					<th bgcolor="#ddddff">男</th>
					<th bgcolor="#ffdddd">女</th>
					<th bgcolor="#ddffff">計</th>
				</tr>
<?
}

function table_data($i, $text, $fetch) {
	$monitor = $fetch->pf_data_mm + $fetch->pf_data_mw;
	$partner = $fetch->pf_data_pm + $fetch->pf_data_pw;
	$total = $monitor + $partner;

	echo "<tr class='tc", $i % 2, "'>\n";
	echo "<td width='20%'>$text</td>\n";
	echo "<td align='right' width='10%'>", number_format($fetch->pf_data_mm), "</td>\n";
	echo "<td align='right' width='10%'>", number_format($fetch->pf_data_mw), "</td>\n";
	echo "<td align='right' width='10%'>", number_format($monitor), "</td>\n";
	echo "<td align='right' width='10%'>", number_format($fetch->pf_data_pm), "</td>\n";
	echo "<td align='right' width='10%'>", number_format($fetch->pf_data_pw), "</td>\n";
	echo "<td align='right' width='10%'>", number_format($partner), "</td>\n";
	echo "<td align='right' width='10%'>", number_format($total), "</td>\n";
	echo "</tr>\n";
}

function table_footer() {
?>
			</table>
		</td>
	</tr>
</table>
<?
}

set_global('monitor', 'モニター管理', 'モニタープロファイル', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onClick_manual(f) {
	if (confirm("手動集計を実行します。しばらく時間がかかりますが、よろしいですか？"))
		location.href = "sum.php";
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■集計日時 <?profile_date()?></td>
		<td class="lb">
			<select name="monitor_type" onchange="document.form1.submit()">
				<option <?=value_selected(0, $monitor_type)?>>全モニター</option>
				<option <?=value_selected(1, $monitor_type)?>>アクティブモニター</option>
				<option <?=value_selected(2, $monitor_type)?>>はいめーる</option>
			</select>
			<input type="button" value="手動集計" onclick="onClick_manual()">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
</table>
</form>
<?
$offset = $monitor_type * 100;

list_all(0 + $offset);
list_age(1 + $offset);
list_mikikon(2 + $offset);
list_area(3 + $offset);
list_shokugyou(4 + $offset);
list_gyoushu(5 + $offset);
list_shokushu(6 + $offset);
list_have_child(11 + $offset);
list_housing_form(12 + $offset);
list_have_car(13 + $offset);
list_conveni(7 + $offset);
list_super(8 + $offset);
list_genre(9 + $offset);
?>
<? page_footer() ?>
</body>
</html>
