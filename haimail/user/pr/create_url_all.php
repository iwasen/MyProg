<?
include('../inc/common.php');
include('../inc/database.php');

if ($create) {
	$sql = "SELECT PR_pr_id FROM T_PR ORDER BY PR_pr_id";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	while (!$ds->EOF()) {
		$pr_id = $ds->GetValue('PR_pr_id');

		//�����å������ղ�
		$sum = 0;
		for ($i = 0; $i < 7; $i++)
			$sum = ($sum + $pr_id{$i}) % 10;

		$pr_id .= chr((10 - $sum) % 10 + ord('0'));

		$path = $PATH_TRANSLATED;
		$i = strrpos($path , '/');
		$path = substr($path, 0, $i);
		$path .= "/$pr_id";
		mkdir($path, 0777);
		chmod($path, 0777);

		$path .= '/index.php';
		if ($fp = fopen ($path, 'w')) {
			fputs($fp, "<? header('location: ../h.php?id=$pr_id'); exit; ?>");
			fclose($fp);
		}

		$ds->MoveNext();
	}
}
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=EUC-JP">
</HEAD>
<BODY>

<?
if ($create) {
?>
<p>�У���Ͽ�գң̤�������ޤ�����</p>
<?
} else {
?>
<form method="post">
	�У���Ͽ�գң̤�������ޤ���
	<input type="submit" value="���¹ԡ�">
	<input type="hidden" name="create" value="1">
</form>
<?
}
?>

</BODY>
</HTML>
