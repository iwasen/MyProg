/* ----- %Z%%M% %I% %E% %U% ----- */
/*		FILE DESCRIPTION

			FILE NAME: d3msgd.h
			ROUTINE	 : ���b�Z�[�W�e�[�u����`
			REVITION : 
			REMARKS	 :	88.06.08	S.AIZAWA

*/


/*	���b�Z�[�W�e�[�u��	*/

struct	msgtbl {
	int	msgno;		/* ���b�Z�[�W�ԍ� */
	char	type;		/* ���b�Z�[�W��� */
	int	argc;		/* �p�����[�^�̌� */
	char	*msg;		/* ���b�Z�[�W�f�[�^ */
};

extern	struct	msgtbl	msg_tbl[];	/* ���b�Z�[�W�e�[�u�� */
extern	int	msg_tbl_sz;		/* ���b�Z�[�W�e�[�u���T�C�Y */
extern	char	pdsys_err[];		/* �o�n�k�|�c�V�X�e���G���[ */