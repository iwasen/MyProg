<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if (!isset($_SESSION['ss_regist']))
	redirect('mkk_upd_b.php');
$reg = &$_SESSION['ss_regist'];

// テーブルに保存
$reg->update_db();

// セッション変数削除
unset($_SESSION['ss_regist']);
session_unregister('ss_regist');
?>
<? marketer_header('登録情報の更新', PG_NULL) ?>

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

<? marketer_menu(M_REGIST); ?>

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
                <td><img src="images/mkk_update/title_01.gif" alt="登録情報の更新" width="130" height="28"> 
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20" /></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_update/bt_01.gif" alt="基本情報の更新 " name="Image54" width="171" height="31" border="0"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="5" /></td>
              </tr>
              <tr> 
<td>

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
                          </table>
            </td>
          </tr>
          <tr> 
            <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
          </tr>
          <tr> 
            <td align="center"> 
              <table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center">
						〔 <a href="mkk_upd_c.php">企業情報更新ページへ</a> 〕　〔 <a href="mkk_index.php">Topへ戻る</a>  〕
					</td>
				</tr>
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
