<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:追加依頼入力画面
'******************************************************

SetHeaderTitle "追加依頼広告入力", TC_KOUKOKU

Dim DB, RS, SQL
Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function DateToString(date) {
  var month,day;
  month = "0" + (date.getMonth() + 1);
  day = "0" + date.getDate();
  return date.getFullYear() + "/" + month.substr(month.length-2,2) + "/" + day.substr(day.length-2,2);
}
function OnChange_client_id() {
    var client_id;
    if (document.form1.client_id.selectedIndex != 0) {
      client_id = document.form1.client_id.value.substr(0, 5);
      switch (client_id) {
<%
SQL = "SELECT CM_client_id,CM_client_name,CM_company,CM_tantousha,CM_mail_adr,CM_tel_no FROM T_CLIENT ORDER BY CM_client_id"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
Do Until RS.EOF
  Writeln "case '" & RS("CM_client_id") & "':"
  Writeln "document.form1.client_name.value = '" & RS("CM_client_name") & "';"
  Writeln "document.form1.company.value = '" & RS("CM_company") & "';"
	Writeln "document.form1.tantousha.value = '" & RS("CM_tantousha") & "';"
	Writeln "document.form1.mail_adr.value = '" & RS("CM_mail_adr") & "';"
	Writeln "document.form1.tel_no.value = '" & RS("CM_tel_no") & "';"
  Writeln "break;"
  RS.MoveNext
Loop
RS.Close
%>
      }
    }
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
    }
  }
}
function OnSubmit_form1() {
  with (document.form1) {
    if (koukoku_shuu.value == "") {
      alert("広告掲載週を選択してください。");
      koukoku_shuu.focus();
      return false;
    }
    if (pub_start.value == "") {
      alert("配信開始日を入力してください。");
      pub_start.focus();
      return false;
    }
    if (pub_end.value == "") {
      alert("配信終了日を入力してください。");
      pub_end.focus();
      return false;
    }
    if (limit_date.value == "") {
      alert("報告期限を入力してください。");
      limit_date.focus();
      return false;
    }
    if (busuu.value == "") {
      alert("発行部数を入力してください。");
      busuu.focus();
      return false;
    }
    if (keisai_irai.value == "") {
      alert("掲載依頼部数入力してください。");
      keisai_irai.focus();
      return false;
    }
    if (koukoku_type.value == "") {
      alert("広告タイプを選択してください。");
      koukoku_type.focus();
      return false;
    }
    if (koukoku_msg.value == "") {
      alert("広告文を入力してください。");
      koukoku_msg.focus();
      return false;
    }
  }
	document.all("wait").style.display = "";
  return true;
}
//-->
</SCRIPT>
</head>
<body onLoad="OnChange_koukoku_shuu()">

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim tsuika, koukoku_name, client_name, company, koukoku_shuu, pub_start, pub_end, busuu, keisai_irai, koukoku_msg, limit_date, koukoku_type, jogai_id, koukoku_url, client_id
Dim mms_melonpan, mms_mag, mms_pubz, mms_melma, mms_macky, mms_kapu, mms_emag, mms_tengoku, mms_tiara, mms_merubox, mms_dokuji, mms_other
Dim cat_int, cat_pcc, cat_soft, cat_mvl, cat_bns, cat_seiji, cat_kabu, cat_fsn, cat_lif, cat_shumi, cat_res, cat_tvl, cat_spt, cat_gamble, cat_hlt, cat_art, cat_shp, cat_ent, cat_game, cat_movie, cat_uranai, cat_std, cat_shikaku, cat_gogaku, cat_nws, cat_zatsu, cat_kenshou, cat_homepage, cat_hitokoto, cat_other
Dim age_10m, age_10f, age_20m, age_20f, age_30m, age_30f, age_40m, age_40f, age_50m, age_50f
Dim shk_stu, shk_enp, shk_syf, shk_other
Dim are_ht, are_kt, are_cb, are_kk, are_cs, are_ks, are_fr, are_zk
Dim i, tantousha, mail_adr, tel_no

tsuika = Request.QueryString("id")
If Not IsEmpty(tsuika) Then
  SQL = "SELECT * FROM V_KOUKOKU WHERE KK_koukoku_id='" & tsuika & "'"
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
    jogai_id = RS("KK_jogai_id")
    koukoku_url = RS("KK_koukoku_url")
		mms_melonpan = RS("KK_mms_melonpan")
		mms_mag = RS("KK_mms_mag")
		mms_pubz = RS("KK_mms_pubz")
		mms_melma = RS("KK_mms_melma")
		mms_macky = RS("KK_mms_macky")
		mms_kapu = RS("KK_mms_kapu")
		mms_emag = RS("KK_mms_emag")
		mms_tengoku = RS("KK_mms_tengoku")
		mms_tiara = RS("KK_mms_tiara")
		mms_merubox = RS("KK_mms_merubox")
		mms_dokuji = RS("KK_mms_dokuji")
		mms_other = RS("KK_mms_other")
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
    koukoku_shuu = CStr(CLng(koukoku_shuu) + 1)
    client_id = RS("KK_client_id")
		tantousha = RS("KK_tantousha")
		mail_adr = RS("KK_mail_adr")
		tel_no = RS("KK_tel_no")
  End If
  RS.Close

  SQL = "SELECT KK_koukoku_id FROM V_KOUKOKU WHERE KK_koukoku_id='" & tsuika & "' OR KK_tsuika_id='" & tsuika & "'"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
  Do Until RS.EOF
    If Not IsEmpty(jogai_id) And jogai_id <> "" Then
      jogai_id = jogai_id & ","
    End If
    jogai_id = jogai_id & RS(0)
    RS.MoveNext
  Loop
  RS.Close

End If
%>
<center>
<form method="post" action="nyuukou3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='800'>
    <tr>
      <td>■広告依頼主情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m2" width="20%">クライアントID</td>
            <td class="n2"><input type="hidden" name="client_id" value="<%=client_id%>"><%=client_id%></td>
          </tr>
          <tr>
            <td class="m2">広告依頼主名</td>
            <td class="n2"><input type="hidden" name="client_name" value="<%=client_name%>"><%=ReplaceTag(client_name)%></td>
          </tr>
          <tr>
            <td class="m2">組織名</td>
            <td class="n2"><input type="hidden" name="company" value="<%=company%>"><%=ReplaceTag(company)%></td>
          </tr>
          <tr>
            <td class="m2">担当者名</td>
            <td class="n2"><input type="hidden" name="tantousha" value="<%=tantousha%>"><%=ReplaceTag(tantousha)%></td>
          </tr>
          <tr>
            <td class="m2">連絡先ﾒｰﾙｱﾄﾞﾚｽ</td>
            <td class="n2"><input type="hidden" name="mail_adr" value="<%=mail_adr%>"><%=ReplaceTag(mail_adr)%></td>
          </tr>
          <tr>
            <td class="m2">連絡先電話番号</td>
            <td class="n2"><input type="hidden" name="tel_no" value="<%=tel_no%>"><%=tel_no%></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>■広告情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m2" width="20%">初回広告ID</td>
            <td class="n2"><input type="hidden" name="tsuika" value="<%=tsuika%>"><%=tsuika%></td>
          </tr>
          <tr>
            <td class="m2">広告名</td>
            <td class="n2"><input type="hidden" name="koukoku_name" value="<%=koukoku_name%>"><%=koukoku_name%></td>
          </tr>
          <tr>
            <td class="m2">広告掲載週</td>
            <td class="n2">
              <select name="koukoku_shuu" onChange="OnChange_koukoku_shuu()">
<%
SQL = "SELECT * FROM T_WEEK WHERE WE_start_date >= '" & Date() & "' ORDER BY WE_shuu_code"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

Writeln "<option value=''" & Selected(koukoku_shuu, "") & ">選択してください</option>"
For i = 1 to 10
  If RS.EOF Then
    Exit For
  End If
  Writeln "<option value='" & RS("WE_shuu_code") & RS("WE_start_date") & "'" & Selected(koukoku_shuu, RS("WE_shuu_code")) & ">" & RS("WE_shuu_code") & " (" & RS("WE_start_date") & ")</option>"
  RS.MoveNext
Next
RS.Close
%>
              </select>
            </td>
          </tr>
          <tr>
            <td class="m2">配信開始日</td>
            <td class="n2"><input type="text" name="pub_start"></td>
          </tr>
          <tr>
            <td class="m2">配信終了日</td>
            <td class="n2"><input type="text" name="pub_end"></td>
          </tr>
          <tr>
            <td class="m2" width=160>報告期限</td>
            <td class="n2"><input type="text" name="limit_date"></td>
          </tr>
          <tr>
            <td class="m2">発行部数</td>
            <td class="n2"><input type="text" name="busuu" size="20"></td>
          </tr>
          <tr>
            <td class="m2">掲載依頼部数</td>
            <td class="n2"><input type="text" name="keisai_irai" size="20" value="<%=keisai_irai%>">%&nbsp;&nbsp;<font size="-1">（0を入力すると手動割り当て）</font></td>
          </tr>
          <tr>
            <td class="m2" width=160>広告タイプ</td>
            <td class="n2">
              <select name="koukoku_type">
                <option value="">選択してください</option>
                <option value="A5" <%=Selected(koukoku_type, "A5")%>>タイプＡ-５行</option>
                <option value="B5" <%=Selected(koukoku_type, "B5")%>>タイプＢ-５行</option>
                <option value="Q5" <%=Selected(koukoku_type, "Q5")%>>タイプＱ-５行</option>
                <option value="R5" <%=Selected(koukoku_type, "R5")%>>タイプＲ-５行</option>
                <option value="A10" <%=Selected(koukoku_type, "A10")%>>タイプＡ-１０行</option>
                <option value="B10" <%=Selected(koukoku_type, "B10")%>>タイプＢ-１０行</option>
                <option value="Q10" <%=Selected(koukoku_type, "Q10")%>>タイプＱ-１０行</option>
                <option value="R10" <%=Selected(koukoku_type, "R10")%>>タイプＲ-１０行</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="m2">広告文</td>
            <td class="n2"><textarea name="koukoku_msg" cols=80 rows=10 class="np"><%=vbCrLf & koukoku_msg%></textarea></td>
          </tr>
          <tr>
            <td class="m2">広告URL</td>
            <td class="n2"><input type="text" name="koukoku_url" size="60" value="<%=koukoku_url%>"><font size="-1">（↑http://www.melmel.net/m.asp?p=123456789012）</font></td>
          </tr>
          <tr>
            <td class="m2">発行システム</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=130><input type="checkbox" name="mms_melonpan" value="1" <%=Checked(mms_melonpan)%>>めろんぱん</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_mag" value="1" <%=Checked(mms_mag)%>>まぐまぐ</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_pubz" value="1" <%=Checked(mms_pubz)%>>Pubzine</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_melma" value="1" <%=Checked(mms_melma)%>>melma!</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox" name="mms_macky" value="1" <%=Checked(mms_macky)%>>Macky</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_kapu" value="1" <%=Checked(mms_kapu)%>>カプライト</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_emag" value="1" <%=Checked(mms_emag)%>>E-MAGAZINE</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_tengoku" value="1" <%=Checked(mms_tengoku)%>>メルマガ天国</td>
              </tr>
              <tr>
              <td class="n2c" width=130><input type="checkbox" name="mms_tiara" value="1" <%=Checked(mms_tiara)%>>ティアラオンライン</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_merubox" value="1" <%=Checked(mms_merubox)%>>MERU-BOX</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_dokuji" value="1" <%=Checked(mms_dokuji)%>>独自配信</td>
              <td class="n2c" width=130><input type="checkbox" name="mms_other" value="1" <%=Checked(mms_other)%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">メルマガカテゴリ</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_int" value="1" <%=Checked(cat_int)%>>インターネット</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_pcc" value="1" <%=Checked(cat_pcc)%>>パソコン/コンピュータ全般</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_soft" value="1" <%=Checked(cat_soft)%>>ソフトウェア/プログラミング</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_mvl" value="1" <%=Checked(cat_mvl)%>>携帯/モバイル</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_bns" value="1" <%=Checked(cat_bns)%>>ビジネス全般</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_seiji" value="1" <%=Checked(cat_seiji)%>>政治/経済</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_kabu" value="1" <%=Checked(cat_kabu)%>>株式/投資</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_fsn" value="1" <%=Checked(cat_fsn)%>>ファッション/美容</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_lif" value="1" <%=Checked(cat_lif)%>>暮らし/日記</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_shumi" value="1" <%=Checked(cat_shumi)%>>趣味</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_res" value="1" <%=Checked(cat_res)%>>グルメ/レストラン</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_tvl" value="1" <%=Checked(cat_tvl)%>>旅行/地域</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_spt" value="1" <%=Checked(cat_spt)%>>スポーツ/レジャー</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_gamble" value="1" <%=Checked(cat_gamble)%>>ギャンブル</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_hlt" value="1" <%=Checked(cat_hlt)%>>健康/医療/育児</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_art" value="1" <%=Checked(cat_art)%>>アート/文芸</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_shp" value="1" <%=Checked(cat_shp)%>>ショッピング</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_ent" value="1" <%=Checked(cat_ent)%>>エンターテイメント</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_game" value="1" <%=Checked(cat_game)%>>ゲーム/クイズ</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_movie" value="1" <%=Checked(cat_movie)%>>映画/音楽/芸能</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_uranai" value="1" <%=Checked(cat_uranai)%>>占い</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_std" value="1" <%=Checked(cat_std)%>>学習/教育</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_shikaku" value="1" <%=Checked(cat_shikaku)%>>資格</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_gogaku" value="1" <%=Checked(cat_gogaku)%>>語学</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_nws" value="1" <%=Checked(cat_nws)%>>ニュース</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_zatsu" value="1" <%=Checked(cat_zatsu)%>>雑学</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_kenshou" value="1" <%=Checked(cat_kenshou)%>>懸賞/プレゼント</td>
              </tr>
              <tr>
              <td class="n2c" width=170><input type="checkbox" name="cat_homepage" value="1" <%=Checked(cat_homepage)%>>ホームページ紹介</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_hitokoto" value="1" <%=Checked(cat_hitokoto)%>>一日ひとこと系</td>
              <td class="n2c" width=170><input type="checkbox" name="cat_other" value="1" <%=Checked(cat_other)%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m2">読者層（年代）</td>
            <td class="n2">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n2c" width=90><input type="checkbox" name="age_10m" value="1" <%=Checked(age_10m)%>>10代男</td>
              <td class="n2c" width=90><input type="checkbox" name="age_20m" value="1" <%=Checked(age_20m)%>>20代男</td>
              <td class="n2c" width=90><input type="checkbox" name="age_30m" value="1" <%=Checked(age_30m)%>>30代男</td>
              <td class="n2c" width=90><input type="checkbox" name="age_40m" value="1" <%=Checked(age_40m)%>>40代男</td>
              <td class="n2c" width=90><input type="checkbox" name="age_50m" value="1" <%=Checked(age_50m)%>>50代以上男</td>
              </tr>
              <tr>
              <td class="n2c" width=90><input type="checkbox" name="age_10f" value="1" <%=Checked(age_10f)%>>10代女</td>
              <td class="n2c" width=90><input type="checkbox" name="age_20f" value="1" <%=Checked(age_20f)%>>20代女</td>
              <td class="n2c" width=90><input type="checkbox" name="age_30f" value="1" <%=Checked(age_30f)%>>30代女</td>
              <td class="n2c" width=90><input type="checkbox" name="age_40f" value="1" <%=Checked(age_40f)%>>40代女</td>
              <td class="n2c" width=90><input type="checkbox" name="age_50f" value="1" <%=Checked(age_50f)%>>50代以上女</td>
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
          <tr>
            <td class="m2">依頼除外広告ID</td>
            <td class="n2"><input type="text" name="jogai_id" size="40" value="<%=jogai_id%>">&nbsp;&nbsp;<font size="-1">（除外したい過去の広告IDをカンマで区切って入力してください）</font></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value="　ＯＫ　">
  <input type="reset" value="リセット">
	<div style="display:none" id="wait"><br><font color="orange">振り分け処理中です。しばらくお待ちください・・・</font></div>

</form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
