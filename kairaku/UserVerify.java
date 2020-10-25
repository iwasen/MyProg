//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		登録情報確認処理
//		FileName:	UserVerify.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.*;
import java.text.*;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.ServletException;

// ユーザ登録情報確認ページ処理クラス
public class UserVerify extends BaseServlet2
{
	// "GET"メソッド処理
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "登録情報確認", "", "bgcolor='#CCFFFF'");
		Body(pw, order);
		Footer(pw);
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		Order.User user = order.user;

		pw.println("<p align='center' class='s5'><b>ユーザー登録確認</b></p>");
		pw.println("<p align='center' class='s1'><b>パスワードは、メールにて連絡します。<br>");
		pw.println("</b></p>");
		pw.println("<table width='699' border='2' cellspacing='1' cellpadding='6' height='317' bgcolor='#FFCCCC' bordercolordark='#666666' bordercolorlight='#FFFFFF' class='s1' align='center'>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>ＩＤ</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.UserID + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>ふりがな</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Furigana + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>氏名</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Shimei + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>性別</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Seibetsu + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>生年月日</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.SeiNen + " 年" + user.SeiTsuki + "月" + user.Sexxx + "日</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>年齢</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Nenrei + "歳</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>職業</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + myutil.GetShokugyouName(user.Shokugyou) + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>郵便番号</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Zip + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>住所</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Address + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap height='38'>");
		pw.println("<div align='right'>建物・マンション名</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + ConvertBlank(user.Tatemono) + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>連絡先電話番号</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.TelNo + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>FAX番号</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + ConvertBlank(user.FaxNo) + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>Eメールアドレス</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.EMail + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>あなたのホームページアドレス</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + ConvertBlank(user.Url) + "</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<form name='form1' method='post' class='s1'>");
		pw.println("<div align='center'>");
		pw.println("<input type='button' value='会員登録へもどる' onClick='history.back()'>");
		pw.println("<input type='submit' name='soushin' value=' 送信 '>");
		pw.println("</div>");
		pw.println("</form>");
		pw.println("<p>　</p>");
		pw.println("<p align='center' class='s1'>　</p>");
	}

	// "POST"メソッド処理
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		if (param.GetParam("soushin") != null) {
			if (StoreDB(order))
				SendRegistMail(order);

			response.sendRedirect("OrderList");
		}
	}

	// ユーザ登録情報保存処理
	private boolean StoreDB(Order order)
			throws ServletException, IOException, SQLException {

		Contrxxxaster cm = new Contrxxxaster();
		MyUtil myutil = new MyUtil();
		boolean result = false;

		// データベース接続
		DataBase db = new DataBase();
		db.Transaction();
		Statement st = db.createStatement();

		try {
			// ユーザ登録情報保存
			Order.User user = order.user;
			user.UserCode = cm.GetNewUserCode();
			String sql = "insert into M_USER values ("
					+ "'" + user.UserCode + "',"
					+ "'" + user.UserID + "',"
					+ "'" + user.Password + "',"
					+ "'" + user.Touroku + "',"
					+ "'" + user.Furigana + "',"
					+ "'" + user.Shimei + "',"
					+ "'" + user.Seibetsu + "',"
					+ user.SeiNen + ","
					+ user.SeiTsuki + ","
					+ user.Sexxx + ","
					+ user.Nenrei + ","
					+ user.Over18Flag + ","
					+ myutil.NullAble(user.Over18Date) + ","
					+ "'" + user.Shokugyou + "',"
					+ "'" + user.Zip + "',"
					+ "'" + user.Address + "',"
					+ "'" + user.Tatemono + "',"
					+ "'" + user.TelNo + "',"
					+ "'" + user.FaxNo + "',"
					+ "'" + user.EMail + "',"
					+ user.MailingList + ","
					+ "'" + user.Url + "')";
			st.executeUpdate(sql);

			// アンケート結果保存
			Order.Enquete enquete = order.enquete;
			enquete.EnqueteCode = cm.GetNewEnqueteCode();
			enquete.UserCode = user.UserCode;
			sql = "insert into F_ENQUETE values ("
					+ "'" + enquete.EnqueteCode + "',"
					+ "'" + enquete.UserCode + "',"
					+ "'" + enquete.Setsumon1 + "',"
					+ "'" + enquete.Setsumon2 + "',"
					+ "'" + enquete.Setsumon3 + "',"
					+ "'" + enquete.Setsumon3Koumoku + "',"
					+ "'" + enquete.Setsumon4 + "',"
					+ "'" + enquete.Setsumon4Koumoku + "',"
					+ "'" + enquete.Setsumon5Title + "',"
					+ "'" + enquete.Setsumon4Url + "')";
			st.executeUpdate(sql);

			// 正常ならコミット
			db.Commit();

			result = true;
		} catch (SQLException e) {
			// エラーならロールバック
			db.Rollback();
		}

		st.close();

		return result;
	}

	// ユーザ登録メール送信処理
	private void SendRegistMail(Order order)
			throws ServletException, IOException, SQLException {

		Order.User user = order.user;
		Contrxxxaster.MailInfo mailinfo = new Contrxxxaster().GetMailInfo();
		SendMail mail = new SendMail();

		// 担当者あてメール送信
		mail.send(mailinfo.MailServer, mailinfo.ToAddress, mailinfo.CcAddress, mailinfo.FromAddress, "[Kairaku-DB]新規ユーザー登録確認", MailBodyTantosha(user));

		// 登録ユーザあてメール送信
		mail.send(mailinfo.MailServer, user.EMail, null, mailinfo.FromAddress, "[OnLineShop<F-net>]会員登録完了のお知らせ", MailBodyUser(user));
	}

	// 登録ユーザあてメール本文作成
	private String MailBodyTantosha(Order.User user)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		SimpleDateFormat datefmt = new SimpleDateFormat("yyyy年MM月dd日");

		String text = "■■■■　快楽市場データベース　■■■■\n"
				+ "\n"
				+ "以下のユーザーから会員登録がありましたので\n"
				+ "登録内容を転送致します。\n"
				+ "\n"
 				+ "ユーザーＩＤ：" + user.UserID + "\n"
				+ "パスワード：" + user.Password + "\n"
				+ "登録日：" + datefmt.format(user.Touroku) + "\n"
				+ "ふりがな：" + user.Furigana + "\n"
				+ "氏名：" + user.Shimei + "\n"
				+ "性別：" + user.Seibetsu + "\n"
				+ "生年月日：" + user.SeiNen + "年" + user.SeiTsuki + "月" + user.Sexxx + "日" + "\n"
				+ "年齢：" + user.Nenrei + "\n"
				+ "18歳以上確認：" + user.Over18Flag + "\n"
				+ "18歳以上確認日：" + myutil.DateToString(user.Over18Date) + "\n"
				+ "職業：" + myutil.GetShokugyouName(user.Shokugyou) + "\n"
				+ "郵便番号：" + user.Zip + "\n"
				+ "住所：" + user.Address + "\n"
				+ "建物名：" + user.Tatemono + "\n"
				+ "電話番号：" + user.TelNo + "\n"
				+ "ＦＡＸ番号：" + user.FaxNo + "\n"
				+ "メールアドレス：" + user.EMail + "\n"
				+ "メーリングリスト登録：" + user.MailingList + "\n"
				+ "ＵＲＬ：" + user.Url + "\n"
				+ "\n"
				+ "mailed by JapanLinkServer\n"
				+ "inquire to mc@japanlink.ne.jp\n";
 		return text;
	}

	// 担当者あてメール本文作成
	private String MailBodyUser(Order.User user) {

		String text = user.Shimei + "　様\n\n"
				+ "■■■ 快楽市場　ユーザー登録のお知らせ ■■■\n"
				+ "\n"
				+ "ユーザー登録が完了いたしましたので、ＩＤとパスワードをお知らせ致します。\n"
				+ "\n"
				+ "　ＩＤ：" + user.UserID + "\n"
				+ "　パスワード：" + user.Password + "\n"
				+ "\n"
				+ "ご登録ありがとうございました。\n"
				+ "\n"
				+ "成人向け商品の通販が、風営法の改正で「無店舗型」という扱いになりましたので\n"
				+ "快楽市場では初めて商品をご購入いただく時に、お客様の年齢・身分を予め確認させ\n"
				+ "ていただいております。\n"
				+ "下記の方法にて年齢の確認を行っております。\n"
				+ "\n"
				+ "●FAX、又は封書にて年齢確認を行います（初回のみ）。\n"
				+ "免許証・パスポートなど、お客様の年齢を確認できるもののコピーを、FAXまたは\n"
				+ "封書にて下記までご送付下さい。\n"
				+ "確認が出来次第、確認ＯＫのメールを送らせていただきます。\n"
				+ "代金送金に関してはこちらからの年齢確認ＯＫのメールが届き次第、料金の送金を\n"
				+ "お願い致します。（料金代引を選択された方は料金送金の必要はありません）\n"
				+ "料金入金確認後、商品の発送となります。\n"
				+ "\n"
				+ "ご注文後１カ月経っても年齢確認ができない場合は今回発行したID・パスワードは\n"
				+ "使用できなくなります。\n"
				+ "\n"
				+ "年齢確認書類郵送先・お問い合わせ先\n"
				+ "〒162-0813　東京都新宿区新小川町3-10　SEビル4F　快楽市場事務局\n"
				+ "　　　　　　TEL03-5229-7512　FAX03-5229-7513\n"
				+ "FAX先\n"
				+ "03-5229-7513\n"
				+ "\n"
				+ "年齢確認のために送付いただいたコピーについては、他の目的に使用されることは\n"
				+ "決してありませんので、ご安心ください。\n"
				+ "\n"
				+ "お手数ですが、宜しくお願い致します。\n"
				+ "\n"
				+ "またのお越しをお待ちしております。\n"
				+ "\n"
				+ "快楽市場総合デパート\n"
				+ "http://www.kairakuichiba.co.jp\n"
				+ "f-net@kairakuichiba.co.jp\n";

		return text;
	}
}
