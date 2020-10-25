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

// セッション処理
if (isset($my_enq_no)) {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	$myenq->read_db($_SESSION['ss_staff_id'], $my_enq_no);
} else
	redirect('myenq.php');

$enquete = &$myenq->enquete;

// ステータスが作成完了または再申請でなければ戻す
if ($enquete->status != 1 && $enquete->status != 8)
	redirect('myenq.php');
?>
<? staff_header('アンケート登録') ?>

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
						事務局にアンケート実施の申請をしますか？<br>（申請を完了すると変更ができなくなりますので、ご注意ください。）
						</font>
					</td>
				</tr>
				<tr>
					<td align="center">
						<font size="2" class="honbun2">事務局宛てにコメントがあればお書きください。</font><br>
						<textarea name="comment" cols=60 rows=5></textarea>
					</td>
				</tr>
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="50%" align="right">
									<input type="button" value="　戻　る　" onclick="history.back()">
								</td>
								<td width="50%" align="left">
									<input type="submit" value="　申　請　">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</center>
			</form>

<? staff_footer() ?>
