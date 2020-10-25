//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		バスケットシステム初期設定処理
//		FileName:	entry.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.ServletException;

// バスケットシステム入り口処理
public class entry extends HttpServlet
{
	// service()オーバーライド
	public void service(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		// セッションを新規に作成
		HttpSession session = request.getSession(true);

		// オーダ情報を作成し、セッションに保存
		Order order = new Order();
		session.putValue("order", order);

		// 商品分類の初期値をオーダ情報に保存
		Param param = new Param(request);
		String bunrui = param.GetParam("bunrui");
		order.bunrui = bunrui == null ? "" : bunrui;

		// 検索条件指定画面へリダイレクト
		response.sendRedirect("Kensaku");
	}
}
