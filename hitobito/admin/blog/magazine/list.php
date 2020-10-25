<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���ޥ��ʼ��������ڡ���
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
include("$inc/format.php");
include("$inc/ngword.php");
include("$inc/decode.php");
include("$inc/csv.php");

// �ڡ����إå���������
$header = new header_class('���ޥ��ʼ�����', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// ɽ���Կ�
$limit = $list->get_limit_offset();

// �ꥹ�ȥإå�����
$list->set_header('�ޥ�����ID', 'center', 'mmc_melmaga_contents_id', true);
$list->set_search_text('melmaga_contents_id', $_REQUEST['melmaga_contents_id'], 'number', 10);

$list->set_header('��̾', 'left', 'mmc_subject', true);
$list->set_search_text('subject', $_REQUEST['subject'], 'kanji', 10);

$list->set_header('���ޥ�̾', 'left', 'mmg_melmaga_name', true);
$list->set_search_text('melmaga_name', $_REQUEST['melmaga_name'], 'kanji', 10);

$list->set_header('Blog̾', 'left', 'nvp_title', true);
$list->set_search_text('blog_name', $_REQUEST['blog_name'], 'kanji', 10);

$list->set_header('���ơ�����', 'center', 'mmc_status', true);
$list->set_search_select('status', select_melmaga_send_status('���٤�', $_REQUEST['status']));

$list->set_header('pickup', 'center', 'mmc_pickup', true);
$list->set_search_select('pickup', select_pickup('���٤�', $_REQUEST['pickup']));

$list->set_header('����', 'center', 'sb1_kind');
$list->set_search_select('kind', select_remark_kind('���٤�', $_REQUEST['kind']));

$list->set_header('NG���', 'left', 'sb1_ng_word');
$list->set_search_select('ng_word', select_ngword('���٤�', $_REQUEST['ng_word']));

$list->set_header('ȯ�ԡ���Ͽ��', 'center', 'sb1_date');
$list->set_search_select('date', select_year_month('���٤�', $_REQUEST['date']));

$list->set_header('����', 'center', 'sb1_open_flag');
$list->set_search_select('open_flag', select_open_status('���٤�', $_REQUEST['open_flag']));

$list->set_header('�ѥȥ�����CHK', 'center', 'sb1_patrol_check');
$list->set_search_select('patrol_check', select_admin_check('���٤�', $_REQUEST['patrol_check']));

$list->set_header('��̳��CHK', 'center', 'sb1_admin_check');
$list->set_search_select('admin_check', select_admin_check('���٤�', $_REQUEST['admin_check']));

// �ꥹ�ȥǡ�������
$sb1 = "SELECT mmc_melmaga_contents_id AS sb1_melmaga_contents_id"
	. ",1 AS sb1_kind,mmc_ng_word AS sb1_ng_word,mmc_send_date AS sb1_date,mmc_open_flag AS sb1_open_flag,mmc_patrol_check AS sb1_patrol_check,mmc_admin_check AS sb1_admin_check"
	. " FROM t_melmaga_contents"
	. " UNION SELECT mmt_melmaga_contents_id"
	. ",2 AS kind,mmt_ng_word,mmt_date,mmt_open_flag,mmt_patrol_check,mmt_admin_check"
	. " FROM l_melmaga_trackback"
	. " UNION SELECT mmo_melmaga_contents_id"
	. ",3 AS kind,mmo_ng_word,mmo_date,mmo_open_flag,mmo_patrol_check,mmo_admin_check"
	. " FROM l_melmaga_comment";

$sql = "SELECT mmc_melmaga_contents_id,mmc_subject,mmc_melmaga_id,mmg_melmaga_name,nvp_blog_id,nvp_title,mmc_status,mmc_pickup"
	. ",sb1_kind,sb1_ng_word,sb1_date,sb1_open_flag,sb1_patrol_check,sb1_admin_check"
	. " FROM t_melmaga_contents LEFT JOIN ($sb1) AS sb1 ON mmc_melmaga_contents_id=sb1_melmaga_contents_id"
	. " LEFT JOIN t_melmaga ON mmc_melmaga_id=mmg_melmaga_id"
	. " LEFT JOIN t_navi_page ON mmg_melmaga_id=nvp_melmaga_id"
	. $list->get_where()
	. $list->get_order_by(0, 1)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->mmc_melmaga_contents_id);
	$list->set_data($fetch->mmc_subject, 'check.php?melmaga_contents_id=' . $fetch->mmc_melmaga_contents_id, 'ȯ�ԥ��ޥ������ɽ�����ѹ����ޤ�');
	if ($page_kind == 'admin') {
		$list->set_data($fetch->mmg_melmaga_name, "$top/navi/publisher/edit.php?melmaga_id=" . $fetch->mmc_melmaga_id, '���ޥ���Ͽ�����ɽ�����ѹ����ޤ�');
		$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=" . $fetch->nvp_blog_id, 'Blog��Ͽ�����ɽ�����ѹ����ޤ�');
	} else {
		$list->set_data($fetch->mmg_melmaga_name);
		$list->set_data($fetch->nvp_title);
	}
	$list->set_data(decode_melmaga_send_status($fetch->mmc_status));
	$list->set_data(decode_pickup($fetch->mmc_pickup));
	$list->set_data(decode_contents_kind($fetch->sb1_kind));
	$list->set_data(decode_ngword($fetch->sb1_ng_word));
	$list->set_data(format_datetime($fetch->sb1_date));
	$list->set_data(decode_open_status($fetch->sb1_open_flag));
	$list->set_data(decode_patrol_check($fetch->sb1_patrol_check));
	$list->set_data(decode_admin_check($fetch->sb1_admin_check));
}

// ���ޥ����ơ�����
function decode_melmaga_send_status($code, $def='') {
	switch ($code) {
	case '1':
		return '̤�ۿ�';
	case '2':
		return '�ۿ���';
	}
	return $def;
}

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("magazine.csv");
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
