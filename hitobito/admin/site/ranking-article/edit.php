<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �͵�������󥭥��Խ��ڡ���
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
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/edit.php");
include("$inc/list.php");
include("$inc/data.php");
include("./data.php");

// �ڡ����إå���������
$header = new header_class('�͵�������󥭥󥰴���', BACK_TOP);

// �ǡ����������饹����
$data = new ranking_article_data_class();

// DB����ǡ����ɤ߹���
$data->read_db($_POST['channel_id']);

// ��󥭥󥰷׻�
if (isset($_POST['next_action'])) {
	$data->read_form();
	$data->calc_ranking();
}

// ��󥭥󥰥ꥹ��
$list1 = new list_class('small');
$list1->set_header('���', 'center');
$list1->set_header('����No.', 'center');
$list1->set_header('����ͥ�', 'left');
$list1->set_header('�ʥӥڡ���', 'left');
$list1->set_header('�����ȥ�', 'left');
$list1->set_header('�Ǻ���', 'center');
$list1->set_header('�ڡ����ӥ塼', 'right');
$list1->set_header('�ȥ�å��Хå�', 'right');
$list1->set_header('������', 'right');
$list1->set_header('�ؿ�', 'right');
$list1->set_header('��Ǻ�', 'center');

$rank_no = 1;
foreach ($data->ranking_ary as $navi_page_id => $ranking) {
	$list1->set_data($rank_no++);
	$list1->set_data($ranking->article_id, "../../blog/article/check.php?article_id=$ranking->article_id");
	$list1->set_data($ranking->channel_name);
	$list1->set_data($ranking->navi_page_title, "../../navi/blog/edit.php?blog_id=$ranking->blog_id");
	$list1->set_data($ranking->article_title);
	$list1->set_data(format_datetime($ranking->date));
	$list1->set_data(number_format($ranking->page_view));
	$list1->set_data(number_format($ranking->trackback));
	$list1->set_data(number_format($ranking->comment));
	$list1->set_data(number_format($ranking->index, 1));
	$list1->set_html(html_input_checkbox('keisai[]', $ranking->article_id, !$ranking->keisai_flag, 'height:13px'));
}

// ��󥭥�����������饹����
$edit1 = new edit_class('��󥭥�');
$edit1->set_command('����ͥ�' . html_select('channel_id', select_channel('���ȥåץڡ�����', $_POST['channel_id']), 1, '', 'submit_self()'));

$edit1->set_item($list1);

// �ؿ�����������饹����
$edit2 = new edit_class('�ؿ�����');

$edit2->set_item('�����ڡ����ӥ塼', true);
$edit2->set_string('x');
$edit2->set_text('page_view', $data->page_view, 'number', 10);

$edit2->set_item('�����ȥ�å��Хå���', true);
$edit2->set_string('x');
$edit2->set_text('trackback', $data->trackback, 'number', 10);

$edit2->set_item('���������ȿ�', true);
$edit2->set_string('x');
$edit2->set_text('comment', $data->comment, 'number', 10);

$edit2->set_item('ȿ�Ǵ��֡�����', true);
$edit2->set_text('update_period', $data->update_period, 'number', 10);

// �ؿ������ꥹ��
$list3 = new list_class('small');
$list3->set_header('�ʥӥڡ���', 'center');
$list3->set_header('�����ʥ�С��������ȥ�', 'center');
$list3->set_header('�ڡ����ӥ塼', 'center');
$list3->set_header('�ȥ�å��Хå�', 'center');
$list3->set_header('������', 'center');
$list3->set_header('�ؿ�', 'center');
$list3->set_header('�ؿ�����', 'center');

for ($i = 0; $i < $data->adjust_num; $i++) {
	$adjust = &$data->adjust_ary[$i];

	$list3->set_html(html_select("navi_page_id[$i]", select_navi_page_title('�ݡݡݡݡݡݡݡݡݡݡݡݡ�', $adjust->navi_page_id, $_POST['channel_id']), 1, '', 'submit_self()'));
	$list3->set_html(html_select("article_id[$i]", select_article('------ �ݡݡݡݡݡݡݡ�', $adjust->article_id, $adjust->navi_page_id), 1, '', 'submit_self()'));
	$list3->set_data($adjust->page_view);
	$list3->set_data($adjust->trackback);
	$list3->set_data($adjust->comment);
	$list3->set_data($adjust->index);
	$list3->set_html(html_input_text("adjust[$i]", $adjust->adjust, 'number', 10));
}

// �ؿ������������饹����
$edit3 = new edit_class('�ؿ�����');

$edit3->set_item($list3);

// ��������
function select_article($default, $selected, $navi_page_id) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	if ($navi_page_id) {
		$sql = "SELECT nvp_blog_id FROM t_navi_page WHERE nvp_navi_page_id=$navi_page_id";
		$blog_id = db_fetch1($sql);
	}

	if ($blog_id) {
		$sql = "SELECT bla_article_id, bla_title"
				. " FROM t_blog_article"
				. " JOIN t_blog_ranking ON blr_article_id=bla_article_id"
				. " WHERE bla_status = 1 AND bla_open_flag = 1 AND bla_blog_id=$blog_id"
				. " ORDER BY bla_article_id";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$tag .= option_tag($fetch->bla_article_id, $selected, htmlspecialchars(sprintf('%06d %s', $fetch->bla_article_id, $fetch->bla_title)));
		}
	}

	return $tag;
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit2->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	switch (f.next_action.value) {
	case 'update':
		f.action = 'update.php';
		if (input_check2(f))
			return confirm("�͵�������󥭥󥰤򹹿����ޤ���������Ǥ�����");
		break;
	case 'calc':
		f.action = 'edit.php';
		return input_check2(f);
	}
	return false;
}
function input_check2(f) {
	if (!input_check(f))
		return false;
	for (var i = 0; i < <?=$data->adjust_num?>; i++) {
		var adj = f["adjust[" + i + "]"];
		if (!check_number(f["adjust[" + i + "]"], "�ؿ�����"))
			return false;
	}
	return true;
}
function submit_self() {
	var f = document.edit;
	f.action = "edit.php";
	f.submit();
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form name="edit" method="post" action="update.php" onsubmit="return onsubmit_edit(this)">
<?php $edit1->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<br>
<?php $edit3->disp_edit() ?>
<br>
<input type="hidden" name="next_action">
<input type="submit" value="�׻�����" onclick="document.edit.next_action.value='calc'">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="�ꥻ�å�" onclick="location.href='edit.php'">
<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
