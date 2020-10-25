<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:マーケター引継ぎ設定
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

set_global('marketer', 'マーケター管理', 'サービスの引継ぎ', BACK_TOP);
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
	if (f.from_marketer_id.value == "") {
		alert("引き継がれるマケーターを選択してください。");
		f.from_marketer_id.focus();
		return false;
	}
	if (f.to_marketer_id.value == "") {
		alert("引き継ぐマケーターを選択してください。");
		f.to_marketer_id.focus();
		return false;
	}
	if (f.from_marketer_id.value == f.to_marketer_id.value) {
		alert("同一マーケターに引き継ぐことはできません。");
		f.from_marketer_id.focus();
		return false;
	}
	return confirm("サービスの引継ぎを行います。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■サービスの引継ぎを行うマーケターの選択</td>
	</tr>
	<tr>
		<td class="m1" width="20%">引き継がれるマケーター<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="from_marketer_id"><? select_marketer('- 選択してください -', '', 3) ?></select>
<?/*			<input type="checkbox" name="flag" value="1" checked>引継ぎ後もサービスを見られるようにする*/?>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">引き継ぐマケーター<?=MUST_ITEM?></td>
		<td class="n1"><select name="to_marketer_id"><? select_marketer('- 選択してください -', '', 3) ?></select>
	</tr>
</table>
<br>
<input type="submit" value="　引継ぎ　">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
