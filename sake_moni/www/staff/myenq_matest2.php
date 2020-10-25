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
	$staff_id = $_SESSION['ss_staff_id'];

	if (isset($_SESSION['ss_my_enq'])) {
		$myenq = &$_SESSION['ss_my_enq'];
		if ($myenq->my_enq_no != $my_enq_no)
			$myenq->read_db($staff_id, $my_enq_no);
	} else {
		$_SESSION['ss_my_enq'] = new my_enquete_class;
		$myenq = &$_SESSION['ss_my_enq'];
		$myenq->read_db($staff_id, $my_enq_no);
	}
} else
	redirect('myenq.php');

$search = &$myenq->search;

// モニター数取得
$monitor_num = $search->get_monitor_num();
?>
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="710" align="center" valign="top">
			<table width="650" border="0" cellspacing="0" cellpadding="" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						<font size="3" class="honbun1">設定した条件による絞込み結果です。</font>
					</td>
				</tr>
			</table>
			<br>
			<form method="" action="myenq_con.php">
			<center>
			<table width="500" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">
						<font size="2" class="honbun2">
						<b><?=$monitor_num?></b> 人が該当しました
						<br><br>
						※ここでの結果は参考値であり、実施時の対象モニターとは異なる場合があります
						</font>
					</td>
				</tr>
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="100%" align="center">
									<input type="button" value="　戻る　" onclick="history.back()">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</center>
			</form>
		</td>
	</tr>
</table>

<? staff_footer() ?>
