<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/crontab_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:送信メールテンプレート
'******************************************************/

$title_text = $TT_batch_schedule;
$title_color = $TC_MASTER_MAINTE;

//'=== Main ===
$cron_list = array();
write_crontab($CRONTAB_NAME, $param, $sh_name);

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

更新しました。<br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
