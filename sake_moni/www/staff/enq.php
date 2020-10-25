<?
$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
?><? staff_header('アンケート結果') ?>

<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
</table>
				<table width="540" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>
							<table width="500" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
								<tr>
									<td bgcolor="#eeeeee">
										<a href="pjtlist.php"><font size="3" class="honbun1">現在実施中のアンケート一覧</font></a>
									</td>
								</tr>
								<tr>
									<td width="100%"><font size="3" class="honbun1">現在実施中のアンケートの設定・対象モニターの詳細はもちろん、回答状況などを随時チェックしていただくことができます。また、アンケートの結果をダウンロードしていただくこともできます。<br><br></font>
									</td>
								</tr>
								<tr>
									<td bgcolor="#eeeeee">
										<a href="endpjtlist.php"><font size="3" class="honbun1">過去に実施したアンケート一覧</font></a>
									</td>
								</tr>
								<tr>
									<td width="100%"><font size="3" class="honbun1">過去に実施したアンケートの設定・対象モニターの詳細の他に、回答結果をダウンロードしていただけます。</font>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
<? staff_footer() ?>
