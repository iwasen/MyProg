<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メンバーリスト
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("inc.php");

set_global($class, $section, 'メンバーリスト更新', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = sprintf("INSERT INTO t_myenq_member_grp (mmg_group_name,mmg_summary) VALUES (%s,%s)",
			sql_char($group_name),
			sql_char($summary));
	db_exec($sql);
	$msg = 'メンバーリストを登録しました。';
	break;
case 'update':
	$sql = sprintf("UPDATE t_myenq_member_grp SET mmg_group_name=%s,mmg_summary=%s,mmg_update_date=CURRENT_TIMESTAMP WHERE mmg_group_id=$group_id",
			sql_char($group_name),
			sql_char($summary));
	db_exec($sql);
	$msg = 'メンバーリストを更新しました。';
	break;
case 'delete':
	db_begin_trans();
	$sql = "DELETE FROM t_myenq_member_grp WHERE mmg_group_id=$group_id";
	db_exec($sql);
	$sql = "DELETE FROM t_myenq_member_list WHERE mml_group_id=$group_id";
	db_exec($sql);
	db_commit_trans();
	$msg = 'メンバーリストを削除しました。';
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
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
