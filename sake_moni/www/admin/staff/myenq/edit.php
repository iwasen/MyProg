<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
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
function disp_sel(&$sel) {
	if (is_array($sel))
		echo join("\n", $sel);
}

set_global('staff', 'スタッフ管理｜Ｍｙアンケート管理', 'Ｍｙアンケート情報変更', BACK_TOP);

$myenq = new my_enquete_class;
$myenq->read_db($staff_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;

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
	switch (f["question_type" + n].value) {
	case "1":
	case "2":
		document.getElementById("sel" + n).style.display = "";
		document.getElementById("matrix" + n).style.display = "none";
		break;
	case "3":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("matrix" + n).style.display = "none";
		break;
	case "4":
	case "5":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("matrix" + n).style.display = "";
		break;
	}
}
function onload_body() {
	change_age_type();
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++)
		sel_question_type(i);
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
		<td class="m1" width="20%">アンケート種類</td>
		<td class="n1" width="80%">
			<input type="radio" name="enq_type" <?=value_checked('1', $enquete->enq_type)?>>メールアンケート
			<input type="radio" name="enq_type" <?=value_checked('2', $enquete->enq_type)?>>Ｗｅｂアンケート
		</td>
	</tr>
	<tr>
		<td class="m1">開始日時</td>
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
			<input class="number" type="text" name="send_num" size="4" maxlength="4" <?=value($myenq->send_num)?>>人
		</td>
	</tr>
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
			<input class="kanji" type="text" name="title" size="50" maxlength="60" <?=value($enquete->title)?>>
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
					<td class="m1" width="15%" rowspan="5">Ｑ<?=mb_convert_kana($i, 'N')?></td>
					<td class="n1">
						<textarea rows="3" cols="70" name="question_text<?=$i?>"<?=$disabled?>><?=htmlspecialchars($question->question_text)?></textarea>
						<div class="small">■画像の指定<br>
							<input type="radio" name="image_type<?=$i?>" <?=value_checked('1', $image_type)?><?=$disabled?>>なし<br>
							<input type="radio" name="image_type<?=$i?>" <?=value_checked('2', $image_type)?><?=$disabled?>>URLを指定する 　<input type="text" name="url<?=$i?>" size="30" <?=value($question->url)?><?=$disabled?>><br>
							<input type="radio" name="image_type<?=$i?>" <?=value_checked('3', $image_type)?><?=$disabled?>>画像を埋め込む　<input type="file" name="image<?=$i?>" size="30"<?=$disabled?>>
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
							<option <?=value_selected('1', $question->question_type)?>>単一選択(SA)
							<option <?=value_selected('2', $question->question_type)?>>複数選択(MA)
							<option <?=value_selected('4', $question->question_type)?>>ﾏﾄﾘｸｽ(SA)
							<option <?=value_selected('5', $question->question_type)?>>ﾏﾄﾘｸｽ(MA)
							<option <?=value_selected('3', $question->question_type)?>>自由回答(FA)
						</select>
					</td>
				</tr>
				<tr id="sel<?=$i?>">
					<td class="n1">
						<textarea name="sel_text<?=$i?>" cols="50" rows="10"<?=$disabled?>><?=disp_sel($question->sel_text)?></textarea>
						<div class="small"><input type="checkbox" name="fa_flag<?=$i?>" <?=value_checked('t', $question->fa_flag)?><?=$disabled?>>最後の選択肢でフリー回答を取得</div>
					</td>
				</tr>
				<tr id="matrix<?=$i?>">
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
		<td class="m1">年代</td>
		<td class="n1">
			<select name="age_type" onchange="change_age_type()">
				<option <?=value_selected('', $myenq->age_type)?>>形式を選択してください</option>
				<option <?=value_selected('1', $myenq->age_type)?>>5歳刻みの年齢</option>
				<option <?=value_selected('2', $myenq->age_type)?>>任意の年齢</option>
			</select>
			<span id=age_type1>
<?
$sql = "SELECT ae_age_cd,ae_age_text FROM m_age ORDER BY ae_age_cd";
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
			<input type="checkbox" name="mikikon[]" <?=value_checked('1', $search->mikikon)?>>独身&nbsp;
			<input type="checkbox" name="mikikon[]" <?=value_checked('2', $search->mikikon)?>>既婚
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
		<td class="m1">チェーン</td>
		<td class="n1">
<?
$sql = "SELECT ch_chain_cd,ch_name FROM m_chain WHERE ch_status=0 ORDER BY ch_order";
checkbox_common($sql, 'chain', $search->chain, 2);
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
<input type="hidden" name="staff_id" value="<?=$staff_id?>">
<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
