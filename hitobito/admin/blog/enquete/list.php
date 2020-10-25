<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���󥱡����ʼ��������ڡ���
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
include("$inc/decode.php");
include("$inc/ngword.php");
include("$inc/format.php");
include("$inc/csv.php");

// �ڡ����إå���������
$header = new header_class('���󥱡��Ȱ���', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// ɽ���Կ�
$limit = $list->get_limit_offset();

// �ꥹ�ȥإå�����
$list->set_header('���ݎ�����ID', 'center', 'nen_enquete_id', true);
$list->set_search_text('enquete_id', $_REQUEST['enquete_id'], 'number', 10);

$list->set_header('���󥱡���̾', 'left', 'nen_title', true);
$list->set_search_text('enq_title', $_REQUEST['enq_title'], 'kanji', 10);

$list->set_header('Blog̾', 'left', 'nvp_title', true);
$list->set_search_text('blog_title', $_REQUEST['blog_title'], 'kanji', 10);

$list->set_header('pickup', 'center', 'nen_pickup', true);
$list->set_search_select('pickup', select_pickup('���٤�', $_REQUEST['pickup']));

$list->set_header('������', 'center', 'nen_start_date', true);
$list->set_search_select('start_date', select_year_month('���٤�', $_REQUEST['start_date']));

$list->set_header('������', 'center', 'nen_end_date', true);
$list->set_search_select('enq_date', select_year_month('���٤�', $_REQUEST['enq_date']));

$list->set_header('����', 'center', 'sb1_kind');
$list->set_search_select('kind', select_remark_kind('���٤�', $_REQUEST['kind']));

$list->set_header('NG���', 'left', 'sb1_ng_word');
$list->set_search_select('ng_word', select_ngword('���٤�', $_REQUEST['ng_word']));

$list->set_header('������', 'center', 'sb1_date');
$list->set_search_select('open_date', select_year_month('���٤�', $_REQUEST['open_date']));

$list->set_header('����', 'center', 'sb1_open_flag');
$list->set_search_select('open_flag', select_open_status('���٤�', $_REQUEST['open_flag']));

$list->set_header('�ѥȥ�����CHK', 'center', 'sb1_patrol_check');
$list->set_search_select('patrol_check', select_admin_check('���٤�', $_REQUEST['patrol_check']));

$list->set_header('��̳��CHK', 'center', 'sb1_admin_check');
$list->set_search_select('admin_check', select_admin_check('���٤�', $_REQUEST['admin_check']));

// �ꥹ�ȥǡ�������

$sb1 = "SELECT nen_enquete_id as sb1_enquete_id"
	. ",1 AS sb1_kind,nen_ng_word AS sb1_ng_word,nen_date AS sb1_date,nen_open_flag AS sb1_open_flag,nen_patrol_check AS sb1_patrol_check,nen_admin_check AS sb1_admin_check"
	. " FROM t_navi_enquete "
	. " UNION SELECT net_enquete_id"
	. ",2 AS kind,net_ng_word,net_date,net_open_flag,net_patrol_check,net_admin_check"
	. " FROM l_navi_enq_trackback"
	. " UNION SELECT neo_enquete_id"
	. ",3 AS kind,neo_ng_word,neo_date,neo_open_flag,neo_patrol_check,neo_admin_check"
	. " FROM l_navi_enq_comment";

$sql = "SELECT nen_enquete_id,nen_title,nvp_blog_id,nvp_title,nen_pickup,nen_start_date,nen_end_date"
	. ",sb1_kind,sb1_ng_word,sb1_date,sb1_open_flag,sb1_patrol_check,sb1_admin_check"
	. " FROM t_navi_enquete LEFT JOIN ($sb1) AS sb1 ON nen_enquete_id=sb1_enquete_id"
	. " LEFT JOIN t_navi_page ON nen_navi_page_id=nvp_navi_page_id"
. $list->get_where($where)
. $list->get_order_by(0, 1)
. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->nen_enquete_id);
	$list->set_data($fetch->nen_title, 'check.php?enquete_id=' . $fetch->nen_enquete_id, 'ȯ�ԥ��ޥ������ɽ�����ѹ����ޤ�');
	if ($page_kind == 'admin')
		$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=" . $fetch->nvp_blog_id, 'Blog��Ͽ�����ɽ�����ѹ����ޤ�');
	else
		$list->set_data($fetch->nvp_title);
	$list->set_data(decode_pickup($fetch->nen_pickup));
	$list->set_data(format_datetime($fetch->nen_start_date));
	$list->set_data(format_datetime($fetch->nen_end_date));
	$list->set_data(decode_contents_kind($fetch->sb1_kind));
	$list->set_data(decode_ngword($fetch->sb1_ng_word));
	$list->set_data(format_datetime($fetch->sb1_date));
	$list->set_data(decode_open_status($fetch->sb1_open_flag));
	$list->set_data(decode_patrol_check($fetch->sb1_patrol_check));
	$list->set_data(decode_admin_check($fetch->sb1_admin_check));
}

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("enquete.csv");
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
		<td class="lc">
<?php $list->disp_page_line($nrow); ?>
		</td>
		<td class="lb">
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
