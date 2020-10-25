<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:本登録初期チェックのメルマガ情報修正画面
'******************************************************

SetHeaderTitle "本登録初期チェック", TC_HAKKOUSHA
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
function OnSubmit_form1() {
  with (document.form1) {
    if (shuu_keisuu.value == "") {
      alert("週発行係数を入力してください。");
      shuu_keisuu.focus();
      return false;
    }
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, mag_id

mag_id = Request.QueryString("id")
If IsEmpty(mag_id) Then
  Response.Redirect("index.asp")
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM V_MELMAG WHERE MG_mag_id='" & mag_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="init_check3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>■発行者情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="23%">発行者ID</td>
            <td class="n1"><%=RS("MM_mel_id")%></td>
          </tr>
          <tr>
            <td class="m1">発行者名</td>
            <td class="n1"><%=RS("MM_h_name")%></td>
          </tr>
          <tr>
            <td class="m1">e-mailアドレス</td>
            <td class="n1"><%=RS("MM_mail_adr")%></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>■メルマガ情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="23%">メルマガID</td>
            <td class="n1"><input type="hidden" name="mag_id" value="<%=RS("MG_mag_id")%>"><%=RS("MG_mag_id")%></td>
          </tr>
          <tr>
            <td class="m1">メルマガ名</td>
            <td class="n1"><input type="text" name="m_name" size="50" value="<%=RS("MG_m_name")%>" maxlength="100"></td>
          </tr>
          <tr>
            <td class="m1">発行頻度</td>
            <td class="n1"><input type="text" name="hindo" size="20" value="<%=RS("MG_hindo")%>" maxlength="30"></td>
          </tr>
          <tr>
            <td class="m1">発行部数</td>
            <td class="n1"><input type="text" name="busuu" size="20" value="<%=RS("MG_busuu")%>" maxlength="30"></td>
          </tr>
          <tr>
            <td class="m1">広告罫線</td>
            <td class="n1">
              <select name="keisen_id" class="np">
<%
Dim RS2
SQL = "SELECT KE_keisen_id,KE_start_line FROM T_KEISEN ORDER BY KE_keisen_id"
Set RS2 = DB.Execute(SQL)

Writeln "<option value=''" & Selected(RS("MG_keisen_id"), "") & ">選択してください</option>"
Do Until RS2.EOF
  Writeln "<option value='" & RS2("KE_keisen_id") & "'" & Selected(RS("MG_keisen_id"), RS2("KE_keisen_id")) & ">" & ReplaceTag(ZenkakuRight(RS2("KE_start_line"), 35)) & "</option>"
  RS2.MoveNext
Loop
RS2.Close
%>
            </select>
          </tr>
          <tr>
            <td class="m1">１行の文字数</td>
            <td class="n1"><input type="text" name="moji_suu" size="20" value="<%=RS("MG_moji_suu")%>"></td>
          </tr>
          <tr>
            <td class="m1">メルマガ紹介文</FONT></TD>
            <td class="n1"><textarea name="shoukai_text" cols=60 rows=5><%=RS("MG_shoukai_text")%></textarea></td>
          </tr>
          <tr>
            <td class="m1">購読用ＵＲＬ</FONT></TD>
            <td class="n1"><input type="text" name="koudoku_url" size="50" value="<%=RS("MG_koudoku_url")%>" maxlength="100">
<%
If Len(RS("MG_koudoku_url")) > 0 Then
  Writeln "<input type='button' value='参照' onClick='window.open(document.form1.koudoku_url.value, ""koudoku"")'>"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">詳細説明用ＵＲＬ</FONT></TD>
            <td class="n1"><input type="text" name="shousai_url" size="50" value="<%=RS("MG_shousai_url")%>" maxlength="100">
<%
If Len(RS("MG_shousai_url")) > 0 Then
  Writeln "<input type='button' value='参照' onClick='window.open(document.form1.shousai_url.value, ""shousai"")'>"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">読者向け紹介</td>
            <td class="n1">
              <input type="checkbox" name="shoukai_flag1" value="1" <%=Checked(RS("MG_shoukai_flag1"))%>>掲載を希望する（発行者設定）　
              <input type="checkbox" name="shoukai_flag2" value="1" <%=Checked(RS("MG_shoukai_flag2"))%>>掲載する（事務局設定）
            </td>
          </tr>
          <tr>
            <td class="m1">めろんぱんID</td>
            <td class="n1"><input type="text" name="melonpan_id" size="20" value="<%=RS("MG_melonpan_id")%>" maxlength="20"></td>
          </tr>
          <tr>
            <td class="m1">発行システム</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_melonpan" value="1" <%=Checked(RS("MG_mms_melonpan"))%>>めろんぱん</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_mag" value="1" <%=Checked(RS("MG_mms_mag"))%>>まぐまぐ</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_pubz" value="1" <%=Checked(RS("MG_mms_pubz"))%>>Pubzine</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_melma" value="1" <%=Checked(RS("MG_mms_melma"))%>>melma!</td>
              </tr>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_macky" value="1" <%=Checked(RS("MG_mms_macky"))%>>Macky</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_kapu" value="1" <%=Checked(RS("MG_mms_kapu"))%>>カプライト</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_emag" value="1" <%=Checked(RS("MG_mms_emag"))%>>E-MAGAZINE</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_tengoku" value="1" <%=Checked(RS("MG_mms_tengoku"))%>>メルマガ天国</td>
              </tr>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_tiara" value="1" <%=Checked(RS("MG_mms_tiara"))%>>ティアラオンライン</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_merubox" value="1" <%=Checked(RS("MG_mms_merubox"))%>>MERU-BOX</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_dokuji" value="1" <%=Checked(RS("MG_mms_dokuji"))%>>独自配信</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_other" value="1" <%=Checked(RS("MG_mms_other"))%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">メルマガカテゴリ</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_int" value="1" <%=Checked(RS("MG_cat_int"))%>>インターネット</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_pcc" value="1" <%=Checked(RS("MG_cat_pcc"))%>>パソコン/コンピュータ全般</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_soft" value="1" <%=Checked(RS("MG_cat_soft"))%>>ソフトウェア/プログラミング</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_mvl" value="1" <%=Checked(RS("MG_cat_mvl"))%>>携帯/モバイル</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_bns" value="1" <%=Checked(RS("MG_cat_bns"))%>>ビジネス全般</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_seiji" value="1" <%=Checked(RS("MG_cat_seiji"))%>>政治/経済</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_kabu" value="1" <%=Checked(RS("MG_cat_kabu"))%>>株式/投資</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_fsn" value="1" <%=Checked(RS("MG_cat_fsn"))%>>ファッション/美容</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_lif" value="1" <%=Checked(RS("MG_cat_lif"))%>>暮らし/日記</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_shumi" value="1" <%=Checked(RS("MG_cat_shumi"))%>>趣味</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_res" value="1" <%=Checked(RS("MG_cat_res"))%>>グルメ/レストラン</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_tvl" value="1" <%=Checked(RS("MG_cat_tvl"))%>>旅行/地域</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_spt" value="1" <%=Checked(RS("MG_cat_spt"))%>>スポーツ/レジャー</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_gamble" value="1" <%=Checked(RS("MG_cat_gamble"))%>>ギャンブル</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_hlt" value="1" <%=Checked(RS("MG_cat_hlt"))%>>健康/医療/育児</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_art" value="1" <%=Checked(RS("MG_cat_art"))%>>アート/文芸</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_shp" value="1" <%=Checked(RS("MG_cat_shp"))%>>ショッピング</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_ent" value="1" <%=Checked(RS("MG_cat_ent"))%>>エンターテイメント</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_game" value="1" <%=Checked(RS("MG_cat_game"))%>>ゲーム/クイズ</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_movie" value="1" <%=Checked(RS("MG_cat_movie"))%>>映画/音楽/芸能</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_uranai" value="1" <%=Checked(RS("MG_cat_uranai"))%>>占い</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_std" value="1" <%=Checked(RS("MG_cat_std"))%>>学習/教育</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_shikaku" value="1" <%=Checked(RS("MG_cat_shikaku"))%>>資格</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_gogaku" value="1" <%=Checked(RS("MG_cat_gogaku"))%>>語学</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_nws" value="1" <%=Checked(RS("MG_cat_nws"))%>>ニュース</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_zatsu" value="1" <%=Checked(RS("MG_cat_zatsu"))%>>雑学</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_kenshou" value="1" <%=Checked(RS("MG_cat_kenshou"))%>>懸賞/プレゼント</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_homepage" value="1" <%=Checked(RS("MG_cat_homepage"))%>>ホームページ紹介</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_hitokoto" value="1" <%=Checked(RS("MG_cat_hitokoto"))%>>一日ひとこと系</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_other" value="1" <%=Checked(RS("MG_cat_other"))%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">読者層（年代）</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=90><input type="checkbox" name="age_10m" value="1" <%=Checked(RS("MG_age_10m"))%>>10代男</td>
              <td class="n1c" width=90><input type="checkbox" name="age_20m" value="1" <%=Checked(RS("MG_age_20m"))%>>20代男</td>
              <td class="n1c" width=90><input type="checkbox" name="age_30m" value="1" <%=Checked(RS("MG_age_30m"))%>>30代男</td>
              <td class="n1c" width=90><input type="checkbox" name="age_40m" value="1" <%=Checked(RS("MG_age_40m"))%>>40代男</td>
              <td class="n1c" width=90><input type="checkbox" name="age_50m" value="1" <%=Checked(RS("MG_age_50m"))%>>50代以上男</td>
              </tr>
              <tr>
              <td class="n1c" width=90><input type="checkbox" name="age_10f" value="1" <%=Checked(RS("MG_age_10f"))%>>10代女</td>
              <td class="n1c" width=90><input type="checkbox" name="age_20f" value="1" <%=Checked(RS("MG_age_20f"))%>>20代女</td>
              <td class="n1c" width=90><input type="checkbox" name="age_30f" value="1" <%=Checked(RS("MG_age_30f"))%>>30代女</td>
              <td class="n1c" width=90><input type="checkbox" name="age_40f" value="1" <%=Checked(RS("MG_age_40f"))%>>40代女</td>
              <td class="n1c" width=90><input type="checkbox" name="age_50f" value="1" <%=Checked(RS("MG_age_50f"))%>>50代以上女</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">読者層（職業）</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="shk_stu" value="1" <%=Checked(RS("MG_shk_stu"))%>>学生</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_enp" value="1" <%=Checked(RS("MG_shk_enp"))%>>社会人</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_syf" value="1" <%=Checked(RS("MG_shk_syf"))%>>主婦</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_other" value="1" <%=Checked(RS("MG_shk_other"))%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">読者層（エリア）</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="are_ht" value="1" <%=Checked(RS("MG_are_ht"))%>>北海道/東北</td>
              <td class="n1c" width=100><input type="checkbox" name="are_kt" value="1" <%=Checked(RS("MG_are_kt"))%>>関東</td>
              <td class="n1c" width=100><input type="checkbox" name="are_cb" value="1" <%=Checked(RS("MG_are_cb"))%>>中部</td>
              <td class="n1c" width=100><input type="checkbox" name="are_kk" value="1" <%=Checked(RS("MG_are_kk"))%>>近畿</td>
              </tr>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="are_cs" value="1" <%=Checked(RS("MG_are_cs"))%>>中国/四国</td>
              <td class="n1c" width=100><input type="checkbox" name="are_ks" value="1" <%=Checked(RS("MG_are_ks"))%>>九州</td>
              <td class="n1c" width=100><input type="checkbox" name="are_fr" value="1" <%=Checked(RS("MG_are_fr"))%>>海外</td>
              <td class="n1c" width=100><input type="checkbox" name="are_zk" value="1" <%=Checked(RS("MG_are_zk"))%>>全国</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1" width=160>週発行係数</td>
            <td class="n1"><input type="text" name="shuu_keisuu" value="<%=RS("MG_shuu_keisuu")%>"></td>
          <tr>
            <td class="m1" width=160>まぐまぐ係数</td>
            <td class="n1"><input type="text" name="magmag_keisuu" value="<%If IsNull(RS("MG_magmag_keisuu")) Then Response.Write "100" Else Response.Write FormatNumber(NVL(RS("MG_magmag_keisuu"), 0) * 100,1)%>"> %</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value="　ＯＫ　">
  <input type="reset" value="リセット">

</form>
</center>

<%
RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
