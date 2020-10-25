<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header.php");

// •ª•√•∑•Á•ÛΩËÕ˝
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('mk_kregi1.php');
$reg = &$_SESSION['ss_regist'];
?>
<? marketer_header('∏Êº“æ Û§Œ≈–œø', PG_REGIST) ?>

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
                <td align="right" bgcolor="#FFFFFF"><img src="../../images/regist/side_regist.gif" alt="§¥≈–œø" width="57" height="18"></td>
              </tr>
              <tr>
                <td><img src="../../images/regist/side_mypage_on.gif" alt="My•⁄°º•∏≈–œø" width="170" height="31"></td>
              </tr>
              <tr>
                
                <td><a href="mk_regist.php#mail" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image59','','../../images/regist/side_mailnews_on.gif',1)"><img src="../../images/regist/side_mailnews.gif" alt="•·°º•Î•À•Â°º•π≈–œø" name="Image59" width="170" height="31" border="0"></a></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>°°°‰°°<a href="mk_regist.php">≈–œø</a>°°°‰°°My•⁄°º•∏≈–œø</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr> 
                <td><img src="../../images/regist/title.gif" alt="§¥≈–œø§À§ƒ§§§∆" width="114" height="28"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr> 
                <td width="578"><img src="../../images/regist/header1.gif" alt="My•⁄°º•∏≈–œø" width="578" height="38"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td align="center"><img src="../../images/regist/sequence3.gif" width="539" height="30"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <form method="post" name="form1" action="mk_kregi2_check.php">
                <tr> 
                  <td align="center">
                    <table width="539" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td bgcolor="#dadada"> 
                          <table width="539"  border="0" cellspacing="1" cellpadding="5">
                            <tr class="description"> 
                              <td width="180" height="30" bgcolor="#e5eaf0">≤Òº“Ãæ</td>
                              <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <input type="text" name="kinmu_name" size="40" maxlength=50 <?=value($reg->kinmu_name)?>>
                              </td>
                            </tr>
                            <tr class="description"> 
                              <td width="180" height="30" bgcolor="#e5eaf0">…ÙΩÃæ</td>
                              <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <input type="text" name="kinmu_unit" size="40" maxlength=50 <?=value($reg->kinmu_unit)?>>
                              </td>
                            </tr>
                            <tr class="description"> 
                              <td width="180" height="30" bgcolor="#e5eaf0">ÃÚø¶</td>
                              <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <input type="text" name="kinmu_post" size="40" maxlength=50 <?=value($reg->kinmu_post)?>>
                              </td>
                            </tr>
                            <tr class="description"> 
                              <td width="180" height="30" bgcolor="#e5eaf0">Õπ ÿ»÷πÊ</td>
                              <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <input type="text" name="kinmu_zip1" size="3" maxlength="3" <?=value($reg->kinmu_zip1)?>>
                                - 
                                <input type="text" name="kinmu_zip2" size="4" maxlength="4" <?=value($reg->kinmu_zip2)?>>
                                (»æ≥—øÙª˙)<a href="<?=$top?>/../common/zip_search.php?pfc=2" target="zip_search">Õπ ÿ»÷πÊ§¨ ¨§´§È§ §§ ˝</a></td>
                            </tr>
                            <tr class="description"> 
                              <td width="180" height="30" bgcolor="#e5eaf0">∂–Ã≥¿ËΩªΩÍ°°° ≈‘∆ª…‹∏©°À</td>
                              <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <select name="kinmu_area">
                                  <? select_area('¡™¬Ú§∑§∆§Ø§¿§µ§§', $reg->kinmu_area) ?>
                                </select>
                              </td>
                            </tr>
                            <tr class="description"> 
                              <td width="180" height="30" bgcolor="#e5eaf0">∂–Ã≥¿ËΩªΩÍ°°° ª‘∂Ë°À</td>
                              <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <input type="text" name="kinmu_addr1" size="50" maxlength="50" <?=value($reg->kinmu_addr1)?>>
                                <br>
                                <img src="../../images/common/spacer.gif" width="35" height="10">° Œ„°ßπ¡∂Ë°ø≤£…Õª‘¿ƒÕ’∂Ë°À</td>
                            </tr>
                            <tr class="description"> 
                              <td width="180" height="30" bgcolor="#e5eaf0">∂–Ã≥¿ËΩªΩÍ°°° ƒÆ¬º°À</td>
                              <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <input type="text" name="kinmu_addr2" size="50" maxlength="50" <?=value($reg->kinmu_addr2)?>>
                                <br>
                                <img src="../../images/common/spacer.gif" width="35" height="10"> 
                                ° Œ„°ßø∑∂∂6-20-2°À</td>
                            </tr>
                            <tr class="description"> 
                              <td width="180" height="30" bgcolor="#e5eaf0">∂–Ã≥¿ËΩªΩÍ°°° •”•ÎÃæ§ §…°À</td>
                              <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <input type="text" name="kinmu_addr3" size="40" maxlength="50" <?=value($reg->kinmu_addr3)?>>
                                <br>
                                <img src="../../images/common/spacer.gif" width="35" height="10"> 
                                ° Œ„°ß£”£…•”•Î°À</td>
                            </tr>
                            <tr class="description"> 
                              <td width="180" height="28" bgcolor="#e5eaf0">∂–Ã≥¿Ë≈≈œ√»÷πÊ</td>
                              <td bgcolor="#FFFFFF" height="28"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <input type="text" name="kinmu_tel1" size="5" maxlength="5" <?=value($reg->kinmu_tel1)?>>
                                - 
                                <input type="text" name="kinmu_tel2" size="5" maxlength="5" <?=value($reg->kinmu_tel2)?>>
                                - 
                                <input type="text" name="kinmu_tel3" size="5" maxlength="4" <?=value($reg->kinmu_tel3)?>>
                                ° »æ≥—øÙª˙°À</td>
                            </tr>
                            <tr class="description"> 
                              <td width="180" height="30" bgcolor="#e5eaf0">∂–Ã≥¿Ë£∆£¡£ÿ</td>
                              <td bgcolor="#FFFFFF"><img src="../../images/common/spacer.gif" width="35" height="10"> 
                                <input type="text" name="kinmu_fax1" size="5" maxlength="5" <?=value($reg->kinmu_fax1)?>>
                                - 
                                <input type="text" name="kinmu_fax2" size="5" maxlength="5" <?=value($reg->kinmu_fax2)?>>
                                - 
                                <input type="text" name="kinmu_fax3" size="5" maxlength="4" <?=value($reg->kinmu_fax3)?>>
                                ° »æ≥—øÙª˙°À</td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr> 
                  <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
                </tr>
                <tr> 
                  <td align="center"> 
                    <input type="image" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('next','','../../images/common/next_on.gif',1)" src="../../images/common/next.gif" alt="º°§ÿ" name="next" width="120" height="32" border="0">
                    <a href="mk_kregi1.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('back','','../../images/common/pback_on.gif',1)"><img src="../../images/common/pback.gif" alt="Ã·§Î" name="back" width="120" height="32" border="0"></a> 
                    <a href="javascript:document.form1.reset();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('clear','','../../images/common/clear_on.gif',1)"><img src="../../images/common/clear.gif" alt="•Ø•Í•¢" name="clear" width="120" height="32" border="0"></a></td>
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
