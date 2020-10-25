<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:プロジェクト用告知メールテンプレート
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");

//メイン処理
set_global2('project', 'プロジェクト管理｜テンプレート編集', '告知メールテンプレート更新', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT pm_seq_no FROM m_pjt_mail WHERE pm_title=".sql_char($pm_title);
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "INSERT INTO m_pjt_mail (pm_title,pm_body) VALUES (%s,%s)";
		$sql = sprintf($sql,
			sql_char($pm_title),
			sql_char($pm_body));
		db_exec($sql);
		$back = "location.href='list.php'";
		$msg = "登録しました。";
	} else {
		$back = 'history.back()';
		$msg = "入力したテンプレート名は、既に登録されています。";
	}
	break;
case 'update':
	$sql = "SELECT pm_seq_no FROM m_pjt_mail WHERE pm_title=".sql_char($pm_title)." AND pm_seq_no<>$pm_no";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_pjt_mail SET pm_title=%s,pm_body=%s WHERE pm_seq_no=$pm_no";
		$sql = sprintf($sql,
			sql_char($pm_title),
			sql_char($pm_body));
		db_exec($sql);

		$msg = '登録しました。';
		$back = "location.href='list.php'";
	} else {
		$msg = "入力したテンプレート名は、既に登録されています。";
		$back = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_pjt_mail WHERE pm_seq_no=$pm_no";
	db_exec($sql);
	$msg = '削除しました。';
	$back = "location.href='list.php'";
	break;
default:
	redirect('list.php');
}

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br><br>
			<p class="msg"><?=$msg?></p>
			<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
