<?
$TC_HAKKOUSHA = "aa4488";
$TC_DOKUSHA = "44aa88";
$TC_MASTER = "aa8844";
$TC_MASTER_MAINTE = "9f8fc0";
$TC_OTHER = "888888";

$TT_kari_touroku = "����Ͽ��ǧ����";
$TT_hon_touroku = "����Ͽ��ǧ����";
$TT_teishi = "���ޥ���߽���";
$TT_riba_regist = "��Фå�����Ͽ";
$TT_hinshitsu = "�ʼ������å�";
$TT_pub_msg = "�ֻ�̳�ɤ���Τ��Τ餻������";
$TT_hakkousha_mainte = "ȯ�ԼԾ�����ƥʥ�";
$TT_letter_hakkousha = "ȯ�Լ԰��ƥ᡼��";

$TT_dokusha_mainte = "�ɼԾ�����ƥʥ�";
$TT_addr_search = "�ɼԸ���/�������";
$TT_dokusha_ikkatsu = "�ɼ԰����Ͽ/���";
$TT_melonpai_regist = "���ޥ��ʥ���Ͽ";
$TT_melonpai_kannri = "���ޥ��ʥӴ���";
$TT_max_setting = "���ޥ��ʥӾ������";
$TT_disabled_adr = "���ɶػߥ᡼�륢�ɥ쥹��Ͽ";

$TT_letter_melonpan = "����Ѥ�쥿���ۿ�";
$TT_letter_bakyun = "�С����塼���ۿ�";
$TT_letter_tsuushin = "����Ѥ��̿��ۿ�";
$TT_haishin_log = "��̳�ɥ᡼���ۿ���";
$TT_hitorigoto = "�ֺ����ΤҤȤꤴ�ȡ�����";
$TT_whats_new = "��What's New������";
$TT_footer = "�إå������եå����ǡ�����Ͽ";
$TT_haishin_yoyaku = "�ۿ�ͽ�����";
$TT_kanri_suuji = "��������ɽ��";
$TT_osusume_log = "�������������";
$TT_henkou_list = "��Ͽ�����ѹ��ꥹ��";
$TT_sabun_point = "�ɼ���Ͽ��/��ʬ�ݥ������Ϳ";
$TT_add_point = "ȯ�Լԡ��ɼԡ����ޥ��ʥ�/�ݥ������Ϳ";
$TT_point_list = "�ݥ���Ⱦ�������";
$TT_letter_html = "����Ѥ�HTML�쥿��";

$TT_present = "�ץ쥼��ȱ������";
$TT_mail_cleaning = "�᡼�륯�꡼�˥󥰥����ӥ�";
$TT_banner_set = "����Хʡ�����";
$TT_click = "���𥯥�å�������";

$TT_mail_template = "�����᡼��ƥ�ץ졼��";
$TT_batch_schedule = "�Хå��������塼��";
$TT_server_mainte = "�ۿ�ͽ��ػ�����";
$TT_tassei = "�ɼ�ã���᡼������";

if ($cookie_admin_id == "") {
	$cookie_admin_id = "";
	header ("location: ../login.php");
	exit;
} else {
	$sql = "SELECT * FROM M_ADMIN, M_SUPERPWD WHERE admin_id = '$cookie_admin_id'";
	$result = db_exec($sql);
	if (pg_numrows($result) <= 0) {
		$cookie_admin_id = "";
		header ("location: ../login.php");
		exit;
	}
}
?>