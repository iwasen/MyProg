<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if (isset($pro_enq_no)) {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$proenq = &$_SESSION['ss_pro_enq'];
	$proenq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
} else
	redirect('pe_index.php');

$enquete = &$proenq->enquete;

// ステータスが作成完了または再申請でなければ戻す
if ($enquete->status != 1 && $enquete->status != 8)
	redirect('pe_index.php');
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<form method="post" action="pe_app2.php">
			<br>
			<center>
			<table bgcolor="#ffffff" width="550" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td bgcolor="#dadada"> 
					  <table border="0" cellspacing="0" cellpadding="5" width="100%" class="nor_text">
						<tr bgcolor="#e5eaf0"> 
						  <td align="center">事務局にアンケート実施の申請をしますか？<br>（申請を完了すると変更ができなくなりますので、ご注意ください。）</td>
						</tr>
					  </table>
					</td>
				</tr>
				<tr>
					<td bgcolor="#dadada"> 
						<table border="0" cellspacing="0" cellpadding="5" width="100%" class="nor_text">
							<tr bgcolor="#e5eaf0"> 
								<td align="center">
<table width="400" border="0" cellspacing="0" cellpadding="12" bgcolor="#FF9933">
  <tr>
	<td>アンケート内容を確認させていただく為、こちらからご連絡させていただく場合がございますので、お手数ですが、下記にご連絡先等をご入力ください。<br /><br />
	  ※ご連絡がとれない場合、ご申請頂いた日時にアンケートを発信できない可能性がございます。何卒ご了承ください。</td>
  </tr>
</table>
<br />
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="4">
				<tr>
					<td>◇アンケートご担当者：</td>
					<td><input type="text" name="tantou_name" size="40"></td>
				</tr>
				<tr>
					<td>◇ご連絡先電話番号：</td>
					<td><input type="text" name="renraku_tel" size="40"></td>
				</tr>
				<tr>
					<td>◇ご連絡先メールアドレス：</td>
					<td><input type="text" name="renraku_mail" size="40"></td>
				</tr>
				<tr>
					<td colspan="2">◇その他、ご連絡可能な時間帯、ご質問などございましたらお書きください。</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><textarea name="sonota" cols="60" rows="7"></textarea></td>
	</tr>
</table>
<br>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
				</tr>
				<tr>
					<td>
						<table width="100%" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="50%" align="right">
									<a href="javascript:history.back();"><img src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31" border="0"></a>
								</td>
								<td width="50%" align="left">
									<input type="image" src="images/mkk_bt/apply.gif" alt="申請" width="108" height="31">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
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
