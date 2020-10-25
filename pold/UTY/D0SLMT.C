/*	FILE DESCRIPTION

		FILE NAME	: d0slmt.c
		ROUTINE		: ���������x�����̕ێ�
		REVISION	:
		REMARKS		: created	88.06.15	s.aizawa
*/

#include	<jcurses.h>
#include	"la_ws/include/d2lerr.h"
#include	"la_ws/include/d3libc.h"
#include	"d3data.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d0slmt()
		ROUTINE		: ���������x�����̕ێ�
		INPUT		: none
		OUTPUT		: none
*/

d0slmt()
{
	static	char	*func[8] = { 	/* description of func-key */
		"������", "���x����", NULL, NULL,
		"��������", "file�ꗗ", "LADB�ύX", NULL
	};
	static	char	title[] = "���@�������E���x�����̕ێ�@��";

	int	loop_sw;		/* ���[�v�X�C�b�` */
	int	err;			/* �G���[�R�[�h */
	chtype	ch;			/* ���̓L�[ */
	FILE	*fp;			/* �t�@�C���|�C���^ */
	char	dataset[14];		/* �k�`�f�[�^�Z�b�g�� */
	char	tmp_file[128];		/* �e���|�����t�@�C���� */
	char	s[128], s2[128];	/* ���[�N�o�b�t�@ */

	loop_sw = 1;			/* ���[�v�X�C�b�`�n�m */
	dataset[0] = '\0';		/* �f�[�^�Z�b�g���N���A */

	tmpnam(tmp_file);		/* �e���|�����t�@�C�����쐬 */

	while (loop_sw) {
		werase(pad);		/* ��ʏ��� */
		d3pned(2);		/* �������\�� */
		d3dred();		/* �J�����g�f�B���N�g���\�� */
		d3fcmk(func);		/* �t�@���N�V�����L�[�\�� */

		mvwaddstr(pad, 5, 10, "�k�`�f�[�^�Z�b�g��  : ");
		pnoutrefresh(pad,0,0,0,0,l_num,80); /* display screen */

		ch = d3chin(5, 32, 12, dataset);
		switch (ch) {
		case KEY_F(1):		/* ������ */
			if (dataset[0] == '\0') {	/* ������ ? */
				break;
			}

			if (d3xdat(dataset) == 0) {
				d3errw(E_DSNAME);
				break;
			}

			sprintf(s, "%s/%s", cur_ladb, dataset);

			d3slmt(cur_ladb, dataset, 0, title);
			break;
		case KEY_F(2):		/* ���x���� */
			if (dataset[0] == '\0') {	/* ������ ? */
				break;
			}

			if (d3xdat(dataset) == 0) {
				d3errw(E_DSNAME);
				break;
			}

			sprintf(s, "%s/%s", cur_ladb, dataset);

			d3slmt(cur_ladb, dataset, 1, title);
			break;
		case KEY_F(5):		/* �������� */
			d0cdrf(tmp_file);
			if ((fp = fopen(tmp_file, "r")) == 0)
				break;
			if (fgets(dataset, sizeof(dataset), fp) != 0)
				dataset[strlen(dataset) - 1] = '\0';
			fclose(fp);
			unlink(tmp_file);	/* �e���|�����t�@�C���폜 */
			break;
		case KEY_F(6):		/* file�ꗗ */
			sprintf(s, "%s/%s", cur_ladb,
				dataset[0] ? dataset : "[!A-Z]*");
			d3fsel(s, s2, 0);
			if (s2[0] != '\0')
				strcpy(dataset, s2);
			break;
		case KEY_F(7):		/* LADB�ύX */
			d0whre();
			break;
		case KEY_F(17):		/* �����I�� */
		case KEY_F(18):		/* ����I�� */
			loop_sw = 0;	/* ���[�v�X�C�b�`�n�e�e */
			break;
		}
	}
}
