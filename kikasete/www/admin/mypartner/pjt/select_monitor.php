<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・該当者選定
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー　該当者選定', BACK_TOP);

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;

// マーケター選定フラグ取得
$sql = "SELECT pmn_monitor_id FROM t_mp_monitor WHERE pmn_pjt_id=$myp->pjt_id AND pmn_marketer_choice=1";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$marketer_choice[$fetch->pmn_monitor_id] = 1;
}

// 事務局の決定フラグ取得
if (!$pset) {
	$attr_flag = $_SESSION['s_select_monitor']['attr_flag'];
	$qno_flag = $_SESSION['s_select_monitor']['qno_flag'];

	$sql = "SELECT pmn_monitor_id,pmn_admin_choice FROM t_mp_monitor WHERE pmn_pjt_id=$myp->pjt_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		if ($fetch->pmn_admin_choice === null)
			$admin_choice[$fetch->pmn_monitor_id] = 	$marketer_choice[$fetch->pmn_monitor_id];
		elseif ($fetch->pmn_admin_choice != 0)
			$admin_choice[$fetch->pmn_monitor_id] = $fetch->pmn_admin_choice;
	}

	if (!$admin_choice)
		$admin_choice = $marketer_choice;
} else {
	$_SESSION['s_select_monitor']['attr_flag'] = $attr_flag;
	$_SESSION['s_select_monitor']['qno_flag'] = $qno_flag;
}

// ソート条件
$order_by = order_by(1, 0, '3-pmn_admin_choice,mn_monitor_id', '1-pmn_marketer_choice,mn_monitor_id', 'mn_monitor_id', 'mn_name1,mn_name2', 'pmn_nickname', 'mn_sex', 'mn_age', 'mn_jitaku_area', 'pmn_gyoushu', 'pmn_family_info');
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
function on_update(na) {
	var f = document.form1;
	f.next_action.value = na;
	f.action = 'select_update.php';
	f.submit();
}
function set_check(check) {
	var f = document.form1;
	for (i = 1; i <= 7; i++)
		f["attr_flag[" + i + "]"].checked = check;
	for (i = 1; i <= <?=$enquete->max_question_no?>; i++)
		f["qno_flag[" + i + "]"].checked = check;
}
function choice(monitor_id,st) {
	var f = document.form1;
	f["admin_choice[" + monitor_id + "]"].value = st;
	f.submit();
}
//-->
</script>
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
			<input type="button" value="　戻る　" onclick="location.href='<?=get_return_page()?>'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>

<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" width="20%">■表示条件設定</td>
		<td class="m0" align="right" width="80%">
			<input type="button" name="" value="設問表示" onclick="window.open('disp_question.php?pjt_id=<?=$pjt_id?>', 'question', 'width=750,resizable=yes,scrollbars=yes')">
			<input type="button" name="" value="全チェック" onclick="set_check(true)">
			<input type="button" name="" value="全クリア" onclick="set_check(false)">
		</td>
	</tr>
	<tr>
		<td class="m1">属性</td>
		<td class="n1">
			<input type="checkbox" name="attr_flag[1]" <?=value_checked('1', $attr_flag[1])?>>名前&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="attr_flag[2]" <?=value_checked('1', $attr_flag[2])?>>ニックネーム&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="attr_flag[3]" <?=value_checked('1', $attr_flag[3])?>>性別&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="attr_flag[4]" <?=value_checked('1', $attr_flag[4])?>>年齢&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="attr_flag[5]" <?=value_checked('1', $attr_flag[5])?>>居住地&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="attr_flag[6]" <?=value_checked('1', $attr_flag[6])?>>勤務先業種&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="attr_flag[7]" <?=value_checked('1', $attr_flag[7])?>>家族構成
		</td>
	</tr>
	<tr>
		<td class="m1">設問</td>
		<td class="n1">
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
?>
			<input type="checkbox" name="qno_flag[<?=$qno?>]"<?=value_checked('1', $qno_flag[$qno])?>>Q<?=$qno?>&nbsp;&nbsp;&nbsp;&nbsp;
<?
	}
}
?>
		</td>
	</tr>
</table>
<br>
<?
if ($myp->select_sql != '') {
?>
<div align="center"><input type="submit" value="表示"></div>
<br>
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
	sort_header(1, '決定F', 'rowspan=2');
	sort_header(2, 'ﾏｰｹﾀｰ選定F', 'rowspan=2');
	sort_header(3, 'ﾓﾆﾀｰID', 'rowspan=2');
	if ($attr_flag[1])
		sort_header(4, '名前', 'rowspan=2');
	if ($attr_flag[2])
		sort_header(5, 'ニックネーム', 'rowspan=2');
	if ($attr_flag[3])
		sort_header(6, '性別', 'rowspan=2');
	if ($attr_flag[4])
		sort_header(7, '年齢', 'rowspan=2');
	if ($attr_flag[5])
		sort_header(8, '居住地', 'rowspan=2');
	if ($attr_flag[6])
		sort_header(9, '勤務先業種', 'rowspan=2');
	if ($attr_flag[7])
		sort_header(10, '家族構成', 'rowspan=2');

	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			if ($qno_flag[$qno]) {
				sort_header(0, "Q$qno", count($question->hyousoku) == 0 ? 'rowspan=2' : 'colspan=' . count($question->hyousoku));
			}
		}
	}
?>
	</tr>
	<tr class="tch">
<?
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			if ($qno_flag[$qno]) {
				if (count($question->hyousoku) > 0) {
					foreach (array_keys($question->hyousoku) as $sno) {
?>
		<th><font><?=$sno?></font></th>
<?
					}
				}
			}
		}
	}
?>
	</tr>
<?
	// 単一選択肢、複数選択肢、自由回答
	$sql = "SELECT as_monitor_id,as_question_no,as_sel_no,as_free_answer"
			. " FROM t_ans_select"
			. " WHERE as_enquete_id=$myp->enquete_id"
			. " ORDER BY as_monitor_id,as_question_no,as_sel_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		if ($fetch->as_sel_no > 0)
			$ans[$fetch->as_monitor_id][$fetch->as_question_no][] = $fetch->as_sel_no;
		if ($fetch->as_free_answer != '')
			$ans[$fetch->as_monitor_id][$fetch->as_question_no][] = nl2br(htmlspecialchars($fetch->as_free_answer));
	}

	// マトリクス
	$sql = "SELECT ax_monitor_id,ax_question_no,ax_hyoutou_no,ax_hyousoku_no"
			. " FROM t_ans_matrix"
			. " WHERE ax_enquete_id=$myp->enquete_id"
			. " ORDER BY ax_monitor_id,ax_question_no,ax_hyoutou_no,ax_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$ans[$fetch->ax_monitor_id][$fetch->ax_question_no][$fetch->ax_hyousoku_no][] = $fetch->ax_hyoutou_no;
	}

	// 回答内容を表示
	$sql = "SELECT mn_monitor_id,pmn_nickname,mn_name1,mn_name2,mn_sex,DATE_PART('Y',AGE(mn_birthday)) AS mn_age,ar_area_name,pmn_gyoushu,pmn_family_info,pmn_icp_member_id"
			. " FROM t_mp_monitor"
			. " JOIN t_monitor ON mn_monitor_id=pmn_monitor_id"
			. " JOIN m_area ON ar_area_cd=mn_jitaku_area"
			. " WHERE $myp->select_sql"
			. " $order_by";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$monitor_id = $fetch->mn_monitor_id;
?>
				<tr class="tc<?=$i % 2?>">
<?
		if ($admin_choice[$monitor_id] == 2) {
?>
					<td align="center"><a href="javascript:choice(<?=$monitor_id?>,3)">決定</a><input type="hidden" name="admin_choice[<?=$monitor_id?>]" value="2"></td>
<?
		} elseif ($admin_choice[$monitor_id] == 3) {
?>
					<td align="center"><a href="javascript:choice(<?=$monitor_id?>,2)">ＮＧ</a><input type="hidden" name="admin_choice[<?=$monitor_id?>]" value="3"></td>
<?
		} else {
?>
					<td align="center"><input type="checkbox" name="admin_choice[<?=$monitor_id?>]" <?=value_checked(1, $admin_choice[$monitor_id])?>></td>
<?
		}
?>
					<td align="center"><input type="checkbox" name="marketer_choice[<?=$monitor_id?>]" <?=value_checked(1, $marketer_choice[$monitor_id])?> disabled></td>
					<td align="center"><?=$monitor_id?></td>
<?
		if ($attr_flag[1]) {
?>
					<td align="center"><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
<?
		}
		if ($attr_flag[2]) {
?>
					<td align="center"><?=htmlspecialchars($fetch->pmn_nickname)?></td>
<?
		}
		if ($attr_flag[3]) {
?>
					<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
<?
		}
		if ($attr_flag[4]) {
?>
					<td align="center"><?=$fetch->mn_age?></td>
<?
		}
		if ($attr_flag[5]) {
?>
					<td align="center"><?=$fetch->ar_area_name?></td>
<?
		}
		if ($attr_flag[6]) {
?>
					<td align="center"><?=$fetch->pmn_gyoushu?></td>
<?
		}
		if ($attr_flag[7]) {
?>
					<td align="left"><nobr><?=nl2br(htmlspecialchars($fetch->pmn_family_info))?></nobr></td>
<?
		}
		if (is_array($enquete->question)) {
			foreach ($enquete->question as $qno => $question) {
				if ($qno_flag[$qno]) {
					if (count($question->hyousoku) == 0) {
?>
					<td align="<?=$question->question_type == 3 ? 'left' : 'center'?>"><?=is_array($ans[$monitor_id][$qno]) ? join(',', $ans[$monitor_id][$qno]) : ''?></td>
<?
					} else {
						foreach (array_keys($question->hyousoku) as $sno) {
?>
					<td align="center"><?=is_array($ans[$monitor_id][$qno][$sno]) ? join(',', $ans[$monitor_id][$qno][$sno]) : ''?></td>
<?
						}
					}
				}
?>
<?
			}
		}
?>
				</tr>
<?
	}
?>
</table>
<br>

<div align="center">
	<input type="button" value="一時保存" onclick="on_update('save')">
	<input type="button" value="<?=$myp->icp_job_id ? '参加者を追加・修正する' : '承認して参加者を会議室へ入れる'?>" onclick="on_update('apply')">
	<input type="hidden" name="next_action">
	<input type="hidden" name="pjt_id" <?=value($myp->pjt_id)?>>
</div>
<?
}
?>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
