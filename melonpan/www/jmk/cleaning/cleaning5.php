<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�᡼�륢�ɥ쥹�����꡼�˥󥰡������ӥ�
'******************************************************/

$title_text = $TT_mail_cleaning;
$title_color = $TC_OTHER;

$sql = "DELETE FROM M_CLEANING_DATA WHERE CD_seq_no=$seq_no";
db_exec($sql);
$sql = "DELETE FROM T_CLEANING_LIST WHERE CL_seq_no=$seq_no";
db_exec($sql);

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

������ޤ�����<br><br>

<input type="button" value=" ��� " onclick="location.href='index.php'">
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
