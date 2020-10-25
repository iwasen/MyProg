<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:質問文マスタ登録
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");

// アンケート種別選択肢
function select_enq_type($sel) {
	echo "<option ", value_selected('1', $sel), ">単一選択(SA)</option>";
	echo "<option ", value_selected('2', $sel), ">複数回答(MA)</option>";
	echo "<option ", value_selected('3', $sel), ">自由回答(FA)</option>";
}

//メイン処理

set_global2('project', 'プロジェクト管理｜テンプレート編集', '質問文テンプレート登録', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	document.form1.pq_title.focus();
}
function OnClick_answer() {
	var f = document.form1;
	if (f.en_count.value <= 0) {
		alert("質問数を入力してください。");
		f.en_count.focus();
		return(false);
	}
	f.submit();
}
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function OnClick_regist() {
	var f = document.form1;
	if (f.pq_title.value == "") {
		alert("タイトルを入力してください。");
		f.pq_title.focus();
		return false;
	}
	if (f.en_count.value <= 0) {
		alert("質問数を入力してください。");
		f.en_count.focus();
		return(false);
	}
	if (confirm("アンケート内容を登録します。よろしいですか？")) {
//		f.target = "";
		f.next_action.value='new';
		f.action = "update.php";
		f.submit();
	}
}

//-->
</script>
<? menu_script() ?>
</head>
<body onload="onLoad_body()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center"><?=$g_title?></td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=0 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">■マスタ情報を設定して下さい。</td>
	</tr>
	<tr>
		<td class="m1" width=140>タイトル</td>
		<td class="n1"><input type="text" name="pq_title" size=70 maxlength=100 <?=value($pq_title)?>></td>
	</tr>
<?
if (!$en_count) { $en_count = 3; }
?>
	<tr>
		<td class="m1" width=140>質問数</td>
		<td class="n1"><input type="text" class="number" name="en_count" size=4 maxlength=2 <?=value($en_count)?>>問
		<input type="button" value="変更" OnClick='JavaScript:OnClick_answer()'>
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=0 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">■質問内容を設定して下さい。</td>
	</tr>
</table>
<?
for ($i = 0; $i < $en_count; $i++) {
	$qno = $i + 1;
?>
<table border=1 cellspacing=0 cellpadding=2 width=700>
	<tr>
		<td valign="top" class="q<?=$i % 3?>" width=140>【問<?=$qno?>】質問文</td>
		<td colspan=2>
			<textarea name="eq_question_text[<?=$qno?>]" cols=72 rows=3><?=htmlspecialchars($eq_question_text[$qno])?></textarea>
		</td>
	</tr>
	<tr>
		<td valign="top" class="q<?=$i % 3?>" rowspan=11>【問<?=$qno?>】選択肢<br><br>（自由回答形式を指定した場合は、選択肢を空欄にして下さい。）</td>
		<td>
			<table border=0>
				<tr>
					<td>
						<table border=0>
							<tr>
								<td></td>
								<td>
									<select name="eq_question_type[<?=$qno?>]"><? select_enq_type($eq_question_type[$qno])?></select>
								</td>
							</tr>
<?
	for ($n = 0; $n < 10; $n++) {
		$sno = $n + 1;
?>
							<tr>
								<td align="right"><?=$sno?>.</td>
								<td><input type="text" name="es_sel_text[<?=$qno?>][<?=$sno?>]" size=45 maxlength=45 <?=value($es_sel_text[$qno][$sno])?>></td>
							</tr>
<?
	}
?>
						</table>
					</td>
					<td>
						<table border=0>
							<tr>
								<td>　</td>
								<td></td>
							</tr>
<?

	for ($n = 10; $n < 20; $n++) {
		$sno = $n + 1;
?>
							<tr>
								<td><?=$sno?>.</td>
								<td><input type="text" name="es_sel_text[<?=$qno?>][<?=$sno?>]" size=45 maxlength=50 <?=value($es_sel_text[$qno][$sno])?>></td>
							</tr>
<?
	}
?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
}
?>
<br>
<table width=700>
	<div align="center">
	<input type="hidden" name="next_action">
	<input type="button" value="　登録　" OnClick='JavaScript:OnClick_regist()'>
	<input type="reset" value="リセット">
	<input type="button" value="　戻る　" onclick="location.href='list.php'">
	</div>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
