<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_agent_list.php");
include("$inc/mk_subr.php");

// エージェント選択
function agent_select2($agent_id, $cols=10) {
	$sql = "SELECT ag_agent_id,ag_name1 FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	
	$agent = array();
	for ($i = 0; $i < $nrow; $i++) {
		$fetch[] = pg_fetch_object($result, $i);
	}
	
	for($i=0; $i<$nrow; $i++)
	{
		// 写真行出力
		echo("<tr><td colspan=$cols><img src='images/common/spacer.gif' width='2' height='15'></td><tr>");
		echo("<tr>");
		for($k=$i; $k-$i<$cols; $k++)
		{
			if(!isset($fetch[$k])) 
			{
				echo("<td><img src='images/common/spacer.gif' width='2' height='2'></td>");
				continue;
			}
			
			echo("<td>");
			echo "<img src='images/agent{$fetch[$k]->ag_agent_id}.jpg' alt='{$fetch[$k]->ag_name1}' width=50 height=50 border=0>\n";
			echo("</td>");
			echo("<td><img src='images/common/spacer.gif' width='5' height='2'></td>");
		}
		echo("</tr>");
		
		// 名前行出力
		echo("<tr><td colspan=$cols><img src='images/common/spacer.gif' width='2' height='2'></td><tr>");
		echo("<tr>");
		for($k=$i; $k-$i<$cols; $k++)
		{
			if(!isset($fetch[$k])) 
			{
				echo("<td><img src='images/common/spacer.gif' width='2' height='2'></td>");
				continue;
			}
			
			echo("<td align='center' valign='top'>");
			echo "<a href='mk/agent/mk_agintro.php#{$fetch[$k]->ag_agent_id}' target='_blank'><span  class='description'>{$fetch[$k]->ag_name1}</span></a>\n";
			echo("</td>");
			echo("<td><img src='images/common/spacer.gif' width='2' height='2'></td>");
		}
		echo("</tr>");
		
		// ラジオボタン行出力
		echo("<tr>");
		for($k=$i; $k-$i<$cols; $k++)
		{
			if(!isset($fetch[$k])) 
			{
				echo("<td><img src='images/common/spacer.gif' width='2' height='2'></td>");
				continue;
			}
			
			echo("<td align='center' valign='top'>");
			echo "<input type='radio' name='agent_id'", value_checked($fetch[$k]->ag_agent_id, $agent_id), ">\n";
			echo("</td>");
			echo("<td><img src='images/common/spacer.gif' width='2' height='2'></td>");
		}
		echo("</tr>");
		
		$i = $k;
	}
	
	/*	
		if ($i % $cols == 0) echo "</tr><tr><td colspan=$cols><img src='images/common/spacer.gif' width='10' height='2'></td></tr><tr>\n";
		
		echo "<td width=65 align='center' valign='top'>\n";
		echo "<img src='images/agent{$fetch->ag_agent_id}.jpg' alt='{$fetch->ag_name1}' width=50 height=50 border=0>\n";
		echo "<a href='mk_agintro.php#{$fetch->ag_agent_id}' target='_blank'><span  class='description'>{$fetch->ag_name1}</span></a>\n";
		echo "<br><input type='radio' name='agent_id'", value_checked($fetch->ag_agent_id, $agent_id), ">\n";
		echo "</td>\n";
		echo "<td>&nbsp;</td>\n";
		
	}
	
	if ($i % $cols != 0) {
		for ( ; $i % $cols != 0; $i++)
			echo "<td width=65></td>\n";
		echo "</tr><tr><td colspan=$cols></td></tr>\n";
	}
	*/
}


?>



<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_NULL) ?>

<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="789"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"></td>
<td width="12" rowspan="2"><img src="images/common/spacer.gif" width="18" height="10"></td>
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
<td><img src="images/mkk_achange/title01.gif" alt="エージェントの変更" width="150" height="28"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td> 
<p>エージェントの変更をしていただくことができます。 <br>
下記のエージェントよりお好きなエージェントをお選びください。</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<form name="form1" method="post" action="mkk_achengeconfirm.php">
<tr> 
<td> 

<?
// 専属エージェントID取得
$fetch = get_marketer_info('mr_agent_id');
$agent_id = $fetch->mr_agent_id;
?>

<div align="center">
<table width="450" border="0" cellspacing="0" cellpadding="0">
<? agent_select2($agent_id, 8) ?>
</table>
</div>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td> 
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
<td> 
<input name="image22" type="image" src="images/mkk_assistant/bt04.gif" alt="登　録" width="108" height="31">
</td>
<td><img src="images/common/spacer.gif" width="20" height="10"></td>
<td> 
<a href="javascript:history.back();"><img src="images/mkk_achange/bt01.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
</td>
<td><img src="images/common/spacer.gif" width="20" height="10"></td>
<td> 
<input type="image" name="image3" src="images/mkk_assistant/bt05.gif" alt="クリア" width="108" height="31" onclick="document.form1.reset(); return false;">
</td>
</tr>
</form>
</table>
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

<? marketer_footer(BLUE) ?>
