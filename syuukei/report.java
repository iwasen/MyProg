//**********************************************************************
//		アンケート集計システム
//
//		Title:		フレーム処理
//		FileName:	report.java
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

public class report extends BaseServlet {

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

    String id = param.GetParam("id");
    if (id == null) {
      ErrorMessage(pw, "アンケートＩＤが指定されていません。");
      return;
    }

    pw.println("<html>");
    pw.println("<head>");
    pw.println("<title>リアルタイム集計</title>");
    pw.println("<meta http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("</head>");
    pw.println("<frameset rows='270,*' cols='*' frameborder='NO'>");
    pw.println("<frame src='title?id=" + id + "' frameborder='NO'  name='menu'>");
    pw.println("<frameset cols='170,*' rows='*'>");
    pw.println("<frame src='menu?id=" + id + "' name='left' frameborder='NO'>");
    pw.println("<frame src='body01?id=" + id + "' name='body' frameborder='NO'>");
    pw.println("</frameset>");
    pw.println("</frameset>");
    pw.println("<noframes><body bgcolor='#FFFFFF'>");
    pw.println("</body></noframes>");
    pw.println("</html>");
  }
}
