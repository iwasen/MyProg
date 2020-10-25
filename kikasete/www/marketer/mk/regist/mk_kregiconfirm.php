<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('mk_kregi1.php');
$reg = &$_SESSION['ss_regist'];
?>
<? marketer_header('登録内容の確認', PG_REGIST) ?>

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
                <td><img src="../../images/regist/side_mypage_on.gif" alt="Myページ登録" width="170" height="31"></td>
              </tr>
              <tr>
                
                <td><a href="mk_regist.php#mail" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image59','','../../images/regist/side_mailnews_on.gif',1)"><img src="../../images/regist/side_mailnews.gif" alt="メールニュース登録" name="Image59" width="170" height="31" border="0"></a></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top">
            <table width="578" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>　＞　<a href="mk_regist.php">登録</a>　＞　Myページ登録</td>
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
                <td width="578"><img src="../../images/regist/header1.gif" alt="Myページ登録" width="578" height="38"></td>
              </tr>
              　　 
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td>以下の内容で登録します。よろしいですか？<br>
                  （修正は、ご登録後Myページからも行うことができます。） </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td> 
                  <form method="post" action="mk_kregicode.php">
                    <table width="553" border="0" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td bgcolor="#dadada"> 
                          <table width="553" border="0" cellspacing="1" cellpadding="5" class="nor_text">
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">姓</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->name1)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">名</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->name2)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">セイ</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->name1_kana)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">メイ</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->name2_kana)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">生年月日</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=$reg->birthday_y?>
                                年 
                                <?=$reg->birthday_m?>
                                月 
                                <?=$reg->birthday_d?>
                                日</td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">性別</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_sex($reg->sex)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">メールアドレス</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->mail_addr)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">パスワード</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->password)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">職業</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_shokugyou($reg->shokugyou_cd)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">業種</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_gyoushu($reg->gyoushu_cd)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">職種</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_shokushu($reg->shokushu_cd)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0" colspan="2">勤務先情報</td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">勤務先名</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_name)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0" height="22">部署名</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF" height="22"> 
                                <?=htmlspecialchars($reg->kinmu_unit)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">役職名</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_post)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">勤務先郵便番号</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=$reg->kinmu_zip1?>
                                - 
                                <?=$reg->kinmu_zip2?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">勤務先住所(都道府県)</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=decode_area($reg->kinmu_area)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">勤務先住所(市区)</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_addr1)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">勤務先住所(町村・番地)</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_addr2)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">勤務先住所(ビル名など)</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=htmlspecialchars($reg->kinmu_addr3)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">勤務先電話番号</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=format_tel($reg->kinmu_tel1, $reg->kinmu_tel2, $reg->kinmu_tel3)?>
                              </td>
                            </tr>
                            <tr> 
                              <td width="30%" valign="top" bgcolor="#e5eaf0">勤務先FAX番号</td>
                              <td width="70%" valign="top" bgcolor="#FFFFFF"> 
                                <?=format_tel($reg->kinmu_fax1, $reg->kinmu_fax2, $reg->kinmu_fax3)?>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr> 
                        <td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
                      </tr>
                      <tr> 
                        <td align="center"> 
                          <table border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td> 
                                
<input name="image2" type="image" src="../../images/common/entry.gif" alt="登録" width="118" height="32">
                              </td>
                              <td><img src="../../images/common/spacer.gif" width="20" height="10"></td>
                              <td> 
<a href="mk_kregi1.php"><img name="image3" src="../../images/common/shusei.gif" alt="修　正" width="118" height="31"></a>
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
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
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

<? marketer_footer(BLUE); ?>

</body>
</html>
