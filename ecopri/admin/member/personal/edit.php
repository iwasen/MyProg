<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員個人検索
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
include("$inc/decode.php");
include("$inc/format.php");

// 登録状況
function decode_step($code) {
	if ($code) {
		switch($code) {
		case 1:
			return "仮登録１";
			break;
		case 2:
			return "仮登録２";
			break;
		case 3:
			return "本登録";
			break;
		case 9:
			return "退会";
			break;
		default:
			return "不明";
			break;
		}
	}
}

// 建築年次
function select_chikunen($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT cn_chikunen_cd,cn_chikunen_text FROM m_chikunen ORDER BY cn_chikunen_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->cn_chikunen_cd, $selected), '>', htmlspecialchars($fetch->cn_chikunen_text), '</option>', "\n";
	}
}
// 住居面積
function select_space($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT sp_space_cd,sp_space_text FROM m_space ORDER BY sp_space_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->sp_space_cd, $selected), '>', htmlspecialchars($fetch->sp_space_text), '</option>', "\n";
	}
}
// null→0
function null_to_zero($num) {
	if (!$num)
		return 0;
	else
		return $num;
}

// 種別select
function select_kind($selected) {
	echo '<option ', value_selected('', $selected), '>一般会員</option>', "\n";
	echo '<option ', value_selected(1, $selected), '>関係者</option>', "\n";
}

// メイン処理
set_global('member', '会員情報管理', '会員個人検索', BACK_TOP);

$sql = "SELECT * FROM t_member LEFT JOIN t_point ON pt_seq_no=mb_seq_no WHERE mb_seq_no=" . sql_number($seq_no);
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
function onload() {
	var f = document.form1;
	show_hide('family', f.disp_family.checked);
}

function show_hide(id, show) {
	if (!document.form1.disp_family.disabled)
	document.all(id)[0].style.display = show ? "" : "none";
}

function onSubmit_form1(f) {
	if (f.member_id.value == "") {
		alert("会員IDを入力してください。");
		f.member_id.focus();
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
	return confirm("会員登録情報を更新します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="onload()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■会員情報</td>
	</tr>
	<tr>
		<td class="m1">登録状況</td>
		<td class="n1"><?=decode_step($fetch->mb_step)?></td>
	</tr>
	<tr>
		<td class="m1">会員ID<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="member_id" size=28 maxlength=20 <?=value($fetch->mb_id)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->mb_password)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->mb_mail_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（漢字）</td>
		<td class="n1">
			<font size=-1>姓&nbsp;</font><input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->mb_name1)?>>
			<span class="note">（全角２５文字まで）</span>
			<br>
			<font size=-1>名&nbsp;</font><input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->mb_name2)?>>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）</td>
		<td class="n1">
			<font size=-1>姓&nbsp;</font><input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->mb_name1_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
			<br>
			<font size=-1>名&nbsp;</font><input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->mb_name2_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">性別</td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->mb_sex)?>><font size=-1>男性</font>
			<input type="radio" name="sex" <?=value_checked(2, $fetch->mb_sex)?>><font size=-1>女性</font>
		</td>
	</tr>
	<tr>
		<td class="m1">生年月日</td>
		<td class="n1">
			<font size=-1>西暦&nbsp;<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->mb_birthday))?>>年
			<select name="birthday_m"><?=select_month('', get_datepart('M', $fetch->mb_birthday))?></select>月
			<select name="birthday_d"><?=select_day('', get_datepart('D', $fetch->mb_birthday))?></select>日</font>
		</td>
	</tr>
	<tr>
		<td class="m1">郵便番号</td>
		<td class="n1">
			<input class="number" type="text" name="zip1" size=3 maxlength=3 <?=value(substr($fetch->mb_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="zip2" size=4 maxlength=4 <?=value(substr($fetch->mb_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">自宅住所</td>
		<td class="n1">
			<select name="area_cd"><? select_area('-未設定-', $fetch->mb_area_cd) ?></select><br>
			<input class="kanji" type="text" name="address" size=80 maxlength=50 <?=value($fetch->mb_address)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■同居のご家族</td>
	</tr>
	<tr>
		<td class="m1">家族カテゴリ</td>
		<input type="hidden" name="family_ctg" <?=value($fetch->mb_family_ctg)?>>
		<td class="n1">
			<?=decode_family_ctg($fetch->mb_family_ctg)?>&nbsp;&nbsp;
			<input type="checkbox" name="disp_family" onclick="show_hide('family', checked)" <?=$fetch->mb_family_ctg > 1 ? '': 'disabled';?>>
			<span class="note">家族構成表示</span></td>
	</tr>
<?
if ($fetch->mb_family_ctg > 1) {
?>
	<tr id="family">
		<td class="m1">家族構成</td>
		<td class="n1">
			<table border=0 cellspacing=2 cellpadding=0 width="100%">
<?
	$sql = "select * from t_family where fm_mb_seq_no=" . sql_number($seq_no);
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch2 = pg_fetch_object($result, $i);
?>
				<tr id="family">
					<td align="right" width=30><?=$i + 1?>.</td>
					<td width=100><?=decode_family_rel($fetch2->fm_family_rel_cd)?></td>
					<td><?=$fetch2->fm_age?> 歳</td>
				</tr>
<?
	}
?>
			</table>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m0" colspan=2>■住居について</td>
	</tr>
	<tr>
		<td class="m1">住居形態</td>
		<td class="n1">
			<font size=-1>
			<select name="keitai_cd"><?=select_keitai('-未設定-', $fetch->mb_keitai_cd)?></select><br>
			<select name="sozai_cd"><?=select_sozai('-未設定-',$fetch->mb_sozai_cd)?></select><br>
			へーベルハウス<input type="radio" name="hebel_flag" <?=value_checked(1,$fetch->mb_hebel_flag)?>>はい
			<input type="radio" name="hebel_flag" <?=value_checked(0,$fetch->mb_hebel_flag)?>>いいえ
			</font>
		</td>
	</tr>
	<tr>
		<td class="m1">建築年次</td>
		<td class="n1">
			<select name="chikunen_cd"><?=select_chikunen('-未設定-', $fetch->mb_chikunen_cd)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">住居間取り</td>
		<td class="n1">
			<select name="room_cd"><?=select_room('-未設定-', $fetch->mb_room_cd)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">居住面積</td>
		<td class="n1">
			<select name="space_cd"><?=select_space('-未設定-', $fetch->mb_space_cd)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">給湯器タイプ</td>
		<td class="n1">
			<select name="boiler_cd"><?=select_boiler('-未設定-', $fetch->mb_boiler_cd)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">ガス種</td>
		<td class="n1">
			<select name="gas_kind_cd"><?=select_gas_kind('-未設定-', $fetch->mb_gas_kind_cd)?></select>
			<font size=2>※都市ガスの方のみ→ </font>
			<!-- 都市ガス選択者のみ有効 -->
			<select name="gas_type_cd"><?=select_gas_type('-未設定-', $fetch->mb_gas_type_cd)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">水道請求月</td>
		<td class="n1">
			<select name="water_month">
			<option value=''>--請求月--
			<option <?=value_selected('1', $fetch->mb_water_month)?>>奇数月
			<option <?=value_selected('2', $fetch->mb_water_month)?>>偶数月
			</select>
		</td>
	</tr>
	<tr>
		<td class="m1">車所有</td>
		<td class="n1">
			<table width=100%>
				<tr>
					<td width=70><font size=2>ガソリン車</font></td>
					<td><font size=2><input type="text" size=5 maxlength=2 name="gcar_num" <?=value($fetch->mb_gcar_num)?>>台</font></td>
				</tr>
				<tr>
					<td><font size=2>ディーゼル車</font></td>
					<td><font size=2><input type="text" size=5 maxlength=2 name="dcar_num" <?=value($fetch->mb_dcar_num)?>>台</font></td>
				</tr>
				<tr>
					<td><font size=2>バイク</font></td>
					<td><font size=2><input type="text" size=5 maxlength=2 name="bike_num" <?=value($fetch->mb_bike_num)?>>台</font></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■入力項目情報</td>
	</tr>
	<tr>
		<td class="m1">灯油</td>
		<td class="n1">
			<select name="ol_flag">
			<option value=''>--未設定--
			<option <?=value_selected('1', $fetch->mb_ol_flag)?>>入力する
			<option <?=value_selected('0', $fetch->mb_ol_flag)?>>入力しない
			</select>
		</td>
	</tr>
	<tr>
		<td class="m1">ゴミ</td>
		<td class="n1">
			<select name="gm_flag">
			<option value=''>--未設定--
			<option <?=value_selected('1', $fetch->mb_gm_flag)?>>入力する
			<option <?=value_selected('0', $fetch->mb_gm_flag)?>>入力しない
			</select>
		</td>
	</tr>
	<tr>
		<td class="m1">ガソリン</td>
		<td class="n1">
			<select name="gl_flag">
			<option value=''>--未設定--
			<option <?=value_selected('1', $fetch->mb_gl_flag)?>>入力する
			<option <?=value_selected('0', $fetch->mb_gl_flag)?>>入力しない
			</select>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■その他情報</td>
	</tr>
	<tr>
		<td class="m1">会員種別</td>
		<td class="n1"><select name="kind"><?=select_kind($fetch->mb_kind)?></select></td>
	</tr>
	<tr>
		<td class="m1">ポイント</td>
		<td class="n1">
			<font size=-1>
			<input type="hidden" name="point" <?=value($fetch->pt_point)?>>
			ポイント&nbsp;&nbsp;<font size=3><?=null_to_zero($fetch->pt_point)?>&nbsp;pt</font>
<!--
			加算ポイント&nbsp;<input class="number" type="text" name="add_point" size=10 maxlength=6>
			</font>
			<span class="note">（減算する場合は、マイナス値）</span>
-->
		</td>
	</tr>
	<tr>
		<td class="m1">登録日時</td>
		<td class="n1"><?=format_datetime($fetch->mb_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">最新更新日時</td>
		<td class="n1"><?=format_datetime($fetch->mb_modify_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="seq_no" <?=value($seq_no)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
