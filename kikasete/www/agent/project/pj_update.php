<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:プロジェクト情報更新
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//メイン処理
set_global2('project', 'プロジェクト管理', 'プロジェクト情報更新', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT pj_project_id FROM t_project WHERE pj_project_name='$project_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = sprintf("INSERT INTO t_project (pj_project_name, pj_tantousha_id, pj_regist_date, pj_summary)"
			. "VALUES (%s,%s,'now',%s)",
			sql_char($project_name),
			sql_char($tantousha_id),
			sql_char($summary));
		db_exec($sql);
		$msg = 'プロジェクト情報を登録しました。';
		$back = "location.href='list.php'";
	} else {
		$msg = '入力したプロジェクト名は既に登録されています。';
		$back = 'history.back()';
	}
	$title = "プロジェクト情報 新規登録";
	break;
case 'update':
	$sql = "SELECT pj_project_id FROM t_project WHERE pj_project_name='$project_name' AND pj_project_id<>$pjt_id";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE t_project SET"
			. " pj_project_name=" . sql_char($project_name)
			. ",pj_summary=" . sql_char($summary)
			. " WHERE pj_project_id=$pjt_id";
		db_exec($sql);
		$title = "プロジェクト登録情報更新";
		$msg = 'プロジェクト情報を更新しました。';
		$back = "location.href='list.php'";
	} else {
		$title = "プロジェクト情報 更新";
		$msg = '入力したプロジェクト名は既に登録されています。';
		$back = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM t_project WHERE pj_project_id=$pjt_id";
	db_exec($sql);
	$title = "プロジェクト情報 更新";
	$msg = 'プロジェクト情報を削除しました。';
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
		<td align="center" class="m4"><?=$title?></td>
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
