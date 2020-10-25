<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӥ�󥭥��Խ��ڡ���
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
include("$inc/edit.php");
include("$inc/list.php");
include("$inc/data.php");
include("./data.php");

// �ڡ����إå���������
$header = new header_class('�ʥӥ�󥭥󥰴���', BACK_TOP);

// �ǡ����������饹����
$data = new ranking_navi_data_class();

// DB����ǡ����ɤ߹���
$data->read_db();

// ��󥭥󥰷׻�
if ($_POST['next_action'] == 'calc') {
	$data->read_form();
	$data->calc_ranking();
}

// ��󥭥󥰥ꥹ��
$list1 = new list_class('small', 'id_rank');
$list1->set_header('���', 'center');
$list1->set_header('�ʥӥڡ���', 'left');
$list1->set_header('���С���', 'right');
$list1->set_header('���ÿ�', 'right');
$list1->set_header('ȯ����', 'right');
$list1->set_header('MLPV', 'right');
$list1->set_header('������', 'right');
$list1->set_header('��ƿ�', 'right');
$list1->set_header('����PV', 'right');
$list1->set_header('TB', 'right');
$list1->set_header('������', 'right');
$list1->set_header('�ؿ�', 'right');
$list1->set_header('��ư', 'center');

$rank_no = 1;
foreach ($data->ranking_ary as $navi_page_id => $ranking) {
	$list1->set_data($rank_no++);
	$list1->set_data($ranking->title, "../../navi/blog/edit.php?blog_id=$ranking->blog_id");
	$list1->set_data(number_format($ranking->ml_member_num));
	$list1->set_data(number_format($ranking->ml_member_up));
	$list1->set_data(number_format($ranking->ml_total_remark));
	$list1->set_data(number_format($ranking->ml_page_view));
	$list1->set_data(number_format($ranking->ml_member_remark, 2));
	$list1->set_data(number_format($ranking->blog_new_post));
	$list1->set_data(number_format($ranking->blog_page_view));
	$list1->set_data(number_format($ranking->blog_trackback));
	$list1->set_data(number_format($ranking->blog_comment));
	$list1->set_data(number_format($ranking->index, 1));
	$list1->set_html(html_input_radio('rank', $navi_page_id, '', 'height:13px'));
}

// ��󥭥�����������饹����
$edit1 = new edit_class('��󥭥�');
$edit1->set_command('<input type="button" value="��" onclick="rank_up()"><input type="button" value="��" onclick="rank_down()">');
$edit1->set_item($list1);

// �ؿ�����������饹����
$edit2 = new edit_class('�ؿ�����');

$edit2->set_item('ML���С���', true);
$edit2->set_string('x');
$edit2->set_text('ml_member_num', $data->ml_member_num, 'number', 10);

$edit2->set_item('���С����ÿ�', true);
$edit2->set_string('x');
$edit2->set_text('ml_member_up', $data->ml_member_up, 'number', 10);

$edit2->set_item('ML����ȯ����', true);
$edit2->set_string('x');
$edit2->set_text('ml_total_remark', $data->ml_total_remark, 'number', 10);

$edit2->set_item('ML�ڡ����ӥ塼', true);
$edit2->set_string('x');
$edit2->set_text('ml_page_view', $data->ml_page_view, 'number', 10);

$edit2->set_item('�����ȯ����', true);
$edit2->set_string('x');
$edit2->set_text('ml_member_remark', $data->ml_member_remark, 'number', 10);

$edit2->set_item('����������ƿ�', true);
$edit2->set_string('x');
$edit2->set_text('blog_new_post', $data->blog_new_post, 'number', 10);

$edit2->set_item('�����ڡ����ӥ塼', true);
$edit2->set_string('x');
$edit2->set_text('blog_page_view', $data->blog_page_view, 'number', 10);

$edit2->set_item('�����ȥ�å��Хå���', true);
$edit2->set_string('x');
$edit2->set_text('blog_trackback', $data->blog_trackback, 'number', 10);

$edit2->set_item('���������ȿ�', true);
$edit2->set_string('x');
$edit2->set_text('blog_comment', $data->blog_comment, 'number', 10);

// �������ٽ������饹����
$edit3 = new edit_class('��������');

$edit3->set_item('��������', true);
$edit3->set_radio('update_mode', '1', $data->update_mode, '������1������<br>');
$edit3->set_radio('update_mode', '2', $data->update_mode, '������1����16������<br>');
$edit3->set_radio('update_mode', '3', $data->update_mode, '�轵���轵����������');
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php edit_class::js_input_check_all($edit2, $edit3); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	switch (f.next_action.value) {
	case 'update':
		f.action = 'update.php';
		get_ranking();
		if (input_check(f))
			return confirm("�ʥӥ�󥭥󥰤򹹿����ޤ���������Ǥ�����");
		break;
	case 'calc':
		f.action = 'edit.php';
		return input_check(f);
	}
	return false;
}
function rank_up() {
	var f = document.edit;
	var move = check_move();
	if (move <= 1)
		return false;
	var table = document.getElementById("id_rank").firxxxhild;
	var tr1 = table.childNodes[move - 1];
	var tr2 = table.childNodes[move];
	exchange_row(table, tr1, tr2);
	f.rank[move - 2].checked = true;
}
function rank_down() {
	var f = document.edit;
	var move = check_move();
	if (move < 1)
		return false;
	var table = document.getElementById("id_rank").firxxxhild;
	var tr1 = table.childNodes[move];
	var tr2 = table.childNodes[move + 1];
	exchange_row(table, tr1, tr2);
	f.rank[move].checked = true;
}
function check_move() {
	var f = document.edit;
	if (f.rank) {
		if (f.rank.length) {
			for (var i = 0; i < f.rank.length; i++) {
				if (f.rank[i].checked)
					return i + 1;
			}
		} else {
			if (f.rank.checked)
				return 0;
		}
	}
	alert("��󥭥󥰤��ѹ�����Ԥ����򤷤Ƥ���������");
	return 0;
}
function exchange_row(table, tr1, tr2) {
	var td1 = tr1.firxxxhild;
	var td2 = tr2.firxxxhild;
	var text1 = td1.innerText;
	var text2 = td2.innerText;
	var className1 = tr1.getAttribute("className");
	var className2 = tr2.getAttribute("className");
	table.insertBefore(table.removeChild(tr2), tr1);
	td1.innerText = text2;
	td2.innerText = text1;
	tr1.setAttribute("className", className2);
	tr2.setAttribute("className", className1);
}
function get_ranking() {
	var f = document.edit;
	var table = document.getElementById("id_rank").firxxxhild;
	var tr = table.childNodes;
	var rank = "";
	for (var i = 1; i < tr.length; i++) {
		if (rank != "")
			rank += ":";
		rank += tr[i].childNodes[12].firxxxhild.value;
	}
	f.ranking.value = rank;
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
<input type="hidden" name="ranking">
<input type="hidden" name="next_action">
<input type="submit" value="�׻�����" onclick="document.edit.next_action.value='calc'">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="�ꥻ�å�" onclick="location.href='edit.php'">
<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
