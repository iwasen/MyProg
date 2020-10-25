//**********************************************************************
//		アンケート集計システム
//
//		Title:		タイトルフレーム処理
//		FileName:	title.java
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

public class title extends BaseServlet {

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

    // SQL実行
		String sql = "select * from M_ENQUETE where MEN_ENQUETE_MID='" + id + "'";
		ResultSet rs = st.executeQuery(sql);
    if (rs.next() == false) {
      ErrorMessage(pw, "指定したアンケートＩＤは登録されていません。");
      return;
    }

    pw.println("<HTML>");
    pw.println("<HEAD>");
    pw.println("<META http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("<TITLE>リアルタイム集計</TITLE>");
    pw.println("</HEAD>");
    pw.println("<BODY bgcolor='#ffffff' marginwidth=10 marginheight=20 topmargin='20' leftmargin='10' text ='#222222'>");
    pw.println("<p><font size='5'>■<b>" + rs.getString("MEN_TITLE_TEXT") + "</b></font><div align=right><A HREF='/syuukei.html' target='_top'>↑トップページへ</A></div></p>");
    pw.println("<font size='4'><b>＜表示条件＞</b></font><br>");
    pw.println("<table border='0' width='565' cellspacing='2' cellpadding='4'>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' width='30%'>・メディア</td>");
    pw.println("<td bgcolor='#dddddd' width='70%'>" + rs.getString("MEN_MEDIA_TEXT") + "</td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' width='30%'>・媒体社</td>");
    pw.println("<td bgcolor='#dddddd' width='70%'>" + rs.getString("MEN_BAITAI_TEXT") + "</td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' width='30%'>・ビークル</td>");
    pw.println("<td bgcolor='#dddddd' width='70%'>" + rs.getString("MEN_VEHICLE_TEXT") + "</td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' width='30%'>・コーナー</td>");
    pw.println("<td bgcolor='#dddddd' width='70%'>" + rs.getString("MEN_CORNER_TEXT") + "</td>");
    pw.println("</tr>");
    pw.println("</table>");
    pw.println("</BODY>");
    pw.println("</HTML>");

    rs.close();
    st.close();
  }
}
