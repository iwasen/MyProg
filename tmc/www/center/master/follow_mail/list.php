<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ե����᡼�����ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡ý���ե����᡼��') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">������ե����᡼�����</td>
		<td class="lb">
			<input type="button" value="�ۿ�������" onclick="location.href='schedule.php'">
			<input type="button" value=" ��� " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
sort_header(0,'VOL');
sort_header(0,'��̾');
sort_header(0,'����ʸ');
sort_header(0,'�᡼��������������');
sort_header(0,'�桼�����٥ͥե��åȾ���');
sort_header(0,'�ּ��������Ź����');
sort_header(0,'����ư��');
?>
	</tr>
<?
for ($i = 0; $i < MAX_FOLLOW_MAIL; $i++) {
	$vol_no = $i + 1;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$vol_no?></td>
		<td align="center"><a href="subject_list.php?vol_no=<?=$vol_no?>" title="����ե����᡼��Vol.<?=$vol_no?>�η�̾����Ͽ���ޤ���">��̾-<?=$vol_no?></a></td>
		<td align="center"><a href="greeting_list.php?vol_no=<?=$vol_no?>" title="����ե����᡼��Vol.<?=$vol_no?>�ΰ���ʸ����Ͽ���ޤ���">����ʸ-<?=$vol_no?></a></td>
		<td align="center"><a href="maker_list.php?vol_no=<?=$vol_no?>" title="����ե����᡼��Vol.<?=$vol_no?>�Υ᡼�����������������Ͽ���ޤ���">�᡼��������������-<?=$vol_no?></a></td>
		<td align="center"><a href="benefit_list.php?vol_no=<?=$vol_no?>" title="����ե����᡼��Vol.<?=$vol_no?>�Υ桼�����٥ͥե��åȾ������Ͽ���ޤ���">�桼�����٥ͥե��åȾ���-<?=$vol_no?></a></td>
		<td align="center"><a href="car_dealer_list.php?vol_no=<?=$vol_no?>" title="����ե����᡼��Vol.<?=$vol_no?>�μּ��������Ź�������Ͽ���ޤ���">�ּ��������Ź����-<?=$vol_no?></a></td>
		<td align="center"><a href="buy_list.php?vol_no=<?=$vol_no?>" title="����ե����᡼��Vol.<?=$vol_no?>�ι���ư������Ͽ���ޤ���">����ư��-<?=$vol_no?></a></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
