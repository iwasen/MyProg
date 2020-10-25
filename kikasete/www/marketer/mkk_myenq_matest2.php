<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if (isset($my_enq_no)) {
	$marketer_id= $_SESSION['ss_marketer_id'];
	$owner_id = $_SESSION['ss_owner_id'];

	if (isset($_SESSION['ss_my_enq'])) {
		$myenq = &$_SESSION['ss_my_enq'];
		if ($myenq->my_enq_no != $my_enq_no)
			$myenq->read_db($owner_id, $my_enq_no);
	} else {
		$_SESSION['ss_my_enq'] = new my_enquete_class;
		$myenq = &$_SESSION['ss_my_enq'];
		$myenq->read_db($owner_id, $my_enq_no);
	}
} else
	redirect('mkk_myenq.php');

$search = &$myenq->search;

// モニター数取得
$monitor_num = $search->get_monitor_num();
?>
<? marketer_header('Ｍｙアンケート', PG_NULL) ?>
<!-- ↓全体かこみテーブル -->
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
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
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<br>
			<table width="650" border="0" cellspacing="0" cellpadding="" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						設定した条件による絞込み結果です。
					</td>
				</tr>
			</table>
			<br>
			<form>
			<center>
			<table width="500" border="0" cellspacing="0" cellpadding="0">
				<tr>
					    <td bgcolor="#dadada"> 
      <table border="0" cellspacing="0" cellpadding="5" width="100%" class="nor_text">
        <tr bgcolor="#e5eaf0"> 
          <td align="center">
		  						<b><?=$monitor_num?></b> 人が該当しました
						<br><br>
						※ここでの結果は参考値であり、実施時の対象モニターとは異なる場合があります
		  </td>
        </tr>
      </table>
    </td>
				</tr>
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="100%" align="center">
									
<a href="javascript:history.back();"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
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
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
