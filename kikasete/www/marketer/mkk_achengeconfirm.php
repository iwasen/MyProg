<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

$marketer_id = $_SESSION['ss_marketer_id'];

// 申請済みチェック
$sql = "SELECT ac_agent_id FROM t_agent_change WHERE ac_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	if ($fetch->ac_agent_id == $agent_id)
		$msg[] = 'お選びいただいたエージェントへの変更は申請済みとなっております。<br>　エージェントの変更が終了しましたら、事務局よりお知らせいたしますのでお待ちください。';
} else {
	// 変更有無チェック
	$sql = "SELECT mr_agent_id,mr_name1 FROM t_marketer WHERE mr_marketer_id=$marketer_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->mr_agent_id == $agent_id)
			$msg[] = sprintf('お選びいただいたエージェントは現在、%sさまの専属エージェントとなっております。', htmlspecialchars($fetch->mr_name1));
	}
}
?>
<? marketer_header('エージェントの変更', PG_NULL) ?>

<!--contents start-->
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"> 
<td width="12" rowspan="2"> <img src="images/common/spacer.gif" width="18" height="10"> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_AGENTEDIT); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> </td>
<td valign="top"> 
<?
if (!$msg) {
?>

<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/common_my/title_07.gif" alt="MY SETTING" width="553" height="25" /></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mkk_achange/title01.gif" alt="エージェントの変更" width="150" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0"><img src="images/common/spacer.gif" width="10" height="50" /> 
</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center">

			<form method="post" action="mkk_achengecomple.php">
			<input type="hidden" name="agent_id" value="<?=$agent_id?>">
			<table width="553" border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td align="center">
						<table width="553" border="0" cellspacing="0" cellpadding="0">
							<tr>
<?
$sql = "SELECT ag_name1 FROM t_agent WHERE ag_agent_id=$agent_id";
$agent_name = htmlspecialchars(db_fetch1($sql));
?>
								<td align="center" valign="center">
									<img src="images/agent<?=$agent_id?>.jpg" alt="<?=$agent_name?>です" width="50" height="50" border="0">
									<br>
                                <a href="mk/agent/mk_agintro.php#<?=$agent_id?>" target="_blank"> 
                                <?=$agent_name?>
                                </a> </td>
							</tr>
							<tr>
								<td><img src="images/common/spacer.gif" width="10" height="30" /> </td>
							</tr>
							<tr>
								<td width="30%" align="center">
									<input name="image2" type="image" src="images/common/shinsei.gif" alt="申請" width="130" height="32">
									
									<a href="javascript:history.back();"><img src="images/common/pback.gif" alt="戻る" name="image2" width="120" height="32" border="0"></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</form>

</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0"><img src="images/common/spacer.gif" width="10" height="50" /> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? } else{?>
	<table width="553" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<? error_msg($msg);?></td>
  </tr>
</table>
<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="1" border="0"></td>
  </tr>
</table>

<!--footer start-->

<? marketer_footer(BLUE) ?>

