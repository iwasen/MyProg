<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・会議室の設計
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー　会議室の設計', BACK_TOP);

// Myパートナークラス取得
if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
	$marketer_id = $myp->marketer_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	if ($pjt_id) {
		$myp->read_db($pjt_id);
		$marketer_id = $myp->marketer_id;
	} else {
		$myp->marketer_id = $marketer_id;
		$myp->save_enquete = true;
		$myp->save_search = true;

		$fetch = get_system_info('sy_myp_max_send');
		$myp->send_num = $fetch->sy_myp_max_send;
	}
}
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

<form method="post" name="form1" action="pjt_update.php">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
<?
$sql = "SELECT mr_marketer_id,mr_name1,mr_name2,mr_kinmu_name"
		. " FROM t_marketer"
		. " WHERE mr_marketer_id=" . sql_number($marketer_id);
$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
?>
	<tr>
		<td class="m0" colspan=2>■マーケター情報</td>
	</tr>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1"><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
	</tr>
	<tr>
		<td class="m1">マーケターID</td>
		<td class="n1"><?=$fetch->mr_marketer_id?></td>
	</tr>
	<tr>
		<td class="m1">マーケター名</td>
		<td class="n1"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■会議室情報</td>
	</tr>
	<tr>
		<td class="m1">会議室名</td>
		<td class="n1"><input class="kanji" type="text" name="room_name" size=50 maxlength=100 <?=value($myp->room_name)?>></td>
	</tr>
	<tr>
		<td class="m1">目的</td>
		<td class="n1"><textarea name="purpose" cols="50" rows="5" class="kanji"><?=htmlspecialchars($myp->purpose)?></textarea></td>
	</tr>
<!--
	<tr>
		<td class="m1">参加条件</td>
		<td class="n1">
			<table>
<?
if ($myp->sanka_jouken_num < 3)
	$myp->sanka_jouken_num = 3;

for ($i = 1; $i <= $myp->sanka_jouken_num; $i++) {
?>
				<tr>
					<td><input class="kanji" type="text" name="sanka_jouken<?=$i?>" size=50 maxlength=100 <?=value($myp->sanka_jouken[$i])?>></td>
				</tr>
<?
}
?>
				<tr>
					<td><input type="submit" value="参加条件の追加" onclick="document.form1.next_action.value='add_sanka_jouken'"></td>
				</tr>
			</table>
		</td>
	</tr>
-->
	<tr>
		<td class="m1">リクルート条件</td>
		<td class="n1">
			<table>
<?
if ($myp->recruit_jouken_num < 3)
	$myp->recruit_jouken_num = 3;

for ($i = 1; $i <= $myp->recruit_jouken_num; $i++) {
?>
				<tr>
					<td><input class="kanji" type="text" name="recruit_jouken<?=$i?>" size=50 maxlength=100 <?=value($myp->recruit_jouken[$i])?>></td>
				</tr>
<?
}
?>
				<tr>
					<td><input type="submit" value="リクルート条件の追加" onclick="document.form1.next_action.value='add_recruit_jouken'"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">会議室運営ポリシー</td>
		<td class="n1"><input type="radio" name="policy" <?=value_checked('1', $myp->policy)?>>本名を使用する　<input type="radio" name="policy" <?=value_checked('2', $myp->policy)?>>ニックネームを使用する</td>
	</tr>
	<tr>
		<td class="m1">メンバー数</td>
		<td class="n1"><input type="text" name="member_num" size=5 maxlength=100 <?=value($myp->member_num)?>>人</td>
	</tr>
	<tr>
		<td class="m1">リクルート期間</td>
		<td class="n1">
			<input type="text" name="recruit_start_y" <?=value($myp->recruit_start_y)?> size="5" maxlength="4">年
			<input type="text" name="recruit_start_m" <?=value($myp->recruit_start_m)?> size="3" maxlength="2">月
			<input type="text" name="recruit_start_d" <?=value($myp->recruit_start_d)?> size="3" maxlength="2">日
			<input type="text" name="recruit_start_h" <?=value($myp->recruit_start_h)?> size="3" maxlength="2">時
			<input type="submit" value="自動計算" onclick="document.form1.next_action.value='calc_date'">
			〜
			<input type="text" name="recruit_end_y" <?=value($myp->recruit_end_y)?> size="5" maxlength="4">年
			<input type="text" name="recruit_end_m" <?=value($myp->recruit_end_m)?> size="3" maxlength="2">月
			<input type="text" name="recruit_end_d" <?=value($myp->recruit_end_d)?> size="3" maxlength="2">日
			<input type="text" name="recruit_end_h" <?=value($myp->recruit_end_h)?> size="3" maxlength="2">時
		</td>
	</tr>
	<tr>
		<td class="m1">決定日</td>
		<td class="n1">
			<input type="text" name="decision_date_y" <?=value($myp->decision_date_y)?> size="5" maxlength="4">年
			<input type="text" name="decision_date_m" <?=value($myp->decision_date_m)?> size="3" maxlength="2">月
			<input type="text" name="decision_date_d" <?=value($myp->decision_date_d)?> size="3" maxlength="2">日
		</td>
	</tr>
	<tr>
		<td class="m1">会議室実施期間</td>
		<td class="n1">
			<input type="text" name="room_start_y" <?=value($myp->room_start_y)?> size="5" maxlength="4">年
			<input type="text" name="room_start_m" <?=value($myp->room_start_m)?> size="3" maxlength="2">月
			<input type="text" name="room_start_d" <?=value($myp->room_start_d)?> size="3" maxlength="2">日
			〜
			<input type="text" name="room_end_y" <?=value($myp->room_end_y)?> size="5" maxlength="4">年
			<input type="text" name="room_end_m" <?=value($myp->room_end_m)?> size="3" maxlength="2">月
			<input type="text" name="room_end_d" <?=value($myp->room_end_d)?> size="3" maxlength="2">日
		</td>
	</tr>
	<tr>
		<td align="center" colspan=2>
			<br>
<?
if (!$pjt_id) {
?>
			<input type="submit" value="　登録　" onclick="document.form1.next_action.value='update'">
<?
} else {
	if ($myp->status <= MPS_REC_CHECK) {
?>
			<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<?
	}
}
?>
			<input type="button" value="　戻る　" onclick="location.href='pjt_list.php'">
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
