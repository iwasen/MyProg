//**********************************************************************
//		�A���P�[�g�W�v�V�X�e��
//
//		Title:		�֐S���ʏW�v����
//		FileName:	body06.java
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

public class body06 extends BaseServlet {

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

    // �֐S���ʏW�v
    int[] count = new int[36];
    sql = "select DEN_Q1FLAG, count(*) as CNT"
        + " from " + table
        + " where DEN_ENQUETE_MID='" + id + "'"
        + " and DEN_ENQUETE_DATE between '" + from_date + "' and '" + to_date + "'"
        + " group by DEN_Q1FLAG";
		rs = st.executeQuery(sql);
    while (rs.next()) {
      String flag = rs.getString("DEN_Q1FLAG");
      int cnt = rs.getInt("CNT");
      for (int i = 0; i < count.length; i++) {
        if (i < flag.length()) {
          if (flag.charAt(i) == '1')
            count[i] += cnt;
        }
      }
    }

    rs.close();
    st.close();

    pw.println("<html>");
    pw.println("<head>");
    pw.println("<title>�֐S���ʏW�v</title>");
    pw.println("<meta http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("</head>");
    pw.println("<BODY bgcolor='#ffffff' marginwidth=10 marginheight=10 topmargin='10' leftmargin='10' text ='#222222'>");
    pw.println("<p><font size='4'>��<b>�֐S���ʁ@�o�^��</b></font> </p>");
    pw.println("<table border='0' width='400' cellspacing='2' cellpadding='4'>");
    pw.println("<tr bgcolor='#55555'>");
    pw.println("<td nowrap colspan=2>");
    pw.println("<font color='#ffffff'><b>���y</b></font>");
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("J-POP");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[0]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�m�yROCK&POPS");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[1]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�m�yDANCE&SOUL");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[2]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�W���Y");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[3]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�N���V�b�N");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[4]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("���́E�̗w��");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[5]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#55555'>");
    pw.println("<td nowrap colspan=2>");
    pw.println("<font color='#ffffff'><b>�X�|�[�c</b></font>");
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�싅");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[6]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�T�b�J�[");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[7]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�S���t");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[8]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�e�j�X");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[9]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("����");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[10]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�}�����X�|�[�c");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[11]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�E�B���^�[�X�|�[�c�i�X�L�[���j");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[12]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("���o");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[13]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�v�����X�E�i���Z");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[14]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("���[�^�[�X�|�[�c");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[15]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�t�B�b�V���O");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[35]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�X�|�[�c�F���̑�");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[16]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#55555'>");
    pw.println("<td nowrap colspan=2>");
    pw.println("<font color='#ffffff'><b>�</b></font>");
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�f��");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[17]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�A�[�g");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[18]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("���w�E���|�i�Ǐ��j");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[19]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�}���K�E�A�j��");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[20]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�p�\�R��");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[21]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�Q�[��");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[22]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�ԁE�I�[�g�o�C");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[23]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�������s");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[24]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�C�O���s");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[25]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�h���C�u�E�c�[�����O");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[26]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�L�����v�E�A�E�g�h�A");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[27]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("���e�N�E�}�l�[");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[28]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("���N�E�t�B�b�g�l�X");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[29]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�y�b�g");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[30]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�����E�O����");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[31]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�t�@�b�V����");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[32]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#eeeeee'>");
    pw.println("<td nowrap width=50%>");
    pw.println("�V���b�s���O");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[33]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("<tr bgcolor='#dddddd'>");
    pw.println("<td nowrap width=50%>");
    pw.println("���̑�");
    pw.println("</td>");
    pw.println("<td nowrap width=50%>");
    pw.println(count[34]);
    pw.println("</td>");
    pw.println("</tr>");
    pw.println("</table>");
    pw.println("</body>");
    pw.println("</html>");
  }
}
