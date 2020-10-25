<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// チェックボックス表示処理
function checkbox_common($sql, $name, $code, $cols) {
	$ary = explode(',', $code);

	$result = db_exec($sql);
	$nrow = pg_num_rows($result);

	echo '<table border=0 cellspacing=0 cellpadding=0 width="100%">', "\n";
	$col = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%'><input type='checkbox' name='${name}[]' value='$fetch[0]'", in_array($fetch[0], $ary) ? ' checked' : '', "><font size=2 class='honbun2'>", htmlspecialchars($fetch[1]), "</font></td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}

// セッション処理
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	if ($my_enq_no == '') {
		$myenq->staff_id = $_SESSION['ss_staff_id'];
	} else
		$myenq->read_db($_SESSION['ss_staff_id'], $my_enq_no);
}

$search = &$myenq->search;

// 最大発信数のデフォルト
$send_num = ($myenq->send_num) ? $myenq->send_num : 300;
?>
<? staff_header('アンケート登録', "onload='change_age_type()'") ?>

<script type="text/javascript">
<!--
function change_age_type() {
	var f = document.form1;
	if (f.age_type.selectedIndex == 1) {
		document.getElementById("age_type1").disabled = false;
	} else {
		document.getElementById("age_type1").disabled = true;
		for (var i = 0; i < f["age_cd[]"].length; i++) {
			f["age_cd[]"][i].checked = false;
		}
	}
	if (f.age_type.selectedIndex == 2) {
		document.getElementById("age_type2").disabled = false;
		f.age_from.disabled = false;
		f.age_to.disabled = false;
	} else {
		document.getElementById("age_type2").disabled = true;
		f.age_from.disabled = true;
		f.age_to.disabled = true;
		f.age_from.value = "";
		f.age_to.value = "";
	}
}
function all_check(radio, flag) {
	var f = document.form1;
	for (var i = 0; i < f[radio].length; i++)
		f[radio][i].checked = flag;
}
//-->
</script>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<form method="post" name="form1" action="myenq_update3.php">
			<table border=0 cellspacing=2 cellpadding=3 width="95%" class="honbun2">
				<tr>
					<td bgcolor="#eff7e8">■条件の設定</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff">各項目で全て選択していない場合は、全て選択している場合と同じです。</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td bgcolor="#eeeeee" width="15%">最大発信数</td>
								<td width="85%" bgcolor="#ffeecc">
									<input type="text" name="send_num" size="4" maxlength="4" <?=value($send_num)?>> 通
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" width="15%">性別</td>
								<td width="85%" bgcolor="#ffeecc">
									<input type="checkbox" name="sex[]" <?=value_checked('1', $search->sex)?>>男性&nbsp;
									<input type="checkbox" name="sex[]" <?=value_checked('2', $search->sex)?>>女性
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">年代</td>
<?
// 年齢指定方法
if ($search->age_cd != '')
	$age_type = '1';
elseif ($search->age_from != '' || $search->age_to != '')
	$age_type = '2';
else
	$age_type = '';
?>
								<td bgcolor="#ffeecc">
									<select name="age_type" onchange="change_age_type()">
										<option <?=value_selected('', $age_type)?>>形式を選択してください</option>
										<option <?=value_selected('1', $age_type)?>>5歳刻みの年齢</option>
										<option <?=value_selected('2', $age_type)?>>任意の年齢</option>
									</select>
									<span id=age_type1>
<?
$sql = "SELECT ae_age_cd,ae_age_text FROM m_age ORDER BY ae_age_cd";
checkbox_common($sql, 'age_cd', $search->age_cd, 4);
?>
									</span>
									<span id="age_type2">
									<table border=0 width="100%" class="honbun2">
										<tr>
											<td><input type="text" name="age_from" <?=value($search->age_from)?> size="4" maxlength="2">
											才〜
											<input type="text" name="age_to" <?=value($search->age_to)?> size="4" maxlength="2">
											才 <font class="note">（半角数字）</font></td>
										</tr>
									</table>
									</span>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">未既婚</td>
								<td bgcolor="#ffeecc">
									<input type="checkbox" name="mikikon[]" <?=value_checked('1', $search->mikikon)?>>独身&nbsp;
									<input type="checkbox" name="mikikon[]" <?=value_checked('2', $search->mikikon)?>>既婚
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">子供の有無</td>
								<td width="85%" bgcolor="#ffeecc">
									<input type="checkbox" name="child[]" <?=value_checked('1', $search->child)?>>有り&nbsp;
									<input type="checkbox" name="child[]" <?=value_checked('2', $search->child)?>>無し
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">居住地域</td>
								<td bgcolor="#ffeecc">
									<div>
										<input type="button" value="全選択" onclick="all_check('jitaku_area[]', true)">
										<input type="button" value="全ｸﾘｱ" onclick="all_check('jitaku_area[]', false)">
									</div>
<?
$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
checkbox_common($sql, 'jitaku_area', $search->jitaku_area, 5);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">職業</td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
checkbox_common($sql, 'shokugyou', $search->shokugyou, 2);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">チェーン</td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT ch_chain_cd,ch_name FROM m_chain WHERE ch_status=0 ORDER BY ch_order";
checkbox_common($sql, 'chain', $search->chain, 2);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" width="15%">関連調査への参加</td>
								<td width="85%" bgcolor="#ffeecc">
									<input type="checkbox" name="research[]" <?=value_checked('1', $search->research)?>>可能&nbsp;
									<input type="checkbox" name="research[]" <?=value_checked('2', $search->research)?>>不可
								</td>
							</tr>
						</table>

					</td>
				</tr>
			</table>

			<br>
			<input type="submit" value="　人数検索　" onclick="document.form1.next_action.value='search'">
			<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
			<input type="button" value="　戻る　" onclick="location.href='myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
			</form>

<? staff_footer () ?>
