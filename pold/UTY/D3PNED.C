/*		FILE DESCRIPTION

		FILE NAME: d3pned.c
		ROUTINE	 : 
		REVISION :
		REMARKS	 : 	87.10.21	N.MATSUO

*/
#include	"d3pned.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static char	*ptabl[12] = {	/* table for title */
	"���@�k�`�f�[�^�Ǘ��@��",
	"��  �O���[�v�����̕ێ�  ��",
	"��  �������E���x�����̕ێ�  ��",
	"��  �\�f�[�^�Z�b�g�̕ێ�  ��",
	"��  ���[�U�����̕ێ�  ��",
	"��  �k�`�f�[�^�̊i�[  ��",
	"��  �O���\���t�@�C���̕ێ�  ��",
	"��  ��������  ��",
	"��  �k�`�f�[�^�Z�b�g�̍폜  ��",
	"��  �V�X�e�������̕ێ�  ��",
	"��  �k�`�f�[�^�Z�b�g�̕ۑ��E����  ��",
	"��  �o�b�N�A�b�v�t�@�C���̕ێ�  ��"
};
/*
static char	*ptabl[11] = {	#* table for title *#
	"��  �k�`�f�[�^�x�[�X�̐���  ��",
	"��  �������E���x�����̕ێ�  ��",
	"��  �\�f�[�^�̕ێ�  ��",
	"��  ���[�U�����̕ێ�  ��",
	"��  �k�`�f�[�^�Z�b�g�̓o�^�E�i�[  ��",
	"��  �k�`�f�[�^�Z�b�g�̕\���`���̕ϊ�  ��",
	"��  ��������  ��",
	"��  �k�`�f�[�^�Z�b�g�̍폜  ��",
	"��  �V�X�e�������̕ێ�  ��",
	"��  �k�`�f�[�^�x�[�X�̕ۑ��E����  ��",
	"��  �k�`�f�[�^�x�[�X�̕ύX  ��"
};
*/

/*		FUNC. DESCRIPTION

		FUNC.NAME: d3pned()
		INPUT	 : pno	message no.
		OUTPUT	 : none
		REMARKS  : 	87.10.21	N.MATSUO

*/
d3pned(pno)
int	pno;
{
	mvwaddstr( pad , 0 , 0 , ptabl[pno] );

	/*pnoutrefresh(pad,0,0,0,0,l_num,80);*/
}

