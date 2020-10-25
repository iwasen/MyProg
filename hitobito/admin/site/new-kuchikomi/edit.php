<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ǿ�������������Խ��ڡ���
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
$channel_id = $_REQUEST['channel_id'];

// �ڡ����إå���������
$header = new header_class('�ǿ������������', BACK_TOP);

// ��󥭥󥰥ꥹ��
$list = new list_class('small');
$list->set_header('����No.', 'center');
$list->set_header('����ͥ�', 'left');
$list->set_header('����̾', 'left');
$list->set_header('��Ƽ�', 'left');
$list->set_header('�Ǻ���', 'center');
$list->set_header('�Ǻ�', 'center');
$list->set_header('��Ǻ�', 'center');

$new_kuchikomi_count = get_const('max_new_kuchikomi');
$kuchikomi_id_ary = array();

$sql = "SELECT kck_kuchikomi_id, chn_channel_name, kck_shouhin, kck_nickname, kck_regist_date, kck_keisai_flag, kck_page_url"
		. " FROM t_kuchikomi"
		. " JOIN " . ($channel_id ? "t_kuchikomi_channel ON kch_channel_id=$channel_id AND kch_kuchikomi_id=kck_kuchikomi_id" : "(SELECT min(kch_channel_id) AS kch_channel_id,kch_kuchikomi_id FROM t_kuchikomi_channel GROUP BY kch_kuchikomi_id) sb1 ON kch_kuchikomi_id=kck_kuchikomi_id")
		. " JOIN m_channel ON chn_channel_id=kch_channel_id"
		. " ORDER BY kck_kuchikomi_id DESC"
		. " LIMIT 20";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->kck_kuchikomi_id, $fetch->kck_page_url, '', '_blank');
	$list->set_data($fetch->chn_channel_name);
	$list->set_data($fetch->kck_shouhin);
	$list->set_data($fetch->kck_nickname . '����');
	$list->set_data(format_datetime($fetch->kck_regist_date));
	$list->set_data($fetch->kck_keisai_flag == 1 && --$new_kuchikomi_count >= 0 ? '�Ǻ���' : '');
	$list->set_html(html_input_checkbox('keisai_flag[]', $fetch->kck_kuchikomi_id, $fetch->kck_keisai_flag == 0, 'height:13px'));

	$kuchikomi_id_ary[] = $fetch->kck_kuchikomi_id;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	return confirm("�ǿ�����������Ƥ򹹿����ޤ���������Ǥ�����");
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
      <select name="channel_id" onchange="submit_self()"><?=select_channel('���ȥåץڡ�����', $channel_id)?></select>
    </td>
  </tr>
	<tr>
		<td>
<?php $list->disp_list('100%') ?>
		</td>
	</tr>
</table>
<br>
<br>
<input type="hidden" name="kuchikomi_id" <?=value(join(':', $kuchikomi_id_ary))?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="reset" value="�ꥻ�å�">
<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
