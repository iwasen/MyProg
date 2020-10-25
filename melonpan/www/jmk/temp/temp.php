<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/database.php");
include ("../inc/admin_inc.php");
include ("../inc/com_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:
'******************************************************/

$title_text = "てすと";
$title_color = $TC_MASTER;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><? echo ("$title_text") ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>


<FORM ENCTYPE="multipart/form-data" ACTION="temp.php" METHOD=POST>
<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="2000">
Send this file: <INPUT NAME="userfile" TYPE="file">
<INPUT TYPE="submit" VALUE="Send File">
<br>
<br>
</form>
<?php
print "\$userfile = " . $userfile . "<br>";
print "\$userfile_name = " . $userfile_name . "<br>";
print "\$userfile_size = " . $userfile_size . "<br>";
print "\$userfile_type = " . $userfile_type . "<br>";

if (is_uploaded_file($userfile)) {
    copy($userfile, "/temp.jpg");
} else {
    echo "ファイルアップロード攻撃を受けた可能性があります : ファイル名 '$userfile'.";
}
/* ...もしくは... */
move_uploaded_file($userfile, "/temp2.img");


$sql = "update T_PICT SET PR_CONTENTS_PICT='$username' WHERE PR_pr_id='1'";
$select = new select_sql($sql);

?>
		
<? footer_jmk(0); ?>

</body>
</html>
