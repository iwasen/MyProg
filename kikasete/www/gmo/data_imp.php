<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��˥����ãӣ֥ǡ�������ݡ���
'******************************************************/

$top = '../../kikasete/admin';
$inc = "../../kikasete/inc";
include("$inc/common_gmo.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/error_msg.php");
include("csv_upload.class.php");
$admin = '.';

//�ᥤ�����
session_start();

//set_global('proenquete', '��˥�������', 'CSV����ݡ���', BACK_TOP);
$errmsg = '';

$up = &CsvUpload::getInstance();
$up->DEBUGWRITE("--------");

if ($_REQUEST["action"] == "upload") {
	if (isset($_FILES["upfile"]["name"])) {
		if ($_FILES["upfile"]["name"] == '') {
			// ���顼���ե�����̤̾����
			$errmsg = '�ãӣ֥ե�����̾��̤���ϤǤ���';
		}
	}
	if( $errmsg == "" ) {
		$up->DEBUGWRITE("VALIDATE");
		$up->validate($errmsg);
	}

}
if( $errmsg != "" ) {
?>
	<html lang="ja">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>info-Q��˥����ǡ�������ݡ���</title>
	<link rel="stylesheet" type="text/css" href="<?=$admin ?>/css/main.css">
    </head>

<?
    error_msg($errmsg, $true);
}
?>
</body>
</html>
