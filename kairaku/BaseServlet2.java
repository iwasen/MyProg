//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�o�X�P�b�g�V�X�e����{�N���X
//		FileName:	BaseServlet2.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.io.OutputStreamWriter;
import java.util.StringTokenizer;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

// �o�X�P�b�g�V�X�e����ʋ��ʊ�{�N���X
public abstract class BaseServlet2 extends HttpServlet {

	// �o�͕����R�[�h
	protected final String Encoding = "iso-2022-jp";

	// ���y�s��g�b�v�y�[�WURL
	protected final String TopPage = "/index.html";

	// �摜�t�@�C���p�X
	protected final String pictLogicalPath = "/pict/";

	// "GET"���\�b�h����
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// ContentType�ݒ肨���PrintWriter�擾
			response.setContentType("text/html; charset=" + Encoding);
			//PrintWriter pw = response.getWriter();
			PrintWriter pw = new PrintWriter(new OutputStreamWriter(response.getOutputStream(), response.getCharacterEncoding()), true);

			// �Z�b�V�����擾
			HttpSession session = request.getSession(false);
			if (session == null) {
				SessionFailure(response);
				return;
			}

			// �Z�b�V��������I�[�_���擾
			Order order = (Order)session.getValue("order");
			if (order == null) {
				SessionFailure(response);
				return;
			}

			// ���̓p�����[�^�擾�N���X����
			Param param = new Param(request);

			// doGet()�ʏ���
			doGetHandler(response, pw, session, param, order);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// doGet()�ʏ����̃f�t�H���g�����i�������Ȃ��j
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {
	}

	// "POST"���\�b�h����
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// ContentType�ݒ肨���PrintWriter�擾
			response.setContentType("text/html; charset=iso-2022-jp");
			//PrintWriter pw = response.getWriter();
			PrintWriter pw = new PrintWriter(new OutputStreamWriter(response.getOutputStream(), response.getCharacterEncoding()), true);

			// �Z�b�V�����擾
			HttpSession session = request.getSession(false);
			if (session == null) {
				SessionFailure(response);
				return;
			}

			// �Z�b�V��������I�[�_���擾
			Order order = (Order)session.getValue("order");
			if (order == null) {
				SessionFailure(response);
				return;
			}

			// ���̓p�����[�^�擾�N���X����
			Param param = new Param(request);

			// doPost()�ʏ���
			doPostHandler(response, pw, session, param, order);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// doPost()�ʏ����̃f�t�H���g�����i�������Ȃ��j
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {
	}

	// �w�b�_�o�͏����ititle �ƌ��o���������j
	protected void Header(PrintWriter pw, String title, String bodyOpt) {
		Header(pw, title, title, bodyOpt);
	}

	// �w�b�_�o�͏����ititle �ƌ��o�����ʁj
	protected void Header(PrintWriter pw, String title, String pagename, String bodyOpt) {
		pw.println("<html>");
		pw.println("<head>");
		pw.println("<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=" + Encoding + "'>");
		pw.println("<META HTTP-EQUIV='Pragma' CONTENT='no-cache'>");
		pw.println("<link rel='stylesheet' href='/css.txt'>");
		pw.println("<title>"+title+"</title>");
		pw.println("</head>");
		pw.println("<body " + bodyOpt + ">");
		pw.println("<h2>" + pagename + "</h2>");
		pw.println("<p>");
	}

	// �t�b�^�o�͏���
	protected void Footer(PrintWriter pw) {
		pw.println("</body>");
		pw.println("</html>");
	}

	// �G���[�������p���b�Z�[�W�o��
	protected void Failure(PrintWriter pw, String reason) {
		pw.println("<br>");
		pw.println("�ȉ��̌������Q�l�ɂ��ĉ������B:");
		pw.println("<p>");
		pw.println(reason);
		pw.println("<p>");
		pw.println("�Ȃ��A���s�̗��R���s���̏ꍇ�� ");
		pw.println("<a href='mailto:servlet-admin@java-conf.gr.jp'>");
		pw.println("�T�[�o�Ǘ��l</a> �܂ł��m�点�������B");
	}

	// JavaScript�o�͏���
	protected void JavaScript(PrintWriter pw, String[] script) {
		int i;

		pw.println("<script language='JavaScript'><!--");

		for (i = 0; i < script.length; i++) {
			pw.println(script[i]);
		}

		pw.println("// --></script>");
	}

	// �Z�b�V�����ؒf���̏����i���݂͓�����ɖ߂������j
	protected void SessionFailure(HttpServletResponse response)
			throws IOException {

/*
		Init(response);
		Header("Timeout", "", "bgcolor='#CCFFFF'");
		pw.println("<h2>��񂪎���ꂽ���ߏ������p���ł��܂���B<br>");
		pw.println("�\���󂠂�܂��񂪁A������x�ŏ�������͂��Ă��������B</h2>");
		pw.println("<a href='Kensaku'>���i�����y�[�W�ɖ߂�</a>");
		Footer();
*/
		response.sendRedirect("entry");
	}

	// �u�����N�̍��ڂ�<br>���o�͂���i�g��\�������邽�߁j
	protected String ConvertBlank(String text) {

		text = text.trim();
		if (text == null || text.equals(""))
			text = "<br>";

		return text;
	}

	// �f�[�^�ɉ��s���������� <br> �ɕϊ�����
	protected String ConvertCRLF(String in) {
		StringTokenizer st = new StringTokenizer(in, "\n");
		String out = "";

		int count = st.countTokens();
		while (--count >= 0) {
			out += st.nextToken() + "<br>";
		}

		return out;
	}
}
