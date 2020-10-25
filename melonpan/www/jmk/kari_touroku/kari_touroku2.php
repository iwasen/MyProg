<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:����Ͽ��ǧ����
'******************************************************/

$title_text = $TT_kari_touroku;
$title_color = $TC_HAKKOUSHA;
$mag_id = sprintf("%06d", $mag_id);

$sql = "SELECT PUB.mail_add_main, MAG.melmel_id, MAG.mail_add_publish, MAG.mag_nm, MAG.mag_intro_long, MAG.mag_intro_short, MAG.mag_hp, TYPE.type"
	. ", MAG.mag_cycle_day, CATG1.catg as catg1, CATG2.catg as catg2, BG1.bg as bg1, BG2.bg as bg2, MAG.mag_reader_sex, MAG.mag_reader_age"
	. ", AREA.area, OCCUP.occup, MAG.mag_bn_pub, MAG.publisher_id, PUB.owner_nm_kanj"
	. " FROM M_MAGAZINE MAG, M_PUBLISHER PUB, M_MAG_TYPE TYPE, M_MAG_CATG CATG1, M_MAG_CATG CATG2, M_MAG_BG BG1, M_MAG_BG BG2, M_MAG_AREA AREA"
	. ", M_MAG_OCCUP OCCUP"
	. " WHERE MAG.mag_id = '$mag_id' AND MAG.publisher_id=PUB.publisher_id AND MAG.mag_type=TYPE.type_id"
	. " AND MAG.mag_catg_1=CATG1.catg_id AND MAG.mag_catg_2=CATG2.catg_id AND MAG.mag_bg_1=BG1.bg_id AND MAG.mag_bg_2=BG2.bg_id"
	. " AND MAG.mag_reader_area=AREA.area_id AND MAG.mag_reader_occup=OCCUP.occup_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_add_main = $fetch->mail_add_main;
	$melmel_id = $fetch->melmel_id;
	$mail_add_publish = $fetch->mail_add_publish;
	$mag_nm = $fetch->mag_nm;
	$mag_intro_long = $fetch->mag_intro_long;
	$mag_intro_short = $fetch->mag_intro_short;
	$mag_hp = $fetch->mag_hp;
	$type = $fetch->type;
	$mag_cycle_day = $fetch->mag_cycle_day;
	$catg1 = $fetch->catg1;
	$catg2 = $fetch->catg2;
	$mag_bg_1 = $fetch->bg1;
	$mag_bg_2 = $fetch->bg2;
	$mag_reader_sex = $fetch->mag_reader_sex;
	$mag_reader_age = $fetch->mag_reader_age;
	$mag_reader_area = $fetch->area;
	$mag_reader_occup = $fetch->occup;
	$mag_bn_pub = $fetch->mag_bn_pub;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$publisher_id = $fetch->publisher_id;
	if ($mag_reader_sex == 1) {
		$mag_reader_sex = "����";
	} elseif ($mag_reader_sex == 2) {
		$mag_reader_sex = "����";
	} else {
		$mag_reader_sex = "-";
	}
	if ($mag_bn_pub == 1) {
		$mag_bn_pub = "�����";
	} elseif ($mag_bn_pub == 2) {
		$mag_bn_pub = "����";
	} elseif ($mag_bn_pub == 3) {
		$mag_bn_pub = "�ǿ���Τ߸���";
	} else {
		$mag_bn_pub = "-";
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_hp(mag_hp) {
  var win;
  win = window.open(mag_hp,"pub_hp","location=yes,menubar=yes,scrollbars=yes,resizable=yes");
  win.focus();
}
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
function OnClick_mag(mag_id) {
	if (confirm("���Υ��ޥ��β���Ͽ��ǧ���ޤ���������Ǥ�����")) {
	location.href = "kari_touroku3.php?mag_id=ok" + mag_id;
	}
}function OnClick_mag2(mag_id) {
	if (confirm("���Υ��ޥ��β���Ͽ������ݤ��ޤ���������Ǥ�����")) {
	location.href = "kari_touroku3.php?mag_id=ng" + mag_id;
	}
}
//-->
</SCRIPT>

</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1">
	<center>
		<table width='80%'>
			<tr>
				<td>�����ޥ��ܺ� <font size="-1">(��ǧ/��ǧ�����Τ�ȯ�ԼԤ���������ޤ���)</font></td>
				<td align="right"><input type="button" value="ȯ�ԼԾ���" onclick='OnClick_pub("<?= $publisher_id ?>")'></td>
			</tr>
		</table>
		<table width='80%' border="1" CELLSPACING="0" CELLPADDING="3">
			<tr>
				<td class="h1">���ܥ᡼�륢�ɥ쥹</td>
				<td class="h2"><?= replace_br(htmlspecialchars($mail_add_main)) ?></td>
			</tr>
			<tr>
				<td class="h1">�����.net �ɣ�</td>
				<td class="h2"><?= replace_br($melmel_id) ?></td>
			</tr>
			<tr>
				<td class="h1" width="35%"><nobr>���ޥ�ȯ�Ը��᡼�륢�ɥ쥹</nobr></td>
				<td class="h2"><?= replace_br(htmlspecialchars($mail_add_publish)) ?></td>
			</tr>
			<tr>
				<td class="h1">���ޥ�/�ͣ�̾</td>
				<td class="h2"><?= replace_br(htmlspecialchars($mag_nm)) ?></td>
			</tr>
			<tr>
				<td class="h1" width="35%">���ޥ��Ҳ�<br><nobr>�ʥ�� 250ʸ�������</nobr></td>
				<td class="h2"><?= replace_br(htmlspecialchars($mag_intro_long)) ?></td>
			</tr>
			<tr>
				<td class="h1">���ޥ��Ҳ�<br>�ʥ��硼�� 50ʸ�������</td>
				<td class="h2"><?= replace_br(htmlspecialchars($mag_intro_short)) ?></td>
			</tr>
			<tr>
				<td class="h1" width="35%">�ۡ���ڡ���URL</td>
				<td class="h2"><a href='JavaScript:OnClick_hp("<?= $mag_hp ?>")'><?= replace_br(htmlspecialchars($mag_hp)) ?></a></td>
			</tr>
			<tr>
				<td class="h1">������</td>
				<td class="h2"><?= replace_br($type) ?></td>
			</tr>
			<tr>
				<td class="h1" width="35%">ȯ�Լ���</td>
				<td class="h2"><?= replace_br(htmlspecialchars($mag_cycle_day)) ?></td>
			</tr>
			<tr>
				<td class="h1">���ƥ���</td>
				<td class="h2"><?= htmlspecialchars($catg1) ?>��<?= htmlspecialchars($catg2) ?></td>
			</tr>
			<tr>
				<td class="h1" width="35%">�ɤ��طʡʵ�ʬ�����֡�</td>
				<td class="h2"><?= htmlspecialchars($mag_bg_1) ?>��<?= htmlspecialchars($mag_bg_2) ?></td>
			</tr>
			<tr>
				<td class="h1"><nobr>�����ɤ�Ǥ�餤���������̡�</nobr></td>
				<td class="h2"><?= replace_br($mag_reader_sex) ?></td>
			</tr>
			<tr>
				<td class="h1" width="35%">�����ɤ�Ǥ�餤������ǯ���</td>
				<td class="h2"><?= $mag_reader_age ?>��</td>
			</tr>
			<tr>
				<td class="h1"><nobr>�����ɤ�Ǥ�餤�����㥨�ꥢ��</nobr></td>
				<td class="h2"><?= replace_br($mag_reader_area) ?></td>
			</tr>
			<tr>
				<td class="h1" width="35%">�����ɤ�Ǥ�餤�����㿦�ȡ�</td>
				<td class="h2"><?= replace_br($mag_reader_occup) ?></td>
			</tr>
			<tr>
				<td class="h1">�Хå��ʥ�С� ����/�����</td>
				<td class="h2"><?= replace_br($mag_bn_pub) ?></td>
			</tr>
		</table><br>
	<input type="button" value="����ǧ���롡" onclick='OnClick_mag("<?= $mag_id ?>")'>
	<input type="button" value="��ǧ���ʤ�" onclick='OnClick_mag2("<?= $mag_id ?>")'>
	<input type="button" value="����롡" onclick="history.back()">
	</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
