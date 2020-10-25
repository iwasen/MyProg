//**********************************************************************
//		アンケート集計システム
//
//		Title:		性・年代別集計処理
//		FileName:	body01.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/06/25
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Calendar;
import java.util.GregorianCalendar;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

public class body01 extends BaseServlet {

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

    // 全集計
    int total = 0;
    sql = "select count(*) as CNT"
        + " from " + table
        + " where DEN_ENQUETE_MID='" + id + "'"
        + " and DEN_ENQUETE_DATE between '" + from_date + "' and '" + to_date + "'";
    rs = st.executeQuery(sql);
    if (rs.next())
      total = rs.getInt("CNT");
    rs.close();

    // 性別集計
    int male = 0, female = 0;
    sql = "select DEN_SEIBETSU_CODE, count(*) as CNT"
        + " from " + table
        + " where DEN_ENQUETE_MID='" + id + "'"
        + " and DEN_ENQUETE_DATE between '" + from_date + "' and '" + to_date + "'"
        + " group by DEN_SEIBETSU_CODE";
		rs = st.executeQuery(sql);
    while (rs.next()) {
      String seibetsu = rs.getString("DEN_SEIBETSU_CODE");

      if (seibetsu != null) {
        if (seibetsu.equals("0"))
          male = rs.getInt("CNT");

        if (seibetsu.equals("1"))
          female = rs.getInt("CNT");
      }
    }
    rs.close();

    // 男女別パーセント
    int malep = 0, femalep = 0;
    if (total != 0) {
      malep = male * 100 / total;
      femalep = female * 100 / total;
    }

    // 性・年代別集計
    int[] age_male = new int[7];
    int[] age_female = new int[7];
    sql = "select DEN_SEIBETSU_CODE, int(date_part('year',age('now',den_seinengappi_date))) / 10 as AGE, count(*) as CNT"
        + " from " + table
        + " where DEN_ENQUETE_MID='" + id + "'"
        + " and DEN_ENQUETE_DATE between '" + from_date + "' and '" + to_date + "'"
        + " group by AGE,DEN_SEIBETSU_CODE";
		rs = st.executeQuery(sql);
    while (rs.next()) {
      String seibetsu = rs.getString("DEN_SEIBETSU_CODE");
      int n = rs.getInt("AGE");

      if (seibetsu != null && n >= 1 && n <= 6) {
        if (seibetsu.equals("0"))
          age_male[n] = rs.getInt("CNT");

        if (seibetsu.equals("1"))
          age_female[n] = rs.getInt("CNT");
      }
    }

    rs.close();
    st.close();

    Calendar today = new GregorianCalendar();

    pw.println("<html>");
    pw.println("<head>");
    pw.println("<title>性・年代別集計</title>");
    pw.println("<meta http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("</head>");
    pw.println("<BODY bgcolor='#ffffff' marginwidth=10 marginheight=10 topmargin='10' leftmargin='10' text ='#222222'>");
    pw.println("<p><font size='4'>■<b>性・年代別　登録状況</b></font> </p>");
    pw.println("<p><font size='4'><b>" + (today.get(Calendar.MONTH) + 1) + "月" + today.get(Calendar.DATE) + "日現在　登録者数" + total + "人</b></font> </p>");
    pw.println("<b>●男性" + male + "人</b></font>(" + malep + "%)<br>");
    pw.println("<b>　女性" + female + "人</b></font>(" + femalep + "%)<p>");
    pw.println("<b>●性・年代区切</b></font><br>");
    pw.println("<table border='0' width='400' cellspacing='2' cellpadding='4'>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap>");
    pw.println("　");
    pw.println("</td>");
    pw.println("<td bgcolor='#CAEDEC' nowrap>");
    pw.println("男性");
    pw.println("</td>");
    pw.println("<td bgcolor='#EDD1EE' nowrap>");
    pw.println("女性");
    pw.println("</td>");
    pw.println("</tr>");

    for (int i = 1; i <= 6; i++) {
      pw.println("<tr>");
      pw.println("<td bgcolor='#dddddd' nowrap>");
      pw.println(i * 10 + "代");
      pw.println("</td>");
      pw.println("<td bgcolor='#CAEDEC' nowrap>");
      pw.println(age_male[i]);
      pw.println("</td>");
      pw.println("<td bgcolor='#EDD1EE' nowrap>");
      pw.println(age_female[i]);
      pw.println("</td>");
      pw.println("</tr>");
    }
    pw.println("</table>");
    pw.println("</body>");
    pw.println("</html>");
  }
}
