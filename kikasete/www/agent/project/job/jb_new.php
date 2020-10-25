<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:ジョブ情報 新規登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

// アンケート種類選択肢
function select_enq_type($default, $sel) {
	if ($default)
		echo "<option value='0'>$default</option>\n";

	echo "<option ", value_selected('1', $sel), ">メールアンケート</option>";
	echo "<option ", value_selected('2', $sel), ">Webアンケート</option>";
}

// メール形式選択肢
function select_mail_format($default, $sel) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('0', $sel), ">テキスト</option>";
	echo "<option ", value_selected('1', $sel), ">HTML</option>";
}

// 親ジョブ選択肢
function select_parent_job($pjt_id, $category) {
	$sql = "SELECT jb_job_id,jb_job_name FROM t_job WHERE jb_project_id=$pjt_id AND jb_category=$category ORDER BY jb_job_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	echo "c.length = " . ($nrow + 1) . ";\n";
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$index = $i + 1;
		echo "c.options[$index].text = '$fetch->jb_job_name';\n";
		echo "c.options[$index].value = $fetch->jb_job_id;\n";
	}
}

//メイン処理

set_global2('project', 'プロジェクト管理', 'ジョブ新規登録', BACK_TOP);

$sql = "SELECT pj_project_name FROM t_project WHERE pj_project_id=$pjt_id";
$pj_name = db_fetch1($sql);

if ($cpy_job_id) {
	$sql = "SELECT jb_job_name,jb_job_type,jb_mail_format,jb_parent_job_id,jb_send_timing,jb_send_timing_h,jb_send_timing_d,jb_cp_job_id,jb_category,en_enq_type"
		. " FROM t_job LEFT JOIN t_enquete ON en_enquete_id=jb_enquete_id WHERE jb_job_id=$cpy_job_id";
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
} else {
	$fetch->jb_category = 1;
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
function onLoad_body() {
	var f = document.form1;
	document.form1.job_name.focus();
	Change_enq_type();
	OnChange_parent_job();
	OnChange_job_type();
	set_parent_job(f.parent_job_id0, <?=$fetch->jb_cp_job_id ? $fetch->jb_cp_job_id : 0?>);
	set_parent_job(f.parent_job_id1, <?=$fetch->jb_parent_job_id ? $fetch->jb_parent_job_id : 0?>);
	set_parent_job(f.parent_job_id2, <?=$fetch->jb_parent_job_id ? $fetch->jb_parent_job_id : 0?>);
}
function Change_enq_type() {
	if (document.form1.enq[0].checked)
		enq_type.style.display = "block";
	else {
		enq_type.style.display = "none";
		document.form1.enq_type.selectedIndex = 0;
	}
}
function OnChange_parent_job() {
	if (document.form1.parent_job[0].checked)
		parent_job0.style.display = "block";
	else
		parent_job0.style.display = "none";
}
function OnChange_job_type() {
	parent_job1.style.display = "none";
	parent_job2.style.display = "none";
	if (document.form1.job_type[1].checked)
		parent_job1.style.display = "block";
	else if (document.form1.job_type[2].checked)
		parent_job2.style.display = "block";
}
function job_copy() {
  var win;
  win = window.open("sub/job_select.php", "job_list", "scrollbars=yes,resizable=yes,width=600");
  win.focus();
}
function CopyJob(job_id) {
	location.replace("../jb_new.php?pjt_id=<?=$pjt_id?>&cpy_job_id=" + job_id);
}
function onSubmit_form1(f) {
	if (f.job_name.value == "") {
		alert("ジョブ名を入力してください。");
		f.job_name.focus();
		return false;
	}
	if (f.enq[0].checked && f.enq_type.value == "0") {
		alert("アンケート種類を選択してください。");
		f.enq_type.focus();
		return false;
	}
	if (f.parent_job[0].checked && f.parent_job_id0.value == "") {
		alert("親ジョブを指定してください。");
		f.parent_job_id0.focus();
		return false;
	}
	if (f.job_type[1].checked) {
		if (f.parent_job_id1.value == "") {
			alert("親ジョブを指定してください。");
			f.parent_job_id1.focus();
			return false;
		}
	} else if (f.job_type[2].checked) {
		if (f.parent_job_id2.value == "") {
			alert("親ジョブを指定してください。");
			f.parent_job_id2.focus();
			return false;
		}
	}
	return confirm("ジョブ情報を登録します。よろしいですか？");
}
function onclick_category() {
	var f = document.form1;
	set_parent_job(f.parent_job_id0, 0);
	set_parent_job(f.parent_job_id1, 0);
	set_parent_job(f.parent_job_id2, 0);
}
function set_parent_job(c, sel) {
	var f = document.form1;
	if (f.category[0].checked) {
<?
select_parent_job($pjt_id, 1);
?>
	} else if (f.category[1].checked) {
<?
select_parent_job($pjt_id, 2);
?>
	}
	for (var i = 0; i < c.length; i++) {
		if (c[i].value == sel) {
			c.selectedIndex = i;
			break;
		}
	}
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="onLoad_body()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="jb_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center"><?=$g_title?></td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">■プロジェクト情報</td>
	</tr>
	<tr>
		<td class="m1" width=140>プロジェクトID</td>
		<td class="n1"><?=$pjt_id?></td>
	</tr>
	<tr>
		<td class="m1">プロジェクト名</td>
		<td class="n1"><?=htmlspecialchars($pj_name)?></td>
	</tr>
</table><br>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">■ジョブ情報</td>
	</tr>
	<tr>
		<td class="m1" width=140>ジョブ名</td>
		<td class="n1"><input class="kanji" type="text" name="job_name" size=50 maxlength=50 <?=value($fetch->jb_job_name)?>>
		<span class="note">（全角５０文字まで）</span></td>
	</tr>
	<tr>
		<td class="m1">配信カテゴリ</td>
		<td class="n1">
			<input type="radio" name="category" <?=value_checked(1, $fetch->jb_category)?> onclick="onclick_category()">きかせて（主にリサーチ）<br>
			<input type="radio" name="category" <?=value_checked(2, $fetch->jb_category)?> onclick="onclick_category()">はいめーる（主にプロモーション）
		</td>
	</tr>
	<tr>
		<td class="m1">アンケート</td>
		<td class="n1">
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td><input type="radio" name="enq" onclick="Change_enq_type()" value=1 <?=checked(!$cpy_job_id || $fetch->en_enq_type)?>>あり</td>
					<td><span ID="enq_type" style="display:none;">
					<font size=-1 class="note">アンケート種類：</font>
					<select name="enq_type"><? select_enq_type('- 選択して下さい -', $fetch->en_enq_type)?></span></td>
				</td>
				<tr>
					<td width=60><input type="radio" name="enq" onclick="Change_enq_type()" value=0 <?=checked($cpy_job_id && !$fetch->en_enq_type)?>>なし</td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">親ジョブ指定</td>
		<td class="n1">
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td><nobr><input type="radio" name="parent_job" onclick="OnChange_parent_job()" value=1 <?=checked($fetch->jb_cp_job_id)?>>あり</nobr></td>
					<td><span ID="parent_job0" style="display:none;">
						<nobr><font size=-1 class="note">　親JOB指定：</font><select name="parent_job_id0"><option value="">- 選択して下さい -</option></select></nobr>
						</span>
					</td>
				</tr>
				<tr>
					<td><input type="radio" name="parent_job" onclick="OnChange_parent_job()" value=0 <?=checked(!$fetch->jb_cp_job_id)?>>なし<br></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">ジョブ種別</td>
		<td class="n1">
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td><input type="radio" name="job_type" onclick="OnChange_job_type()" <?=value_checked(0, $fetch->jb_job_type)?>>発信JOB<br></td>
					<td></td>
				</tr>
				<tr>
					<td><nobr><input type="radio" name="job_type" onclick="OnChange_job_type()" <?=value_checked(1, $fetch->jb_job_type)?>>アンケートレスポンスJOB</nobr></td>
					<td><span ID="parent_job1" style="display:none;">
						<nobr><font size=-1 class="note">　親JOB指定：</font><select name="parent_job_id1"><option value="">- 選択して下さい -</option></select></nobr>
						</span>
					</td>
				</tr>
				<tr>
					<td><nobr><input colspan=2 type="radio" name="job_type" onclick="OnChange_job_type()" <?=value_checked(2, $fetch->jb_job_type)?>>クリックレスポンスJOB</nobr></td>
					<td><span ID="parent_job2" style="display:none;">
						<nobr><font size=-1 class="note">　親JOB指定：</font><select name="parent_job_id2"><option value="">- 選択して下さい -</option></select></nobr>
						</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">発信通知先アドレス</td>
		<td class="n1">
<?
if ($cpy_job_id) {
	$sql = "SELECT sn_mail_adr FROM t_send_notice WHERE sn_job_id='$cpy_job_id'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$send_adr[$i] = $fetch->sn_mail_adr;
	}
} else {
	$send_adr[0] = $agent_mail_addr;
}
?>
		<input class="alpha" type="text" name="sn_mail_adr[0]" size=50 maxlength=50 <?=value($send_adr[0])?>><br>
		<input class="alpha" type="text" name="sn_mail_adr[1]" size=50 maxlength=50 <?=value($send_adr[1])?>><br>
		<input class="alpha" type="text" name="sn_mail_adr[2]" size=50 maxlength=50 <?=value($send_adr[2])?>><br>
		<input class="alpha" type="text" name="sn_mail_adr[3]" size=50 maxlength=50 <?=value($send_adr[3])?>>
		<span class="note">（半角１００文字まで）</span></td>
	</tr>
</table>
<table width=700>
	<div align="center">
	<input type="hidden" name="cpy_job_id" <?=value($cpy_job_id)?>>
	<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
	<input type="hidden" name="next_action">
	<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
	<input type="button" value="ジョブコピー" OnClick='JavaScript:job_copy()'>
	<input type="reset" value="リセット" onclick="onLoad_body()">
	<input type="button" value="　戻る　" onclick='location.href="list.php"'>
	</div>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
