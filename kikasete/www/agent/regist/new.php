<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:エージェントMyページ新規登録表示
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");

// エージェント識別
function select_agent_disc($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>○○○○○○社員</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>バイター</option>', "\n";
	echo '<option ', value_selected('3', $selected), '>協力会社社員</option>', "\n";
}

//メイン処理

set_global('Myページ新規登録','');

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
	document.form1.job_name.focus();
}
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
	if (f.mail_addr_copy.value == "") {
		alert("メールアドレス確認を入力してください。");
		f.mail_addr_copy.focus();
		return false;
	}
	if (f.mail_addr.value != f.mail_addr_copy.value) {
		alert("メールアドレスが一致していません。");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("パスワードを入力してください。");
		f.password.focus();
		return false;
	}
	if (f.password_copy.value == "") {
		alert("パスワード確認を入力してください。");
		f.password.focus();
		return false;
	}
	if (f.password.value != f.password_copy.value) {
		alert("パスワードが一致していません。");
		f.password_copy.focus();
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
	return confirm("エージェント登録情報を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload='document.form1.name1.focus();'>

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="regist.php" onsubmit="return onSubmit_form1(this)">
<table>
	<tr>
		<td>
			<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr><td align="center" class="m4"><b>登録情報入力画面</b></td></tr>
			</table>
			<table width=100%>
				<tr>
					<td class="m0" colspan=2 align="left">■基本情報</td>
				</tr>
			</table>
			<table border=1 cellspacing=0 cellpadding=3 width=100%>
				<tr width=>
					<td class="m1" width=200>名前（漢字）<?=MUST_ITEM?></td>
					<td class="n1">
						姓<input class="kanji" type="text" name="name1" size=25 maxlength=25>
						<span class="note">（全角２５文字まで）</span>
						<br>
						名<input class="kanji" type="text" name="name2" size=25 maxlength=25>
						<span class="note">（全角２５文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">名前（カナ）<?=MUST_ITEM?></td>
					<td class="n1">
						姓<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25>
						<span class="note">（全角カタカナ２５文字まで）</span>
						<br>
						名<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25>
						<span class="note">（全角カタカナ２５文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">生年月日</td>
					<td class="n1">
						西暦<input class="number" type="text" name="birthday_y" size=4>年
						<select name="birthday_m"><?select_month(' ', '')?></select>月
						<select name="birthday_d"><?select_day(' ', '')?></select>日
					</td>
				</tr>
				<tr>
					<td class="m1">性別</td>
					<td class="n1">
						<input type="radio" name="sex" value=1>男性
						<input type="radio" name="sex" value=2>女性
					</td>
				</tr>
				<tr>
					<td class="m1">メールアドレス<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100>
						<span class="note">（半角１００文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">メールアドレス確認<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="alpha" type="text" name="mail_addr_copy" size=50 maxlength=100>
						<span class="note"><nobr>（確認のため、もう一度メールアドレスを入力してください。）</nobr></span>
					</td>
				</tr>
				<tr>
					<td class="m1">パスワード<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="alpha" type="password" name="password">
						<span class="note">（半角英数字６〜２０文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">パスワード確認<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="alpha" type="password" name="password_copy">
						<span class="note">（確認のため、もう一度パスワードを入力してください。）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">エージェント識別<?=MUST_ITEM?></td>
					<td class="n1"><select name="agent_disc"><? select_agent_disc('選択して下さい', '')?></select></td>
					</td>
				</tr>
			</table>
			<table width=100%>
				<tr>
					<td class="m0" colspan=2>■勤務先情報</td>
				</tr>
			</table>
			<table border=1 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="m1" width=200>企業名</td>
					<td class="n1">
						<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50>
						<span class="note">（全角５０文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">部署名</td>
					<td class="n1">
						<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50>
						<span class="note">（全角５０文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">役職</td>
					<td class="n1">
						<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50>
						<span class="note">（全角５０文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">エージェント所属部署</td>
					<td class="n1">
						<input class="kanji" type="text" name="agent_unit" size=50 maxlength=50>
						<span class="note">（全角５０文字まで）</span>
					</td>
				</tr>
			</table>
			<table width=100%>
				<tr>
					<td class="m0" colspan=2>■設定情報</td>
				</tr>
			</table>
			<table border=1 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="m1" width=200>自己紹介文</td>
					<td class="n1"><textarea class="kanji" name="self_introduction" cols=60 rows=5></textarea>
					<span class="note"><nobr>（全角５００文字まで）</nobr></span>
					</td>
				</tr>
				<tr>
					<td class="m1">表示状態<?=MUST_ITEM?></td>
					<td class="n1">
					<input type="radio" name="disp_flag" value='t'>表示
					<input type="radio" name="disp_flag" value='f'>非表示
					</td>
				</tr>
				<tr>
					<td class="m1">ＢＣＣ設定</td>
					<td class="n1"><textarea class="alpha" name="bcc_addr" cols=60 rows=3></textarea>
					<span class="note"><nobr>（半角英数：複数指定可）</nobr></span>
					</td>
				</tr>
				<tr>
					<td class="m1">署名</td>
					<td class="n1"><textarea class="kanji" name="signature" cols=60 rows=5></textarea>
					<span class="note"><nobr>（全角１００文字まで）</nobr></span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="agent_id" <?=value($agent_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　登録　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
