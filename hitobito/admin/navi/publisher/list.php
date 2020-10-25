<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���ޥ������ڡ���
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
include("$inc/select.php");
include("$inc/list.php");
include("$inc/csv.php");
include("$inc/format.php");
include("$inc/decode.php");

// �ڡ����إå���������
$header = new header_class('�᡼��ޥ��������', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// ɽ���Կ�
$limit = $list->get_limit_offset();

// �ꥹ�ȥإå�����
$list->set_header('���ޥ�ID', 'center', 'mmg_melmaga_id');
$list->set_search_text('melmaga_id', $_REQUEST['melmaga_id'], 'number', 8);

$list->set_header('���ޥ�̾', 'left', 'mmg_melmaga_name');
$list->set_search_text('melmaga_name', $_REQUEST['melmaga_name'], 'kanji', 10);

$list->set_header('�ʥӥڡ���̾', 'left', 'nvp_title');
$list->set_search_text('nvp_title', $_REQUEST['nvp_title'], 'kanji', 10);

$list->set_header('ȯ�Լ�', 'left', 'nav_name1 || nav_name2');
$list->set_search_text('navi_name', $_REQUEST['navi_name'], 'kanji', 10);

$list->set_header('�ɼԿ�', 'right', 'mmn_reader_cnt');
$list->set_search_select('reader_cnt', select_over_count('���٤�', $_REQUEST['reader_cnt']));

$list->set_header('�ۿ����', 'right', 'mmn_publish_cnt');
$list->set_search_select('publish_cnt', select_over_count('���٤�', $_REQUEST['publish_cnt']));

$list->set_header('�ǽ�ȯ����', 'center', 'mmn_last_publish_date');
$list->set_search_select('last_publish', select_year_month('���٤�', $_REQUEST['last_publish']));

$list->set_header('���Î�����', 'center', 'mmg_status');
$list->set_search_select('status', select_open_status('���٤�', $_REQUEST['status']));

// �ꥹ�ȥǡ�������
$sql = "SELECT mmg_melmaga_id, mmg_melmaga_name, nvp_title, trim(nav_name1||' '||COALESCE(nav_name2,'')) AS nav_name, mmn_reader_cnt, mmn_publish_cnt, mmn_last_publish_date, mmg_status, nvp_blog_id, nav_navi_id"
		. " FROM t_melmaga"
		. " LEFT JOIN t_navi_page ON mmg_melmaga_id = nvp_melmaga_id"
		. " LEFT JOIN c_melmaga_count ON mmg_melmaga_id = mmn_melmaga_id"
		. " LEFT JOIN t_navi ON nvp_navi_id = nav_navi_id"
		. $list->get_where()
		. $list->get_order_by(0, 1) . $limit;
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->mmg_melmaga_id);
	$list->set_data($fetch->mmg_melmaga_name, "edit.php?melmaga_id=$fetch->mmg_melmaga_id", '���ޥ���Ͽ�����ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=$fetch->nvp_blog_id", 'Blog��Ͽ�����ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->nav_name, "$top/navi/navi/edit.php?navi_id=$fetch->nav_navi_id", '�ʥ���Ͽ�����ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->mmn_reader_cnt);
	$list->set_data($fetch->mmn_publish_cnt);
	$list->set_data(format_datetime($fetch->mmn_last_publish_date, ''));
	$list->set_data(decode_open_status($fetch->mmg_status));
}

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("publisher.csv");
	$csv->export_data($list);
	exit;
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function mail_search(f) {
	var f = document.list;
	if (f.mail_addr.value == ''){
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.mail_addr.focus();
		return false;
	}
	location.href = "personal.php?mail_addr=" + f.mail_addr.value;
}

//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc">
<?php $list->disp_page_line($nrow); ?>
		</td>
		<td class="lb">
			<span class="lc">�᡼�륢�ɥ쥹</span>
		</td>
		<td class="lb">
			<input type="text" name="mail_addr" size=20 maxlength=100>
			<input type="button" value="�ɼԸ���" onclick="mail_search(this)">
			<input type="submit" name="export" value="�������ݡ���">
			<input type="submit" value="�Ƹ���">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
