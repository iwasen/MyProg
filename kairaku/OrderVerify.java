//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		受注情報確認処理処理
//		FileName:	OrderVerify.java
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

// 商品オーダ確認ページ処理クラス
public class OrderVerify extends BaseServlet2
{
	// "GET"メソッド処理
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "受注情報入力", "", "bgcolor='#FFFFCC'");
		Body(pw, order);
		Footer(pw);
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		DecimalFormat fmt = new DecimalFormat("#,###,###");
		Order.Juchu juchu = order.juchu;

		pw.println("<p align='center' class='s5'><font color='#000000'>▼" + order.user.Shimei + "様　受注確認▼</font></p>");
		pw.println("<p align='center' class='s1'> <font color='#000000'><br>");
		pw.println("</font></p>");
		pw.println("<div align='center'>");
		pw.println("<table width='560' border='2' cellspacing='1' cellpadding='1' class='s1' bgcolor='#CCFFFF' height='62'>");

		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);

			pw.println("<tr>");
			pw.println("<td width='60' nowrap><font color='#000000'>" + meisai.ShouhinNo + "</font></td>");
			pw.println("<td width='241' nowrap><font color='#000000'>" + meisai.ShouhinName + "</font></td>");
			pw.println("<td width='74' nowrap><font color='#000000'>" + meisai.Kosuu + meisai.Tani + "</font></td>");
			pw.println("<td width='163' nowrap>");
			pw.println("<div align='right'><font color='#000000'>" + fmt.format(meisai.Shoukei) + "円</font></div>");
			pw.println("</td>");
			pw.println("</tr>");
		}

		pw.println("<tr>");
		pw.println("<td colspan='4' nowrap>");
		pw.println("<div align='right'> <font color='#000000'>合計金額（税込）　" + fmt.format(order.juchu.Total) + "円</font></div>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<br>");

		pw.println("<span class='s1'><font color='#FF0000'>");
		pw.println("上記の合計金額の他に送料（700円）をご用意ください。<br>");
		pw.println("宅配代金引換をご希望の方は送料の他に手数料（400円）を頂きます。<br>");
		pw.println("ただし、15,000円以上お買い上げの場合、送料、手数料は無料とさせていただきます。<br>");
		pw.println("また、HAB写真集に関しては、送料・手数料は、無料となります。お客様が、HAB写真集<br>");
		pw.println("と他の商品を同時に、購入した場合も送料・手数料は、頂きません。無料となります。");
		pw.println("</font> </span><br>");

		pw.println("</div>");
		pw.println("<br>");
		pw.println("<table cellpadding=8>");
		pw.println("<tr>");
		pw.println("<td align='right'><font color='#000000'>郵便番号</td>");
		pw.println("<td>" + juchu.Zip + "</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'><font color='#000000'>住所</td>");
		pw.println("<td>" + juchu.Address + "</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'><font color='#000000'>お届け先建物</td>");
		pw.println("<td>" + juchu.Tatemono + "</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'><font color='#000000'>お届け先電話番号</td>");
		pw.println("<td>" + juchu.TelNo + "</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'><font color='#000000'>お支払い方法</td>");
		pw.println("<td>" + myutil.GetShiharaiName(juchu.Shiharai) + "</font></td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<form name='form1' method='post'>");
		pw.println("<font color='#000000'>");
		pw.println("<div align='center'>");
		pw.println("<input type='submit' name='ok' value=' 送信 '>");
		pw.println("<input type='submit' name='cancel' value='やめる'>");
		pw.println("</div>");
		pw.println("</font>");
		pw.println("</form>");
	}

	// "POST"メソッド処理
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		// クリックされたボタンによる振り分け
		if (param.GetParam("ok") != null) {
			// 「送信」ボタンがクリックされたら受注情報をデータベースに保存とメールの送信
			if (StoreDB(order))
				SendOrderMail(order);
			response.sendRedirect("thankyou");
		} else if (param.GetParam("cancel") != null) {
			// 「やめる」ボタンがクリックされたらトップページに戻る
			response.sendRedirect(TopPage);
		}
	}

	// 受注情報保存処理
	private boolean StoreDB(Order order)
			throws ServletException, IOException, SQLException {

		Contrxxxaster cm = new Contrxxxaster();
		DecimalFormat mf = new DecimalFormat("000");
		boolean result = false;

		// データベース接続
		DataBase db = new DataBase();
		db.Transaction();
		Statement st = db.createStatement();

		try {
			// 受注情報保存
			Order.Juchu juchu = order.juchu;
			juchu.JuchuCode = cm.GetNewJuchuCode();
			juchu.JuchuDate = new java.util.Date();
			juchu.UserCode = order.user.UserCode;
			juchu.Over18Flag = order.user.Over18Flag;
			String sql = "insert into F_JUCHU values ("
					+ "'" + juchu.JuchuCode + "',"
					+ "'" + juchu.JuchuDate + "',"
					+ "'" + juchu.UserCode + "',"
					+ juchu.Over18Flag + ","
					+ "'" + juchu.Zip + "',"
					+ "'" + juchu.Address + "',"
					+ "'" + juchu.Tatemono + "',"
					+ "'" + juchu.TelNo + "',"
					+ "'" + juchu.Total + "',"
					+ "'" + juchu.Shiharai + "')";
			st.executeUpdate(sql);

			// 受注明細保存
			int count = order.GetMeisaiNum();
			for (int i = 0; i < count; i++) {
				Order.Meisai meisai = order.GetMeisai(i);
				meisai.MeisaiCode = mf.format(i + 1);
				sql = "insert into F_JUCHUMEISAI values ("
						+ "'" + juchu.JuchuCode + "',"
						+ "'" + meisai.MeisaiCode + "',"
						+ "'" + meisai.ShouhinNo + "',"
						+ "'" + meisai.ShouhinName + "',"
						+ meisai.HanbaiKakaku + ","
						+ meisai.Kosuu + ","
						+ meisai.Shoukei + ")";
				st.executeUpdate(sql);
			}

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

	// メール送信処理
	private void SendOrderMail(Order order)
			throws ServletException, IOException, SQLException {

		Order.User user = order.user;
		Contrxxxaster.MailInfo mailinfo = new Contrxxxaster().GetMailInfo();
		SendMail mail = new SendMail();

		// 担当者あてメール送信
		if (!user.UserID.equals("aizawa"))
		mail.send(mailinfo.MailServer, mailinfo.ToAddress, mailinfo.CcAddress, mailinfo.FromAddress, "[Kairaku-DB]商品受注確認", MailBodyTantosha(order));

		// 購入ユーザあてメール送信
		mail.send(mailinfo.MailServer, user.EMail, null, mailinfo.FromAddress, "[OnLineShop<F-net>]ご注文ありがとうございました", MailBodyUser(order));
	}

	// 担当者あてメール本文作成
	private String MailBodyTantosha(Order order)
			throws ServletException, IOException, SQLException {

		DecimalFormat decfmt = new DecimalFormat("#,###,###");
		SimpleDateFormat datefmt = new SimpleDateFormat("yyyy年MM月dd日");
		MyUtil myutil = new MyUtil();
		Order.Juchu juchu = order.juchu;
		Order.User user = order.user;

		String text = "■■■■　快楽市場データベース　■■■■\n"
				+ "\n" 
				+ "以下の会員から商品のご注文がありましたので\n" 
				+ "注文内容を転送致します。\n" 
				+ "\n"
				+ "［注文情報］\n"
				+ "--------------------------------------------------------\n";

		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);
			text += meisai.ShouhinNo + "　"
					+ meisai.ShouhinName + "　"
					+ meisai.Kosuu + meisai.Tani + "　"
					+ decfmt.format(meisai.Shoukei) + "円\n";
		}

		text += "--------------------------------------------------------\n"
				+ "合計 " + decfmt.format(juchu.Total) + "円\n"
				+ "\n"
				+ "［お届け先情報］\n"
				+ "　受注日：" + datefmt.format(juchu.JuchuDate) + "\n"
				+ "　氏名：" + user.Shimei + "\n"
				+ "　郵便番号：" + juchu.Zip + "\n"
				+ "　住所：" + juchu.Address + "\n"
				+ "　建物名：" + juchu.Tatemono + "\n"
				+ "　電話番号：" + juchu.TelNo + "\n"
				+ "　支払方法：" + myutil.GetShiharaiName(juchu.Shiharai) + "\n"
				+ "\n"
				+ "［ユーザー情報］\n"
 				+ "　ユーザーＩＤ：" + user.UserID + "\n"
				+ "　パスワード：" + user.Password + "\n"
				+ "　登録日：" + datefmt.format(user.Touroku) + "\n"
				+ "　ふりがな：" + user.Furigana + "\n"
				+ "　氏名：" + user.Shimei + "\n"
				+ "　性別：" + user.Seibetsu + "\n"
				+ "　生年月日：" + user.SeiNen + "年" + user.SeiTsuki + "月" + user.Sexxx + "日" + "\n"
				+ "　年齢：" + user.Nenrei + "\n"
				+ "　18歳以上確認：" + user.Over18Flag + "\n"
				+ "　18歳以上確認日：" + myutil.DateToString(user.Over18Date) + "\n"
				+ "　職業：" + myutil.GetShokugyouName(user.Shokugyou) + "\n"
				+ "　郵便番号：" + user.Zip + "\n"
				+ "　住所：" + user.Address + "\n"
				+ "　建物名：" + user.Tatemono + "\n"
				+ "　電話番号：" + user.TelNo + "\n"
				+ "　ＦＡＸ番号：" + user.FaxNo + "\n"
				+ "　メールアドレス：" + user.EMail + "\n"
				+ "　メーリングリスト登録：" + user.MailingList + "\n"
				+ "　ＵＲＬ：" + user.Url + "\n"
				+ "\n"
				+ "mailed by JapanLinkServer\n"
				+ "inquire to mc@japanlink.ne.jp\n";
 
		return text;
	}

	// 登録ユーザあてメール本文作成
	private String MailBodyUser(Order order)
			throws ServletException, IOException, SQLException {

		DecimalFormat decfmt = new DecimalFormat("#,###,###");
		MyUtil myutil = new MyUtil();
		Order.Juchu juchu = order.juchu;
		Order.User user = order.user;

		String text = user.Shimei + "　様\n\n";
		text += "■■■ 快楽市場　ご注文内容 ■■■\n";

		text += "--------------------------------------------------------\n";
		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);
			text += meisai.ShouhinNo + "　"
					+ meisai.ShouhinName + "　"
					+ meisai.Kosuu + meisai.Tani + "　"
					+ decfmt.format(meisai.Shoukei) + "円\n";
		}
		text += "--------------------------------------------------------\n";
		text += "合計 " + decfmt.format(juchu.Total) + "円\n\n";

		text += "受注番号：" + juchu.JuchuCode + "\n"
				+ "お届け先郵便番号：" + juchu.Zip + "\n"
				+ "お届け先住所：" + juchu.Address + "\n"
				+ "お届け先建物：" + juchu.Tatemono + "\n"
				+ "お届け先電話番号：" + juchu.TelNo + "1\n"
				+ "お支払方法：" + myutil.GetShiharaiName(juchu.Shiharai) + "\n"
				+ "\n"
				+ ShiharaiGuide(juchu.Shiharai)
				+ "\n"
				+ "********************************************** \n"
				+ "●初めての方へ（18歳以上年齢確認がまだの方）● \n"
				+ "\n"
				+ "18歳以上の年齢を確認できる物を、快楽市場までFAX、またはご送付してください。\n"
				+ "\n"
				+ "[送付先・連絡先]\n"
				+ "162-0814　東京都新宿区新小川町3-10SEビル4F 快楽市場事務局\n"
				+ "TEL03-5229-7512　FAX03-5229-7513\n"
				+ "\n"
				+ "18歳以上の確認が出来次第、メールにて連絡致します。\n"
				+ "それまで料金のお振り込みはしないで下さい。\n"
				+ "年齢確認ができるまで、商品の発送はできません。\n"
				+ "**********************************************\n"
				+ "\n"
				+ "ご注文ありがとうございました。\n";

		return text;
	}

	private String ShiharaiGuide(String ShiharaiCode) {

		String guide = null;

		if (ShiharaiCode.equals("01")) {		// 郵便為替
			guide = "下記の口座へ（商品合計代金+送料700円）を御入金ください。\n"
					+ "ただし、購入代金の合計が15,000円以上の場合、送料、代引手数料は無料とさせて\n"
					+ "いただきます。\n"
					+ "又、HAB写真集に関しては、送料は、無料となります。\n"
					+ "お客様が、HAB写真集と他の商品を 同時に、購入した場合も送料は、頂きません。\n"
					+ "商品合計代金のみをご入金下さい。\n"
					+ "入金確認後、商品を発送いたします。\n"
					+ "　　　　　　　　　　　新宿新小川町郵便局　　00160-3-189753\n"
					+ "　　　　　　　　　　　口座名　　　快楽市場\n";
		} else if (ShiharaiCode.equals("02")) {	// 銀行振込
			guide = "下記の口座へ（商品合計代金+送料700円）を御入金ください。\n"
					+ "ただし、購入代金の合計が15,000円以上の場合、送料、代引手数料は無料とさせて\n"
					+ "いただきます。\n"
					+ "又、HAB写真集に関しては、送料は、無料となります。\n"
					+ "お客様が、HAB写真集と他の商品を 同時に、購入した場合も送料・手数料は、頂き\n"
					+ "ません。商品合計代金のみをご入金下さい。\n"
					+ "入金確認後、商品を発送いたします。\n"
					+ " 　　　　　　　　　　　住友銀行　飯田橋支店　（普）775751\n"
					+ " 　　　　　　　　　　　口座名　　　フリーダムエフ快楽市場\n";
		} else if (ShiharaiCode.equals("03")) {	// 料金代引
			guide = "商品を発送いたします。\n"
					+ "\n"
					+ "お届け商品と引き換えに代金（商品合計代金+送料700円+手数料400円）をお支払い\n"
					+ "ください。\n"
					+ "ただし、購入代金の合計が15,000円以上の場合、送料、代引手数料は無料とさせて\n"
					+ "いただきます。\n"
					+ "又、HAB写真集に関しては、送料・手数料は、無料となります。\n"
					+ "お客様が、HAB写真集と他の商品を 同時に、購入した場合も送料・手数料は、頂き\n"
					+ "ません。 商品合計代金のみをご入金下さい。\n";
		} else if (ShiharaiCode.equals("04")) {	// 現金書留
			guide = "入金確認後、商品を発送いたします。\n"
					+ "\n"
					+ "お届け商品と引き換えに代金（商品合計代金+送料700円）をお支払いください。\n"
					+ "ただし、購入代金の合計が15,000円以上の場合、送料、代引手数料は無料とさせて\n"
					+ "いただきます。\n"
					+ "又、HAB写真集に関しては、送料は、無料となります。お客様が、\n"
					+ "HAB写真集と他の商品を 同時に、購入した場合も送料・手数料は、頂きません。\n"
					+ "商品合計代金のみをご入金下さい。\n";
		}

		return guide;
	}
}
