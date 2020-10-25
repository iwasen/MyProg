<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

$sql = sprintf("UPDATE t_user_dealer_mail SET udm_recv_flag=%s,udm_cancel_cd=%s,udm_cancel_text=%s,udm_cancel_date=current_timestamp WHERE udm_user_id=(SELECT ups_user_id FROM t_user_personal WHERE ups_login_id=%s)",
		sql_bool(false),
		sql_number($_POST['cancel_cd']),
		sql_char($_POST['cancel_text']),
		sql_char($_POST['login_id']));
db_exec($sql);

// Shift_JISで出力
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title>販売店メール解除</title>
</head>
<body>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td align="center" bgcolor="black"><font color="white">定期ﾒｰﾙﾆｭｰｽ解除</font></td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td>販売店メールの購読の解除が完了しました。</td>
	</tr>
</table>
</body>
</html>
