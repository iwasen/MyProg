<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

if (isset($pro_enq_no)) {
	$pro_enq = new pro_enquete_class;
	$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
	$enquete = &$pro_enq->enquete;
} else
	redirect('pe_index.php');

switch ($enquete->status) {
case 0:
	$status = 1;
	$price = number_format($pro_enq->get_total_price());
	$msg = "Pro���󥱡��ȤΥ��ơ������������λ���֤����ꤷ�ޤ�����<br>
���������ܥ���򥯥�å����ơ�Pro���󥱡��Ȥ����ѿ����򤪴ꤤ�פ��ޤ���<br>
<br>
����������ϡ���פǡ�{$price}�ߡ����̡ˡ��Ǥ���<br>
<br>
���ʤ����嵭�ϴ������⡦�ե�����ǥ���Ĵ���������⡦���ץ����(����ץ���������Τ�)������ɽ�򸵤ˡ�<br>
�������ꤷ�Ƥ������������Ƥ�ɽ���򤷤Ƥ���ޤ���<br>
����¾�Υ��ץ���󡢾ܺ٤ˤĤ��ޤ��Ƥϡ�����������Ȥؤ��䤤��碌����������";
	break;
case 1:
	$status = 0;
	$msg = 'Pro���󥱡��ȤΥ��ơ��������������ᤷ�ޤ�����';
	break;
}

$rec['en_status'] = sql_number($status);
db_update('t_enquete', $rec, "en_enquete_id=$pro_enq->enquete_id");
?>
<? marketer_header('�У�異�󥱡���', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="778">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td><?=$msg?></td>
				</tr>
			</table>
			<br>
<a href="pe_make.php?pro_enq_no=<?=$pro_enq_no?>"><img src="images/mkk_bt/back.gif" alt="���" width="108" height="31" border="0"></a>

			</form>
			</div>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
