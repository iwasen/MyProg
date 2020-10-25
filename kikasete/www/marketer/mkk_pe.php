<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション変数削除
unset($_SESSION['ss_pro_enq']);
session_unregister('ss_pro_enq');
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>

<script language=javascript>
<!--
function gotoPage(url, pro_enq_no) {
	var f = document.form1;
	f.action = url;
	f.pro_enq_no.value = pro_enq_no;
	f.submit();
}
//-->
</script>


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

<? marketer_menu(M_MYENQ); ?>

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
                <td><img src="images/mkk_pe/title_01.gif" width="123" height="28"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
                <td><img src="images/mkk_pe/title_02.gif" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="5"></td>
</tr>
<tr> 
                <td class="footer_text"><img src="images/common_my/bt_03.gif" width="12" height="11"><a href="mk/service/mk_myenq.php" target="_blank">Myアンケートについてはこちら</a>（新しくブラウザが開きます）</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"> </td>
</tr>
<tr> 
<td height="90" bgcolor="#e5eaf0"> 
<div align="center"> 
<table border="0" cellspacing="0" cellpadding="0">
<tr> 
                        <td><a href="pdf/myenq_manual_5_1.pdf" target="_blank"><img src="images/mkk_myenq/mark_pdf.gif" width="37" height="32" border="0"></a></td>
                        <td><a href="pdf/myenq_manual_5_1.pdf" target="_blank" onMouseOver="MM_swapImage('Image54','','images/mkk_myenq/bt_01_ov.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/mkk_myenq/bt_01.gif" alt="設定マニュアル" name="Image54" width="148" height="31" border="0"></a></td>
<td><img src="images/common/spacer.gif" width="10" height="10"> </td>
<td> <a href="myenq_price.php" onMouseOver="MM_swapImage('Image55','','images/mkk_myenq/bt_02_ov.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/mkk_myenq/bt_02.gif" alt="ご利用料金について" name="Image55" width="148" height="31" border="0"></a></td>
<td><img src="images/common/spacer.gif" width="10" height="10"> </td>
<td><a href="mkk_contact.php" onMouseOver="MM_swapImage('Image56','','images/mkk_myenq/bt_03_ov.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/mkk_myenq/bt_03.gif" alt="エージェントに相談" name="Image56" width="148" height="31" border="0"></a></td>
</tr>
<tr>
<td><img src="images/common/spacer.gif" width="1" height="1"></td>
                        <td class="footer_text" colspan="3"> Ver5.1（2005/8/30更新）
                        </td>
</tr>
<tr>
<td><img src="images/common/spacer.gif" width="1" height="1"></td>
                        <td class="footer_text" colspan="3"> ※設定マニュアルは、右クリックで「対象をファイルに保存」を選んでください。 
                        </td>
</tr>
</table>
</div>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
<tr> 
                <td><img src="images/mkk_pe/title_03.gif" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td><img src="images/mkk_myenq/title05.gif" alt="Ｍｙページから簡単設定" width="161" height="21"></td>
</tr>
<tr> 
<td> 
<p>１０問までのアンケートなら、思ったその日にすぐ実施できます。<br>
選択回答方式、自由回答方式かの設問タイプを選び、質問文・選択肢を入れるだけで自動的にアンケートが作成されます。また、対象者の絞り込みも可能なので、聞きたいその人に聞くことができます。<br>
<br>
<span class="footer_text">※標準ご利用は３問までとなります。オプションサービスとして質問数と発信数の追加ができます。<br>
(最大１０問、３０００通発信まで)</span> </p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td><img src="images/mkk_myenq/title06.gif" alt="回答結果をチェック" width="161" height="21"></td>
</tr>
<tr> 
<td> 
<p>リアルタイム集計・データダウンロードがあなたのMyページからおこなえるので、回答状況、大まかな結果などをすぐに把握していただくことができます。</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"> </td>
</tr>
<tr> 
<td><a href="pe_make.php?pro_enq_no=" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image63','','images/mkk_pe/bt_01_ov.gif',1)"><img src="images/mkk_pe/bt_01.gif" alt="Proアンケート新規製作" name="pe_myenq_new" width="201" height="40" border="0" usemap="#pe_myenq_new"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
<tr> 
<td><img src="images/mkk_myenq/title07.gif" alt="アンケート結果" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td class="footer_text style1">※下記の【削除】ボタンを押すとアンケートがすべて削除されます。<br>
削除される場合にはご注意くださいますようお願い申し上げます。</td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="100%"  border="0" cellpadding="5" cellspacing="1" >
<form name="form1" method="post">
<input type="hidden" name="pro_enq_no">
<tr class="description"> 
<td width="15%" height="14" bgcolor="#e5eaf0">ＩＤ</td>
<td width="60%" height="14" bgcolor="#e5eaf0">名称 </td>
<td colspan="2" bgcolor="#e5eaf0">状態 </td>
</tr>

<?
$marketer_id = $_SESSION['ss_marketer_id'];
$owner_id = $_SESSION['ss_owner_id'];

// 引き継がれたマーケターが自分が作成したProアンケートのみを見れるようにする処置
$where = "WHERE mep_marketer_id=$owner_id AND en_status<>9";
if ($_SESSION['ss_marketer_type'] == MK_PAY && $marketer_id != $owner_id)
	$where .= " AND mep_creator_id=$marketer_id";

// Proアンケート一覧取得
$sql = "SELECT mep_enquete_id,mep_pro_enq_no,en_status,en_enq_type,en_title"
		. " FROM t_pro_enquete LEFT JOIN t_enquete ON mep_enquete_id=en_enquete_id"
		. " $where"
		. " ORDER BY mep_pro_enq_no DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$title = ($fetch->en_title == '') ? 'タイトル未設定' : htmlspecialchars($fetch->en_title);
	$pro_enq_no = $fetch->mep_pro_enq_no;
?>

<tr class="description">
<!-- 2003.12.18 井上　ID追加 -->
<td height="15" bgcolor="#FFFFFF"><?=$fetch->mep_enquete_id?></td>
<td height="15" bgcolor="#FFFFFF">
<a href="pe_make.php?pro_enq_no=<?=$pro_enq_no?>&show=1"><?=$title?></a><br>
<?=decode_enq_type($fetch->en_enq_type)?>
</td>
<td width="15%" height="15" bgcolor="#FFFFFF" align="center">
<?=decode_enq_status($fetch->en_status)?>
</td>
<td width="25%" bgcolor="#FFFFFF" align="center">
<nobr>
<?
	$btn = false;

	if ($fetch->en_status <= 1) {
		$btn = true;
?>
								<input type="button" value="作成続行" onclick="gotoPage('pe_make.php', <?=$pro_enq_no?>)">
<?
	}
	if ($fetch->en_status == 1) {
		$btn = true;
?>
								<input type="button" value="申請" onclick="gotoPage('pe_app1.php', <?=$pro_enq_no?>)">
<?
	}
	if ($fetch->en_status == 8) {
		$btn = true;
?>
								<input type="button" value="修正" onclick="gotoPage('pe_make.php', <?=$pro_enq_no?>)">
								<input type="button" value="再申請" onclick="gotoPage('pe_app1.php', <?=$pro_enq_no?>)">
<?
	}
	if ($fetch->en_status <= 4) {
		$btn = true;
?>
								<input type="button" value="削除" onclick="gotoPage('pe_erase.php', <?=$pro_enq_no?>)">
<?
	}
	if ($fetch->en_status == 5 || $fetch->en_status == 6 || $fetch->en_status == 7) {
		$btn = true;
?>
								<a href="pe_enqdet1.php?pro_enq_no=<?=$pro_enq_no?>">結果</a>
<?
	}
	if (!$btn) {
?>
								−
<?
	}
?>
</nobr>
</td>
</tr>
<?
}
?>

</form>
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


<? marketer_footer (BLUE) ?>
