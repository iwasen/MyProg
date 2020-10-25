<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート更新処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

set_global('myenquete', 'Ｍｙアンケート管理', 'Ｍｙアンケート更新', BACK_TOP);

// セッション処理
$myenq = &$_SESSION['ss_my_enq'];
if ($myenq->my_enq_no != $my_enq_no)
	redirect("show.php?marketer_id=$marketer_id&my_enq_no=$my_enq_no");

$enquete = &$myenq->enquete;

// フォームからクラスへ代入
$enquete->description = $description;

// リセット処理
if ($next_action == 'reset') {
	$enquete->description = '';
	redirect('edit_web.php?marketer_id=$marketer_id&edit=1');
}

if (!$msg) {
	// DBに保存
	$rec['en_description'] = sql_char($enquete->description);
	db_update('t_enquete', $rec, "en_enquete_id=$myenq->enquete_id");

	// セッション変数削除
	unset($_SESSION['ss_my_enq']);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<br>
<?
if (!$msg) {
?>
<div align="center">
<form>
<p>ＭｙアンケートのアンケートＷＥＢ画面を設定しました。</p>
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
</form>
</div>
<?
} else
    error_msg($msg);
?>
<? page_footer() ?>
</body>
</html>
