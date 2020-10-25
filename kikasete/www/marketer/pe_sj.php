<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/encrypt.php");

decrypt_param($_SERVER['QUERY_STRING'], $pro_enq_no, $marketer_id);
$add_send_count = substr($_SERVER['QUERY_STRING'], -1);

if ($pro_enq_no && $marketer_id) {
	$sql = "SELECT mr_mail_addr,mr_password FROM t_marketer WHERE mr_marketer_id=$marketer_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->mr_mail_addr;
		$password = $fetch->mr_password;
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<body onload="document.form1.submit()">
<form method="post" name="form1" action="mk_login.php">
<input type="hidden" name="mail_addr" <?=value($mail_addr)?>>
<input type="hidden" name="password" <?=value($password)?>>
<input type="hidden" name="url" value="pe_send_judg.php?pro_enq_no=<?=$pro_enq_no?>&add_send_count=<?=$add_send_count?>">
</form>
</body>
</html>
