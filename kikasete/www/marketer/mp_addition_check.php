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
			<!-- my�ѡ��ȥʡ� -->

			<p>
			<table border=0 cellspacing=0 cellpadding=1 width="100%">
				<tr>
					<td>���ۥ��ۥ��˴ؤ����ļ�</td>
					<td align="right">
						<input type="button" value="����롡" onclick="location.href='../menu.php'">
					</td>
				</tr>
			</table>
			</p>

			<div align="center">
			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="80%" class="small">
				<tr bgcolor="#292864">
					<td width="20%" align="center">&nbsp;</td>
					<td width="10%" align="center"><span style="color:#ffffff;">ȯ����</span></td>
					<td width="10%" align="center"><span style="color:#ffffff;">�ֿ�����Ψ��</span></td>
					<td width="10%" align="center"><span style="color:#ffffff;">��������Ψ��</span></td>
					<td width="10%" align="center"><span style="color:#ffffff;">��ɸ��</span></td>
					<td width="10%" align="center"><span style="color:#ffffff;">��­��</span></td>
				</tr>
				<tr class="tc0" align="center">
					<td class="tch" align="center">���ߤξ���</td>
					<td>100</td>
					<td>40(40%)</td>
					<td>20(20%)</td>
					<td>30</td>
					<td>10</td>
				</tr>
				<tr class="tc1" align="center">
					<td class="tch" align="center">�ǽ�ͽ¬</td>
					<td>100</td>
					<td>50(50%)</td>
					<td><strong>23(23%)</strong></td>
					<td>30</td>
					<td bgcolor="#ffff00"><strong>7</strong></td>
				</tr>
				<tr class="tc2" align="center">
					<td class="tch" align="center">�ɲ�ȯ��ͽ¬</td>
					<td><strong>120</strong></td>
					<td>80(65%)</td>
					<td><strong>35(35%)</strong></td>
					<td>30</td>
					<td>-5</td>
				</tr>
			</table>
			<br>
			<div align="center">��ɸ����<strong>30</strong>���ͤޤǤˤϤ��ȡ�<strong>20</strong>���ͤ��ɲ�ȯ������ɬ�פ�����ޤ���</div>
			<div align="right"><small>�����ο����Ϥ����ޤǻ��ͤǤ���</small></div>
			<form>
			<input type="button" value="�ɲ�ȯ���򤹤�" onclick="location.href('r_job_new.php')">
			<input type="button" value="�ɲ�ȯ���Ϥ��ʤ�" onclick="">
			</form>
			</div>
			<!-- my�ѡ��ȥʡ� -->

		</td>
	</tr>
</table>

<? marketer_footer () ?>
