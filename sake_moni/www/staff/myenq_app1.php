<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// ���å�������
if (isset($my_enq_no)) {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	$myenq->read_db($_SESSION['ss_staff_id'], $my_enq_no);
} else
	redirect('myenq.php');

$enquete = &$myenq->enquete;

// ���ơ�������������λ�ޤ��Ϻƿ����Ǥʤ�����᤹
if ($enquete->status != 1 && $enquete->status != 8)
	redirect('myenq.php');
?>
<? staff_header('���󥱡�����Ͽ') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<form method="post" action="myenq_app2.php">
			<br>
			<center>
			<table width="500" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">
						<font size="2" class="honbun2">
						��̳�ɤ˥��󥱡��ȼ»ܤο����򤷤ޤ�����<br>�ʿ�����λ������ѹ����Ǥ��ʤ��ʤ�ޤ��Τǡ�����դ�����������
						</font>
					</td>
				</tr>
				<tr>
					<td align="center">
						<font size="2" class="honbun2">��̳�ɰ��Ƥ˥����Ȥ�����Ф��񤭤���������</font><br>
						<textarea name="comment" cols=60 rows=5></textarea>
					</td>
				</tr>
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="50%" align="right">
									<input type="button" value="���ᡡ�롡" onclick="history.back()">
								</td>
								<td width="50%" align="left">
									<input type="submit" value="����������">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</center>
			</form>

<? staff_footer() ?>
