<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");
include ("../inc/com_mail_inc.php");

/******************************************************
' System :¤á¤í¤ó¤Ñ¤ó»öÌ³¶ÉÍÑ¥Ú¡¼¥¸
' Content:²¾ÅĞÏ¿¾µÇ§½èÍı
'******************************************************/

$title_text = $TT_kari_touroku;
$title_color = $TC_HAKKOUSHA;

$job_flg = substr($mag_id, 0, 2);
$mag_id = substr($mag_id, -6);
$mag_id = sprintf("%06d", $mag_id);

$sql = "SELECT MAG_PUB_STATUS_FLG, PUB.OWNER_NM_KANJ, PUB.MAIL_ADD_MAIN, MAG.MAG_NM"
		. " FROM M_MAGAZINE MAG, M_PUBLISHER PUB"
		. " WHERE MAG_ID = '$mag_id' AND MAG.PUBLISHER_ID=PUB.PUBLISHER_ID";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow == 0) {
	header ("location: ../admin/err_msg.php?id=0204");
	exit;
} else {
	$fetch = pg_fetch_object($result, 0);
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$mail_add_main = $fetch->mail_add_main;
	$mag_nm = $fetch->mag_nm;
	$mag_pub_status_flg = $fetch->mag_pub_status_flg;
	
	if ($mag_pub_status_flg <> '00') {
		header ("location: ../admin/err_msg.php?id=0104");
		exit;
	} else {
		if ($job_flg == "ok") {
			$sql = "UPDATE M_MAGAZINE SET MAG_PUB_STATUS_FLG = '01', MAG_PUB_STATUS_DT='now', MODEFY_DT='now' WHERE mag_id = '$mag_id'";
			db_exec($sql);
			mag_ok_mail($owner_nm_kanj, $mail_add_main, $mag_nm, $mag_id);
			$msg = "¾µÇ§¤·¤Ş¤·¤¿¡£";
		} elseif ($job_flg == "ng") {
			$sql = "UPDATE M_MAGAZINE SET MAG_PUB_STATUS_FLG = '02', MAG_PUB_STATUS_DT='now', MODEFY_DT='now' WHERE mag_id = '$mag_id'";
			db_exec($sql);
			mag_ng_mail($owner_nm_kanj, $mail_add_main, $mag_nm);
			$msg = "¾µÇ§µñÈİ¤·¤Ş¤·¤¿¡£";
		}
	}
}
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

<form method="post" name="form1" action="hon_touroku3.php">
<center>
²¾ÅĞÏ¿¤ò<?= $msg ?><br>
<br>
<input type="button" value=" Ìá¤ë " onclick="location.href='kari_touroku1.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
