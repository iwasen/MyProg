<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");
include("$inc/my_search.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

if ($search_id == '')
	redirect('enq.php');

$search = new search_class;
$search->read_db($search_id);
?>
<? staff_header('�оݼԾ��') ?>

<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">���оݼԾ���</font><br><br></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="700" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="400">
						<font size="3" class="honbun1">
						���󥱡��Ȥ�ȯ�������оݼԾ��Ǥ���
						</font>
					</td>
					<td width="200">
						<a href="javascript:history.back()"><font size="3" class="honbun1">���󥱡��Ⱦܺ�ɽ�������</font></a>
					</td>
				</tr>
			</table>
			<br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>����</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=decode_sex($search->sex, '̤����')?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>ǯ��</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><? disp_age2($search->age_cd, $search->age_from, $search->age_to, '̤����') ?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>̤����</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=decode_mikikon($search->mikikon, '̤����')?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>�Ҷ���̵ͭ</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><?=decode_child($search->child, '̤����')?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>����</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><? disp_area($search->jitaku_area, '̤����') ?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>����</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><? disp_shokugyou($search->shokugyou, '̤����') ?></font>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>��������</th>
					<td width="70%" align="center">
						<font size="2" class="honbun2"><? disp_chain($search->chain, '̤����') ?></font>
					</td>
				</tr>
			</table>
			<br><br>
		</td>
	</tr>
</table>

<? staff_footer() ?>
