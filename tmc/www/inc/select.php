<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:選択肢表示関数
'******************************************************/

define('DEFAULT_SELECT', '- 選択してください -');

// <option>タグ出力
function option_tag($value, $selected, $display) {
	echo '<option ', value_selected($value, $selected), '>', $display, '</option>', "\n";
}

// 年
function select_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = $start_year; $i < $start_year + 5; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// 月
function select_month($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 日
function select_day($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 31; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 時間
function select_hour($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i < 24; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// 年月
function select_ym($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$y = Date('Y');
	$m = Date('m');
	while ($y >= 2003) {
		$ym = $y . "/" . substr("0$m", -2);
		echo '<option ', value_selected($ym, $selected), ">$ym</option>\n";

		if (--$m == 0) {
			$m = 12;
			$y--;
		}
	}
}

// スタッフ
function select_staff($default, $selected, $cond = '') {
	if ($default)
		echo "<option value=''>$default</option>\n";

	if ($cond != '')
		$cond = " AND " . $cond;

	$sql = "SELECT stf_staff_id,stf_name FROM t_staff WHERE stf_status=0 $cond ORDER BY stf_staff_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->stf_staff_id, $selected), ">", htmlspecialchars($fetch->stf_name), "</option>\n";
	}
}

// 表示行数
function select_displine($selected) {
	$ary = array(10, 20, 50, 100, 200, 500, 1000);
	option_tag(0, $selected, '- 全て -');
	foreach ($ary as $i)
		option_tag($i, $selected, $i);
}

// ユーザ状態
function select_user_status($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, '初期フォロー中');
	option_tag('2', $selected, '継続リレーション中');
	option_tag('3', $selected, '引継ぎ済み');
	option_tag('7', $selected, 'メール不達');
	option_tag('8', $selected, 'メール購読解除');
	option_tag('9', $selected, '終了済み');
}

// 性別
function select_seibetsu($selected) {
	option_tag('', $selected, DEFAULT_SELECT);
	option_tag('1', $selected, '男性');
	option_tag('2', $selected, '女性');
	option_tag('3', $selected, '法人');
	option_tag('9', $selected, '不明');
}

// 車種カテゴリ
function select_car_category($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT crc_car_category_cd,crc_car_category_name FROM t_car_category ORDER BY crc_car_category_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->crc_car_category_cd, $selected), ">", htmlspecialchars($fetch->crc_car_category_name), "</option>\n";
	}
}

// 担当者
function select_tantousha($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT tts_tantousha_id,tts_tantousha_name FROM t_tantousha ORDER BY tts_tantousha_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->tts_tantousha_id, $selected), ">", htmlspecialchars($fetch->tts_tantousha_name), "</option>\n";
	}
}

// 販売チャンネル
function select_sales_channel($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT sch_sales_channel_cd,sch_sales_channel_name FROM t_sales_channel ORDER BY sch_sales_channel_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->sch_sales_channel_cd, $selected), ">", htmlspecialchars($fetch->sch_sales_channel_name), "</option>\n";
	}
}

// キャンペーン選択
function select_campaign($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT cmp_campaign_cd,cmp_campaign_name FROM t_campaign ORDER BY cmp_campaign_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->cmp_campaign_cd, $selected), ">", htmlspecialchars($fetch->cmp_campaign_name), "</option>\n";
	}
}
?>