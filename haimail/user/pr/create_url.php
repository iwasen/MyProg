<?
header('Cache-Control: no-cache');
include('../inc/common.php');

$ok = false;
$pr_id = $id;
if ($pr_id == '')
	$msg = 'PR-ID�λ��꤬����ޤ���';
elseif (strlen($pr_id) != 7)
	$msg = 'PR-ID�η�����㤤�ޤ���';
elseIf (!NumCheck($pr_id))
	$msg = 'PR-ID�˿����ʳ����ޤޤ�Ƥ��ޤ���';
else {
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
		$msg = 'URL��������ޤ�����';
		$ok = true;
	} else
		$msg = 'URL�κ����˼��Ԥ��ޤ�����';
}
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=EUC-JP">
<?
if ($ok) {
?>
<SCRIPT LANGUAGE=javascript>
<!--
close();
//-->
</SCRIPT>
<?
}
?>
</HEAD>
<BODY>

<P><?=$msg?></P>

</BODY>
</HTML>
