<?
$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('退会', PG_CENTER) ?>


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

<? marketer_menu(M_OFFICE); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
            <table width="553" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="553"><img src="images/common_my/title_06.gif" alt="COMMON" width="553" height="25"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_news/title01.gif" alt="事務局より" width="164" height="28"> 
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_news/title06.gif" alt="退会" width="553" height="24"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td bgcolor="#dadada" align="center"> 
<form method="post" action="mkk_index.php">
                    <br>
                    退会処理を行います。よろしいですか？<br>
                        一度退会されますと、これまでのデータは全て消去されますのでご注意下さい。<br>
                        退会手続きを取り消す場合は、下記の「戻る」ボタンを押して下さい。 <br>
                        <br>
                        <div align="center"> 
                          <input type="submit" value="戻る（退会手続きをキャンセル）">
                        </div><br>

</form>				  
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
<form method="post" action="mkk_rescompl.php">
              <tr> 
                <td align="center">　
				<input type="submit" value="きかせて・net を退会する">
				</td>
              </tr>
</form>
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
