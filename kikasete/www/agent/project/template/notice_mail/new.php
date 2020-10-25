<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:プロジェクト用告知メールテンプレート登録
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

//メイン処理

set_global2('project', 'プロジェクト管理｜テンプレート編集', '告知メールテンプレート登録', BACK_TOP);
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
	if (f.pm_title.value == "") {
		alert("テンプレート名を入力してください。");
		f.pm_title.focus();
		return false;
	}
	if (f.pm_body.value == "") {
		alert("本文を入力してください。");
		f.pm_body.focus();
		return false;
	}
	return confirm("告知メールテンプレートをを登録します。よろしいですか？");
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="document.form1.pm_title.focus()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center"><?=$g_title?></td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=0 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">■テンプレート名を入力して下さい。</td>
	</tr>
	<tr>
		<td class="m1" width=140>タイトル</td>
		<td class="n1"><input type="text" name="pm_title" size=90 maxlength=100>
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=0 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">■テンプレートの内容を入力して下さい。</td>
	</tr>
	<tr>
		<td class="m1" width=140>本文</td>
		<td class="n1">
			<textarea name="pm_body" cols=72 rows=20></textarea>
		</td>
	</tr>
</table>
<br>
<table width=700>
	<div align="center">
	<input type="hidden" name="next_action" value='new'>
	<input type="submit" value="　登録　">
	<input type="reset" value="リセット">
	<input type="button" value="　戻る　" onclick="location.href='list.php'">
	</div>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
