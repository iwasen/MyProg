/*	FILE DESCRIPTION

		FILE NAME	: pamenu.c
		ROUTINE		: main menu editing
		REVITION	:
		REMARKS		:	87.10.20	N.MATSUO
		REMARKS		:	88.04.21	M.MOTOKI
*/

#include	"pdmenu.h"

#define		MENU_NUM	11		/* menu item number */

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static 	char	pa[] = "������  �k�`�f�[�^�Ǘ����j���[  ������";

static	char	*pb[MENU_NUM] = {	/* item for main menu */
	"   �P  �k�`�f�[�^�̊i�[             ",
	"   �Q  �������E���x�����̕ێ�       ",
	"   �R  �\�f�[�^�Z�b�g�̕ێ�         ",
	"   �S  ���[�U�����̕ێ�             ",
	"   �T  �O���\���t�@�C���̕ێ�       ",
	"   �U  ��������                     ",
	"   �V  �k�`�f�[�^�Z�b�g�̍폜       ",
	"   �W  �o�b�N�A�b�v�t�@�C���̕ێ�   ",
	"   �X  �k�`�f�[�^�Z�b�g�̕ۑ��E���� ",
	" �P�O  �V�X�e�������̕ێ�           ",
	" �P�P  �O���[�v�����̕ێ�           "
};


static 	char	*pc[8] = {	/* function key for main menu */
	"shell", NULL, NULL, NULL,
	NULL, NULL, "LADB�ύX", NULL
};

static	char	pd[] = "������I�����Ă�������";


/*	FUNC. DESCRIPTION

		FUNC.NAME	: main()
		INPUT		: none
		OUTPUT		: none
		REMARKS		:	87.10.20		N.MATSUO
*/

main(argc, argv)
int	argc;
char	*argv[];
{
	int	stat, i_no, com_no, loop_sw;
	char	pno[55];
	chtype	fno;

	com_no = menu10(argc, argv);		/* get command No. */

	d3init();				/*	initialize	*/

	loop_sw = 1;
	while (loop_sw) {
		wclear(pad);

		i_no = MENU_NUM;

		if (com_no == 0) {
			d3pned(0);		/* display title */
			d3mnmk(0, pa, pb, i_no, pc, pd, pno, &fno);
		} else {
			fno = '0';
			pno[0] = com_no;
			loop_sw = 0;
		}

		switch (fno){
		case '0':		/* push < CR > */
			switch (pno[0]) {
			case	1:	/* �k�`�f�[�^�Z�b�g �o�^�E�i�[ */
				stat = d0dsmk();
				break;
			case	2:	/* �������E���x���� �ێ� */
				stat = d0slmt();
				break;
			case	3:	/* �\�f�[�^ �ێ� */
				stat = d0kdmt();
				break;
			case	4:	/* ���[�U���� �ێ� */
				stat = d0usmt();
				break;
			case	5:	/* �k�`�f�[�^�Z�b�g �\���`���̕ϊ� */
				stat = d0dtcm();
				break;
			case	6:	/* �������� */
				if (argc < 2 && com_no == 0)
					stat = d0cdrf(NULL);
				else
					stat = d0cdrf(argv[1]);
				break;
			case	7:	/* �k�`�f�[�^�Z�b�g �폜 */
				stat = d0dsdl();
				break;
			case	8:	/* �o�b�N�A�b�v�t�@�C���̕ێ� */
				stat = d0back();
				break;
			case	9:	/* �k�`�f�[�^�x�[�X �ۑ��E���� */
				stat = d0dbcp();
				break;
			case	10:	/* �V�X�e�������̐����E�ێ� */
				stat = d0sind();
				break;
			case	11:	/* �k�`�f�[�^�x�[�X�̐����E�ێ� */
				stat = d0ladb();
				break;
			}
			break;
		case '1':		/* shell */
			menu20();
			break;
		case '7':		/* push < F7 > */
			stat = d0whre();	/* CHANGE LADB */
			break;
		case 'A':		/* f9  �i�����I���j */
		case 'E':		/* f10 �i����I���j */
			loop_sw = 0;
			break;
		}
	}
	d3term();	/* terminate process */
	exit(OK);
}

/*	FUNC. DESCRIPTION

		FUNC.NAME	: menu10
		INPUT		: argc, argv
		OUTPUT		: none
		REMARKS		:	87.10.20		N.MATSUO
*/

menu10(argc, argv)
int	argc;
char	*argv[];
{
	static	char	command[11][8] = {
		"pd_dsmk" , "pd_slmt" , "pd_kdmt" , "pd_usmt" ,
		"pd_dtcm" , "pd_cdrf" , "pd_dsdl" , "pd_back" ,
		"pd_dbcp" , "pd_sidx" , "pd_ladb"
	};
	int	i;
	char	*cp;

	if ((cp = strrchr(argv[0], '/')) != NULL)
		cp++;
	else
		cp = argv[0];

	for (i = 0; i < 11; i++) {
		if (strcmp(command[i], cp) == 0)
	 		return(i+1);
	}
	return(0);
}

/*	FUNC. DESCRIPTION

			�r�g�d�k�k�N��

		FUNC.NAME	: menu20
		INPUT		: none
		OUTPUT		: none
		REMARKS		:	87.10.20		N.MATSUO
*/

menu20()
{
	/* ��ʏ������J�[�V�Y�I�� */
	slk_clear();
	clear();
	refresh();
	endwin();

	/* �S�V�O�i������ */
	signal(SIGHUP, SIG_IGN);
	signal(SIGINT, SIG_IGN);
	signal(SIGQUIT, SIG_IGN);
	signal(SIGILL, SIG_IGN);
	signal(SIGTRAP, SIG_IGN);
	signal(SIGIOT, SIG_IGN);
	signal(SIGEMT, SIG_IGN);
	signal(SIGFPE, SIG_IGN);
	signal(SIGBUS, SIG_IGN);
	signal(SIGSEGV, SIG_IGN);
	signal(SIGSYS, SIG_IGN);

	/* �r�g�d�k�k�N�� */
	d3exec("sh", 0);

	/* �V�O�i�������ݒ� */
	d3sgnl();
}