//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		商品マスタ更新処理
//		FileName:	ShouhinItem.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
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

// 商品更新削除処理クラス
public class ShouhinItem extends BaseServlet1
{
	// 画像ファイル論理パス
	private final String pictLogicalPath = "/pict/";
	// 画像ファイル物理パス
	private String pictPhysicalPath = "/usr/users/kairaku/webs/pict/";

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
			Header(pw, "商品マスター登録");
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

		ShouhinMaster sm = new ShouhinMaster();
		if (code != null) {
			// コードの指定があれば更新または削除なので現在のデータを取得
			if (sm.Read(code)) {
				ShouhinInfo(pw, sm, true);
			} else {
				pw.println("<h2>指定した商品コードは登録されていません。</h2>");
				pw.println("<a href='master'>戻る</a>");
			}
		} else {
			// コードの指定がなければ新規追加
			sm.Init();
			sm.ShouhinCode = new Contrxxxaster().GetNewShouhinCode();
			ShouhinInfo(pw, sm, false);
		}
	}

	// 商品情報表示
	private void ShouhinInfo(PrintWriter pw, ShouhinMaster sm, boolean update)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		SimpleDateFormat formatter = new SimpleDateFormat ("yyyy/MM/dd");
 
		pw.println("<form enctype='multipart/form-data' method='POST' name='fm' onSubmit='return OnSubmit();'>");
		pw.println("<table border=1 size='90%' align='center' bgcolor='#E0FFE0'>");
		pw.println("<tr>");
		pw.println("<td bgcolor='#FFE0E0'>商品コード</td>");
		pw.println("<td><input type='hidden' name='ShouhinCode' size=15 value='" + sm.ShouhinCode + "'>");
		pw.println("<input type='text' size=15 value='" + sm.ShouhinCode + "' disabled></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>商品名</td>");
		pw.println("<td><input type='text' name='ShouhinName' size=50 value='" + sm.ShouhinName + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>商品番号</td>");
		pw.println("<td><input type='text' name='ShouhinNo' size=50 value='" + sm.ShouhinNo + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>商品分類コード</td>");
		pw.println("<td>"); myutil.BunruiSelect(pw, sm.BunruiCode, "選択してください", null); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>カテゴリー</td>");
		pw.println("<td>"); myutil.CategorySelect(pw, sm.CategoryCode, "選択してください", null); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>登録日</td>");
		pw.println("<td><input type='text' name='TourokuDate' size=15 value='" + formatter.format(sm.TourokuDate) + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>シリーズ名</td>");
		pw.println("<td><input type='text' name='SeriesName' size=50 value='" + sm.SeriesName + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>女優名</td>");
		pw.println("<td><input type='text' name='ActorName' size=50 value='" + sm.ActorName + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>商品解説</td>");
		pw.println("<td><textarea name='KaisetsuText' rows=4 cols=40 wrap='soft'>" + sm.KaisetsuText + "</textarea></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>定価</td>");
		pw.println("<td><input type='text' name='TeikaYen' size=15 value='" + (update ? String.valueOf(sm.TeikaYen) : "") + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>販売価格</td>");
		pw.println("<td><input type='text' name='HanbaiYen' size=15 value='" + (update ? String.valueOf(sm.HanbaiYen) : "") + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>仕入価格</td>");
		pw.println("<td><input type='text' name='ShiireYen' size=15 value='" + (update ? String.valueOf(sm.ShiireYen) : "") + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>単位</td>");
		pw.println("<td><input type='text' name='TaniText' size=15 value='" + sm.TaniText + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>商品画像１（小画像）</td>");
		pw.println("<td><input type='file' name='Gazou1File' size=50 accept='image'>");
		pw.println("<input type='hidden' name='Gazou1FileName' size=50 value='" + sm.Gazou1File + "'>");
		pw.println("<input type='button' value='表示' onClick='RefreshPict1()'><br>");
		pw.println("<img src='" + pictLogicalPath + sm.Gazou1File + "' name='pict1'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>商品画像２（大画像）</td>");
		pw.println("<td><input type='file' name='Gazou2File' size=50 accept='image'>");
		pw.println("<input type='hidden' name='Gazou2FileName' size=50 value='" + sm.Gazou2File + "'>");
		pw.println("<input type='button' value='表示' onClick='RefreshPict2()'><br>");
		pw.println("<img src='" + pictLogicalPath + sm.Gazou2File + "' name='pict2'></td>");
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
			"function RefreshPict1() {",
			" if (document.fm.Gazou1File.value != '')",
			"  document.pict1.src = document.fm.Gazou1File.value;",
			"}",
			"function RefreshPict2() {",
			" if (document.fm.Gazou2File.value != '')",
			"  document.pict2.src = document.fm.Gazou2File.value;",
			"}",
			"function OnSubmit() {",
			" with (document.fm) {",
			" if (action.value == '登録' || action.value == '変更') {",
			" if (ShouhinName.value == '') {",
			"  alert('商品名を入力してください。');",
			"  ShouhinName.focus();",
			"  return false;",
			" }",
			" if (ShouhinNo.value == '') {",
			"  alert('商品番号を入力してください。');",
			"  ShouhinNo.focus();",
			"  return false;",
			" }",
			" if (BunruiCode.value == '') {",
			"  alert('商品分類コードを選択してください。');",
			"  BunruiCode.focus();",
			"  return false;",
			" }",
			" if (CategoryCode.value == '') {",
			"  alert('カテゴリーを選択してください。');",
			"  CategoryCode.focus();",
			"  return false;",
			" }",
			" if (TourokuDate.value == '') {",
			"  alert('登録日を入力してください。');",
			"  TourokuDate.focus();",
			"  return false;",
			" }",
			" if (TeikaYen.value == '') {",
			"  alert('定価を入力してください。');",
			"  TeikaYen.focus();",
			"  return false;",
			" }",
			" if (HanbaiYen.value == '') {",
			"  alert('販売価格を入力してください。');",
			"  HanbaiYen.focus();",
			"  return false;",
			" }",
			" if (ShiireYen.value == '') {",
			"  alert('仕入価格を入力してください。');",
			"  ShiireYen.focus();",
			"  return false;",
			" }",
			" if (TaniText.value == '') {",
			"  alert('単位を入力してください。');",
			"  TaniText.focus();",
			"  return false;",
			" }",
			" if (action.value == '登録') {",
			" if (Gazou1File.value == '') {",
			"  alert('商品画像１（小画像）を入力してください。');",
			"  Gazou1File.focus();",
			"  return false;",
			" }",
			" if (Gazou2File.value == '') {",
			"  alert('商品画像２（大画像）を入力してください。');",
			"  Gazou2File.focus();",
			"  return false;",
			" }}}",
			" return confirm('この商品を' + action.value + 'します。よろしいですか？')",
			"}}",
		};

		JavaScript(pw, script);
	}

	// "POST"メソッド処理
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// マルチパート処理
			MultiPart multiPart = new MultiPart(request);

			// ボタンによる処理振り分け
			if (multiPart.GetParameter("regist") != null) {
				// "登録"ならデータを保存して再度同じ画面を表示
				StoreData(multiPart);
				response.sendRedirect("ShouhinItem");
			} else if (multiPart.GetParameter("change") != null) {
				// "変更"ならデータを保存して一覧画面に戻る
				StoreData(multiPart);
				response.sendRedirect("ShouhinList");
			} else if (multiPart.GetParameter("delete") != null) {
				// "削除"ならデータを削除して一覧画面に戻る
				DeleteData(multiPart);
				response.sendRedirect("ShouhinList");
			} else if (multiPart.GetParameter("cancel1") != null) {
				// 変更の"中止"なら一覧画面に戻る
				response.sendRedirect("ShouhinList");
			} else if (multiPart.GetParameter("cancel2") != null) {
				// 新規追加の"中止"なら現在の商品コードをキャンセルして初期画面に戻る
				new Contrxxxaster().CancelShouhinCode(multiPart.GetParameter("ShouhinCode"));
				response.sendRedirect("master");
			}
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// データ保存処理
	private void StoreData(MultiPart multiPart)
			throws ServletException, IOException, SQLException, ParseException {

		// 入力されたデータを商品マスタクラスにセット
		ShouhinMaster sm = new ShouhinMaster();
		sm.ShouhinCode = multiPart.GetParameter("ShouhinCode");
		sm.ShouhinName = multiPart.GetParameter("ShouhinName");
		sm.ShouhinNo = multiPart.GetParameter("ShouhinNo");
		sm.BunruiCode = multiPart.GetParameter("BunruiCode");
		sm.CategoryCode = multiPart.GetParameter("CategoryCode");
		sm.TourokuDate = new SimpleDateFormat("yyyy/MM/dd").parse(multiPart.GetParameter("TourokuDate"));
		sm.SeriesName = multiPart.GetParameter("SeriesName");
		sm.ActorName = multiPart.GetParameter("ActorName");
		sm.KaisetsuText = multiPart.GetParameter("KaisetsuText");
		sm.TeikaYen = Integer.parseInt(multiPart.GetParameter("TeikaYen"));
		sm.HanbaiYen = Integer.parseInt(multiPart.GetParameter("HanbaiYen"));
		sm.ShiireYen = Integer.parseInt(multiPart.GetParameter("ShiireYen"));
		sm.TaniText = multiPart.GetParameter("TaniText");
		sm.Gazou1File = multiPart.GetParameter("Gazou1File");
		if (sm.Gazou1File == null || sm.Gazou1File.equals(""))
			sm.Gazou1File = multiPart.GetParameter("Gazou1FileName");
		sm.Gazou2File = multiPart.GetParameter("Gazou2File");
		if (sm.Gazou2File == null || sm.Gazou2File.equals(""))
			sm.Gazou2File = multiPart.GetParameter("Gazou2FileName");

		// データベースに保存
		sm.Store(true);

		// 画像ファイルを作成
		multiPart.CreateFile(pictPhysicalPath);
	}

	// データ削除処理
	private void DeleteData(MultiPart multiPart)
			throws ServletException, IOException, SQLException {

		// 表示した商品コードのデータをデータベースから削除
		ShouhinMaster sm = new ShouhinMaster();
		sm.ShouhinCode = multiPart.GetParameter("ShouhinCode");
		sm.Delete();
	}
}
