<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:CSSファイルアップロード
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', 'その他管理｜マスタメンテナンス', 'アンケートメールテンプレート', BACK_TOP);

db_begin_trans();

if ($_FILES["upload_file"]["error"] == UPLOAD_ERR_OK) {
	$sql = sprintf("SELECT htc_css_id FROM t_html_css WHERE htc_job_id=%s AND htc_file_name=%s",
		sql_number($job_id),
		sql_char($_FILES["upload_file"]["name"]));
	$css_id = db_fetch1($sql);

	$rec['htc_job_id'] = sql_number($job_id);
	$rec['htc_file_name'] = sql_char($_FILES["upload_file"]["name"]);
	$rec['htc_size'] = sql_number($_FILES['upload_file']['size']);
	$rec['htc_content_type'] = sql_char($_FILES['upload_file']['type']);
	$rec['htc_css_data'] = sql_char(file_get_contents($_FILES["upload_file"]["tmp_name"]));
	if ($css_id)
		db_update('t_html_css', $rec, "htc_css_id=$css_id");
	else
		db_insert('t_html_css', $rec);
}

db_commit_trans();
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
<p class="msg">CSSファイルのアップロードを完了しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='edit_html.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
