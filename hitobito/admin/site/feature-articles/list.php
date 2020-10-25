<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ý����������ڡ���
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
include("$inc/list.php");
include("$inc/data.php");
include("./data.php");

// �ڡ����إå���������
$header = new header_class('�ý�����', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// �ꥹ�ȥإå�����
$list->set_header('����No.', 'center');
$list->set_header('ɽ������', 'center');
$list->set_header('Alt̾', 'left');
$list->set_header('������', 'center');
$list->set_header('��λ��', 'center');
$list->set_header('����å���', 'right');
$list->set_header('�Ǻ�', 'center');

$sql = "SELECT far_article_id,far_order,far_image_alt,far_start_date,far_end_date,fac_click_count, CASE WHEN far_status=1 AND current_timestamp BETWEEN far_start_date AND far_end_date THEN '�Ǻ���' ELSE '' END AS keisai"
		. " FROM t_feature_articles"
		. " JOIN c_feature_articles ON fac_article_id=far_article_id"
		. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->far_article_id, "edit.php?article_id=$fetch->far_article_id", '�ý�������ɽ�����Խ����ޤ�');
	$list->set_data($fetch->far_order);
	$list->set_data($fetch->far_image_alt);
	$list->set_data(format_datetime($fetch->far_start_date));
	$list->set_data(format_datetime($fetch->far_end_date));
	$list->set_data(number_format($fetch->fac_click_count));
	$list->set_data($fetch->keisai);
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc">
<?php $list->disp_page_line($nrow); ?>
		</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='edit.php'">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>

<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
