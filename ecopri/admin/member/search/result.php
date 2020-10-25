<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:���������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/list_item.php");

//�ᥤ�����
set_global('member', '����������', '���������', BACK_TOP);

// ���å�������
session_start();
if (!isset($_SESSION['ss_search']))
	$_SESSION['ss_search'] = new search_class;
$search = &$_SESSION['ss_search'];

if (!isset($_SESSION['ss_litem']))
	$_SESSION['ss_litem'] = new list_item_class;
$litem = &$_SESSION['ss_litem'];

$search->get_form();

// �������ɽ��
$sql = 'SELECT COUNT(*) FROM t_member';
$all = db_fetch1($sql);

$sql = 'SELECT COUNT(*) FROM t_member where mb_step=3';
$mb_count = db_fetch1($sql);

if ($litem->where == '')
	$where = $search->make_sql();
else
	$where = $litem->where;

$sql = "SELECT COUNT(*) FROM t_member WHERE $where";

$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$find = $fetch[0];
}

if ($litem->condition == '')
	$condition = $search->get_condition();
else
	$condition = $litem->condition;

$litem->renew_flag = 'on';

// ���󹹿���������
$sql = "SELECT MAX(lb_date) AS lb_date FROM l_batch WHERE lb_file_name='co2_update.php'";
$lb_date = db_fetch1($sql);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function open_list() {
	window.open("<?=$top?>/member/search/list.php" , "_blank", "scrollbars=yes,resizable=yes,status=yes,menubar=yes,toolbar=yes");
}
function all_nocheck() {
	var n = document.form2.length;
	for (var i = 0; i < n; i++)
		document.form2[i].checked = '';
	document.form2.id.checked = true;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0" width=700>���������</td>
				</tr>
				<tr>
					<td>
						<table border=0 cellspacing=2 cellpadding=3 width='100%'>
							<tr>
								<td class="m2" width="25%">�������줿�����</td>
								<td class="n2"><?=number_format($find)?></td>
							</tr>
							<tr>
								<td class="m2">��Ͽ�����</td>
								<td class="n2"><?=number_format($mb_count)?></td>
							</tr>
							<tr>
								<td class="m2">�������줿���</td>
								<td class="n2"><?=number_format($find / $mb_count * 100, 1)?>%</td>
							</tr>
							<tr>
								<td class="m2">�������</td>
								<td class="n2"><font size="-1"><?=nl2br($condition)?></font></td>
							</tr>
							<tr>
								<td class="m2">�ӣѣ�</td>
								<td class="n2"><font class="small"><?=$sql?></font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<div align="center"><br>
			<form method="post" name="form1" action="list.php" target="_blank">
				<input type="hidden" name="where" <?=value($where)?>>
				<input type="submit" value="����ɽ��">
				<input type="button" value="����롡" onclick="location.href='condition.php'">
			</form>
			</div>

			<form method="post" name="form2" action="list_item.php">
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0" width=700>��ɽ�����ܻ���</td>
				</tr>
				<tr>
					<td>
						<table border=0 cellspacing=2 cellpadding=3 width='100%' class="small">
							<tr>
								<td class="m2" width="25%">��Ͽ�Ծ���</td>
								<td class="n2">
									<nobr><input type="checkbox" name="id" <?=checked($litem->id)?> checked>ID&nbsp;</nobr>
									<nobr><input type="checkbox" name="name" <?=checked($litem->name)?>>��̾&nbsp;</nobr>
									<nobr><input type="checkbox" name="kana" <?=checked($litem->kana)?>>�եꥬ��&nbsp;</nobr>
									<nobr><input type="checkbox" name="mail_addr" <?=checked($litem->mail_addr)?>>�᡼�륢�ɥ쥹&nbsp;</nobr>
									<nobr><input type="checkbox" name="sex" <?=checked($litem->sex)?>>����&nbsp;</nobr>
									<nobr><input type="checkbox" name="birthday" <?=checked($litem->birthday)?>>��ǯ����&nbsp;</nobr>
									<nobr><input type="checkbox" name="age" <?=checked($litem->age)?>>ǯ��&nbsp;</nobr>
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">�������</td>
								<td class="n2">
									<nobr><input type="checkbox" name="area" <?=checked($litem->area)?>>�ｻ�ϰ�&nbsp;</nobr>
									<nobr><input type="checkbox" name="zip" <?=checked($litem->zip)?>>͹���ֹ�&nbsp;</nobr>
									<nobr><input type="checkbox" name="todoufuken" <?=checked($litem->todoufuken)?>>��ƻ�ܸ�&nbsp;</nobr>
									<nobr><input type="checkbox" name="address" <?=checked($litem->address)?>>����&nbsp;</nobr>
									<nobr><input type="checkbox" name="keitai" <?=checked($litem->keitai)?>>�������&nbsp;</nobr>
									<nobr><input type="checkbox" name="sozai" <?=checked($litem->sozai)?>>���ﹽ¤(��¤��y/n)&nbsp;</nobr>
									<nobr><input type="checkbox" name="hebel" <?=checked($litem->hebel)?>>�ء��٥�ϥ���&nbsp;</nobr>
									<nobr><input type="checkbox" name="chikunen" <?=checked($litem->chikunen)?>>����ǯ��&nbsp;</nobr>
									<nobr><input type="checkbox" name="room" <?=checked($litem->room)?>>����ּ��&nbsp;</nobr>
									<nobr><input type="checkbox" name="space" <?=checked($litem->space)?>>��������&nbsp;</nobr>
									<nobr><input type="checkbox" name="boiler" <?=checked($litem->boiler)?>>����勵����&nbsp;</nobr>
									<nobr><input type="checkbox" name="gas_kind" <?=checked($litem->gas_kind)?>>������&nbsp;</nobr>
									<nobr><input type="checkbox" name="gas_type" <?=checked($litem->gas_type)?>>����������&nbsp;</nobr>
									<nobr><input type="checkbox" name="wt_month" <?=checked($litem->wt_month)?>>��ƻ���˷�&nbsp;</nobr>
									<nobr><input type="checkbox" name="car" <?=checked($litem->car)?>>�����&nbsp;</nobr>
									<nobr><input type="checkbox" name="gcar" <?=checked($litem->gcar)?>>��������&nbsp;</nobr>
									<nobr><input type="checkbox" name="dcar" <?=checked($litem->dcar)?>>�ǥ��������&nbsp;</nobr>
									<nobr><input type="checkbox" name="bike" <?=checked($litem->bike)?>>�Х���&nbsp;</nobr>
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">��²����</td>
								<td class="n2">
									<nobr><input type="checkbox" name="fm_ctg" <?=checked($litem->fm_ctg)?>>��²���ƥ���&nbsp;</nobr>
									<nobr><input type="checkbox" name="fm_detail" <?=checked($litem->fm_detail)?>>��²��³����ǯ��</nobr>
									<nobr><input type="checkbox" name="fm_child" <?=checked($litem->fm_child)?>>�Ҷ�(20��̤��)��̵ͭ&nbsp;</nobr>
									<nobr><input type="checkbox" name="fm_youngest" <?=checked($litem->fm_youngest)?>>��ǯ����²��ǯ��&nbsp;</nobr>
<!--									<nobr><input type="checkbox" name="fm_age" <?=checked($litem->fm_age)?>>��²��ǯ��&nbsp;</nobr>-->
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">���Ϲ�������</td>
								<td class="n2">
									<nobr><input type="checkbox" name="inp_ol" <?=checked($litem->inp_ol)?>>��������&nbsp;</nobr>
									<nobr><input type="checkbox" name="inp_gm" <?=checked($litem->inp_gm)?>>��������&nbsp;</nobr>
									<nobr><input type="checkbox" name="inp_gl" <?=checked($litem->inp_gl)?>>�����������&nbsp;</nobr>
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">��Ͽ����λ����</td>
								<td class="n2">
									<nobr><input type="checkbox" name="reg_date_kari" <?=checked($litem->reg_date_kari)?>>����Ͽ��&nbsp;</nobr>
									<nobr><input type="checkbox" name="reg_date" <?=checked($litem->reg_date)?>>����Ͽ��&nbsp;</nobr>
									<nobr><input type="checkbox" name="entry" <?=checked($litem->entry)?>>����в���&nbsp;</nobr>
									<nobr><input type="checkbox" name="reg_inp" <?=checked($litem->reg_inp)?>>���ʴ�λ���&nbsp;</nobr>
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">���Ϸ�̾���</td>
								<td class="n2"><font class="small">
									<select name="inp_y"><?=select_year('2002', '����ʤ�', $litem->inp_y)?></select>ǯ
									<select name="inp_m"><?=select_month('��', $litem->inp_m)?></select>��<br>
<!--									<nobr>&nbsp;���ʴ�λ��̵ͭ��<input type="checkbox" name="commit_m" <?=checked($litem->commit_m)?>>ɽ����</nobr><br>-->
									<nobr>&nbsp;���ϥե饰��
										<input type="checkbox" name="inp_flag_ol" <?=checked($litem->inp_flag_ol)?>>����,
										<input type="checkbox" name="inp_flag_gm" <?=checked($litem->inp_flag_gm)?>>����,
										<input type="checkbox" name="inp_flag_gl" <?=checked($litem->inp_flag_gl)?>>��������
									</nobr><br><br>
									<nobr>&nbsp;�����̡�
										<input type="checkbox" name="use_al" <?=checked($litem->use_al)?>>����,
										<input type="checkbox" name="use_el" <?=checked($litem->use_el)?>>�ŵ�,
										<input type="checkbox" name="use_gs" <?=checked($litem->use_gs)?>>����,
										<input type="checkbox" name="use_wt" <?=checked($litem->use_wt)?>>��ƻ,
										<input type="checkbox" name="use_ol" <?=checked($litem->use_ol)?>>����,
										<input type="checkbox" name="use_gl" <?=checked($litem->use_gl)?>>�������,
										<input type="checkbox" name="use_gm" <?=checked($litem->use_gm)?>>���ߡ�
									</nobr><br>
									<nobr>&nbsp;��ۡ� ��
										<input type="checkbox" name="sum_al" <?=checked($litem->sum_al)?>>����,
										<input type="checkbox" name="sum_el" <?=checked($litem->sum_el)?>>�ŵ�,
										<input type="checkbox" name="sum_gs" <?=checked($litem->sum_gs)?>>����,
										<input type="checkbox" name="sum_wt" <?=checked($litem->sum_wt)?>>��ƻ,
										<input type="checkbox" name="sum_ol" <?=checked($litem->sum_ol)?>>����,
										<input type="checkbox" name="sum_gl" <?=checked($litem->sum_gl)?>>�������ˡ�
										<input type="checkbox" name="sum_4total" <?=checked($litem->sum_4total)?>>4����,
										<input type="checkbox" name="sum_6total" <?=checked($litem->sum_6total)?>>6���ס�
									</nobr><br>
									<nobr>&nbsp;CO2�̡�
										<input type="checkbox" name="co2_al" <?=checked($litem->co2_al)?>>����,
										<input type="checkbox" name="co2_el" <?=checked($litem->co2_el)?>>�ŵ�,
										<input type="checkbox" name="co2_gs" <?=checked($litem->co2_gs)?>>����,
										<input type="checkbox" name="co2_wt" <?=checked($litem->co2_wt)?>>��ƻ,
										<input type="checkbox" name="co2_ol" <?=checked($litem->co2_ol)?>>����,
										<input type="checkbox" name="co2_gl" <?=checked($litem->co2_gl)?>>�������,
										<input type="checkbox" name="co2_gm" <?=checked($litem->co2_gm)?>>���ߡˡ�
										<input type="checkbox" name="co2_4total" <?=checked($litem->co2_4total)?>>4����,
										<input type="checkbox" name="co2_6total" <?=checked($litem->co2_6total)?>>6���ס�
									<nobr><br><br>
									<nobr>&nbsp;�︺�����̡�
										<input type="checkbox" name="use_cut_al" <?=checked($litem->use_cut_al)?>>����,
										<input type="checkbox" name="use_cut_el" <?=checked($litem->use_cut_el)?>>�ŵ�,
										<input type="checkbox" name="use_cut_gs" <?=checked($litem->use_cut_gs)?>>����,
										<input type="checkbox" name="use_cut_wt" <?=checked($litem->use_cut_wt)?>>��ƻ,
										<input type="checkbox" name="use_cut_ol" <?=checked($litem->use_cut_ol)?>>����,
										<input type="checkbox" name="use_cut_gl" <?=checked($litem->use_cut_gl)?>>�������,
										<input type="checkbox" name="use_cut_gm" <?=checked($litem->use_cut_gm)?>>���ߡ�
									</nobr><br>
									<nobr>&nbsp;�︺��� ����
										<input type="checkbox" name="sum_cut_al" <?=checked($litem->sum_cut_al)?>>����,
										<input type="checkbox" name="sum_cut_el" <?=checked($litem->sum_cut_el)?>>�ŵ�,
										<input type="checkbox" name="sum_cut_gs" <?=checked($litem->sum_cut_gs)?>>����,
										<input type="checkbox" name="sum_cut_wt" <?=checked($litem->sum_cut_wt)?>>��ƻ,
										<input type="checkbox" name="sum_cut_ol" <?=checked($litem->sum_cut_ol)?>>����,
										<input type="checkbox" name="sum_cut_gl" <?=checked($litem->sum_cut_gl)?>>�������ˡ�
										<input type="checkbox" name="sum_4cut" <?=checked($litem->sum_4cut)?>>4����,
										<input type="checkbox" name="sum_6cut" <?=checked($litem->sum_6cut)?>>6���ס�
									</nobr>
									<nobr>&nbsp;CO2�︺�̡�
										<input type="checkbox" name="co2_cut_al" <?=checked($litem->co2_cut_al)?>>����,
										<input type="checkbox" name="co2_cut_el" <?=checked($litem->co2_cut_el)?>>�ŵ�,
										<input type="checkbox" name="co2_cut_gs" <?=checked($litem->co2_cut_gs)?>>����,
										<input type="checkbox" name="co2_cut_wt" <?=checked($litem->co2_cut_wt)?>>��ƻ,
										<input type="checkbox" name="co2_cut_ol" <?=checked($litem->co2_cut_ol)?>>����,
										<input type="checkbox" name="co2_cut_gl" <?=checked($litem->co2_cut_gl)?>>�������,
										<input type="checkbox" name="co2_cut_gm" <?=checked($litem->co2_cut_gm)?>>���ߡˡ�
										<input type="checkbox" name="co2_4cut" <?=checked($litem->co2_4cut)?>>4����,
										<input type="checkbox" name="co2_6cut" <?=checked($litem->co2_6cut)?>>6���ס�
									<nobr><br><br>
									<nobr>&nbsp;���Ѻ︺�����̡�
										<input type="checkbox" name="use_rui_al" <?=checked($litem->use_rui_al)?>>����,
										<input type="checkbox" name="use_rui_el" <?=checked($litem->use_rui_el)?>>�ŵ�,
										<input type="checkbox" name="use_rui_gs" <?=checked($litem->use_rui_gs)?>>����,
										<input type="checkbox" name="use_rui_wt" <?=checked($litem->use_rui_wt)?>>��ƻ,
										<input type="checkbox" name="use_rui_ol" <?=checked($litem->use_rui_ol)?>>����,
										<input type="checkbox" name="use_rui_gl" <?=checked($litem->use_rui_gl)?>>�������,
										<input type="checkbox" name="use_rui_gm" <?=checked($litem->use_rui_gm)?>>���ߡ�
									</nobr>
									<nobr>&nbsp;���Ѻ︺��ۡ� ��
										<input type="checkbox" name="sum_rui_al" <?=checked($litem->sum_rui_al)?>>����,
										<input type="checkbox" name="sum_rui_el" <?=checked($litem->sum_rui_el)?>>�ŵ�,
										<input type="checkbox" name="sum_rui_gs" <?=checked($litem->sum_rui_gs)?>>����,
										<input type="checkbox" name="sum_rui_wt" <?=checked($litem->sum_rui_wt)?>>��ƻ,
										<input type="checkbox" name="sum_rui_ol" <?=checked($litem->sum_rui_ol)?>>����,
										<input type="checkbox" name="sum_rui_gl" <?=checked($litem->sum_rui_gl)?>>�������ˡ�
										<input type="checkbox" name="sum_4rui" <?=checked($litem->sum_4rui)?>>4����,
										<input type="checkbox" name="sum_6rui" <?=checked($litem->sum_6rui)?>>6���ס�
									</nobr>
									<nobr>&nbsp;����CO2�︺�̡�
										<input type="checkbox" name="co2_rui_al" <?=checked($litem->co2_rui_al)?>>����,
										<input type="checkbox" name="co2_rui_el" <?=checked($litem->co2_rui_el)?>>�ŵ�,
										<input type="checkbox" name="co2_rui_gs" <?=checked($litem->co2_rui_gs)?>>����,
										<input type="checkbox" name="co2_rui_wt" <?=checked($litem->co2_rui_wt)?>>��ƻ,
										<input type="checkbox" name="co2_rui_ol" <?=checked($litem->co2_rui_ol)?>>����,
										<input type="checkbox" name="co2_rui_gl" <?=checked($litem->co2_rui_gl)?>>�������,
										<input type="checkbox" name="co2_rui_gm" <?=checked($litem->co2_rui_gm)?>>���ߡˡ�
										<input type="checkbox" name="co2_4rui" <?=checked($litem->co2_4rui)?>>4����,
										<input type="checkbox" name="co2_6rui" <?=checked($litem->co2_6rui)?>>6���ס�
									<nobr><br>
									<span class="note">�� ����ɽ���ˤϻ��֤�������ޤ���</span>
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="right"><font color="red" size=-1>�ǽ�����������<?=date('Y/m/d H:i:s', sql_time($lb_date))?></font></td>
	</tr>
		<td>
			<div align="center">
				<input type="hidden" name="where" <?=value($where)?>>
				<input type="hidden" name="find" <?=value($find)?>>
				<input type="hidden" name="mb_count" <?=value($mb_count)?>>
				<input type="hidden" name="condition" <?=value($condition)?>>
				<input type="hidden" name="sql" <?=value($sql)?>>
				<input type="submit" value=" ����ɽ�� ">
				<input type="button" value=" �ꥻ�å� " onclick="document.form1.reset();all_nocheck()">
				<input type="button" value="�ǿ��������" onclick="location.href='../../renew.php'">
				<input type="button" value="����롡" onclick="location.href='condition.php'">
			</form>
			</div>
		</td>
	</tr>
</table>

</div>

<? page_footer() ?>
</body>
</html>
