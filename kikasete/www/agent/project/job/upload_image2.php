<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�����ե����륢�åץ���
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global2('project', '�ץ������ȴ����å᡼��ʸ��Ͽ', '�����λ���', BACK_TOP);

db_begin_trans();

foreach ($_FILES["upload_file"]["error"] as $key => $error) {
	if ($error == UPLOAD_ERR_OK) {
		$sql = sprintf("SELECT hti_image_id FROM t_html_image WHERE hti_job_id=%s AND hti_file_name=%s",
			sql_number($job_id),
			sql_char($_FILES["upload_file"]["name"][$key]));
		$image_id = db_fetch1($sql);

		$rec['hti_job_id'] = sql_number($job_id);
		$rec['hti_file_name'] = sql_char($_FILES["upload_file"]["name"][$key]);
		$rec['hti_size'] = sql_number($_FILES['upload_file']['size'][$key]);
		$rec['hti_content_type'] = sql_char($_FILES['upload_file']['type'][$key]);
		$rec['hti_image_data'] = sql_bytea(file_get_contents($_FILES["upload_file"]["tmp_name"][$key]));
		if ($image_id)
			db_update('t_html_image', $rec, "hti_image_id=$image_id");
		else
			db_insert('t_html_image', $rec);
	}
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
<p class="msg">�����ե�����Υ��åץ��ɤ�λ���ޤ�����</p>
<p><input type="button" id="ok" value="�᡼��ʸ��������" onclick="location.href='enq_mail.php?job_id=<?=$job_id?>'"></p>
</div>

<? page_footer() ?>
</body>
</html>
