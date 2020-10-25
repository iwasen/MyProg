<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙモニターメンバー更新処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// メイン処理
set_global('marketer', 'マーケター管理｜Ｍｙモニター管理', 'Ｍｙモニターメンバー更新', BACK_TOP);

switch ($next_action) {
case 'add':
	db_begin_trans();
	$ary = explode("\n", $member);
	foreach ($ary as $data) {
		$ary2 = explode(',', trim($data), 2);
		if ($ary2[0] != '') {
			$sql = "SELECT * FROM t_my_monitor_list WHERE rl_room_id=$room_id AND rl_monitor_id=$ary2[0]";
			$result = db_exec($sql);
			if (pg_numrows($result) == 0) {
				$sql = sprintf("INSERT INTO t_my_monitor_list (rl_room_id,rl_monitor_id,rl_self_introduction) VALUES (%s,%s,%s)",
						sql_number($room_id),
						sql_number($ary2[0]),
						sql_char($ary2[1]));
				db_exec($sql);
			}
		}
	}
	db_commit_trans();
	$msg = 'Ｍｙモニターメンバーを追加しました。';
	$back = "location.href='member_list.php?room_id=$room_id'";
	break;
case 'delete':
	$monitor_id = get_multi_data($monitor_id);
	if ($monitor_id != '') {
		$sql = "DELETE FROM t_my_monitor_list WHERE rl_room_id=$room_id AND rl_monitor_id IN ($monitor_id)";
		db_exec($sql);
	}
	$msg = 'Ｍｙモニターメンバーを削除しました。';
	$back = "location.href='member_list.php?room_id=$room_id'";
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
