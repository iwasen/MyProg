<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ������
$sql = "UPDATE t_marketer SET mr_status=9,mr_taikai_date=current_timestamp WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
db_exec($sql);

// ���å�����ѿ����ꥢ
session_destroy();
?>
<? marketer_header('���', PG_CENTER) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		
    <td valign="top"><img src="images/mkk_kkstfrom/title_06.gif" width="553" height="24">
      <br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		
    <td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="8" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="768" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
				<tr>
					<td>
						<table width="768" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
							<tr>
								<td>
									����³������λ���ޤ���������ޤǤ������ơ�net �ˤ��դ��礤ĺ�������꤬�Ȥ��������ޤ�����<br><br>
									����Ȥ⡢����³����������ꤤ�����夲�ޤ����ޤ������Ȥˤ��ۤ�ĺ����Τ�ڤ��ߤˤ��Ԥ����Ƥ���ޤ���<br><br>
									�� ����Ͽ�ξ��ϡ�Top�ڡ�������ɬ�׻���򤴵�����������<br><br>
								</td>
							</tr>
							<tr>
								<td align="right">
									��<a href="index.php">�ޡ�������Top�ڡ��������</a>��
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
