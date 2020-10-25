<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:アンケート内容登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");

// アンケート種別選択肢
function select_enq_type($sel,$default = '　-未設定-') {
	echo "<option value=''>$default</option>";
	echo "<option ", value_selected('1', $sel), ">単一選択(SA)</option>";
	echo "<option ", value_selected('2', $sel), ">複数回答(MA)</option>";
	echo "<option ", value_selected('3', $sel), ">自由回答(FA)</option>";
}

//メイン処理

set_global2('project', 'プロジェクト管理', 'アンケート内容', BACK_TOP);

if (!$en_count) {
	$sql = "SELECT en_enquete_id,en_enq_type,en_title,en_description,jb_enq_web_url FROM t_enquete LEFT JOIN t_job ON en_job_id=jb_job_id WHERE en_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$en_enquete_id = $fetch->en_enquete_id;
		$en_enq_type = $fetch->en_enq_type;
		$en_title = $fetch->en_title;
		$en_description = $fetch->en_description;
		$jb_enq_web_url = $fetch->jb_enq_web_url;
	}
}

if ($en_enq_type == 1 && (!$en_count || $copy_id)) {
	if ($en_enquete_id)
		$enq_id = $en_enquete_id;
	if ($copy_id)
		$enq_id = $copy_id;

	$sql = "SELECT eq_question_no,eq_question_text,eq_question_type,eq_fa_flag FROM t_enq_question WHERE eq_enquete_id=$enq_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$qno = $fetch->eq_question_no;
		$eq_question_text[$qno] = $fetch->eq_question_text;
		$eq_question_type[$qno] = $fetch->eq_question_type;
		$eq_fa_flag[$qno] = $fetch->eq_fa_flag;

		$sql = "SELECT es_sel_no,es_sel_text FROM t_enq_select WHERE es_enquete_id=$enq_id AND es_question_no=$qno";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);
			$sno = $fetch2->es_sel_no;
			$es_sel_text[$qno][$sno] = $fetch2->es_sel_text;
		}
		$es_sel_num[$qno] = max($nrow2, 10);
	}

	$en_count = $nrow ? $nrow : 3;
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
function pjt_mail() {
  var win;
  win = window.open("sub/tmp_mail.php", "list", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
function pjt_question() {
  var win;
  win = window.open("sub/tmp_question.php", "list", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
function CopyQuestion(enquete_id) {
	var f = document.form1;
	f.action = "enq_contents.php?job_id=<?=$job_id?>&copy_id=" + enquete_id;
	f.submit();
}
function onclick_kakunin() {
  var win;
	if (document.form1.jb_enq_web_url.value) {
	  win = window.open(document.form1.jb_enq_web_url.value, "list");
	  win.focus();
	}
}
function OnClick_answer() {
	var f = document.form1;
	if (f.en_count.value == "") {
		alert("質問数を入力してください。");
		f.en_count.focus();
		return(false);
	}
	f.submit();
}
function clear_mail() {
	document.form1.en_description.value = "";
}
function clear_question(count) {
	var f = document.form1;
	for (i=1; i<=count; i++) {
		f("eq_question_text[" + i + "]").value = "";
		f("eq_question_type[" + i + "]").value = "";
		for (j=1; ; j++) {
			if (f("es_sel_text[" + i + "][" + j + "]"))
				f("es_sel_text[" + i + "][" + j + "]").value = "";
			else
				break;
		}
	}
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
	if (f.en_title.value <= 0) {
		alert("タイトルを入力してください。");
		f.en_title.focus();
		return(false);
	}
	for (i = 1; f("eq_question_text[" + i + "]"); i++){
		if (f("eq_question_text[" + i + "]").value != "" && f("eq_question_type[" + i + "]").value == "") {
			alert("タイプを選択してください。");
			f("eq_question_type[" + i + "]").focus();
			return(false);
		}
	}
	if (confirm("アンケート内容を登録します。よろしいですか？")) {
		f.target = "";
		f.next_action.value='contents';
		f.action = "enq_save.php";
		f.submit();
	}
}

//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m0" colspan=2 align="left">■タイトル、説明文を設定して下さい。</td>
				</tr>
				<tr>
					<td class="m1" width=140>タイトル（テーマ）</td>
					<td class="n1"><input class="kanji" type="text" name="en_title" size=90 maxlength=100 <?=value($en_title)?>>
					</td>
				</tr>
				<tr>
					<td class="m1">説明文</td>
					<td class="n1">
						<textarea class="kanji" name="en_description" cols=72 rows=8><?=htmlspecialchars($en_description)?></textarea>
					</td>
				</tr>
<?
if ($en_enq_type == 2) { //Webアンケートのみ
?>
				<tr>
					<td class="m1">アンケートWebのURL</td>
					<td class="n1"><input class="alpha" type="text" name="jb_enq_web_url" size=90 maxlength=100 <?=value($jb_enq_web_url)?>>
					<input type="button" value="確認" onclick="onclick_kakunin()">
					</td>
				</tr>
			</table>
<?
} elseif ($en_enq_type == 1) { //メールアンケートのみ
?>
				<tr>
					<td class="m1">質問数</td>
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
					<td valign="top" class="q<?=$i % 3?>" rowspan=11>【問<?=$qno?>】選択肢</td>
					<td>
						<table border=0>
							<tr>
								<td valign="top" width="50%">
									<table border=0>
										<tr>
											<td></td>
											<td>
												<select name="eq_question_type[<?=$qno?>]"><? select_enq_type($eq_question_type[$qno])?></select>
											</td>
										</tr>
<?
		if ($es_sel_num[$qno] == '')
			$es_sel_num[$qno] = 10;
		$es_sel_num[$qno] = floor(($es_sel_num[$qno] + 1) / 2) * 2;

		for ($sno = 1; $sno <= $es_sel_num[$qno] / 2; $sno++) {
?>
										<tr>
											<td align="right"><?=$sno?>.</td>
											<td><input type="text" name="es_sel_text[<?=$qno?>][<?=$sno?>]" size=44 maxlength=45 <?=value($es_sel_text[$qno][$sno])?>></td>
										</tr>
<?
		}
?>
									</table>
								</td>
								<td valign="top" width="50%">
									<table border=0>
										<tr>
											<td></td>
											<td><font size="-1">選択肢数<input type="text" name="es_sel_num[<?=$qno?>]" size=4 value="<?=$es_sel_num[$qno]?>">個 <input type="submit" value="変更"></font></td>
										</tr>
<?
		for ( ; $sno <= $es_sel_num[$qno]; $sno++) {
?>
										<tr>
											<td align="right"><?=$sno?>.</td>
											<td><input type="text" name="es_sel_text[<?=$qno?>][<?=$sno?>]" size=44 maxlength=50 <?=value($es_sel_text[$qno][$sno])?>></td>
										</tr>
<?
		}
?>
									</table>
								</td>
							</tr>
							<tr>
								<td>　<font size="-1"><input type="checkbox" name="eq_fa_flag[<?=$qno?>]" <?=value_checked('t', $eq_fa_flag[$qno])?>>その他フリー回答を取得</font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
<?
	}
}
?>
			<br>
			<div align="center">
				<input type="hidden" name="eq_enquete_id" <?=value($eq_enquete_id)?>>
				<input type="hidden" name="en_enquete_id" <?=value($en_enquete_id)?>>
				<input type="hidden" name="job_id" <?=value($job_id)?>>
				<input type="hidden" name="en_enq_type" <?=value($en_enq_type)?>>
				<input type="hidden" name="next_action">
				<input type="button" value="　登録　" OnClick='JavaScript:OnClick_regist()'>
				<input type="reset" value="リセット">
				<input type="button" value="　戻る　" onclick="location.href='jb_contents.php?job_id=<?=$job_id?>'">
			</div>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
