<?
$print_msg = "";
$Chk_flg = TRUE;

$mag_cycle = "00";

// �֥��ƥ��꡼�פ��Ѵ�
if (count($mag_catg_chk) == 2) {
	$mag_catg_1 = $mag_catg_chk[0];
	$mag_catg_2 = $mag_catg_chk[1];
}
if ($mm_update) {
	if (count($mag_catg_chk) == 0) {
		$mag_catg_chk[0] = $mag_catg_1;
		$mag_catg_chk[1] = $mag_catg_2;
	}
}
// ���طʡפ��Ѵ�
if (count($mag_bg_chk) == 2) {
	$mag_bg_1 = $mag_bg_chk[0];
	$mag_bg_2 = $mag_bg_chk[1];
}
if ($mm_update) {
	if (count($mag_bg_chk) == 0) {
		$mag_bg_chk[0] = $mag_bg_1;
		$mag_bg_chk[1] = $mag_bg_2;
	}
}

// ��ȯ�Լ����פ�Ĺ�������å�
$mag_cycle_day = i18n_ja_jp_hantozen($mag_cycle_day, "K");
if (strlen($mag_cycle_day) > 40)
{
	$print_msg = $print_msg . "��ȯ�Լ����פϡ����ѣ���ʸ����������Ϥ��Ƥ���������<br>\n";
}
if (strlen($mag_cycle_day) == 0)
{
	$print_msg = $print_msg . "��ȯ�Լ����פ���������Ƥ��ʤ��褦�Ǥ���<br>\n";
}

// �֣գң̡פ��Ѵ�
if (strlen($mag_hp) == 0) {
	$mag_hp = "http://";
} else {
	if (strlen($mag_hp) > 80)
	{
		$print_msg = $print_msg . "�֣գң̡פϡ�Ⱦ�ѣ���ʸ����������Ϥ��Ƥ���������<br>\n";
	}
}

// �֤���롦net��ID�פΥ����å�
if ($melmel_id != "") {
	if (strlen($melmel_id) > 5)
	{
		$print_msg =  "�֤���롦net��ID�פϡ�Ⱦ�ѣ�ʸ�������Ϥ��Ƥ���������<br>\n";
	}
	if ( !(ereg("^[0-9]{5}$", $melmel_id)) )
	{
		$print_msg =  "�֤���롦net��ID�פϡ�Ⱦ�ѣ�ʸ�������Ϥ��Ƥ���������<br>\n";
	}
}
// �֥��ޥ�ȯ�Ը��᡼�륢�ɥ쥹�פΥ����å�
if (strlen($mail_add_publish) == 0)
{
	$print_msg =  "�֥��ޥ�ȯ�Ը��᡼�륢�ɥ쥹�פ���������Ƥ��ʤ��褦�Ǥ���<br>\n";
} else {
	// �֥��ޥ�ȯ�Ը��᡼�륢�ɥ쥹�פ�Ĺ�������å�
	if (strlen($mail_add_publish) > 50)
	{
		$print_msg = $print_msg . "�֥��ޥ�ȯ�Ը��᡼�륢�ɥ쥹�פϡ�����ʸ����������Ϥ��Ƥ���������<br>\n";
	} else {
		if ( !(ereg ("^([0-9a-zA-Z._-]+)(@[0-9a-zA-Z._-]+)$", $mail_add_publish)) )
		{
			$print_msg = $print_msg . "�֥��ޥ�ȯ�Ը��᡼�륢�ɥ쥹�פ����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>\n";
		}
	}
}
// �֥��ޥ������ȥ�פΥ����å�
if (strlen($mag_nm) == 0)
{
	$print_msg = $print_msg . "�֥��ޥ������ȥ�פ���������Ƥ��ʤ��褦�Ǥ���<br>\n";
} else {
	// �֥��ޥ������ȥ�פ�Ĺ�������å�
	$mag_nm = i18n_ja_jp_hantozen($mag_nm, "K");
	if (strlen($mag_nm) > 80)
	{
		$print_msg = $print_msg . "�֥��ޥ������ȥ�פϡ����ѣ���ʸ����������Ϥ��Ƥ���������<br>\n";
	}
	//else {
		//$mag_nm_chk = i18n_ja_jp_hantozen($mag_nm, "K");
		//if (strcmp($mag_nm, $mag_nm_chk) != 0)
		//{
		//	$print_msg = $print_msg . "�֥��ޥ������ȥ�פˤϡ�Ⱦ�ѥ��ʤϻ��ѤǤ��ޤ���<br>\n";
		//}
	//}
}
// �֥��ޥ��Ҳ�ʸ[����]�פΥ����å�
if (strlen($mag_intro_long) == 0)
{
	$print_msg = $print_msg . "�֥��ޥ��Ҳ�ʸ[����]�פ���������Ƥ��ʤ��褦�Ǥ���<br>\n";
} else {
	// �֥��ޥ��Ҳ�ʸ[����]�פ�Ĺ�������å�
	$mag_intro_long = i18n_ja_jp_hantozen($mag_intro_long, "K");
	if (strlen($mag_intro_long) > 500)
	{
		$print_msg = $print_msg . "�֥��ޥ��Ҳ�ʸ[����]�פϡ����ѣ�����ʸ����������Ϥ��Ƥ���������<br>\n";
	}
}
// �֥��ޥ��Ҳ�ʸ[���硼��]�פΥ����å�
if (strlen($mag_intro_short) == 0)
{
	$print_msg =$print_msg . "�֥��ޥ��Ҳ�ʸ[���硼��]�פ���������Ƥ��ʤ��褦�Ǥ���<br>\n";
} else {
	// �֥��ޥ��Ҳ�ʸ[���硼��]�פ�Ĺ�������å�
	$mag_intro_short = i18n_ja_jp_hantozen($mag_intro_short, "K");
	if (strlen($mag_intro_short) > 100)
	{
		$print_msg = $print_msg . "�֥��ޥ��Ҳ�ʸ[���硼��]�פϡ����ѣ���ʸ����������Ϥ��Ƥ���������<br>\n";
	}
}
// �֥����ספΥ����å�
if (strlen($mag_type) == 0)
{
	$print_msg = $print_msg . "�֥����ספ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
}
// ��ȯ�Լ����פΥ����å�
//	if (strlen($mag_cycle) == 0)
//	{
//		$print_msg = $print_msg . "��ȯ�Լ����פ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
//	}
// �֥��ƥ���פΥ����å�
if (count($mag_catg_chk) != 2)
{
	$print_msg = $print_msg . "�֥��ƥ���פ���������ְ�äƤ��ޤ����������򤷤Ƥ���������<br>\n";
}
// ���ɤ��طʡפΥ����å�
if (count($mag_bg_chk) != 2)
{
	$print_msg = $print_msg . "���ɤ��طʡפ���������ְ�äƤ��ޤ����������򤷤Ƥ���������<br>\n";
}
// �֤Ȥ����ɤ�Ǥ�餤�������̡פΥ����å�
if (strlen($mag_reader_sex) == 0)
{
	$print_msg = $print_msg . "�֤Ȥ����ɤ�Ǥ�餤�������̡פ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
}
// �֤Ȥ����ɤ�Ǥ�餤����ǯ��פΥ����å�
if (strlen($mag_reader_age) == 0)
{
	$print_msg = $print_msg . "�֤Ȥ����ɤ�Ǥ�餤����ǯ��פ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
}
// �֤Ȥ����ɤ�Ǥ�餤�������ꥢ�פΥ����å�
if (strlen($mag_reader_area) == 0)
{
	$print_msg = $print_msg . "�֤Ȥ����ɤ�Ǥ�餤�������ꥢ�פ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
}
// �֤Ȥ����ɤ�Ǥ�餤�������ȡפΥ����å�
if (strlen($mag_reader_occup) == 0 || $mag_reader_occup == "���򤷤Ƥ�������")
{
	$print_msg = $print_msg . "�֤Ȥ����ɤ�Ǥ�餤�������ȡפ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
}
// �֥Хå��ʥ�С�������������פΥ����å�
if (strlen($mag_bn_pub) == 0) {
	$print_msg = $print_msg . "�֥Хå��ʥ�С�������������פ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
}
// �֥ץ쥼��ȱ�����������פΥ����å�
if (strlen($mag_present_ins) == 0) {
	$print_msg = $print_msg . "�֥ץ쥼��ȱ�����������פ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
}
// �֤����Ѥ󥪥ꥸ�ʥ�פΥ����å�
if (strlen($mag_original_flg) == 0) {
	$print_msg = $print_msg . "�֤����Ѥ󥪥ꥸ�ʥ�פ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
}
// ���ۿ�������Υ᡼��פΥ����å�
if (strlen($send_notice_flg) == 0) {
	$print_msg = $print_msg . "���ۿ�������Υ᡼��פ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>\n";
}

// �֣̤ͣξ��פΥ����å�
//	if (strlen($ml_type) == 0)
//	{
//		$print_msg = $print_msg . "�֣̤ͣξ��פ����򤵤�Ƥ��ޤ���<br>\n";
//	}


if ($print_msg != "") {
	$Chk_flg = FALSE;
}

?>