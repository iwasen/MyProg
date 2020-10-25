/*	FILE DESCRIPTION
 * ----- %Z%%M% %I% %E% %U% -----

		FILE NAME: ERCODE.H
		ROUTINE	 : HEADER FILE
		REVISION :
		REMARKS	 :		87.10.21		N.MATSUO

*/

/*		�G���[�R�[�h�`��

		8 bit code
		x x x x x x x x
		----- ---------
		  |	  |
		  |	  ---�t�@�C�����
		  |
		  -----------�G���[���
				���O�F�t�@�C���ȊO�̃G���[
				�@�O�F�t�@�C���Ɋւ���G���[
*/

/*------------ �t�@�C���Ɋւ���G���[ ----------------*/

/*	�G���[���	*/

#define	E_FN	0x20		/* �t�@�C�������� */
#define	E_FA	0x40		/* �A�N�Z�X�G���[ */
#define	E_FU	0x60		/* �㏑���G���[ */
#define	E_FF	0x80		/* �t�H�[�}�b�g�G���[ */
#define	E_FL	0xa0		/* ���b�N�G���[ */

/*	�t�@�C�����	*/

#define	F_FL	0x00		/* �t�@�C�� */
#define	F_GI	0x01		/* �O���[�v�����t�@�C�� */
#define	F_IF	0x02		/* �����t�@�C�� */
#define	F_DS	0x03		/* �k�`�f�[�^�Z�b�g */
#define	F_UF	0x04		/* ���[�U�����t�@�C�� */
#define	F_EF	0x05		/* �������t�@�C�� */
#define	F_LF	0x06		/* ���x�����t�@�C�� */
#define	F_RF	0x07		/* ���f�[�^�t�@�C�� */
#define	F_AF	0x08		/* ��̓f�[�^�t�@�C�� */
#define	F_DF	0x09		/* �k�`�f�[�^�t�@�C�� */
#define	F_KF	0x0a		/* �Œ藓�`���t�@�C�� */
#define	F_GF	0x0b		/* �O���[�v�����G�f�B�b�g�t�@�C�� */
#define	F_UE	0x0c		/* ���[�U�����G�f�B�b�g�t�@�C�� */
#define	F_TF	0x0d		/* �e���|�����t�@�C�� */
#define	F_CF	0x0e		/* �J�����g�t�@�C�� */
#define	F_KJ	0x0f		/* ���������t�@�C�� */
#define	F_GH	0x10		/* �O���\���`���t�@�C�� */
#defile	F_BK	0x11		/* �o�b�N�A�b�v�t�@�C�� */


/*-------------- �t�@�C���ȊO�̃G���[ ----------------*/

#define		E_CLEAR		-1	/*  error messege clear  */
#define		NOERR		0x00	/*  OK  */
#define		E_X		0x01	/*  command error */
#define		E_M		0x02	/*  memory error  */
#define		E_P		0x03	/*  parameter error  */
#define		E_D		0x04	/*  data error  */
#define		E_S		0x05	/*  save error  */
#define		E_E		0x06	/*  quit  */
#define		E_C		0x07	/*  curses error  */
#define		E_K		0x08	/*  kouho nasi	*/
#define		E_N		0x09	/*  directory can't make  */
#define		E_GRP_NONE_ENTRY 0x0a	/* none entry group sakuin file */
#define		E_DSNAME	 0x0b	/* no correct data set name */
