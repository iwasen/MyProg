<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
Dim client_id

client_id = Session("client_id")
If IsEmpty(client_id) Then
  Session("client_next") = "yoyaku.asp"
  Response.Redirect "login.asp"
End If

Dim browser
If InStr(Request.ServerVariables("HTTP_USER_AGENT"), "MSIE") > 0 Then
  browser = "IE"
Else
  browser = "NN"
End If
%>
<html>
<head>
<title>めるめる・net 広告主向け予約修正ページ</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<LINK rel="stylesheet" href="../melmel.css">
<LINK rel="stylesheet" href="client.css">
<SCRIPT LANGUAGE=javascript>
<!--
function DateToString(date) {
  var month,day;
  month = "0" + (date.getMonth() + 1);
  day = "0" + date.getDate();
  return date.getFullYear() + "/" + month.substr(month.length-2,2) + "/" + day.substr(day.length-2,2);
}
function OnChange_koukoku_shuu() {
  with (document.form1) {
    var date;
    if (koukoku_shuu.value != "") {
      date = new Date(koukoku_shuu.value.substr(6));
      pub_start.value = DateToString(date);
      date.setTime(date.getTime() + 6*24*60*60*1000);
      pub_end.value = DateToString(date);
      date.setTime(date.getTime() + 1*24*60*60*1000);
      limit_date.value = DateToString(date);
      pub_start.focus();
    }
  }
}
function OnSubmit_form1() {
  var array,lines;
  with (document.form1) {
    if (koukoku_name.value == "") {
      alert("広告名を入力してください。");
      koukoku_name.focus();
      return false;
    }
    if (koukoku_shuu.value == "") {
      alert("広告掲載週を選択してください。");
      koukoku_shuu.focus();
      return false;
    }
    if (busuu.value == "") {
      alert("発行部数を入力してください。");
      busuu.focus();
      return false;
    }
    if (koukoku_msg.value != "") {
      array = koukoku_msg.value.split("\r\n");
      for (lines = array.length; lines >= 0 && array[lines-1] == ""; lines--);
      if (lines != 5 && lines != 10) {
        alert("広告文の行数は５行または１０行にしてください。");
        koukoku_msg.focus();
        return false;
      }
    }
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body bgcolor="#ff9999" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="">
<TABLE width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <TR>
    <TD colspan="3">
      <TABLE bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <TR> <!-- Shim row, height 1. -->
          <TD><IMG src="../images/shim.gif" width="252" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="348" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="1" border="0"></TD>
        </TR>
        <TR><!-- row 1 -->
          <TD rowspan="3" valign="top"><A href="../index.html"><IMG name="head_logo" src="../images/head_logo.gif" width="252" height="96" border="0" alt="めるめる・net ロゴ"></A></TD>
          <TD valign="top"><IMG src="../images/shim.gif" width="348" height="26" border="0"></TD>
          <TD valign="top"><IMG src="../images/shim.gif" width="1" height="26" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 2 -->
          <TD><IMG name="head_t1" src="../images/head_t1.gif" width="348" height="52" border="0" alt="Welcome to melmel-net !"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="52" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 3 -->
          <TD width="348" height="18">
            <DIV align="center"><IMG src="images/shim.gif" width="348" height="18"></DIV>
          </TD>
          <TD><IMG src="../images/shim.gif" width="1" height="18" border="0"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR bgcolor="#666666">
    <TD valign="top" colspan="3" align="left"><IMG src="../images/shim.gif" width="600" height="7"></TD>
  </TR>
  <TR>
    <TD valign="top" colspan="3" align="left">
      <TABLE width="600" border="0" cellspacing="0" cellpadding="0">
        <TR>
          <TD bgcolor="#FFCCCC" valign="top">
            <TABLE border="0" cellpadding="0" cellspacing="0" width="130">
                <TR> <!-- Shim row, height 1. -->
                  <TD><IMG src="images/shim.gif" width="8" height="1" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="112" height="1" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="10" height="1" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="1" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 1 -->
                  <TD colspan="3"><IMG src="images/shim.gif" width="130" height="6" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="6" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 2 -->
                  <TD colspan="3"><IMG name="menu_4" src="../images/menu_4.gif" width="130" height="20" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="20" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 3 -->
                  <TD colspan="3"><IMG src="images/shim.gif" width="130" height="4" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="4" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 4 -->
                  <TD rowspan="7"><IMG src="images/shim.gif" width="8" height="154" border="0"></TD>

                <TD><IMG name="b_4top" src="images/b_4top2.gif" width="112" height="34" border="0" alt="広告を出したい！TopPage"></TD>
                  <TD rowspan="7"><IMG src="images/shim.gif" width="10" height="154" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="34" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 5 -->
                  <TD><IMG src="images/shim.gif" width="112" height="6" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="6" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 6 -->

                <TD><A href="type.html"><IMG name="b_4type" src="images/b_4type.gif" width="112" height="34" border="0" alt="広告タイプ"></A></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="34" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 7 -->
                  <TD><IMG src="images/shim.gif" width="112" height="6" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="6" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 8 -->

                <TD><A href="nagare.html"><IMG name="b_4nagare" src="images/b_4nagare.gif" width="112" height="34" border="0" alt="広告出稿からご報告までの流れ"></A></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="34" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 9 -->
                  <TD><IMG src="images/shim.gif" width="112" height="6" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="6" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 10 -->

                <TD><A href="kijyun.html"><IMG name="b_4kijyun" src="images/b_4kijyun.gif" width="112" height="34" border="0" alt="登録メールマガジンの審査基準"></A></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="34" border="0"></TD>
                </TR>
              </TABLE>

            <P align="center"><A href="yoyaku.asp">予約</A></P>
            <P align="center"><A href="sokuhou.asp">掲載状況速報</A></P>
            <P align="center"><A href="../index.html"><IMG src="../images/b_rt2.gif" width="112" height="25" alt="めるめる・netトップページに戻る" border="0"></A></P>
            <P align="center">　</P>
            <P align="center"><IMG src="../images/chara03r2.gif" width="63" height="97"></P>
          </TD>

          <TD valign="top" bgcolor="#FFFFFF">
            <TABLE width="470" border="0" cellspacing="5" cellpadding="0">
              <TR>
                <TD>
<%
Dim DB, RS, SQL
Dim koukoku_id, koukoku_name, client_name, company, koukoku_shuu, pub_start, pub_end, busuu, keisai_irai, koukoku_msg, limit_date, koukoku_type
Dim cat_int, cat_pcc, cat_soft, cat_mvl, cat_bns, cat_seiji, cat_kabu, cat_fsn, cat_lif, cat_shumi, cat_res, cat_tvl, cat_spt, cat_gamble, cat_hlt, cat_art, cat_shp, cat_ent, cat_game, cat_movie, cat_uranai, cat_std, cat_shikaku, cat_gogaku, cat_nws, cat_zatsu, cat_kenshou, cat_homepage, cat_hitokoto, cat_other
Dim age_10m, age_10f, age_20m, age_20f, age_30m, age_30f, age_40m, age_40f, age_50m, age_50f
Dim shk_stu, shk_enp, shk_syf, shk_other
Dim are_ht, are_kt, are_cb, are_kk, are_cs, are_ks, are_fr, are_zk
Dim i, tantousha, mail_adr, tel_no

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

koukoku_id = Request.QueryString("id")
If IsEmpty(koukoku_id) Then
  koukoku_shuu = ""
  koukoku_type = ""
Else
  SQL = "SELECT * FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "' AND KK_client_id='" & client_id & "'"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

  If Not RS.EOF Then
    koukoku_name = RS("KK_koukoku_name")
    client_name = RS("KK_client_name")
    company = RS("KK_company")
    koukoku_shuu = RS("KK_koukoku_shuu")
    pub_start = DateFormat(RS("KK_pub_start"))
    pub_end = DateFormat(RS("KK_pub_end"))
    busuu = RS("KK_busuu")
    keisai_irai = RS("KK_keisai_irai")
    koukoku_msg = RS("KK_koukoku_msg")
		cat_int = RS("KK_cat_int")
		cat_pcc = RS("KK_cat_pcc")
		cat_soft = RS("KK_cat_soft")
		cat_mvl = RS("KK_cat_mvl")
		cat_bns = RS("KK_cat_bns")
		cat_seiji = RS("KK_cat_seiji")
		cat_kabu = RS("KK_cat_kabu")
		cat_fsn = RS("KK_cat_fsn")
		cat_lif = RS("KK_cat_lif")
		cat_shumi = RS("KK_cat_shumi")
		cat_res = RS("KK_cat_res")
		cat_tvl = RS("KK_cat_tvl")
		cat_spt = RS("KK_cat_spt")
		cat_gamble = RS("KK_cat_gamble")
		cat_hlt = RS("KK_cat_hlt")
		cat_art = RS("KK_cat_art")
		cat_shp = RS("KK_cat_shp")
		cat_ent = RS("KK_cat_ent")
		cat_game = RS("KK_cat_game")
		cat_movie = RS("KK_cat_movie")
		cat_uranai = RS("KK_cat_uranai")
		cat_std = RS("KK_cat_std")
		cat_shikaku = RS("KK_cat_shikaku")
		cat_gogaku = RS("KK_cat_gogaku")
		cat_nws = RS("KK_cat_nws")
		cat_zatsu = RS("KK_cat_zatsu")
		cat_kenshou = RS("KK_cat_kenshou")
		cat_homepage = RS("KK_cat_homepage")
		cat_hitokoto = RS("KK_cat_hitokoto")
		cat_other = RS("KK_cat_other")
    age_10m = RS("KK_age_10m")
    age_10f = RS("KK_age_10f")
    age_20m = RS("KK_age_20m")
    age_20f = RS("KK_age_20f")
    age_30m = RS("KK_age_30m")
    age_30f = RS("KK_age_30f")
    age_40m = RS("KK_age_40m")
    age_40f = RS("KK_age_40f")
    age_50m = RS("KK_age_50m")
    age_50f = RS("KK_age_50f")
    shk_stu = RS("KK_shk_stu")
    shk_enp = RS("KK_shk_enp")
    shk_syf = RS("KK_shk_syf")
    shk_other = RS("KK_shk_other")
    are_ht = RS("KK_are_ht")
    are_kt = RS("KK_are_kt")
    are_cb = RS("KK_are_cb")
    are_kk = RS("KK_are_kk")
    are_cs = RS("KK_are_cs")
    are_ks = RS("KK_are_ks")
    are_fr = RS("KK_are_fr")
    are_zk = RS("KK_are_zk")
    limit_date = DateFormat(RS("KK_limit_date"))
    koukoku_type = RS("KK_koukoku_type") & RS("KK_koukoku_line")
		tantousha = RS("KK_tantousha")
		mail_adr = RS("KK_mail_adr")
		tel_no = RS("KK_tel_no")
  Else
    Response.Redirect "yoyaku.asp"
  End If
  RS.Close
End If
%>
<form method="post" action="yoyaku3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width=<%If browser = "IE" Then Response.Write "600" Else Response.Write "600"%> class="kihon">
    <tr>
      <td>■ご担当者様のお名前と連絡先を入力してください</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%' class="kihon">
          <tr>
            <td class="m2" width="22%">担当者名</td>
            <td class="n2"><input type="text" name="tantousha" size=50 maxlength=50 value="<%=tantousha%>"></td>
          </tr>
          <tr>
            <td class="m2">連絡先ﾒｰﾙｱﾄﾞﾚｽ</td>
            <td class="n2"><input type="text" name="mail_adr" size=50 maxlength=100 value="<%=mail_adr%>"></td>
          </tr>
          <tr>
            <td class="m2">連絡先電話番号</td>
            <td class="n2"><input type="text" name="tel_no" size=20 maxlength=20 value="<%=tel_no%>"></td>
          </tr>
				</table>
			</td>
		</tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>■予約したい広告の情報を入力してＯＫボタンをクリックしてください</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%' class="kihon">
<%
If Not IsEmpty(koukoku_id) Then
%>
          <tr>
            <td class="m2">広告ID</td>
            <td class="n2"><input type="hidden" name="koukoku_id" value="<%=koukoku_id%>"><%=koukoku_id%></td>
          </tr>
<%
Else
%>
          <input type="hidden" name="koukoku_id" value="">
<%
End If
%>
          <tr>
            <td class="m2" width="22%">広告名（必須）</td>
            <td class="n2"><input type="text" name="koukoku_name" size=<%If browser = "IE" Then Response.Write "70" Else Response.Write "60"%> maxlength="100" value="<%=koukoku_name%>"></td>
          </tr>
          <tr>
            <td class="m2">広告掲載週（必須）</td>
            <td class="n2">
              <select name="koukoku_shuu" onChange="OnChange_koukoku_shuu()">
<%
Dim dt

SQL = "SELECT * FROM T_WEEK WHERE WE_start_date >= '" & Date() & "' ORDER BY WE_shuu_code"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

Writeln "<option value=''" & Selected(koukoku_shuu, "") & ">選択してください</option>"
dt = DateAdd("m", 6, date)
Do While True
  If RS.EOF Then
    Exit Do
  End If
  Writeln "<option value='" & RS("WE_shuu_code") & DateFormat(RS("WE_start_date")) & "'" & Selected(koukoku_shuu, RS("WE_shuu_code")) & ">" & RS("WE_shuu_code") & " (" & DateFormat(RS("WE_start_date")) & ")</option>"
  If RS("WE_start_date") > dt Then
    Exit Do
  End If
  RS.MoveNext
Loop
RS.Close
%>
              </select>
            </td>
          </tr>
          <tr>
            <td class="m2">配信開始日</td>
            <td class="n2"><input type="text" name="pub_start" size="20" value="<%=pub_start%>"></td>
          </tr>
          <tr>
            <td class="m2">配信終了日</td>
            <td class="n2"><input type="text" name="pub_end" size="20" value="<%=pub_end%>"></td>
          </tr>
<!--
          <tr>
            <td class="m2" width=160>報告期限</td>
            <td class="n2"><input type="text" name="limit_date" value="<%=limit_date%>"></td>
          </tr>
-->
<input type="hidden" name="limit_date" value="<%=limit_date%>">
          <tr>
            <td class="m2">発行部数（必須）</td>
            <td class="n2"><input type="text" name="busuu" size="20" value="<%=busuu%>"></td>
          </tr>
<!--
          <tr>
            <td class="m2">掲載依頼部数</td>
            <td class="n2"><input type="text" name="keisai_irai" size="20" value="<%=keisai_irai%>">%</td>
          </tr>
-->
<input type="hidden" name="keisai_irai" value="<%=keisai_irai%>">
          <tr>
            <td class="m2" width=160>広告タイプ</td>
            <td class="n2">
              <select name="koukoku_type">
                <option value="">選択してください</option>
                <option value="A5"<%=Selected(koukoku_type, "A5")%>>タイプＡ-５行</option>
                <option value="B5"<%=Selected(koukoku_type, "B5")%>>タイプＢ-５行</option>
                <option value="A10"<%=Selected(koukoku_type, "A10")%>>タイプＡ-１０行</option>
                <option value="B10"<%=Selected(koukoku_type, "B10")%>>タイプＢ-１０行</option>
              </select>
              <font size="-2">（Ａはひとつのメルマガで１回のみ、Ｂは何度でも掲載できるタイプです）</font>
            </td>
          </tr>
         <tr>
            <td class="m2">広告文</td>
            <td class="n2"><font face="ＭＳ ゴシック" point-size="9"><textarea name="koukoku_msg" cols=72 rows=10 class="np"><%=vbCrLf & koukoku_msg%></textarea></font></td>
          </tr>
          <tr>
            <td class="m2">メルマガカテゴリ</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_int" value="1" <%=Checked(cat_int)%>>インターネット</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_pcc" value="1" <%=Checked(cat_pcc)%>>パソコン/コンピュータ全般</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_soft" value="1" <%=Checked(cat_soft)%>>ソフトウェア/プログラミング</td>
              </tr>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_mvl" value="1" <%=Checked(cat_mvl)%>>携帯/モバイル</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_bns" value="1" <%=Checked(cat_bns)%>>ビジネス全般</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_seiji" value="1" <%=Checked(cat_seiji)%>>政治/経済</td>
              </tr>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_kabu" value="1" <%=Checked(cat_kabu)%>>株式/投資</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_fsn" value="1" <%=Checked(cat_fsn)%>>ファッション/美容</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_lif" value="1" <%=Checked(cat_lif)%>>暮らし/日記</td>
              </tr>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_shumi" value="1" <%=Checked(cat_shumi)%>>趣味</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_res" value="1" <%=Checked(cat_res)%>>グルメ/レストラン</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_tvl" value="1" <%=Checked(cat_tvl)%>>旅行/地域</td>
              </tr>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_spt" value="1" <%=Checked(cat_spt)%>>スポーツ/レジャー</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_gamble" value="1" <%=Checked(cat_gamble)%>>ギャンブル</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_hlt" value="1" <%=Checked(cat_hlt)%>>健康/医療/育児</td>
              </tr>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_art" value="1" <%=Checked(cat_art)%>>アート/文芸</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_shp" value="1" <%=Checked(cat_shp)%>>ショッピング</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_ent" value="1" <%=Checked(cat_ent)%>>エンターテイメント</td>
              </tr>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_game" value="1" <%=Checked(cat_game)%>>ゲーム/クイズ</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_movie" value="1" <%=Checked(cat_movie)%>>映画/音楽/芸能</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_uranai" value="1" <%=Checked(cat_uranai)%>>占い</td>
              </tr>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_std" value="1" <%=Checked(cat_std)%>>学習/教育</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_shikaku" value="1" <%=Checked(cat_shikaku)%>>資格</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_gogaku" value="1" <%=Checked(cat_gogaku)%>>語学</td>
              </tr>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_nws" value="1" <%=Checked(cat_nws)%>>ニュース</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_zatsu" value="1" <%=Checked(cat_zatsu)%>>雑学</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_kenshou" value="1" <%=Checked(cat_kenshou)%>>懸賞/プレゼント</td>
              </tr>
              <tr>
              <td class="n2c" width=150><input type="checkbox" name="cat_homepage" value="1" <%=Checked(cat_homepage)%>>ホームページ紹介</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_hitokoto" value="1" <%=Checked(cat_hitokoto)%>>一日ひとこと系</td>
              <td class="n2c" width=150><input type="checkbox" name="cat_other" value="1" <%=Checked(cat_other)%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（年代）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=80><input type="checkbox" name="age_10m" value="1" <%=Checked(age_10m)%>>10代男</td>
              <td class="n2c" width=80><input type="checkbox" name="age_20m" value="1" <%=Checked(age_20m)%>>20代男</td>
              <td class="n2c" width=80><input type="checkbox" name="age_30m" value="1" <%=Checked(age_30m)%>>30代男</td>
              <td class="n2c" width=80><input type="checkbox" name="age_40m" value="1" <%=Checked(age_40m)%>>40代男</td>
              <td class="n2c" width=80><input type="checkbox" name="age_50m" value="1" <%=Checked(age_50m)%>>50代以上男</td>
              </tr>
              <tr>
              <td class="n2c" width=80><input type="checkbox" name="age_10f" value="1" <%=Checked(age_10f)%>>10代女</td>
              <td class="n2c" width=80><input type="checkbox" name="age_20f" value="1" <%=Checked(age_20f)%>>20代女</td>
              <td class="n2c" width=80><input type="checkbox" name="age_30f" value="1" <%=Checked(age_30f)%>>30代女</td>
              <td class="n2c" width=80><input type="checkbox" name="age_40f" value="1" <%=Checked(age_40f)%>>40代女</td>
              <td class="n2c" width=80><input type="checkbox" name="age_50f" value="1" <%=Checked(age_50f)%>>50代以上女</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（職業）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox" name="shk_stu" value="1" <%=Checked(shk_stu)%>>学生</td>
              <td class="n2c" width=100><input type="checkbox" name="shk_enp" value="1" <%=Checked(shk_enp)%>>社会人</td>
              <td class="n2c" width=100><input type="checkbox" name="shk_syf" value="1" <%=Checked(shk_syf)%>>主婦</td>
              <td class="n2c" width=100><input type="checkbox" name="shk_other" value="1" <%=Checked(shk_other)%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（エリア）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=100><input type="checkbox" name="are_ht" value="1" <%=Checked(are_ht)%>>北海道/東北</td>
              <td class="n2c" width=100><input type="checkbox" name="are_kt" value="1" <%=Checked(are_kt)%>>関東</td>
              <td class="n2c" width=100><input type="checkbox" name="are_cb" value="1" <%=Checked(are_cb)%>>中部</td>
              <td class="n2c" width=100><input type="checkbox" name="are_kk" value="1" <%=Checked(are_kk)%>>近畿</td>
              </tr>
              <tr>
              <td class="n2c" width=100><input type="checkbox" name="are_cs" value="1" <%=Checked(are_cs)%>>中国/四国</td>
              <td class="n2c" width=100><input type="checkbox" name="are_ks" value="1" <%=Checked(are_ks)%>>九州</td>
              <td class="n2c" width=100><input type="checkbox" name="are_fr" value="1" <%=Checked(are_fr)%>>海外</td>
              <td class="n2c" width=100><input type="checkbox" name="are_zk" value="1" <%=Checked(are_zk)%>>全国</td>
              </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <center>
  <input type="submit" value="　ＯＫ　">
  <input type="reset" value="リセット">
  </center>

</form>
                </TD>
              </TR>
            </TABLE>
          </TD>
          </TR>
        </TABLE>
    </TD>
  </TR>
  <TR bgcolor="#666666">
    <TD valign="top" colspan="3" align="left"><IMG src="../images/shim.gif" width="600" height="7"></TD>
  </TR>
  <TR>
    <TD colspan="3">
      <TABLE bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <TR> <!-- Shim row, height 1. -->
          <TD><IMG src="../images/shim.gif" width="147" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="66" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="28" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="155" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="204" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="1" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 1 -->
          <TD colspan="3"><IMG src="../images/shim.gif" width="241" height="15" border="0"></TD>
          <TD rowspan="2"><IMG name="logo_s" src="../images/logo_s.gif" width="155" height="42" border="0" alt="めるめる・net ロゴ"></TD>
          <TD rowspan="2"><IMG src="../images/shim.gif" width="204" height="42" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="15" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 2 -->
          <TD rowspan="2"><IMG src="../images/shim.gif" width="147" height="49" border="0"></TD>
          <TD rowspan="2"><IMG name="cara_s" src="../images/cara_s.gif" width="66" height="49" border="0" alt="めるめる イメージ"></TD>
          <TD><IMG src="../images/shim.gif" width="28" height="27" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="27" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 3 -->
          <TD colspan="3" height="22" valign="top"> <FONT size="2" class="komoji">Copyright
            (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</FONT></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="22" border="0"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</body>

</html>
