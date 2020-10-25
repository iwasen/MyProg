<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if (!isset($_SESSION['ss_regist']) || !$cont) {
	$_SESSION['ss_regist'] = new marketer_regist;
	$reg = &$_SESSION['ss_regist'];
	$reg->read_db($_SESSION['ss_marketer_id']);
} else
	$reg = &$_SESSION['ss_regist'];
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
                <td><img src="images/mkk_update/bt_02.gif" alt="企業情報の更新 " name="Image54" width="171" height="31" border="0"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="5" /></td>
              </tr>
              <tr> 
<td align="center">
               <form method="post" action="mkk_upd_c_check.php">     
<table width="539" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td bgcolor="#dadada">
    
              <table width="539"  border="0" cellspacing="1" cellpadding="5">
                <tr class="description"> 
                  <td width="180" height="30" bgcolor="#e5eaf0">会社名</td>
                  <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <input type="text" name="kinmu_name" size="40" maxlength=50 <?=value($reg->kinmu_name)?>>
                    </td>
                </tr>
                <tr class="description"> 
                  <td width="180" height="30" bgcolor="#e5eaf0">部署名</td>
                  <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <input type="text" name="kinmu_unit" size="40" maxlength=50 <?=value($reg->kinmu_unit)?>></td>
                </tr>
                <tr class="description"> 
                  <td width="180" height="30" bgcolor="#e5eaf0">役職</td>
                  <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <input type="text" name="kinmu_post" size="40" maxlength=50 <?=value($reg->kinmu_post)?>></td>
                </tr>
                <tr class="description"> 
                  <td width="180" height="30" bgcolor="#e5eaf0">郵便番号</td>
                  <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <input type="text" name="kinmu_zip1" size="3" maxlength="3" <?=value($reg->kinmu_zip1)?>> - <input type="text" name="kinmu_zip2" size="4" maxlength="4" <?=value($reg->kinmu_zip2)?>>(半角数字)</td>
                </tr>
                <tr class="description"> 
                  <td width="180" height="30" bgcolor="#e5eaf0">勤務先住所　（都道府県）</td>
                  <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <select name="kinmu_area"><? select_area('選択してください', $reg->kinmu_area) ?></select></td>
                </tr>
                <tr class="description"> 
                  <td width="180" height="30" bgcolor="#e5eaf0">勤務先住所　（市区）</td>
                  <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <input type="text" name="kinmu_addr1" size="50" maxlength="50" <?=value($reg->kinmu_addr1)?>><br>
<img src="images/common/spacer.gif" width="35" height="10">（例：港区／横浜市青葉区）</td>
                </tr>
                <tr class="description"> 
                  <td width="180" height="30" bgcolor="#e5eaf0">勤務先住所　（町村）</td>
                  <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <input type="text" name="kinmu_addr2" size="50" maxlength="50" <?=value($reg->kinmu_addr2)?>><br>
<img src="images/common/spacer.gif" width="35" height="10"> （例：新橋6-20-2）</td>
                </tr>
                <tr class="description"> 
                  <td width="180" height="30" bgcolor="#e5eaf0">勤務先住所　（ビル名など）</td>
                  <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <input type="text" name="kinmu_addr3" size="40" maxlength="50" <?=value($reg->kinmu_addr3)?>><br>
<img src="images/common/spacer.gif" width="35" height="10"> （例：ＳＩビル）</td>
                </tr>
                <tr class="description"> 
                  <td width="180" height="28" bgcolor="#e5eaf0">勤務先電話番号</td>
                  <td bgcolor="#FFFFFF" height="28"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <input type="text" name="kinmu_tel1" size="5" maxlength="5" <?=value($reg->kinmu_tel1)?>> - <input type="text" name="kinmu_tel2" size="5" maxlength="5" <?=value($reg->kinmu_tel2)?>> - <input type="text" name="kinmu_tel3" size="5" maxlength="4" <?=value($reg->kinmu_tel3)?>>（半角数字）</td>
                </tr>
                <tr class="description"> 
                  <td width="180" height="30" bgcolor="#e5eaf0">勤務先ＦＡＸ</td>
                  <td bgcolor="#FFFFFF"><img src="images/common/spacer.gif" width="35" height="10"> 
                    <input type="text" name="kinmu_fax1" size="5" maxlength="5" <?=value($reg->kinmu_fax1)?>> - <input type="text" name="kinmu_fax2" size="5" maxlength="5" <?=value($reg->kinmu_fax2)?>> - <input type="text" name="kinmu_fax3" size="5" maxlength="4" <?=value($reg->kinmu_fax3)?>>（半角数字）</td>
                </tr>

              </table>

                      </td>
                  </tr>
				  			  <tr> 
                <td colspan="2" bgcolor="#ffffff"><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
				  <tr>
				  <td align="center" colspan="2" bgcolor="#ffffff">
				  					<input name="image2" type="image" src="images/common/kousin.gif" alt="更新" width="130" height="32">
                          			<a href="mkk_upd_c.php"><img src="images/common/motoback.gif" border="0"></a> 

				  </td>
				  </tr>
                </table>
						</form>
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
