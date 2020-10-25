<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// エージェント変更申請処理
$marketer_id = $_SESSION['ss_marketer_id'];
$sql = "SELECT ac_agent_id FROM t_agent_change WHERE ac_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result))
	$sql = "UPDATE t_agent_change SET ac_agent_id=$agent_id WHERE ac_marketer_id=$marketer_id";
else
	$sql = "INSERT INTO t_agent_change (ac_marketer_id,ac_agent_id) VALUES ($marketer_id,$agent_id)";
db_exec($sql);
?>
<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_NULL) ?>


<!--contents start-->
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
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
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
<p>エージェントへの変更を申請しました。<br>
エージェントの変更が終了しましたら、事務局よりお知らせいたしますのでお待ちください。</p>
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
<!--footer start-->

<? marketer_footer() ?>
