//**********************************************************************
//		アンケート集計システム
//
//		Title:		関心事別集計処理
//		FileName:	body06.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/06/25
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

public class body06 extends BaseServlet {

	// "GET"メソッド処理
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// ContentType設定およびPrintWriter取得
			PrintWriter pw = GetPrintWriter(response);

      // パラメータ取得クラス
      Param param = new Param(request);

			Html(pw, param);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// "GET"メソッドhtml出力
	public void Html(PrintWriter pw, Param param) 
			throws ServletException, IOException, SQLException {

    // アンケートＩＤを取得
    String id = param.GetParam("id");
    if (id == null)
      return;

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

    // アンケートマスタより期間とテーブル名を取得
		String sql = "select * from M_ENQUETE where MEN_ENQUETE_MID='" + id + "'";
		ResultSet rs = st.executeQuery(sql);
    if (rs.next() == false) {
      ErrorMessage(pw, "指定したアンケートＩＤは登録されていません。");
      return;
    }
    Date from_date = rs.getDate("MEN_FROM_DATE");
    Date to_date = rs.getDate("MEN_TO_DATE");
    String table = rs.getString("MEN_TABLE_NAME");
    rs.close();

    // 関心事別集計
    int[] count = new int[36];
    sql = "select DEN_Q1FLAG, count(*) as CNT"
        + " from " + table
        + " where DEN_ENQUETE_MID='" + id + "'"
        + " and DEN_ENQUETE_DATE between '" + from_date + "' and '" + to_date + "'"
        + " group by DEN_Q1FLAG";
		rs = st.executeQuery(sql);
    while (rs.next()) {
      String flag = rs.getString("DEN_Q1FLAG");
      int cnt = rs.getInt("CNT");
      for (int i = 0; i < count.length; i++) {
        if (i < flag.length()) {
          if (flag.charAt(i) == '1')
            count[i] += cnt;
        }
      }
    }

    rs.close();
    st.close();

    pw.println("<html>");
    pw.println("<head>");
    pw.println("<title>関心事別集計</title>");
    pw.println("<meta http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("</head>");
    pw.println("<BODY bgcolor='#ffffff' marginwidth=10 marginheight=10 topmargin='10' leftmargin='10' text ='#222222'>");
    pw.println("<p><font size='4'>■<b>関心事別　登録状況</b></font> </p>");
    pw.println("<table border='0' width='400' cellspacing='2' cellpadding='4'>");
    pw.println("<tr bgcolor='#55555'>");
    pw.println("<td nowrap colspan=2>");
    pw.println("<font color='#ffffff'><b>音楽</b></font>");
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("J-POP");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[0]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("洋楽ROCK&POPS");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[1]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("洋楽DANCE&SOUL");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[2]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("ジャズ");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[3]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("クラシック");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[4]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("演歌・歌謡曲");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[5]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#55555'>");
    pw.println("<td nowrap colspan=2>");
    pw.println("<font color='#ffffff'><b>スポーツ</b></font>");
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("野球");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[6]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("サッカー");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[7]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("ゴルフ");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[8]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("テニス");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[9]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("陸上");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[10]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("マリンスポーツ");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[11]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("ウィンタースポーツ（スキー等）");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[12]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("相撲");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[13]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("プロレス・格闘技");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[14]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("モータースポーツ");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[15]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("フィッシング");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[35]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("スポーツ：その他");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[16]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#55555'>");
    pw.println("<td nowrap colspan=2>");
    pw.println("<font color='#ffffff'><b>趣味</b></font>");
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("映画");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[17]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("アート");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[18]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("文学・文芸（読書）");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[19]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("マンガ・アニメ");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[20]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("パソコン");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[21]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("ゲーム");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[22]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("車・オートバイ");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[23]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("国内旅行");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[24]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("海外旅行");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[25]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("ドライブ・ツーリング");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[26]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("キャンプ・アウトドア");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[27]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("財テク・マネー");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[28]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("健康・フィットネス");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[29]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("ペット");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[30]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("料理・グルメ");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[31]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("ファッション");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[32]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("ショッピング");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[33]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("その他");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[34]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("</table>");
    pw.println("</body>");
    pw.println("</html>");
  }
}
