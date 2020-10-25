<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// 退会処理
$sql = "UPDATE t_marketer SET mr_status=9,mr_taikai_date=current_timestamp WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
db_exec($sql);

// セッション変数クリア
session_destroy();
?>
<? marketer_header('退会', PG_CENTER) ?>

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
									退会手続きが完了しました。これまできかせて・net にお付き合い頂き、ありがとうございました。<br><br>
									今後とも、引き続きよろしくお願い申し上げます。またサイトにお越し頂けるのを楽しみにお待ちしております。<br><br>
									※ 再登録の場合は、Topページから必要事項をご記入下さい。<br><br>
								</td>
							</tr>
							<tr>
								<td align="right">
									〔<a href="index.php">マーケターTopページへ戻る</a>〕
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
