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
<script type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<body>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"></td>
<td width="12" rowspan="2"><img src="images/common/spacer.gif" width="18" height="10"></td>
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_PROENQ); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
                <td width="553"><img src="images/mkk_myenq/title01.gif" width="553" height="25"></td>
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
<td align="right"><a href="pe_make.php?pro_enq_no=" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Imagepro','','images/mkk_pe/bt_01_ov.gif',1)"><img src="images/mkk_pe/bt_01.gif" alt="Proアンケート新規製作" name="Imagepro" width="201" height="40" border="0" usemap="#pe_myenq_new"></a></td>
</tr>
<tr> 
<td class="footer_text style1">&nbsp;</td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="100%"  border="0" cellpadding="5" cellspacing="1" >
<form name="form1" method="post">
<input type="hidden" name="pro_enq_no">
<tr class="description"> 
<td width="5%" height="14" bgcolor="#e5eaf0">ID</td>
<td width="40%" height="14" bgcolor="#e5eaf0">アンケートタイトル </td>
<td colspan="2" bgcolor="#e5eaf0">&nbsp;</td>
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
<a href="pe_make.php?pro_enq_no=<?=$pro_enq_no?>&show=1"><?=$title?></a><br></td>
<td width="15%" height="15" bgcolor="#FFFFFF" align="center">
<?=decode_enq_status($fetch->en_status)?></td>
<td width="25%" bgcolor="#FFFFFF" align="center">
<nobr>
<?
	$btn = false;

	if ($fetch->en_status <= 1) {
		$btn = true;
?>
								<input type="button" value="作成続行" onClick="gotoPage('pe_make.php', <?=$pro_enq_no?>)">
<?
	}
	if ($fetch->en_status == 1) {
		$btn = true;
?>
								<input type="button" value="申請" onClick="gotoPage('pe_app1.php', <?=$pro_enq_no?>)">
<?
	}
	if ($fetch->en_status == 8) {
		$btn = true;
?>
								<input type="button" value="修正" onClick="gotoPage('pe_make.php', <?=$pro_enq_no?>)">
								<input type="button" value="再申請" onClick="gotoPage('pe_app1.php', <?=$pro_enq_no?>)">
<?
	}
	if ($fetch->en_status <= 4) {
		$btn = true;
?>
								<input type="button" value="削除" onClick="gotoPage('pe_erase.php', <?=$pro_enq_no?>)">
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
</nobr></td>
</tr>
<?
}
?>
</form>
</table></td>
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
