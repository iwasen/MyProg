//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		マスターメンテナンス基本クラス
//		FileName:	BaseServlet1.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.io.OutputStreamWriter;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// マスターメンテナンス共通のクラス
public class BaseServlet1 extends HttpServlet {

	// 出力文字コード
	protected final String Encoding = "iso-2022-jp";

	// 快楽市場トップページURL
	protected final String TopPage = "master";

	// PrintWriter 取得処理
	protected PrintWriter GetPrintWriter(HttpServletResponse response)
			throws IOException {

		response.setContentType("text/html; charset=" + Encoding);
		return new PrintWriter(new OutputStreamWriter(response.getOutputStream(), response.getCharacterEncoding()), true);
		//return response.getWriter();
	}

	// ログインチェック
	public boolean LoginCheck(HttpServletRequest request)
			throws IOException {

		MyUtil myutil = new MyUtil();
		String login = myutil.GetCookieValue(request, "MasterLogin");

		if (login != null && login.equals("ok"))
			return true;
		else
			return false;
	}

	// ヘッダ出力処理（title と見出しが同じ）
	protected void Header(PrintWriter pw, String title) {
		Header(pw, title, title);
	}

	// ヘッダ出力処理（title と見出しが別）
	protected void Header(PrintWriter pw, String title, String pagename) {
		pw.println("<html>");
		pw.println("<head>");
		pw.println("<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=" + Encoding + "'>");
		pw.println("<META HTTP-EQUIV='Pragma' CONTENT='no-cache'>");
		//pw.println("<META HTTP-EQUIV='Cache-Control' CONTENT='no-cache'>");
		pw.println("<title>"+title+"</title>");
		pw.println("<style type='TEXT/CSS'>");
		pw.println("<!--");
		pw.println("div.master {margin: 0% 2%}");
		pw.println("-->");
		pw.println("</style>");
		pw.println("</head>");
		pw.println("<body bgcolor='#efefef' text=Black link=blue vlink=blue alink=red>");
		pw.println("<center>");
		pw.println("<table cellspacing=0 cellpadding=0 width='100%'>");
		pw.println("<tr><td align=left valign=bottom norap>");
		pw.println("<small><B>最終更新日:</B> May 12, 2000</small>");
		pw.println("</td><td align=right valign=bottom norap>");
		pw.println("<small><font color=Blue>■</font>");
		pw.println("<a href='"+ TopPage +"'>トップへ戻る</a>");
		pw.println("</small>");
		pw.println("</td></tr></table>");
		pw.println("<hr>");
		pw.println("<table border=2 width='90%' bgcolor='darkblue' cellspacing=1 cellpadding=6>");
		pw.println("<tr><th>");
		pw.println("<font color=White size=6>" + pagename + "</font>");
		pw.println("</th></tr></table>");
		pw.println("<hr>");
		pw.println("</center>");
		pw.println("<div class='master'>");
	}

	// フッタ出力処理
	protected void Footer(PrintWriter pw) {
		pw.println("</div>");
		pw.println("<center>");
		pw.println("<hr>");
		pw.println("<table cellspacing=0 cellpadding=0 width=100% border=0>");
		pw.println("<tr valign=bottom><td align=right valign=bottom nowrap>");
		pw.println("<font color=#0000ff>■</font>");
		pw.println("<a href='"+ TopPage +"'>トップへ戻る</a>");
		pw.println("</td></tr>");
		pw.println("</table>");
		pw.println("Copyright(C) 2000 快楽市場 Co.,Ltd. All rights reserved.");
		pw.println("</center>");
		pw.println("</body>");
		pw.println("</html>");
	}

	// エラー発生時用メッセージ出力
	protected void Failure(PrintWriter pw, String reason) {
		pw.println("<br>");
		pw.println("以下の原因を参考にして下さい。:");
		pw.println("<p>");
		pw.println(reason);
		pw.println("<p>");
		pw.println("なお、失敗の理由が不明の場合は ");
		pw.println("<a href='mailto:servlet-admin@java-conf.gr.jp'>");
		pw.println("サーバ管理人</a> までお知らせ下さい。");
	}

	// JavaScript出力処理
	protected void JavaScript(PrintWriter pw, String[] script) {
		int i;

		pw.println("<script language='JavaScript'><!--");

		for (i = 0; i < script.length; i++) {
			pw.println(script[i]);
		}

		pw.println("// --></script>");
	}
}
