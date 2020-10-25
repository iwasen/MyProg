package xxx;

import base.*;
import javax.servlet.*;
import javax.servlet.http.*;
import java.io.*;
import java.util.*;
import java.sql.*;

/**
 * コントロールサーブレット　すべてのリクエストはここで処理
 */
public class AllAccess extends HttpServlet{
  private I_Access_Page In = null; //ビーン共通のインターフェイス

	/**
	 * doPost
	 * @param request
	 * @param response
	 * @throws ServletException
	 * @throws IOException
	 */
	public void doPost(HttpServletRequest request, HttpServletResponse response)
  throws ServletException, IOException {
		response.setContentType("text/html; charset=Shift_JIS");
		HttpSession session = request.getSession();
 		Param o_param = new Param(request);
    String str;

    try {
      // データベースオープン（一度で済ますため、ここで行う）
      DataBase db = new DataBase();

      // 選択条件格納クラスをセッションから取得
      SelectCondBean selectcondBean = (SelectCondBean)session.getAttribute("SelectCondBeanId");
      if (selectcondBean == null) {
        // 初回またはセッション切れの時
        selectcondBean = new SelectCondBean(db);
        session.setAttribute("SelectCondBeanId", selectcondBean);
      }

      // 既定値／詳細区分
      selectcondBean.setMode(request.getParameter("mode"));

      // 検索条件をselectcondBeanに格納
      selectcondBean.setCorner(request.getParameter("corner"));
      selectcondBean.setCategory(request.getParameter("category"));
      selectcondBean.setTable(request.getParameter("table"));
      selectcondBean.setCornerdepth(request.getParameter("cornerdepth"));
      for (int i = 0; i <= 4; i++)
        selectcondBean.setAttribute(i, o_param.GetParam("attribute" + i));
      selectcondBean.setOrder(request.getParameter("order"));
      selectcondBean.setPage(request.getParameter("page"));
      selectcondBean.setGroup(request, response, request.getParameter("group"));
      selectcondBean.setMenu(request, response, request.getParameter("menu"));
      selectcondBean.setTargetCorner(request.getParameter("targetCorner"));
      selectcondBean.setMoveFromCorner(request.getParameter("moveFromCorner"));
      selectcondBean.setMoveToCorner(request.getParameter("moveToCorner"));
      selectcondBean.setStreamErrMsg("");
      for (int i = 0; i <= 4; i++) {
        str = request.getParameter("streamCorner" + i);
        if (str != null ) {
          // コーナーコードのみを取り出す
          int n = str.lastIndexOf(':');
          if (n != -1)
            selectcondBean.setStreamSelectCorner(i, str.substring(n + 1));
        }
        selectcondBean.setStreamUrl(i, request.getParameter("streamUrl" + i));
      }
      selectcondBean.setStreamDispLevel(request.getParameter("streamDispLevel"));
      selectcondBean.setStreamRecycle(request.getParameter("streamRecycle"));

      // 表示ボタンチェック
      if (request.getParameter("ToMonth") != null) {
        selectcondBean.setTable("MONTH");
        selectcondBean.setMonthFromY(request.getParameter("monthFromY"));
        selectcondBean.setMonthFromM(request.getParameter("monthFromM"));
        selectcondBean.setMonthToY(request.getParameter("monthToY"));
        selectcondBean.setMonthToM(request.getParameter("monthToM"));
        selectcondBean.resetPageNo();
      } else if (request.getParameter("ToWeek") != null) {
        selectcondBean.setTable("WEEK");
        selectcondBean.setWeekFromY(request.getParameter("weekFromY"));
        selectcondBean.setWeekFromW(request.getParameter("weekFromW" + request.getParameter("weekFromY")));
        selectcondBean.setWeekToY(request.getParameter("weekToY"));
        selectcondBean.setWeekToW(request.getParameter("weekToW" + request.getParameter("weekToY")));
      } else if (request.getParameter("ToDay") != null) {
        selectcondBean.setTable("DATE");
        selectcondBean.setDayFromY(request.getParameter("dayFromY"));
        selectcondBean.setDayFromM(request.getParameter("dayFromM"));
        selectcondBean.setDayFromD(request.getParameter("dayFromD"));
        selectcondBean.setDayToY(request.getParameter("dayToY"));
        selectcondBean.setDayToM(request.getParameter("dayToM"));
        selectcondBean.setDayToD(request.getParameter("dayToD"));
        selectcondBean.setStreamTableID("");
      } else if (request.getParameter("ToHour") != null) {
        selectcondBean.setTable("HOUR");
        selectcondBean.setHourFromY(request.getParameter("hourFromY"));
        selectcondBean.setHourFromM(request.getParameter("hourFromM"));
        selectcondBean.setHourFromD(request.getParameter("hourFromD"));
        selectcondBean.setHourFromH(request.getParameter("hourFromH"));
        selectcondBean.setHourToY(request.getParameter("hourToY"));
        selectcondBean.setHourToM(request.getParameter("hourToM"));
        selectcondBean.setHourToD(request.getParameter("hourToD"));
        selectcondBean.setHourToH(request.getParameter("hourToH"));
      } else if (request.getParameter("ToStreamTableID") != null) {
        selectcondBean.setTable("DATE");
        selectcondBean.setStreamTableID(request.getParameter("streamTableID"));
      }

      // 表示順序ボタンチェック
      if (request.getParameter("JuniJun") != null) {
        selectcondBean.setOrder("ALLCOUNT");
        selectcondBean.resetPageNo();
      } else if (request.getParameter("SetteiJun") != null) {
        selectcondBean.setOrder("SORT");
        selectcondBean.resetPageNo();
      }

      // ページめくりボタンチェック
      if (request.getParameter("Foward") != null)
        selectcondBean.pageForward();
      else if (request.getParameter("Back") != null)
        selectcondBean.pageBack();
      else if (request.getParameter("Foward2") != null)
        selectcondBean.pageForward2();
      else if (request.getParameter("Back2") != null)
        selectcondBean.pageBack2();
      else {
        selectcondBean.resetPageNo();
        selectcondBean.resetPageNo2();
      }

      // 「変換→」ボタン処理
      str = request.getParameter("transCorner");
      if (str != null && !str.equals(""))
        selectcondBean.setTransCorner(db, Integer.parseInt(str));

      // 「下位を表示」ボタン処理
      str = request.getParameter("lowerCorner");
      if (str != null && !str.equals(""))
        selectcondBean.setLowerCorner(Integer.parseInt(str));

      // 「上位を表示」ボタン処理
      str = request.getParameter("upperCorner");
      if (str != null && !str.equals(""))
        selectcondBean.setUpperCorner(Integer.parseInt(str));

      // 検索条件の初期化
      str = request.getParameter("init");
      if (str != null && str.equals("1"))
        selectcondBean.initCondition(db);

      // 既定値の条件設定
      if (selectcondBean.getMode() == selectcondBean.modeResult)
        selectcondBean.setDefaultCondition(db);

      // ページをクラス名とサブコードに分解（"/"の後ろをサブコードとする）
      String page = selectcondBean.getPage();
      int index = page.indexOf('/');
      String className;
      if (index != -1) {
        className = page.substring(0, index);
        selectcondBean.setSubMode(page.substring(index + 1));
      } else {
        className = page;
        selectcondBean.setSubMode("");
      }

      // Beanを動的に生成
      try {
        Class beanClass = Class.forName("xxx." + className + "Bean");
        In = (I_Access_Page)beanClass.newInstance();
      } catch (Exception e) {
        e.printStackTrace();
        return;
      }

      // テーブルの中身のHTMLを作成
      if ((selectcondBean.getMode() & selectcondBean.modeResult) != 0)
        In.searchDB(db, selectcondBean);

      // BeanをJSPに渡すためにHttpServletRequestオブジェクトにセット
      request.setAttribute("SearchDbBeanId", In);
      request.setAttribute("db", db);

      // ViewであるJSPを呼び出す
      RequestDispatcher rDispatcher = request.getRequestDispatcher(className + ".jsp");
      rDispatcher.forward(request, response);

      // DBクローズ（DataBaseのファイナライザがすぐに実行されるとは限らないのでここで閉じる）
      db.close();
    } catch (Exception e) {
      e.printStackTrace();
      return;
    }
	}//doPost

	/**
	 * doGet
	 * doPostへまる投げ
	 * @param request
	 * @param response
	 * @throws ServletException
	 * @throws IOException
	 */
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws ServletException, IOException {
		doPost(request, response);
	}//doGet

  /**後始末*/
  public void destroy() {
  }
}


