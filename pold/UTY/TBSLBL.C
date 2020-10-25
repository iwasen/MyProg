 /*
 * File		: tbslbl.c
 * Comment	: Data entry for slk labels
 *			(parts replaced in UNIX file manager)
 *
 * --- Modify history ---
 * No.   Date      Name        Description                       Report No
 * --- --------  --------  ------------------------------------  ---------
 *  2. 88/04/19  M.Hosho   for options (No_delete_line etc.)
 *  1. 88/04/07  M.Hosho   created
 *
 */

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/* �\�t�g�L�[�E���x���i�V�X�e���p�j */
/* for 1st page on chapter 1 */
#define SYS_F01	"�ꗗ��"
#define SYS_F02 "��������"
#define SYS_F03 "        "
#define SYS_F04 "�I������"
#define SYS_F05 "�����ݒ�"
#define SYS_F06 "        "
#define SYS_F07 "        "

/* for 2nd page on chapter 1 */
#define SYS_F11	" �s�폜 "
#define SYS_F12	" �sCOPY "
#define SYS_F13	"�ꗗ��"
#define SYS_F14 "rec �I��"
#define SYS_F15 " �񑀍� "
#define SYS_F16 "��������"
#define SYS_F17 "        "

/* for 3rd page on chapter 1 */
#define SYS_F21	" ��폜 "
#define SYS_F22	" ��ǉ� "
#define SYS_F23 "        "
#define SYS_F24 "rec �I��"
#define SYS_F25 " �s���� "
#define SYS_F26 "        "
#define SYS_F27 "        "

int	tb_slk1[8] = {
	7,
	(int)SYS_F01, (int)SYS_F02, (int)SYS_F03, (int)SYS_F04,
	(int)SYS_F05, (int)SYS_F06, (int)SYS_F07
	};

int	tb_slk2[8] = {
	7,
	(int)SYS_F11, (int)SYS_F12, (int)SYS_F13, (int)SYS_F14,
	(int)SYS_F15, (int)SYS_F16, (int)SYS_F17
	};

int	tb_slk3[8] = {
	7,
	(int)SYS_F21, (int)SYS_F22, (int)SYS_F23, (int)SYS_F24,
	(int)SYS_F25, (int)SYS_F26, (int)SYS_F27
	};