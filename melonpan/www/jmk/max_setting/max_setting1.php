<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:����ʸ�����������/���������ɲåݥ��������
'******************************************************/

$title_text = $TT_max_setting;
$title_color = $TC_DOKUSHA;

//'=== Main ===

//���������ɲåݥ���ȹ���
$sql = "UPDATE M_OSUSUMEPT SET osusume_pt=$osusume_pt WHERE pt_id=1";
db_exec($sql);

//����ʸ���/���������¹���
$i = 0;
while ($melonpai_id[$i]) {
	$new_melonpai_id = $melonpai_id[$i];
	$new_recom_num = $recom_num[$i];
	$new_osusume_num = $osusume_num[$i];
	$new_choice_num = $choice_num[$i];
	$sql = "UPDATE M_MELONPAI SET recom_num=" . sql_number($new_recom_num) . 
				", osusume_num=" . sql_number($new_osusume_num) . 
				", choice_num=" . sql_number($new_choice_num) . 
				" WHERE melonpai_id=$new_melonpai_id";
	db_exec($sql);
	$i++;
}
$msg = "��Ͽ���ޤ�����";
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

<input type="button" value=" ��� "  onclick="location.href='../max_setting/index.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
