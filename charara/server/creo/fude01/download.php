<?
/******************************************************
' System :「きゃららFactory」クレオキャンペーン
' Content:入力画面
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

if (!$id)
	$id = $QUERY_STRING;

if ($id) {
	$sql = "SELECT df_name1,df_name2,nd_mail_addr,nd_status,nd_photo_file,nd_nigaoe_file,nd_name_text"
		. " FROM d_fude01 LEFT JOIN d_fude01_nigaoe ON df_seq_no=fn_seq_no"
		. " LEFT JOIN t_nigaoe_data ON fn_nigaoe_id=nd_nigaoe_id"
		. " WHERE (nd_status=8 OR nd_status=9) AND nd_random_id=" . sql_char($id);
	$result = db_exec($sql);
	if (pg_numrows($result))
		$fetch = pg_fetch_object($result, 0);
	else
		redirect('error.php');
}

// ダウンロード日時書き込み
if ($fetch->nd_status == 8) {
	$sql = "UPDATE t_nigaoe_data SET nd_status=9,nd_download_date='now' WHERE nd_random_id=" . sql_char($id);
	db_exec($sql);
}

// 写真画像URL取得
$url_photo = get_system_info('url_photo');

// 似顔絵画像URL取得
$url_nigaoe = get_system_info('url_nigaoe');
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>クレオにがおえキャンペーン：ご当選おめでとうございます！！</title>
<script language="JavaScript">
<!--
mac=(navigator.appVersion.indexOf('Mac')!=-1)?true:false;
ie=(navigator.appName.charAt(0)=="M")?true:false;
ns=(navigator.appName.charAt(0)=="N")?true:false;

if(mac){
	fName="css/mac.css";
}
else{
	if(ie){
	fName="css/win_ie.css";
	}
	else if(ns){
	fName="css/win_ns.css";
	}
}

if(fName!=null){
	document.write("<link rel=stylesheet href='"+fName+"' type='text/css'>");
}
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<span class="xx-large"><span class="gyokan125">
<br>
<br>
<b><?=$fetch->df_name1 . " " . $fetch->df_name2?></b>さん<br>
<font color="#FF0000"><b>似顔絵をダウンロードできます！</b></font></span></span><br>
<br>
<table width="640" border="0">
	<tr>
		<td><div align="center"><img src="img/step.gif" alt="にがおえ作成のステップ" width="604" height="297"></div></td>
	</tr>
</table>
<br><br>
<hr width=500>
<table width="500" border="0" cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=2 align="center"><b>ニックネーム</b>： <?=$fetch->nd_name_text?> さん<br><br></td>
	</tr>
	<tr>
		<td align="center" width=50%>【写真】</td>
		<td align="center" width=50%>【にがおえ】</td>
	</td>
	<tr>
		<td align="center" width=50%><img src="<?=$url_photo?><?=$fetch->nd_photo_file?>" width=180 border=1 alt="顔写真"></td>
		<td align="center" width=50%><img src="<?=$url_nigaoe?><?=$fetch->nd_nigaoe_file?>" width=180 height=180 border=1 alt="似顔絵"></td>
	</tr>
	<tr><td><br></td></tr>
</table>
<hr width=500>
<br><br>
<table width="500" border="1" cellpadding=3 cellspacing=1 bordercolordark="#3399ff" bordercolor="#99ccff">
	<tr>
		<td align="center">
			<table width=97% height=90%>
				<tr>
					<td colspan=2><span class="medium">　＝ ダウンロードの仕方 ＝</span></td>
				</tr>
				<tr>
					<td valign="top"><span class="medium">　1. </span></td>
					<td><span class="medium">【にがおえ】の画像上でマウスの右ボタンをクリックすると、ポップアップメニューが<br>でます。</span></td>
				</tr>
				<tr>
					<td valign="top"><span class="medium">　2. </span></td>
					<td><span class="medium">メニューの中の「名前をつけて画像を保存」を選び、お好きな名前をつけて保存して<br>下さい。</span></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br><br>
<table width=500>
	<tr>
		<td><div align="center">
			<span class="medium"><font color="#009933">
			☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★ </font><br>
			似顔絵キャンペーン事務局<br>
			E-mail : <a href="mailto:creo_camp@chrr.jp">creo_camp@chrr.jp</a>
			<br>
			営業時間 10:00-12:00, 13:00-17:00（土日曜・祝祭日を除く） <br>
			<font color="#009933">☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★ </font><br><br></span></span></div>
		</td>
	</tr>
</table>

</div>
</body>
</html>