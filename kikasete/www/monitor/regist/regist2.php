<?
$top = './..';
$inc = "$top/../inc";
include("$inc/select.php");
$img = "$top/image";
$inc = "$top/inc";
include("$inc/mn_header.php");
include("$inc/mn_regist.php");


// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	$_SESSION['ss_regist'] = new monitor_regist;
$reg = &$_SESSION['ss_regist'];
?>
<? monitor_menu() ?>

<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
<form name="form1" method="post" action="regist2_check.php">
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_reg.gif" width=590 height=30 border=0 alt="��˥�����Ͽ"></TD>
		<TD align="right" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_bk.gif" width=10 height=30 border=0></TD>
	</TR>
</TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD align="left"><IMG src="../image/reg2_m.gif" width=600 height=36 border=0 alt="���ܾ������Ͽ"></TD>
</TR>
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD colspan="2">

	<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left">
			<SPAN class=fs12_lh120><font color="#666666"><B>�ޤ��ϡ����ܾ���ʤ�̾���ʤɡˤ���Ͽ���Ƥ���������</B><BR><BR>���󥱡��Ȱ���Υ᡼��䡢���ʷ��Τ��Ϥʤɡ�������Ͽ���Ƥ�������������Ȥˤ��Ϥ��������ޤ���<BR>���ְ㤤�Τʤ��褦�ˤ�����Ĥ�����������<BR>��<font color="#CC0000">��</font>�� �Τ�����ܤ�ɬ�ܹ��ܤȤʤ�ޤ��Τǡ�ɬ������������������<BR><BR></font></span>
		</TD>
	</TR>
	<TR>
		<TD align="left">
			<table border="0" cellpadding="0" cellspacing="0" width="600">
				<tr><td bgcolor="#999999">
					<table border="0" cellpadding="4" cellspacing="1" width="100%">
							<tr>
								<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120>��̾���ʴ�����</span></td>
								<td width="141" bgcolor="#FFFFFF"><SPAN class=fs12_lh120>����<input type="text" name="name1" size="13" maxlength="25" <?=value($reg->name1)?>></SPAN></td>
								<td bgcolor="#FFFFFF"><SPAN class=fs12_lh120>̾��</SPAN><input type="text" name="name2" size="13" maxlength="25" <?=value($reg->name2)?>></td>
							</tr>
							<tr>
								<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> ��̾���ʥեꥬ�ʡ�</SPAN></td>
								<td width="141" bgcolor="#FFFFFF"><SPAN class=fs12_lh120>������<input type="text" name="name1_kana" size="13" maxlength="25" <?=value($reg->name1_kana)?>></SPAN></td>
								<td bgcolor="#FFFFFF"><SPAN class=fs12_lh120>�ᥤ��<input type="text" name="name2_kana" size="13" maxlength="25" <?=value($reg->name2_kana)?>></SPAN></td>
							</tr>
							<tr>
								<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> ��ǯ����</SPAN></td>
								<td colspan="2" bgcolor="#FFFFFF">
								<SPAN class=fs12_lh120>
								<input type="text" name="birthday_y" size="4" maxlength="4" <?=value($reg->birthday_y)?>>ǯ��
													<input type="text" name="birthday_m" size="2" maxlength="2" <?=value($reg->birthday_m)?>>�
													<input type="text" name="birthday_d" size="2" maxlength="2" <?=value($reg->birthday_d)?>>����Ⱦ�ѿ�����
													<br>��� 1974ǯ 01�� 06��
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> ̤����</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2">
												<SPAN class=fs12_lh120>
													<input type="radio" name="mikikon" <?=value_checked('1', $reg->mikikon)?>>̤����
													<input type="radio" name="mikikon" <?=value_checked('2', $reg->mikikon)?>>������
													<input type="radio" name="mikikon" <?=value_checked('3', $reg->mikikon)?>>����¾
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> ����</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2">
												<SPAN class=fs12_lh120>
													<input type="radio" name="sex" <?=value_checked('1', $reg->sex)?>>������
													<input type="radio" name="sex" <?=value_checked('2', $reg->sex)?>>����
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> �᡼�륢�ɥ쥹</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2"><SPAN class=fs12_lh120><input type="text" name="mail_addr" size="39" maxlength="100" <?=value($reg->mail_addr)?>><br>���ӥ᡼�륢�ɥ쥹�ǤΤ���Ͽ�Ϥ���θ����������<br>���󥱡��Ȥˤ�����������>�����Ȥ��Ǥ��ޤ���</SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> �᡼�륢�ɥ쥹<BR>&nbsp;&nbsp;&nbsp;&nbsp;��ǧ</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="text" name="mail_addr2" size="39" maxlength="100" <?=value($reg->mail_addr2)?>><br>�ʥ��ԡ��Ϥ��ʤ��ǲ�������</SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> �ѥ����</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="password" name="password" size="14" <?=value($reg->password)?>>��Ⱦ�ѱѿ���6��20ʸ���ޤǡ�</SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> �ѥ���ɳ�ǧ</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="password" name="password2" size="14" <?=value($reg->password2)?>>��Ⱦ�ѱѿ���6��20ʸ���ޤǡ�</SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> HTML�᡼�����</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2">
												<SPAN class=fs12_lh120>
													<input type="radio" name="html_mail" <?=value_checked(DBTRUE, $reg->html_mail)?>>�������롡
													<input type="radio" name="html_mail" <?=value_checked(DBFALSE, $reg->html_mail)?>>�������ʤ�
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> �Ϥ��᡼�����</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2">
												<SPAN class=fs12_lh120>
													<input type="radio" name="haimail_flag" <?=value_checked(DBTRUE, $reg->haimail_flag)?>>�������롡
													<input type="radio" name="haimail_flag" <?=value_checked(DBFALSE, $reg->haimail_flag)?>>�������ʤ�
													<br>�Ϥ��᡼���������<a href="../haimail/haimail.php" target="_blank">������</a>
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> ͹���ֹ�ʼ����</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="text" name="jitaku_zip1" size="3" maxlength="3" <?=value($reg->jitaku_zip1)?>>-<input type="text" name="jitaku_zip2" size="4" maxlength="4" <?=value($reg->jitaku_zip2)?>>��Ⱦ�ѿ����ˡ�<a href="<?=$top?>/../common/zip_search.php?pfc=1" target="zip_search">͹���ֹ椬ʬ����ʤ���</a></SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> �ｻ�ϰ�ʼ����</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="jitaku_area" size="1"><?select_area('�����Ӳ���������������', $reg->jitaku_area)?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> ����</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="shokugyou_cd" size="1"><?select_shokugyou('�����Ӳ���������������', $reg->shokugyou_cd)?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> �ȼ�</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="gyoushu_cd" size="1"><?select_gyoushu('�����Ӳ���������������', $reg->gyoushu_cd, 1)?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">��</font></SPAN><SPAN class=fs12_lh120> ����</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="shokushu_cd" size="1"><?select_shokushu('�����Ӳ���������������', $reg->shokushu_cd)?></select>
											</td>
										</tr>
<tr>                                                                                        <td colspan="3"><SPAN class=fs12_lh120><font color="#FFFFFF">���̶С�
�̳���&nbsp;&nbsp;&nbsp;���̶С��̳���Τ������Ϥ���������������</SPAN></SPAN></td>
                                                                                </tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120> ͹���ֹ�</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="text" name="kinmu_zip1" size="3" maxlength="3" <?=value($reg->kinmu_zip1)?>>-<input type="text" name="kinmu_zip2" size="4" maxlength="4" <?=value($reg->kinmu_zip2)?>>��Ⱦ�ѿ����ˡ�<a href="<?=$top?>/../common/zip_search.php?pfc=2" target="zip_search">͹���ֹ椬ʬ����ʤ���</a></SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120> �̶С��̳����ϰ�</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="kinmu_area" size="1"><?select_area('�����Ӳ���������������', $reg->kinmu_area)?></select><BR>
<SPAN class=fs12_lh120>���̶С��̳���Τ������Ϥ���������������</SPAN>
											</td>
										</tr>
										<tr>
											<td colspan="3" align="center" bgcolor="#FFFFFF">
										<input type="submit" value="�������ء�">
										<input type="reset" value="�����ꥢ��">
											</td>
										</tr>
									</table>

										</td>
										</tr>
									</table>

	</TD>
	</TR>
</SPAN></SPAN>
	</TBODY>
	</TABLE>
<DIV align="left"><img height="75" width="600" src="../image/reg_flow02.gif" border="0" alt="���ܾ��������" vspace="15"></DIV>
</TD>
</TR>
</TBODY>
</TABLE>
</form>

<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
