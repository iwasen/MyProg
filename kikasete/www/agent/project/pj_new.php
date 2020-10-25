<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:プロジェクト情報新規登録
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

//メイン処理
set_global2('project', 'プロジェクト管理', 'プロジェクト新規登録', BACK_TOP);

$sql = "SELECT cl_name,ts_name1,ts_name2"
		. " FROM m_client JOIN m_tantousha ON ts_client_id=cl_client_id"
		. " WHERE ts_tantousha_id=" . sql_char($tantousha_id);
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('担当者ＩＤが不正', __FILE__);
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
function onSubmit_form1(f) {
	if (f.project_name.value == "") {
		alert("プロジェクト名を入力してください。");
		f.project_name.focus();
		return false;
	}
	return confirm("プロジェクトを登録します。よろしいですか？");
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="document.form1.project_name.focus()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="pj_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m0" colspan=2>■プロジェクト情報を入力してください。
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m1" width=140>企業名</td>
		<td class="n1"><?=htmlspecialchars($fetch->cl_name)?></td>
	</tr>
	<tr>
		<td class="m1" width=140>企業担当者</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2")?></td>
	</tr>
	<tr>
		<td class="m1" width=140>プロジェクト名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="project_name" size=70 maxlength=150>
		</td>
	</tr>
	<tr>
		<td class="m1">プロジェクト概要</td>
		<td class="n1">
			<textarea class="kanji" name="summary" cols=70 rows=20></textarea>
		</td>
	</tr>
</table>
<table width=700>
	<div align="center">
	<input type="hidden" name="tantousha_id" <?=value($tantousha_id)?>>
	<input type="hidden" name="next_action">
	<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
	<input type="reset" value="リセット">
	<input type="button" value="　戻る　" onclick='location.href="list.php"'>
	</div>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
