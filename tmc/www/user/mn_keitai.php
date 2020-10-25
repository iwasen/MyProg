<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

// 解除理由選択肢表示
function select_kaijo_riyuu() {
	$sql = "SELECT dmk_cancel_cd,dmk_cancel_keitai FROM t_dealer_mail_kaijo WHERE dmk_cancel_keitai IS NOT NULL ORDER BY dmk_cancel_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<input type="radio" ', value($fetch->dmk_cancel_cd), ' name="cancel_cd">', htmlspecialchars($fetch->dmk_cancel_keitai), "</option><br>\n";
	}
}

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
<form method="post" name="form1" action="mn_keitai2.php">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td align="center" bgcolor="black"><font color="white">定期ﾒｰﾙﾆｭｰｽ解除</font></td>
	</tr>
	<tr>
		<td align="center">解除理由の選択</td>
	</tr>
	<tr>
		<td><? select_kaijo_riyuu() ?></td>
	</tr>
	<tr>
		<td align="center">
			<input type="submit" value="購読を解除">
			<input type="hidden" name="login_id" <?=value($_SERVER['QUERY_STRING'])?>>
		</td>
	</tr>
</table>
</form>
</body>
</html>
