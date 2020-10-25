//**********************************************************************
//		�A���P�[�g�W�v�V�X�e��
//
//		Title:		�E�ƕʏW�v����
//		FileName:	body04.java
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

public class body04 extends BaseServlet {

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
    pw.println("<title>�E�ƕʏW�v</title>");
    pw.println("<meta http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("</head>");
    pw.println("<BODY bgcolor='#ffffff' marginwidth=10 marginheight=10 topmargin='10' leftmargin='10' text ='#222222'>");
    pw.println("<p><font size='4'>��<b>�E�ƕʁ@�o�^��</b></font> </p>");
    pw.println("<table border='0' width='400' cellspacing='2' cellpadding='4'>");

    // �E�ƕʏW�v
    sql = "select DEN_SHOKUGYOU_CODE, count(*) as CNT"
        + " from " + table + ",M_SHOKUGYOU"
        + " where DEN_ENQUETE_MID='" + id + "' and DEN_SHOKUGYOU_CODE is not null and DEN_SHOKUGYOU_CODE<>''"
        + " and DEN_ENQUETE_DATE between '" + from_date + "' and '" + to_date + "'"
        + " and DEN_SHOKUGYOU_CODE=MSG_SHOKUGYOU_CODE"
        + " group by DEN_SHOKUGYOU_CODE,MSG_ORDER_NUM order by MSG_ORDER_NUM";
		rs = st.executeQuery(sql);

    // �E�ƃ}�X�^�Q��
    sql = "select * from M_SHOKUGYOU order by MSG_ORDER_NUM";
		ResultSet rs2 = st.executeQuery(sql);

    boolean read = rs.next();
    int count;
    int line = 0;

    // �S�E�Ɛ����[�v
    while (rs2.next()) {
      // �E�ƕʂ̌������擾
      count = 0;
      if (read) {
        if (rs2.getString("MSG_SHOKUGYOU_CODE").equals(rs.getString("DEN_SHOKUGYOU_CODE"))) {
          count = rs.getInt("CNT");
          read = rs.next();
        }
      }

      // �����s�Ɗ�s�ŐF��ς���
      if (line++ % 2 == 0)
        pw.println("<tr bgcolor='#dddddd'>");
      else
        pw.println("<tr bgcolor='#eeeeee'>");

      pw.println("<td nowrap width=50%>");
      pw.println(rs2.getString("MSG_SHOKUGYOU_NAME"));
      pw.println("</td>");
      pw.println("<td nowrap width=50%>");
      pw.println(count);
      pw.println("</td>");
      pw.println("</tr>");
    }

    rs.close();
    rs2.close();

    pw.println("</table>");
    pw.println("</body>");
    pw.println("</html>");

    st.close();
  }
}
