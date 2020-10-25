<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:見積り画面処理
'******************************************************

'=== メイン処理 ===
Dim send_num
Dim search

Set search = New CSearch
search.ReadCondition

send_num = Request.Form("send_num")

SetHeaderTitle "見積り画面"
'Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(id) {
 with (document.form1) {
		if (send_num.value == "") {
			alert("発信数を入力してください。");
			send_num.focus();
			return false;
		}
		if (isNaN(Number(send_num.value))) {
			alert("正しい発信数を入力してください。");
			send_num.focus();
			return false;
		}
		if (Number(send_num.value) > <%=send_num%>) {
			alert("発信数は発信可能数以下にしてください。");
			send_num.focus();
			return false;
		}
		if (mail_format[0].checked == false && mail_format[1].checked == false && mail_format[2].checked == false) {
			alert("メール形式を選択してください。");
			mail_format[0].focus();
			return false;
		}
		if (mail_type[0].checked == false && mail_type[1].checked == false) {
			alert("メールタイプを選択してください。");
			mail_type[0].focus();
			return false;
		}
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body onload="document.form1.send_num.focus()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="estimate1.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■お見積り内容を入力してください</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">発信可能数</td>
						<td class="n3"><input type="hidden" name="kanou_suu" value="<%=send_num%>"><%=NumFormat(send_num)%> 通</td>
					</tr>
					<tr>
						<td class="m3" width="25%">発信希望数</td>
						<td class="n3"><input type="text" name="send_num">
								通</td>
					</tr>
					<tr>
						<td class="m3">メール形式</td>
						<td class="n3">
							<input type="radio" name="mail_format" value="0">テキスト
							<input type="radio" name="mail_format" value="1">テキストHTML
							<input type="radio" name="mail_format" value="2">HTML
						</td>
					</tr>
					<tr>
						<td class="m3">メールタイプ</td>
						<td class="n3">
							<input type="radio" name="mail_type" value="CR">
								チラシ（本文200行以内）
								<input type="radio" name="mail_type" value="TG">
								電報（本文20行以内）
						</td>
					</tr>
				</table>
				<br>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value="　お見積り計算　">
	<input type="button" value="　戻る　" onclick="history.back()">
    <p class="info">はいめーるの料金体系は、配信タイプごとに一律料金となっております。</p>
    
  <table width="500" cellspacing="3" cellpadding="3">
    <tr bgcolor="#000000"> 
      <td colspan="2" class="info"> 
        <div align="center"><font color="#FFFFFF"><b>Mail to Web</b></font></div>
      </td>
      <td width="100" class="info"> 
        <div align="center"><font color="#FFFFFF"><b>通常配信</b></font></div>
      </td>
      <td width="100" class="info"> 
        <div align="center"><font color="#FFFFFF"><b>テキストHTML</b></font></div>
      </td>
      <td width="100" class="info"> 
        <div align="center"><font color="#FFFFFF"><b>HTML</b></font></div>
      </td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td width="50" class="info"> 
        <div align="right">１</div>
      </td>
      <td width="150" class="info"> 
        <div align="center">チラシ（200行）</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">\20</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">\25</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">\30</div>
      </td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td width="50" class="info"> 
        <div align="right">２</div>
      </td>
      <td width="150" class="info"> 
        <div align="center">電報（20行）</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">\8</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">－</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">－</div>
      </td>
    </tr>
  </table>
  <p>　</p><table width="500" border="0" cellspacing="3" cellpadding="3">
    <tr> 
      <td nowrap valign="top" class="info"> 
        <div align="right"><font color="336699">■</font></div>
        </td>
        <td valign="top" class="info">通常のDM配信は「チラシ」タイプとなっております。</td>
      </tr>
      <tr> 
        
      <td nowrap valign="top" class="info"> 
        <div align="right"><font color="336699">■</font></div>
        </td>
        <td valign="top" class="info">配信数に関わらず、最低広告料金は5万円からとさせていただいております。</td>
      </tr>
      <tr> 
        
      <td nowrap valign="top" class="info"> 
        <div align="right"><font color="336699">■</font></div>
        </td>
        <td valign="top" class="info">メール検索のお見積もり、ご入稿は別途ご連絡下さい。</td>
      </tr>
      <tr valign="top"> 
        
      <td nowrap class="info"> 
        <div align="right"><font color="336699">■</font></div>
        </td>
        <td class="info"> 
          <p>クリックカウンタ設定は、URLの数に関わらず全て無料となります。</p>
        </td>
      </tr>
      <tr valign="top"> 
        
      <td nowrap class="info"> 
        <div align="right"><font color="336699">■</font></div>
        </td>
        <td class="info">その他、疑問点などございましたら別途ご相談下さい。</td>
      </tr>
    </table>
<%search.WriteCondition%>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
