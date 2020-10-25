<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

// Myパートナークラス取得
if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	if ($pjt_id)
		$myp->read_db($pjt_id);
	else {
		$myp->marketer_id = $_SESSION['ss_owner_id'];
		$myp->save_enquete = true;
		$myp->save_search = true;

		$fetch = get_system_info('sy_myp_max_send');
		$myp->send_num = $fetch->sy_myp_max_send;
	}
}
?>
<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_NULL) ?>


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

<? marketer_menu(M_MYPARTNER); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/mkk_myenq/title01.gif" alt="MY MENU" width="553" height="25"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title01.gif" alt="Myパートナー" width="123" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title05.gif" alt="Ｍｙパートナー料金表" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td class="footer_text"><img src="images/mp_pjt_list/title06.gif" alt="基本料金" width="176" height="15"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
<tr> 
<td width="20%" align="center" bgcolor="#e5eaf0">&nbsp;</td>
<td width="40%" align="center" bgcolor="#e5eaf0">１ヶ月目</td>
<td width="40%" align="center" bgcolor="#e5eaf0">２ヶ月目以降</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">リクルート実施</td>
<td bgcolor="#FFFFFF" align="center" width="40%">50,000円</td>
<td bgcolor="#FFFFFF" align="center" width="40%">−</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">会議室開催（６名）</td>
<td bgcolor="#FFFFFF" align="center" width="40%">98,000円</td>
<td bgcolor="#FFFFFF" align="center" width="40%">98,000円</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title07.gif" alt="メンバー追加オプション" width="176" height="15"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
<tr bgcolor="#e5eaf0"> 
<td width="20%" align="center">&nbsp;</td>
<td width="40%" align="center">&nbsp;</td>
<td width="20%" align="center">１ヶ月目</td>
<td width="20%" align="center">２ヶ月目以降</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" width="20%">メンバー追加<br>
リクルート費（６名ごと）</td>
<td bgcolor="#FFFFFF" width="40%" rowspan="2">メンバーを追加（６名ごと）にかかる料金です。</td>
<td bgcolor="#FFFFFF" align="center" width="20%" rowspan="2">80,000円</td>
<td bgcolor="#FFFFFF" align="center" width="20%">−</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" width="20%">メンバー追加分<br>
会議室開催費（６名ごと）</td>
<td bgcolor="#FFFFFF" align="center" width="20%">40,000円</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title08.gif" alt="リクルートオプション" width="176" height="15"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
<tr bgcolor="#e5eaf0"> 
<td width="20%" align="center">&nbsp;</td>
<td width="40%" align="center">&nbsp;</td>
<td width="20%" align="center">１ヶ月目</td>
<td width="20%" align="center">２ヶ月目以降</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">リクルート代行費</td>
<td bgcolor="#FFFFFF" width="40%">事務局でリクルートを代行する場合の費用です。</td>
<td bgcolor="#FFFFFF" align="center" width="20%">30,000円</td>
<td bgcolor="#FFFFFF" align="center" width="20%">−</td>
</tr>
<tr>
  <td bgcolor="#e5eaf0" align="center">追加属性</td>
  <td bgcolor="#FFFFFF">対象条件設定で「子供の有無」「同居家族」を対象者絞込み条件としてご利用いただいた場合の料金です。</td>
  <td bgcolor="#FFFFFF" align="center">5,000円/1属性</td>
  <td bgcolor="#FFFFFF" align="center">&nbsp;</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">画像加工</td>
<td bgcolor="#FFFFFF" width="40%">リクルートアンケートで画像を見せる場合に、事務局で画像を加工する費用です。</td>
<td bgcolor="#FFFFFF" align="center" width="20%">3,000円</td>
<td bgcolor="#FFFFFF" align="center" width="20%">−</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">サーバー利用料</td>
<td bgcolor="#FFFFFF" width="40%">リクルートアンケートで画像を見せる場合のサーバー使用料です。</td>
<td bgcolor="#FFFFFF" align="center" width="20%">5,000円</td>
<td bgcolor="#FFFFFF" align="center" width="20%">−</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">スピードチャージ</td>
<td bgcolor="#FFFFFF" width="40%">１１時を過ぎての申請で、当日に発信したい場合にはスピードチャージをいただきます。ただし１６：００以降の申請の場合は翌日の１８：００発信となります。</td>
<td bgcolor="#FFFFFF" align="center" width="20%">&nbsp;&nbsp;10,000円</td>
<td bgcolor="#FFFFFF" align="center" width="20%">−</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title09.gif" alt="会議室運営オプション" width="176" height="15"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
<tr bgcolor="#e5eaf0"> 
<td width="20%" align="center">&nbsp;</td>
<td width="40%" align="center">&nbsp;</td>
<td width="20%" align="center">１ヶ月目</td>
<td width="20%" align="center">２ヶ月目以降</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">フロー作成代行</td>
<td bgcolor="#FFFFFF" width="40%">事務局でインタビューフローを作成する場合の費用です。</td>
<td bgcolor="#FFFFFF" align="center" width="40%" colspan="2">150,000円</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">モデレーション代行</td>
<td bgcolor="#FFFFFF" width="40%">事務局が司会進行を行う場合にかかる費用です。</td>
<td bgcolor="#FFFFFF" align="center" width="20%">100,000円</td>
<td bgcolor="#FFFFFF" align="center" width="20%">100,000円</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">画像加工</td>
<td bgcolor="#FFFFFF" width="40%">会議室内で画像を見せる場合に、事務局で画像を加工する費用です。</td>
<td bgcolor="#FFFFFF" align="center" width="40%" colspan="2">3,000円</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">サーバー利用料</td>
<td bgcolor="#FFFFFF" width="40%">会議室内で画像を見せる場合のサーバー使用料です。</td>
<td bgcolor="#FFFFFF" align="center" width="40%" colspan="2">5,000円</td>
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

<? marketer_footer () ?>
