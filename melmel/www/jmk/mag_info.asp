<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:メルマガ情報参照画面
'******************************************************

SetHeaderTitle "メルマガ情報", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
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
function OnClick_disp(id) {
  var win;
  win = window.open("melmag_disp.asp?id=" + id, "melmag_disp");
  win.focus();
}
function OnClick_mel_id(id) {
  var win;
  win = window.open("mel_info.asp?id=" + id, "mel_info", "resizable=yes,scrollbars=yes,width=740");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<%
Dim DB, RS, SQL, mag_id

mag_id = Request.QueryString("id")
If IsEmpty(mag_id) Then
  Writeln "指定したメルマガＩＤのデータがみつかりません。"
Else
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT *" & vbCrLf & _
        "FROM (T_MELMAG LEFT OUTER JOIN T_KEISEN ON MG_keisen_id=KE_keisen_id) LEFT OUTER JOIN T_MELMEL ON MG_mel_id=MM_mel_id" & vbCrLf & _
        "WHERE MG_mag_id='" & mag_id & "'"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>■メルマガ登録情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">メルマガID</td>
            <td class="n1"><%=RS("MG_mag_id")%></td>
          </tr>
          <tr>
            <td class="m1">メルマガ名</td>
            <td class="n1"><%=ReplaceTag(RS("MG_m_name"))%>　<input type="button" value="表示" onClick="OnClick_disp('<%=RS("MG_mag_id")%>')"></td>
          </tr>
          <tr>
            <td class="m1">発行頻度</td>
            <td class="n1"><%=ReplaceTag(RS("MG_hindo"))%></td>
          </tr>
          <tr>
            <td class="m1">申告発行部数</td>
            <td class="n1"><%=NumFormat(RS("MG_busuu"))%></td>
          </tr>
          <tr>
            <td class="m1">広告罫線</td>
						<td class="n1"><font class="np"><%=ReplaceTag(ZenkakuRight(RS("KE_start_line"), 35))%></font></td>
          </tr>
          <tr>
            <td class="m1">１行の文字数</td>
            <td class="n1"><%=RS("MG_moji_suu")%></td>
          </tr>
          <tr>
            <td class="m1">メルマガ紹介文</td>
            <td class="n1"><textarea name="shoukai_text" cols=60 rows=5 readonly><%=RS("MG_shoukai_text")%></textarea></td>
          </tr>
          <tr>
            <td class="m1">購読用ＵＲＬ</td>
            <td class="n1">
<%
If Len(RS("MG_koudoku_url")) > 7 Then
	Writeln "<a href='" & RS("MG_koudoku_url") & "' target='koudoku'>" & ReplaceTag(RS("MG_koudoku_url")) & "</a>"
Else
	Writeln ReplaceTag(RS("MG_koudoku_url"))
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">詳細説明用ＵＲＬ</td>
            <td class="n1">
<%
If Len(RS("MG_koudoku_url")) > 7 Then
	Writeln "<a href='" & RS("MG_shousai_url") & "' target='koudoku'>" & ReplaceTag(RS("MG_shousai_url")) & "</a>"
Else
	Writeln ReplaceTag(RS("MG_shousai_url"))
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">読者向け紹介</td>
            <td class="n1">
<%
If RS("MG_shoukai_flag1") Then
	Response.Write "・掲載を希望する（発行者設定）"
End If
Response.Write "　"
If RS("MG_shoukai_flag2") Then
	Response.Write "・掲載する（事務局設定）"
End If
%>
            </td>
          </tr>
          <tr>
            <td class="m1">めろんぱんID</td>
            <td class="n1"><%=RS("MG_melonpan_id")%></td>
          </tr>
          <tr>
            <td class="m1">発行システム</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_melonpan"<%=Checked(RS("MG_mms_melonpan"))%> onclick="return false">めろんぱん</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_mag"<%=Checked(RS("MG_mms_mag"))%> onclick="return false">まぐまぐ</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_pubz"<%=Checked(RS("MG_mms_pubz"))%> onclick="return false">Pubzine</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_melma"<%=Checked(RS("MG_mms_melma"))%> onclick="return false">melma!</td>
              </tr>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_macky"<%=Checked(RS("MG_mms_macky"))%> onclick="return false">Macky</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_kapu"<%=Checked(RS("MG_mms_kapu"))%> onclick="return false">カプライト</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_emag"<%=Checked(RS("MG_mms_emag"))%> onclick="return false">E-MAGAZINE</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_tengoku"<%=Checked(RS("MG_mms_tengoku"))%> onclick="return false">メルマガ天国</td>
              </tr>
              <tr>
              <td class="n1c" width=130><input type="checkbox" name="mms_tiara"<%=Checked(RS("MG_mms_tiara"))%> onclick="return false">ティアラオンライン</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_merubox"<%=Checked(RS("MG_mms_merubox"))%> onclick="return false">MERU-BOX</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_dokuji"<%=Checked(RS("MG_mms_dokuji"))%> onclick="return false">独自配信</td>
              <td class="n1c" width=130><input type="checkbox" name="mms_other"<%=Checked(RS("MG_mms_other"))%> onclick="return false">その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">メルマガカテゴリ</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_int"<%=Checked(RS("MG_cat_int"))%> onclick="return false">インターネット</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_pcc"<%=Checked(RS("MG_cat_pcc"))%> onclick="return false">パソコン/コンピュータ全般</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_soft"<%=Checked(RS("MG_cat_soft"))%> onclick="return false">ソフトウェア/プログラミング</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_mvl"<%=Checked(RS("MG_cat_mvl"))%> onclick="return false">携帯/モバイル</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_bns"<%=Checked(RS("MG_cat_bns"))%> onclick="return false">ビジネス全般</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_seiji"<%=Checked(RS("MG_cat_seiji"))%> onclick="return false">政治/経済</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_kabu"<%=Checked(RS("MG_cat_kabu"))%> onclick="return false">株式/投資</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_fsn"<%=Checked(RS("MG_cat_fsn"))%> onclick="return false">ファッション/美容</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_lif"<%=Checked(RS("MG_cat_lif"))%> onclick="return false">暮らし/日記</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_shumi"<%=Checked(RS("MG_cat_shumi"))%> onclick="return false">趣味</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_res"<%=Checked(RS("MG_cat_res"))%> onclick="return false">グルメ/レストラン</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_tvl"<%=Checked(RS("MG_cat_tvl"))%> onclick="return false">旅行/地域</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_spt"<%=Checked(RS("MG_cat_spt"))%> onclick="return false">スポーツ/レジャー</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_gamble"<%=Checked(RS("MG_cat_gamble"))%> onclick="return false">ギャンブル</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_hlt"<%=Checked(RS("MG_cat_hlt"))%> onclick="return false">健康/医療/育児</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_art"<%=Checked(RS("MG_cat_art"))%> onclick="return false">アート/文芸</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_shp"<%=Checked(RS("MG_cat_shp"))%> onclick="return false">ショッピング</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_ent"<%=Checked(RS("MG_cat_ent"))%> onclick="return false">エンターテイメント</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_game"<%=Checked(RS("MG_cat_game"))%> onclick="return false">ゲーム/クイズ</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_movie"<%=Checked(RS("MG_cat_movie"))%> onclick="return false">映画/音楽/芸能</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_uranai"<%=Checked(RS("MG_cat_uranai"))%> onclick="return false">占い</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_std"<%=Checked(RS("MG_cat_std"))%> onclick="return false">学習/教育</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_shikaku"<%=Checked(RS("MG_cat_shikaku"))%> onclick="return false">資格</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_gogaku"<%=Checked(RS("MG_cat_gogaku"))%> onclick="return false">語学</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_nws"<%=Checked(RS("MG_cat_nws"))%> onclick="return false">ニュース</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_zatsu"<%=Checked(RS("MG_cat_zatsu"))%> onclick="return false">雑学</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_kenshou"<%=Checked(RS("MG_cat_kenshou"))%> onclick="return false">懸賞/プレゼント</td>
              </tr>
              <tr>
              <td class="n1c" width=170><input type="checkbox" name="cat_homepage"<%=Checked(RS("MG_cat_homepage"))%> onclick="return false">ホームページ紹介</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_hitokoto"<%=Checked(RS("MG_cat_hitokoto"))%> onclick="return false">一日ひとこと系</td>
              <td class="n1c" width=170><input type="checkbox" name="cat_other"<%=Checked(RS("MG_cat_other"))%> onclick="return false">その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">読者層（年代）</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=90><input type="checkbox" name="age_10m"<%=Checked(RS("MG_age_10m"))%> onclick="return false">10代男</td>
              <td class="n1c" width=90><input type="checkbox" name="age_20m"<%=Checked(RS("MG_age_20m"))%> onclick="return false">20代男</td>
              <td class="n1c" width=90><input type="checkbox" name="age_30m"<%=Checked(RS("MG_age_30m"))%> onclick="return false">30代男</td>
              <td class="n1c" width=90><input type="checkbox" name="age_40m"<%=Checked(RS("MG_age_40m"))%> onclick="return false">40代男</td>
              <td class="n1c" width=90><input type="checkbox" name="age_50m"<%=Checked(RS("MG_age_50m"))%> onclick="return false">50代以上男</td>
              </tr>
              <tr>
              <td class="n1c" width=90><input type="checkbox" name="age_10f"<%=Checked(RS("MG_age_10f"))%> onclick="return false">10代女</td>
              <td class="n1c" width=90><input type="checkbox" name="age_20f"<%=Checked(RS("MG_age_20f"))%> onclick="return false">20代女</td>
              <td class="n1c" width=90><input type="checkbox" name="age_30f"<%=Checked(RS("MG_age_30f"))%> onclick="return false">30代女</td>
              <td class="n1c" width=90><input type="checkbox" name="age_40f"<%=Checked(RS("MG_age_40f"))%> onclick="return false">40代女</td>
              <td class="n1c" width=90><input type="checkbox" name="age_50f"<%=Checked(RS("MG_age_50f"))%> onclick="return false">50代以上女</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">読者層（職業）</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="shk_stu"<%=Checked(RS("MG_shk_stu"))%> onclick="return false">学生</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_enp"<%=Checked(RS("MG_shk_enp"))%> onclick="return false">社会人</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_syf"<%=Checked(RS("MG_shk_syf"))%> onclick="return false">主婦</td>
              <td class="n1c" width=100><input type="checkbox" name="shk_other"<%=Checked(RS("MG_shk_other"))%> onclick="return false">その他</td>
              </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="m1">読者層（エリア）</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=0>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="are_ht"<%=Checked(RS("MG_are_ht"))%> onclick="return false">北海道/東北</td>
              <td class="n1c" width=100><input type="checkbox" name="are_kt"<%=Checked(RS("MG_are_kt"))%> onclick="return false">関東</td>
              <td class="n1c" width=100><input type="checkbox" name="are_cb"<%=Checked(RS("MG_are_cb"))%> onclick="return false">中部</td>
              <td class="n1c" width=100><input type="checkbox" name="are_kk"<%=Checked(RS("MG_are_kk"))%> onclick="return false">近畿</td>
              </tr>
              <tr>
              <td class="n1c" width=100><input type="checkbox" name="are_cs"<%=Checked(RS("MG_are_cs"))%> onclick="return false">中国/四国</td>
              <td class="n1c" width=100><input type="checkbox" name="are_ks"<%=Checked(RS("MG_are_ks"))%> onclick="return false">九州</td>
              <td class="n1c" width=100><input type="checkbox" name="are_fr"<%=Checked(RS("MG_are_fr"))%> onclick="return false">海外</td>
              <td class="n1c" width=100><input type="checkbox" name="are_zk"<%=Checked(RS("MG_are_zk"))%> onclick="return false">全国</td>
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
            <td class="m1" width="22%">発行者</td>
            <td class="n1"><a href="Javascript:OnClick_mel_id('<%=RS("MG_mel_id")%>')"><%=RS("MG_mel_id")%></a>&nbsp;<%=ReplaceTag(RS("MM_h_name"))%></td>
          </tr>
          <tr>
            <td class="m1">週発行係数</td>
            <td class="n1"><%=RS("MG_shuu_keisuu")%></td>
          </tr>
          <tr>
            <td class="m1">まぐまぐ係数</td>
						<td class="n1"><%=FormatNumber(NVL(RS("MG_magmag_keisuu"), 0) * 100, 1)%>%</td>
          </tr>
          <tr>
            <td class="m1">最新発行部数</td>
						<td class="n1"><%=NumFormat(RS("MG_new_busuu"))%></td>
          </tr>
          <tr>
            <td class="m1">状態</td>
            <td class="n1">
<%
Select Case RS("MG_status")
Case 0
	Response.Write "活動中"
Case 1
	Response.Write "休止中"
Case 2
	Response.Write "廃刊"
Case 3
	Response.Write "強制停止（事務局判断・部数の虚偽申告）"
Case 4
	Response.Write "強制停止（事務局判断・広告が多すぎる）"
Case 5
	Response.Write "強制停止（事務局判断・内容がよろしくない）"
Case 8
	Response.Write "発行者休会中"
Case 9
	Response.Write "発行者退会"
End Select
%>
            </td>
          </tr>
          <tr>
            <td class="m1">登録日</td>
            <td class="n1"><%=TimeFormat(RS("MG_regist_date"))%></td>
          </tr>
          <tr>
            <td class="m1">最終更新日</td>
            <td class="n1"><%=TimeFormat(RS("MG_last_update"))%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</center>

<%
  RS.Close
End If
%>

</body>
</html>
