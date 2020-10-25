<?
$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/thread.php");

function store_inquiry1($user_id, $title, $contents) {
	$sql = "INSERT INTO t_inquiry (iq_inquiry_type,iq_user_id,iq_title,iq_contents,iq_date,iq_status) VALUES (" .
					"'0'," .
					sql_char($user_id) . "," .
					sql_char($title) . "," .
					sql_char($contents) . "," .
					"current_timestamp," .
					"'0')";
	db_exec($sql);
}

function store_inquiry2($seq_no, $title, $contents) {
	$sql = "SELECT iq_user_id,iq_staff_id FROM t_inquiry WHERE iq_seq_no=$seq_no";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$user_id = $fetch->iq_user_id;
		$staff_id = $fetch->iq_staff_id;
		$inquiry_type = '0';
	}
	$sql = "INSERT INTO t_inquiry (iq_parent_no,iq_inquiry_type,iq_user_id,iq_title,iq_contents,iq_date,iq_staff_id,iq_status) VALUES (" .
					sql_number($seq_no) . "," .
					sql_char($inquiry_type) . "," .
					sql_char($user_id) . "," .
					sql_char($title) . "," .
					sql_char($contents) . "," .
					"current_timestamp," .
					sql_char($staff_id) . "," .
					"'0')";
	if (db_exec($sql)) {
		change_thread_status($seq_no, '2');
	}
}

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>問い合わせテスト</title>
</head>
<body>

<?
if ($contents) {
	if ($user_id)
		store_inquiry1(make_id($user_id, 8), $title, $contents);
	else
		store_inquiry2($n, $title, $contents);
?>
<p>メッセージを送信しました。</p>
<?
} else {
?>
<h3>Inbound Navigator plus　問い合わせテスト</h3>
<hr>
<form method="post">
<p>
タイトル<br>
<input type="text" name="title" size=70 maxlength=100 value="問い合わせ">
</p>

<p>
問い合わせ内容<br>
<textarea name="contents" cols=80 rows=10></textarea>
</p>

<input type="hidden" name="n" value="<?=$n?>">
<input type="hidden" name="user_id" value="<?=$id?>">
<input type="submit" value="　送信　">
</form>
<?
}
?>
<hr>
Copyright(c) <?=date(Y)?> xx xxxxx Inc. All rights reserved.

</body>
</html>
