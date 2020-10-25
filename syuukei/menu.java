//**********************************************************************
//		�A���P�[�g�W�v�V�X�e��
//
//		Title:		���j���[�t���[������
//		FileName:	menu.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/06/25
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

public class menu extends BaseServlet {

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

    pw.println("<html>");
    pw.println("<head>");
    pw.println("<title>����h�L�������g</title>");
    pw.println("<meta http-equiv='Content-Type' content='text/html; charset=x-sjis'>");
    pw.println("</head>");
    pw.println("<BODY bgcolor='#ffffff' marginwidth=10 marginheight=10 topmargin='10' leftmargin='10' text ='#222222' scroll='no'>");
    pw.println("<table border='0' width='100%' cellspacing='2' cellpadding='10'>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body01?id=" + id + "' target='body'>�i1�j���E�N���</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body02?id=" + id + "' target='body'>�i2�j����</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body03?id=" + id + "' target='body'>�i3�j�j���E���ԑѕ�</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body04?id=" + id + "' target='body'>�i4�j�E�ƕ�</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body05?id=" + id + "' target='body'>�i5�j�n���</A></b></td>");
    pw.println("</tr>");
    pw.println("<tr>");
    pw.println("<td bgcolor='#dddddd' nowrap><b><A HREF='body06?id=" + id + "' target='body'>�i6�j�֐S����</A></b></td>");
    pw.println("</tr>");
    pw.println("</table>");
    pw.println("</body>");
    pw.println("</html>");
  }
}
