<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$myp = &$_SESSION['s_my_partner'];
$search = &$myp->search;

// モニター数取得
$monitor_num = $search->get_monitor_num();
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
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
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->

			<div align="center">
			<br>
			<table width="650" border="0" cellspacing="0" cellpadding="" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						設定した条件による絞込み結果です。
					</td>
				</tr>
			</table>
			</div>
			<br>
			<form method="" action="mkk_myenq_con.php">
			<div align="center">
			<table width="500" border="0" cellspacing="0" cellpadding="0">
				<tr>
    <td bgcolor="#dadada"> 
      <table border="0" cellspacing="0" cellpadding="5" width="100%" align="center">
        <tr bgcolor="#e5eaf0"> 
          <td align="center">
<strong><?=$monitor_num?></strong> 人が該当しました
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
									<a href="javascript:history.back();"><img name="image2" src="images/mkk_achange/bt01.gif" alt="戻る" width="108" height="31"></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</div>
			</form>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
