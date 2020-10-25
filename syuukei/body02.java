//**********************************************************************
//		アンケート集計システム
//
//		Title:		日別集計処理
//		FileName:	body02.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/06/25
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.*;
import java.util.Calendar;
import java.util.GregorianCalendar;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

public class body02 extends BaseServlet {

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

    pw.println("<html>");
    pw.println("<head>");
    pw.println("<title>日別集計</title>");
    pw.println("<meta http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("</head>");
    pw.println("<BODY bgcolor='#ffffff' marginwidth=10 marginheight=10 topmargin='10' leftmargin='10' text ='#222222'>");
    pw.println("<p><font size='4'>■<b>日別　登録状況</b></font> </p>");
    pw.println("<table border='0' width='400' cellspacing='2' cellpadding='4'>");

    // 日別集計
    sql = "select date_trunc('day',DEN_ENQUETE_DATE) as DATE, count(*) as CNT"
        + " from " + table
        + " where DEN_ENQUETE_MID='" + id + "'"
        + " and DEN_ENQUETE_DATE between '" + from_date + "' and '" + to_date + "'"
        + " group by DATE order by DATE";
		rs = st.executeQuery(sql);

    // 変数初期化
    String[] weekstr = {"日", "月", "火", "水", "木", "金", "土"};
    int line = 0;
    boolean read = rs.next();
    int count;
    Calendar date = new GregorianCalendar();
    date.setTime(from_date);
    date.set(Calendar.HOUR, 0);
    date.set(Calendar.MINUTE, 0);
    date.set(Calendar.SECOND, 0);

    // 期間(from)から期間(to)までループ
    for ( ; date.getTime().before(to_date); date.add(Calendar.DATE, 1)) {
      // その日の件数を取得
      count = 0;
      if (read) {
        if (date.getTime().equals(rs.getDate("DATE"))) {
          count = rs.getInt("CNT");
          read = rs.next();
        }
      }

      // 偶数行と奇数行で色を変える
      if (line++ % 2 == 0)
        pw.println("<tr bgcolor='#dddddd'>");
      else
        pw.println("<tr bgcolor='#eeeeee'>");

      pw.println("<td nowrap width=50%>");
      pw.println((date.get(Calendar.MONTH) + 1) + "月" + date.get(Calendar.DATE) + "日（" + weekstr[date.get(Calendar.DAY_OF_WEEK) - 1] + "）");
      pw.println("</td>");
      pw.println("<td nowrap width=50%>");
      pw.println(count);
      pw.println("</td>");
      pw.println("</tr>");
    }

    pw.println("</table>");
    pw.println("</body>");
    pw.println("</html>");

    rs.close();
    st.close();
  }
}
