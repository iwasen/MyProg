<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// ���å�����ѿ����
unset($_SESSION['ss_my_enq']);
session_unregister('ss_my_enq');
?>
<? staff_header('���󥱡�����Ͽ') ?>

<script language=javascript>
<!--
function gotoPage(url, my_enq_no) {
	var f = document.form1;
	f.action = url;
	f.my_enq_no.value = my_enq_no;
	f.submit();
}
//-->
</script>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="20"><img src="images/shim.gif" width="20" border="0"></td>
		<td width="540">
			<form name="form1" method="post">
			<input type="hidden" name="my_enq_no">
			<table width="670" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right">
						<input type="button" value="���󥱡��ȿ�����Ͽ" onclick="location.href='myenq_make.php?my_enq_no='">
					</td>
				</tr>
			</table>
			<br>
			<table width="670" border="1" cellspacing="0" cellpadding="3">
				<tr>
<!-- 2003.12.18 ��塡ID�ɲ� -->
					<td width="15%" align="center" bgcolor="#eeeeee"><font size="2" class="honbun2">�ɣ�</font>
					</td>
					<td width="60%" align="center" bgcolor="#eeeeee"><font size="2" class="honbun2">̾��</font>
					</td>
					<td colspan="2" align="center" bgcolor="#eeeeee"><font size="2" class="honbun2">����</font>
					</td>
				</tr>
<?
$staff_id = $_SESSION['ss_staff_id'];

// ���󥱡��Ȱ�������
$sql = "SELECT me_enquete_id,me_my_enq_no,me_make_step,en_status,en_enq_type,en_title"
		. " FROM t_my_enquete LEFT JOIN t_enquete ON me_enquete_id=en_enquete_id"
		. " WHERE me_staff_id=$staff_id AND en_status<>9"
		. " ORDER BY me_my_enq_no DESC";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$title = ($fetch->en_title == '') ? '�����ȥ�̤����' : htmlspecialchars($fetch->en_title);
	$my_enq_no = $fetch->me_my_enq_no;
?>
				<tr>
<!-- 2003.12.18 ��塡ID�ɲ� -->
					<td align="center" width="15%"><?=$fetch->me_enquete_id?></td>
					<td width="45%">
					<font size="2" class="honbun2">
					<a href="myenq_make.php?my_enq_no=<?=$my_enq_no?>&show=1"><?=$title?></a><br>
					</font>
					<font style="font-size:10px"><?=decode_enq_type($fetch->en_enq_type)?></font>
					</td>
					<td width="15%" align="center">
					<font size="2" class="honbun2"><?=decode_enq_status($fetch->en_status)?></font>
					</td>
					<td width="25%" align="center" valign="middle">
					<nobr>
<?
	$btn = false;

	if ($fetch->en_status <= 1) {
		$btn = true;
?>
					<input type="button" value="����³��" onclick="gotoPage('myenq_make.php', <?=$my_enq_no?>)">
<?
	}
	if ($fetch->en_status == 1) {
		$btn = true;
?>
					<input type="button" value="����" onclick="gotoPage('myenq_app1.php', <?=$my_enq_no?>)">
<?
	}
	if ($fetch->en_status == 8) {
		$btn = true;
?>
					<input type="button" value="����" onclick="gotoPage('myenq_make.php', <?=$my_enq_no?>)">
					<input type="button" value="�ƿ���" onclick="gotoPage('myenq_app1.php', <?=$my_enq_no?>)">
<?
	}
	if ($fetch->en_status <= 4) {
		$btn = true;
?>
					<input type="button" value="���" onclick="gotoPage('myenq_erase.php', <?=$my_enq_no?>)">
<?
	}
	if (!$btn) {
?>
								��
<?
	}
?>
					</nobr>
					</td>
				</tr>
<?
}
?>
			</table>
			</form>
		</td>
	</tr>
</table>
<? staff_footer () ?>