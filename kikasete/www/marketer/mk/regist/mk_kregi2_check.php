<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header.php");
include("$inc/mk_error.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('mk_kregi1.php');
$reg = &$_SESSION['ss_regist'];

// フォームからデータ取得
$reg->kinmu_name = trim($kinmu_name);
$reg->kinmu_unit = trim($kinmu_unit);
$reg->kinmu_post = trim($kinmu_post);
$reg->kinmu_zip1 = get_zip($kinmu_zip1);
$reg->kinmu_zip2 = get_zip($kinmu_zip2);
$reg->kinmu_area = $kinmu_area;
$reg->kinmu_addr1 = trim($kinmu_addr1);
$reg->kinmu_addr2 = trim($kinmu_addr2);
$reg->kinmu_addr3 = trim($kinmu_addr3);
$reg->kinmu_tel1 = get_tel($kinmu_tel1);
$reg->kinmu_tel2 = get_tel($kinmu_tel2);
$reg->kinmu_tel3 = get_tel($kinmu_tel3);
$reg->kinmu_fax1 = get_tel($kinmu_fax1);
$reg->kinmu_fax2 = get_tel($kinmu_fax2);
$reg->kinmu_fax3 = get_tel($kinmu_fax3);

// 入力チェック
if ($reg->kinmu_name == '')
	$msg[] = '会社名を入力してください。';

if ($reg->kinmu_zip1 == '' || $reg->kinmu_zip2 == '')
	$msg[] = '勤務先郵便番号を入力してください。';
elseif (!check_zip($reg->kinmu_zip1, $reg->kinmu_zip2))
	$msg[] = '勤務先郵便番号が正しく入力されていません。';
else
	$kinmu_zip_ok = true;

if ($reg->kinmu_area == '')
	$msg[] = '勤務先住所（都道府県）を入力してください。';
else
	$kinmu_area_ok = true;

if ($kinmu_zip_ok && $kinmu_area_ok && !check_zip_area($reg->kinmu_zip1 . $reg->kinmu_zip2, $reg->kinmu_area))
	$msg[] = '勤務先郵便番号と勤務先住所（都道府県）が一致していません。';

if ($reg->kinmu_addr1 == '')
	$msg[] = '勤務先住所（市区）を入力してください。';

if ($reg->kinmu_addr2 == '')
	$msg[] = '勤務先住所（町村）を入力してください。';

if ($reg->kinmu_tel1 == '' || $reg->kinmu_tel2 == '' || $reg->kinmu_tel3 == '')
	$msg[] = '勤務先電話番号を入力してください。';
elseif (!check_tel($reg->kinmu_tel1, $reg->kinmu_tel2, $reg->kinmu_tel3))
	$msg[] = '勤務先電話番号が正しく入力されていません。';

if ($reg->kinmu_fax1 != '' || $reg->kinmu_fax2 != '' || $reg->kinmu_fax3 != '') {
	if (!check_tel($reg->kinmu_fax1, $reg->kinmu_fax2, $reg->kinmu_fax3))
		$msg[] = '勤務先FAX番号が正しく入力されていません。';
}

if (!$msg)
	redirect('mk_kregi3.php');
?>
<? marketer_header('基本情報の登録', PG_REGIST) ?>

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
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
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
                <td align="center"><img src="../../images/regist/sequence3.gif" width="539" height="30"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td width="578">
                  <? error_msg($msg, 'mk_kregi2.php') ?>
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
