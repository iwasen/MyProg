/************************************************************************
 *									*
 *		���Y�����ԁ@�o���p�[�h�F�x���V�X�e��			*
 *									*
 *		����		: ���C������				*
 *		�t�@�C����	: main.c				*
 *									*
 ************************************************************************/

#include "bumper.h"

/*	�����֐���`	*/
static	void	read_err(void);

static	struct key_buf	func_save; /* �t�@���N�V�����L�[�f�[�^�Z�[�u�G���A */

/*=======================================================================
 |
 |		���C������
 |
 |	int	main()
 |
 =======================================================================*/
int	main()
{
	initialize();		/* ���������� */

	menu();			/* ���j���[��� */

	final();		/* �I������ */

	return(0);
}

/*=======================================================================
 |
 |		����������
 |
 |	void	initialize()
 |
 =======================================================================*/
static	void	xxx()
{
	final();
	exit(1);
}
void	initialize()
{
	signal(SIGINT, xxx);	/* �r�s�n�o�L�[�𖳎����� */

	/*set_err_handler();*/	/* �G���[�n���h���Z�b�g */

	t_fget(&func_save);	/* �t�@���N�V�����L�[���x�����o�� */
	t_fput(&key_data);	/* �t�@���N�V�����L�[���x���ݒ� */

	cputs("\x1b[>1h");	/* �ŉ��ʍs���[�U�g�p */
	t_csroff();		/* �J�[�\������ */

	g_init();		/* �O���t�B�b�N������ */
	g_screen(3, 0, 0, 1);	/* �X�N���[�����[�h�ݒ� */
	g_color(-1, -1, -1, 1);
	g_color2(0, 0x236);

	target_id = 1;
	source_id = 1;

	t_cls();
	dspstr(24, 11, "�o�k�b���W�X�^�ǂݍ��ݒ��I�I ", C_WHITE);

	putch('*');
	if (rccm(target_id, source_id, &bumper_tbl, GR_BPT, sizeof(bumper_tbl)) != 0)
		read_err();
	putch('*');
	if (rccm(target_id, source_id, &schedule_tbl, GR_TRT, sizeof(schedule_tbl)) != 0)
		read_err();
	putch('*');
	if (rccm(target_id, source_id, dolly_tbl, GR_DLT, sizeof(dolly_tbl)) != 0)
		read_err();
	putch('*');
	if (rccm(target_id, source_id, colcnv_tbl, GR_CCT, sizeof(colcnv_tbl)) != 0)
		read_err();
	putch('*');
	if (rccm(target_id, source_id, dolcnv_tbl, GR_DCT, sizeof(dolcnv_tbl)) != 0)
		read_err();
/*
	{
		FILE	*fp;

		if ((fp = fopen("BUMPER.TBL", "rb")) != NULL) {
			fread(&bumper_tbl, sizeof(bumper_tbl), 1, fp);
			fclose(fp);
		}
		if ((fp = fopen("DOLLY.TBL", "rb")) != NULL) {
			fread(dolly_tbl, 2, 500, fp);
			fclose(fp);
		}
		if ((fp = fopen("COLCNV.TBL", "rb")) != NULL) {
			fread(colcnv_tbl, 4, 30, fp);
			fclose(fp);
		}
		if ((fp = fopen("DOLCNV.TBL", "rb")) != NULL) {
			fread(dolcnv_tbl, 6, 50, fp);
			fclose(fp);
		}
	}
*/
}

void	read_err()
{
	dspstr(28, 15, "�o�k�b���W�X�^�ǂݍ��ݎ��s", C_RED);
	getch();
	final();
	exit(1);
}

/*=======================================================================
 |
 |		�I������
 |
 |	void	final()
 |
 =======================================================================*/
void	final()
{
	t_color(0xe1);		/* �����F�𔒂ɐݒ� */

	cputs("\x1b[>1l");	/* �ŉ��ʍs�V�X�e���g�p */
	t_fput(&func_save);	/* �t�@���N�V�����L�[���x���ݒ� */

	t_csron();		/* �J�[�\���\�� */

	g_color2(0, 0);
	g_cls();		/* �O���t�B�b�N��ʃN���A */
	t_cls();		/* �e�L�X�g��ʃN���A */

	rs1_close();		/* �q�r�|�Q�R�Q�b�N���[�Y */
}