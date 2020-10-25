<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:似顔絵情報管理｜似顔絵受付リスト
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/decode.php");
include("$inc/status.php");
include("inc.php");

// *表示
function check_aster($date) {
	if ($date != '')
		echo '*';
}

// 納期チェック
function check_nouki($nouki_date, $dl_mail_date) {
	if ($nouki_date != '') {
		if ($dl_mail_date == '') {
			// 納期超過
			if ($nouki_date < date("Y-m-d H:i:s"))
				echo "<font color='red'><b>" . format_date($nouki_date) . "</b></font>";
			// 納期まで24時間以内
			elseif (add_datetime($nouki_date, 0 , 0, 0, -24, 0, 0) <= date("Y-m-d H:i:s"))
				echo "<font color='red'>" . format_date($nouki_date) . "</font>";
			// それ以外
			else
				echo format_date($nouki_date);
		} else {
			// 納品済み
			echo "<font color='green'>" . format_date($nouki_date) . "</font>";
		}
	} else {
		echo '未設定';
	}
}

//メイン処理
set_global('nigaoe', '似顔絵情報管理', '似顔絵受付リスト', BACK_TOP);

// セッション登録
get_session_vars($pset, 'order_list', 'status', 'product', 'displine', 'sort_col', 'sort_dir', 'page', 'photo');

// where条件
if ($status != '') {
	and_join($where, "nd_status=" . $status);
}

if ($product != '') {
	and_join($where, "nd_product_cd LIKE '%$product%'");
} else {
	and_join($where, "nd_product_cd='01'");
	$product = '01';
}

if ($where != '')
	$where = "WHERE $where";

// ソート条件
$order_by = order_by(9, 0, 'nd_random_id', 'nd_mail_addr', 'nd_sex_code', 'nd_status', 'as_server_id', 'nd_product_cd', 'st_name1_kana', 'nd_accept_date', 'nd_nouki_date');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT count(nd_nigaoe_id) AS count FROM t_nigaoe_data LEFT JOIN m_account_server ON nd_server_id=as_server_id $where";
$num = db_fetch1($sql);

$sql = "SELECT * FROM t_nigaoe_data LEFT JOIN m_account_server ON nd_server_id=as_server_id LEFT JOIN m_staff ON nd_staff_id=st_staff_id $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);

// 写真画像URL取得
$ur_photo = get_system_info('url_photo');

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">2004/01/13
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}

function photo_disp() {
	var f = document.form1;
	f.photo.value='on'
	f.submit();
}

function list_disp() {
	var f = document.form1;
	f.photo.value=''
	f.submit();
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■似顔絵受付一覧　<font size=-1 color="blue">（総数:&nbsp;<?=$num?>名）</font></td>
		<td class="lb" align="right">
			<input type="button" value="　戻る　" onclick='location.href="<?=$top?>/menu.php"'>
		</td>
	</tr>
	<tr>
		<td class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
			<nobr>ｽﾃｰﾀｽ<select name="status"><?=select_status('全て',$status)?></select>&nbsp;</nobr>
			<nobr>商品<select name="product"><?=select_product('',$product)?></select>&nbsp;</nobr>
			<input type="button" value="検索" onclick="submit()">
		</td>
<?
if (!$photo) {
?>
		<td class="lc"><nobr><a href="javascript:photo_disp()">写真表示</a></nobr></td>
<?
} else {
?>
		<td class="lc"><nobr><a href="javascript:list_disp()">リスト表示</a></nobr></td>
<?
}
?>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="photo" <?=value($photo)?>>
</form>
<?
if (!$photo) {
?>
<table <?=LIST_TABLE?> width="100%" class="small2">
	<tr class="tch">
<?
		sort_header(1, '申込みID');
		sort_header(2, 'メールアドレス');
		sort_header(3, '性別');
		sort_header(4, 'ｽﾃｰﾀｽ');
		sort_header(5, '発注元');
		sort_header(6, '商品');
		sort_header(7, '担当');
		sort_header(8, '受付日時');
		sort_header(9, '納期');
		sort_header(0, '割当');
		sort_header(0, '開始');
		sort_header(0, '完了');
		sort_header(0, '通知');
		sort_header(0, 'ＤＬ');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$status = $fetch->st_status;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?nd_id=<?=$fetch->nd_nigaoe_id?>" title="詳細情報を表示・変更します"><?=htmlspecialchars($fetch->nd_random_id)?></a></td>
		<td><a href='mailto:<?=$fetch->nd_mail_addr?>' title="会員にメールします"><?=htmlspecialchars($fetch->nd_mail_addr)?></a></td>
		<td align="center"><?=decode_sex($fetch->nd_sex_code)?></td>
		<td align="center"><?=$fetch->nd_status?></td>
		<td><?=decode_ac_server($fetch->as_server_id)?></td>
		<td align="center"><?=decode_product($fetch->nd_product_cd)?></td>
		<td align="center"><?=$fetch->st_name1?></td>
		<td align="center"><?=format_date($fetch->nd_accept_date)?></td>
		<td align="center"><?=check_nouki($fetch->nd_nouki_date, $fetch->nd_dl_mail_date)?></td>
		<td align="center"><?=check_aster($fetch->nd_assign_date)?></td>
		<td align="center"><?=check_aster($fetch->nd_creation_date)?></td>
		<td align="center"><?=check_aster($fetch->nd_completion_date)?></td>
		<td align="center"><?=check_aster($fetch->nd_dl_mail_date)?></td>
		<td align="center"><?=check_aster($fetch->nd_download_date)?></td>
	</tr>
<?
}
?>
</table>
<table width="100%" class="small2">
	<tr>
		<td align="right">（<font color="#000099">納期</font>：<font color="green">緑</font>=納品済み、<font color="red">赤</font>=納期まで24時間以内、<font color="red"><b>赤</b></font>=納期超過、黒=それ以外）</td>
	</tr>
</table>
<?
} else {
?>

<table <?=LIST_TABLE?> width="100%">
<?
for ($i = 0; $i < $nrow; ) {
?>
	<tr>
<?
	for ($j = 0; $j < 5; $j++) {
?>
		<td width="20%" valign="top">
<?
		if ($i < $nrow) {
			$fetch = pg_fetch_object($result, $i);
			$random_id = $fetch->nd_random_id;
?>
				<table border=0 cellspacing=4 cellpadding=0 width="100%">
					<tr>
						<td class="small" align="center">性別：<?=disp_sex($fetch->nd_sex_code)?>　年齢：<?=disp_age($fetch->nd_age)?></td>
					</tr>
					<tr>
						<td align="center"><a href="edit.php?nd_id=<?=$fetch->nd_nigaoe_id?>"><img class="photo" src="<?=$ur_photo?><?=$fetch->nd_photo_file?>" alt="<?=$random_id?>" width=160></a></td>
					</tr>
					<tr>
						<td class="note" align="center"><?=disp_status($fetch->nd_status)?></td>
					</tr>
				</table>
<?
			$i++;
		}
?>
		</td>
<?
	}
?>
	</tr>
<?
}
?>
</table>

<?
}
?>
</div>

<? page_footer() ?>
</body>
</html>
