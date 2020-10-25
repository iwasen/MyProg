<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ץ������ȿʹԴ����ꥹ��ɽ��
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/search.php");
include("job_sub.php");

// ��ʬ�����å�
function diff_check($col) {
	global $nrow, $fetch;

	for ($i = 1; $i < $nrow; $i++) {
		if ($fetch[0][$col] != $fetch[$i][$col])
			return true;
	}
	return false;
}

// �귿������ʬ�����å�
function diff_check_teikei() {
	global $nrow, $fetch;

	for ($i = 0; $i < $nrow; $i++) {
		$sql = "SELECT tk_teikei_qno,tk_teikei_andor,tk_teikei_ans FROM t_teikei WHERE tk_job_id={$fetch[$i]['jb_job_id']} ORDER BY tk_teikei_qno";
		$result = db_exec($sql);
		$nrow2 = pg_numrows($result);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result, $j);
			$teikei[$i] .= $fetch2->tk_teikei_qno . $fetch2->tk_teikei_andor . $fetch2->tk_teikei_ans;
		}

		if ($i != 0) {
			if ($teikei[0] != $teikei[$i])
				return true;
		}
	}

	return false;
}

set_global2('project', '�ץ������ȴ���', '����ֺ�ʬɽ��', BACK_TOP);

$sql = "SELECT *"
		. " FROM t_job"
		. " LEFT JOIN t_enquete ON en_enquete_id=jb_enquete_id"
		. " LEFT JOIN t_search ON sr_search_id=jb_search_id"
		. " WHERE jb_job_id IN (" . get_multi_data($job_id) . ")"
		. " ORDER BY jb_job_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++)
	$fetch[$i] = pg_fetch_array($result, $i);
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

<table border=0 cellspacing=2 cellpadding=3 width='100%' class="small">
	<tr>
		<td class="m0">������ֺ�ʬɽ��</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�����ID</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1">
			<a href="jb_contents.php?job_id=<?=$fetch[$i]['jb_job_id']?>"><?=$fetch[$i]['jb_job_id']?></a>
			<? show_parent_child($fetch[$i]['jb_job_id'], $fetch[$i]['jb_parent_job_id']) ?>
		</td>
<? } ?>
	</tr>
	<tr>
		<td class="m1">�����̾</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=htmlspecialchars($fetch[$i]['jb_job_name'])?></td>
<? } ?>
	</tr>
<? if (diff_check('jb_mail_format')) { ?>
	<tr>
		<td class="m1">�᡼�����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=decode_mail_format($fetch[$i]['jb_mail_format'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_category')) { ?>
	<tr>
		<td class="m1">�ۿ����ƥ���</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=decode_job_category($fetch[$i]['jb_category'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_job_type')) { ?>
	<tr>
		<td class="m1">����ּ���</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=decode_job_type($fetch[$i]['jb_job_type'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('en_title')) { ?>
	<tr>
		<td class="m1">���󥱡���̾��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=htmlspecialchars($fetch[$i]['en_title'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_enq_web_url')) { ?>
	<tr>
		<td class="m1">���󥱡���URL</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=htmlspecialchars($fetch[$i]['jb_enq_web_url'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('en_enq_type')) { ?>
	<tr>
		<td class="m1">���󥱡��ȼ���</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=decode_enq_type($fetch[$i]['en_enq_type'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_start_date')) { ?>
	<tr>
		<td class="m1">ȯ��������</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=format_datetime($fetch[$i]['jb_start_date'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_end_date')) { ?>
	<tr>
		<td class="m1">ȯ����λ��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=format_datetime($fetch[$i]['jb_end_date'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('en_start_date')) { ?>
	<tr>
		<td class="m1">���󥱡��ȳ�����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=format_datetime($fetch[$i]['en_start_date'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('en_end_date')) { ?>
	<tr>
		<td class="m1">���󥱡��Ƚ�λ��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=format_datetime($fetch[$i]['en_end_date'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('en_point')) { ?>
	<tr>
		<td class="m1">���󥱡��ȥݥ����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=number_format($fetch[$i]['en_point'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_send_num')) { ?>
	<tr>
		<td class="m1">����ȯ����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=number_format($fetch[$i]['jb_send_num'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_bunkatsu_kankaku')) { ?>
	<tr>
		<td class="m1">ʬ��ȯ���ֳ�</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=number_format($fetch[$i]['jb_bunkatsu_kankaku'])?> ��</td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_bunkatsu_kaisuu')) { ?>
	<tr>
		<td class="m1">ʬ��ȯ�����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=number_format($fetch[$i]['jb_bunkatsu_kaisuu'])?> ��</td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_point_jushin')) { ?>
	<tr>
		<td class="m1">�����ݥ����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=number_format($fetch[$i]['jb_point_jushin'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_parent_job_id')) { ?>
	<tr>
		<td class="m1">���󥱡��ȥ쥹�ݥ󥹥���ֻ���</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=$fetch[$i]['jb_parent_job_id']?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_taishou_hasshin')) { ?>
	<tr>
		<td class="m1">�о�ȯ�������</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_condition($fetch[$i]['sr_taishou_hasshin'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_taishou_response')) { ?>
	<tr>
		<td class="m1">�оݥ쥹�ݥ󥹥����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_condition($fetch[$i]['sr_taishou_response'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_jogai_hasshin')) { ?>
	<tr>
		<td class="m1">����ȯ�������</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_condition($fetch[$i]['sr_jogai_hasshin'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_jogai_response')) { ?>
	<tr>
		<td class="m1">�����쥹�ݥ󥹥����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_condition($fetch[$i]['sr_jogai_response'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_virgin_mail')) { ?>
	<tr>
		<td class="m1">�����оݥ�˥���</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_virgin_mail($fetch[$i]['jb_virgin_mail'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_send_timing') || diff_check('jb_send_timing_h') || diff_check('jb_send_timing_d')) { ?>
	<tr>
		<td class="m1">ȯ�������ߥ�</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_send_timing($fetch[$i]['jb_send_timing'], $fetch[$i]['jb_send_timing_h'], $fetch[$i]['jb_send_timing_d'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check_teikei()) { ?>
	<tr>
		<td class="m1">�귿����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_teikei_shori($fetch[$i]['jb_job_id']) ?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('jb_ct_id')) { ?>
	<tr>
		<td class="m1">����å�������</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><?=$fetch[$i]['jb_ct_id']?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_sql')) { ?>
	<tr>
		<td class="m1">�ɲãӣѣ�</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_condition($fetch[$i]['sr_sql'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_member_list')) { ?>
	<tr>
		<td class="m1">���С��ꥹ��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_condition($fetch[$i]['sr_member_list'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_type')) { ?>
	<tr>
		<td class="m1">�������</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_monitor_type($fetch[$i]['sr_type'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_sex')) { ?>
	<tr>
		<td class="m1">����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_sex($fetch[$i]['sr_sex'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_age_from') || diff_check('sr_age_to')) { ?>
	<tr>
		<td class="m1">ǯ��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_age($fetch[$i]['sr_age_from'], $fetch[$i]['sr_age_to'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_mikikon')) { ?>
	<tr>
		<td class="m1">̤����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_mikikon($fetch[$i]['sr_mikikon'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_shokugyou')) { ?>
	<tr>
		<td class="m1">����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_shokugyou($fetch[$i]['sr_shokugyou'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_gyoushu')) { ?>
	<tr>
		<td class="m1">�ȼ�</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_gyoushu($fetch[$i]['sr_gyoushu'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_shokushu')) { ?>
	<tr>
		<td class="m1">����</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_shokushu($fetch[$i]['sr_shokushu'])?></tr>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_kyojuu')) { ?>
	<tr>
		<td class="m1">�ｻ�ϰ�</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_area($fetch[$i]['sr_kyojuu'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_kinmu')) { ?>
	<tr>
		<td class="m1">��̳���ϰ�</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_area($fetch[$i]['sr_kinmu'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_spouse_flg')) { ?>
	<tr>
		<td class="m1">�۶��Ԥ�̵ͭ</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_spouse_flg($fetch[$i]['sr_spouse_flg'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_spouse_age_from') || diff_check('sr_spouse_age_to')) { ?>
	<tr>
		<td class="m1">�۶��Ԥ�ǯ��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_age($fetch[$i]['sr_spouse_age_from'], $fetch[$i]['sr_spouse_age_to'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_spouse_shokugyo')) { ?>
	<tr>
		<td class="m1">�۶��Ԥο���</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_shokugyou($fetch[$i]['sr_spouse_shokugyo'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_have_child')) { ?>
	<tr>
		<td class="m1">�Ҷ���̵ͭ</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_have_child($fetch[$i]['sr_have_child'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_child_sex')) { ?>
	<tr>
		<td class="m1">�Ҷ�������</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_child_sex($fetch[$i]['sr_child_sex'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_child_age_from') || diff_check('sr_child_age_to')) { ?>
	<tr>
		<td class="m1">�Ҷ���ǯ��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_age($fetch[$i]['sr_spouse_age_from'], $fetch[$i]['sr_spouse_age_to'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_child_gakunen')) { ?>
	<tr>
		<td class="m1">�Ҷ��γ�ǯ</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_child_gakunen($fetch[$i]['sr_child_gakunen'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_senior_flg')) { ?>
	<tr>
		<td class="m1">���˥��ؤ�̵ͭ</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_senior_flg($fetch[$i]['sr_senior_flg'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_housing_form')) { ?>
	<tr>
		<td class="m1">�������</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_housing_form($fetch[$i]['sr_housing_form'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_have_car')) { ?>
	<tr>
		<td class="m1">�֤ν�ͭ</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_have_car($fetch[$i]['sr_have_car'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_conveni')) { ?>
	<tr>
		<td class="m1">�褯�Ԥ�����ӥ�</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_conveni($fetch[$i]['sr_conveni'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_super')) { ?>
	<tr>
		<td class="m1">�褯�Ԥ������ѡ�</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_super($fetch[$i]['sr_super'])?></td>
<? } ?>
	</tr>
<? } ?>
<?
// �ɲåץ�ե�����
$search = new search_class;
for ($i = 0; $i < $nrow; $i++) {
	$ary = explode(',', $fetch[$i]['sr_ma_profile']);
	foreach ($ary as $profile_cd)
		$profile_ary[$i][$search->ma_profile_cd[$profile_cd]][] = $profile_cd;
}
foreach ($search->ma_profile_tbl as $profile_id => $profile_date) {
	$diff = false;
	for ($i = 1; $i < $nrow; $i++) {
		if ($profile_ary[0][$profile_id] != $profile_ary[$i][$profile_id]) {
			$diff = true;
			break;
		}
	}
	if ($diff) {
?>
	<tr>
		<td class="m1"><?=htmlspecialchars($profile_date->profile_name)?></td>
<?
		for ($i = 0; $i < $nrow; $i++) {
?>
		<td class="n1"><? show_ma_profile($profile_ary[$i][$profile_id])?></td>
<?
		}
?>
	</tr>
<?
	}
}
?>
<? if (diff_check('sr_genre')) { ?>
	<tr>
		<td class="m1">��̣������</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_genre($fetch[$i]['sr_genre'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_family_sex_m') || diff_check('sr_family_sex_w')) { ?>
	<tr>
		<td class="m1">��²������</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_family_sex($fetch[$i]['sr_family_sex_m'], $fetch[$i]['sr_family_sex_w'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_family_rel')) { ?>
	<tr>
		<td class="m1">��²��³��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_family_rel($fetch[$i]['sr_family_rel'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_family_age_from') || diff_check('sr_family_age_to')) { ?>
	<tr>
		<td class="m1">��²��ǯ��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_age($fetch[$i]['sr_family_age_from'], $fetch[$i]['sr_family_age_to'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_child')) { ?>
	<tr>
		<td class="m1">�Ҷ���̵ͭ</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_child($fetch[$i]['sr_child'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_child_age_from') || diff_check('sr_child_age_to')) { ?>
	<tr>
		<td class="m1">�Ҷ���ǯ��</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_age($fetch[$i]['sr_child_age_from'], $fetch[$i]['sr_child_age_to'])?></td>
<? } ?>
	</tr>
<? } ?>
<? if (diff_check('sr_zip')) { ?>
	<tr>
		<td class="m1">͹���ֹ�</td>
<? for ($i = 0; $i < $nrow; $i++) { ?>
		<td class="n1"><? show_condition($fetch[$i]['sr_zip'])?></td>
<? } ?>
	</tr>
<? } ?>
</table>
<br>

<div align="center">
<form>
	<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
