<?
function marketer_list($top, $sort_col, $sort_dir) {
	global $agent_id;

	$sub1 = "SELECT MAX(co_regist_date) FROM t_communication WHERE co_direction=2 AND co_marketer_id=mr_marketer_id";

	$order_by = order_by(1, 0, 'mr_regist_date','mr_name1_kana||mr_name2_kana', 'mr_kinmu_name', 'co_regist_date', 'lg_date');
	$sql = "SELECT mr_marketer_id,mr_name1,mr_name2,mr_kinmu_name,($sub1) AS co_regist_date,mll_last_login_date AS lg_date,mr_agent_id"
			. " FROM t_marketer"
			. " LEFT JOIN t_marketer_last_login ON mll_marketer_id=mr_marketer_id"
			. " WHERE mr_agent_id=$agent_id AND mr_status=0 $order_by";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
?>
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1>
	<tr>
		<td>■担当マーケター一覧</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>
<table <?=LIST_TABLE?> width=95% class="small">
	<tr class="tch">
<?
		sort_header(1, 'ID');
		sort_header(2, '氏名');
		sort_header(3, '企業名');
		sort_header(4, 'ﾒｯｾｰｼﾞ送信最終日時');
		sort_header(5, 'ﾛｸﾞｲﾝ最終日時');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->mr_marketer_id?></td>
		<td align="left"><a href='JavaScript:mr_detail("<?= $fetch->mr_marketer_id ?>","<?=$top?>")' title="登録情報の詳細を表示します"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td align="left"><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td align="center">
<?
	if ($fetch->co_regist_date) {
?>
			<a href="<?=$top?>/marketer/list/personal_list.php?id=<?=$fetch->mr_marketer_id?>" title="メッセージ送信履歴を表示します"><?=format_datetime($fetch->co_regist_date)?></a>
<?
	} else { echo "-"; }
?>
		</td>
		<td align="center">
<?
	if ($fetch->lg_date) {
?>
						<a href="<?=$top?>/marketer/list/login_log.php?id=<?=$fetch->mr_marketer_id?>" title="ログイン履歴を表示します"><?=format_datetime($fetch->lg_date)?></a>
<?
	} else { echo "-"; }
?>
		</td>
	</tr>
<?
	}
?>
</table>
<?
}
?>