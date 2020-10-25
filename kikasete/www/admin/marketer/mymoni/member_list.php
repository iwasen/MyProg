<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙモニター管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/list.php");

//メイン処理
set_global('marketer', 'マーケター管理｜Ｍｙモニター管理', 'Ｍｙモニターメンバー', BACK_TOP);

// ソート条件
$order_by = order_by(1, 0, 'mn_monitor_id', 'mn_name1_kana||mn_name2_kana', 'mn_sex', 'mn_age', 'mn_mikikon', 'mn_jitaku_area', 'mn_shokugyou_cd', 'rl_self_introduction');

$sql = "SELECT mn_monitor_id,mn_name1,mn_name2,mn_sex,DATE_PART('Y', AGE(mn_birthday)) AS mn_age,mn_mikikon,ar_area_name,sg_shokugyou_name,rl_self_introduction"
		. " FROM t_my_monitor_list"
		. " JOIN t_monitor ON mn_monitor_id=rl_monitor_id"
		. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " WHERE rl_room_id=$room_id"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function onclick_delete() {
	if (delete_check())
		document.form2.submit();
}
function delete_check() {
	var f = document.form2;
	var chk = false;
	if (f["monitor_id[]"]) {
		if (f["monitor_id[]"].length) {
			for (var i = 0; i < f["monitor_id[]"].length; i++) {
				if (f["monitor_id[]"][i].checked) {
					chk = true;
					break;
				}
			}
		} else {
			if (f["monitor_id[]"].checked)
				chk = true;
		}
	}
	if (chk)
		return confirm("選択したメンバーをこのルームから削除しますか？");
	else {
		alert("削除するメンバーを選択してください。");
		return false;
	}
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■Ｍｙモニターメンバー一覧</td>
		<td class="lb">
			<input type="button" value="ﾒﾝﾊﾞｰ追加" onclick="location.href='member_add.php?room_id=<?=$room_id?>'">
			<input type="button" value="ﾒﾝﾊﾞｰ削除" onclick="onclick_delete()">
			<input type="button" value="　戻る　" onclick="location.href='show.php?room_id=<?=$room_id?>'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<form method="post" name="form2" action="member_update.php">
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'ﾓﾆﾀｰID');
		sort_header(2, '名前');
		sort_header(3, '性別');
		sort_header(4, '年齢');
		sort_header(5, '未既婚');
		sort_header(6, '居住地域');
		sort_header(7, '職業');
		sort_header(8, '自己紹介');
		sort_header(0, '削除');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->mn_monitor_id?></td>
		<td><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
		<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
		<td align="right"><?=$fetch->mn_age?></td>
		<td align="center"><?=decode_mikikon($fetch->mn_mikikon)?></td>
		<td><?=htmlspecialchars($fetch->ar_area_name)?></td>
		<td><?=htmlspecialchars($fetch->sg_shokugyou_name)?></td>
		<td><?=nl2br(htmlspecialchars($fetch->rl_self_introduction))?></td>
		<td align="center"><input type="checkbox" name="monitor_id[]" value="<?=$fetch->mn_monitor_id?>"></td>
	</tr>
<?
}
?>
</table>
<input type="hidden" name="room_id" value="<?=$room_id?>">
<input type="hidden" name="next_action" value="delete">
</div>

<? page_footer() ?>
</body>
</html>
