<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

// �����ͳ�����ɽ��
function select_kaijo_riyuu() {
	$sql = "SELECT dmk_cancel_cd,dmk_cancel_keitai FROM t_dealer_mail_kaijo WHERE dmk_cancel_keitai IS NOT NULL ORDER BY dmk_cancel_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<input type="radio" ', value($fetch->dmk_cancel_cd), ' name="cancel_cd">', htmlspecialchars($fetch->dmk_cancel_keitai), "</option><br>\n";
	}
}

// Shift_JIS�ǽ���
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title>����Ź�᡼����</title>
</head>
<body>
<form method="post" name="form1" action="mn_keitai2.php">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td align="center" bgcolor="black"><font color="white">����Ҏ��َƎ��������</font></td>
	</tr>
	<tr>
		<td align="center">�����ͳ������</td>
	</tr>
	<tr>
		<td><? select_kaijo_riyuu() ?></td>
	</tr>
	<tr>
		<td align="center">
			<input type="submit" value="���ɤ���">
			<input type="hidden" name="login_id" <?=value($_SERVER['QUERY_STRING'])?>>
		</td>
	</tr>
</table>
</form>
</body>
</html>
