<?
/******************************************************
' System :「きゃららFactory」作画スタッフ用ページ
' Content:似顔絵申込み写真一覧
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/status.php");
include("$inc/list.php");
include("$inc/format.php");
include("inc.php");

set_global('charara', '', 'きゃららFactory 似顔絵申込み写真一覧', BACK_LOGOFF);

get_session_vars($pset, 'nigaoe', 'status');

// ステータス初期値
if (!$status) {
	$status[STATUS_PHOTO_RECEIVED] = '1';
	$status[STATUS_STAFF_ASSIGNED] = '1';
	$status[STATUS_MAKING] = '1';
}

// 写真画像URL取得
$ur_photo = get_system_info('url_photo');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>

<form method="post" action="list.php">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr bgcolor="#ffffdd">
		<td class="lt">■写真をクリックすると詳細が表示されます。</td>
		<td class="small" align="right">
			表示条件：
			<input type="checkbox" name="status[<?=STATUS_PHOTO_RECEIVED?>]" <?=value_checked('1', $status[STATUS_PHOTO_RECEIVED])?>>未着手
			<input type="checkbox" name="status[<?=STATUS_STAFF_ASSIGNED?>]" <?=value_checked('1', $status[STATUS_STAFF_ASSIGNED])?>>割当分
			<input type="checkbox" name="status[<?=STATUS_MAKING?>]" <?=value_checked('1', $status[STATUS_MAKING])?>>作成中
			<input type="checkbox" name="status[<?=STATUS_COMPLETED?>]" <?=value_checked('1', $status[STATUS_COMPLETED])?>>完成
			<input type="checkbox" name="status[<?=STATUS_CHECK_OK?>]" <?=value_checked('1', $status[STATUS_CHECK_OK])?>>チェックOK
			<input type="submit" value="表示">
		</td>
	</tr>
</table>
<input type="hidden" name="pset" value=1>

<table <?=LIST_TABLE?> width="100%">
<?
if ($status[STATUS_CHECK_OK]) {
	$status[STATUS_USER_NOTIFIED] = '1';
	$status[STATUS_DOWNLOADED] = '1';
}
and_join($where, "nd_status IN (" . join(',', array_keys($status)) . ")");
and_join($where, "(nd_staff_id IS NULL OR nd_staff_id=$staff_login_id)");

$sql = "SELECT nd_nigaoe_id,nd_random_id,nd_status,nd_sex_code,nd_age,nd_photo_file,nd_nouki_date FROM t_nigaoe_data WHERE $where ORDER BY nd_nouki_date";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
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
						<td class="note" align="center">納期：<?=format_datetime($fetch->nd_nouki_date)?></td>
					</tr>
					<tr>
						<td class="small" align="center">性別：<?=disp_sex($fetch->nd_sex_code)?>　年齢：<?=disp_age($fetch->nd_age)?></td>
					</tr>
					<tr>
						<td align="center"><a href="edit.php?nigaoe_id=<?=$fetch->nd_nigaoe_id?>"><img class="photo" src="<?=$ur_photo?><?=$fetch->nd_photo_file?>" alt="<?=$random_id?>" width=160></a></td>
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
</form>

<? page_footer() ?>

</body>
</html>
