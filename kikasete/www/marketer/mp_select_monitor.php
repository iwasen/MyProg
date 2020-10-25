<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;

set_return_page('mp_require_update.php', "pjt_id=$pjt_id");

if (!$pset) {
	$attr_flag = $_SESSION['s_select_monitor']['attr_flag'];
	$qno_flag = $_SESSION['s_select_monitor']['qno_flag'];

	$sql = "SELECT pmn_monitor_id FROM t_mp_monitor WHERE pmn_pjt_id=$myp->pjt_id AND pmn_marketer_choice=1";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$choice[$fetch->pmn_monitor_id] = 1;
	}
} else {
	$_SESSION['s_select_monitor']['attr_flag'] = $attr_flag;
	$_SESSION['s_select_monitor']['qno_flag'] = $qno_flag;
}

if ($sort_col == '')
	$sort_col = 2;
?>
<? marketer_header('Myパートナー', PG_NULL) ?>

<script LANGUAGE="JavaScript">
<!--
function sort_list(sort) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.submit();
}
function on_update(na) {
	var f = document.form1;
	f.next_action.value = na;
	f.action = 'mp_select_update.php';
	f.submit();
}
function all_check() {
	var f = document.form1;
	for (i = 1; i <= 6; i++)
		f["attr_flag[" + i + "]"].checked = true;
	for (i = 1; i <= <?=$enquete->max_question_no?>; i++)
		f["qno_flag[" + i + "]"].checked = true;
}
// -->
</script>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->


			<div align="center">
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td width="95"><img src="images/fl_l_1_0.gif" alt="" width="95" height="18" border="0"></td>
				<td colspan="3" width="285"><img src="images/fl_l_2_0.gif" alt="" width="285" height="18" border="0"></td>
				<td colspan="2" width="190"><img src="images/fl_l_3_1.gif" alt="" width="190" height="18" border="0"></td>
				<td width="95"><img src="images/fl_l_4_0.gif" alt="" width="95" height="18" border="0"></td>
			</tr>
			<tr>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
			</tr>
			<tr>
				<td><img src="images/fl_s_1_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_2_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_3_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_4_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_5_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_6_2.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_7_0.gif" alt="" width="95" height="50" border="0"></td>
			</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			</div>

			<form method="post" name="form1">
			<table border=0 cellspacing=0 cellpadding=1 width="100%">
				<tr>
					<td>■<?=htmlspecialchars($myp->room_name)?></td>
				</tr>
			</table>

			<table border=0 cellspacing=2 cellpadding=3 width="99%">
				<tr>
					<td width="20%">■表示条件設定</td>
					<td align="right" width="80%">
						<input type="button" value="該当者条件<?=$myp->select_sql == '' ? '設定' : '変更'?>" onclick="location.href='mp_require_edit.php?pjt_id=<?=$pjt_id?>'">
						<input type="button" value="設問表示" onclick="window.open('mp_question.php?pjt_id=<?=$pjt_id?>', 'question')">
						<input type="button" value="全チェック" onclick="all_check()">
					</td>
				</tr>
				<tr>
					<td bgcolor="#292864"><span style="color:#ffffff;">属性</span></td>
					<td bgcolor="#e3e3f4">
						<input type="checkbox" name="attr_flag[1]" <?=value_checked('1', $attr_flag[1])?>><?=$myp->policy == 1 ? '名前' : 'ニックネーム'?>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" name="attr_flag[2]" <?=value_checked('1', $attr_flag[2])?>>性別&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" name="attr_flag[3]" <?=value_checked('1', $attr_flag[3])?>>年齢&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" name="attr_flag[4]" <?=value_checked('1', $attr_flag[4])?>>居住地&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" name="attr_flag[5]" <?=value_checked('1', $attr_flag[5])?>>勤務先業種&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" name="attr_flag[6]" <?=value_checked('1', $attr_flag[6])?>>家族構成
					</td>
				</tr>
				<tr>
					<td bgcolor="#292864"><span style="color:#ffffff;">設問</span></td>
					<td bgcolor="#e3e3f4">
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
			<input type="hidden" name="pset" value="1">
			<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<?
if ($myp->select_sql != '') {
?>
			<div align="center"><input type="submit" value="表示"></div>
			<br>
			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="99%">
				<tr bgcolor="#292864">
					<td align="center" rowspan="2" onclick='sort_list(1)' style='cursor:hand' title='クリックすると選定フラグで昇順にソートします'><span style="color:#ffffff;">選定F</span></td>
					<td align="center" rowspan="2"onclick='sort_list(2)' style='cursor:hand' title='クリックするとモニターIOで昇順にソートします'><span style="color:#ffffff;">モニターID</span></td>
<?
	if ($attr_flag[1]) {
?>
					<td rowspan="2" onclick='sort_list(3)' style='cursor:hand' title='クリックすると名前で昇順にソートします' align="center"><span style="color:#ffffff;">名前</span></td>
<?
	}
	if ($attr_flag[2]) {
?>
					<td rowspan="2" onclick='sort_list(4)' style='cursor:hand' title='クリックすると性別で昇順にソートします' align="center"><span style="color:#ffffff;">性別</span></td>
<?
	}
	if ($attr_flag[3]) {
?>
					<td rowspan="2" onclick='sort_list(5)' style='cursor:hand' title='クリックすると年齢で昇順にソートします' align="center"><span style="color:#ffffff;">年齢</span></td>
<?
	}
	if ($attr_flag[4]) {
?>
					<td rowspan="2" onclick='sort_list(6)' style='cursor:hand' title='クリックすると居住地で昇順にソートします' align="center"><span style="color:#ffffff;">居住地</span></td>
<?
	}
	if ($attr_flag[5]) {
?>
					<td rowspan="2" onclick='sort_list(7)' style='cursor:hand' title='クリックすると勤務先業種で昇順にソートします' align="center"><span style="color:#ffffff;">勤務先業種</span></td>
<?
	}
	if ($attr_flag[6]) {
?>
					<td rowspan="2" onclick='sort_list(8)' style='cursor:hand' title='クリックすると家族構成で昇順にソートします' align="center"><span style="color:#ffffff;">家族構成</span></td>
<?
	}
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			if ($qno_flag[$qno]) {
?>
					<td <?=count($question->hyousoku) == 0 ? 'rowspan=2' : 'colspan=' . count($question->hyousoku)?> align="center"><span style="color:#ffffff;">Q<?=$qno?></span></td>
<?
			}
		}
	}
?>
				</tr>
				<tr bgcolor="#292864">
<?
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			if ($qno_flag[$qno]) {
				if (count($question->hyousoku) > 0) {
					foreach (array_keys($question->hyousoku) as $sno) {
?>
					<td align="center"><span style="color:#ffffff;"><?=$sno?></span></td>
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

	// 表示順
	switch ($sort_col) {
	case 1:
		$order = is_array($choice) ? 'pmn_monitor_id IN (' . join(',', array_keys($choice)) . ') DESC' : 'mn_monitor_id';
		break;
	case 2:
		$order = 'mn_monitor_id';
		break;
	case 3:
		$order = $myp->policy == 1 ? 'mn_name1,mn_name2' : 'pmn_nickname';
		break;
	case 4:
		$order = 'mn_sex';
		break;
	case 5:
		$order = 'mn_age';
		break;
	case 6:
		$order = 'mn_jitaku_area';
		break;
	case 7:
		$order = 'pmn_gyoushu';
		break;
	case 8:
		$order = 'pmn_family_info';
		break;
	}

	// 回答内容を表示
	$sql = "SELECT mn_monitor_id,pmn_nickname,mn_name1,mn_name2,mn_sex,DATE_PART('Y',AGE(mn_birthday)) AS mn_age,ar_area_name,pmn_gyoushu,pmn_family_info"
			. " FROM t_mp_monitor"
			. " JOIN t_monitor ON mn_monitor_id=pmn_monitor_id"
			. " JOIN m_area ON ar_area_cd=mn_jitaku_area"
			. " WHERE $myp->select_sql"
			. " ORDER BY $order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$monitor_id = $fetch->mn_monitor_id;
?>
				<tr>
					<td align="center"><input type="checkbox" name="choice[<?=$monitor_id?>]" <?=value_checked(1, $choice[$monitor_id])?><?=$myp->status <= MPS_MEM_SENTEI ? '' : ' disabled'?>></td>
					<td align="center"><?=$monitor_id?></td>
<?
		if ($attr_flag[1]) {
?>
					<td align="center"><?=htmlspecialchars($myp->policy == 1 ? "$fetch->mn_name1 $fetch->mn_name2" : $fetch->pmn_nickname)?></td>
<?
		}
		if ($attr_flag[2]) {
?>
					<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
<?
		}
		if ($attr_flag[3]) {
?>
					<td align="center"><?=$fetch->mn_age?></td>
<?
		}
		if ($attr_flag[4]) {
?>
					<td align="center"><?=$fetch->ar_area_name?></td>
<?
		}
		if ($attr_flag[5]) {
?>
					<td align="center"><?=$fetch->pmn_gyoushu?></td>
<?
		}
		if ($attr_flag[6]) {
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
<?
	if ($myp->status <= MPS_MEM_SENTEI) {
?>
			<input type="button" value="一時保存する" onclick="on_update('save')">
<?
		if (sql_time($myp->decision_date) <= time()) {
?>
			<input type="button" value="参加者を確定し、申請する" onclick="on_update('apply')">
<?
		}
	}
?>
			<input type="button" value="　戻る　" onclick="location.href='<?=get_return_page()?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="pjt_id" <?=value($myp->pjt_id)?>>
			</div>
<?
}
?>
			</form>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
