<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
include("$inc/mk_error.php");

$mail_addr = get_mail_addr($mail_addr);
if ($mail_addr == '')
	$msg[] = 'メールアドレスが入力されていません。';
else {
	if (!check_mail_addr($mail_addr))
		$msg[] = 'メールアドレスが正しくないようです。';
	else {
		$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_mail_addr_low=" . sql_char(strtolower($mail_addr)) . " AND mr_status<>9";
		$result = db_exec($sql);
		if (pg_numrows($result))
			$msg[] = '入力されたメールアドレスは既に登録されています。';
		else {
			$sql = sprintf("INSERT INTO t_marketer (mr_type,mr_mail_addr,mr_mail_addr_low) VALUES (1,%s,%s)",
				sql_char($mail_addr),
				sql_char(strtolower($mail_addr)));
			db_exec($sql);
		}
	}
}
?>
<? marketer_header('メールニュース登録', PG_REGIST) ?>


<style type="text/css">
<!--
.style1 {color: #777777}
.style3 {font-size: 12px}
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
          <td valign="top">
<?
if (!$msg) {
?>		  
		    <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>　＞　<a href="mk_regist.php">登録</a>　＞　メールニュース登録</td>
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
                <td><a name="mail"><img src="../../images/regist/header2.gif" alt="" width="578" height="38"></a></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td class="description"> 
                  <p>ご登録ありがとうございました。</p>
                  <p> メールニュースの登録が完了いたしました。</p>
                  <p> 毎月最終木曜日に、新鮮な生活者情報・お役立ちマーケティング情報をお届けして参ります。 どうぞお楽しみに！ </p>
                </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
<?
} else {
	$hidden[] = '<input type="hidden" name="mail_addr" ' . value($mail_addr) . '>';
	error_msg($msg, 'mk_regist.php#mail', $hidden);
}
?>			
			</td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(BLUE); ?>

</body>
</html>
