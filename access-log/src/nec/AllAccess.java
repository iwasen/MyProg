package xxx;

import base.*;
import javax.servlet.*;
import javax.servlet.http.*;
import java.io.*;
import java.util.*;
import java.sql.*;

/**
 * �R���g���[���T�[�u���b�g�@���ׂẴ��N�G�X�g�͂����ŏ���
 */
public class AllAccess extends HttpServlet{
  private I_Access_Page In = null; //�r�[�����ʂ̃C���^�[�t�F�C�X

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
      // �f�[�^�x�[�X�I�[�v���i��x�ōς܂����߁A�����ōs���j
      DataBase db = new DataBase();

      // �I�������i�[�N���X���Z�b�V��������擾
      SelectCondBean selectcondBean = (SelectCondBean)session.getAttribute("SelectCondBeanId");
      if (selectcondBean == null) {
        // ����܂��̓Z�b�V�����؂�̎�
        selectcondBean = new SelectCondBean(db);
        session.setAttribute("SelectCondBeanId", selectcondBean);
      }

      // ����l�^�ڍ׋敪
      selectcondBean.setMode(request.getParameter("mode"));

      // ����������selectcondBean�Ɋi�[
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
          // �R�[�i�[�R�[�h�݂̂����o��
          int n = str.lastIndexOf(':');
          if (n != -1)
            selectcondBean.setStreamSelectCorner(i, str.substring(n + 1));
        }
        selectcondBean.setStreamUrl(i, request.getParameter("streamUrl" + i));
      }
      selectcondBean.setStreamDispLevel(request.getParameter("streamDispLevel"));
      selectcondBean.setStreamRecycle(request.getParameter("streamRecycle"));

      // �\���{�^���`�F�b�N
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

      // �\�������{�^���`�F�b�N
      if (request.getParameter("JuniJun") != null) {
        selectcondBean.setOrder("ALLCOUNT");
        selectcondBean.resetPageNo();
      } else if (request.getParameter("SetteiJun") != null) {
        selectcondBean.setOrder("SORT");
        selectcondBean.resetPageNo();
      }

      // �y�[�W�߂���{�^���`�F�b�N
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

      // �u�ϊ����v�{�^������
      str = request.getParameter("transCorner");
      if (str != null && !str.equals(""))
        selectcondBean.setTransCorner(db, Integer.parseInt(str));

      // �u���ʂ�\���v�{�^������
      str = request.getParameter("lowerCorner");
      if (str != null && !str.equals(""))
        selectcondBean.setLowerCorner(Integer.parseInt(str));

      // �u��ʂ�\���v�{�^������
      str = request.getParameter("upperCorner");
      if (str != null && !str.equals(""))
        selectcondBean.setUpperCorner(Integer.parseInt(str));

      // ���������̏�����
      str = request.getParameter("init");
      if (str != null && str.equals("1"))
        selectcondBean.initCondition(db);

      // ����l�̏����ݒ�
      if (selectcondBean.getMode() == selectcondBean.modeResult)
        selectcondBean.setDefaultCondition(db);

      // �y�[�W���N���X���ƃT�u�R�[�h�ɕ����i"/"�̌����T�u�R�[�h�Ƃ���j
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

      // Bean�𓮓I�ɐ���
      try {
        Class beanClass = Class.forName("xxx." + className + "Bean");
        In = (I_Access_Page)beanClass.newInstance();
      } catch (Exception e) {
        e.printStackTrace();
        return;
      }

      // �e�[�u���̒��g��HTML���쐬
      if ((selectcondBean.getMode() & selectcondBean.modeResult) != 0)
        In.searchDB(db, selectcondBean);

      // Bean��JSP�ɓn�����߂�HttpServletRequest�I�u�W�F�N�g�ɃZ�b�g
      request.setAttribute("SearchDbBeanId", In);
      request.setAttribute("db", db);

      // View�ł���JSP���Ăяo��
      RequestDispatcher rDispatcher = request.getRequestDispatcher(className + ".jsp");
      rDispatcher.forward(request, response);

      // DB�N���[�Y�iDataBase�̃t�@�C�i���C�U�������Ɏ��s�����Ƃ͌���Ȃ��̂ł����ŕ���j
      db.close();
    } catch (Exception e) {
      e.printStackTrace();
      return;
    }
	}//doPost

	/**
	 * doGet
	 * doPost�ւ܂铊��
	 * @param request
	 * @param response
	 * @throws ServletException
	 * @throws IOException
	 */
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws ServletException, IOException {
		doPost(request, response);
	}//doGet

  /**��n��*/
  public void destroy() {
  }
}


