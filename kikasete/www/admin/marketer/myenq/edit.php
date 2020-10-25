<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート変更処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
include("$inc/my_search.php");
include("$inc/mye_temp.php");
include("$inc/ma_profile.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// アンケート実施状態選択肢
function select_enq_status($sel) {
	if ($sel <= 4) {
		echo "<option ", value_selected('0', $sel), ">作成中</option>";
		echo "<option ", value_selected('1', $sel), ">作成完了</option>";
		echo "<option ", value_selected('2', $sel), ">申請中</option>";
		echo "<option ", value_selected('3', $sel), ">事務局チェック中</option>";
		echo "<option ", value_selected('4', $sel), ">承認済み</option>";
		echo "<option ", value_selected('8', $sel), ">再申請</option>";
	} else {
		echo "<option ", value_selected('5', $sel), ">実施中</option>";
		echo "<option ", value_selected('6', $sel), ">一時停止</option>";
		echo "<option ", value_selected('7', $sel), ">終了</option>";
	}
	echo "<option ", value_selected('9', $sel), ">削除済み</option>";
}

// 選択肢表示
function disp_sel(&$sel, $ex_sno = 0) {
	if (is_array($sel)) {
		foreach ($sel as $sno => $text) {
			if ($sno != $ex_sno)
				echo "$text\n";
		}
	}
}

// セル設定値取得
function cell_name_value($age_option, $sex, $age) {
	global $cell;

	$name = "cell[$age_option][$sex][$age]";
	$value = $cell->age_option == $age_option ? $cell->send_num[$sex][$age] : '';

	return "name='$name' value='$value'";
}

set_global('myenquete', 'Ｍｙアンケート管理', 'Ｍｙアンケート情報変更', BACK_TOP);

$myenq = new my_enquete_class;
$myenq->read_db($marketer_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;
$cell = &$myenq->cell;
$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

// 説明文が設定されていない場合はテンプレートを表示
$description = ($enquete->description != '') ? $enquete->description : get_enq_description($myenq);

// メールタイトルが設定されていない場合はアンケートタイトルを表示
$mail_title = ($myenq->mail_title != '') ? $myenq->mail_title : $enquete->title;

// メール分が設定されていない場合はテンプレートを表示
if ($myenq->mail_contents == '')
	get_enq_body($myenq, $mail_header, $mail_contents, $mail_footer);
else {
	$mail_header = $myenq->mail_header;
	$mail_contents = $myenq->mail_contents;
	$mail_footer = $myenq->mail_footer;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	var f = document.form1;
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++) {
		if (f["image" + i].value != "" || f["current_image_type" + i].value != 3) {
			if (f["image_type" + i][2].checked) {
				switch (f["image" + i].value.slice(-4).toLowerCase()) {
				case ".jpg":
				case ".gif":
					break;
				default:
					alert("挿入画像は「jpg」または「gif」形式のファイルを指定してください。");
					f["image" + i].focus();
					return false;
				}
			}
		}
	}
	return confirm("このＭｙアンケートを更新します。よろしいですか？");
}
function change_age_type() {
	var f = document.form1;
	if (f.age_type.selectedIndex == 1) {
		document.getElementById("age_type1").disabled = false;
	} else {
		document.getElementById("age_type1").disabled = true;
		for (var i = 0; i < f["age_cd[]"].length; i++) {
			f["age_cd[]"][i].checked = false;
		}
	}
	if (f.age_type.selectedIndex == 2) {
		document.getElementById("age_type2").disabled = false;
		f.age_from.disabled = false;
		f.age_to.disabled = false;
	} else {
		document.getElementById("age_type2").disabled = true;
		f.age_from.disabled = true;
		f.age_to.disabled = true;
		f.age_from.value = "";
		f.age_to.value = "";
	}
}
function sel_question_type(n) {
	var f = document.form1;
	var t = f["question_type" + n].value;
	switch (t) {
	case "1":
	case "2":
	case "7":
		document.getElementById("sel" + n).style.display = "";
		document.getElementById("mtx" + n).style.display = "none";
		document.getElementById("na1" + n).style.display = "none";
		document.getElementById("na2" + n).style.display = "none";
		document.getElementById("exf" + n).style.display = t == "2" ? "" : "none";
		document.getElementById("faf" + n).style.display = t != "7" ? "" : "none";
		break;
	case "3":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("mtx" + n).style.display = "none";
		document.getElementById("na1" + n).style.display = "none";
		document.getElementById("na2" + n).style.display = "none";
		break;
	case "4":
	case "5":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("mtx" + n).style.display = "";
		document.getElementById("na1" + n).style.display = "none";
		document.getElementById("na2" + n).style.display = "none";
		break;
	case "6":
	case "8":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("mtx" + n).style.display = "none";
		document.getElementById("na1" + n).style.display = "";
		document.getElementById("na2" + n).style.display = "";
		break;
	}
}
function onload_body() {
	change_age_type();
<? if ($cell->cell_option == 2) { ?>
	calc_cell_num();
<? } ?>
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++)
		sel_question_type(i);
}
function calc_cell_num() {
	var f = document.form1;
	var sex_s = <?=$sex_s?>;
	var sex_e = <?=$sex_e?>;
	var age_s = <?=$age_s?>;
	var age_e = <?=$age_e?>;
	var count = 0;
	var sum = 0;
	for (sex = sex_s; sex <= sex_e; sex++) {
		for (age = age_s; age <= age_e; age++) {
			var cell = f["cell[" + "<?=$cell->age_option?>" + "][" + sex + "][" + age +"]"];
			if (cell.value != "") {
				if (check_num(cell.value)) {
					count++;
					sum += parseInt(cell.value);
				} else {
					alert("半角数字で入力してください。");
					cell.value = "";
					cell.focus();
					return;
				}
			}
		}
	}
	f.cell_diff.value = parseInt(f.send_num.value) - sum;
}
function check_num(s) {
	for (var i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9")
			return false;
	}
	return true;
}
//-->
</script>
</head>
<body onload="onload_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■概要設定内容</td>
	</tr>
	<tr>
		<td class="m1" width="20%">開始日時</td>
		<td class="n1">
			<input class="number" type="text" name="start_date_y" size="4" maxlength="4" <?=value($enquete->start_date_y)?>>年
			<input class="number" type="text" name="start_date_m" size="2" maxlength="2" <?=value($enquete->start_date_m)?>>月
			<input class="number" type="text" name="start_date_d" size="2" maxlength="2" <?=value($enquete->start_date_d)?>>日
			<input class="number" type="text" name="start_date_h" size="2" maxlength="2" <?=value($enquete->start_date_h)?>>時
		</td>
	</tr>
	<tr>
		<td class="m1">終了日時</td>
		<td class="n1">
			<input class="number" type="text" name="end_date_y" size="4" maxlength="4" <?=value($enquete->end_date_y)?>>年
			<input class="number" type="text" name="end_date_m" size="2" maxlength="2" <?=value($enquete->end_date_m)?>>月
			<input class="number" type="text" name="end_date_d" size="2" maxlength="2" <?=value($enquete->end_date_d)?>>日
			<input class="number" type="text" name="end_date_h" size="2" maxlength="2" <?=value($enquete->end_date_h)?>>時
		</td>
	</tr>
	<tr>
		<td class="m1">ポイント数</td>
		<td class="n1">
			<input class="number" type="text" name="point" size="3" maxlength="3" <?=value($enquete->point)?>>ポイント
		</td>
	</tr>
	<tr>
		<td class="m1">最大発信数</td>
		<td class="n1">
			<input class="number" type="text" name="send_num" size="5" maxlength="5" <?=value($myenq->send_num)?>>人
		</td>
	</tr>
<?
if ($cell->cell_option == 2) {
?>
	<tr>
		<td class="m1">セル別発信数</td>
		<td class="n1">
<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
<?
	if ($cell->age_option == 1) {
?>
			<!-- 【年代を指定しない】選択時に表示 -->
	<tr>
		<td>
			<table width="40%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box" class="small">
				<tr>
					<td>男性</td>
					<td align="center"><input type="text" <?=cell_name_value(1, 1, 0)?> size="5" onblur="calc_cell_num()"></td>
				</tr>
				<tr>
					<td>女性</td>
					<td align="center"><input type="text" <?=cell_name_value(1, 2, 0)?> value="" size="5" onblur="calc_cell_num()"></td>
				</tr>
			</table>
		</td>
	</tr>
			<!-- //【年代を指定しない】選択時に表示 -->
<?
	} elseif ($cell->age_option == 2) {
?>
			<!-- 【5歳刻み】選択時に表示 -->
	<tr>
		<td>
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box" class="small">
				<tr>
					<td rowspan=2>&nbsp;</td>
					<td align="center">10代</td>
					<td align="center" colspan=2>20代</td>
					<td align="center" colspan=2>30代</td>
					<td align="center" colspan=2>40代</td>
					<td align="center" colspan=2>50代</td>
					<td align="center" colspan=2>60代</td>
					<td align="center" rowspan=2>70代<br>以上</td>
				</tr>
				<tr>
					<td align="center">後</td>
					<td align="center">前</td>
					<td align="center">後</td>
					<td align="center">前</td>
					<td align="center">後</td>
					<td align="center">前</td>
					<td align="center">後</td>
					<td align="center">前</td>
					<td align="center">後</td>
					<td align="center">前</td>
					<td align="center">後</td>
				</tr>
<?
		if ($cell->sex_option == 1) {
?>
				<tr>
					<td align="center">性別指定なし</td>
<?
			for ($i = 3; $i <= 14; $i++) {
?>
					<td align="center"><input type="text" <?=cell_name_value(2, 0, $i)?> size="3" onblur="calc_cell_num()"></td>
<?
			}
?>
				</tr>
<?
		} elseif ($cell->sex_option == 2) {
?>
				<tr>
					<td align="center" nowrap>男性</td>
<?
			for ($i = 3; $i <= 14; $i++) {
?>
					<td align="center"><input type="text" <?=cell_name_value(2, 1, $i)?> size="3" onblur="calc_cell_num()"></td>
<?
			}
?>
				</tr>
				<tr>
					<td align="center" nowrap>女性</td>
<?
			for ($i = 3; $i <= 14; $i++) {
?>
					<td align="center"><input type="text" <?=cell_name_value(2, 2, $i)?> size="3" onblur="calc_cell_num()"></td>
<?
			}
?>
				</tr>
<?
		}
?>
			</table>
		</td>
	</tr>
			<!-- //【5歳刻み】選択時に表示 -->
<?
	} elseif ($cell->age_option == 3) {
?>
			<!-- 【10歳刻み】選択時に表示 -->
	<tr>
		<td>
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box" class="small">
				<tr>
					<td>&nbsp;</td>
					<td align="center">10代</td>
					<td align="center">20代</td>
					<td align="center">30代</td>
					<td align="center">40代</td>
					<td align="center">50代</td>
					<td align="center">60代</td>
					<td align="center">70代以上</td>
				</tr>
<?
		if ($cell->sex_option == 1) {
?>
				<tr>
					<td align="center">性別指定なし</td>
<?
		for ($i = 2; $i <= 8; $i++) {
?>
					<td align="center"><input type="text" <?=cell_name_value(3, 0, $i)?> size="5" onblur="calc_cell_num()"></td>
<?
		}
?>
				</tr>
<?
		} elseif ($cell->sex_option == 2) {
?>
				<tr>
					<td align="center">男性</td>
<?
			for ($i = 2; $i <= 8; $i++) {
?>
					<td align="center"><input type="text" <?=cell_name_value(3, 1, $i)?> size="5" onblur="calc_cell_num()"></td>
<?
			}
?>
				</tr>
				<tr>
					<td align="center">女性</td>
<?
			for ($i = 2; $i <= 8; $i++) {
?>
					<td align="center"><input type="text" <?=cell_name_value(3, 2, $i)?> size="5" onblur="calc_cell_num()"></td>
<?
			}
?>
				</tr>
<?
		}
?>
			</table>
		</td>
	</tr>
			<!-- //【10歳刻み】選択時に表示 -->
<?
	}
?>

</table>
		</td>
	</tr>
	<tr>
		<td class="m1"><span class="small">最大発信数 - セル別指定合計</span></td>
		<td class="n1">
			<input class="number" type="text" name="cell_diff" size="5" maxlength="5" readonly>　（この数字が0になるように設定してください。）
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">実施状態</td>
		<td class="n1"><select name="status"><? select_enq_status($enquete->status) ?></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■アンケート内容</td>
	</tr>
	<tr>
		<td class="m1">アンケートタイトル</td>
		<td class="n1">
			<input class="kanji" type="text" name="title" size="50" maxlength="30" <?=value($enquete->title)?>>についてのアンケート
		</td>
	</tr>
	<tr>
		<td class="m1">説明文</td>
		<td class="n1">
			<textarea class="kanji" rows="20" cols="70" name="description"><?=htmlspecialchars($description)?></textarea>
		</td>
	</tr>
<?
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];

	if ($question->url)
		$image_type = 2;
	elseif ($question->image_id)
		$image_type = 3;
	else
		$image_type = 1;
?>
	<tr>
		<td class="m1" width="15%" rowspan="7">Ｑ<?=mb_convert_kana($i, 'N')?>
			<div class="small"><input type="checkbox" name="must_flag<?=$i?>" <?=value_checked('t', $question->must_flag)?>>必須</div>
		</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text<?=$i?>"<?=$disabled?>><?=htmlspecialchars($question->question_text)?></textarea>
			<div class="small">■画像の指定<br>
				<input type="radio" name="image_type<?=$i?>" <?=value_checked('1', $image_type)?><?=$disabled?>>なし<br>
				<input type="radio" name="image_type<?=$i?>" <?=value_checked('2', $image_type)?><?=$disabled?>>URLを指定する 　<input type="text" name="url<?=$i?>" size="30" <?=value($question->url)?><?=$disabled?>><br>
				<input type="radio" name="image_type<?=$i?>" <?=value_checked('3', $image_type)?><?=$disabled?>>画像を埋め込む　<input type="file" name="image<?=$i?>" size="30"<?=$disabled?>>
				<input type="hidden" name="current_image_type<?=$i?>" <?=value($image_type)?>>
<?
if ($image_type == 3) {
?>
						<br>　　（画像は設定済みです。画像を上書きしたい場合は、再度パスを指定してください）
<?
}
?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type<?=$i?>" onchange="sel_question_type(<?=$i?>)"<?=$disabled?>>
				<option <?=value_selected('1', $question->question_type)?>><?=decode_question_type(1)?>
				<option <?=value_selected('7', $question->question_type)?>><?=decode_question_type(7)?>
				<option <?=value_selected('2', $question->question_type)?>><?=decode_question_type(2)?>
				<option <?=value_selected('8', $question->question_type)?>><?=decode_question_type(8)?>
				<option <?=value_selected('3', $question->question_type)?>><?=decode_question_type(3)?>
				<option <?=value_selected('6', $question->question_type)?>><?=decode_question_type(6)?>
				<option <?=value_selected('4', $question->question_type)?>><?=decode_question_type(4)?>
				<option <?=value_selected('5', $question->question_type)?>><?=decode_question_type(5)?>
			</select>
		</td>
	</tr>
	<tr id="sel<?=$i?>">
		<td class="n1">
			<textarea name="sel_text<?=$i?>" cols="50" rows="10"<?=$disabled?>><?=disp_sel($question->sel_text, $question->ex_sno)?></textarea>
			<div id="faf<?=$i?>" class="small"><input type="checkbox" name="fa_flag<?=$i?>" <?=value_checked('t', $question->fa_flag)?><?=$disabled?>>最後の選択肢でフリー回答を取得</div>
			<div id="exf<?=$i?>"><input type="checkbox" name="ex_flag<?=$i?>" <?=value_checked(true, $question->ex_sno != 0)?>>排他選択肢を末尾につける（「この中にない」「特にない」など）<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排他選択肢の文字列 <input type="text" name="ex_sel<?=$i?>" <?=value($question->sel_text[$question->ex_sno])?> size=50></div>
		</td>
	</tr>
	<tr id="mtx<?=$i?>">
		<td class="n1">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td width="50%"><strong>表側（10個まで）</strong><br>
			<textarea name="hyousoku<?=$i?>" cols="32" rows="10"<?=$disabled?>><? disp_sel($question->hyousoku) ?></textarea></td>
			<td width="50%"><strong>表頭（10個まで）</strong><br>
			<textarea name="hyoutou<?=$i?>" cols="32" rows="10"<?=$disabled?>><? disp_sel($question->hyoutou) ?></textarea></td>
		</tr>
		</table>
			<div class="small"><input type="checkbox" name="dup_flag<?=$i?>" <?=value_checked('t', $question->dup_flag)?><?=$disabled?>>回答の重複を許さない</div>
		</td>
	</tr>
	<tr id="na1<?=$i?>">
		<td class="n1">
			<div>■回答欄の前に表示する文字列（全角１５文字程度）</div>
			<textarea name="pre_text<?=$i?>" cols="50" rows="3"><?=htmlspecialchars($question->pre_text)?></textarea>
		</td>
	</tr>
	<tr id="na2<?=$i?>">
		<td class="n1">
			<div>■回答欄の後に表示する文字列（全角１５文字程度）</div>
			<textarea name="post_text<?=$i?>" cols="50" rows="3"><?=htmlspecialchars($question->post_text)?></textarea>
		</td>
	</tr>
	<tr></tr>
<?
}
?>
	<tr>
		<td class="m0" colspan=2>■条件設定内容</td>
	</tr>
	<tr>
		<td class="m1">性別</td>
		<td class="n1">
			<input type="checkbox" name="sex[]" <?=value_checked('1', $search->sex)?>>男性&nbsp;
			<input type="checkbox" name="sex[]" <?=value_checked('2', $search->sex)?>>女性
		</td>
	</tr>
	<tr>
		<td class="m1">対象モニター</td>
		<td class="n1">
			<input type="radio" name="type" <?=value_checked('0', $search->type)?>>全モニター&nbsp;
			<input type="radio" name="type" <?=value_checked('5', $search->type)?>>アクティブモニターのみ&nbsp;
			<input type="radio" name="type" <?=value_checked('2', $search->type)?>>リサーチパートナーのみ
		</td>
	</tr>
	<tr>
		<td class="m1">年代</td>
		<td class="n1">
			<select name="age_type" onchange="change_age_type()">
				<option <?=value_selected('', $myenq->age_type)?>>形式を選択してください</option>
				<option <?=value_selected('1', $myenq->age_type)?>>5歳刻みの年齢</option>
				<option <?=value_selected('2', $myenq->age_type)?>>任意の年齢</option>
			</select>
			<span id=age_type1>
<?
$sql = "SELECT ae_age_cd,ae_age_text FROM m_age WHERE ae_age_cd>=3 ORDER BY ae_age_cd";
checkbox_common($sql, 'age_cd', $search->age_cd, 4);
?>
			</span>
			<span id="age_type2">
			<table border=0 width="100%">
				<tr>
					<td><input type="text" name="age_from" <?=value($search->age_from)?> size="4" maxlength="2">
					才〜
					<input type="text" name="age_to" <?=value($search->age_to)?> size="4" maxlength="2">
					才<font class="note">（半角数字）</font></td>
				</tr>
			</table>
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">未既婚</td>
		<td class="n1">
			<input type="checkbox" name="mikikon[]" <?=value_checked('1', $search->mikikon)?>>未婚&nbsp;
			<input type="checkbox" name="mikikon[]" <?=value_checked('2', $search->mikikon)?>>既婚&nbsp;
			<input type="checkbox" name="mikikon[]" <?=value_checked('3', $search->mikikon)?>>その他
		</td>
	</tr>
	<tr>
		<td class="m1">居住地域</td>
		<td class="n1">
<?
$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
checkbox_common($sql, 'jitaku_area', $search->jitaku_area, 5);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">職業</td>
		<td class="n1">
<?
$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
checkbox_common($sql, 'shokugyou', $search->shokugyou, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">業種</td>
		<td class="n1">
<?
$sql = "SELECT gs_gyoushu_cd,gs_gyoushu_name FROM m_gyoushu WHERE gs_type_cd=1 ORDER BY gs_order";
checkbox_common($sql, 'gyoushu', $search->gyoushu, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">職種</td>
		<td class="n1">
<?
$sql = "SELECT ss_shokushu_cd,ss_shokushu_name FROM m_shokushu ORDER BY ss_order";
checkbox_common($sql, 'shokushu', $search->shokushu, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">子供の有無</td>
		<td class="n1">
			<input type="checkbox" name="have_child[]" <?=value_checked_multi('1', $search->have_child)?>>有り&nbsp;
			<input type="checkbox" name="have_child[]" <?=value_checked_multi('2', $search->have_child)?>>無し
		</td>
	</tr>
	<tr>
		<td class="m1">住居形態</td>
		<td class="n1">
			<input type="checkbox" name="housing_form[]" <?=value_checked_multi('1', $search->housing_form)?>>一戸建て&nbsp;
			<input type="checkbox" name="housing_form[]" <?=value_checked_multi('2', $search->housing_form)?>>集合住宅
		</td>
	</tr>
	<tr>
		<td class="m1">車の所有</td>
		<td class="n1">
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('1', $search->have_car)?>>免許と車を所有している&nbsp;
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('2', $search->have_car)?>>免許のみ所有している&nbsp;
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('3', $search->have_car)?>>免許は所有していない
		</td>
	</tr>
	<tr>
		<td class="m1">コンビニ</td>
		<td class="n1">
<?
$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
checkbox_common($sql, 'conveni', $search->conveni, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">スーパー</td>
		<td class="n1">
<?
$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
checkbox_common($sql, 'super', $search->super, 2);
?>
		</td>
	</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
	<tr>
		<td class="m1"><?=htmlspecialchars($profile_name)?></td>
		<td class="n1">
<?
$sql = "SELECT pfs_profile_cd,pfs_select_text FROM t_profile_sel WHERE pfs_profile_id=$profile_id ORDER BY pfs_order";
checkbox_common($sql, 'ma_profile', $search->ma_profile, 2);
?>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">興味ジャンル</td>
		<td class="n1">
<?
$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
checkbox_common($sql, 'genre', $search->genre, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■メール内容</td>
	</tr>
	<tr>
		<td class="m1">メールタイトル</td>
		<td class="n1">
			<input type="text" name="mail_title" size="50" maxlength="100" <?=value($mail_title)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">ヘッダ</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_header"><?=htmlspecialchars($mail_header)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">本文</td>
		<td class="n1">
			<textarea rows="20" cols="70" wrap="hard" name="mail_contents"><?=htmlspecialchars($mail_contents)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">フッタ</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_footer"><?=htmlspecialchars($mail_footer)?></textarea>
		</td>
	</tr>
</table>

<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
