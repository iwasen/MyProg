<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/reginfo.php');
include('../inc/sel_profile2.php');

//=== �ᥤ����� ===
session_start();
$reginfo = new CRegInfo;
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>�Ŀ;��󡦤褯�Ԥ���Ź����Ͽ</title>
<SCRIPT LANGUAGE=javascript>
<!--
function ZipKensaku(pfc) {
	open("../10/10-1.php?pfc=" + pfc);
}
//-->
</SCRIPT>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="52">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="�Ϥ��᡼�롦net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form method="post" action="personal_check.php" name="form1">
		<br>
		<table width="641" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="24">
				<td height="24" width="50" valign="top"></td>
				<td height="24"><img height="26" width="58" src="img/step2.gif" alt="step2">
					<hr>
					<br>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36" align="left" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td height="36">
					<div align="left">
						<table width="569" border="0" cellspacing="2" cellpadding="2">
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">�᡼�륢�ɥ쥹<?ErrMark($reginfo->err_mail_adr || $reginfo->err_already_regist)?></font></td>
								<td colspan="2" align="left" valign="middle"><input type="text" name="mail_adr" size="30" maxlength=50 value="<?=$reginfo->mail_adr?>"> <font size="2">��Ⱦ�ѱѿ���</font></td>
							</tr>
							<tr>
								<td width="140">��</td>
								<td colspan="2" align="left" valign="middle"><input type="text" name="mail_adr2" size="30" maxlength=50 value="<?=$reginfo->mail_adr2?>"> <font size="2">�ʳ�ǧ�Τ���2�����Ϥ��Ƥ���������</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">�ѥ����<?ErrMark($reginfo->err_password)?></font></td>
								<td colspan="2" align="left" valign="middle"><input type="password" name="password" size="18" maxlength=14 value="<?=$reginfo->password?>"> <font size="2">��Ⱦ�ѱѿ�6ʸ���ʾ�14ʸ�������</font></td>
							</tr>
							<tr>
								<td width="140">��</td>
								<td colspan="2" align="left" valign="middle"><input type="password" name="password2" size="18" maxlength=14 value="<?=$reginfo->password2?>"> <font size="2">�ʳ�ǧ�Τ���2�����Ϥ��Ƥ���������</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">����<?ErrMark($reginfo->err_seibetsu)?></font></td>
								<td width="170" bgcolor="white" align="left" valign="middle"><input type="radio" name="seibetsu" value="0"<?=RadioChecked($reginfo->seibetsu, "0")?>> <font size="2">����</font>���� <input type="radio" name="seibetsu" value="1"<?=RadioChecked($reginfo->seibetsu, "1")?>> <font size="2">����</font> </td>
								<td width="268">��</td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">̤����<?ErrMark($reginfo->err_mikikon)?></font></td>
								<td width="170" bgcolor="white" align="left" valign="middle"><input type="radio" name="mikikon" value="0"<?=RadioChecked($reginfo->mikikon, "0")?>> <font size="2">�ȿ�</font>���� <input type="radio" name="mikikon" value="1"<?=RadioChecked($reginfo->mikikon, "1")?>> <font size="2">����</font> </td>
								<td width="268">��</td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">��ǯ����<?ErrMark($reginfo->err_seinengappi)?></font></td>
								<td colspan="2"><font size="2">����</font> <select name="sei_nen" size=1>
										<option></option>
<?
WriteYear($reginfo->sei_nen);
?>
									</select> <font size="2">ǯ</font> �� <select name="sei_getsu" size="1">
<?
WriteMonth($reginfo->sei_getsu);
?>
									</select> <font size="2">��</font> �� <select name="sei_hi" size="1">
<?
WriteDay($reginfo->sei_hi);
?>
									</select> <font size="2">��</font><br>
									<font size="2">�� �Ϥ��᡼��.net �ϣ����Ͱʾ��������Ͽ�Ǥ��ޤ���</font>

</td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle" rowspan=2><font size="2">�ｻ��͹���ֹ�<?ErrMark($reginfo->err_zip_kyojuu)?></font></td>
								<td width="170" align="left" valign="middle"><input type="text" name="zip_kyojuu1" size="3" maxlength=3 value="<?=$reginfo->zip_kyojuu1?>"> - <input type="text" name="zip_kyojuu2" size="4" maxlength=4 value="<?=$reginfo->zip_kyojuu2?>"> <font size="2">��Ⱦ�ѿ�����</font> �� </td>
								<td width="268"><font size="2"><a href="JavaScript:ZipKensaku('1')">͹���ֹ椬ʬ����ʤ���</a></font></td>
							</tr>
							<tr>
								<td colspan=2 valign="top"><font color="#ff0000" size="2">�����߳����ˤ����ޤ������ϡ���000��0000�פ����Ϥ��Ƥ���������</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle" rowspan=2><font size="2">��̳��͹���ֹ�</font></td>
								<td width="170" align="left" valign="middle"><input type="text" name="zip_kinmu1" size="3" maxlength=3 value="<?=$reginfo->zip_kinmu1?>"> - <input type="text" name="zip_kinmu2" size="4" maxlength=4 value="<?=$reginfo->zip_kinmu2?>"> <font size="2">��Ⱦ�ѿ�����</font> �� �� </td>
								<td width="268"><font size="2"><a href="JavaScript:ZipKensaku('2')">͹���ֹ椬ʬ����ʤ���</a></font></td>
							</tr>
							<tr>
								<td colspan=2 valign="top"><font color="#ff0000" size="2">�����߳����Ƕ�̳����Ƥ������ϡ���000��0000�פ����Ϥ��Ƥ���������</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">����<?ErrMark($reginfo->err_shokugyou)?></font></td>
								<td colspan="2" align="left" valign="middle"><select name="shokugyou" size="1">
										<option value=""<?=Selected($reginfo->shokugyou, "")?>>�����Ӥ�������</option>
<?
WriteShokugyou($reginfo->shokugyou);
?>
									</select> </td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">HTML�᡼���������<?ErrMark($reginfo->err_html_mail)?></font></td>
								<td colspan="2" align="left" valign="middle"><input type="radio" name="html_mail" value="1"<?=RadioChecked($reginfo->html_mail, "1")?>> <font size="2">��˾����</font> ���� <input type="radio" name="html_mail" value="0"<?=RadioChecked($reginfo->html_mail, "0")?>> <font size="2">��˾���ʤ�</font> </td>
							</tr>
						</table><br>
						<br>
						<p>��</div>
				</td>
			</tr>
			<tr height="36">
				<td colspan="2" height="36"><table width="603" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"></td>
							<td width="234" align="left" valign="top"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="225">
									<tr>
										<td bgcolor="#b2cfef"><font size="2">�褯�Ԥ�����ӥ�</font></td>
									</tr>
								</table></font></td>
							<td width="312" align="left" valign="top"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="225">
									<tr>
										<td bgcolor="#b2cfef"><font size="2">�褯�Ԥ������ѡ�</font></td>
									</tr>
								</table></font></td>
						</tr>
						<tr>
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td width="234"><font size="2">���ʤ��Τ褯�Ԥ�����ӥˤ�<br>
								�����ĤǤ�����å����Ƥ���������</font></td>
							<td width="312"><font size="2">���ʤ��Τ褯�Ԥ������ѡ���<br>
								�����ĤǤ�����å����Ƥ�������</font> </td>
						</tr>
						<tr>
							<td width="45"></td>
							<td width="234"><table width="225" border="0" cellspacing="0" cellpadding="0">
<?
WriteYokuikuMise("01", $reginfo->yokuiku_mise);
?>
								</table></td>
							<td width="312"><table width="225" border="0" cellspacing="0" cellpadding="0">
<?
WriteYokuikuMise("02", $reginfo->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table></td>
			</tr>
			<tr height="36">
				<td colspan="2" height="36"><table width="604" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"></td>
							<td width="234">��</td>
							<td width="313">��</td>
						</tr>
						<tr align="left" valign="bottom">
							<td width="45"></td>
							<td colspan="2" align="left" valign="top"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="465">
									<tr>
										<td bgcolor="#b2cfef"><font size="2">����¾�Τ褯�Ԥ���Ź</font></td>
									</tr>
								</table></font></td>
						</tr>
						<tr align="left" valign="bottom">
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td width="234" align="left" valign="top"><font size="2">���ʤ����褯���Ѥ�����֤䥵���ӥ���<br>
								�����ĤǤ�����å����Ƥ���������</font></td>
							<td width="313"><font size="2">���ʤ����褯���Ѥ��뤪Ź�䥵���ӥ���<br>
								�����ĤǤ�����å����Ƥ���������</font></td>
						</tr>
						<tr>
							<td width="45"></td>
							<td width="234" align="left" valign="top"><table width="225" border="0" cellspacing="0" height="220" cellpadding="0">
<?
WriteYokuikuMise("03", $reginfo->yokuiku_mise);
?>
								</table></td>
							<td width="313" valign="top" align="left"><table width="225" border="0" cellspacing="0" height="312">
<?
WriteYokuikuMise("04", $reginfo->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table></td>
			</tr>
			<tr height="36">
				<td colspan="2" height="36"><br>
					<br>
					<table width="606" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td colspan="2"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="468">
									<tr>
										<td bgcolor="#b2cfef"><font size="2">���ʤ��Ϥɤä��ɡ�<br>
											</font></td>
									</tr>
								</table>���ʤ��Ϥɤä��ɤǤ��������ֶᤤ��Τ򣱤�����Ǥ�������</font><br>
							</td>
						</tr>
						<tr>
							<td width="45"></td>
							<td colspan="2"><table border="0" cellspacing="0">
<?
WriteDocchiHa($reginfo->docchiha);
?>
								</table></td>
						</tr>
					</table><br>
					<br>
					<table width="520" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td rowspan="2" width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td colspan="2"><font size="2">�֤Ϥ��᡼�롦net�פؤ���Ͽ�ϰʾ�Ǥ��� <br>
								���ơ��֤Ϥ��᡼�롦net�פ򱿱Ĥ��������ҡ������������ϡ� <br>
								��ȯ����������ԡפ���礹�뤪�Τ餻���᡼��Ǥ��Ϥ����褦�ȻפäƤ��ޤ���<br>
								���������ˤʤ�ޤ�����<?ErrMark($reginfo->err_oshirase_mail)?></font></td>
						</tr>
						<tr height="55">
							<td width="97" height="55"></td>
							<td width="366" height="55"><input type="radio" name="oshirase_mail" value="1"<?=RadioChecked($reginfo->oshirase_mail, "1")?>> <font size="2">�Ϥ�</font> ��<input type="radio" name="oshirase_mail" value="0"<?=RadioChecked($reginfo->oshirase_mail, "0")?>> <font size="2">������</font> </td>
						</tr>
					</table>
        <br>
        <table width="372" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="45"><img height="16" width="45" src="../img/space.gif"></td>
            <td>
			<!--�ץ쥼��ȥ����ڡ���
			<font size="2" color="#cc0033"><b>
              <table border="1" cellpadding="5" cellspacing="0" width="596" bgcolor="#fff6ea">
                <tr>
                  <td>
                    <font size="2" color="#cc0033"><b>���ץ�󥰥����ڡ���»���</b><br>
                      </font><font size="2"><br>
                      �֤Ϥ��᡼�롦net�פǤϡ�2002ǯ3��18������2002ǯ5��31���ޤǤ˥��С���Ͽ���줿����<br>
                      ���ץ�󥰥����ڡ����»ܤ��Ƥ��ޤ���<a href="../present/index.html">�;ܤ����Ϥ�����</a><br>
                      ����</font><font size="2" color="#ff9b2f">XBOX</font><font size="2">�סʣ�̾��<br>
                      ����</font><font size="2" color="#ff9b2f">XBOX special edition</font><font size="2">�סʣ�̾��<br>
                      ����</font><font size="2" color="#ff9b2f">����ǥ����ˡ������ڥ������å�</font><font size="2">�סʣ��ȣ�̾��<br>
                      ����</font><font size="2" color="#ff9b2f">����������1000��ʬ</font><font size="2">�סʣ���̾��<br>
                      </font><font size="2">��������������ޤ���<br>
                    ���ץ�󥰥����ڡ���ˤ����礵������ϡ�����˾�ξ��ʤ�����Ǥ��������� </font> <font size="2">
                    <hr width="100%">
                    </font>
                    <table width="500" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                          <input type="radio" value="1" name="present"<?=RadioChecked($_SESSION['present'], '1')?>>
                        </td>
                        <td><font size="2">��</font><font size="2" color="#ff9b2f">XBOX</font><font size="2">��</font></td>
                        <td>
                          <input type="radio" value="2" name="present"<?=RadioChecked($_SESSION['present'], '2')?>>
                        </td>
                        <td><font size="2">��</font><font size="2" color="#ff9b2f">XBOX
                          special edition</font><font size="2">��</font></td>
                      </tr>
                      <tr>
                        <td>
                          <input type="radio" value="3" name="present"<?=RadioChecked($_SESSION['present'], '3')?>>
                        </td>
                        <td><font size="2">��</font><font size="2" color="#ff9b2f">����ǥ����ˡ������ڥ������å�</font><font size="2">��</font></td>
                        <td>
                          <input type="radio" value="4" name="present"<?=RadioChecked($_SESSION['present'], '4')?>>
                        </td>
                        <td><font size="2">��</font><font size="2" color="#ff9b2f">����������1000��ʬ</font><font size="2">��</font></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              </b></font> 
			  �ץ쥼��ȥ����ڡ���-->
			  </td>
          </tr>
          <tr>
            <td width="45"></td>
            <td></td>
          </tr>
        </table>
        <br>
					<table width="520" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"></td>
							<td colspan="2"><font size="2">����Ͽ�Ͻ�λ�Ǥ����ʾ�Ǥ������С���ǧ�ܥ���򲡤��Ƥ���������</font><br>
								<br>
<!-- 2000/12/01 T.Iyonaga/xx xxxxx ������������ -->
<!--
								<input type="submit" value="���ᡡ�롡" name="btn_back"> �� <input type="submit" value="���Ρ�ǧ��" name="btn_kakunin"> <br>
-->
								<input type="submit" value="���Ρ�ǧ��" name="btn_kakunin">��<input type="submit" value="���ᡡ�롡" name="btn_back"><br>
<!-- ���������ޤ� -->
								<br>
							</td>
						</tr>
					</table></td>
			</tr>
		</table>
		</form>
	</body>

</html>
