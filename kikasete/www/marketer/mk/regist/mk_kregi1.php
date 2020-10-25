<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist'])) {
	$_SESSION['ss_regist'] = new marketer_regist;
	$_SESSION['ss_regist']->type = 3;
}
$reg = &$_SESSION['ss_regist'];

?>

<? marketer_header('ご登録について | きかせて・net', PG_REGIST) ?>

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
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
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
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><img src="../../images/regist/sequence1.gif" width="539" height="30"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
		      <form name="form1" method="post" action="mk_kregi1_check.php">
                <tr>
                <td align="center"><table width="539" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td bgcolor="#dadada"><table width="539"  border="0" cellspacing="1" cellpadding="5">
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">お名前（漢字）</td>
                        <td height="30" bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="40" class="description">姓</td>
                              <td><input name="name1" type="text" size="20" maxlength="25" <?=value($reg->name1)?>></td>
                              <td width="40" class="description">名</td>
                              <td><input name="name2" type="text" size="20" maxlength="25" <?=value($reg->name2)?>></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">お名前（フリガナ）</td>
                        <td bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="40" class="description">セイ</td>
                              <td><input name="name1_kana" type="text" size="20" maxlength="25" <?=value($reg->name1_kana)?>></td>
                              <td width="40" class="description">メイ</td>
                              <td><input name="name2_kana" type="text" size="20" maxlength="25" <?=value($reg->name2_kana)?>></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">生年月日</td>
                        <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10">
                            <input name="birthday_y" type="text" size="6" maxlength="4" <?=value($reg->birthday_y)?>>
      年　
      <input name="birthday_m" type="text" size="3" maxlength="2" <?=value($reg->birthday_m)?>>
      月　
      <input name="birthday_d" type="text" size="3" maxlength="2" <?=value($reg->birthday_d)?>>
      日　（半角数字）</td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">性別</td>
                        <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10">
                            <input name="sex" type="radio" <?=value_checked('1', $reg->sex)?>>
      男性　
      <input name="sex" type="radio" <?=value_checked('2', $reg->sex)?>>
      女性</td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">メールアドレス</td>
                        <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10" maxlength="100" <?=value($reg->mail_addr)?>>
                            <input name="mail_addr" type="text" size="35" <?=value($reg->mail_addr)?>></td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">メールアドレス確認</td>
                        <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10" maxlength="100" <?=value($reg->mail_addr2)?>>
                            <input name="mail_addr2" type="text" size="35" <?=value($reg->mail_addr2)?>></td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">パスワード</td>
                        <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10">
                            <input name="password" type="password" size="10" <?=value($reg->password)?>>
      　（半角英数字6〜20文字まで）</td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">パスワード確認</td>
                        <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10">
                            <input name="password2" type="password" size="10" <?=value($reg->password2)?>>
      　（半角英数字6〜20文字まで）</td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">職業</td>
                        <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10">
                            <select name="shokugyou_cd"><? select_shokugyou('選択してください', $reg->shokugyou_cd) ?></select>
                          </td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">業種</td>
                        <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10">
                            <select name="gyoushu_cd"><? select_gyoushu('選択してください', $reg->gyoushu_cd, 2) ?></select>
                            </td>
                      </tr>
                      <tr class="description">
                        <td width="180" height="30" bgcolor="#e5eaf0">職種</td>
                        <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10">
                            <select name="shokushu_cd"><? select_shokushu('選択してください', $reg->shokushu_cd) ?></select>
                            </td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
                </td>
              </tr>
              <tr>
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr>
                <td align="center"><input type="image" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('next','','../../images/common/next_on.gif',1)" src="../../images/common/next.gif" alt="次へ" name="next" width="120" height="32" border="0">　<a href="javascript:document.form1.reset();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('clear','','../../images/common/clear_on.gif',1)"><img src="../../images/common/clear.gif" alt="クリア" name="clear" width="120" height="32" border="0"></a></td>
              </tr>
			  </form>
              <tr>
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
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
