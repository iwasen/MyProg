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

// ��JOBɽ��
function show_child_job($job_id) {
	$sql = "SELECT jb_job_id,jb_job_name FROM t_job WHERE jb_parent_job_id=$job_id ORDER BY jb_job_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow) {
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			echo $fetch->jb_job_id, ' ', htmlspecialchars($fetch->jb_job_name), "<br>\n";
		}
	} else
		echo '̵��';
}

// ��JOBɽ��
function show_parent_job($parent_job_id) {
	if (parent_job_id == null)
		echo '̵��';
	else {
		$sql = "SELECT jb_job_id,jb_job_name FROM t_job WHERE jb_job_id=$parent_job_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			echo $fetch->jb_job_id, ' ', htmlspecialchars($fetch->jb_job_name), "\n";
		}
	}
}

set_global2('project', '�ץ������ȴ���', '���������ɽ��', BACK_TOP);

$sql = "SELECT *"
		. " FROM t_job"
		. " LEFT JOIN t_enquete ON en_enquete_id=jb_enquete_id"
		. " LEFT JOIN t_search ON sr_search_id=jb_search_id"
		. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
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

<table border=0 cellspacing=2 cellpadding=3 width='95%'>
	<tr>
		<td class="m0" colspan=2>������־���</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�����ID</td>
		<td class="n1" width="80%"><?=$job_id?></td>
	</tr>
	<tr>
		<td class="m1">�����̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->jb_job_name)?></td>
	</tr>
	<tr>
		<td class="m1">�ۿ����ƥ���</td>
		<td class="n1"><?=decode_job_category($fetch->jb_category)?></td>
	</tr>
	<tr>
		<td class="m1">�᡼�����</td>
		<td class="n1"><?=decode_mail_format($fetch->jb_mail_format)?></td>
	</tr>
	<tr>
		<td class="m1">����ּ���</td>
		<td class="n1"><?=decode_job_type($fetch->jb_job_type)?></td>
	</tr>
<? if ($fetch->jb_job_type == 0) { ?>
	<tr>
		<td class="m1">�ҥ쥹�ݥ�JOB</td>
		<td class="n1"><? show_child_job($job_id) ?></td>
	</tr>
<? } else { ?>
	<tr>
		<td class="m1">��ȯ��JOB</td>
		<td class="n1"><? show_parent_job($fetch->jb_parent_job_id) ?></td>
	</tr>
<? } ?>
	<tr>
		<td class="m0" colspan=2>�����󥱡��Ⱦ���</td>
	</tr>
	<tr>
		<td class="m1">���󥱡���̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->en_title) ?></td>
	</tr>
<? if ($fetch->en_enq_type == 2) { ?>
	<tr>
		<td class="m1">���󥱡���URL</td>
		<td class="n1"><?=htmlspecialchars($fetch->jb_enq_web_url) ?></td>
	</tr>
<? } ?>
	<tr>
		<td class="m1">���󥱡��ȼ���</td>
		<td class="n1"><?=decode_enq_type($fetch->en_enq_type)?></td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȳ�����</td>
		<td class="n1"><?=format_datetime($fetch->en_start_date)?></td>
	</tr>
	<tr>
		<td class="m1">���󥱡��Ƚ�λ��</td>
		<td class="n1"><?=format_datetime($fetch->en_end_date)?></td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȥݥ����</td>
		<td class="n1"><?=number_format($fetch->en_point)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>��ȯ�����</td>
	</tr>
	<tr>
		<td class="m1">ȯ��������</td>
		<td class="n1"><?=format_datetime($fetch->jb_start_date)?></td>
	</tr>
	<tr>
		<td class="m1">ȯ����λ��</td>
		<td class="n1"><?=format_datetime($fetch->jb_end_date)?></td>
	</tr>
	<tr>
		<td class="m1">����ȯ����</td>
		<td class="n1"><?=number_format($fetch->jb_send_num)?></td>
	</tr>
	<tr>
		<td class="m1">ʬ��ȯ���ֳ�</td>
		<td class="n1"><?=number_format($fetch->jb_bunkatsu_kankaku)?> ��</td>
	</tr>
	<tr>
		<td class="m1">ʬ��ȯ�����</td>
		<td class="n1"><?=number_format($fetch->jb_bunkatsu_kaisuu)?> ��</td>
	</tr>
	<tr>
		<td class="m1">�����ݥ����</td>
		<td class="n1"><?=number_format($fetch->jb_point_jushin)?></td>
	</tr>
	<tr>
		<td class="m1">�ֿ��ݥ����</td>
		<td class="n1"><?=number_format($fetch->en_point)?></td>
	</tr>
<? if ($fetch->jb_job_type == '0') { ?>
	<tr>
		<td class="m1">�����оݥ�˥���</td>
		<td class="n1"><? show_virgin_mail($fetch->jb_virgin_mail)?></td>
	</tr>
<? } elseif ($fetch->jb_job_type == '1') { ?>
	<tr>
		<td class="m1">ȯ�������ߥ�</td>
		<td class="n1"><? show_send_timing($fetch->jb_send_timing, $fetch->jb_send_timing_h, $fetch->jb_send_timing_d)?></td>
	</tr>
	<tr>
		<td class="m1">�귿����</td>
		<td class="n1"><? show_teikei_shori($job_id) ?></td>
	</tr>
<? } else { ?>
	<tr>
		<td class="m1">����å�������</td>
		<td class="n1"><?=$fetch->jb_ct_id?></td>
	</tr>
<? } ?>
	<tr>
		<td class="m0" colspan=2>���������</td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class="n1"><? show_monitor_type($fetch->sr_type)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><? show_sex($fetch->sr_sex)?></td>
	</tr>
	<tr>
		<td class="m1">ǯ��</td>
		<td class="n1"><? show_age($fetch->sr_age_from, $fetch->sr_age_to)?></td>
	</tr>
	<tr>
		<td class="m1">̤����</td>
		<td class="n1"><? show_mikikon($fetch->sr_mikikon)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><? show_shokugyou($fetch->sr_shokugyou)?></td>
	</tr>
	<tr>
		<td class="m1">�ȼ�</td>
		<td class="n1"><? show_gyoushu($fetch->sr_gyoushu)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><? show_shokushu($fetch->sr_shokushu)?></tr>
	</tr>
	<tr>
		<td class="m1">�ｻ�ϰ�</td>
		<td class="n1"><? show_area($fetch->sr_kyojuu)?></td>
	</tr>
	<tr>
		<td class="m1">��̳���ϰ�</td>
		<td class="n1"><? show_area($fetch->sr_kinmu)?></td>
	</tr>
	<tr>
		<td class="m1">�۶��Ԥ�̵ͭ</td>
		<td class="n1"><? show_spouse_flg($fetch->sr_spouse_flg)?></td>
	</tr>
	<tr>
		<td class="m1">�۶��Ԥ�ǯ��</td>
		<td class="n1"><? show_age($fetch->sr_spouse_age_from, $fetch->sr_spouse_age_to)?></td>
	</tr>
	<tr>
		<td class="m1">�۶��Ԥο���</td>
		<td class="n1"><? show_shokugyou($fetch->sr_spouse_shokugyo)?></td>
	</tr>
	<tr>
		<td class="m1">�Ҷ���̵ͭ</td>
		<td class="n1"><? show_have_child($fetch->sr_have_child)?></td>
	</tr>
	<tr>
		<td class="m1">�Ҷ�������</td>
		<td class="n1"><? show_child_sex($fetch->sr_child_sex)?></td>
	</tr>
	<tr>
		<td class="m1">�Ҷ���ǯ��</td>
		<td class="n1"><? show_age($fetch->sr_child_age_from, $fetch->sr_child_age_to)?></td>
	</tr>
	<tr>
		<td class="m1">�Ҷ��γ�ǯ</td>
		<td class="n1"><? show_child_gakunen($fetch->sr_child_gakunen)?></td>
	</tr>
	<tr>
		<td class="m1">���˥��ؤ�̵ͭ</td>
		<td class="n1"><? show_senior_flg($fetch->sr_senior_flg)?></td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class="n1"><? show_housing_form($fetch->sr_housing_form)?></td>
	</tr>
	<tr>
		<td class="m1">�֤ν�ͭ</td>
		<td class="n1"><? show_have_car($fetch->sr_have_car)?></td>
	</tr>
	<tr>
		<td class="m1">�褯�Ԥ�����ӥ�</td>
		<td class="n1"><? show_conveni($fetch->sr_conveni)?></td>
	</tr>
	<tr>
		<td class="m1">�褯�Ԥ������ѡ�</td>
		<td class="n1"><? show_super($fetch->sr_super)?></td>
	</tr>
<?
// �ɲåץ�ե�����
$search = new search_class;
$ary = explode(',', $fetch->sr_ma_profile);
foreach ($ary as $profile_cd)
	$profile_ary[$search->ma_profile_cd[$profile_cd]][] = $profile_cd;

foreach ($search->ma_profile_tbl as $profile_id => $profile_date) {
?>
	<tr>
		<td class="m1"><?=htmlspecialchars($profile_date->profile_name)?></td>
		<td class="n1"><? show_ma_profile($profile_ary[$profile_id])?></td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">��̣������</td>
		<td class="n1"><? show_genre($fetch->sr_genre)?></td>
	</tr>
<? /*
	<tr>
		<td class="m1">��²������</td>
		<td class="n1"><? show_family_sex($fetch->sr_family_sex_m, $fetch->sr_family_sex_w)?></td>
	</tr>
	<tr>
		<td class="m1">��²��³��</td>
		<td class="n1"><? show_family_rel($fetch->sr_family_rel)?></td>
	</tr>
	<tr>
		<td class="m1">��²��ǯ��</td>
		<td class="n1"><? show_age($fetch->sr_family_age_from, $fetch->sr_family_age_to)?></td>
	</tr>
	<tr>
		<td class="m1">�Ҷ���̵ͭ</td>
		<td class="n1"><? show_child($fetch->sr_child)?></td>
	</tr>
	<tr>
		<td class="m1">�Ҷ���ǯ��</td>
		<td class="n1"><? show_age($fetch->sr_child_age_from, $fetch->sr_child_age_to)?></td>
	</tr>
*/ ?>
	<tr>
		<td class="m1">�ɲãӣѣ�</td>
		<td class="n1"><? show_condition($fetch->sr_sql)?></td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�</td>
		<td class="n1"><? show_condition($fetch->sr_zip)?></td>
	</tr>
	<tr>
		<td class="m1">�о�ȯ�������</td>
		<td class="n1"><? show_condition($fetch->sr_taishou_hasshin)?></td>
	</tr>
	<tr>
		<td class="m1">�оݥ쥹�ݥ󥹥����</td>
		<td class="n1"><? show_condition($fetch->sr_taishou_response)?></td>
	</tr>
	<tr>
		<td class="m1">����ȯ�������</td>
		<td class="n1"><? show_condition($fetch->sr_jogai_hasshin)?></td>
	</tr>
	<tr>
		<td class="m1">�����쥹�ݥ󥹥����</td>
		<td class="n1"><? show_condition($fetch->sr_jogai_response)?></td>
	</tr>
	<tr>
		<td class="m1">���С��ꥹ��</td>
		<td class="n1"><? show_condition($fetch->sr_member_list)?></td>
	</tr>
</table>
<br>
<div align="center">
<form>
	<input type="button" value="����롡" onclick="history.back()">
</form>
<div>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
