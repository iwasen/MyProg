//**********************************************************************
//		���ʃ��[�e�B���e�B
//
//		Title:		������֘A���[�e�B�e�B
//		FileName:	StrUty.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/20
//**********************************************************************

// ������Ɋ܂܂��S�p�̉p�����𔼊p�ɕϊ�����
public class StrUty
{
	static public String HankakuCnv(String str) {

		final String hankaku =
			"0123456789.--" +
			"ABCDEFGHIJKLMNOPQRSTUVWXYZ" +
			"abcdefghijklmnopqrstuvwxyz";
		final String zenkaku =
			"�O�P�Q�R�S�T�U�V�W�X�D" + (char)0x2212 + (char)0xff0d +
			"�`�a�b�c�d�e�f�g�h�i�j�k�l�m�n�o�p�q�r�s�t�u�v�w�x�y" +
			"����������������������������������������������������";

		if (str != null) {
			int len = hankaku.length();
			for (int i = 0; i < len; i++) {
				str = str.replace(zenkaku.charAt(i), hankaku.charAt(i));
			}
		}

		return str;
	}
}
