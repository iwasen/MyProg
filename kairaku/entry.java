//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�o�X�P�b�g�V�X�e�������ݒ菈��
//		FileName:	entry.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.ServletException;

// �o�X�P�b�g�V�X�e�����������
public class entry extends HttpServlet
{
	// service()�I�[�o�[���C�h
	public void service(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		// �Z�b�V������V�K�ɍ쐬
		HttpSession session = request.getSession(true);

		// �I�[�_�����쐬���A�Z�b�V�����ɕۑ�
		Order order = new Order();
		session.putValue("order", order);

		// ���i���ނ̏����l���I�[�_���ɕۑ�
		Param param = new Param(request);
		String bunrui = param.GetParam("bunrui");
		order.bunrui = bunrui == null ? "" : bunrui;

		// ���������w���ʂփ��_�C���N�g
		response.sendRedirect("Kensaku");
	}
}
