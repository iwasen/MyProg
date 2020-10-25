//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		ユーザーマスタ更新処理
//		FileName:	UserUpdate.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/10
//**********************************************************************

import java.util.*;
import java.text.*;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// ユーザー更新削除処理クラス
public class UserUpdate extends BaseServlet1
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
			Header(pw, "ユーザーマスター更新");
			Body(pw, request);
			Script(pw);
			Footer(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, HttpServletRequest request)
			throws ServletException, IOException, SQLException {

		Param param = new Param(request);
		String code = param.GetParam("code");

		UserMaster um = new UserMaster();
		if (code != null) {
			// コードの指定があれば更新または削除なので現在のデータを取得
			if (um.Read(code)) {
				UserInfo(pw, um, true);
			} else {
				pw.println("<h2>指定したユーザコードは登録されていません。</h2>");
				pw.println("<a href='master'>戻る</a>");
			}
		} else {
			// コードの指定がなければ新規追加
			um.Init();
			um.UserCode = new Contrxxxaster().GetNewUserCode();
			UserInfo(pw, um, false);
		}
	}

	// ユーザー情報表示
	private void UserInfo(PrintWriter pw, UserMaster um, boolean update)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		pw.println("<form method='POST' name='fm' onSubmit='return OnSubmit();'>");
		pw.println("<td><input type='hidden' name='UserCode' size=15 value='" + um.UserCode + "'>");
		pw.println("<table border=1 size='90%' align='center' bgcolor='#E0FFE0'>");
		pw.println("<tr>");
		pw.println("<td bgcolor='#FFE0E0'>ユーザーＩＤ</td>");
		pw.println("<td><input type='text' name='UserID' size=15 value='" + um.UserID + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>パスワード</td>");
		pw.println("<td><input type='text' name='Password' size=15 value='" + um.Password + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>登録日</td>");
		pw.println("<td><input type='text' name='TourokuDate' size=15 value='" + myutil.DateToString(um.TourokuDate) + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>ふりがな</td>");
		pw.println("<td><input type='text' name='Furigana' size=50 value='" + um.Furigana + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>氏名</td>");
		pw.println("<td><input type='text' name='Shimei' size=50 value='" + um.Shimei + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>性別</td>");
		pw.println("<td>"); myutil.SeibetsuSelect(pw, um.Seibetsu, "選択してください", null); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>生年月日</td>");
		pw.println("<td><input type='text' name='SeinenYear' size=5 value='" + um.SeinenYear + "'>年");
		myutil.NumberSelect(pw, "SeinenMonth", 1, 12, um.SeinenMonth); pw.println("月");
		myutil.NumberSelect(pw, "SeinenDay", 1, 31, um.SeinenDay); pw.println("日");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>年齢</td>");
		pw.println("<td><input type='text' name='Nenrei' size=5 value='" + um.Nenrei + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>18歳以上確認</td>");
		pw.println("<td>");
		pw.println("<input type='radio' name='Over18Flag' value='false'" + (!um.Over18Flag ? " checked" : "") + " onClick='OnClick_Mikakunin()'>未確認");
		pw.println("<input type='radio' name='Over18Flag' value='true'" + (um.Over18Flag ? " checked" : "") + " onClick='OnClick_KakuninZumi()'>確認済み");
		pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>18歳以上確認日</td>");
		pw.println("<td><input type='text' name='Over18Date' size=15 value='" + myutil.DateToString(um.Over18Date) + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>職業</td>");
		pw.println("<td>"); myutil.ShokugyouSelect(pw, um.Shokugyou, "選択してください", null); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>郵便番号</td>");
		pw.println("<td><input type='text' name='Zip' size=15 value='" + um.Zip + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>住所</td>");
		pw.println("<td><input type='text' name='Address' size=50 value='" + um.Address + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>建物名</td>");
		pw.println("<td><input type='text' name='Tatemono' size=50 value='" + um.Tatemono + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>連絡先電話番号</td>");
		pw.println("<td><input type='text' name='TelNo' size=20 value='" + um.TelNo + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>FAX番号</td>");
		pw.println("<td><input type='text' name='FaxNo' size=20 value='" + um.FaxNo + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>メールアドレス</td>");
		pw.println("<td><input type='text' name='MailAddr' size=50 value='" + um.MailAddr + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>メーリングリスト登録</td>");
		pw.println("<td>"); myutil.BooleanRadio(pw, "MailList", um.MailList, "未登録", "登録済み"); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>ＵＲＬ</td>");
		pw.println("<td><input type='text' name='Url' size=50 value='" + um.Url + "'></td>");
		pw.println("</tr>");
		pw.println("</table><p>");

		pw.println("<center>");
		pw.println("<input type='hidden' name='action'>");
		if (update) {
			pw.println("<input type='submit' name='change' value='　変更　' onClick='document.fm.action.value=\"変更\"'>");
			pw.println("<input type='submit' name='delete' value='　削除　' onClick='document.fm.action.value=\"削除\"'>");
			pw.println("<input type='submit' name='cancel1' value='　中止　' onClick='document.fm.action.value=\"中止\"'>");
		} else {
			pw.println("<input type='submit' name='regist' value='　登録　' onClick='document.fm.action.value=\"登録\"'>");
			pw.println("<input type='submit' name='cancel2' value='　中止　' onClick='document.fm.action.value=\"中止\"'>");
		}
		pw.println("</center>");
		pw.println("</form>");
	}

	// JavaScript出力
	private void Script(PrintWriter pw)
			throws ServletException, IOException {

		final String[] script = {
			"function OnClick_Mikakunin() {",
			" document.fm.Over18Date.value = '';",
			"}",
			"function OnClick_KakuninZumi() {",
			" xx = new Date();",
			" yy = xx.getYear();",
			" mm = xx.getMonth() + 1;",
			" dd = xx.getDate();",
			" if (yy < 2000) yy += 1900;",
			" if (mm < 10) mm = '0' + mm;",
			" if (dd < 10) dd = '0' + dd;",
			" document.fm.Over18Date.value = yy + '/' + mm + '/' + dd;",
  			"}",
			"function OnSubmit() {",
			" with (document.fm) {",
			" if (action.value == '登録' || action.value == '変更') {",
			" if (UserID.value == '') {",
			"  alert('ユーザーＩＤを入力してください。');",
			"  UserID.focus();",
			"  return false;",
			" }",
			" if (Password.value == '') {",
			"  alert('パスワードを入力してください。');",
			"  Password.focus();",
			"  return false;",
			" }",
			" if (TourokuDate.value == '') {",
			"  alert('登録日を入力してください。');",
			"  TourokuDate.focus();",
			"  return false;",
			" }",
			" if (Furigana.value == '') {",
			"  alert('ふりがなを選択してください。');",
			"  Furigana.focus();",
			"  return false;",
			" }",
			" if (Shimei.value == '') {",
			"  alert('氏名を入力してください。');",
			"  Shimei.focus();",
			"  return false;",
			" }",
			" if (Seibetsu.value == '') {",
			"  alert('性別を選択してください。');",
			"  Seibetsu.focus();",
			"  return false;",
			" }",
			" if (SeinenYear.value == '') {",
			"  alert('生年月日を入力してください。');",
			"  SeinenYear.focus();",
			"  return false;",
			" } else {",
			"  if (isNaN(Number(SeinenYear.value))) {",
			"   alert('年が正しくありません。');",
			"   SeinenYear.focus();",
			"   SeinenYear.select();",
			"   return false;",
			"  }",
			" }",
			" if (ShokugyouCode.value == '') {",
			"  alert('職業を選択してください。');",
			"  ShokugyouCode.focus();",
			"  return false;",
			" }",
			" if (Zip.value == '') {",
			"  alert('郵便番号を入力してください。');",
			"  Zip.focus();",
			"  return false;",
			" }",
			" if (Address.value == '') {",
			"  alert('住所を入力してください。');",
			"  Address.focus();",
			"  return false;",
			" }",
			" }",
			" return confirm('このユーザーを' + action.value + 'します。よろしいですか？')",
			"}}",
		};

		JavaScript(pw, script);
	}

	// "POST"メソッド処理
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		Param param = new Param(request);

		try {
			// ボタンによる処理振り分け
			if (param.GetParam("regist") != null) {
				// "登録"ならデータを保存して再度同じ画面を表示
				StoreData(param);
				response.sendRedirect("UserUpdate");
			} else if (param.GetParam("change") != null) {
				// "変更"ならデータを保存して一覧画面に戻る
				StoreData(param);
				response.sendRedirect("UserList");
			} else if (param.GetParam("delete") != null) {
				// "削除"ならデータを削除して一覧画面に戻る
				DeleteData(param);
				response.sendRedirect("UserList");
			} else if (param.GetParam("cancel1") != null) {
				// 変更の"中止"なら一覧画面に戻る
				response.sendRedirect("UserList");
			} else if (param.GetParam("cancel2") != null) {
				// 新規追加の"中止"なら現在のユーザーコードをキャンセルして初期画面に戻る
				new Contrxxxaster().CancelUserCode(param.GetParam("UserCode"));
				response.sendRedirect("master");
			}
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// データ保存処理
	private void StoreData(Param param)
			throws ServletException, IOException, SQLException, ParseException {

		MyUtil myutil = new MyUtil();

		// 入力されたデータをユーザーマスタクラスにセット
		UserMaster um = new UserMaster();
		um.UserCode = param.GetParam("UserCode");
		um.UserID = param.GetParam("UserID");
		um.Password = param.GetParam("Password");
		um.TourokuDate = myutil.StringToDate(param.GetParam("TourokuDate"));
		um.Furigana = param.GetParam("Furigana");
		um.Shimei = param.GetParam("Shimei");
		um.Seibetsu = param.GetParam("Seibetsu");
		um.SeinenYear = Integer.parseInt(param.GetParam("SeinenYear"));
		um.SeinenMonth = Integer.parseInt(param.GetParam("SeinenMonth"));
		um.SeinenDay = Integer.parseInt(param.GetParam("SeinenDay"));
		um.Nenrei = Integer.parseInt(param.GetParam("Nenrei"));
		um.Over18Flag = param.GetParam("Over18Flag").equals("true");
		um.Over18Date = myutil.StringToDate(param.GetParam("Over18Date"));
		um.Shokugyou = param.GetParam("ShokugyouCode");
		um.Zip = param.GetParam("Zip");
		um.Address = param.GetParam("Address");
		um.Tatemono = param.GetParam("Tatemono");
		um.TelNo = param.GetParam("TelNo");
		um.FaxNo = param.GetParam("FaxNo");
		um.MailAddr = param.GetParam("MailAddr");
		um.MailList = param.GetParam("MailList").equals("true");
		um.Url = param.GetParam("Url");

		// データベースに保存
		um.Store(true);
	}

	// データ削除処理
	private void DeleteData(Param param)
			throws ServletException, IOException, SQLException {

		// 表示したユーザーコードのデータをデータベースから削除
		UserMaster um = new UserMaster();
		um.UserCode = param.GetParam("UserCode");
		um.Delete();
	}
}
