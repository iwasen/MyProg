<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・会議室設計情報
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// 会議室ポリシー
function decode_policy($code) {
	switch ($code) {
	case 1:
		return '本名を使用する';
	case 2:
		return 'ニックネームを使用する';
	}
}

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー会議室設計情報', BACK_TOP);

// 戻り先設定
set_return_page('r_job.php', "pjt_id=$pjt_id");
set_return_page('r_job_edit.php', "pjt_id=$pjt_id");
set_return_page('r_job_update.php', "pjt_id=$pjt_id");

$myp = new my_partner_class;
$myp->read_db($pjt_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($myp->room_name)?></td>
		<td class="lb">
<?
if ($myp->recruit_flag != 7) {
?>
			<input type="button" value="リクルートジョブ登録" onclick="location.href='r_job_edit.php?pjt_id=<?=$pjt_id?>'">
<?
}
?>
			<input type="button" value="　戻る　" onclick="location.href='<?=get_return_page()?>'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" value="1">
<input type="hidden" name="sort_dir" value="1">
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr>
		<td class="m0" colspan=2>■ステータス</td>
	</tr>
	<tr>
		<td class="m1" width="20%">リクルート</td>
		<td class="n1" width="80%">
			<a href="r_job.php?pjt_id=<?=$pjt_id?>" title="リクルートジョブの詳細を表示します"><?=decode_recruit_status($myp->status)?></a>
		</td>
	</tr>
	<tr>
		<td class="m1">会議室</td>
		<td class="n1">
<?
if ($myp->status >= MPS_ROOM_JISSHI) {
?>
			<a href="pjt_job.php?pjt_id=<?=$pjt_id?>" title="会議室の詳細を表示します"><?=decode_room_status($myp->status)?></a>
<?
} else {
?>
			<?=decode_room_status($myp->status)?>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■会議室情報</td>
	</tr>
	<tr>
		<td class="m1">目的</td>
		<td class="n1"><?=nl2br(htmlspecialchars($myp->purpose))?></td>
	</tr>
	<tr>
		<td class="m1">リクルート条件</td>
		<td class="n1"><?=join('<br>', $myp->recruit_jouken)?></td>
	</tr>
	<tr>
		<td class="m1">参加条件</td>
		<td class="n1"><?=join('<br>', $myp->sanka_jouken)?></td>
	</tr>
	<tr>
		<td class="m1">会議室運営ポリシー</td>
		<td class="n1"><?=decode_policy($myp->policy)?></td>
	</tr>
	<tr>
		<td class="m1">メンバー数</td>
		<td class="n1"><?=number_format($myp->member_num)?>人</td>
	</tr>
	<tr>
		<td class="m1">リクルート期間</td>
		<td class="n1"><?=format_datetime($myp->recruit_start)?>〜<?=format_datetime($myp->recruit_end)?></td>
	</tr>
	<tr>
		<td class="m1">決定日</td>
		<td class="n1"><?=format_date($myp->decision_date)?></td>
	</tr>
	<tr>
		<td class="m1">会議室実施期間</td>
		<td class="n1"><?=format_date($myp->room_start)?>〜<?=format_date($myp->room_end)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■マーケター情報</td>
	</tr>
<?
$marketer_id = $myp->marketer_id;
$sql = "SELECT mr_kinmu_name,mr_name1,mr_name2 FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
?>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1"><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
	</tr>
	<tr>
		<td class="m1">マーケターID</td>
		<td class="n1"><a href="marketer_info.php?marketer_id=<?=$marketer_id?>" target="_blank" title="担当者情報を表示します"><?=$marketer_id?></a></td>
	</tr>
	<tr>
		<td class="m1">マーケター名</td>
		<td class="n1"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
