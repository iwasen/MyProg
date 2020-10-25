<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('メッセージ一覧', PG_NULL) ?>

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

<? marketer_menu(M_MYAGENT); ?>

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
                <td><img src="images/mkk_myagent/title01.gif" alt="Myエージェント" width="130" height="28"> 
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_mlist/title1.gif" width="200" height="28"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_mlist/title2.gif" width="553" height="25"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td>

<table width="553" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td bgcolor="#dadada"> 
<?
$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT co_title,co_text"
		. " FROM t_communication"
		. " WHERE co_marketer_id=$owner_id AND co_status=0 AND co_direction=2 ORDER BY co_regist_date DESC LIMIT 1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->co_title);
	$text = nl2br($fetch->co_text);
?>
      <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
        <tr bgcolor="#e5eaf0"> 
          <td width="100%"><?=$title?></td>

        </tr>
        <tr> 
          <td bgcolor="#FFFFFF" width="100%"><?=$text?></td>

        </tr>
      </table>
<?
} else
	echo 'メッセージはありません。<br>';
?>		  
    </td>
  </tr>
</table>
		
				
				　</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_mlist/title3.gif" width="553" height="25"></td>
              </tr>
              <tr>
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td>
<table width="553" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td bgcolor="#dadada"> 
      <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
        <tr bgcolor="#e5eaf0"> 
          <td width="20%" align="center">日時</td>
          <td width="60%" align="center">タイトル</td>
        </tr>
<?
$sql = "SELECT co_seq_no,co_regist_date,co_title"
		. " FROM t_communication"
		. " WHERE co_marketer_id=$owner_id AND co_status=0 AND co_direction=2 ORDER BY co_regist_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$seq_no = $fetch->co_seq_no;
	$regist_date = format_date($fetch->co_regist_date);
	$title = htmlspecialchars($fetch->co_title);
?>
        <tr> 
          <td bgcolor="#FFFFFF" width="20%" align="center"><?=$regist_date?></td>
          <td bgcolor="#FFFFFF" width="60%"><a href="mkk_mdet1.php?seq_no=<?=$seq_no?>">
						<?=$title?>
						</a></td>
        </tr>
<?
}
?>		
      </table>
    </td>
  </tr>
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
