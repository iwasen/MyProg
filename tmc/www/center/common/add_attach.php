<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:事務局ページ共通・添付ファイル追加
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$comm_id = $_POST['comm_id'];

foreach ($_FILES as $file) {
	if ($file['size'] != 0) {
		if ($fp = fopen($file['tmp_name'], 'r')) {
			$data = fread($fp, $file['size']);
			fclose($fp);

			$rec['mat_comm_id'] = sql_number($comm_id);
			$rec['mat_file_no'] = "(SELECT COALESCE(MAX(mat_file_no),0)+1 FROM t_mail_attachment WHERE mat_comm_id=$comm_id)";
			$rec['mat_file_size'] = sql_number($file['size']);
			$rec['mat_file_name'] = sql_char($file['name']);
			$rec['mat_file_type'] = sql_char($file['type']);
			$rec['mat_data'] = sql_char(base64_encode($data));
			db_insert('t_mail_attachment', $rec);
		}
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<script type="text/javascript">
<!--
function onload_body() {
	opener.document.form1.action = "answer.php";
	opener.document.form1.submit();
	window.close();
}
//-->
</script>
</head>
<body onload="onload_body()">
</body>
</html>
