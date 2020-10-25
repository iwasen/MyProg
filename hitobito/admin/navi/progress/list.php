<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӱ�����������ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/decode.php");

// �ڡ����إå���������
$header = new header_class('�����ʹԴ���', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// �ꥹ�ȥإå�����
$list->set_header('�ʥ�ID', 'center', 'nvp_navi_page_id');
$list->set_header('�ʥ�̾', 'left', 'nav_name');
$list->set_header('�᡼�륢�ɥ쥹', 'left', 'mbr_mail_addr');
$list->set_header('BlogID', 'center', 'blg_blog_id');
$list->set_header('�ʥӥڡ���̾', 'left', 'nvp_title');
$list->set_header('MLID', 'center', 'rom_room_id');
$list->set_header('�磻�磻���̾', 'left', 'rom_name');
$list->set_header('������', 'center', 'nvp_regist_date');
$list->set_header('����ͽ����', 'center', 'nvp_open_date');
$list->set_header('��������<br>CHK', 'center', 'nvp_status');
$list->set_header('���ݎÎݎ�<br>CHK', 'center', 'nvp_contents_check');
$list->set_header('������<br>�ǽ�CHK', 'center', 'nvp_open_flag');

// �ꥹ�ȥǡ�������
$sql = "SELECT nav_member_id, nvp_navi_page_id, trim(nav_name1||' '||COALESCE(nav_name2,'')) AS nav_name, mbr_mail_addr, rom_name, nvp_regist_date, nvp_open_date, nav_navi_id, rom_room_id, blg_blog_id, nvp_title, nvp_status, nvp_contents_check, nvp_open_flag"
		. ' FROM t_navi_page LEFT JOIN t_navi ON nvp_navi_id = nav_navi_id'
		. ' LEFT JOIN t_member ON nav_member_id = mbr_member_id'
		. ' LEFT JOIN t_room ON nvp_room_id = rom_room_id'
		. ' LEFT JOIN t_blog ON nvp_blog_id = blg_blog_id'
		. ' WHERE nvp_status = 1 AND nvp_open_flag IN (0,2)'
		. $list->get_order_by(0, 1) . $limit;
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$navi_page_id = $fetch->nvp_navi_page_id;

	$list->set_data($navi_page_id, "$top/navi/navi/edit.php?navi_id=$fetch->nav_navi_id", '�ʥ���Ͽ�����ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->nav_name);
	$list->set_data($fetch->mbr_mail_addr);
	$list->set_data($fetch->blg_blog_id);
	$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=" . $fetch->blg_blog_id, 'Blog��Ͽ�����ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->rom_room_id);
	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=$fetch->rom_room_id", 'ML��Ͽ�����ɽ�����ѹ����ޤ�');
	$list->set_data(format_datetime($fetch->nvp_regist_date));
	$list->set_data(format_datetime($fetch->nvp_open_date));
	$list->set_data(decode_navi_page_status($fetch->nvp_status), "javascript:change_navi_status($navi_page_id)", '����־��֤�̤��ǧ���ᤷ�ޤ�');

	switch ($fetch->nvp_contents_check) {
	case 0:
		$list->set_data(decode_navi_contents_check($fetch->nvp_contents_check), "javascript:change_contents_check0($navi_page_id)", '����ƥ�ĥ����å���ǧ���ޤ�');
		break;
	case 1:
		$list->set_data(decode_navi_contents_check($fetch->nvp_contents_check), "javascript:change_contents_check1($navi_page_id)", '����ƥ�ĥ����å���ǧ���ޤ�');
		break;
	case 2:
		$list->set_data(decode_navi_contents_check($fetch->nvp_contents_check), "javascript:change_contents_check2($navi_page_id)", '����ƥ�ĥ����å���ǧ���ޤ�');
		break;
	case 3:
		$list->set_data(decode_navi_contents_check($fetch->nvp_contents_check), "javascript:change_contents_check3($navi_page_id)", '����ƥ�ĥ����å���̤�������ᤷ�ޤ�');
		break;
	}

	switch ($fetch->nvp_open_flag) {
	case 0:
		if ($fetch->nvp_status == 1 && $fetch->nvp_contents_check == 3)
			$list->set_data(decode_navi_open_flag($fetch->nvp_open_flag), "javascript:change_open_flag0($navi_page_id)", '�����ǽ������å�������Ԥ��ޤ�');
		else
			$list->set_data(decode_navi_open_flag($fetch->nvp_open_flag));
		break;
	case 2:
		$list->set_data(decode_navi_open_flag($fetch->nvp_open_flag), "javascript:change_open_flag2($navi_page_id)", '�����ǽ������å���̤�������ᤷ�ޤ�');
		break;
	}
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function change_navi_status(navi_page_id) {
	var params = new Array();
	params['message'] = "����־����ѹ�";
	params['button1'] = "̤��ǧ���᤹"
	params['button2'] = "����󥻥�";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_status.php?navi_page_id=" + navi_page_id + "&status=0";
}
function change_contents_check0(navi_page_id) {
	var params = new Array();
	params['message'] = "����ƥ�ĥ����å�����";
	params['button1'] = "��ǧ����";
	params['button2'] = "����󥻥�";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=3";
}
function change_contents_check1(navi_page_id) {
	var params = new Array();
	params['message'] = "����ƥ�ĥ����å�����";
	params['button1'] = "��ǧ����";
	params['button2'] = "�����᤹";
	params['button3'] = "����󥻥�";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=3";
	else if (rc == 2)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=2";
}
function change_contents_check2(navi_page_id) {
	var params = new Array();
	params['message'] = "����ƥ�ĥ����å�����";
	params['button1'] = "��ǧ����";
	params['button2'] = "����󥻥�";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=3";
}
function change_contents_check3(navi_page_id) {
	var params = new Array();
	params['message'] = "����ƥ�ĥ����å�����";
	params['button1'] = "̤�������᤹";
	params['button2'] = "�����᤹";
	params['button3'] = "����󥻥�";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=0";
	else if (rc == 2)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=2";
}
function change_open_flag0(navi_page_id) {
	var params = new Array();
	params['message'] = "�������ǽ������å�����";
	params['button1'] = "������������";
	params['button2'] = "����󥻥�";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_open_flag.php?navi_page_id=" + navi_page_id + "&flag=2";
}
function change_open_flag2(navi_page_id) {
	var params = new Array();
	params['message'] = "����־����ѹ�";
	params['button1'] = "̤�������᤹";
	params['button2'] = "����󥻥�";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_open_flag.php?navi_page_id=" + navi_page_id + "&flag=0";
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post">

<?php $list->list_form(); ?>
</form>

<?php $list->disp_list(); ?>

<?php $header->page_footer(); ?>

</body>
</html>
