<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('メッセージ削除確認', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top"><img src="images/t_mkk_mdet1.gif" alt="メッセージ詳細" width="400" height="20"><br>
	  <br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768">このメッセージを削除してもよろしいですか？</td>
	</tr>
	<tr>
		<td>
<?
$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT co_title,co_text"
		. " FROM t_communication"
		. " WHERE co_seq_no=$seq_no AND co_marketer_id=$owner_id AND co_status=0 AND co_direction=2";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->co_title);
	$text = nl2br($fetch->co_text);
}
?>
		<form method="post" action="mkk_mdelcompl.php">
			<input type="hidden" name="seq_no" value="<?=$seq_no?>">
<table border="0" cellpadding="0" cellspacing="0" width="600">
<tr>
<td bgcolor="#dadada"> 

      <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
        <tr bgcolor="#e5eaf0"> 
          <td align="center"><?=$title?></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"><?=$text?></td>
        </tr>
      </table></td>
</tr>

				<tr>
				  <td align="right"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
		  </tr>
				<tr>
				  <td align="right">
				  <a href="mkk_mlist.php"><img src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31" 

border="0"></a>
				  <input type="image" src="images/mkk_bt/delete.gif" alt="削除" width="108" height="31"></td>
</tr>
				<tr>
				  <td align="right"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
		  </tr>
</table>		
		</form>
		</td>

	</tr>
</table>


<? marketer_footer() ?>
