<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
?>
<? marketer_header('メールニュース登録削除', PG_REGIST) ?>

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
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>　＞　<a href="mk_regist.php">登録</a>　＞　メールニュース登録削除</td>
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
                <td width="578"><img src="../../images/regist/email_deli.gif" width="553" height="24"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td class="description"> 
                  <p>こちらから、メールニュースの配信を停止することができます。 <br>
                    下記にご登録のメールアドレスを記入の上、削除ボタンを押して下さい。 <br>
                    <br>
                    ※　Myページ登録の方は、MyページよりMyページ退会手続きを行ってください。<br />
                    　　
                    こちらから登録を削除することはできません。<br />
                    　
                  　また、Myページ登録の方は、メールニュースのみの配信停止は行えません。</p>
                  <p><br />
                    <br />
                </p></td>
              </tr>
              <tr> 
                <td align="center"> 
                  <table width="538" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td bgcolor="#e5eaf0"><img src="../../images/common/spacer.gif" width="10" height="30"></td>
                    </tr>
                    <tr> 
                      <td align="center" bgcolor="#e5eaf0"> 
                        <form action="mk_mnrescompl.php" method="post">
                          <img src="../../images/regist/email.gif" alt="" width="44" height="12">　 
                          <input name="mail_addr" type="text" size="30">
                          　 
                          <input type="submit" value="削除">
                        </form>
                      </td>
                    </tr>
                    <tr> 
                      <td bgcolor="#e5eaf0"><img src="../../images/common/spacer.gif" width="10" height="30"></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
          </td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(BLUE) ?>
