package base;

import base.*;
import javax.servlet.*;
import javax.servlet.http.*;
import java.io.*;
import java.util.*;

/**
 * <p>�^�C�g��: </p>
 * <p>����: </p>
 * <p>���쌠: Copyright (c) 2003</p>
 * <p>��Ж�: </p>
 * @author ������
 * @version 1.0
 */

public class WebAppInitializer extends HttpServlet {
  public static String propertyPath;

  public void init() throws ServletException {
    // �v���p�e�B�t�@�C���̃p�X��ݒ�
    propertyPath = getServletContext().getRealPath("/WEB-INF/server.properties");
  }
}