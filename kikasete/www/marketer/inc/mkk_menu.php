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
														<td><font size="3" class="honbun1">���@</font><a href="mkk_myenq.php"><font size="3" class="honbun1">�l���A���P�[�g</font></a></td>
													</tr>
<?
}
if (check_permission(PMR_MY_MONITOR)) {
?>
													<tr>
														<td><font size="3" class="honbun1">���@</font><a href="mkk_mymonitor.php"><font size="3" class="honbun1">�l�����j�^�[</font></a></td>
													</tr>
<?
}
if (check_permission(PMR_MY_SEARCH)) {
?>
													<tr>
														<td><font size="3" class="honbun1">���@</font><a href="mkk_mysearch.php"><font size="3" class="honbun1">�l���T�[�`</font></a></td>
													</tr>
<?
}
if (check_permission(PMR_MY_AGENT)) {
?>
													<tr>
														<td><font size="3" class="honbun1">���@</font><a href="mkk_myagent.php"><font size="3" class="honbun1">�l���G�[�W�F���g</font></a></td>
													</tr>
<?
}
if (check_permission(PMR_ENQ_RESULT)) {
?>
													<tr>
														<td><font size="3" class="honbun1">���@</font><a href="mkk_enq.php"><font size="3" class="honbun1">�A���P�[�g����</font></a></td>
													</tr>
<?
}
if (check_permission(PMR_REGIST_INF)) {
?>
													<tr>
														<td><font size="3" class="honbun1">���@</font><a href="mkk_update.php"><font size="3" class="honbun1">�o�^���̍X�V</font></a></td>
													</tr>
<?
}
if (check_permission(PMR_ASSISTANT)) {
?>
													<tr>
														<td><font size="3" class="honbun1">���@</font><a href="mkk_assistant.php"><font size="3" class="honbun1">�A�V�X�^���g�̐ݒ�</font></a></td>
													</tr>
<?
}
if (check_permission(PMR_CHG_AGENT)) {
?>
													<tr>
														<td><font size="3" class="honbun1">���@</font><a href="mkk_achange.php"><font size="3" class="honbun1">�G�[�W�F���g�̕ύX</font></a></td>
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
// �o�^���擾
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
														<td align="center"><?=$start_y?>�N<?=$start_m?>���`<?=$end_y?>�N<?=$end_m?>��</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>