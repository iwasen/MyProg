<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:継続リレーションメール配信日更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

db_begin_trans();

$table = 't_relation_date';

db_delete($table, 'true');

if (is_array($_POST['date'])) {
	foreach ($_POST['date'] as $date) {
		$rec['rld_send_date'] = sql_date("$date {$_POST['hour']}:{$_POST['minute']}:0");
		db_insert($table, $rec);
	}
}

db_commit_trans();
db_vacuum($table);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('マスタメンテナンス｜継続リレーションメール｜配信日設定') ?>

<div align="center">
<p class="msg">継続リレーションメールの配信日を登録しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
