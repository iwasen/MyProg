<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
?>


<?
// メールニュース一覧表示
if ($no == '') 	$no = 0;
$sql = 'SELECT ns_regist_date,ns_subject'
		. ' FROM t_mail_news'
		. " WHERE ns_status<=1 AND ns_target IN (2,4) ORDER BY ns_regist_date DESC limit 11 OFFSET $no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < 10; $i++) {
	if ($i < $nrow) {
		$fetch = pg_fetch_object($result, $i);
		$title[$i] = htmlspecialchars($fetch->ns_subject);
		$date[$i] = format_date($fetch->ns_regist_date);
	} else {
		$title[$i] = '<br>';
		$date[$i] = '<br>';
	}
}

$prev = $next = "&nbsp;";
// 前の10件へのリンク
if ($no > 0) 
{
	$prev = "<a href='mk_pastmn_1.php?no=".max($no - 10, 0)."' class='footer_text style1'>&lt;&lt; 前の10件</a>";
}


// 次の10件へのリンク
if ($nrow > 10) {
	$next = "<a href='mk_pastmn_1.php?no=".($no + 10)."' class='footer_text style1'>次の10件 &gt;&gt;</a>";
}


?>

<? marketer_header('ご登録について | きかせて・net', PG_REGIST) ?>

<style type="text/css">
<!--
.style1 {font-size: 10}
-->
</style>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
    <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top"><img src="../../images/common/spacer.gif" width="10" height="15" /></td>
          <td bgcolor="#dbdbdb">        
          <td valign="top">        
          <td valign="top">&nbsp;</td>
          <td valign="top"><img src="../../images/common/spacer.gif" width="20" height="15"></td>
        </tr>
        <tr>
          <td width="170" valign="top" bgcolor="#e5eaf0">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right" bgcolor="#FFFFFF"><img src="../../images/regist/side_regist.gif" alt="ご登録" width="57" height="18"></td>
              </tr>
              <tr>
                
                <td><a href="mk_regist.php#mypage" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image60','','../../images/regist/side_mypage_on.gif',1)"><img src="../../images/regist/side_mypage.gif" alt="Myページ登録" name="Image60" width="170" height="31" border="0"></a></td>
              </tr>
              <tr>
                <td><img src="../../images/regist/side_mailnews_on.gif" alt="メールニュース登録" width="170" height="31"></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>　＞　<a href="mk_regist.php">登録</a>　＞　メールニュースバックナンバーリスト</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr>
                <td><img src="../../images/regist/title.gif" alt="ご登録について" width="114" height="28"></td>
              </tr>
 
              <tr>
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr>
                <td width="578"><img src="../../images/regist/header3.gif" alt="メールニュースバックナンバーリスト" width="578" height="23"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="description">これまでに、発行したメールニュースの一覧です。<br>
                  メールニュース購読の登録をしていただくか、Myページ登録をして頂いた方にお届けしています。
                <p><br>
                メールニュースの配信は、毎週水曜日です。 </p></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="right">
 				<table border=0 cellspacing=0 cellpadding=0 width="100%">
 				  <tr>
 				    <td><?= $prev ?></td><td align="right"><?= $next ?></td>
 				  </tr>
 				</table>
               </td>
              </tr>
              <tr>
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr>
                <td bgcolor="#dadada"><table width="100%"  border="0" cellspacing="1" cellpadding="5">
                  <tr bgcolor="#e5eaf0" class="description">
                    <td width="400" height="30">タイトル</td>
                    <td width="130" height="30">発行日</td>
                  </tr>
                  
<? 
	for($i=0; $i<10; $i++) 
	{
		$lno = $no+$i;
		print "<tr bgcolor='#FFFFFF' class='description'>
                    <td><a href='mk_pastmnd.php?no=$lno'>$title[$i]</a></td>
                    <td>$date[$i]</td>
                  </tr>
              ";
     }
?>
                  
                </table></td>
              </tr>
              <tr>
                <td><img src="../../images/common/spacer.gif" width="10" height="40"></td>
              </tr>
              <tr>
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr>
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table></td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(BLUE); ?>

</body>
</html>
