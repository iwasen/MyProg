/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �����e�i���X���[�h����		*
 *		�t�@�C����	: maintenance.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*=======================================================================
 |
 |		�O���Ɠx�f�[�^�\���p�c�^�o�Z�b�g
 |
 |	void	dp_gaikou()
 |
 =======================================================================*/
void	dp_gaikou()
{
	ushort	no, data, renge, n;
	short	i, j;

	/* �P�T�T�R�a��M�f�[�^�`�F�b�N */
	if (r1553B_data != 2) {
		/* �x�[���r�v�̓����e�i���X���[�h�����P�T�T�R�a����܂�
		   �O���Ɠx�f�[�^����M���Ă��Ȃ��̂ŁA�S�Ă̂c�^�o��
		   �X�y�[�X���Z�b�g */
		for (i = 0; i < 15; i++) {
			for (j = 0; j < 18; j++)
				dp_data.data[i][j] = FONT_T4;
		}
		return;
	}

	/* �\���m�����o�� */
	no = lumdata.display_no;

	for (i = 0; i < 15; i++) {
		/* �\���m���Z�b�g */
		for (j = 0, n = no; j < 4; j++) {
			dp_data.data[i][3-j] = (j != 0 && n == 0) ?
					FONT_T4 : numfont4_tbl[n % 10];
			n /= 10;
		}

		/* �u�����N�Z�b�g */
		dp_data.data[i][4] = FONT_T4;
		dp_data.data[i][5] = FONT_T4;

		/* �k�d�e�s�f�[�^�Z�b�g */
		data = lumdata.data[i][0] & 0x3fff;
		renge = lumdata.data[i][0] & 0xc000;
		if (renge == 0x4000)		/* �����W�P�H */
			n = (ushort)(data * 100L / 4092L);
		else if (renge == 0x8000)	/* �����W�Q�H */
			n = (ushort)(data * 1000L / 4092L);
		else if (renge == 0xc000)	/* �����W�R�H */
			n = (ushort)(data * 10000L / 4092L);
		else
			n = 0;
		for (j = 0; j < 5; j++) {
			dp_data.data[i][10-j] = (j != 0 && n == 0) ?
					FONT_T4 : numfont4_tbl[n % 10];
			n /= 10;
		}

		/* �u�����N�Z�b�g */
		dp_data.data[i][11] = FONT_T4;
		dp_data.data[i][12] = FONT_T4;

		/* �q�h�f�g�s�f�[�^�Z�b�g */
		data = lumdata.data[i][1] & 0x3fff;
		renge = lumdata.data[i][1] & 0xc000;
		if (renge == 0x4000)		/* �����W�P�H */
			n = (ushort)(data * 100L / 4092L);
		else if (renge == 0x8000)	/* �����W�Q�H */
			n = (ushort)(data * 1000L / 4092L);
		else if (renge == 0xc000)	/* �����W�R�H */
			n = (ushort)(data * 10000L / 4092L);
		else
			n = 0;
		for (j = 0; j < 5; j++) {
			dp_data.data[i][17-j] = (j != 0 && n == 0) ?
					FONT_T4 : numfont4_tbl[n % 10];
			n /= 10;
		}

		no++;		/* �\���m���X�V */
	}
}