<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 読者検索/代理解除覧ページ
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

if (isset($_REQUEST['mail_addr'])) {
	$mail_addr = htmlspecialchars($_REQUEST['mail_addr']);

	$sql = sprintf("SELECT mbr_member_id FROM t_member WHERE mbr_mail_addr = %s", sql_char($mail_addr));
	$member_id = db_fetch1($sql);
	if (!$member_id)
		redirect('error.php');
}

if (isset($_REQUEST['member_id'])) {
	$member_id = htmlspecialchars($_REQUEST['member_id']);
	$sql = sprintf("SELECT mbr_mail_addr FROM t_member WHERE mbr_member_id = %s", sql_number($member_id));
	$mail_addr = db_fetch1($sql);
}

// ページヘッダ情報設定
$header = new header_class('読者検索/代理解除', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// 表示行数
$limit = $list->get_limit_offset();

// リストヘッダ設定
$list->set_header('メルマガID', 'center', 'mmg_melmaga_id');
$list->set_search_text('melmaga_id', $_REQUEST['melmaga_id'], 'number', 8);

$list->set_header('メルマガ名', 'left', 'mmg_melmaga_name');
$list->set_search_text('melmaga_name', $_POST['melmaga_name'], 'kanji', 10);

$list->set_header('Blog名', 'left', 'blg_title');
$list->set_search_text('blog_title', $_POST['blog_title'], 'kanji', 10);

$list->set_header('発行者', 'left', 'nav_name1 || nav_name2');
$list->set_search_text('navi_name', $_POST['navi_name'], 'kanji', 10);

$list->set_header('ｽﾃｰﾀｽ', 'center', 'mmg_status');
$list->set_search_select('mmg_status', select_open_status('すべて', $_POST['mmg_status']));

$list->set_header('解除', 'center', '');

// リストデータ設定
$sql = "SELECT mmg_melmaga_id,mmg_melmaga_name,blg_title,nvp_blog_id,trim(nav_name1||' '||COALESCE(nav_name2,'')) AS nav_name,mmg_status,nav_navi_id"
	. " FROM t_melmaga_member LEFT JOIN t_melmaga ON mmm_melmaga_id=mmg_melmaga_id"
	. " LEFT JOIN t_navi_page ON mmm_melmaga_id = nvp_melmaga_id"
	. " LEFT JOIN t_navi ON nvp_navi_id=nav_navi_id"
	. " LEFT JOIN t_blog ON nvp_blog_id=blg_blog_id"
	. $list->get_where('mmm_member_id=' . sql_number($member_id))
	. $list->get_order_by(0, 1) . $limit;
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$list->set_data($fetch->mmg_melmaga_id);
	$list->set_data($fetch->mmg_melmaga_name, "edit.php?melmaga_id=$fetch->mmg_melmaga_id", 'メルマガ登録情報を表示・変更します');
	$list->set_data($fetch->blg_title, "$top/navi/blog/edit.php?blog_id=$fetch->nvp_blog_id", 'Blog登録情報を表示・変更します');
	$list->set_data($fetch->nav_name, "$top/navi/navi/edit.php?navi_id=$fetch->nav_navi_id", 'ナビ登録情報を表示・変更します');
	$list->set_data(decode_open_status($fetch->mmg_status));
	$list->set_html('<input type="checkbox" name="delete_flag['.$fetch->mmg_melmaga_id.']">');

}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function proxy_delete() {
	var f = document.list;
	if (confirm("メールマガジン情報を代理解除します。よろしいですか？")) {
		f.action = "proxy_delete.php";
		f.submit(f);
	} else
		return false;
}

//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>
<br>
<form name="list" method="post" onsubmit="return search_check()">
<table border=1 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="tch" width="40%">メールアドレス</td>
		<input type="hidden" name="mail_addr" value="<?=$mail_addr?>">
		<td><?=$mail_addr ?></td>
	</tr>
</table>
<br>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc">
<?php $list->disp_page_line($nrow); ?>
		</td>
		<td class="lb">
		<nobr>
			<input type="button" value="一括解除" onClick="proxy_delete()">
			<input type="submit" value="再検索">
		</nobr>
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>
<input type="hidden" name="member_id" <?=value($member_id)?>>

<?php $list->disp_list(); ?>

</form>
<br>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
<?php $header->page_footer(); ?>

</body>
</html>
