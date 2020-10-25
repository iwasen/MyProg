<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メールアドレス・クリーニング・サービス
'******************************************************/

$title_text = $TT_mail_cleaning;
$title_color = $TC_OTHER;

//'=== エラーメールアドレスチェック ===
function addr_check($mail_adr) {
	$mail_adr = trim($mail_adr);
	if (strrpos($mail_adr, " ")) {
		$err = true;
	} else {
		$mail_parts = explode("@", $mail_adr);
		if ($mail_parts[2]) {
			$err = true;
		}	elseif ($mail_parts[0] == "" || $mail_parts[1] == "") {
			$err = true;
		}	elseif (strpos($mail_parts[1], ".") == 0) {
			$err = true;
		}
	}
	if ($err) {
		return false;
	} else {
		return true;
	}
}

//'=== Main ===
//エラーアドレス削除

$i = 0;
While ($edit_flag[$i]) {
	$sub_seq = $edit_flag[$i];
	$sql = "DELETE FROM T_CLEANING_LIST WHERE CL_seq_no=$seq_no AND CL_sub_seq=$edit_flag[$i]";
	db_exec($sql);
	$i++;
}

//'修正アドレスチェック

$i = 0;
while ($edit_sub_seq[$i]) {
	$cl_sub_seq = $edit_sub_seq[$i];
	$cl_mail_adr = $edit_mail_adr[$i];
	if ($cl_mail_adr <> "") {
		if (addr_check($cl_mail_adr)) {
			$cl_status = "0";
		} else {
			$cl_status = "1";
		}
		$sql = "UPDATE T_CLEANING_LIST SET CL_status='$cl_status', CL_mail_adr='$cl_mail_adr' WHERE CL_seq_no=$seq_no AND CL_sub_seq=$cl_sub_seq";
		db_exec($sql);
	}
	$i++;
}

$msg = "実行終了しました。";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>
<center>
<form method="post" name="form1">

<?= $msg ?><br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">

</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
