<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:１万人アンケート管理
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
include("$inc/enquete.php");

// アンケート実施状態選択肢
function select_enq_status($sel) {
	if ($sel <= 4) {
		echo "<option ", value_selected('0', $sel), ">作成中</option>";
		echo "<option ", value_selected('4', $sel), ">実施待ち</option>";
	} else {
		echo "<option ", value_selected('5', $sel), ">実施中</option>";
		echo "<option ", value_selected('6', $sel), ">一時停止</option>";
		echo "<option ", value_selected('7', $sel), ">終了</option>";
	}
}

set_global('monitor', 'モニター管理', '１万人アンケート管理', BACK_TOP);

$sql = "SELECT fe_top_page,fe_enquete_id,fe_report_addr" .
			" FROM t_free_enquete WHERE fe_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
$fetch = pg_fetch_object($result, 0);

$enquete = new enquete_class;
$enquete->read_db($fetch->fe_enquete_id);

$today = getdate(time() + (60 * 60 * 24));
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
	switch (f.next_action.value) {
	case "update":
		f.action = "update.php";
		f.target = "_self";
		return input_check(f, "１万人アンケートを更新します。よろしいですか？");
	case "delete":
		f.action = "update.php";
		f.target = "_self";
		return confirm("１万人アンケートを削除します。よろしいですか？");
	case "preview":
		f.action = "preview.php";
		f.target = "_blank";
		return true;
	}
	return false;
}
function input_check(f, msg) {
	if (f.title.value == "") {
		alert("お題を入力してください。");
		f.title.focus();
		return false;
	}
	if (f.description.value == "") {
		alert("説明文を入力してください。");
		f.description.focus();
		return false;
	}
	var start_date = new Date(f.start_date_y.value, f.start_date_m.value - 1, f.start_date_d.value, f.start_date_h.value);
	var end_date = new Date(f.end_date_y.value, f.end_date_m.value - 1, f.end_date_d.value, f.end_date_h.value);
	if (start_date > end_date) {
		alert("終了日時は開始日時より後の日付を入力してください。");
		f.end_date_y.focus();
		return false;
	}
	if (f.point.value == "") {
		alert("付与ポイントを入力してください。");
		f.point.focus();
		return false;
	}
	for (var i = 1; i <= f.question_cnt.value; i++) {
		var cnt = f["sel_cnt" + i].value;
		for (var j = 0; j < cnt; j++) {
			if (f["sel_text" + i + "[]"][j].value != '') {
				if (f["sel_text_s" + i + "[]"][j].value == '') {
					alert("短縮形の選択肢を入力してください。");
					f["sel_text_s" + i + "[]"][j].focus();
					return false;
				}
			}
		}
	}
	return confirm("１万人アンケートを登録します。よろしいですか？");
}
function show_hide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
function add_question() {
	var html = '\
<table border=0 cellspacing=2 cellpadding=3 width="80%">\
	<tr>\
		<td width="100%" colspan=2 style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">Ｑ%Q%</td>\
	</tr>\
	<tr>\
		<td class="m1" width="20%">質問形式</td>\
		<td class="n1" width="80%">\
			<select name="question_type%Q%">\
				<option value="1">単一選択肢(SA)</option>\
				<option value="2">複数選択肢(MA)</option>\
			</select>\
			　<input type="radio" name="top_page" value="%Q%"' + (document.form1.question_cnt.value == 0 ? ' checked' : '') + '>トップページに表示\
		</td>\
	</tr>\
	<tr>\
		<td class="m1">質問文</td>\
		<td class="n1"><textarea name="question_text%Q%" cols=65 rows=5></textarea></td>\
	</tr>\
	<tr>\
		<td class="m1">選択肢</td>\
		<td class="n1">\
			<span id="sel%Q%"></span>\
			<input type="button" value="選択肢追加" onclick="add_select(%Q%)">\
			<input type="hidden" name="sel_cnt%Q%" value="0">\
		</td>\
	</tr>\
</table>';

document.all.question.innerHTML += html.replace(/%Q%/g, ++document.form1.question_cnt.value);
add_select(document.form1.question_cnt.value);
add_select(document.form1.question_cnt.value);
add_select(document.form1.question_cnt.value);
}
function add_select(question_no) {
	var sel = document.all["sel" + question_no];
	var cnt = document.form1["sel_cnt" + question_no];
	cnt.value++;
	sel.innerHTML += cnt.value + ' <input type="text" name="sel_text' + question_no + '[]" size=60> <input type="text" name="sel_text_s' + question_no + '[]" size=20><br>';
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%" id="tbl">
	<tr>
		<td class="m0" colspan=2>■１万人アンケートを入力してください</td>
	</tr>
	<tr>
		<td class="m1" width="20%">お題<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="kanji" type="text" name="title" size=80 maxlength=100 <?=value($enquete->title)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">説明文<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea name="description" cols=78 rows=10><?=htmlspecialchars($enquete->description)?></textarea><br>
			<span class="note">（文字数制限はありません）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">開始日時<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="start_date_y"><? select_year(2001, '', $enquete->start_date_y) ?></select>年
			<select name="start_date_m"><? select_month('', $enquete->start_date_m) ?></select>月
			<select name="start_date_d"><? select_day('', $enquete->start_date_d) ?></select>日
			<select name="start_date_h"><? select_hour('', $enquete->start_date_h) ?></select>時
		</td>
	</tr>
	<tr>
		<td class="m1">終了日時<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="end_date_y"><? select_year(2001, '', $enquete->end_date_y) ?></select>年
			<select name="end_date_m"><? select_month('', $enquete->end_date_m) ?></select>月
			<select name="end_date_d"><? select_day('', $enquete->end_date_d) ?></select>日
			<select name="end_date_h"><? select_hour('', $enquete->end_date_h) ?></select>時
		</td>
	</tr>
	<tr>
		<td class="m1">付与ポイント</td>
		<td class="n1"><input class="number" type="text" name="point" size=4 maxlength=4 <?=value($enquete->point)?>></td>
	</tr>
	<tr>
		<td class="m1">結果通知先アドレス</td>
		<td class="n1">
			<textarea class="alpha" name="report_addr" cols=50 rows=4><?=htmlspecialchars(str_replace(',', "\n", $fetch->fe_report_addr))?></textarea><br>
			<span class="note">（１行に１アドレスずつ記入）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">実施状態<?=MUST_ITEM?></td>
		<td class="n1"><select name="status"><? select_enq_status($enquete->status) ?></select></td>
	</tr>
</table>
<?
if (is_array($enquete->question)) {
	$question_no = array_keys($enquete->question);
	$max_question = $question_no[count($question_no) - 1];
	for ($i = 1; $i <= $max_question; $i++) {
		$question = &$enquete->question[$i];
?>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td width="100%" colspan=2 style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">Ｑ<?=$i?></td>
	</tr>
	<tr>
		<td class="m1" width="20%">質問形式</td>
		<td class="n1" width="80%">
			<select name="question_type<?=$i?>">
				<option <?=value_selected('1', $question->question_type)?>>単一選択肢(SA)</option>
				<option <?=value_selected('2', $question->question_type)?>>複数選択肢(MA)</option>
			</select>
			　<input type="radio" name="top_page" <?=value_checked($i, $fetch->fe_top_page)?>>トップページに表示
		</td>
	</tr>
	<tr>
		<td class="m1">質問文</td>
		<td class="n1"><textarea name="question_text<?=$i?>" cols=65 rows=5><?=htmlspecialchars($question->question_text)?></textarea></td>
	</tr>
	<tr>
		<td class="m1">選択肢</td>
		<td class="n1">
<?
		if (is_array($question->sel_text)) {
			$sel_no = array_keys($question->sel_text);
			$max_sel = $sel_no[count($sel_no) - 1];
			for ($j = 1; $j <= $max_sel; $j++) {
?>
				<?=$j?> <input type="text" name="sel_text<?=$i?>[]" size=60 <?=value($question->sel_text[$j])?>> <input type="text" name="sel_text_s<?=$i?>[]" size=20 <?=value($question->sel_text_s[$j])?>><br>
<?
			}
		}
?>
			<span id="sel<?=$i?>"></span>
			<input type="button" value="選択肢追加" onclick="add_select(<?=$i?>)">
			<input type="hidden" name="sel_cnt<?=$i?>" value="<?=$max_sel?>">
		</td>
	</tr>
</table>
<?
	}
}
?>
<input type="hidden" name="question_cnt" value="<?=(int)$max_question?>">
<span id="question"></span>
<br>
<input type="button" value="質問追加" onclick="add_question()">
<input type="submit" value="プレビュー" onclick="document.form1.next_action.value='preview'">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="seq_no" value="<?=$seq_no?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
