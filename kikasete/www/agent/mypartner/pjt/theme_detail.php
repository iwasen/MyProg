<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ����᡼��ʸ�ܺ�
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/icp_db.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '�᡼��ʸ�ܺ�', BACK_CLOSE);

$sql = "SELECT rm_subject,rm_content,rm_date FROM l_remark WHERE rm_remark_id=$remark_id";
$result = icp_db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center">�᡼��ʸ�ܺ�</td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m0" colspan=2 align="left">���᡼������</td>
				</tr>
				<tr>
					<td class="m1">�ۿ���������</td>
					<td class="n1"><?=mb_convert_kana(get_datepart('M', $fetch->rm_date), 'N')?>��<?=mb_convert_kana(get_datepart('D', $fetch->rm_date), 'N')?>��<?=mb_convert_kana(get_datepart('h', $fetch->rm_date), 'N')?>��
					</td>
				</tr>
				<tr>
					<td class="m1">�ʤ����������ȥ�</td>
					<td class="n1"><?=htmlspecialchars($fetch->rm_subject)?></td>
				</tr>
				<tr>
					<td class="m1" width=140>��ʸ</td>
					<td class="n1" height=200><pre><?=htmlspecialchars($fetch->rm_content)?></pre></td>
				</tr>
			</table><br>
			<div align="center">
				<input type="button" value="�Ĥ���" onclick="window.close()">
			</div>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
