<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

?>
<? marketer_header('My�ѡ��ȥʡ�', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

	<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff"><tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
			<td width="778" align="center" valign="top"> <table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- my�ѡ��ȥʡ� -->
			<form method="post" name="form1">
			<input type="hidden" name="sort_col" value="1">
			<input type="hidden" name="sort_dir" value="1">
			<input type="hidden" name="page" value=0>
			<input type="hidden" name="pset" value=1>
			</form>
			<form method="post" name="form1" action="mp_pjt_update.php" onsubmit="return onsubmit_form1(this)">
			<div align="center">
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
			<tr>
				<td bgcolor="#eff7e8" colspan=2>����ļ�����</td>
			</tr>
	<tr>
		<td  bgcolor="#eeeeee">��ļ�̾</td>
		<td bgcolor="#ffeecc"><input class="kanji" type="text" name="" size=50 maxlength=100 value="�����β�ļ�"></td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">��Ū</td>
		<td bgcolor="#ffeecc"><textarea name="" cols="50" rows="5" class="kanji">��Ū��Ū</textarea></td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">���þ��</td>
		<td bgcolor="#ffeecc">
			<table>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="����������������������������������"></td>
				</tr>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="����������������������������������"></td>
				</tr>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="����������������������������������"></td>
				</tr>
				<tr>
					<td><input type="button" value="���þ����ɲ�"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">�ꥯ�롼�Ⱦ��</td>
		<td bgcolor="#ffeecc">
			<table>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="����������������������������������"></td>
				</tr>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="����������������������������������"></td>
				</tr>
				<tr>
					<td><input class="kanji" type="text" name="" size=50 maxlength=100 value="����������������������������������"></td>
				</tr>
				<tr>
					<td><input type="button" value="�ꥯ�롼�Ⱦ����ɲ�"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">��ļ����ĥݥꥷ��</td>
		<td bgcolor="#ffeecc"><input type="radio"  name="policy" value="1" checked>��̾����Ѥ��롡<input type="radio"  name="policy" value="2">�˥å��͡������Ѥ���</td>
	</tr>
	<tr>
		<td bgcolor="#eeeeee">���С���</td>
		<td bgcolor="#ffeecc"><input class="kanji" type="text" name="" size=5 maxlength=100 value="10">��</td>
	</tr>
	<tr>
		<td bgcolor="#eeeeee">�ꥯ�롼�ȴ���</td>
		<td bgcolor="#ffeecc"><input type="text" name="" value="2004" size="5" maxlength="4">ǯ
						<input type="text" name="" value="12" size="3" maxlength="2">��
						<input type="text" name="" value="1" size="3" maxlength="2">��
						<input type="text" name="" value="18" size="3" maxlength="2">��
						��
						<input type="text" name="" value="2004" size="5" maxlength="4">ǯ
						<input type="text" name="" value="12" size="3" maxlength="2">��
						<input type="text" name="" value="2" size="3" maxlength="2">��
						<input type="text" name="" value="18" size="3" maxlength="2">��</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">������</td>
		<td bgcolor="#ffeecc"><input type="text" name="" value="2004" size="5" maxlength="4">ǯ
						<input type="text" name="" value="12" size="3" maxlength="2">��
						<input type="text" name="" value="3" size="3" maxlength="2">��</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">��ļ��»ܴ���</td>
		<td bgcolor="#ffeecc"><input type="text" name="" value="2004" size="5" maxlength="4">ǯ
						<input type="text" name="" value="12" size="3" maxlength="2">��
						<input type="text" name="" value="4" size="3" maxlength="2">��
						��
						<input type="text" name="" value="2004" size="5" maxlength="4">ǯ
						<input type="text" name="" value="12" size="3" maxlength="2">��
						<input type="text" name="" value="5" size="3" maxlength="2">��</td>
	</tr>
			</table>
			</div>
			<table>
				<tr align="right">
					<td> <input type="button" value="����"><input type="button" value="���"> </td>
				</tr>
			</table>
			</form>
			<!-- my�ѡ��ȥʡ� -->
		</td></tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
