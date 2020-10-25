<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:メルマガマスタ修正画面
'******************************************************

SetHeaderTitle "メルマガマスタ修正", TC_MASTER

Dim mag_id

mag_id = Request.QueryString("id")
If IsEmpty(mag_id) Then
  Response.Redirect("index.asp")
End If
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
  return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
  if (confirm("このメルマガを削除してよろしいですか？")) {
    location.href = "mag_master4.asp?id=<%=mag_id%>";
  }
}
function OnClick_disp(id) {
  var win;
  win = window.open("melmag_disp.asp?id=" + id, "melmag_disp");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, status

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT T_MELMAG.*,MM_h_name" & vbCrLf & _
      "FROM T_MELMAG LEFT OUTER JOIN T_MELMEL ON MG_mel_id=MM_mel_id" & vbCrLf & _
      "WHERE MG_mag_id='" & mag_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="mag_master3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>■メルマガ登録情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="22%">メルマガID</td>
            <td class="n3"><input type="hidden" name="mag_id" value="<%=RS("MG_mag_id")%>"><%=RS("MG_mag_id")%></td>
          </tr>
          <tr>
            <td class="m3">メルマガ名</td>
            <td class="n3"><input type="text" name="m_name" size="50" value="<%=RS("MG_m_name")%>" maxlength="100">　<input type="button" value="表示" onClick="OnClick_disp('<%=RS("MG_mag_id")%>')"></td>
          </tr>
          <tr>
            <td class="m3">発行頻度</td>
            <td class="n3"><input type="text" name="hindo" size="20" value="<%=RS("MG_hindo")%>" maxlength="30"></td>
          </tr>
          <tr>
            <td class="m3">申告発行部数</td>
            <td class="n3"><input type="text" name="busuu" size="20" value="<%=RS("MG_busuu")%>" maxlength="30"></td>
          </tr>
          <tr>
            <td class="m3">広告罫線</td>
            <td class="n3">
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
            <td class="m3">１行の文字数</td>
            <td class="n3"><input type="text" name="moji_suu" size="20" value="<%=RS("MG_moji_suu")%>"></td>
          </tr>
          <tr>
            <td class="m3">メルマガ紹介文</td>
            <td class="n3"><textarea name="shoukai_text" cols=60 rows=5><%=RS("MG_shoukai_text")%></textarea></td>
          </tr>
          <tr>
            <td class="m3">購読用ＵＲＬ</td>
            <td class="n3"><input type="text" name="koudoku_url" size="60" value="<%=RS("MG_koudoku_url")%>" maxlength="100">
<%
If Len(RS("MG_koudoku_url")) > 7 Then
  Writeln "<input type='button' value='参照' onClick='window.open(document.form1.koudoku_url.value, ""koudoku"")'>"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m3">詳細説明用ＵＲＬ</td>
            <td class="n3"><input type="text" name="shousai_url" size="60" value="<%=RS("MG_shousai_url")%>" maxlength="100">
<%
If Len(RS("MG_shousai_url")) > 7 Then
  Writeln "<input type='button' value='参照' onClick='window.open(document.form1.shousai_url.value, ""shousai"")'>"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m3">読者向け紹介</td>
            <td class="n3">
              <input type="checkbox" name="shoukai_flag1" value="1" <%=Checked(RS("MG_shoukai_flag1"))%>>掲載を希望する（発行者設定）　
              <input type="checkbox" name="shoukai_flag2" value="1" <%=Checked(RS("MG_shoukai_flag2"))%>>掲載する（事務局設定）
            </td>
          </tr>
          <tr>
            <td class="m3">めろんぱんID</td>
            <td class="n3"><input type="text" name="melonpan_id" size="20" value="<%=RS("MG_melonpan_id")%>" maxlength="20"></td>
          </tr>
          <tr>
            <td class="m3">発行システム</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=130><input type="checkbox" name="mms_melonpan" value="1" <%=Checked(RS("MG_mms_melonpan"))%>>めろんぱん</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_mag" value="1" <%=Checked(RS("MG_mms_mag"))%>>まぐまぐ</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_pubz" value="1" <%=Checked(RS("MG_mms_pubz"))%>>Pubzine</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_melma" value="1" <%=Checked(RS("MG_mms_melma"))%>>melma!</td>
              </tr>
              <tr>
              <td class="n3c" width=130><input type="checkbox" name="mms_macky" value="1" <%=Checked(RS("MG_mms_macky"))%>>Macky</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_kapu" value="1" <%=Checked(RS("MG_mms_kapu"))%>>カプライト</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_emag" value="1" <%=Checked(RS("MG_mms_emag"))%>>E-MAGAZINE</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_tengoku" value="1" <%=Checked(RS("MG_mms_tengoku"))%>>メルマガ天国</td>
              </tr>
              <tr>
              <td class="n3c" width=130><input type="checkbox" name="mms_tiara" value="1" <%=Checked(RS("MG_mms_tiara"))%>>ティアラオンライン</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_merubox" value="1" <%=Checked(RS("MG_mms_merubox"))%>>MERU-BOX</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_dokuji" value="1" <%=Checked(RS("MG_mms_dokuji"))%>>独自配信</td>
              <td class="n3c" width=130><input type="checkbox" name="mms_other" value="1" <%=Checked(RS("MG_mms_other"))%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m3">メルマガカテゴリ</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_int" value="1" <%=Checked(RS("MG_cat_int"))%>>インターネット</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_pcc" value="1" <%=Checked(RS("MG_cat_pcc"))%>>パソコン/コンピュータ全般</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_soft" value="1" <%=Checked(RS("MG_cat_soft"))%>>ソフトウェア/プログラミング</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_mvl" value="1" <%=Checked(RS("MG_cat_mvl"))%>>携帯/モバイル</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_bns" value="1" <%=Checked(RS("MG_cat_bns"))%>>ビジネス全般</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_seiji" value="1" <%=Checked(RS("MG_cat_seiji"))%>>政治/経済</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_kabu" value="1" <%=Checked(RS("MG_cat_kabu"))%>>株式/投資</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_fsn" value="1" <%=Checked(RS("MG_cat_fsn"))%>>ファッション/美容</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_lif" value="1" <%=Checked(RS("MG_cat_lif"))%>>暮らし/日記</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_shumi" value="1" <%=Checked(RS("MG_cat_shumi"))%>>趣味</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_res" value="1" <%=Checked(RS("MG_cat_res"))%>>グルメ/レストラン</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_tvl" value="1" <%=Checked(RS("MG_cat_tvl"))%>>旅行/地域</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_spt" value="1" <%=Checked(RS("MG_cat_spt"))%>>スポーツ/レジャー</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_gamble" value="1" <%=Checked(RS("MG_cat_gamble"))%>>ギャンブル</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_hlt" value="1" <%=Checked(RS("MG_cat_hlt"))%>>健康/医療/育児</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_art" value="1" <%=Checked(RS("MG_cat_art"))%>>アート/文芸</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_shp" value="1" <%=Checked(RS("MG_cat_shp"))%>>ショッピング</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_ent" value="1" <%=Checked(RS("MG_cat_ent"))%>>エンターテイメント</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_game" value="1" <%=Checked(RS("MG_cat_game"))%>>ゲーム/クイズ</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_movie" value="1" <%=Checked(RS("MG_cat_movie"))%>>映画/音楽/芸能</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_uranai" value="1" <%=Checked(RS("MG_cat_uranai"))%>>占い</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_std" value="1" <%=Checked(RS("MG_cat_std"))%>>学習/教育</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_shikaku" value="1" <%=Checked(RS("MG_cat_shikaku"))%>>資格</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_gogaku" value="1" <%=Checked(RS("MG_cat_gogaku"))%>>語学</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_nws" value="1" <%=Checked(RS("MG_cat_nws"))%>>ニュース</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_zatsu" value="1" <%=Checked(RS("MG_cat_zatsu"))%>>雑学</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_kenshou" value="1" <%=Checked(RS("MG_cat_kenshou"))%>>懸賞/プレゼント</td>
              </tr>
              <tr>
              <td class="n3c" width=170><input type="checkbox" name="cat_homepage" value="1" <%=Checked(RS("MG_cat_homepage"))%>>ホームページ紹介</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_hitokoto" value="1" <%=Checked(RS("MG_cat_hitokoto"))%>>一日ひとこと系</td>
              <td class="n3c" width=170><input type="checkbox" name="cat_other" value="1" <%=Checked(RS("MG_cat_other"))%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m3">読者層（年代）</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=90><input type="checkbox" name="age_10m" value="1" <%=Checked(RS("MG_age_10m"))%>>10代男</td>
              <td class="n3c" width=90><input type="checkbox" name="age_20m" value="1" <%=Checked(RS("MG_age_20m"))%>>20代男</td>
              <td class="n3c" width=90><input type="checkbox" name="age_30m" value="1" <%=Checked(RS("MG_age_30m"))%>>30代男</td>
              <td class="n3c" width=90><input type="checkbox" name="age_40m" value="1" <%=Checked(RS("MG_age_40m"))%>>40代男</td>
              <td class="n3c" width=90><input type="checkbox" name="age_50m" value="1" <%=Checked(RS("MG_age_50m"))%>>50代以上男</td>
              </tr>
              <tr>
              <td class="n3c" width=90><input type="checkbox" name="age_10f" value="1" <%=Checked(RS("MG_age_10f"))%>>10代女</td>
              <td class="n3c" width=90><input type="checkbox" name="age_20f" value="1" <%=Checked(RS("MG_age_20f"))%>>20代女</td>
              <td class="n3c" width=90><input type="checkbox" name="age_30f" value="1" <%=Checked(RS("MG_age_30f"))%>>30代女</td>
              <td class="n3c" width=90><input type="checkbox" name="age_40f" value="1" <%=Checked(RS("MG_age_40f"))%>>40代女</td>
              <td class="n3c" width=90><input type="checkbox" name="age_50f" value="1" <%=Checked(RS("MG_age_50f"))%>>50代以上女</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m3">読者層（職業）</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=100><input type="checkbox" name="shk_stu" value="1" <%=Checked(RS("MG_shk_stu"))%>>学生</td>
              <td class="n3c" width=100><input type="checkbox" name="shk_enp" value="1" <%=Checked(RS("MG_shk_enp"))%>>社会人</td>
              <td class="n3c" width=100><input type="checkbox" name="shk_syf" value="1" <%=Checked(RS("MG_shk_syf"))%>>主婦</td>
              <td class="n3c" width=100><input type="checkbox" name="shk_other" value="1" <%=Checked(RS("MG_shk_other"))%>>その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m3">読者層（エリア）</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n3c" width=100><input type="checkbox" name="are_ht" value="1" <%=Checked(RS("MG_are_ht"))%>>北海道/東北</td>
              <td class="n3c" width=100><input type="checkbox" name="are_kt" value="1" <%=Checked(RS("MG_are_kt"))%>>関東</td>
              <td class="n3c" width=100><input type="checkbox" name="are_cb" value="1" <%=Checked(RS("MG_are_cb"))%>>中部</td>
              <td class="n3c" width=100><input type="checkbox" name="are_kk" value="1" <%=Checked(RS("MG_are_kk"))%>>近畿</td>
              </tr>
              <tr>
              <td class="n3c" width=100><input type="checkbox" name="are_cs" value="1" <%=Checked(RS("MG_are_cs"))%>>中国/四国</td>
              <td class="n3c" width=100><input type="checkbox" name="are_ks" value="1" <%=Checked(RS("MG_are_ks"))%>>九州</td>
              <td class="n3c" width=100><input type="checkbox" name="are_fr" value="1" <%=Checked(RS("MG_are_fr"))%>>海外</td>
              <td class="n3c" width=100><input type="checkbox" name="are_zk" value="1" <%=Checked(RS("MG_are_zk"))%>>全国</td>
              </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>■その他の情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="22%">発行者</td>
            <td class="n3"><a href="mel_master2.asp?id=<%=RS("MG_mel_id")%>"><%=RS("MG_mel_id")%></a>&nbsp;<%=ReplaceTag(RS("MM_h_name"))%></td>
          </tr>
          <tr>
            <td class="m3" width="22%">週発行係数</td>
            <td class="n3"><input type="text" name="shuu_keisuu" value="<%=RS("MG_shuu_keisuu")%>"></td>
          </tr>
          <tr>
            <td class="m3">まぐまぐ係数</td>
            <td class="n3"><input type="text" name="magmag_keisuu" value="<%=FormatNumber(NVL(RS("MG_magmag_keisuu"), 0) * 100, 1)%>"> %</td>
          </tr>
          <tr>
            <td class="m3">最新発行部数</td>
            <td class="n3"><input type="text" name="new_busuu" value="<%=RS("MG_new_busuu")%>"></td>
          </tr>
          <tr>
            <td class="m3">状態</td>
            <td class="n3">
              <select name="status">
                <%status = RS("MG_status")%>
                <option value="0" <%=Selected(status, 0)%>>活動中</option>
                <option value="1" <%=Selected(status, 1)%>>休止中</option>
                <option value="2" <%=Selected(status, 2)%>>廃刊</option>
                <option value="3" <%=Selected(status, 3)%>>強制停止（事務局判断・部数の虚偽申告）</option>
                <option value="4" <%=Selected(status, 4)%>>強制停止（事務局判断・広告が多すぎる）</option>
                <option value="5" <%=Selected(status, 5)%>>強制停止（事務局判断・内容がよろしくない）</option>
                <option value="8" <%=Selected(status, 8)%>>発行者休会中</option>
                <option value="9" <%=Selected(status, 9)%>>発行者退会</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="m3">登録日</td>
            <td class="n3"><%=TimeFormat(RS("MG_regist_date"))%></td>
          </tr>
          <tr>
            <td class="m3">最終更新日</td>
            <td class="n3"><%=TimeFormat(RS("MG_last_update"))%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" 更新 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 削除 " onClick="OnClick_sakujo()">
  <input type="button" value=" 戻る " onClick="history.back()">

</form>
</center>

<%
RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
