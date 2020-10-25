<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:�ץ쥼��ȴ����ꥹ��ɽ��
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/decode.php");

// ���󥱡��Ⱦ���
function decode_enq_status($code) {
	switch ($code) {
	case 0:
		return '������';
	case 4:
		return '�»��Ԥ�';
	case 5:
		return '�»���';
	case 6:
		return '������';
	case 7:
		return '��λ';
	case 9:
		return '����Ѥ�';
	}
	return '����';
}

//�ᥤ�����
set_global('etc', '����¾����', '�ץ쥼��ȴ���', BACK_TOP);

// ���å������Ͽ
//get_session_vars($pset, 'monitor_enquete', 'displine', 'sort_col', 'sort_dir', 'page');
// �����Ⱦ��
$order_by = order_by(6, 0, 'mb_id','mb_name1_kana || mb_name2_kana', 'mb_mail_addr', 'mb_zip', 'mb_area_cd', 'mb_regist_date');

$sql = "select mb_seq_no,mb_id,mb_name1,mb_name2,mb_name1_kana,mb_name2_kana,mb_mail_addr,mb_zip,mb_area_cd,mb_address,mb_regist_date"
	. " from t_member left join t_present_list on mb_seq_no=li_mb_seq_no and li_ps_no=$ps_no where mb_step=3 and li_mb_seq_no is null and mb_kind is null"
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
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function onSubmit_form2(f) {
	return confirm("��Ͽ���ޤ�����������Ǥ�����");
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">�������԰�����<font size=-1 color="blue">������:&nbsp;<?=$nrow?>̾��</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='list.php'">
		</td>
	</tr>
<!--
	<tr>
		<td class="lc">
			<nobr>ȯ������
			<select name="end_date_y"><? select_year(2002, '', $enquete->end_date_y) ?></select>ǯ
			<select name="end_date_m"><? select_month('', $enquete->end_date_m) ?></select>��
			<select name="end_date_d"><? select_day('', $enquete->end_date_d) ?></select>��
			</nobr>
		<span class="note"><font size=2>���� ȯ��������ꤷ�ơ�������������ȯ����˥����å����Ʋ�������</font></span>
		</td>
	</tr>
-->
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'ID');
		sort_header(2, '��̾');
		sort_header(3, '�᡼�륢�ɥ쥹');
		sort_header(4, '͹���ֹ�');
		sort_header(5, '����');
		sort_header(6, '��Ͽ��');
		sort_header(0, '����');
		sort_header(0, '��礻');
		sort_header(0, '����');
?>
	</tr>
</form>
<form method="post" name="form2" action="regist.php" onsubmit="return onSubmit_form2(this)">
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<input type="hidden" name="seq_no[<?=$i?>]" <?=value($fetch->mb_seq_no)?>>
		<td><a href="#" title="�������Ͽ�����ɽ�����ޤ�"><?=htmlspecialchars($fetch->mb_id)?></a></td>
		<td align="left"><?=$fetch->mb_name1?>&nbsp;<?=$fetch->mb_name2?></td>
		<td align="left"><a href="mailto:<?=$fetch->mb_mail_addr?>" title="����˥᡼�뤷�ޤ�"><?=$fetch->mb_mail_addr?></a></td>
		<td align="center"><?=substr($fetch->mb_zip,0,3)?>-<?=substr($fetch->mb_zip,3,4)?></td>
		<td align="left"><?=htmlspecialchars(decode_area($fetch->mb_area_cd))." ".$fetch->mb_address?></td>
		<td align="center"><?=format_date($fetch->mb_regist_date)?></td>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=3 checked></td>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=1></td>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=9></td>
	</tr>
<?
}
?>
</table>
<br>
<input type="submit" value="����Ͽ��" onclick="document.form2.next_action.value='regist'">
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="ps_no" value="<?=$ps_no?>">
</div>
</form>

<? page_footer() ?>
</body>
</html>