<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:対象条件マスタ
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
set_global2('project', 'プロジェクト管理｜テンプレート編集', '対象条件テンプレート更新', BACK_TOP);

switch ($next_action) {
case 'new':
	$search = new search_class;
	$search->get_form();
	$search_id = $search->write_db();
	$sql = "INSERT INTO m_pjt_search (ps_title,ps_search_id) VALUES (%s,%s)";
	$sql = sprintf($sql,
		sql_char($ps_title),
		sql_number($search_id));
	db_exec($sql);

	$msg = '登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$search = new search_class;
	$search->get_form();
	$search->write_db($search_id);

	$sql = "UPDATE m_pjt_search SET ps_title=%s WHERE ps_search_id=$search_id";
	$sql = sprintf($sql,
		sql_char($ps_title));
	db_exec($sql);

	$msg = '更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "DELETE FROM m_pjt_search WHERE ps_search_id=$search_id";
	db_exec($sql);
	$sql = "DELETE FROM t_search WHERE sr_search_id=$search_id";
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
