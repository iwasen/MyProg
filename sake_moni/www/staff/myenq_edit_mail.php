<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// ���å�������
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	if ($my_enq_no == '') {
		$myenq->staff_id = $_SESSION['ss_staff_id'];
	} else
		$myenq->read_db($_SESSION['ss_staff_id'], $my_enq_no);
}

$enquete = &$myenq->enquete;

if ($myenq->mail_title == '')
	$subject = $enquete->title;
else
	$subject = $myenq->mail_title;

if ($myenq->mail_contents == '')
	get_enq_body($myenq, $header, $body, $footer);
else {
	$header = $myenq->mail_header;
	$body = $myenq->mail_contents;
	$footer = $myenq->mail_footer;
}
?>
<? staff_header('���󥱡�����Ͽ') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<form method="post" name="form1" action="myenq_update_mail.php">
			<table width="90%" border=0 cellspacing=2 cellpadding=3 class="honbun2">
				<tr>
					<td bgcolor="#eff7e8">���᡼��ʸ�Υ������ޥ���</td>
				</tr>
				<tr>
					<td>
						<table border=0 cellspacing=2 cellpadding=3 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eeeeee" width="20%">��̾</td>
								<td bgcolor="#ffeecc"><input type="text" name="subject" size=80 maxlength=100 <?=value($subject)?>>�����ѣ�����ʸ���ޤǡ�</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�إå�</td>
								<td bgcolor="#ffeecc"><textarea name="header" cols=72 rows=10><?=htmlspecialchars($header)?></textarea></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee"0>��ʸ</td>
								<td bgcolor="#ffeecc">��������ʸ�����Ȥ��ޤ����ʥ�˥�����̾= %MONITOR_NAME% ��<br>
									<textarea name="body" cols=72 rows=25><?=htmlspecialchars($body)?></textarea></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�եå�</td>
								<td bgcolor="#ffeecc"><textarea name="footer" cols=72 rows=8><?=htmlspecialchars($footer)?></textarea></td>
							</tr>
						</table><br>
						<div align="center">
						<input type="submit" value="�᡼��ץ�ӥ塼" onclick="document.form1.next_action.value='preview'">
						<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='update'">
						<input type="submit" value="�ꥻ�å�" onclick="document.form1.next_action.value='reset'">
						<input type="button" value="����롡" onclick="location.href='myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
						<input type="hidden" name="next_action">
						<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
						</div>
					</td>
				</tr>
			</table>
			</form>

<? staff_footer () ?>
