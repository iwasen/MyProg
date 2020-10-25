<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӵ����ǿ�����Խ��ڡ���
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
include("$inc/const.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");

// �ѥ�᡼������
$channel_id = $_POST['channel_id'];

// �ڡ����إå���������
$header = new header_class('�ʥӵ����ǿ����', BACK_TOP);

// �ǿ���ƥꥹ��
$list = new list_class('small');
$list->set_header('����No.', 'center');
$list->set_header('����ͥ�', 'left');
$list->set_header('�ʥӥڡ���', 'left');
$list->set_header('�����ȥ�', 'left');
$list->set_header('�Ǻ���', 'center');
$list->set_header('�ڡ����ӥ塼', 'right');
$list->set_header('�ȥ�å��Хå�', 'right');
$list->set_header('������', 'right');
$list->set_header('�Ǻ�', 'center');
$list->set_header('��Ǻ�', 'center');

$new_article_count = get_const('max_new_articles');
$article_id_ary = array();

// ���20��������֥�����
$sb1 = "SELECT bla_article_id AS sb1_article_id"
		. " FROM t_blog_article"
		. ($channel_id ? " JOIN t_navi_page ON nvp_blog_id=bla_blog_id" : "")
		. " WHERE bla_status=1 AND bla_open_flag=1" . ($channel_id ? " AND nvp_channel_id=$channel_id" : "")
		. " ORDER BY bla_article_id DESC"
		. " LIMIT 20";

// �ȥ�å��Хå����������֥�����
$sb2 = "SELECT COUNT(*) FROM t_blog_trackback WHERE blt_article_id=sb1_article_id AND blt_open_flag=1";

// �����ȿ��������֥�����
$sb3 = "SELECT COUNT(*) FROM t_blog_comment WHERE blo_article_id=sb1_article_id AND blo_open_flag=1";

$sql = "SELECT bla_article_id, chn_channel_name, nvp_title, bla_title, bla_date, pvc_pv_count, ($sb2) AS trackback_count, ($sb3) AS comment_count, bla_keisai_flag, bla_blog_id"
		. " FROM ($sb1) sb1"
		. " JOIN t_blog_article ON bla_article_id=sb1_article_id"
		. " JOIN t_navi_page ON nvp_blog_id=bla_blog_id"
		. " JOIN m_channel ON chn_channel_id=nvp_channel_id"
		. " JOIN c_page_view ON pvc_pv_id=bla_pv_id"
		. " ORDER BY bla_article_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->bla_article_id, "../../blog/article/check.php?article_id=$fetch->bla_article_id");
	$list->set_data($fetch->chn_channel_name);
	$list->set_data($fetch->nvp_title, "../../navi/blog/edit.php?blog_id=$fetch->bla_blog_id");
	$list->set_data($fetch->bla_title);
	$list->set_data(format_datetime($fetch->bla_date));
	$list->set_data(number_format($fetch->pvc_pv_count));
	$list->set_data(number_format($fetch->trackback_count));
	$list->set_data(number_format($fetch->comment_count));
	$list->set_data($fetch->bla_keisai_flag == 1 && --$new_article_count >= 0 ? '�Ǻ���' : '');
	$list->set_html(html_input_checkbox('keisai_flag[]', $fetch->bla_article_id, $fetch->bla_keisai_flag == 0, 'height:13px'));

	$article_id_ary[] = $fetch->bla_article_id;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	f.action = "update.php";
	return confirm("�ʥӵ����ǿ���Ƥ򹹿����ޤ���������Ǥ�����");
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
<table border="0" cellpadding="1" width="85%">
  <tr>
    <td align="right" valign="top">����ͥ�
      <select name="channel_id" onchange="submit_self()"><?=select_channel('���ȥåץڡ�����', $_POST['channel_id'])?></select>
    </td>
  </tr>
	<tr>
		<td>
<?php $list->disp_list('100%') ?>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="article_id" <?=value(join(':', $article_id_ary))?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="reset" value="�ꥻ�å�">
<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
