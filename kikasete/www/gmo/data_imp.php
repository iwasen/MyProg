<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニターＣＳＶデータインポート
'******************************************************/

$top = '../../kikasete/admin';
$inc = "../../kikasete/inc";
include("$inc/common_gmo.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/error_msg.php");
include("csv_upload.class.php");
$admin = '.';

//メイン処理
session_start();

//set_global('proenquete', 'モニター管理', 'CSVインポート', BACK_TOP);
$errmsg = '';

$up = &CsvUpload::getInstance();
$up->DEBUGWRITE("--------");

if ($_REQUEST["action"] == "upload") {
	if (isset($_FILES["upfile"]["name"])) {
		if ($_FILES["upfile"]["name"] == '') {
			// エラー：ファイル名未入力
			$errmsg = 'ＣＳＶファイル名が未入力です。';
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
	<title>info-Qモニターデータインポート</title>
	<link rel="stylesheet" type="text/css" href="<?=$admin ?>/css/main.css">
    </head>

<?
    error_msg($errmsg, $true);
}
?>
</body>
</html>
