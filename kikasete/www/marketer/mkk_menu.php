						<table width="150" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<table width="150" border="0" cellspacing="0" cellpadding="1" bgcolor="#666699">
										<tr>
											<td align="center"><img src="images/mku_mymenu.gif" width="140" height="15" border="0"></a></td>
										</tr>
										<tr>
											<td>
												<table width="148" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
<?
if (check_permission(PMR_MY_ENQUETE)) {
?>
													<tr>
														<td>□　<a href="mkk_myenq.php">Ｍｙアンケート</a></td>
													</tr>
<?
}
if (check_permission(PMR_MY_MONITOR)) {
?>
													<tr>
														<td>□　<a href="mkk_mymonitor.php">Ｍｙモニター</a></td>
													</tr>
<?
}
if (check_permission(PMR_MY_SEARCH)) {
?>
													<tr>
														<td>□　<a href="mkk_mysearch.php">Ｍｙサーチ</a></td>
													</tr>
<?
}
if (check_permission(PMR_MY_AGENT)) {
?>
													<tr>
														<td>□　<a href="mkk_myagent.php">Ｍｙエージェント</a></td>
													</tr>
<?
}
if (check_permission(PMR_ENQ_RESULT)) {
?>
													<tr>
														<td>□　<a href="mkk_enq.php">アンケート結果</a></td>
													</tr>
<?
}
if (check_permission(PMR_REGIST_INF)) {
?>
													<tr>
														<td>□　<a href="mkk_update.php">登録情報の更新</a></td>
													</tr>
<?
}
if (check_permission(PMR_ASSISTANT)) {
?>
													<tr>
														<td>□　<a href="mkk_assistant.php">アシスタントの設定</a></td>
													</tr>
<?
}
if (check_permission(PMR_CHG_AGENT)) {
?>
													<tr>
														<td>□　<a href="mkk_achange.php">エージェントの変更</a></td>
													</tr>
<?
}
?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<br>
						<table width="150" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<table width="150" border="0" cellspacing="0" cellpadding="1" bgcolor="#666699">
										<tr>
											<td align="center"><img src="images/useperiod.gif" width="140" height="15" border="0"></td>
										</tr>
										<tr>
											<td>
												<table width="148" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
													<tr>
<?
// 登録日取得
$sql = "SELECT mr_regist_date FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$start_y = get_datepart('Y', $fetch[0]);
	$start_m = get_datepart('M', $fetch[0]);
}
$end_y = date('Y');
$end_m = (int)date('m');
?>
														<td align="center"><?=$start_y?>年<?=$start_m?>月～<?=$end_y?>年<?=$end_m?>月</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>