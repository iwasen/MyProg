<?
/******************************************************
' System :きかせて・net共通
' Content:検索条件入力処理
'******************************************************/

function search_script($search, $job_id = '') {
	global $top, $show_haimail_flag;
?>
<script type="text/javascript">
<!--
function show_reset() {
	with (document.form1) {
		show_hide('html_mail_c', html_mail_flag.checked);
<? if ($show_haimail_flag) { ?>
		show_hide('haimail_flag_c', haimail_flag_flag.checked);
<? } ?>
		show_hide('sex_c', sex_flag.checked);
		show_hide('age_c', age_flag.checked);
		show_hide('mikikon_c', mikikon_flag.checked);
		show_hide('shokugyou_c', shokugyou_flag.checked);
		show_hide('gyoushu_c', gyoushu_flag.checked);
		show_hide('shokushu_c', shokushu_flag.checked);
		show_hide('kyojuu_c', kyojuu_flag.checked);
		show_hide('kinmu_c', kinmu_flag.checked);
		show_hide('spouse_flg_c', spouse_flg_flag.checked);
		show_hide('have_child_c', have_child_flag.checked);
		show_hide('senior_flg_c', senior_flg_flag.checked);
		show_hide('housing_form_c', housing_form_flag.checked);
		show_hide('have_car_c', have_car_flag.checked);
		show_hide('genre_c', genre_flag.checked);
		show_hide('conveni_c', conveni_flag.checked);
		show_hide('super_c', super_flag.checked);
<? /* ?>
		show_hide('family_sex_c', family_sex_flag.checked);
		show_hide('family_rel_c', family_rel_flag.checked);
		show_hide('family_age_c', family_age_flag.checked);
		show_hide('child_c', child_flag.checked);
		show_hide('child_age_c', child_age_flag.checked);
<? */ ?>
		show_hide('sql_c', sql_flag.checked);
		show_hide('zip_c', zip_flag.checked);
		show_hide('taishou_hasshin_c', taishou_hasshin_flag.checked);
		show_hide('taishou_response_c', taishou_response_flag.checked);
		show_hide('jogai_hasshin_c', jogai_hasshin_flag.checked);
		show_hide('jogai_response_c', jogai_response_flag.checked);
		show_hide('member_list_c', member_list_flag.checked);
		show_hide('cross1_c', cross1_flag.checked);
		show_hide('cross2_c', cross2_flag.checked);
<?
	foreach (array_keys($search->ma_profile_tbl) as $profile_id) {
		$base_name = "profile$profile_id";
?>
		show_hide('<?=$base_name?>_c', <?=$base_name?>_flag.checked);
<?
	}
?>
		show_spouse();
		show_child();
	}
}
function show_hide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
function all_check(name, check) {
	var n = document.form1(name).length;
	for (var i = 0; i < n; i++)
		document.form1(name)[i].checked = check;
}
function job_select(title, name) {
	var url;
	url = "<?=$top?>/../common/job_select.php?job_id=<?=$job_id?>&title=" + title + "&name=" + name + "&id=" + document.form1(name).value.split("\n").join(",");
	window.open(url, "job_select", "resizable=yes,scrollbars=yes");
}
function member_select(name) {
	var url;
	url = "<?=$top?>/../common/member_list.php?name=" + name + "&id=" + document.form1(name).value.split("\n").join(",") + "&job_id=<?=$job_id?>";
	window.open(url, "member_list", "resizable=yes,scrollbars=yes,width=600");
}
function check_search_data(f) {
	if (f.age_flag.checked) {
		if (!check_number(f.age_from.value)) {
			alert("年齢は数値で入力してください。");
			f.age_from.focus();
			return false;
		}
		if (!check_number(f.age_to.value)) {
			alert("年齢は数値で入力してください。");
			f.age_to.focus();
			return false;
		}
	}
<? /* ?>
	if (f.family_age_flag.checked) {
		if (!check_number(f.family_age_from.value)) {
			alert("家族の年齢は数値で入力してください。");
			f.family_age_from.focus();
			return false;
		}
		if (!check_number(f.family_age_to.value)) {
			alert("家族の年齢は数値で入力してください。");
			f.family_age_to.focus();
			return false;
		}
	}
	if (f.child_age_flag.checked) {
		if (!check_number(f.child_age_from.value)) {
			alert("子供の年齢は数値で入力してください。");
			f.child_age_from.focus();
			return false;
		}
		if (!check_number(f.child_age_to.value)) {
			alert("子供の年齢は数値で入力してください。");
			f.child_age_to.focus();
			return false;
		}
	}
<? */ ?>
	if (f.zip_flag.checked) {
		if (!check_numbers(f.zip.value)) {
			alert("郵便番号の指定に誤りがあります。");
			f.zip.focus();
			return false;
		}
	}
	if (f.taishou_hasshin_flag.checked) {
		if (!check_numbers(f.taishou_hasshin.value)) {
			alert("対象発信ジョブの指定に誤りがあります。");
			f.taishou_hasshin.focus();
			return false;
		}
	}
	if (f.taishou_response_flag.checked) {
		if (!check_numbers(f.taishou_response.value)) {
			alert("対象レスポンスジョブの指定に誤りがあります。");
			f.taishou_response.focus();
			return false;
		}
	}
	if (f.jogai_hasshin_flag.checked) {
		if (!check_numbers(f.jogai_hasshin.value)) {
			alert("除外発信ジョブの指定に誤りがあります。");
			f.jogai_hasshin.focus();
			return false;
		}
	}
	if (f.jogai_response_flag.checked) {
		if (!check_numbers(f.jogai_response.value)) {
			alert("除外レスポンスジョブの指定に誤りがあります。");
			f.jogai_response.focus();
			return false;
		}
	}
	if (f.member_list_flag.checked) {
		if (!check_numbers(f.member_list.value)) {
			alert("メンバーリストの指定に誤りがあります。");
			f.member_list.focus();
			return false;
		}
	}
	if (f.cross1_flag.checked && f.cross2_flag.checked) {
		for (var i = 0; i < f.cross1.length; i++) {
			if (f.cross1[i].checked && f.cross2[i].checked) {
				alert("クロスサーチの表頭と表側は異なる項目を選択してください。");
				f.cross1[0].focus();
				return false;
			}
		}
	}
	return true;
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
function check_numbers(s) {
	var okStr = "0123456789,\r\n ";
	var ok = true;
	for (var i = 0; i < s.length; i++) {
		if (okStr.indexOf(s.charAt(i)) == -1) {
			ok = false;
			break;
		}
	}
	return ok;
}
function show_spouse() {
	var f = document.form1;
	document.getElementById("id_spouse").style.display = (f.spouse_flg_flag.checked && f.spouse_flg[0].checked) ? "" : "none";
}
function show_child() {
	var f = document.form1;
	document.getElementById("id_child").style.display = (f.have_child_flag.checked && f.have_child[0].checked) ? "" : "none";
}
//-->
</script>
<?
}

// dislay 出力
function display($flag) {
	if (!$flag)
		return 'style="display:none"';
}

// 職業選択
function checkbox_shokugyou($shokugyou, $class) {
	$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
	checkbox_common($sql, 'shokugyou', $shokugyou, 2, $class);
}

// 業種選択
function checkbox_gyoushu($gyoushu, $class) {
	$sql = "SELECT gs_gyoushu_cd,gs_gyoushu_name FROM m_gyoushu WHERE gs_type_cd=1 ORDER BY gs_order";
	checkbox_common($sql, 'gyoushu', $gyoushu, 2, $class);
}

// 職種選択
function checkbox_shokushu($shokushu, $class) {
	$sql = "SELECT ss_shokushu_cd,ss_shokushu_name FROM m_shokushu ORDER BY ss_order";
	checkbox_common($sql, 'shokushu', $shokushu, 2, $class);
}

// チェックボックス表示処理
function checkbox_common($sql, $name, $code, $cols, $class) {
	$code_ary = explode(',', $code);
	$sel_ary = array();

	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$sel_ary[$fetch[0]] = $fetch[1];
	}

	checkbox_common2($sel_ary, $name, $code_ary, $cols, $class);
}

// チェックボックス表示処理
function checkbox_common2($sel_ary, $name, $code_ary, $cols, $class) {
	echo '<table border=1 width="100%" bgcolor="#FFFFFF" bordercolorlight="#d0d0d0"', $class ? " class='$class'" : '', ">\n";
	$col = 0;
	foreach ($sel_ary as $code => $text) {
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%'><input type='checkbox' name='${name}[]' value='$code'", (is_array($code_ary) && in_array($code, $code_ary)) ? ' checked' : '', '>', htmlspecialchars($text), "</td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}

// 住所
function checkbox_juusho($name, $def, $class) {
	$defc = "$def,";

	echo '<table border=1 width="100%" bgcolor="#FFFFFF" bordercolorlight="#d0d0d0"', $class ? " class='$class'" : '', ">\n";

	$sql = "SELECT ci_todoufuken,ci_shiku FROM m_city ORDER BY ci_seq_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	$flag = false;
	$count = 0;
	$cols = 6;
	for ($i = 0; $i < $nrow; ) {
		$fetch = pg_fetch_object($result, $i);

		$kakko = '';
		if (is_null($fetch->ci_shiku)) {
			$value = $fetch->ci_todoufuken;
			$disp = $fetch->ci_todoufuken;
		} else {
			$value = "$fetch->ci_todoufuken/$fetch->ci_shiku";
			if (!$flag) {
				$kakko = '（';
				$flag = true;
			}
			$disp = '<font color="#008000">' . $fetch->ci_shiku . '</font>';
		}

		$i++;

		if ($flag) {
			if ($i == $nrow)
				$disp .= '）';
			else {
				$fetch = pg_fetch_object($result, $i);
				if (is_null($fetch->ci_shiku)) {
					$disp .= '）';
					$flag = false;
				}
			}
		}

		if ($count % $cols == 0)
			echo '<tr>';

		if (strstr($defc, "$value,"))
			$check = ' checked';
		else
			$check = '';

		echo "<td width='10%'><nobr>$kakko<input type='checkbox' name='{$name}[]' value='$value'$check>$disp</nobr></td>\n";

		if (++$count % $cols == 0)
			echo '</tr>';
	}

	if ($count % $cols != 0)
		echo '</tr>';

	echo '</table>';
}

// 興味ジャンル
function checkbox_genre($genre, $class) {
	$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
	checkbox_common($sql, 'genre', $genre, 4, $class);
}

// よく行くコンビニ
function checkbox_conveni($conveni, $class) {
	$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
	checkbox_common($sql, 'conveni', $conveni, 4, $class);
}

// よく行くスーパー
function checkbox_super($super, $class) {
	$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
	checkbox_common($sql, 'super', $super, 4, $class);
}

// 家族の続柄
function checkbox_family_rel($family_rel, $class) {
	$sql = "SELECT fr_family_rel_cd,fr_name FROM m_family_rel ORDER BY fr_family_rel_cd";
	checkbox_common($sql, 'family_rel', $family_rel, 8, $class);
}

function search_condition($search, $class = '', $shubetu = '') {
	global $show_haimail_flag;
?>
<tr>
	<td class="m1" width="20%">会員種別</td>
	<td class="n1" width="80%">
<?
// 全モニターをデフォルトとする
if ($search->type =='')
	$search->type = '0';
?>
		<input type="hidden" name="type_flag" value="1">
		<input type="radio" name="type" <?=value_checked('0', $search->type)?>>全モニター
		<input type="radio" name="type" <?=value_checked('5', $search->type)?>>アクティブモニターのみ
		<input type="radio" name="type" <?=value_checked('2', $search->type)?>>リサーチパートナーのみ
	</td>
</tr>
<tr>
	<td class="m1">HTMLメール受信フラグ</td>
	<td class="n1">
		<input type="checkbox" name="html_mail_flag" <?=checked($search->html_mail)?> onclick="show_hide('html_mail_c', checked)">指定する
		<span id="html_mail_c">&nbsp;
			<input type="radio" name="html_mail" <?=value_checked(DBTRUE, $search->html_mail)?>>配信OK
			<input type="radio" name="html_mail" <?=value_checked(DBFALSE, $search->html_mail)?>>配信NG
		</span>
	</td>
</tr>
<? if ($show_haimail_flag) { ?>
<tr>
	<td class="m1">はいめーる受信フラグ</td>
	<td class="n1">
		<input type="checkbox" name="haimail_flag_flag" <?=checked($search->mn_haimail_flag)?> onclick="show_hide('haimail_flag_c', checked);if (!checked) {document.form1.haimail_flag[0].checked = false;document.form1.haimail_flag[1].checked = false;}">指定する
		<span id="haimail_flag_c">&nbsp;
			<input type="radio" name="haimail_flag" <?=value_checked(DBTRUE, $search->haimail_flag)?>>受信する
			<input type="radio" name="haimail_flag" <?=value_checked(DBFALSE, $search->haimail_flag)?>>受信しない
		</span>
	</td>
</tr>
<? } ?>
<tr style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">
	<td colspan=2>基本情報</td>
</tr>
<tr>
	<td class="m1">性別</td>
	<td class="n1">
		<input type="checkbox" name="sex_flag" <?=checked($search->sex)?> onclick="show_hide('sex_c', checked)">指定する
		<span id="sex_c">&nbsp;
			<input type="radio" name="sex" <?=value_checked('1', $search->sex)?>>男性
			<input type="radio" name="sex" <?=value_checked('2', $search->sex)?>>女性
		</span>
	</td>
</tr>
<tr>
	<td class="m1">年齢</td>
	<td class="n1">
		<input type="checkbox" name="age_flag" <?=checked($search->age_from || $search->age_to)?> onclick="show_hide('age_c', checked)">指定する
		<span id="age_c">&nbsp;
			<input type="text" name="age_from" size=4 value="<?=$search->age_from?>">才から
			<input type="text" name="age_to" size=4 value="<?=$search->age_to?>">才まで
		</span>
	</td>
</tr>
<tr>
	<td class="m1">未既婚</td>
	<td class="n1">
		<input type="checkbox" name="mikikon_flag" <?=checked($search->mikikon)?> onclick="show_hide('mikikon_c', checked)">指定する
		<span id="mikikon_c">&nbsp;
			<input type="radio" name="mikikon" <?=value_checked('1', $search->mikikon)?>>未婚
			<input type="radio" name="mikikon" <?=value_checked('2', $search->mikikon)?>>既婚
			<input type="radio" name="mikikon" <?=value_checked('3', $search->mikikon)?>>その他
		</span>
	</td>
</tr>
<tr>
	<td class="m1">職業</td>
	<td class="n1">
		<input type="checkbox" name="shokugyou_flag" <?=checked($search->shokugyou)?> onclick="show_hide('shokugyou_c', checked)">指定する
		<span id="shokugyou_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('shokugyou[]', true)">
			<input type="button" value="全解除" onclick="all_check('shokugyou[]', false)">
			<? checkbox_shokugyou($search->shokugyou, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">業種</td>
	<td class="n1">
		<input type="checkbox" name="gyoushu_flag" <?=checked($search->gyoushu)?> onclick="show_hide('gyoushu_c', checked)">指定する
		<span id="gyoushu_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('gyoushu[]', true)">
			<input type="button" value="全解除" onclick="all_check('gyoushu[]', false)">
			<? checkbox_gyoushu($search->gyoushu, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">職種</td>
	<td class="n1">
		<input type="checkbox" name="shokushu_flag" <?=checked($search->shokushu)?> onclick="show_hide('shokushu_c', checked)">指定する
		<span id="shokushu_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('shokushu[]', true)">
			<input type="button" value="全解除" onclick="all_check('shokushu[]', false)">
			<? checkbox_shokushu($search->shokushu, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">居住地域</td>
	<td class="n1">
		<input type="checkbox" name="kyojuu_flag" <?=checked($search->kyojuu)?> onclick="show_hide('kyojuu_c', checked)">指定する
		<span id="kyojuu_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('kyojuu[]', true)">
			<input type="button" value="全解除" onclick="all_check('kyojuu[]', false)">
			<? checkbox_juusho('kyojuu', $search->kyojuu, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">勤務先地域</td>
	<td class="n1">
		<input type="checkbox" name="kinmu_flag" <?=checked($search->kinmu)?> onclick="show_hide('kinmu_c', checked)">指定する
		<span id="kinmu_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('kinmu[]', true)">
			<input type="button" value="全解除" onclick="all_check('kinmu[]', false)">
			&nbsp;　居住地／勤務地
			<input type="radio" name="kinmu_andor" <?=value_checked('O', $search->kinmu_andor)?>>OR
			<input type="radio" name="kinmu_andor" <?=value_checked('A', $search->kinmu_andor)?>>AND
			<? checkbox_juusho('kinmu', $search->kinmu, $class) ?>
		</span>
	</td>
</tr>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>生活情報</b></font></td>
</tr>
<tr>
	<td class="m1">同居家族（配偶者）</td>
	<td class="n1">
		<p>
			<input type="checkbox" name="spouse_flg_flag" <?=checked($search->spouse_flg)?> onclick="show_hide('spouse_flg_c', checked);show_spouse()">指定する
			<span id="spouse_flg_c">&nbsp;
				<input type="radio" name="spouse_flg" <?=value_checked('1', $search->spouse_flg)?> onclick="show_spouse()">有り
				<input type="radio" name="spouse_flg" <?=value_checked('2', $search->spouse_flg)?> onclick="show_spouse()">無し
				<input type="radio" name="spouse_flg" <?=value_checked('3', $search->spouse_flg)?> onclick="show_spouse()">その他
			</span>
		</p>
		<div id="id_spouse">
			<p>
				−−配偶者の年齢<br>
				&nbsp;&nbsp;&nbsp;
				<input type="text" name="spouse_age_from" size=4 value="<?=$search->spouse_age_from?>">才から
				<input type="text" name="spouse_age_to" size=4 value="<?=$search->spouse_age_to?>">才まで
			</p>
			<p>
				−−配偶者の職業<br>
				&nbsp;&nbsp;&nbsp;
				<select name="spouse_shokugyo"><? select_shokugyou('指定無し', $search->spouse_shokugyo) ?></select>
			</p>
		</div>
	</td>
</tr>
<tr>
	<td class="m1">同居家族(子供）</td>
	<td class="n1">
		<p>
			<input type="checkbox" name="have_child_flag" <?=checked($search->have_child)?> onclick="show_hide('have_child_c', checked);show_child()">指定する
			<span id="have_child_c">&nbsp;
				<input type="radio" name="have_child" <?=value_checked('1', $search->have_child)?> onclick="show_child()">有り
				<input type="radio" name="have_child" <?=value_checked('2', $search->have_child)?> onclick="show_child()">無し
			</span>
		</p>
		<div id="id_child">
			<p>
				−−子供の性別<br>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="child_sex" <?=value_checked('1', $search->child_sex)?>>男子
				<input type="radio" name="child_sex" <?=value_checked('2', $search->child_sex)?>>女子
			</p>
			<p>
				−−子供の年齢<br>
				&nbsp;&nbsp;&nbsp;
				<input type="text" name="child_age_from" size=4 value="<?=$search->child_age_from?>">才から
				<input type="text" name="child_age_to" size=4 value="<?=$search->child_age_to?>">才まで
			</p>
			<p>
				−−子供の学年<br>
				&nbsp;&nbsp;&nbsp;
				<select name="child_gakunen"><? select_child_gakunen('指定無し', $search->child_gakunen) ?></select>
			</p>
		</div>
	</td>
</tr>
<tr>
	<td class="m1">同居家族（シニア層）</td>
	<td class="n1">
		<div id="id_senior">
			<p>
				<input type="checkbox" name="senior_flg_flag" <?=checked($search->senior_flg)?> onclick="show_hide('senior_flg_c', checked)">指定する
				<span id="senior_flg_c">&nbsp;
					<input type="radio" name="senior_flg" <?=value_checked('1', $search->senior_flg)?>>有り
					<input type="radio" name="senior_flg" <?=value_checked('2', $search->senior_flg)?>>無し
				</span>
			</p>
		</div>
	</td>
</tr>
<tr>
	<td class="m1">住居形態</td>
	<td class="n1">
		<input type="checkbox" name="housing_form_flag" <?=checked($search->housing_form)?> onclick="show_hide('housing_form_c', checked)">指定する
		<span id="housing_form_c">&nbsp;
			<input type="radio" name="housing_form" <?=value_checked('1', $search->housing_form)?>>一戸建て
			<input type="radio" name="housing_form" <?=value_checked('2', $search->housing_form)?>>集合住宅
		</span>
	</td>
</tr>
<tr>
	<td class="m1">車の所有</td>
	<td class="n1">
		<input type="checkbox" name="have_car_flag" <?=checked($search->have_car)?> onclick="show_hide('have_car_c', checked)">指定する
		<span id="have_car_c">&nbsp;
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('1', $search->have_car)?>>免許と車を所有している
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('2', $search->have_car)?>>免許のみ所有している
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('3', $search->have_car)?>>免許は所有していない
		</span>
	</td>
</tr>
<tr>
	<td class="m1">興味ジャンル</td>
	<td class="n1">
		<input type="checkbox" name="genre_flag" <?=checked($search->genre)?> onclick="show_hide('genre_c', checked)">指定する
		<span id="genre_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('genre[]', true)">
			<input type="button" value="全解除" onclick="all_check('genre[]', false)">
			&nbsp;　結合条件
			<input type="radio" name="genre_andor" <?=value_checked('O', $search->genre_andor)?>>OR
			<input type="radio" name="genre_andor" <?=value_checked('A', $search->genre_andor)?>>AND
			<? checkbox_genre($search->genre, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">よく行くコンビニ</td>
	<td class="n1">
		<input type="checkbox" name="conveni_flag" <?=checked($search->conveni)?> onclick="show_hide('conveni_c', checked)">指定する
		<span id="conveni_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('conveni[]', true)">
			<input type="button" value="全解除" onclick="all_check('conveni[]', false)">
			&nbsp;　結合条件
			<input type="radio" name="conveni_andor" <?=value_checked('O', $search->conveni_andor)?>>OR
			<input type="radio" name="conveni_andor" <?=value_checked('A', $search->conveni_andor)?>>AND
			<? checkbox_conveni($search->conveni, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">よく行くスーパー</td>
	<td class="n1">
		<input type="checkbox" name="super_flag" <?=checked($search->super)?> onclick="show_hide('super_c', checked)">指定する
		<span id="super_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('super[]', true)">
			<input type="button" value="全解除" onclick="all_check('super[]', false)">
			&nbsp;　結合条件
			<input type="radio" name="super_andor" <?=value_checked('O', $search->super_andor)?>>OR
			<input type="radio" name="super_andor" <?=value_checked('A', $search->super_andor)?>>AND
			<? checkbox_super($search->super, $class) ?>
		</span>
	</td>
</tr>
<? /* ?>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>家族情報</b></font></td>
</tr>
<tr>
	<td class="m1">家族の性別</td>
	<td class="n1">
		<input type="checkbox" name="family_sex_flag" <?=checked($search->family_sex)?> onclick="show_hide('family_sex_c', checked)">指定する
		<span id="family_sex_c">&nbsp;
			<input type="checkbox" name="family_sex_m" <?=value_checked('t', $search->family_sex_m)?>>男性
			<input type="checkbox" name="family_sex_w" <?=value_checked('t', $search->family_sex_w)?>>女性
		</span>
	</td>
</tr>
<tr>
	<td class="m1">家族の続柄</td>
	<td class="n1">
		<input type="checkbox" name="family_rel_flag" <?=checked($search->family_rel)?> onclick="show_hide('family_rel_c', checked)">指定する
		<span id="family_rel_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('family_rel[]', true)">
			<input type="button" value="全解除" onclick="all_check('family_rel[]', false)">
			&nbsp;　結合条件
			<input type="radio" name="family_rel_andor" <?=value_checked('O', $search->family_rel_andor)?>>OR
			<input type="radio" name="family_rel_andor" <?=value_checked('A', $search->family_rel_andor)?>>AND
			<? checkbox_family_rel($search->family_rel, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">家族の年齢</td>
	<td class="n1">
		<input type="checkbox" name="family_age_flag" <?=checked($search->family_age_from || $search->family_age_to)?> onclick="show_hide('family_age_c', checked)">指定する
		<span id="family_age_c">&nbsp;
			<input type="text" name="family_age_from" size=4 value="<?=$search->family_age_from?>">才から
			<input type="text" name="family_age_to" size=4 value="<?=$search->family_age_to?>">才まで
		</span>
	</td>
</tr>
<tr>
	<td class="m1">子供の有無</td>
	<td class="n1">
		<input type="checkbox" name="child_flag" <?=checked($search->child)?> onclick="show_hide('child_c', checked)">指定する
		<span id="child_c">&nbsp;
			<input type="radio" name="child" <?=value_checked('t', $search->child)?>>有り
			<input type="radio" name="child" <?=value_checked('f', $search->child)?>>無し
		</span>
	</td>
</tr>
<tr>
	<td class="m1">子供の年齢</td>
	<td class="n1">
		<input type="checkbox" name="child_age_flag" <?=checked($search->child_age_from || $search->child_age_to)?> onclick="show_hide('child_age_c', checked)">指定する
		<span id="child_age_c">&nbsp;
			<input type="text" name="child_age_from" size=4 value="<?=$search->child_age_from?>">才から
			<input type="text" name="child_age_to" size=4 value="<?=$search->child_age_to?>">才まで
		</span>
	</td>
</tr>
<? */ ?>
<?
if (count($search->ma_profile_tbl) > 0) {
	$ma_profile = array();
	foreach (explode(',', $search->ma_profile) as $profile_cd) {
		if ($profile_cd)
			$ma_profile[$search->ma_profile_cd[$profile_cd]][] = $profile_cd;
	}

	$ma_profile_andor = explode(',', $search->ma_profile_andor);
?>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>手動追加プロファイル</b></font></td>
</tr>
<?
	foreach ($search->ma_profile_tbl as $profile_id => $profile_data) {
		$base_name = "profile$profile_id";
		$and_or = in_array($profile_id, $ma_profile_andor) ? 'A' : 'O';
?>
<tr>
	<td class="m1"><?=htmlspecialchars($profile_data->profile_name)?></td>
	<td class="n1">
		<input type="checkbox" name="<?=$base_name?>_flag" <?=checked(isset($ma_profile[$profile_id]))?> onclick="show_hide('<?=$base_name?>_c', checked)">指定する
		<span id="<?=$base_name?>_c">&nbsp;
			<input type="button" value="全選択" onclick="all_check('<?=$base_name?>[]', true)">
			<input type="button" value="全解除" onclick="all_check('<?=$base_name?>[]', false)">
			&nbsp;　結合条件
			<input type="radio" name="<?=$base_name?>_andor" <?=value_checked('O', $and_or)?>>OR
			<input type="radio" name="<?=$base_name?>_andor" <?=value_checked('A', $and_or)?>>AND
			<? checkbox_common2($profile_data->sel, $base_name, $ma_profile[$profile_id], 4, $class) ?>
		</span>
	</td>
</tr>
<?
	}
}
?>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>その他の条件</b></font></td>
</tr>
<tr>
	<td class="m1">追加ＳＱＬ</td>
	<td class="n1">
		<input type="checkbox" name="sql_flag" <?=checked($search->sql)?> onclick="show_hide('sql_c', checked)">指定する
		<span id="sql_c">&nbsp;
			<font class="note">（SQL文のWHERE条件を入力してください）</font><br>
			<textarea name="add_sql" cols=80 rows=5><?=$search->sql?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">郵便番号</td>
	<td class="n1">
		<input type="checkbox" name="zip_flag" <?=checked($search->zip)?> onclick="show_hide('zip_c', checked)">指定する
		<span id="zip_c">&nbsp;
			<font class="note">（郵便番号をカンマで区切って入力してください）</font><br>
			<textarea name="zip" cols=80 rows=3><?=$search->zip?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">対象発信ジョブ</td>
	<td class="n1">
		<input type="checkbox" name="taishou_hasshin_flag" <?=checked($search->taishou_hasshin)?> onclick="show_hide('taishou_hasshin_c', checked)">指定する
		<span id="taishou_hasshin_c">&nbsp;
			<font class="note">（ジョブIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
			<input type="button" value="選択" onclick="job_select('<?=urlencode('対象発信ジョブ選択')?>', 'taishou_hasshin')"><br>
			<textarea name="taishou_hasshin" cols=80 rows=3><?=$search->taishou_hasshin?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">対象レスポンスジョブ</td>
	<td class="n1">
		<input type="checkbox" name="taishou_response_flag" <?=checked($search->taishou_response)?> onclick="show_hide('taishou_response_c', checked)">指定する
		<span id="taishou_response_c">&nbsp;
			<font class="note">（ジョブIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
			<input type="button" value="選択" onclick="job_select('<?=urlencode('対象レスポンスジョブ選択')?>', 'taishou_response')"><br>
			<textarea name="taishou_response" cols=80 rows=3><?=$search->taishou_response?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">除外発信ジョブ</td>
	<td class="n1">
		<input type="checkbox" name="jogai_hasshin_flag" <?=checked($search->jogai_hasshin)?> onclick="show_hide('jogai_hasshin_c', checked)">指定する
		<span id="jogai_hasshin_c">&nbsp;
			<font class="note">（ジョブIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
			<input type="button" value="選択" onclick="job_select('<?=urlencode('除外発信ジョブ選択')?>', 'jogai_hasshin')"><br>
			<textarea name="jogai_hasshin" cols=80 rows=3><?=$search->jogai_hasshin?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">除外レスポンスジョブ</td>
	<td class="n1">
		<input type="checkbox" name="jogai_response_flag" <?=checked($search->jogai_response)?> onclick="show_hide('jogai_response_c', checked)">指定する
		<span id="jogai_response_c">&nbsp;
			<font class="note">（ジョブIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
			<input type="button" value="選択" onclick="job_select('<?=urlencode('除外レスポンスジョブ選択')?>', 'jogai_response')"><br>
			<textarea name="jogai_response" cols=80 rows=3><?=$search->jogai_response?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">メンバーリスト</td>
	<td class="n1">
		<input type="checkbox" name="member_list_flag" <?=checked($search->member_list)?> onclick="show_hide('member_list_c', checked)">指定する
		<span id="member_list_c">&nbsp;
			<font class="note">（メンバーグループIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
			<input type="button" value="選択" onclick="member_select('member_list')"><br>
			<textarea name="member_list" cols=80 rows=3><?=$search->member_list?></textarea>
		</span>
	</td>
</tr>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>クロスサーチ</b></font></td>
</tr>
<tr>
	<td class="m1">表頭指定</td>
	<td class="n1">
		<input type="checkbox" name="cross1_flag" onclick="show_hide('cross1_c', checked)">指定する
		<span id="cross1_c">&nbsp;
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td><input type="radio" name="cross1" value="1">性別</td>
					<td><input type="radio" name="cross1" value="2">年代別（10歳刻み）</td>
					<td><input type="radio" name="cross1" value="3">年代別（5歳刻み）</td>
					<td><input type="radio" name="cross1" value="4">未既婚別</td>
					<td><input type="radio" name="cross1" value="5">職業別</td>
				</tr>
				<tr>
					<td><input type="radio" name="cross1" value="6">業種別</td>
					<td><input type="radio" name="cross1" value="7">職種別</td>
					<td><input type="radio" name="cross1" value="8">都道府県別</td>
					<td><input type="radio" name="cross1" value="9">エリア別</td>
				</tr>
				<tr>
					<td><input type="radio" name="cross1" value="10">コンビニ別</td>
					<td><input type="radio" name="cross1" value="11">スーパー別</td>
					<td><input type="radio" name="cross1" value="12">興味ジャンル別</td>
				</tr>
			</table>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">表側指定</td>
	<td class="n1">
		<input type="checkbox" name="cross2_flag" onclick="show_hide('cross2_c', checked)">指定する
		<span id="cross2_c">&nbsp;
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td><input type="radio" name="cross2" value="1">性別</td>
					<td><input type="radio" name="cross2" value="2">年代別（10歳刻み）</td>
					<td><input type="radio" name="cross2" value="3">年代別（5歳刻み）</td>
					<td><input type="radio" name="cross2" value="4">未既婚別</td>
					<td><input type="radio" name="cross2" value="5">職業別</td>
				</tr>
				<tr>
					<td><input type="radio" name="cross2" value="6">業種別</td>
					<td><input type="radio" name="cross2" value="7">職種別</td>
					<td><input type="radio" name="cross2" value="8">都道府県別</td>
					<td><input type="radio" name="cross2" value="9">エリア別</td>
				</tr>
				<tr>
					<td><input type="radio" name="cross2" value="10">コンビニ別</td>
					<td><input type="radio" name="cross2" value="11">スーパー別</td>
					<td><input type="radio" name="cross2" value="12">興味ジャンル別</td>
				</tr>
			</table>
		</span>
	</td>
</tr>
<?
}
?>