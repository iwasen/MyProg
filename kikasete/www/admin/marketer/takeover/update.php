<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ޡ����������Ѥ��¹�
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global('marketer', '�ޡ�����������', '�����ӥ��ΰ��Ѥ�', BACK_TOP);

db_begin_trans();

// My���󥱡��Ȥ�����Ѥ�
$sql = "SELECT me_marketer_id,me_my_enq_no FROM t_my_enquete WHERE me_marketer_id=$from_marketer_id ORDER BY me_my_enq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$rec['me_marketer_id'] = sql_number($to_marketer_id);
	$rec['me_my_enq_no'] = "COALESCE((SELECT MAX(me_my_enq_no) FROM t_my_enquete WHERE me_marketer_id=$to_marketer_id),0)+1";
	db_update('t_my_enquete', $rec, "me_marketer_id=$fetch->me_marketer_id AND me_my_enq_no=$fetch->me_my_enq_no");
}

// PRO���󥱡��Ȥ�����Ѥ�
$sql = "SELECT mep_marketer_id,mep_pro_enq_no FROM t_pro_enquete WHERE mep_marketer_id=$from_marketer_id ORDER BY mep_pro_enq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$rec['mep_marketer_id'] = sql_number($to_marketer_id);
	$rec['mep_pro_enq_no'] = "COALESCE((SELECT MAX(mep_pro_enq_no) FROM t_pro_enquete WHERE mep_marketer_id=$to_marketer_id),0)+1";
	db_update('t_pro_enquete', $rec, "mep_marketer_id=$fetch->mep_marketer_id AND mep_pro_enq_no=$fetch->mep_pro_enq_no");
}

// My�ѡ��ȥʡ�����Ѥ�
$rec['ppj_marketer_id'] = sql_number($to_marketer_id);
db_update('t_mp_pjt', $rec, "ppj_marketer_id=$from_marketer_id");

// �����Ѥ���οƥޡ�������ID��ä�
$rec['mr_parent_id'] = 'null';
db_update('t_marketer', $rec, "mr_marketer_id=$to_marketer_id AND mr_parent_id IS NOT NULL");

// ���Ѥ����My���󥱡��Ȥ򸫤���褦�ˤ���
/*
if ($flag) {
	$rec['mr_parent_id'] = sql_number($to_marketer_id);
	db_update('t_marketer', $rec, "mr_marketer_id=$from_marketer_id");
}
*/

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg">�����ӥ��ΰ��Ѥ���Ԥ��ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='../../menu.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
