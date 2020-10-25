<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート表示処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// 選択肢表示
function disp_sel(&$sel, $ex_sno = 0) {
	if (is_array($sel)) {
		foreach ($sel as $sno => $text) {
			if ($sno != $ex_sno)
				echo "$text\n";
		}
	}
}

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｐｒｏアンケート情報変更', BACK_TOP);

// セッション処理
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
	$marketer_id = $pro_enq->marketer_id;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq->read_db($marketer_id, $pro_enq_no);
}

if ($main)
	$enquete = &$pro_enq->enquete2;
else
	$enquete = &$pro_enq->enquete;

if ($enquete->max_question_no == 0)
	$enquete->max_question_no = 1;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
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
	if (navigator.appName.charAt(0) == "N") {
		switch (f["question_type" + n].selectedIndex) {
		case 0:
		case 1:
		case 2:
		case 3:
			document.getElementById("q" + n).rowSpan = 4;
			break;
		case 4:
			document.getElementById("q" + n).rowSpan = 3;
			break;
		case 5:
			document.getElementById("q" + n).rowSpan = 5;
			break;
		}
	}
}
function onload_body() {
	var f = document.form1;
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++) {
		if (f["qno" + i])
			sel_question_type(i);
	}
}
function update() {
	var f = document.form1;
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++) {
		if (f["qno" + i]) {
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
	}
	f.next_action.value = "update";
	f.submit();
}
function add_question() {
	var f = document.form1;
	f.next_action.value = "add_question";
	f.submit();
}
function delete_question(i, qno) {
	var f = document.form1;
	if (confirm("Q" + qno + " を削除してもよろしいですか？")) {
		f.delete_qno.value = i;
		f.next_action.value = "delete_question";
		f.submit();
	}
}
function move_question(i, qno) {
	var width = 300;
	var height = 200;
	var left = (window.screen.width - width) / 2;
	var top = (window.screen.height - height) / 2;
	window.open("<?=$top?>/../common/move_question.php?ss=ss_pro_enq&main=<?=$main?>&move_from=" + i, "move_question", "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top);
}
window.onload = function() {
  onload_body();
}
//-->
</script>
</head>
<body onload="onload_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update2.php" enctype="multipart/form-data">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">■アンケートの設定</td>
	</tr>
	<tr>
		<td>
			<table border=2 cellspacing=0 cellpadding=3 width="100%" frame="box" id="question" bordercolorlight="#ffffff" bordercolordark="#ffffff">
<?
if ($main && $pro_enq->finding_flag == DBTRUE) {
	$description = $enquete->description;
	if ($description == '')
		$description = $pro_enq->res_cond_text('とお答えいただいた方にお聞きします。');
?>
				<tr>
					<td class="m1" width="20%">説明文</td>
					<td class="n1" width="80%">
						アンケートの説明文を入れてください<br>
						<textarea rows="5" cols="70" name="description"><?=htmlspecialchars($description)?></textarea>
					</td>
				</tr>
<?
}

// 有効な（削除されていない）質問数を取得
$qnum = 0;
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	if (!$enquete->question[$i]->delete_flag)
		$qnum++;
}

// 質問ごとのループ
$qno = 1;
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];

	if ($question->delete_flag)
		continue;

	// 初期値設定
	if (!isset($question))
		$question->must_flag = 't';

	// 画像・リンクの指定
	if ($question->url)
		$image_type = 2;
	elseif ($question->image_id)
		$image_type = 3;
	else
		$image_type = 1;
?>
				<tr>
					<td class="m1" rowspan="8" id="q<?=$i?>" height="100%">
						<input type="hidden" name="qno<?=$i?>" value="1">
						<b>Ｑ<?=mb_convert_kana($qno, 'N')?></b>
						<div><nobr><input type="checkbox" name="must_flag<?=$i?>" <?=value_checked('t', $question->must_flag)?>>必須</nobr></div>
<?
	if ($qnum >= 2) {
?>
						<div><input type="button" value="　削除　" onclick="delete_question(<?=$i?>,<?=$qno?>)"></div>
						<div><input type="button" value="　移動　" onclick="move_question(<?=$i?>,<?=$qno?>)"></div>
<?
	}
?>
					</td>
					<td class="n1">
						<div>■質問文</div>
						<textarea rows="5" cols="70" name="question_text<?=$i?>"><?=htmlspecialchars($question->question_text)?></textarea>
					</td>
				</tr>
				<tr>
					<td class="n1">
						<div>■画像・リンクの指定</div>
						<input type="radio" name="image_type<?=$i?>" <?=value_checked('1', $image_type)?>>なし<br>
						<input type="radio" name="image_type<?=$i?>" <?=value_checked('2', $image_type)?>>URLを指定する 　<input type="text" name="url<?=$i?>" size="50" <?=value($question->url)?>><br>
						<input type="radio" name="image_type<?=$i?>" <?=value_checked('3', $image_type)?>>画像を埋め込む　<input type="file" name="image<?=$i?>" size="50">
						<input type="hidden" name="current_image_type<?=$i?>" <?=value($image_type)?>>
<?
if ($image_type == 3) {
?>
						<br>　　（画像は設定済みです。画像を上書きしたい場合は、再度パスを指定してください）
<?
}
?>
					</td>
				</tr>
				<tr>
					<td class="n1">
						<select name="question_type<?=$i?>" onchange="sel_question_type(<?=$i?>)">
							<option <?=value_selected('1', $question->question_type)?>><?=decode_question_type(1)?>
							<option <?=value_selected('7', $question->question_type)?>><?=decode_question_type(7)?>
							<option <?=value_selected('2', $question->question_type)?>><?=decode_question_type(2)?>
<?
// FAは本アンケートのみ
if ($main) {
?>
							<option <?=value_selected('8', $question->question_type)?>><?=decode_question_type(8)?>
							<option <?=value_selected('3', $question->question_type)?>><?=decode_question_type(3)?>
<?
}
?>
							<option <?=value_selected('6', $question->question_type)?>><?=decode_question_type(6)?>
							<option <?=value_selected('4', $question->question_type)?>><?=decode_question_type(4)?>
							<option <?=value_selected('5', $question->question_type)?>><?=decode_question_type(5)?>
						</select>
						<input type="hidden" name="question_type_org<?=$i?>" <?=value($question->question_type)?>>
						<input type="hidden" name="res_cond_flag<?=$i?>" <?=value(!$main && isset($pro_enq->res_cond[$question->question_no]) ? 1 : 0)?>>
					</td>
				</tr>
				<tr id="sel<?=$i?>">
					<td class="n1">
						選択肢（<?=MAX_PRO_SEL?>個まで)<br>
						<textarea name="sel_text<?=$i?>" cols="50" rows="10"><? disp_sel($question->sel_text, $question->ex_sno) ?></textarea>
						<div id="faf<?=$i?>"><input type="checkbox" name="fa_flag<?=$i?>" <?=value_checked('t', $question->fa_flag)?>>最後の選択肢でフリー回答を取得</div>
						<div id="exf<?=$i?>"><input type="checkbox" name="ex_flag<?=$i?>" <?=value_checked(true, $question->ex_sno != 0)?>>排他選択肢を末尾につける<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排他選択肢の文字列 <input type="text" name="ex_sel<?=$i?>" <?=value($question->sel_text[$question->ex_sno])?> size=50></div>
					</td>
				</tr>
				<tr id="mtx<?=$i?>">
					<td class="n1">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>表側（10個まで）<br>
								<textarea name="hyousoku<?=$i?>" cols="32" rows="10"><? disp_sel($question->hyousoku) ?></textarea></td>
								<td>&nbsp;&nbsp;</td>
								<td>表頭（10個まで）<br>
								<textarea name="hyoutou<?=$i?>" cols="32" rows="10"><? disp_sel($question->hyoutou) ?></textarea></td>
							</tr>
						</table>
						<div><input type="checkbox" name="dup_flag<?=$i?>" <?=value_checked('t', $question->dup_flag)?>>回答の重複を許さない</div>
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
	$qno++;
}

if ($main)
	$max_enq = MAX_PRO_ENQ;
else
	$max_enq = MAX_PRO_FINDING;

if ($qno <= $max_enq) {
?>
				<tr>
					<td colspan="2" class="n1">
						<input type="button" value="　質問を追加　" onclick="add_question()">
					</td>
				</tr>
<?
}
?>
			</table>
		</td>
	</tr>
</table>

<br>
<input type="button" value="　更新　" onclick="update()">
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
<input type="hidden" name="delete_qno">
<input type="hidden" name="move_from">
<input type="hidden" name="move_to">
<input type="hidden" name="main" <?=value($main)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
