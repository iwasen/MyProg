//**********************************************************************
//		アンケート集計システム
//
//		Title:		メニューフレーム処理
//		FileName:	menu.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/06/25
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

public class menu extends BaseServlet {

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

    pw.println("<html>");
    pw.println("<head>");
    pw.println("<title>無題ドキュメント</title>");
    pw.println("<meta http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("</head>");
    pw.println("<BODY bgcolor='#ffffff' marginwidth=10 marginheight=10 topmargin='10' leftmargin='10' text ='#222222' scroll='no'>");
    pw.println("<table border='0' width='100%' cellspacing='2' cellpadding='10'>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body01?id=" + id + "' target='body'>（1）性・年代別</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body02?id=" + id + "' target='body'>（2）日別</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body03?id=" + id + "' target='body'>（3）曜日・時間帯別</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body04?id=" + id + "' target='body'>（4）職業別</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body05?id=" + id + "' target='body'>（5）地域別</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body06?id=" + id + "' target='body'>（6）関心事別</A></b></td>");
    pw.println("</tr>");
    pw.println("</table>");
    pw.println("</body>");
    pw.println("</html>");
  }
}
