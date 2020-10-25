<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:登録情報更新
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");
include("$inc/format.php");

// エージェント識別
function select_agent_disc($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>○○○○○○社員</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>バイター</option>', "\n";
	echo '<option ', value_selected('3', $selected), '>協力会社社員</option>', "\n";
}

// エージェント状態選択肢
function select_agent_status($sel) {
	echo "<option ", value_selected('0', $sel), ">有効</option>";
	echo "<option ", value_selected('9', $sel), ">無効</option>";
}

// エージェント種別選択肢
function select_agent_type($sel) {
	echo "<option ", value_selected('1', $sel), ">エージェント</option>";
	echo "<option ", value_selected('2', $sel), ">サブエージェント</option>";
}
//display状態
function check_disp($sel) {
	if ($sel == 2)
		echo "block";
	else
		echo "none";
}
//BCC情報表示
function ag_bcc_tok($bcc_addr) {
	$ag_bcc = strtok($bcc_addr, ",");
	while($ag_bcc) {
		echo "$ag_bcc\r\n";
		$ag_bcc = strtok(",");
	}
}
// メイン処理
set_global('登録情報更新', BACK_TOP);

$sql = "SELECT * FROM t_agent WHERE ag_agent_id=$agent_login_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('エージェントＩＤが不正', __FILE__);
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
function OnChange_agent_type() {
	if (document.form1.type.value == 2) {
		main_agent.style.display = "block";
	} else {
		main_agent.style.display = "none";
	}
}

function onSubmit_form1(f) {
	if (f.name1.value == "") {
		alert("名前（漢字）を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name1.value == "") {
		alert("名前（漢字）を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("名前（漢字）を入力してください。");
		f.name2.focus();
		return false;
	}
	if (f.name1_kana.value == "") {
		alert("名前（カナ）を入力してください。");
		f.name1_kana.focus();
		return false;
	}
	if (f.name2_kana.value == "") {
		alert("名前（カナ）を入力してください。");
		f.name2_kana.focus();
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
	if (f.agent_disc.value == "") {
		alert("エージェント識別を選択してください。");
		f.agent_disc.focus();
		return false;
	}
	if (!f.disp_flag[0].checked && !f.disp_flag[1].checked) {
		alert("表示状態を選択してください。");
		return false;
	}
	return confirm("登録情報を更新します。よろしいですか？");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m4" align="center" colspan=2><?=$g_title?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2 align="left">■基本情報</td>
	</tr>
	<tr>
		<td class="m1" width="20%">名前（漢字）<?=MUST_ITEM?></td>
		<td class="n1">
			姓<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->ag_name1)?>>
			<span class="note">（全角２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->ag_name2)?>>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）<?=MUST_ITEM?></td>
		<td class="n1">
			姓<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->ag_name1_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->ag_name2_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">生年月日</td>
		<td class="n1">
			西暦<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->ag_birthday))?>>年
			<select name="birthday_m"><? select_month('', get_datepart('M', $fetch->ag_birthday))?></select>月
			<select name="birthday_d"><? select_day('', get_datepart('D', $fetch->ag_birthday))?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">性別</td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->ag_sex)?>>男性
			<input type="radio" name="sex" <?=value_checked(2, $fetch->ag_sex)?>>女性
		</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->ag_mail_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->ag_password)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">エージェント識別<?=MUST_ITEM?></td>
		<td class="n1"><select name="agent_disc"><? select_agent_disc('', $fetch->ag_agent_disc)?></select></td>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■勤務先情報</td>
	</tr>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50 <?=value($fetch->ag_kinmu_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">部署名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50 <?=value($fetch->ag_kinmu_unit)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">役職</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50 <?=value($fetch->ag_kinmu_post)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">エージェント所属部署</td>
		<td class="n1">
			<input class="kanji" type="text" name="agent_unit" size=50 maxlength=50 <?=value($fetch->ag_agent_unit)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■設定情報</td>
	</tr>
	<tr>
		<td class="m1">自己紹介文</td>
		<td class="n1"><textarea class="kanji" name="self_introduction" cols=60 rows=5><?=htmlspecialchars($fetch->ag_self_introduction)?></textarea>
		<span class="note"><nobr>（全角５００文字まで）</nobr></span>
		</td>
	</tr>
	<tr>
		<td class="m1">表示状態<?=MUST_ITEM?></td>
		<td class="n1">
		<input type="radio" name="disp_flag" <?=value_checked('t', $fetch->ag_disp_flag)?>>表示
		<input type="radio" name="disp_flag" <?=value_checked('f', $fetch->ag_disp_flag)?>>非表示
		</td>
	</tr>
	<tr>
		<td class="m1">ＢＣＣ設定</td>
		<td class="n1"><textarea class="alpha" name="bcc_addr" cols=60 rows=3><?=ag_bcc_tok($fetch->ag_bcc_addr)?></textarea>
		<span class="note"><nobr>（半角英数：複数指定可）</nobr></span>
		</td>
	</tr>
	<tr>
		<td class="m1">署名</td>
		<td class="n1"><textarea class="kanji" name="signature" cols=60 rows=5><?=htmlspecialchars($fetch->ag_signature)?></textarea>
		<span class="note"><nobr>（全角１００文字まで）</nobr></span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■その他情報</td>
	</tr>
	<tr>
		<td class="m1">登録日時</td>
		<td class="n1"><?=format_datetime($fetch->ag_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">最新更新日時</td>
		<td class="n1"><?=format_datetime($fetch->ag_update_date)?></td>
	</tr>
</table>
<br>
<div align="center">
	<input type="submit" value="　更新　">
	<input type="button" value="　戻る　" onclick="history.back()">
</div>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
