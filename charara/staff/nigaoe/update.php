<?
/******************************************************
' System :「きゃららFactory」作画スタッフ用ページ
' Content:似顔絵テーブル更新
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/status.php");
include("$inc/file.php");

set_global('charara', '', 'きゃららFactory 似顔絵作成', BACK_TOP);

switch ($next_action) {
case 'make':
	$rec['nd_status'] = STATUS_MAKING;
	$rec['nd_assign_date'] = 'current_timestamp';
	$rec['nd_creation_date'] = 'current_timestamp';
	$rec['nd_staff_id'] = $staff_login_id;
	db_update('t_nigaoe_data', $rec, "nd_nigaoe_id=$nigaoe_id");
	$msg = '選択した写真の似顔絵を作成してください。';
	$url = "edit.php?nigaoe_id=$nigaoe_id";
	break;
case 'upload':
	$sql = "SELECT nd_random_id FROM t_nigaoe_data WHERE nd_nigaoe_id=" . sql_number($nigaoe_id);
	$random_id = db_fetch1($sql);
	$file = &$_FILES['nigaoe_file'];
	$path_parts = pathinfo($file['name']);
	$filename = "$random_id." . $path_parts['extension'];
	save_nigaoe_file($file['tmp_name'], $filename);

	$rec['nd_status'] = STATUS_COMPLETED;
	$rec['nd_completion_date'] = 'current_timestamp';
	$rec['nd_nigaoe_file'] = sql_char($filename);
	db_update('t_nigaoe_data', $rec, "nd_nigaoe_id=$nigaoe_id");
	$msg = '似顔絵ファイルのアップロードが完了しました。';
	$url = "list.php";
	break;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='<?=$url?>'"></p>
</div>

<? page_footer() ?>

</body>
</html>
