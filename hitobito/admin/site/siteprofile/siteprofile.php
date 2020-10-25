<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����ȥץ�ե�����ɽ���ڡ���
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
include("$inc/edit.php");
include("$inc/list.php");
include("$inc/date.php");
include("$inc/select.php");
include("$inc/csv.php");
include("$inc/data.php");
include("./data.php");

// �ڡ����إå���������
$header = new header_class('�����ȥץ�ե�����', BACK_TOP);

// ���ϥѥ�᡼��
$year = $_GET['year'];
$month = $_GET['month'];

// �ǥե���Ȥ�ǯ�������
if (!$year) {
	$year = date('Y');
	$month = date('m');
}

// ����
if (isset($_GET['prev_month'])) {
	if (--$month < 1) {
		$year--;
		$month = 12;
	}
}

// ���
if (isset($_GET['next_month'])) {
	if (++$month > 12) {
		$year++;
		$month = 1;
	}
}

// �����ȥץ�ե�����ǡ�������
$data = new site_profile_data_class();
$data->read_db(get_date_from_ymd($year, $month, 1));

// ���׾���
$edit = new edit_class();
$edit->set_item('�ʥӿ�');
$edit->set_string(number_format($data->navi_num) . ' ��');

$edit->set_item('���С���');
$edit->set_string(number_format($data->member_num) . ' ��');

$edit->set_item('�ؤ���Ͽ�Կ�');
$edit->set_string(number_format($data->letter_member) . ' ��');

$edit->set_item('Blog��');
$edit->set_string(number_format($data->blog_num) . ' ��');

$edit->set_item('�磻�磻��Ŀ�');
$edit->set_string(number_format($data->room_num) . ' ���');

$edit->set_item('�ʥӵ���������');
$edit->set_string(number_format($data->article_num) . ' ����');

$edit->set_item('ȯ���Ϳ�����');
$edit->set_string(number_format($data->remark_num) . ' ��');

$edit->set_item('ȯ��������');
$edit->set_string(number_format($data->remark_count) . ' ȯ��');

$edit->set_item('�᡼���ۿ�������');
$edit->set_string(number_format($data->mail_num) . ' ��');

$edit->set_item('RAMΨ����');
$edit->set_string(number_format($data->ram_rate) . ' ��');

$edit->set_item('�ڡ����ӥ塼������');
$edit->set_string(number_format($data->page_view) . ' PV');

$edit->set_item('ˬ��Կ�����');
$edit->set_string(number_format($data->visitor_num) . ' UU');

$edit->set_item('����å�������');
$edit->set_string(number_format($data->click_num) . ' ����å�');

$edit->set_item('�ȥ�å��Хå�������');
$edit->set_string(number_format($data->trackback_num) . ' ��');

$edit->set_item('�����ȿ�����');
$edit->set_string(number_format($data->comment_num) . ' ��');

// ����ͥ��̥ơ��޿�
$list = new list_class('small');
$list->set_header('����ͥ�');
$list->set_header('�ơ��޿�', 'center');

foreach ($data->navi_page_ary as $navi_page) {
	$list->set_data($navi_page->channel_name);
	$list->set_data($navi_page->navi_page_num);
}

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("site_profile_{$year}_{$month}.csv");
	$csv->export_data($edit);
	$csv->output_newline();
	$csv->export_data($list);
	exit;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<body>

<?php $header->page_header(); ?>

<form name="form1">
<table width="85%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" align="right" colspan=2>
      <select name="year" onChange="document.form1.submit()">
<?=select_year(min(2005, $year), '', $year)?>
      </select>
      <input type="submit" name="prev_month" value="��">
      <select name="month" onChange="document.form1.submit()">
<?=select_month('', $month)?>
      </select>
      <input type="submit" name="next_month" value="��">
      <input type="submit" name="export" value="�������ݡ���">
    </td>
  </tr>
  <tr>
    <td width="50%" valign="top">�����׾���</td>
    <td width="50%" valign="top">������ͥ��̥ơ��޿�</td>
  </tr>
  <tr>
    <td width="50%" valign="top">
<?php $edit->disp_edit('95%', '50%') ?>
    </td>
    <td width="50%" valign="top">
<?php $list->disp_list('100%') ?>
    </td>
  </tr>
</table>
</form>
<br>

<?php $header->page_footer(); ?>

</body>
</html>
