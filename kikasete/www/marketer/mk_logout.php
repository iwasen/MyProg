<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");

// ���å����λ
session_start();

if (!isset($_SESSION['agent_login_id'])) {
	session_destroy();

	// �ޡ��������ȥåץڡ�����
	redirect(http_url('index.php'));
} else {
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<script type="text/javascript">
<!--
window.close();
//-->
</script>
</head>
</html>
<?
}
?>