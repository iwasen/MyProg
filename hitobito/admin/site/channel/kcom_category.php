<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����ȴ���������ͥ��Խ��ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");

// �ڡ����إå���������
$header = new header_class('����ͥ��Խ�', BACK_NONE);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>
<form name="form1">
<?php
if ($_GET['channel_id']) {
	$sql = "SELECT kcg_l1,kcg_l2,kcg_l3,kcg_l4,kcg_l5,kcg_name,chk_channel_id"
			. " FROM m_kcom_category"
			. " LEFT JOIN m_channel_kcom ON chk_channel_id={$_GET['channel_id']} AND chk_l1=kcg_l1 AND chk_l2=kcg_l2 AND chk_l3=kcg_l3 AND chk_l4=kcg_l4 AND chk_l5=kcg_l5"
			. " WHERE kcg_status=0"
			. " ORDER BY kcg_l1,kcg_l2,kcg_l3,kcg_l4,kcg_l5";
} else {
	$sql = "SELECT kcg_l1,kcg_l2,kcg_l3,kcg_l4,kcg_l5,kcg_name,0 AS chk_channel_id"
			. " FROM m_kcom_category"
			. " WHERE kcg_status=0"
			. " ORDER BY kcg_l1,kcg_l2,kcg_l3,kcg_l4,kcg_l5";
}
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$value = "$fetch->kcg_l1:$fetch->kcg_l2:$fetch->kcg_l3:$fetch->kcg_l4:$fetch->kcg_l5";

	if ($fetch->kcg_l5 != 0)
		$level = '������������������';
	elseif ($fetch->kcg_l4 != 0)
		$level = '��������������';
	elseif ($fetch->kcg_l3 != 0)
		$level = '����������';
	elseif ($fetch->kcg_l2 != 0)
		$level = '������';
	else
		$level = '��';
?>
<input type="checkbox" name="kcom_category" id="l<?=$i?>" <?=value($value)?><?=checked($fetch->chk_channel_id)?>><font size=2><label for="l<?=$i?>"><?=$level?><?=$fetch->kcg_name?></label></font><br>
<?php
}
?>
</form>
</body>
</html>
