<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ひとびと便り配信ジョブ一覧ページ
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
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/select.php");

// ページヘッダ情報設定
$header = new header_class('ひとびと便り配信ジョブ管理', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('JOBID', 'center', 'hbl_letter_id');
$list->set_header('JOB名', 'left', 'hbl_job_name');
$list->set_header('配信数', 'right', 'hbl_send_num');
$list->set_header('配信日時', 'center', 'hbl_send_date');
$list->set_header('〆切日時', 'center', 'hbl_enquete_date');
$list->set_header('回答数', 'right', 'hlc_answer_count');
$list->set_header('ｸﾘｯｸ数', 'right', 'hlc_click_count');
$list->set_header('発信状態', 'center', 'hbl_status');
$list->set_header('承認', 'center', 'hbl_status');
$list->set_header('ﾊﾞｯｸﾅﾝﾊﾞｰ', 'center', 'hbl_backnumber');

// リストデータ設定
$sql = "SELECT hbl_letter_id, hbl_job_name, hbl_send_num, hbl_send_date, hbl_enquete_date, hlc_answer_count, hlc_click_count, hbl_status, hbl_backnumber, hbl_body"
		. " FROM t_hitobito_letter"
		. " JOIN c_hitobito_letter_count ON hbl_letter_id=hlc_letter_id"
		. $list->get_order_by(0, 1)
		. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->hbl_letter_id);
	$list->set_data($fetch->hbl_job_name, "edit.php?letter_id=$fetch->hbl_letter_id", 'ひとびと便り情報を表示・変更します');
	$list->set_data(number_format($fetch->hbl_send_num));
	$list->set_data(format_datetime($fetch->hbl_send_date));
	$list->set_data(format_datetime($fetch->hbl_enquete_date));
	$list->set_data(number_format($fetch->hlc_answer_count));
	$list->set_data(number_format($fetch->hlc_click_count));
	$list->set_data(decode_letter_status1($fetch->hbl_status));

	if ($fetch->hbl_status == 1 && $fetch->hbl_body != '')
		$list->set_data(decode_letter_status2($fetch->hbl_status), "javascript:change_status1($fetch->hbl_letter_id)", 'このジョブを承認します');
	elseif ($fetch->hbl_status == 2)
		$list->set_data(decode_letter_status2($fetch->hbl_status), "javascript:change_status2($fetch->hbl_letter_id)", 'このジョブの承認を取り消します');
	else
		$list->set_data(decode_letter_status2($fetch->hbl_status));

	if ($fetch->hbl_backnumber == 0)
		$list->set_data(decode_backnumber($fetch->hbl_backnumber), "javascript:change_backnumber0($fetch->hbl_letter_id)", 'このジョブのバックナンバーを掲載します');
	else
		$list->set_data(decode_backnumber($fetch->hbl_backnumber), "javascript:change_backnumber1($fetch->hbl_letter_id)", 'このジョブのバックナンバーを非掲載にします');

}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function change_status1(letter_id) {
	if (confirm("指定したジョブを承認します。よろしいですか？"))
		location.href = "change_status.php?letter_id=" + letter_id;
}
function change_status2(letter_id) {
	if (confirm("指定したジョブの承認を取り消します。よろしいですか？"))
		location.href = "change_status.php?letter_id=" + letter_id;
}
function change_backnumber0(letter_id) {
	if (confirm("このジョブのバックナンバーを掲載します。よろしいですか？"))
		location.href = "change_bn.php?letter_id=" + letter_id;
}
function change_backnumber1(letter_id) {
	if (confirm("このジョブのバックナンバーを非掲載にします。よろしいですか？"))
		location.href = "change_bn.php?letter_id=" + letter_id;
}
//-->
</script>
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
			<input type="button" value="新規登録" onclick="location.href='edit.php'">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>
</form>

<?php $list->disp_list(); ?>

<?php $header->page_footer(); ?>

</body>
</html>
