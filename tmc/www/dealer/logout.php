<?
/******************************************************
' System :�֤���������� �������������ӥ�������Ź���ѥڡ���
' Content:�������Ƚ���
'******************************************************/

session_start();

if ($_SESSION['s_campaign_id'] == '') {
	// ���å�������
	session_unset();
	session_destroy();

	// ������ڡ�����
	header('location: index.php');
	exit;
} else {
	// �����ڡ����̳���ѥڡ������������
	unset($_SESSION['s_dealer_cd']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
</head>
<body onload="window.close()">
</body>
</html>
<?
}
?>