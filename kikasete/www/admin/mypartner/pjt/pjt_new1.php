<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ����ޡ�����������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ�����ļ�������Ͽ���ޡ�����������', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'pjt_list', 'sort_col', 'sort_dir');

// ��о��
$where = "WHERE mr_status<>9 AND mr_type=3";
if (SERVICE == 'agent')
	$where .= " AND mr_agent_id=$agent_id";

// �����Ⱦ��
$order_by = order_by(1, 0, 'mr_kinmu_name', 'mr_marketer_id', 'mr_name1_kana,mr_name2_kana');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT mr_marketer_id,mr_name1,mr_name2,mr_kinmu_name"
		. " FROM t_marketer"
		. " $where"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
//-->
</script>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���ޡ����������� </td>
		<td class="lb">
			<input type="button" value="����롡" onclick="history.back()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>


<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%">
	<tr class="tch">
<?
sort_header(1, '���̾');
sort_header(2, '�ώ�������ID');
sort_header(3, '�ޡ�������̾');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td align="center"><?=$fetch->mr_marketer_id?></td>
		<td><a href="pjt_new2.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="������ļ���������ޤ�"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
