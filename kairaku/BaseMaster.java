//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		マスター更新基本クラス
//		FileName:	BaseMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.text.*;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// コード、表示順、名称だけのマスター専用基本クラス
public abstract class BaseMaster extends BaseServlet1
{
	// "GET"メソッド処理
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
		throws ServletException, IOException {

		try {
			// ログインチェック
			if (!LoginCheck(request)) {
				// ログインしていない場合は強制的にトップページに戻す
				response.sendRedirect(TopPage);
				return;
			}

			PrintWriter pw = GetPrintWriter(response);
			Header(pw, GetTitle());
			Body(pw);
			Script(pw);
			Footer(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw)
		throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		ResultSet rs = st.executeQuery(GetSelectSql());

		// カラム名取得
		String[] header = GetHeader();

		// ヘッダ部出力
		pw.println("<form method='post' name='fm' onSubmit='return OnSubmit();'>");
		pw.println("<center>");
		pw.println("<table border=1 bgcolor='#E0FFE0'>");
		pw.println("<tr bgcolor='#FFE0E0'>");
		pw.println("<th>" + header[0] + "</th>");
		pw.println("<th>" + header[1] + "</th>");
		pw.println("<th>" + header[2] + "</th>");
		pw.println("</tr>");

		// レコード内容出力
		while (rs.next()) {
			pw.println("<tr>");
			pw.println("<td><input type='text' name='code' value='" + rs.getString(1).trim() + "' size=20></td>");
			pw.println("<td><input type='text' name='order' value='" + rs.getString(2).trim() + "' size=20></td>");
			pw.println("<td><input type='text' name='name' value='" + rs.getString(3).trim() + "' size=50></td>");
			pw.println("</tr>");
		}

		// 新規追加用の行を出力
		pw.println("<tr>");
		pw.println("<td><input type='text' name='code' size=20></td>");
		pw.println("<td><input type='text' name='order' size=20></td>");
		pw.println("<td><input type='text' name='name' size=50></td>");
		pw.println("</tr>");

		// ボタン出力
		pw.println("</table><br>");
		pw.println("<input type='submit' value='　更新　'>");
		pw.println("<input type='button' value='　終了　' onClick='location.href=\"master\"'>");
		pw.println("</center>");
		pw.println("</form>");

		// データベースクローズ
		rs.close();
		st.close();
	}

	// クライアント用JavaScrip出力
	private void Script(PrintWriter pw)
			throws ServletException, IOException {
		final String[] script = {
			"function OnSubmit() {",
			" return confirm('マスターを更新します。よろしいですか？')",
			"}",
		};

		JavaScript(pw, script);
	}

	// "POST" メソッド処理
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
		throws ServletException, IOException {

		try {
			// マスターテーブル更新
			String msg = UpdateMaster(request);
			if (msg == null)
				doGet(request, response);
			else {
				PrintWriter pw = GetPrintWriter(response);
				Header(pw, GetTitle());
				pw.println("<h2>更新でエラーが発生しました。</h2>");
				pw.println(msg + "<p>");
				pw.println("<input type='button' value='戻る' onClick='history.back()'>");
				Footer(pw);
			}
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// マスターテーブル更新処理
	private String UpdateMaster(HttpServletRequest request)
		throws ServletException, IOException, SQLException {

		// データベース接続
		Param param = new Param(request);
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// トランザクション開始
		db.Transaction();

		int i = 0;

		try {
			// 全レコード削除
			st.executeUpdate(GetDeleteSql());

			// 入力データ取得
			String[] code = param.GetParams("code");
			String[] order = param.GetParams("order");
			String[] name = param.GetParams("name");

			// 入力されたレコード数分Insert実行
			for (i = 0; i < code.length; i++) {
				if (!code[i].equals("")) {
					String sql = GetInsertSql() + " ('" + code[i] + "'," + order[i] + ",'" + name[i] + "')";
					st.executeUpdate(sql);
				}
			}

			// データベースクローズ
			st.close();

			// コミット
			db.Commit();
		} catch (SQLException e) {
			// エラーが発生した場合はロールバック
			db.Rollback();
			return e.toString();
		} catch (NumberFormatException e) {
			// 数値項目に数字以外が入力された場合
			db.Rollback();
			return (i + 1) + "行目の入力データに誤りがあります。";
		}

		return null;
	}

	// それぞれのマスターに合わせてカスタマイズ用の関数群
	protected abstract String GetTitle();
	protected abstract String GetSelectSql();
	protected abstract String GetDeleteSql();
	protected abstract String GetInsertSql();
	protected abstract String[] GetHeader();
}
