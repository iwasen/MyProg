<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート変更処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// アンケート実施状態選択肢
function select_enq_status($sel) {
	if ($sel <= 4) {
		echo "<option ", value_selected('0', $sel), ">作成中</option>";
		echo "<option ", value_selected('1', $sel), ">作成完了</option>";
		echo "<option ", value_selected('2', $sel), ">申請中</option>";
		echo "<option ", value_selected('3', $sel), ">事務局チェック中</option>";
		echo "<option ", value_selected('4', $sel), ">承認済み</option>";
		echo "<option ", value_selected('8', $sel), ">再申請</option>";
	} else {
		echo "<option ", value_selected('5', $sel), ">実施中</option>";
		echo "<option ", value_selected('6', $sel), ">一時停止</option>";
		echo "<option ", value_selected('7', $sel), ">終了</option>";
	}
	echo "<option ", value_selected('9', $sel), ">削除済み</option>";
}

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｐｒｏアンケート情報変更', BACK_TOP);

// セッション処理
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
	$marketer_id = $pro_enq->marketer_id;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq->read_db($marketer_id, $pro_enq_no);
}

$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;

// ファインディング有無の初期値
if ($pro_enq->finding_flag == '')
	$pro_enq->finding_flag = DBFALSE;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update1.php">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">■実施概要</td>
	</tr>
	<tr>
		<td width="20%" class="m1">アンケートタイトル</td>
		<td width="80%" class="n1">
			<input type="text" name="title" size="46" <?=value($enquete->title)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">アンケート形式</td>
		<td class="n1">
			<?=$pro_enq->finding_flag == DBTRUE ? 'ファインディングあり' : 'ファインディングなし'?>
			<input type="hidden" name="finding_flag" <?=value($pro_enq->finding_flag)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">開始日時</td>
		<td class="n1">
			<input type="text" name="start_date_y" <?=value($enquete->start_date_y)?> size="5" maxlength="4">年
			<input type="text" name="start_date_m" <?=value($enquete->start_date_m)?> size="3" maxlength="2">月
			<input type="text" name="start_date_d" <?=value($enquete->start_date_d)?> size="3" maxlength="2">日
		</td>
	</tr>
	<tr>
		<td class="m1">終了日時</td>
		<td class="n1">
			<input type="text" name="end_date_y" <?=value($enquete->end_date_y)?> size="5" maxlength="4">年
			<input type="text" name="end_date_m" <?=value($enquete->end_date_m)?> size="3" maxlength="2">月
			<input type="text" name="end_date_d" <?=value($enquete->end_date_d)?> size="3" maxlength="2">日
			<input type="text" name="end_date_h" <?=value($enquete->end_date_h)?> size="3" maxlength="2">時
		</td>
	</tr>
	<tr>
		<td class="m1">サンプル条件</td>
		<td class="n1">
<?
$ans_cond = explode("\n", $pro_enq->ans_cond);
for ($i = 0; $i < 5; $i++) {
?>
				<input type="text" name="ans_cond[]" size=50 maxlength=100 <?=value($ans_cond[$i])?>><br>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">ポイント数</td>
		<td class="n1">
			<input class="number" type="text" name="point" size="3" maxlength="3" <?=value($enquete->point)?>>
<?
if ($pro_enq->finding_flag == DBTRUE) {
?>
			/ <input class="number" type="text" name="point2" size="3" maxlength="3" <?=value($enquete2->point)?>>
<?
}
?>
			ポイント
		</td>
	</tr>
	<tr>
		<td class="m1">実施状態</td>
		<td class="n1"><select name="status"><? select_enq_status($enquete->status) ?></select></td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
