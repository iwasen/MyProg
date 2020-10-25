<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ML��ƴ����ڡ���
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
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/ngword.php");
include("$inc/list.php");

// �إå���������
$header = new header_class('ȯ������', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// ɽ���Կ�
$limit = $list->get_limit_offset();

// �ꥹ�ȥإå�����
$list->set_header('ȯ��ID', 'center', 'rmk_remark_id');
$list->set_search_text('remark_id', $_REQUEST['remark_id'], 'number', 8);

$list->set_header('�����ȥ�', 'left', 'rmk_subject');
$list->set_search_text('subject', $_REQUEST['subject'], 'kanji', 20);

$list->set_header('�磻�磻���', 'left', 'rom_name');
$list->set_search_text('room_name', $_REQUEST['room_name'], 'kanji', 20);

$list->set_header('��Ƽ�', 'left', 'rmm_nickname');
$list->set_search_text('nickname', $_REQUEST['nickname'], 'kanji', 20);

$list->set_header('NG���', 'left', 'rmk_ng_word');
$list->set_search_select('ng_word', select_ngword('���٤�', $_REQUEST['ng_word']));

$list->set_header('��Ͽ��', 'center', 'rmk_date');
$list->set_search_select('regist_date', select_year_month('���٤�', $_REQUEST['regist_date']));

$list->set_header('Pickup', 'center', 'rmk_pickup');
$list->set_search_select('pickup', select_pickup('���٤�', $_REQUEST['pickup']));

$list->set_header('�������', 'right', 'pvc_pv_count');
$list->set_search_select('view_count', select_over_count('���٤�', $_REQUEST['view_count']));

$list->set_header('����', 'center', 'rmk_open_flag');
$list->set_search_select('open_flag', select_open_status('���٤�', $_REQUEST['open_flag']));

$list->set_header('�ʎߎĎێ�����CHK', 'center', 'rmk_patrol_check');
$list->set_search_select('patrol_check', select_admin_check('���٤�', $_REQUEST['patrol_check']));

$list->set_header('��̳��CHK', 'center', 'rmk_admin_check');
$list->set_search_select('admin_check', select_admin_check('���٤�', $_REQUEST['admin_check']));

// �ꥹ�ȥǡ�������
$sql = "SELECT rmk_remark_id,rmk_subject,rmk_room_id,rom_name,rmk_member_id,rmm_nickname,rmk_ng_word,rmk_date,rmk_pickup,pvc_pv_count,rmk_open_flag,rmk_patrol_check,rmk_admin_check"
	. " FROM l_room_remark"
	. " LEFT JOIN t_room ON rmk_room_id=rom_room_id"
	. " LEFT JOIN t_room_member ON rmk_member_id=rmm_member_id AND rmk_room_id=rmm_room_id"
	. " LEFT JOIN c_page_view ON rmk_pv_id=pvc_pv_id"
	. $list->get_where()
	. $list->get_order_by(0, 1)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rmk_remark_id);
	$list->set_data($fetch->rmk_subject, "check.php?remark_id=" . $fetch->rmk_remark_id, 'ȯ�������ɽ�����ѹ����ޤ�');
	if ($page_kind == 'admin') {
		$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=" . $fetch->rmk_room_id, 'ML��Ͽ�����ɽ�����ѹ����ޤ�');
		$list->set_data($fetch->rmm_nickname, "$top/member/member/edit.php?member_id=" . $fetch->rmk_member_id, '���С���Ͽ�����ɽ�����ѹ����ޤ�');
	} else {
		$list->set_data($fetch->rom_name);
		$list->set_data($fetch->rmm_nickname);
	}
	$list->set_data(decode_ngword($fetch->rmk_ng_word));
	$list->set_data(format_datetime($fetch->rmk_date));
	$list->set_data(decode_pickup($fetch->rmk_pickup));
	$list->set_data(number_format($fetch->pvc_pv_count));
	$list->set_data(decode_open_status($fetch->rmk_open_flag));
	$list->set_data(decode_patrol_check($fetch->rmk_patrol_check));
	$list->set_data(decode_admin_check($fetch->rmk_admin_check));
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
		<input type="submit" value="�Ƹ���">
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
