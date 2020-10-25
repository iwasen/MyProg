<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニター個人検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/lifeinfo.php");
include("$inc/format.php");
include("$inc/ma_profile.php");

// メイン処理
set_global('monitor', 'モニター管理', 'モニター個人検索', BACK_TOP);

$sql = "SELECT * FROM t_monitor JOIN t_monitor_point ON mp_monitor_id=mn_monitor_id WHERE mn_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('モニターＩＤが不正', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.name1.value == "") {
		alert("名前（漢字）の姓を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("名前（漢字）の名を入力してください。");
		f.name2.focus();
		return false;
	}
	if (f.name1_kana.value == "") {
		alert("名前（カナ）の姓を入力してください。");
		f.name1_kana.focus();
		return false;
	}
	if (f.name2_kana.value == "") {
		alert("名前（カナ）の名を入力してください。");
		f.name2_kana.focus();
		return false;
	}
	if (f.birthday_y.value == "") {
		alert("生年月日を入力してください。");
		f.birthday_y.focus();
		return false;
	}
	if (f.mikikon[0].checked == false && f.mikikon[1].checked == false && f.mikikon[2].checked == false) {
		alert("未既婚を選択してください。");
		f.mikikon[0].focus();
		return false;
	}
	if (f.sex[0].checked == false && f.sex[1].checked == false) {
		alert("性別を選択してください。");
		f.sex[0].focus();
		return false;
	}
	if (f.mail_addr.value == "") {
		alert("メールアドレスを入力してください。");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("パスワードを入力してください。");
		f.password.focus();
		return false;
	}
	if (f.jitaku_zip1.value == "") {
		alert("郵便番号（自宅）を入力してください。");
		f.jitaku_zip1.focus();
		return false;
	}
	if (f.jitaku_zip2.value == "") {
		alert("郵便番号（自宅）を入力してください。");
		f.jitaku_zip2.focus();
		return false;
	}
	if (f.jitaku_area.value == "") {
		alert("居住地域（自宅）を選択してください。");
		f.jitaku_area.focus();
		return false;
	}
	if (f.shokugyou_cd.value == "") {
		alert("職業を選択してください。");
		f.shokugyou_cd.focus();
		return false;
	}
	if (f.shokugyou_cd.value != "5" && f.shokugyou_cd.value != "10" && f.shokugyou_cd.value != "11") {
		if (f.gyoushu_cd.value == "") {
			alert("業種を選択してください。");
			f.gyoushu_cd.focus();
			return false;
		}
		if (f.shokushu_cd.value == "") {
			alert("職種を選択してください。");
			f.shokushu_cd.focus();
			return false;
		}
	}
	return confirm("モニター登録情報を更新します。よろしいですか？");
}
function onload_body() {
	disp_spouse();
	disp_child();
}
function disp_spouse() {
	document.getElementById("id_spouse").style.display = document.form1.spouse_flg[0].checked ? "" : "none";
}
function disp_child() {
	var f = document.form1;
	document.getElementById("id_child").style.display = f.have_child[0].checked ? "" : "none";
	for (i = 0; i < <?=MAX_MONITOR_CHILD?>; i++)
		document.getElementById("id_child_" + i).style.display = (i < f.child_num.value) ? "" : "none";
	document.getElementById("id_add_child").style.display = (f.child_num.value < <?=MAX_MONITOR_CHILD?>) ? "" : "none";
	document.getElementById("id_del_child").style.display = (f.child_num.value > 1) ? "" : "none";
}
function add_child() {
	document.form1.child_num.value++;
	disp_child();
}
function del_child() {
	document.form1.child_num.value--;
	disp_child();
}
//-->
</script>
</head>
<body onload="onload_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■基本情報</td>
	</tr>
	<tr>
		<td class="m1">名前（漢字）<?=MUST_ITEM?></td>
		<td class="n1">
			姓<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->mn_name1)?>>
			<span class="note">（全角２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->mn_name2)?>>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）<?=MUST_ITEM?></td>
		<td class="n1">
			姓<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->mn_name1_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->mn_name2_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">生年月日<?=MUST_ITEM?></td>
		<td class="n1">
			西暦<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->mn_birthday))?>>年
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->mn_birthday))?></select>月
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->mn_birthday))?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">未既婚<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="mikikon" <?=value_checked(1, $fetch->mn_mikikon)?>>未婚
			<input type="radio" name="mikikon" <?=value_checked(2, $fetch->mn_mikikon)?>>既婚
			<input type="radio" name="mikikon" <?=value_checked(3, $fetch->mn_mikikon)?>>その他
		</td>
	</tr>
	<tr>
		<td class="m1">性別<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->mn_sex)?>>男性
			<input type="radio" name="sex" <?=value_checked(2, $fetch->mn_sex)?>>女性
		</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->mn_mail_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->mn_password)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">HTMLメール受信<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="html_mail" <?=value_checked(DBTRUE, $fetch->mn_html_mail)?>>受信する
			<input type="radio" name="html_mail" <?=value_checked(DBFALSE, $fetch->mn_html_mail)?>>受信しない
		</td>
	</tr>
	<tr>
		<td class="m1">はいめーる受信フラグ<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="haimail_flag" <?=value_checked(DBTRUE, $fetch->mn_haimail_flag)?>>受信する
			<input type="radio" name="haimail_flag" <?=value_checked(DBFALSE, $fetch->mn_haimail_flag)?>>受信しない
		</td>
	</tr>
	<tr>
		<td class="m1">郵便番号（自宅）<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="jitaku_zip1" size=3 maxlength=3 <?=value(substr($fetch->mn_jitaku_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="jitaku_zip2" size=4 maxlength=4 <?=value(substr($fetch->mn_jitaku_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">居住地域（自宅）<?=MUST_ITEM?></td>
		<td class="n1"><select name="jitaku_area"><? select_area('- 未設定 -', $fetch->mn_jitaku_area)?></select></td>
	</tr>
	<tr>
		<td class="m1">職業<?=MUST_ITEM?></td>
		<td class="n1"><select name="shokugyou_cd"><? select_shokugyou('- 未設定 -', $fetch->mn_shokugyou_cd)?></select></td>
	</tr>
	<tr>
		<td class="m1">業種<?=MUST_ITEM?></td>
		<td class="n1"><select name="gyoushu_cd"><? select_gyoushu('- 未設定 -', $fetch->mn_gyoushu_cd, 1)?></select></td>
	</tr>
	<tr>
		<td class="m1">職種<?=MUST_ITEM?></td>
		<td class="n1"><select name="shokushu_cd"><? select_shokushu('- 未設定 -', $fetch->mn_shokushu_cd)?></select></td>
	</tr>
	<tr>
		<td class="m1">郵便番号（勤務先）</td>
		<td class="n1">
			<input class="number" type="text" name="kinmu_zip1" size=3 maxlength=3 <?=value(substr($fetch->mn_kinmu_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="kinmu_zip2" size=4 maxlength=4 <?=value(substr($fetch->mn_kinmu_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先地域<?=MUST_ITEM?></td>
		<td class="n1"><select name="kinmu_area"><? select_area('- 未設定 -', $fetch->mn_kinmu_area)?></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■生活情報</td>
	</tr>
	<tr>
		<td class="m1">同居家族（配偶者）</td>
		<td class="n1">
			<input type="radio" name="spouse_flg" <?=value_checked('1', $fetch->mn_spouse_flg)?> onclick="disp_spouse()">あり　
			<input type="radio" name="spouse_flg" <?=value_checked('2', $fetch->mn_spouse_flg)?> onclick="disp_spouse()">なし　
			<input type="radio" name="spouse_flg" <?=value_checked('3', $fetch->mn_spouse_flg)?> onclick="disp_spouse()">その他　
			<input type="text" name="spouse_other" <?=value($fetch->mn_spouse_other)?>>
			<div id="id_spouse">
				<br>
				−−配偶者の生年月日<br>
				&nbsp;&nbsp;&nbsp;
				<select name="spouse_birthday_y"><? select_year(1900, ' ', get_datepart('Y', $fetch->mn_spouse_birthday), date('Y')) ?></select> 年　
				<select name="spouse_birthday_m"><? select_month(' ', get_datepart('M', $fetch->mn_spouse_birthday)) ?></select> 月　
				<select name="spouse_birthday_d"><? select_day(' ', get_datepart('D', $fetch->mn_spouse_birthday)) ?></select> 日
				<br><br>
				−−配偶者の職業<br>
				&nbsp;&nbsp;&nbsp;
				<select name="spouse_shokugyo_cd" size="1"><?select_shokugyou('お選び下さい', $fetch->mn_spouse_shokugyo_cd)?></select>
			</div>
		</td>
	</tr>
	<tr>
		<td class="m1">同居家族（子供）</td>
		<td class="n1">
			<input type="radio" name="have_child" <?=value_checked('1', $fetch->mn_have_child)?> onclick="disp_child()">子供あり　
			<input type="radio" name="have_child" <?=value_checked('2', $fetch->mn_have_child)?> onclick="disp_child()">子供なし
			<div id="id_child">
<?
$child = array();
$sql = "SELECT mc_sex,mc_birthday FROM t_monitor_child WHERE mc_monitor_id=$monitor_id ORDER BY mc_child_no";
$result2 = db_exec($sql);
$nrow2 = pg_numrows($result2);
for ($i = 0; $i < $nrow2; $i++) {
	$fetch2 = pg_fetch_object($result2, $i);

	$child[$i]->sex = $fetch2->mc_sex;
	$child[$i]->birthday_y = get_datepart('Y', $fetch2->mc_birthday);
	$child[$i]->birthday_m = get_datepart('M', $fetch2->mc_birthday);
	$child[$i]->birthday_d = get_datepart('D', $fetch2->mc_birthday);
}

$child_num = count($child);

for ($i = 0; $i < MAX_MONITOR_CHILD; $i++) {
?>
				<table cellpadding="1" cellspacing="0" id="id_child_<?=$i?>" <?=$i < $child_num ? '' : 'style="display:none"'?>>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">−−<?=$i + 1?>人目</td>
					</tr>
					<tr>
						<td width="100">&nbsp;&nbsp;&nbsp; 性別</td>
						<td>
							<input type="radio" name="child_sex[<?=$i?>]" <?=value_checked('1', $child[$i]->sex)?>>男子　
							<input type="radio" name="child_sex[<?=$i?>]" <?=value_checked('2', $child[$i]->sex)?>>女子
						</td>
					</tr>
					<tr>
						<td width="100">&nbsp;&nbsp;&nbsp; 生年月日</td>
						<td>
							<select name="child_birthday_y[<?=$i?>]"><? select_year(1900, ' ', $child[$i]->birthday_y, date('Y')) ?></select> 年　
							<select name="child_birthday_m[<?=$i?>]"><? select_month(' ', $child[$i]->birthday_m) ?></select> 月　
							<select name="child_birthday_d[<?=$i?>]"><? select_day(' ', $child[$i]->birthday_d) ?></select> 日
						</td>
					</tr>
				</table>
<?
}
?>
				&nbsp;&nbsp;&nbsp;
				<input type="button" id="id_add_child" value="追加" onclick="add_child()">
				<input type="button" id="id_del_child" value="削除" onclick="del_child()">
				<input type="hidden" name="child_num" <?=value($child_num)?>>
			</div>
		</td>
	</tr>
	<tr>
		<td class="m1">同居家族（シニア層）</td>
		<td class="n1">
			<input type="radio" name="senior_flg" <?=value_checked('1', $fetch->mn_senior_flg)?>>あり　
			<input type="radio" name="senior_flg" <?=value_checked('2', $fetch->mn_senior_flg)?>>なし
		</td>
	</tr>
	<tr>
		<td class="m1">住居形態<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="housing_form" <?=value_checked(1, $fetch->mn_housing_form)?>>一戸建て
			<input type="radio" name="housing_form" <?=value_checked(2, $fetch->mn_housing_form)?>>集合住宅
		</td>
	</tr>
	<tr>
		<td class="m1">車の所有<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="have_car" <?=value_checked(1, $fetch->mn_have_car)?>>免許と車を所有している
			<input type="radio" name="have_car" <?=value_checked(2, $fetch->mn_have_car)?>>免許のみ所有している
			<input type="radio" name="have_car" <?=value_checked(3, $fetch->mn_have_car)?>>免許は所有していない
		</td>
	</tr>
	<tr>
		<td class="m1">よく行くコンビニ</td>
		<td class="n1"><? checkbox_conveni($monitor_id, 4)?></td>
	</tr>
	<tr>
		<td class="m1">よく行くスーパー</td>
		<td class="n1"><? checkbox_super($monitor_id, 4)?></td>
	</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
	<tr>
		<td class="m1"><?=htmlspecialchars($profile_name)?></td>
		<td class="n1"><? checkbox_ma_profile($monitor_id, $profile_id, 4)?></td>
<?
}
?>
	<tr>
		<td class="m1">興味ジャンル</td>
		<td class="n1"><? checkbox_genre($monitor_id, 4)?></td>
	</tr>
<? /* ?>
	<tr>
		<td class="m1">自己紹介文</td>
		<td class="n1">
			<textarea class="kanji" name="self_introduction" cols=60 rows=5><?=htmlspecialchars($fetch->mn_self_introduction)?></textarea><br>
			<span class="note">（全角５００文字まで）</span>
		</td>
	</tr>
<? */ ?>
	<tr>
		<td class="m0" colspan=2>■個人情報</td>
	</tr>
	<tr>
		<td class="m1">自宅住所１</td>
		<td class="n1">
			<input class="kanji" type="text" name="jitaku_addr1" size=80 maxlength=50 <?=value($fetch->mn_jitaku_addr1)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">自宅住所２</td>
		<td class="n1">
			<input class="kanji" type="text" name="jitaku_addr2" size=80 maxlength=50 <?=value($fetch->mn_jitaku_addr2)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">自宅住所３</td>
		<td class="n1">
			<input class="kanji" type="text" name="jitaku_addr3" size=80 maxlength=50 <?=value($fetch->mn_jitaku_addr3)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">自宅電話番号</td>
		<td class="n1">
			<input class="alpha" type="text" name="jitaku_tel" size=20 maxlength=20 <?=value($fetch->mn_jitaku_tel)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">自宅FAX番号</td>
		<td class="n1">
			<input class="alpha" type="text" name="jitaku_fax" size=20 maxlength=20 <?=value($fetch->mn_jitaku_fax)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">緊急連絡先</td>
		<td class="n1">
			<input class="kanji" type="text" name="urgent_addr" size=80 maxlength=50 <?=value($fetch->mn_urgent_addr)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
<?
/*
?>
	<tr>
		<td class="m1">勤務先住所１</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr1" size=80 maxlength=50 <?=value($fetch->mn_kinmu_addr1)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先住所２</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr2" size=80 maxlength=50 <?=value($fetch->mn_kinmu_addr2)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先住所３</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr3" size=80 maxlength=50 <?=value($fetch->mn_kinmu_addr3)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先電話番号</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_tel" size=20 maxlength=20 <?=value($fetch->mn_kinmu_tel)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先FAX番号</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_fax" size=20 maxlength=20 <?=value($fetch->mn_kinmu_fax)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
<?
*/
?>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50 <?=value($fetch->mn_kinmu_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">部署名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50 <?=value($fetch->mn_kinmu_unit)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">役職</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50 <?=value($fetch->mn_kinmu_post)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■その他情報</td>
	</tr>
	<tr>
		<td class="m1">種別</td>
		<td class="n1"><select name="type"><? select_monitor_type('', $fetch->mn_type)?></select></td>
	</tr>
	<tr>
		<td class="m1">ブラックリストフラグ</td>
		<td class="n1">
			<select name="blacklist_flg"><? select_blacklist('設定なし', $fetch->mn_blacklist_flg) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">状態</td>
		<td class="n1"><select name="status"><? select_monitor_status('', $fetch->mn_status)?></select></td>
	</tr>
	<tr>
		<td class="m1">ポイント</td>
		<td class="n1">
			現ポイント<input class="number" type="text" name="curr_point" size=10 maxlength=10 <?=value($fetch->mp_curr_point)?>>
			累積ポイント<input class="number" type="text" name="total_point" size=10 maxlength=10 <?=value($fetch->mp_total_point)?>>
			　<input type="checkbox" name="point_change" value="1">変更
		</td>
	</tr>
	<tr>
		<td class="m1">ポイントカウントダウン</td>
		<td class="n1"><select name="stop_period_flag"><option <?=value_selected(DBFALSE, $fetch->mp_stop_period_flag)?>>有効</option><option <?=value_selected(DBTRUE, $fetch->mp_stop_period_flag)?>>無効</option></select></td>
	</tr>
	<tr>
		<td class="m1">登録日時</td>
		<td class="n1"><?=format_datetime($fetch->mn_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">最新更新日時</td>
		<td class="n1"><?=format_datetime($fetch->mn_update_date)?></td>
	</tr>
	<tr>
		<td class="m1">退会日時</td>
		<td class="n1"><?=format_datetime($fetch->mn_taikai_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="monitor_id" <?=value($monitor_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
