<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ����ʼ������ڡ���
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
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/ngword.php");
include("$inc/format.php");
include("$inc/csv.php");

// �إå���������
$header = new header_class('��󥯴���', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// �ꥹ�ȥإå�����
$list->set_header('���ID', 'center', 'nol_osusume_link_id');
$list->set_search_text('link_id', $_REQUEST['link_id'], 'number', 8);

$list->set_header('�����̾��', 'left', 'nol_link_name');
$list->set_search_text('link_name', $_REQUEST['link_name'], 'kanji', 20);

$list->set_header('���ƥ���', 'left', 'nol_link_category_id');
$list->set_search_text('category_id', $_REQUEST['category_id'], 'kanji', 20);

$list->set_header('�ʥӥڡ���', 'left', 'blg_title');
$list->set_search_text('blog_title', $_REQUEST['blog_title'], 'kanji', 20);

$list->set_header('NG���', 'left', 'nol_ng_word');
$list->set_search_select('ng_word', select_ngword('���٤�', $_REQUEST['ng_word']));

$list->set_header('��Ͽ��', 'center', 'nol_date');
$list->set_search_select('regist_date', select_year_month('���٤�', $_REQUEST['regist_date']));

$list->set_header('Pickup', 'center', 'nol_pickup');
$list->set_search_select('pickup', select_pickup('���٤�', $_REQUEST['pickup']));

$list->set_header('����', 'center', 'nol_open_flag');
$list->set_search_select('open_flag', select_open_status('���٤�', $_REQUEST['open_flag']));

$list->set_header('�ѥȥ�����CHK', 'center', 'nol_patrol_check');
$list->set_search_select('patrol_check', select_admin_check('���٤�', $_REQUEST['patrol_check']));

$list->set_header('��̳��CHK', 'center', 'nol_admin_check');
$list->set_search_select('admin_check', select_admin_check('���٤�', $_REQUEST['admin_check']));

// �ꥹ�ȥǡ�������
$sql = "SELECT nol_osusume_link_id,nol_link_name,nol_link_category_id,blg_title,nol_ng_word,nol_date,nol_pickup,nol_open_flag,nol_patrol_check,nol_admin_check"
	. " FROM t_navi_osusume_link LEFT JOIN t_navi_page ON nol_navi_page_id=nvp_navi_page_id"
	. " LEFT JOIN t_blog ON nvp_blog_id=blg_blog_id"
	. $list->get_where()
	. $list->get_order_by(0, 0)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->nol_osusume_link_id);
	$list->set_data($fetch->nol_link_name, "check.php?link_id=$fetch->nol_osusume_link_id", '���������󥯾����ɽ�����ѹ����ޤ�');
	$list->set_data(decode_navi_osusume_link($fetch->nol_link_category_id));
	$list->set_data($fetch->blg_title);
	$list->set_data(decode_ngword($fetch->nol_ng_word));
	$list->set_data(format_datetime($fetch->nol_date));
	$list->set_data(decode_pickup($fetch->nol_pickup));
	$list->set_data(decode_open_status($fetch->nol_open_flag));
	$list->set_data(decode_patrol_check($fetch->nol_patrol_check));
	$list->set_data(decode_admin_check($fetch->nol_admin_check));
}

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("link.csv");
	$csv->export_data($list);
	exit;
}

?>

<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>
<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc"><?php $list->disp_page_line($nrow); ?></td>
		<td class="lc" align="right">
		<nobr>
		<input type="submit" name="export" value="�������ݡ���">
		<input type="submit" value="�Ƹ���">
		</nobr>
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</form>

 <br>

<?php $header->page_footer(); ?>
</body>
</html>
