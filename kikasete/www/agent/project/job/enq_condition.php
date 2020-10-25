<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:対象条件登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/search.php");
include("$inc/condition.php");
include("$inc/select.php");

//display状態
function check_disp($sel1, $sel2) {
	if ($sel1 >  $sel2)
		echo "block";
	else
		echo "none";
}

//定型処理チェック
function teikei_check($value, $checked='', $default='') {
	if (!$checked && $default)
		echo " checked";
	elseif ($checked) {
		if ($value == $checked)
			echo " checked";
	}
}

//定型処理選択肢表示
function disp_ans($parent_enq_id, $teikei_qno, &$es_sel_no, $idx) {
	if ($parent_enq_id && $teikei_qno) {
		$sql = "SELECT es_sel_no,es_sel_text FROM t_enq_select WHERE es_enquete_id=$parent_enq_id AND es_question_no=$teikei_qno ORDER BY es_sel_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$sel_no = $fetch->es_sel_no;
			echo " <nobr><input type='checkbox' name='teikei_ans[$idx][$sel_no]' ", value_checked($sel_no, $es_sel_no[$sel_no]), ">$sel_no:$fetch->es_sel_text</nobr>";
		}
	}
}

//クリックカンタ表示
function disp_click_counter($parent_job_id, $ct_id) {
	if ($ct_id) {
		$p_ct_ans = explode(",", $ct_id);
		foreach ($p_ct_ans as $val)
			$cc_ct_id[] = $val;
	}

	$sql = "SELECT cc_ct_id,cc_url_name FROM t_click_counter WHERE cc_job_id=$parent_job_id order by cc_ct_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$j = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo "<input type='checkbox' name='cc_ct_id[$i]' ", value_checked($fetch->cc_ct_id, $cc_ct_id[$j]), ">$fetch->cc_ct_id:$fetch->cc_url_name<br>";
		if ($fetch->cc_ct_id == $cc_ct_id[$i])
			$j++;
	}
}
//メイン処理
set_global2('project', 'プロジェクト管理', '対象条件', BACK_TOP);

$sql = "SELECT jb_job_type,jb_parent_job_id,jb_teikei_qno,jb_teikei_andor,jb_teikei_ans,jb_enquete_id,jb_ct_id,jb_search_id,jb_send_num,jb_virgin_mail,jb_virgin_day,jb_virgin_hour,jb_category FROM t_job WHERE jb_job_id=$job_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
$search_id = $fetch->jb_search_id;
$job_type = $fetch->jb_job_type;
$parent_job_id = $fetch->jb_parent_job_id;
$teikei_qno = $fetch->jb_teikei_qno;
$teikei_andor = $fetch->jb_teikei_andor;
$teikei_ans = $fetch->jb_teikei_ans;
$enquete_id = $fetch->jb_enquete_id;
$ct_id = $fetch->jb_ct_id;
$virgin_mail = $fetch->jb_virgin_mail;
$virgin_day = $fetch->jb_virgin_day;
$virgin_hour = $fetch->jb_virgin_hour;
$category = $fetch->jb_category;
$send_num = $fetch->jb_send_num;

$search = new search_class;
if ($search_id)
	$search->read_db($search_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function OnChange_timing(id, type) {
	timing.style.display = "none";
	if (type == 1)
		document.form1.all(id).style.display = "block";
}
function OnClick_search() {
	var f = document.form1;
	if (check_search_data(f)) {
		f.target = "_blank";
		f.action = "sub/search_result.php";
		f.submit();
	}
}
function reset_form1() {
	var f = document.form1;
	f.target = "";
	f.action = "./enq_condition.php";
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
	var i, check;
	if (f.send_num.value <= 0) {
		alert("最大配信数を入力してください。");
		f.send_num.focus();
		return(false);
	}
	if (!num_chk(f.send_num.value)){
		alert("最大配信数は半角数値で入力してください。");
		f.send_num.focus();
		return(false);
	}
	if (f.virgin_mail[1].checked || f.virgin_mail[2].checked) {
		if (f.virgin_day.value == "") {
			alert("指定日を入力してください。");
			f.virgin_day.focus();
			return(false);
		}
		if (f.virgin_hour.value == "") {
			alert("指定時間を入力してください。");
			f.virgin_hour.focus();
			return(false);
		}
	}
<? if ($job_type == 2) { ?>
	i = 0;
	check = false;
	while (f["cc_ct_id[" + i + "]"]) {
		if (f["cc_ct_id[" + i + "]"].checked) {
			check = true;
			break;
		}
		i++;
	}
	if (!check) {
		alert("クリックカウンタを設定してください。");
		if (f["cc_ct_id[0]"]) {
			f["cc_ct_id[0]"].focus();
			return false;
		}
	}
<? } ?>
	if (!check_search_data(f))
		return false;
	if (confirm("対象条件を登録します。よろしいですか？")) {
		f.target = "";
		f.next_action.value='condition'
		f.action = "enq_save.php";
		f.submit();
	}
}
function show_teikei() {
	var f = document.form1;
	var i = 0;
	while (f["teikei_qno[" + i + "]"]) {
		qno = f["teikei_qno[" + i + "]"].value;
		disp_teikei(qno, f["teikei_qno[" + i + "]"].checked);
		i++;
	}
}
function disp_teikei(qno, chk) {
	var f = document.form1;
	document.all["ta" + qno].style.display = chk ? "" : "none";
	document.all["ts" + qno].style.display = chk ? "" : "none";
}
//-->
</script>
<? search_script($search, $job_id) ?>
<? menu_script() ?>
</head>
<body onload="show_reset();show_teikei()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=800>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
			</table>
			<table class="small" border=0 cellspacing=2 cellpadding=2 width=800>
				<tr><td><br></td>
				</tr>
				<tr>
					<td class="m1" width=20%>最大配信数</td>
					<td class="n1" width=80%><input class="number" type="text" name="send_num" size=6 maxlength=6 <?=value($send_num)?>><font size=-1>人</font>
					<span class="note">（半角数字）</span></td>
				</tr>
				<tr>
					<td class="m1">バージンメール</td>
					<td class="n1">
						<input type="radio" name="virgin_mail" onclick="OnChange_timing('', 0)" <?=value_checked('',$virgin_mail)?>><font size=-1>なし</font>
						<input type="radio" name="virgin_mail" onclick="OnChange_timing('timing', 1)" <?=value_checked(1,$virgin_mail)?>><font size=-1>新規登録者のみ</font>
						<input type="radio" name="virgin_mail" onclick="OnChange_timing('timing', 1)" <?=value_checked(2,$virgin_mail)?>><font size=-1>既存会員+新規登録者</font><br>
						<span ID="timing" style="display:<?=check_disp($virgin_mail,0)?>;">
						<font size=-1>　新規会員への送信タイミング： 入会日から</font>
						<input type="text" class="number" name="virgin_day" size=3 maxlength=2 <?=value($virgin_day)?>><font size=-1>日後</font>
						<input type="text" class="number" name="virgin_hour" size=3 maxlength=2 <?=value($virgin_hour)?>><font size=-1> 時</font>
						</span>
					</td>
				</tr>
<?
if ($job_type == 1) { //アンケートレスポンスのみ表示
	if ($parent_job_id) {
		$sql = "SELECT jb_enquete_id,jb_teikei_ans FROM t_job WHERE jb_job_id=$parent_job_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$parent_enq_id = $fetch->jb_enquete_id;
		}
	}
?>
				<tr>
					<td class="m1">定型処理</td>
					<td class="n1">
						<table border=1 cellspacing=1 cellpadding=1 class="small" frame="box">
<?
	$sql = "SELECT eq_question_no,tk_teikei_qno,tk_teikei_andor,tk_teikei_ans"
			. " FROM t_enq_question LEFT JOIN t_teikei ON tk_job_id=$job_id AND tk_teikei_qno=eq_question_no"
			. " WHERE eq_enquete_id=$parent_enq_id AND (eq_question_type=1 OR eq_question_type=2)"
			. " ORDER BY eq_question_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$teikei_qno = $fetch->eq_question_no;
		$teikei_andor = $fetch->tk_teikei_andor;
		$teikei_ans = $fetch->tk_teikei_ans;
		$es_sel_no = array();
		if ($teikei_ans) {
			$p_teikei_ans = explode(",", $teikei_ans);
			foreach ($p_teikei_ans as $val)
				$es_sel_no[$val] = true;
		}
?>
							<tr valign="top">
								<td width="5%"><input type="checkbox" name="teikei_qno[<?=$i?>]" <?=value_checked($teikei_qno,$fetch->tk_teikei_qno)?> onclick="disp_teikei(<?=$teikei_qno?>,checked)"><nobr>Ｑ<?=mb_convert_kana($fetch->eq_question_no, 'N')?>&nbsp;</nobr></td>
								<td width="15%"><span id="ta<?=$teikei_qno?>"><nobr>
									<input type="radio" name="teikei_andor[<?=$i?>]" value='O'<?=teikei_check('O',$teikei_andor,1)?>>OR
									<input type="radio" name="teikei_andor[<?=$i?>]" value='A'<?=teikei_check('A',$teikei_andor)?>>AND
								&nbsp;</nobr></span></td>
								<td width="80%"><span id="ts<?=$teikei_qno?>">
<?
		disp_ans($parent_enq_id, $teikei_qno, $es_sel_no, $i);
?>
								</span></td>
							</tr>
<?
	}
?>
						</table>
					</td>
				</tr>
<?
} elseif ($job_type == 2) { //クリックレスポンスのみ表示
?>
				<tr>
					<td class="m1">クリックカウンタ</td>
					<td class="n1">
<?
	disp_click_counter($parent_job_id, $ct_id);
?>
					</td>
				</tr>
<?
}
?>
			</table>
			<table class="small" border=0 cellspacing=2 cellpadding=2 width=800>
				<tr>
					<td class="m0" align="left">■対象条件を設定して下さい</td>
				</tr>
			</table>
			<table class="small" border=0 cellspacing=2 cellpadding=2 width=800>
			<? search_condition($search, 'small') ?>
			</table><br>
			<table class="small2" border=0 cellspacing=3 cellpadding=0 width=800>
			<div align="center">
			<input type="hidden" name="search_id" <?=value($search_id)?>>
			<input type="hidden" name="job_id" <?=value($job_id)?>>
			<input type="hidden" name="job_type" <?=value($job_type)?>>
			<input type="hidden" name="parent_enq_id" <?=value($parent_enq_id)?>>
			<input type="hidden" name="parent_job_id" <?=value($parent_job_id)?>>
			<input type="hidden" name="next_action">
			<input type="hidden" name="haimail_flag" <?=value($category == 2 ? DBTRUE : '')?>>
			<input type="button" value="上記内容で登録" onclick="OnClick_regist()">
			<input type="button" value="リセット" onclick="reset_form1()">
			<input type="button" value="人数検索" onclick="OnClick_search()">
			<input type="button" value="　戻る　" onclick="location.href='jb_contents.php?job_id=<?=$job_id?>'">
			</div>
			</table>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
