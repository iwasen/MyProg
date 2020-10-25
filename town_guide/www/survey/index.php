<?
$inc = '../inc';
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");

// 終了日時
define('END_DATE', '2005-02-14 09:00:00');

// パラメータ取得
$user_id = $_GET['id'];
$group_id = $_GET['gkey'];

// パラメータ存在チェック
if ($user_id == "" || $group_id == "")
	redirect('end.html');

// 終了日時チェック
if (time() > strtotime(END_DATE))
	redirect('end.html');

// 回答済みチェック
$sql = "SELECT COUNT(*) FROM t_ans_user WHERE au_user_id=" . sql_char($user_id);
if (db_fetch1($sql) != 0)
	redirect('end.html');

// 回答者数チェックと年代・地域取得
$sql = "SELECT gr_mokuhyo_num,ar_todofuken,gr_nendai"
		. " FROM m_group"
		. " JOIN m_area ON ar_area_id=gr_area_id"
		. " WHERE gr_group_id=" . sql_char($group_id);
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	// 同一グループの回答者数取得
	$sql = "SELECT COUNT(*) FROM t_ans_user WHERE au_ans_group_id=" . sql_char($group_id);
	$answer_num = db_fetch1($sql);

	// 回答者数チェック
	if ($answer_num >= $fetch->gr_mokuhyo_num)
		redirect('end.html');

	// 地域名と年代を取得
	$todofuken = $fetch->ar_todofuken;
	$nendai = mb_convert_kana($fetch->gr_nendai, 'N');
} else
	redirect('end.html');

// URLパラメータをクッキーに保存
setcookie('c_user_id', $user_id);
setcookie('c_group_id', $group_id);

// セッション初期化
session_start();
$_SESSION['s_enquete'] = new enquete_class($user_id, $group_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta name="enqweb" CONTENT="Ver3.0">
<title>アンケートトップページ</title>
<script type="text/javascript">
<!--
function go_enq(pm) {
var win, opt;
	opt = 'menubar=no, scrollbars=yes, location=no, resizable=yes, status=yes, toolbar=no';
  win = window.open('ctl.php', 'enq', opt);
  win.focus();
	document.all.go.style.display = "none";
}
// faq オープン
function openfaq() {
	window.open('faq.html','','height=500,width=585,scrollbars=1,resizable=1')
}
//-->
</script>
<style type="text/css">
<!--
	.enqtitle{
		font-family:  "Osaka";
		font-size: 160%;
		font-weight: bold;
		color: #ffffff;
		line-height: 200%;
	}
	
	.t100{
		font-family:  "Osaka";
		font-size: 90%;
		line-height: 140%;
	}
	
	a:link{
		color : #0000ff;
		text-decoration: underline;
		}
	a:visited{
		text-decoration: none;
		color: #0000a0;
	}
	a:hover{
		color : #ff0000;
		text-decoration: underline;
		}
	a:active{
		color : #ff0000;
		text-decoration: underline;
		}
	}
-->
</style>
</head>
<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">
<div align="center"> 
<table border="0" cellspacing="1" cellpadding="0" width="750" bgcolor="#0b06ae">
	<tr> 
			<td> <table border="0" cellspacing="0" cellpadding="0" width="750" bgcolor="#ffffff">
				<tr> 
					<td> 
						<!-- title -->
						<table bgcolor="#0b06ae" align="center" width="100%">
							<tr> 
								<td class="enqtitle" align="center">タウンガイドに関する応募アンケート</td>
							</tr>
						</table>
						<!-- title -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- explain -->
						<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td align="center" class="t100"> 
								</td>
							</tr>
						</table>
						<!-- explain -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 1 -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
							<tr> 
								<td><img src="images/midashi1.gif" alt="アンケートにご回答いただく際の諸注意" width="500" height="35" border="0" hspace="0" vspace="0"></td>
							</tr>
						</table>
						<!-- 1 -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 1_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100"><b>【１．期間】</b></td>
							</tr>
							<tr> 
								<td class="t100"> 有効回答期間は<b><font color="#FF0000">２００５年２月１４日（月） ９時</font></b>までです。 </td>
							</tr>
						</table>
						<!-- 1_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 1_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100"><b>【２．有効回答】</b></td>
							</tr>
							<tr> 
								<td class="t100">有効回答は、下記条件に当てはまり、このアンケートのURLが記載されたメールを受け取ったご本人さま、<br>
								１回限りとさせていただきます。</td>
							</tr>
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
							<tr> 
									<td class="t100"> <table align="center" width="80%" border="0" cellspacing="0" cellpadding="0">
										<tr> 
											<td class="t100" colspan="2">■■□　条件　□■■</td>
										</tr>
										<tr> 
											<td width="10%" class="t100">&nbsp;</td>
											<td width="90%" class="t100" valign="top"> 
												<br>
												&nbsp;&nbsp;&nbsp;・<?=$todofuken?>にお住まいの方
												<br>
												&nbsp;&nbsp;&nbsp;・<?=$nendai?>代の方
												<br>
												&nbsp;&nbsp;&nbsp;・このメールを、センターまたは事務局から直接受取った方 <br>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;※上記条件を全て満たす方であればＯＫ！！ 
											<br>                        </td>
										</tr>
									</table></td>
							</tr>
						</table>
						<!-- 1_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 1_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100"><b>【３．謝礼】</b></td>
							</tr>
							<tr> 
								<td class="t100"> 
									<font color=red>有効回答者にご案内しております応募謝礼</font>を差し上げます。
									<br>
									<br>回答の謝礼は、記述漏れなくご回答いただいたことを確認した後差し上げます。<br>
									記入もれや、回答内容に不備があった場合などは謝礼をお支払いすることができなくなります。<br>
									ご回答の際は、ご注意いただけますようお願いいたします。<br>
								<br>                  </td>
							</tr>
						</table>
						<!-- 1_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 2 -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
							<tr> 
								<td><img src="images/midashi2.gif" alt="個人情報についての取り扱い" width="500" height="35" border="0" hspace="0" vspace="0"></td>
							</tr>
						</table>
						<!-- 2 -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 2_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100"><b>【個人情報】</b></td>
							</tr>
							<tr> 
								<td class="t100"> 
									<br>ＷＥＢアンケート本調査内でお聞きする個人情報は、メンバーの方へのポイント付与作業以外には<br>使用しないことをお約束いたします。<br>
								ご回答いただいた内容は、○○％といった統計数字として集計いたしますので、<br>ご迷惑をおかけすることは一切ございません。<br>                  </td>
							</tr>
						</table>
						<!-- 2_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 3 -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
							<tr> 
								<td><img src="images/midashi3.gif" alt="このアンケートシステムについて" width="500" height="35" border="0" hspace="0" vspace="0"></td>
							</tr>
						</table>
						<!-- 3 -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
									<td class="t100"><table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
										<tr>	
											<td class="t100">今回、ご案内させていただきますＷｅｂアンケートではJavaScriptによる回答チェックを行っております。<br>
												そのためブラウザにバージョンによっては不具合がでる場合もございますのであらかじめご了承ください。<br>
												ご回答いただく際に、ブラウザのバージョンをご確認の上、ご回答をお願いいたします。<br>
												(あなたのお使いの環境については下の「あなたのパソコン環境」をご参照ください。)  
											</td>
										</tr>

										<tr>
											<td class="t100">
											</td>
										</tr>
										<tr>
											<td class="t100">
												<table align="center" width="80%" border="0" cellspacing="0" cellpadding="0">
													<tr> 
														<td class="t100" colspan="2">【推奨ブラウザ】</td>
													</tr>
													<tr> 
														<td class="t100" width="10%"> </td>
														<td class="t100" width="90%">・Netscape　Navigator　7.0以降</td>
													</tr>
													<tr> 
														<td class="t100"> </td>
														<td class="t100">・Microsoft　Internet　Explorer　5.5以降</td>
													</tr>
													<tr> 
														<td class="t100" colspan="2">【不具合が発生する可能性があるブラウザ】</td>
													</tr>
													<tr> 
														<td class="t100"> </td>
														<td class="t100">・Netscape Communicator4.x</td>
													</tr>
													<tr> 
														<td class="t100"> </td>
														<td class="t100">・Netscape　Naviator　6.X</td>
													</tr>
													<tr> 
														<td class="t100"> </td>
														<td class="t100">・＜MacOS＞で　Microsoft　Internet　Explorer　をお使いの方</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td class="t100"> </td>
										</tr>
										<tr>
											<td valign="middle" class="t100">※なお、このアンケートをご回答いただくにあたって
												<img src="images/next.gif" alt="次の質問ボタン" width="69" height="21" border="0" hspace="0" vspace="0">
											ボタンは必ず<font color="#FF0000">【１回だけ】</font>クリックしてください。</td>
										</tr>
										<tr>
											<td valign="middle" class="t100">※２回以上連続でクリックすると、データが送信されない場合があります。 </td>
										</tr>

										<tr>
											<td class="t100">※また、アクセスが集中している場合に動作が遅くなることがあります。あらかじめご了承下さい。</td>
										</tr>
										<tr>
											<td class="t100" align="right"><a href="javascript:openfaq()" title="その他のよくある質問はこちら">＞＞その他のよくある質問はこちら</a></td>
										</tr>
										<tr>
											<td class="t100"> </td>
										</tr>
										<tr>
											<td class="t100"><hr size="1" width="80%" align="center" color="#0b06ae" noshade></td>
										</tr>
										<tr>
											<td class="t100"> </td>
										</tr>
										<tr>
											<td class="t100">上記のご案内や「その他の良くある質問」以外で、ご不明な点／ご不都合な点などありましたら、下記メールアドレスまでお問い合わせくださいますよう、お願い申し上げます。 
											<br></td>
										</tr>
										<tr>
											<td class="t100" align="right"><a href="mailto:e3info@xxxxxxx.co.jp?subject=%81w%83%5e%83E%83%93%83K%83C%83h%81x%82%c9%8a%d6%82%b7%82%e9%92%b2%8d%b8%8e%96%96%b1%8b%c7">『タウンガイド』に関する調査事務局</td>
										</tr>
										<tr>
											<td class="t100"> </td>
										</tr>
										<tr>
											<td class="t100"><hr size="1" width="80%" align="center" color="#0b06ae" noshade></td>
										</tr>
									</table></td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 3_detail -->
						<!-- 3_detail -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 4 -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
							<tr> 
								<td><img src="images/midashi4.gif" alt="あなたのパソコン環境" width="500" height="35" border="0" hspace="0" vspace="0"></td>
							</tr>
						</table>
						<!-- 4 -->
						<!-- spacer -->
						<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">&nbsp;</td>
							</tr>
						</table>
						<!-- spacer -->
						<!-- 4_detail -->
						<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
							<tr> 
								<td class="t100">お使いのOSは<b><font color="#0066CC"> 
                    <Script language="JavaScript">
<!--
var agent = navigator.userAgent ;
if ( agent.indexOf('Win') != -1 ){
userOs = "Windows";
}
else if ( agent.indexOf('Mac') != -1 ){
userOs = "MacOS" ;
}
else{
userOs = "その他のOS" ;
}
document.write(userOs); 
//-->
</Script>
								</font></b> です。</td>
							</tr>
							<tr> 
								<td class="t100">お使いのブラウザは<b><font color="#0066CC"> 
                    <Script language="JavaScript">
<!--
var ua = navigator.userAgent;

if (ua.match(/Opera[\/\s](\d\.\d+)/)) {				//(\d\.\d+)をRegExp.$1で参照
	document.write("Opera "+RegExp.$1);			//Operaの処理
}
else if (ua.match(/MSIE (\d\.\d+)/)) {
	document.write("Internet Explorer"+RegExp.$1);		//IEの処理
}
else if (ua.match(/Mozilla\/(4\.[5678]\d?)/)) {
	document.write("Netscape Communicator"+RegExp.$1);	//Netscape Communicator4.xの処理
}
else if (ua.match(/Netscape6\/(\d\.\d(\.\d)?)/)) {
	document.write("Netscape"+RegExp.$1);			//Netscape6の処理
}
else if (ua.match(/Netscape\/(7[\.\d]+)/)) {
	document.write("Netscape"+RegExp.$1);			//Netscape7の処理
}
else if (ua.match(/Gecko/)) {
	document.write("Mozilla");				//Mozillaの処理
}
else {
	document.write("その他のブラウザ");				//上記以外のブラウザの処理
}
// -->
</script>
								</font></b><font size="-1">です。(Mac版のＩＥは表示されない場合があります）</font></td>
							</tr>
							<tr> 
								<td class="t100">ブラウザのクッキーは<b><font color="#0066CC"> 
                    <script language="JavaScript">
<!--
value1="ok";
setCookie("COOKIE",value1);
function setCookie(key,val){
tmp=key+"="+escape(value1)+";";
document.cookie=tmp;
}
// -->

<!--
value1=getCookie("COOKIE");
if(value1==""){value="<FONT COLOR='#cc0000'>無効になっています。</FONT>";}
if(value1=="ok"){value="有効になっています。";}
function getCookie(key){
tmp=document.cookie+";";
tmp1=tmp.indexOf(key,0);
if(tmp1!=-1){
tmp=tmp.substring(tmp1,tmp.length);
start=tmp.indexOf("=",0) + 1;
end = tmp.indexOf(";",start);
return(unescape(tmp.substring(start,end)));
}
return("");
}
document.write(value);
// -->
</script>
								</font></b></td>
							</tr>
							<tr> 
								<td class="t100">このアンケートにお答えになるためには、JavaScriptとクッキーが有効であることが必要です。</td>
							</tr>
						</table>
						<!-- 4_detail -->
						<!-- submit -->
						<form name="form1">
						<table width="100%" align="center">
							<tr id="go"> 
								<td align="center"><input TYPE="button" VALUE="アンケート開始" onclick="javascript:go_enq()"></td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
			</table></td>
	</tr>
</table>
</div>
</body>
</html>
