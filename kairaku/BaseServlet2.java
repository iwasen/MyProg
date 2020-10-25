//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		バスケットシステム基本クラス
//		FileName:	BaseServlet2.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.io.OutputStreamWriter;
import java.util.StringTokenizer;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

// バスケットシステム画面共通基本クラス
public abstract class BaseServlet2 extends HttpServlet {

	// 出力文字コード
	protected final String Encoding = "iso-2022-jp";

	// 快楽市場トップページURL
	protected final String TopPage = "/index.html";

	// 画像ファイルパス
	protected final String pictLogicalPath = "/pict/";

	// "GET"メソッド処理
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// ContentType設定およびPrintWriter取得
			response.setContentType("text/html; charset=" + Encoding);
			//PrintWriter pw = response.getWriter();
			PrintWriter pw = new PrintWriter(new OutputStreamWriter(response.getOutputStream(), response.getCharacterEncoding()), true);

			// セッション取得
			HttpSession session = request.getSession(false);
			if (session == null) {
				SessionFailure(response);
				return;
			}

			// セッションからオーダ情報取得
			Order order = (Order)session.getValue("order");
			if (order == null) {
				SessionFailure(response);
				return;
			}

			// 入力パラメータ取得クラス生成
			Param param = new Param(request);

			// doGet()個別処理
			doGetHandler(response, pw, session, param, order);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// doGet()個別処理のデフォルト処理（何もしない）
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {
	}

	// "POST"メソッド処理
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// ContentType設定およびPrintWriter取得
			response.setContentType("text/html; charset=iso-2022-jp");
			//PrintWriter pw = response.getWriter();
			PrintWriter pw = new PrintWriter(new OutputStreamWriter(response.getOutputStream(), response.getCharacterEncoding()), true);

			// セッション取得
			HttpSession session = request.getSession(false);
			if (session == null) {
				SessionFailure(response);
				return;
			}

			// セッションからオーダ情報取得
			Order order = (Order)session.getValue("order");
			if (order == null) {
				SessionFailure(response);
				return;
			}

			// 入力パラメータ取得クラス生成
			Param param = new Param(request);

			// doPost()個別処理
			doPostHandler(response, pw, session, param, order);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// doPost()個別処理のデフォルト処理（何もしない）
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {
	}

	// ヘッダ出力処理（title と見出しが同じ）
	protected void Header(PrintWriter pw, String title, String bodyOpt) {
		Header(pw, title, title, bodyOpt);
	}

	// ヘッダ出力処理（title と見出しが別）
	protected void Header(PrintWriter pw, String title, String pagename, String bodyOpt) {
		pw.println("<html>");
		pw.println("<head>");
		pw.println("<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=" + Encoding + "'>");
		pw.println("<META HTTP-EQUIV='Pragma' CONTENT='no-cache'>");
		pw.println("<link rel='stylesheet' href='/css.txt'>");
		pw.println("<title>"+title+"</title>");
		pw.println("</head>");
		pw.println("<body " + bodyOpt + ">");
		pw.println("<h2>" + pagename + "</h2>");
		pw.println("<p>");
	}

	// フッタ出力処理
	protected void Footer(PrintWriter pw) {
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

	// セッション切断時の処理（現在は入り口に戻すだけ）
	protected void SessionFailure(HttpServletResponse response)
			throws IOException {

/*
		Init(response);
		Header("Timeout", "", "bgcolor='#CCFFFF'");
		pw.println("<h2>情報が失われたため処理を継続できません。<br>");
		pw.println("申し訳ありませんが、もう一度最初から入力してください。</h2>");
		pw.println("<a href='Kensaku'>商品検索ページに戻る</a>");
		Footer();
*/
		response.sendRedirect("entry");
	}

	// ブランクの項目は<br>を出力する（枠を表示させるため）
	protected String ConvertBlank(String text) {

		text = text.trim();
		if (text == null || text.equals(""))
			text = "<br>";

		return text;
	}

	// データに改行があったら <br> に変換する
	protected String ConvertCRLF(String in) {
		StringTokenizer st = new StringTokenizer(in, "\n");
		String out = "";

		int count = st.countTokens();
		while (--count >= 0) {
			out += st.nextToken() + "<br>";
		}

		return out;
	}
}
