<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��������ѹ� ��񥢥󥱡�������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// �����ͳɽ��
function disp_reason() {
$sql = "select ta_taikai_cd, ta_reason from m_taikai where ta_status=1";
$result = db_exec($sql);
$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	echo "<nobr><input type=\"checkbox\" name=\"reason[]\" value='" . $fetch->ta_taikai_cd . "'>" . $fetch->ta_reason." </nobr>";
	}
}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" width="714">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center">
					<font size="5" color="#ff6633">��񤹤�ˤ����äƤΥ��󥱡��Ȥ򤪴ꤤ���ޤ���</font>
					</td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">
		<!-- name="" �ϡ�������ž�����˻��Ѥ���̾���Τ��� -->
		<form name="form1" method="post" action="out_end.php">
		<div align="left">
			<table border="0">
			<!-- 1���� -->
				<tr>
					<td colspan="3" bgcolor="#006633">
					<div align="center"><font size="4" color="#FFFFFF">EcoFootprint��Eco������Ȥ�Eco����</font><font size="5" color="#FFFF80"><br>
					<b>���󥱡��Ȥˤ����Ϥ���������</b></font></div>
					</td>
				</tr>
				<!-- 2���� -->
				<tr>
					<td bgcolor="#FF9191"><img src="img/spacer.gif" width="1" height="240"></td>
					<td>
					<!-- ����ҥơ��֥볫�� -->
					<div align="left">
						<table border="0" width=100%>
					<!-- �����ȥ�2 -->
							<tr>
								<td align="center" colspan="3" bgcolor="#000000"><font color="#FFFFFF" size=3>�ۡ���ڡ����ˤĤ���</font></td>
							</tr>
							<tr>
								<td align="right" width=60 height=35><font size=2><nobr>�����ۡ�</nobr></font></td>
								<td colspan="2"><font size=2><input type="radio" name="opinion" value="1">�ɤ�����Ƥ���&nbsp;&nbsp;
								<input type="radio" name="opinion" value="2" checked>����&nbsp;&nbsp;
								<input type="radio" name="opinion" value="3">	�����������Ϥʤ�&nbsp;&nbsp;</font></td>
							</tr>
							<tr>
								<td align="right" valign="top"><font size=2><nobr>�����ͳ��</nobr></font></td>
								<td colspan="2"><font size=2><?=disp_reason()?></td>
							</tr>
							<tr>
								<td></td>
								<td align="left" colspan=2><font size=2>�ʤ���ͳ�ˤ��񤭲�������</font></td>
							</tr>
							<tr>
								<td align="right" colspan=3>
								<textarea  name="reason_text" wrap="hard" rows="5" cols="76"></textarea>
								</td>
							</tr>
						</table>
						</div>
						<!-- ����ҥơ��֥뽪λ -->
						</td>
					<td bgcolor="#FF9191"><img src="img/spacer.gif" width="1" height="240"></td>
				</tr>
			<!-- 3���� -->
				<tr>
					<td colspan="3" bgcolor="#006633" align="center"><br><input name="submit" type="submit" value="��������">
					<input name="reset" type="reset" value="��ľ��">
					<input type="button" value=" ��� " onclick="location.href='member_menu.php'">
					<br>
				��</td>
				</tr>
			</table>
		</div>
		</form>
		<hr width="95%" size="1"></td>

		</tr>
		<tr>
		<td width="35" height="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center"><img src="img/spacer.gif" width="644" height="35"></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</body>
</html>