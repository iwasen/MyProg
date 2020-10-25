<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�桼������ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");

// �桼��ID���᡼�륢�ɥ쥹��Ƚ��
if (is_numeric($_GET['user_id']))
	$where = "ups_user_id={$_GET['user_id']}";
else
	$where = "ups_mail_addr='{$_GET['user_id']}'";

// �桼����Ͽ�������
$sql = "SELECT *"
		. " FROM t_user_personal"
		. " JOIN t_user_manage ON umg_user_id=ups_user_id"
		. " JOIN t_user_car ON ucr_user_id=ups_user_id"
		. " JOIN t_user_campaign ON ucm_user_id=ups_user_id"
		. " JOIN t_user_status ON ust_user_id=ups_user_id"
		. " LEFT JOIN t_mail_cancel_result ON mcr_cancel_cd=ust_cancel_cd"
		. " WHERE $where";

$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	redirect("not_found.php");
if (pg_num_rows($result) >= 2)
	redirect("list.php?mail_addr={$_GET['user_id']}");
$fetch = pg_fetch_object($result, 0);
$user_id = $fetch->ups_user_id;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header('�桼������ɽ��') ?>

<div align="center">
<form>
<table border=0 cellspacing=2 cellpadding=3 width="90%" style="font-size:85%">
	<tr>
		<td class="m0">���桼����Ͽ����</td>
		<td align="right">
			<input type="button" value="���Ѥ�����" onclick="location.href='result_input.php?user_id=<?=$user_id?>'">
			<input type="button" value="����������" onclick="location.href='mail_log.php?user_id=<?=$user_id?>'">
			<input type="button" value="���Ĥ��롡" onclick="window.close()">
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="90%" style="font-size:85%">
	<tr class="subhead">
		<td colspan=2>�桼���Ŀ;���</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�桼���ɣ�</td>
		<td class="n1"><?=$fetch->ups_user_id?></td>
	</tr>
	<tr>
		<td class="m1">̾���ʴ�����</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_name_kanji)?></td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_name_kana)?></td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�</td>
		<td class="n1"><?=$fetch->ups_zip_cd?></td>
	</tr>
	<tr>
		<td class="m1">����0</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_address0)?></td>
	</tr>
	<tr>
		<td class="m1">����1</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_address1)?></td>
	</tr>
	<tr>
		<td class="m1">����2</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_address2)?></td>
	</tr>
	<tr>
		<td class="m1">�����ֹ�</td>
		<td class="n1"><?=$fetch->ups_tel_no?></td>
	</tr>
	<tr>
		<td class="m1">���������ֹ�</td>
		<td class="n1"><?=$fetch->ups_keitai_no?></td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_sex_cd($fetch->ups_sex_cd)?></td>
	</tr>
	<tr>
		<td class="m1">ǯ��</td>
		<td class="n1"><?=$fetch->ups_age?></td>
	</tr>
	<tr class="subhead">
		<td colspan=2>�桼����������</td>
	</tr>
	<tr>
		<td class="m1">������CD</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_customer_cd)?></td>
	</tr>
	<tr>
		<td class="m1">����Ź������</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_dealer_cd)?></td>
	</tr>
	<tr>
		<td class="m1">����Ź̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_dealer_name)?></td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class="n1"><?=decode_oubo_shubetsu($fetch->umg_oubo_shubetsu)?></td>
	</tr>
	<tr>
		<td class="m1">�»ܲ�</td>
		<td class="n1"><?=$fetch->umg_jisshi_kai?></td>
	</tr>
	<tr>
		<td class="m1">������˾��̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_kounyu_kibou_car)?></td>
	</tr>
	<tr>
		<td class="m1">������Ͽǯ��</td>
		<td class="n1"><?=$fetch->umg_regist_ym?></td>
	</tr>
	<tr>
		<td class="m1">����������Ź����</td>
		<td class="n1"><?=format_datetime($fetch->umg_oubo_date)?></td>
	</tr>
	<tr>
		<td class="m1">���ҡ�¾�Ҷ�ʬ</td>
		<td class="n1"><?=decode_jisha_tasha_kubun($fetch->umg_jisha_tasha_kubun)?></td>
	</tr>
	<tr>
		<td class="m1">�ե�����ʬ</td>
		<td class="n1"><?=decode_follow_kubun($fetch->umg_follow_kubun)?></td>
	</tr>
	<tr>
		<td class="m1">������</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_comment)?></td>
	</tr>
	<tr>
		<td class="m1">ô��Ź��CD</td>
		<td class="n1"><?=$fetch->umg_tantou_shop_cd?></td>
	</tr>
	<tr>
		<td class="m1">ô��Ź��̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_tantou_shop_name)?></td>
	</tr>
	<tr>
		<td class="m1">ô�������å�̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_tantou_staff_name)?></td>
	</tr>
	<tr class="subhead">
		<td colspan=2>����ͭ����</td>
	</tr>
	<tr>
		<td class="m1">��ξ�᡼����</td>
		<td class="n1"><?=decode_car_maker($fetch->ucr_car_maker)?></td>
	</tr>
	<tr>
		<td class="m1">�ּ�̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->ucr_car_model)?></td>
	</tr>
	<tr>
		<td class="m1">����ָ�</td>
		<td class="n1"><?=$fetch->ucr_jikai_shaken?></td>
	</tr>
	<tr>
		<td class="m1">��������Ź������</td>
		<td class="n1"><?=$fetch->ucr_dealer_cd?></td>
	</tr>
	<tr>
		<td class="m1">��������Ź����</td>
		<td class="n1"><?=decode_dealer_type($fetch->ucr_dealer_type)?></td>
	</tr>
	<tr>
		<td class="m1">��������Ź̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->ucr_dealer_name)?></td>
	</tr>
	<tr class="subhead">
		<td colspan=2>�����ڡ���������</td>
	</tr>
	<tr>
		<td class="m1">��Ź��Ū</td>
		<td class="n1"><?=decode_raiten_mokuteki($fetch->ucm_raiten_mokuteki)?></td>
	</tr>
	<tr>
		<td class="m1">����¾��ͳ</td>
		<td class="n1"><?=htmlspecialchars($fetch->ucm_sonota_riyuu)?></td>
	</tr>
	<tr>
		<td class="m1">CPǧ�Τ���¾����</td>
		<td class="n1"><?=htmlspecialchars($fetch->ucm_cp_ninchi)?></td>
	</tr>
	<tr>
		<td class="m1">�����ڡ���ǧ��</td>
		<td class="n1"><?=decode_campaign_ninchi($fetch->ucm_campaign_ninchi)?></td>
	</tr>
	<tr>
		<td class="m1">�����󶡵���</td>
		<td class="n1"><?=decode_jouhou_teikyou_kyodaku($fetch->ucm_jouhou_teikyou_kyodaku)?></td>
	</tr>
	<tr class="subhead">
		<td colspan=2>���ơ���������</td>
	</tr>
	<tr>
		<td class="m1">���֥�����</td>
		<td class="n1"><?=decode_user_status($fetch->ust_status)?></td>
	</tr>
	<tr>
		<td class="m1">��³��졼�����᡼��</td>
		<td class="n1"><?=$fetch->rel_stop_flag == DBTRUE ? '������' : '�̾��ۿ�'?></td>
	</tr>
	<tr>
		<td class="m1">�᡼����ɲ����ͳ</td>
		<td class="n1"><?=htmlspecialchars($fetch->mcr_cancel_text)?></td>
	</tr>
</table>
</form>
</div>

<? center_footer() ?>

</body>
</html>
