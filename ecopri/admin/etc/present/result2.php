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

// �����Ⱦ��
$order_by = order_by(1, 1, 'mb_id', 'mb_name1_kana || mb_name2_kana', 'mb_mail_addr', 'mb_zip', 'mb_area_cd', 'li_status');

// ɽ���Կ����
$limit = disp_limit();

$sql = "select mb_seq_no,mb_id,mb_name1,mb_name2,mb_name1_kana,mb_name2_kana,mb_mail_addr,mb_zip,mb_area_cd,mb_address,li_status"
	. " from t_member left join t_present_list on mb_seq_no=li_mb_seq_no where (li_status=1 or li_status=2) and li_ps_no=$ps_no  $order_by";
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

function open_detail(seq_no) {
	window.open("../../inc/detail.php?seq_no=" + seq_no, "_blank", "width=450,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no");
}

function open_mail() {
	window.open("<?=$top?>/etc/mail_template/edit.php?template_id=present1" , "_blank", "scrollbars=yes,resizable=yes,status=yes,menubar=yes,toolbar=yes");
}

function onSubmit_form(f) {
	var count1 = 0;
	var count2 = 0;
	f["r_check[" + i + "]"] = new Array();

	switch (f.next_action.value) {
	case "mail":
		for (var i = 0; i < <?=$nrow?>; i++) {
			if (f["r_check[" + i + "]"].length == 2) {
				if (f["r_check[" + i + "]"][0].checked)
					count1 = count1 + 1;
				else if (f["r_check[" + i + "]"][1].checked)
					count2 = count2 + 1;
			} else {
				if (f["r_check[" + i + "]"].checked)
					count2 = count2 + 1;
			}
		}
		return confirm("��礻�᡼��� " + count1 + "�������� " + count2 + "�������ޤ���������Ǥ�����");
	case "test":
		return confirm("��礻�᡼���ƥ����������ޤ���������Ǥ�����");
	}
	return false;
}

//-->
</script>
</head>
<body>
<? page_header() ?>
<form method="post" name="form1">
<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">�������Ԥ�������<font size=-1 color="blue">�����:&nbsp;<?=$nrow?>���</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="history.back()">
		</td>
	</tr>
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
		sort_header(6, '��礻');
		sort_header(0, '���');
?>
	</tr>
</form>
<form method="post" name="form2" action="regist.php" onsubmit="return onSubmit_form(this)">
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<input type="hidden" name="seq_no[<?=$i?>]" <?=value($fetch->mb_seq_no)?>>
		<td><a href="javascript:open_detail(<?=$fetch->mb_seq_no?>)" title="�������Ͽ�����ɽ�����ޤ�"><?=htmlspecialchars($fetch->mb_id)?></a></td>
		<td align="left"><?=$fetch->mb_name1?>&nbsp;<?=$fetch->mb_name2?></td>
		<td align="left"><a href="mailto:<?=$fetch->mb_mail_addr?>" title="����˥᡼�뤷�ޤ�"><?=$fetch->mb_mail_addr?></a></td>
		<td align="center"><?=substr($fetch->mb_zip,0,3)?>-<?=substr($fetch->mb_zip,3,4)?></td>
		<td align="left"><?=htmlspecialchars(decode_area($fetch->mb_area_cd))." ".$fetch->mb_address?></td>
<?
if ($fetch->li_status == 2) {
?>
		<td align="center"><font color="green">��礻��</font></td>
<?
} else {
?>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=2 checked></td>
<?
}
?>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=9></td>
	</tr>
<?
}
?>
</table>
<br>
<input type="submit" value="���¹ԡ�" onclick="document.form2.next_action.value='mail'">
<input type="button" value="�Ҏ��ٳ�ǧ"  onclick="javascript:open_mail()">
<input type="submit" value="�ƥ�������" onclick="document.form2.next_action.value='test'">
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="ps_no" value="<?=$ps_no?>">
</div>
</form>
<? page_footer() ?>
</body>
</html>
