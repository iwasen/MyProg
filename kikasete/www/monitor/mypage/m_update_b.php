<?
$top = './..';
$inc = "$top/../inc";
include("$inc/select.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// ���å�������
if (!isset($_SESSION['ss_regist'])) {
	$_SESSION['ss_regist'] = new monitor_regist;
	$reg = &$_SESSION['ss_regist'];
	$reg->read_db_b($_SESSION['ss_monitor_id']);
	$reg->read_db_l($_SESSION['ss_monitor_id']);
} else
	$reg = &$_SESSION['ss_regist'];
?>
<? monitor_menu() ?>

<form name="form1" method="post" action="m_update_b_check.php">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>�����ץ�ե�����</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">��</td>
		<td colspan="3"><table width="481" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">����</font><font color="white">���ܾ���ι���</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br>
									</b></font><font size="2"><br>
									��</font><font size="2" color="#ff0009">��</font><font size="2">�� �Τ�����ܤ�ɬ�ܹ��ܤȤʤ�ޤ��Τǡ�ɬ������������������<br>
									</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2">
												<hr>
												</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> ��̾���ʴ�����</font></td>
											<td width="141"><font size="2">����<input type="text" name="name1" size="13" maxlength="25" <?=value($reg->name1)?>></font></td>
											<td><font size="2">̾��</font><input type="text" name="name2" size="13" maxlength="25" <?=value($reg->name2)?>></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> ��̾���ʥեꥬ�ʡ�</font></td>
											<td width="141"><font size="2">������<input type="text" name="name1_kana" size="13" maxlength="25" <?=value($reg->name1_kana)?>></font></td>
											<td><font size="2">�ᥤ��<input type="text" name="name2_kana" size="13" maxlength="25" <?=value($reg->name2_kana)?>></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> ��ǯ����</font></td>
											<td colspan="2">
												<font size="2">
													<input type="text" name="birthday_y" size="4" maxlength="4" <?=value($reg->birthday_y)?>>ǯ��
													<input type="text" name="birthday_m" size="2" maxlength="2" <?=value($reg->birthday_m)?>>�
													<input type="text" name="birthday_d" size="2" maxlength="2" <?=value($reg->birthday_d)?>>����Ⱦ�ѿ�����
													<br>��� 1974ǯ 01�� 06��
												</font>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> ̤����</font></td>
											<td colspan="2">
												<font size="2">
													<input type="radio" name="mikikon" <?=value_checked('1', $reg->mikikon)?>>̤����
													<input type="radio" name="mikikon" <?=value_checked('2', $reg->mikikon)?>>������
													<input type="radio" name="mikikon" <?=value_checked('3', $reg->mikikon)?>>����¾
												</font>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> ����</font></td>
											<td colspan="2">
												<font size="2">
													<input type="radio" name="sex" <?=value_checked('1', $reg->sex)?>>������
													<input type="radio" name="sex" <?=value_checked('2', $reg->sex)?>>����
												</font>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> �᡼�륢�ɥ쥹</font></td>
											<td colspan="2"><font size="2"><input type="text" name="mail_addr" size="39" maxlength="100" <?=value($reg->mail_addr)?>><br>���ӥ᡼�륢�ɥ쥹�ǤΤ���Ͽ�Ϥ���θ����������<br>���󥱡��Ȥˤ����������������Ȥ��Ǥ��ޤ���</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> �᡼�륢�ɥ쥹��ǧ</font></td>
											<td colspan="2"><font size="2"><input type="text" name="mail_addr2" size="39" maxlength="100" <?=value($reg->mail_addr2)?>><br>�ʥ��ԡ��Ϥ��ʤ��ǲ�������</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> �ѥ����</font></td>
											<td colspan="2"><font size="2"><input type="password" name="password" size="14" <?=value($reg->password)?>>��Ⱦ�ѱѿ���6��20ʸ���ޤǡ�</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> �ѥ���ɳ�ǧ</font></td>
											<td colspan="2"><font size="2"><input type="password" name="password2" size="14" <?=value($reg->password2)?>>��Ⱦ�ѱѿ���6��20ʸ���ޤǡ�</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> HTML�᡼�����</font></td>
											<td colspan="2">
												<font size="2">
													<input type="radio" name="html_mail" <?=value_checked(DBTRUE, $reg->html_mail)?>>�������롡
													<input type="radio" name="html_mail" <?=value_checked(DBFALSE, $reg->html_mail)?>>�������ʤ�
												</font>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> �Ϥ��᡼�����</font></td>
											<td colspan="2">
												<font size="2">
													<input type="radio" name="haimail_flag" <?=value_checked(DBTRUE, $reg->haimail_flag)?>>�������롡
													<input type="radio" name="haimail_flag" <?=value_checked(DBFALSE, $reg->haimail_flag)?>>�������ʤ�
													<br>�Ϥ��᡼���������<a href="../haimail/haimail.php" target="_blank">������</a>
												</font>
											</td>
										</tr>
										<tr>
											<td colspan="3"><hr></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> ͹���ֹ�ʼ����</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_zip1" size="3" maxlength="3" <?=value($reg->jitaku_zip1)?>>-<input type="text" name="jitaku_zip2" size="4" maxlength="4" <?=value($reg->jitaku_zip2)?>>��Ⱦ�ѿ�����</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> �ｻ�ϰ�ʼ����</font></td>
											<td colspan="2">
												<select name="jitaku_area" size="1"><? select_area('�����Ӳ���������������', $reg->jitaku_area) ?></select>
											</td>
										</tr>
										<tr>
											<td colspan="3"><hr></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> ����</font></td>
											<td colspan="2">
												<select name="shokugyou_cd" size="1"><? select_shokugyou('�����Ӳ���������������', $reg->shokugyou_cd) ?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> �ȼ�</font></td>
											<td colspan="2">
												<select name="gyoushu_cd" size="1"><? select_gyoushu('�����Ӳ���������������', $reg->gyoushu_cd, 1) ?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> ����</font></td>
											<td colspan="2">
												<select name="shokushu_cd" size="1"><? select_shokushu('�����Ӳ���������������', $reg->shokushu_cd) ?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2"> ͹���ֹ�</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_zip1" size="3" maxlength="3" <?=value($reg->kinmu_zip1)?>>-<input type="text" name="kinmu_zip2" size="4" maxlength="4" <?=value($reg->kinmu_zip2)?>>��Ⱦ�ѿ�����</font></td>
										</tr>
										<tr>
											<td width="119"><font size="2">���̶С��̳����</font></td>
											<td colspan="2"><font size="2">���̶С��̳���Τ������Ϥ���������������</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2"> �̶С��̳����ϰ�</font></td>
											<td colspan="2">
												<select name="kinmu_area" size="1"><? select_area('�����Ӳ���������������', $reg->kinmu_area) ?></select>
											</td>
										</tr>
										<tr>
											<td width="119"></td>
											<td colspan="2"><font size="2">���̶С��̳���Τ������Ϥ���������������</font></td>
										</tr>
									</table></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
											<input type="submit" value="���Ρ�ǧ��">
											<input type="reset" value="�������᤹��">
										</font></center>
								</td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
</form>
</body>
</html>
