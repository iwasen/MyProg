<?
$top = '../../..';
$inc = "$top/inc";
include("$inc/mk_header.php");
?><? marketer_header('ポジティブ・パーセプション・マップ', PG_SURVEY) ?>
<a name='top'></a>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellpadding="0" cellspacing="0" bgcolor="FFFFFF">
  <tr> 
    <td><img src="../../../images/common/spacer.gif" width="10" height="10" /></td>
  </tr>
<tr> 
<td class="map"><a href="../../../index.php">HOME</a>　＞　<a href="../mk_survey.php">きかせて･net調べ</a>　＞　<a href="../mk_ppm.php">PPM(ポジティブ・パーセプション・マップ)</a>　＞　PPM詳細</td>
</tr>
<tr>
<td><img src="../../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
  <tr> 
    <td align="right" class="footer_text"> <img src="../../../images/common_my/bt_03.gif" width="12" height="11" align="middle"><a href="../mk_ppm.php">ポジティブ・パーセプション・マップ一覧に戻る</a> <img src="../../../images/common/spacer.gif" width="90" height="0">    </td>
  </tr>
  <tr> 
    <td><img src="../../../images/common/spacer.gif" width="10" height="20" /></td>
  </tr>
  <tr> 
    <td valign="top" align="center"> 
      <?
	include("result_inc/ppm_" . $_GET['page_id'] . ".inc");				// PPM
?>    </td>
  </tr>
  <tr> 
    <td align="center">
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr> 
          <td rowspan="2" align="right" width="45%">
<a href="mailto:k-info@kikasete.net?subject=%81%a1%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9"><img src="../../../images/survey/ppm/ppm_mail_a.gif" alt="" width="197" height="81" border="0" onmouseover="this.src='../../../images/survey/ppm/ppm_mail_b.gif'" onmouseout="this.src='../../../images/survey/ppm/ppm_mail_a.gif'"></a>          　</td>
          <td width="55%" valign="top"><a href="../mk_ppm.php"><img src="../../../images/survey/ppm/ppm_about_ppm.gif" alt="" width="300" height="35" border="0"></a></td>
        </tr><tr>
          <td valign="top"><a href="../../kkstfrom/mk_faq.php#3"><img src="../../../images/survey/ppm/ppm_about_rp.gif" alt="" width="300" height="35" border="0"></a></td>
        </tr>
      </table>    </td>
  </tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr>
  <td>
  <div style="background-color:#FFFFFF; padding:10px; margin:20px; border-color:#e5eaf0; border-style:solid; border-width:5px;">
<img src="../../../images/top/center_img02.gif" alt="オープンデータ" align="right" hspace="5" />※　各オープンデータのＴＶ・新聞・雑誌・HP等への掲載や、営業資料等でのご利用に関しましては、<br />
メール又はお電話にてお問い合わせください。調査結果データ販売も行っております。<br />
料金につきましては<a href="../mk_survey_price.php">こちら</a>をご覧ください。
	
  </div></td>
</tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
  <tr> 
    <td align="right"><a href="#top"><img src="../../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a><img src="../../../images/common/spacer.gif" width="50" height="1"></td>
  </tr>
  <tr> 
    <td><img src="../../../images/common/spacer.gif" width="10" height="30"></td>
  </tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>

<? marketer_footer(NOBLUE); ?>
