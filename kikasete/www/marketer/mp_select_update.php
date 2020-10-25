<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

if (is_array($choice)) {
	foreach (array_keys($choice) as $monitor_id)
		$ary[] = $monitor_id;
	$choice_monitor_id = join(',', $ary);
} else
	$choice_monitor_id = '0';

db_begin_trans();

$rec['pmn_marketer_choice'] = sql_number(1);
db_update('t_mp_monitor', $rec, "pmn_pjt_id=$pjt_id AND pmn_monitor_id IN ($choice_monitor_id) AND pmn_marketer_choice=0");

$rec['pmn_marketer_choice'] = sql_number(0);
db_update('t_mp_monitor', $rec, "pmn_pjt_id=$pjt_id AND pmn_monitor_id NOT IN ($choice_monitor_id) AND pmn_marketer_choice=1");

switch ($next_action) {
case 'save':
	$msg = '参加者の選定フラグを一時保存しました。';
	break;
case 'apply':
	$rec['ppj_status'] = sql_number(MPS_MEM_CHECK);
	db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id");
	$msg = '参加者を確定し、申請しました。';
	break;
}

db_commit_trans();
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td><?=$msg?></td>
				</tr>
			</table>
			<br>
			<a href="<?=get_return_page()?>"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
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
