//**********************************************************************
//		�A���P�[�g�W�v�V�X�e��
//
//		Title:		�j���E���ԑѕʏW�v����
//		FileName:	body03.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/06/25
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

public class body03 extends BaseServlet {

	// "GET"���\�b�h����
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// ContentType�ݒ肨���PrintWriter�擾
			PrintWriter pw = GetPrintWriter(response);

      // �p�����[�^�擾�N���X
      Param param = new Param(request);

			Html(pw, param);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// "GET"���\�b�hhtml�o��
	public void Html(PrintWriter pw, Param param) 
			throws ServletException, IOException, SQLException {

    // �A���P�[�g�h�c���擾
    String id = param.GetParam("id");
    if (id == null)
      return;

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

    // �A���P�[�g�}�X�^�����Ԃƃe�[�u�������擾
		String sql = "select * from M_ENQUETE where MEN_ENQUETE_MID='" + id + "'";
		ResultSet rs = st.executeQuery(sql);
    if (rs.next() == false) {
      ErrorMessage(pw, "�w�肵���A���P�[�g�h�c�͓o�^����Ă��܂���B");
      return;
    }
    Date from_date = rs.getDate("MEN_FROM_DATE");
    Date to_date = rs.getDate("MEN_TO_DATE");
    String table = rs.getString("MEN_TABLE_NAME");
    rs.close();

    pw.println("<html>");
    pw.println("<head>");
    pw.println("<title>�j���E���ԑѕʏW�v</title>");
    pw.println("<meta http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("</head>");
    pw.println("<BODY bgcolor='#ffffff' marginwidth=10 marginheight=10 topmargin='10' leftmargin='10' text ='#222222'>");
    pw.println("<p><font size='4'>��<b>�j���ʁ@�o�^��</b></font> </p>");
    pw.println("<table border='0' width='400' cellspacing='2' cellpadding='4'>");

    // �j���ʏW�v
    sql = "select date_part('dow',DEN_ENQUETE_DATE) as WEEK, count(*) as CNT"
        + " from " + table
        + " where DEN_ENQUETE_MID='" + id + "'"
        + " and DEN_ENQUETE_DATE between '" + from_date + "' and '" + to_date + "'"
        + " group by WEEK order by WEEK";
		rs = st.executeQuery(sql);

    boolean read = rs.next();
    int count;
    String[] weekstr = {"��", "��", "��", "��", "��", "��", "�y"};

    // ���j������y�j���܂Ń��[�v
    for (int i = 0; i < 7; i++) {
      // ���̓��̌������擾
      count = 0;
      if (read) {
        if (i == rs.getInt("WEEK")) {
          count = rs.getInt("CNT");
          read = rs.next();
        }
      }

      // �����s�Ɗ�s�ŐF��ς���
      if (i % 2 == 0)
        pw.println("<tr bgcolor='#dddddd'>");
      else
        pw.println("<tr bgcolor='#eeeeee'>");

      pw.println("<td nowrap width=50%>");
      pw.println("<a href='body03?id=" + id + "&week=" + i + "'>" + weekstr[i] + "</a>");
      pw.println("</td>");
      pw.println("<td nowrap width=50%>");
      pw.println(count);
      pw.println("</td>");
      pw.println("</tr>");
    }
    pw.println("</table>");
    rs.close();

    // �j�����擾
    String week = param.GetParam("week");
    if (week != null) {
      pw.println("<p><font size='4'>��<b>���ԑѕʁ@�o�^�󋵁@�i" + weekstr[Integer.parseInt(week)] + "�j���j</b></font> </p>");
      pw.println("<table border='0' width='400' cellspacing='2' cellpadding='4'>");

      // ���ԑѕʏW�v
      sql = "select date_part('hour',DEN_ENQUETE_DATE) as HOUR, count(*) as CNT"
          + " from " + table
          + " where DEN_ENQUETE_MID='" + id + "' and date_part('dow',DEN_ENQUETE_DATE)=" + week
          + " group by HOUR order by HOUR";
		  rs = st.executeQuery(sql);

      read = rs.next();

      for (int i = 0; i < 24; i++) {
        // ���̎��ԑт̌������擾
        count = 0;
        if (read) {
          if (i == rs.getInt("HOUR")) {
            count = rs.getInt("CNT");
            read = rs.next();
          }
        }

        // �����s�Ɗ�s�ŐF��ς���
        if (i % 2 == 0)
          pw.println("<tr bgcolor='#dddddd'>");
        else
          pw.println("<tr bgcolor='#eeeeee'>");

        pw.println("<td nowrap width=50%>");
        pw.println(i + "�F00");
        pw.println("</td>");
        pw.println("<td nowrap width=50%>");
        pw.println(count);
        pw.println("</td>");
        pw.println("</tr>");
      }

      pw.println("</table>");
      rs.close();
    }

    pw.println("</body>");
    pw.println("</html>");

    st.close();
  }
}
