/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: �h�r�`�l�֌W				*
 *		�t�@�C����	: isam.h				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#define	BLOCK_SIZE	512	/* �C���f�b�N�X�t�@�C���̃u���b�N�T�C�Y */
#define	MAX_IDX		10	/* �����ɃI�[�v���ł���C���f�b�N�X�t�@�C����*/

/*	�C���f�b�N�X�i�m�c�w�j�t�@�C���̃w�b�_	*/
struct	idx_h	{
	LONG	root;		/* ���[�g�̃u���b�N�m���D */
	LONG	nBlock;		/* �u���b�N�� */
	LONG	dummy1;		/* */
	USHORT	lKey;		/* �L�[�̒��� */
	USHORT	order;		/* ���� */
	USHORT	type;		/* �f�[�^��� */
	USHORT	lRec;		/* �P���R�[�h�̒��� */
	USHORT	dummy2;		/* */
	CHAR	dummy3;		/* */
	CHAR	uniq;		/* ���j�[�N�t���O */
	CHAR	index[100];	/* �C���f�b�N�X�\���� */
	CHAR	work[388];	/* ���[�N�G���A */
};
typedef	struct	idx_h	IDX_H;

/*	�C���f�b�N�X�u���b�N���	*/
struct	idx_b	{
	CHAR	block[BLOCK_SIZE];	/* �u���b�N��� */
	CHAR	work[104];	/* �ǉ��p���[�N�G���A */
	LONG	bno;		/* �u���b�N�m���D */
	SHORT	cp;		/* �J�����g�|�C���^ */
	struct	idx_b	*fwp;	/* �`�F�C���|�C���^ */
	struct	idx_b	*bwp;	/* �`�F�C���|�C���^ */
};
typedef	struct	idx_b	IDX_B;

/*	�C���f�b�N�X�t�@�C���Ǘ����	*/
struct	idx	{
	CHAR	fileName[80];	/* �t�@�C���� */
	int	fh;		/* �t�@�C���n���h�� */
	IDX_H	*ihp;		/* �w�b�_���|�C���^ */
	IDX_B	*bp;		/* �J�����g�u���b�N���|�C���^ */
	IDX_B	*top;		/* �u���b�N���|�C���^ */
	IDX_B	*bottom;	/* �u���b�N���|�C���^ */
	CHAR	*keyp;		/* �L�[�|�C���^ */
	BOOL	wFlag;		/* �w�b�_�������݃t���O */
};
typedef	struct	idx	IDX;

/*	�f�[�^�i�c�a�e�j�t�@�C���̃w�b�_	*/
struct	dbf_h	{
	CHAR	flag;		/* �f�[�^�x�[�X�e�L�X�g�t�@�C���ۗL�t���O */
	CHAR	date[3];	/* �ŏI�X�V�� */
	LONG	nRec;		/* ���R�[�h�� */
	USHORT	lHeader;	/* �w�b�_���̒��� */
	USHORT	lRec;		/* �P���R�[�h�̒��� */
	CHAR	reserve[20];	/* �V�X�e���\��̈� */
};
typedef	struct	dbf_h	DBF_H;

/*	�t�B�[���h���	*/
struct	field	{
	CHAR	name[11];	/* �t�B�[���h�� */
	CHAR	type;		/* �t�B�[���h�`�� */
	CHAR	far *fad;	/* �t�B�[���h�����e�̃��������A�h���X */
	CHAR	lField;	/* �t�B�[���h�� */
	CHAR	lDec;		/* �����ʌ��� */
	CHAR	reserve1[5];	/* �V�X�e���\��̈� */
	CHAR	flag;		/* SET FIELDS ON/OFF �̐ݒ�t���O */
	CHAR	reserve2[8];	/* �V�X�e���\��̈� */
};
typedef	struct	field	FIELD;

/*	�f�[�^�t�@�C���Ǘ����	*/
struct	dbf	{
	CHAR	fileName[80];	/* �t�@�C���� */
	int	fh;		/* �t�@�C���n���h�� */
	DBF_H	*dhp;		/* �w�b�_���ւ̃|�C���^ */
	FIELD	*flp;		/* �t�B�[���h���ւ̃|�C���^ */
	USHORT	nField;		/* �t�B�[���h�� */
	CHAR	*rbp;		/* ���R�[�h�o�b�t�@�ւ̃|�C���^ */
	LONG	rno;		/* �J�����g���R�[�h�m���D */
	BOOL	update;		/* �X�V�t���O */
	BOOL	wFlag;		/* �w�b�_�������݃t���O */
};
typedef	struct	dbf	DBF;

/*	�f�[�^�x�[�X�Ǘ����	*/
struct	db	{
	DBF	*dp;		/* �f�[�^�t�@�C����� */
	IDX	*ip[MAX_IDX+1];	/* �C���f�b�N�X�t�@�C����� */
	USHORT	master;		/* �}�X�^�[�C���f�b�N�X */
	USHORT	nIdx;		/* �C���f�b�N�X�t�@�C���� */
	LONG	rno;		/* �J�����g���R�[�h�m���D */
	BOOL	bof;		/* �a�n�e�t���O */
	BOOL	eof;		/* �d�n�e�t���O */
	DOSFSRSEM	sem;	/* �Z�}�t�H */
	BOOL	updateFlag;	/* �X�V�t���O */
	struct	db	*chain;	/* �`�F�C���|�C���^ */
};
typedef	struct	db	DB;

/*	�f�[�^�t�@�C���쐬���	*/
struct	dbf_i	{
	CHAR	name[11];	/* �t�B�[���h�� */
	CHAR	type;		/* �t�B�[���h�`�� */
	CHAR	lField;		/* �t�B�[���h�� */
	CHAR	lDec;		/* �����ʌ��� */
};
typedef	struct	dbf_i	DBF_I;

/*	�f�[�^��`	*/
extern	SHORT	_db_err_code;		/* �G���[�R�[�h */
extern	SHORT	_db_set_deleted;	/* �폜���R�[�h�����t���O */

/*	�G���[�R�[�h	*/
#define	IE_MEMORY	1	/* �������s�� */
#define	IE_FOPEN	2	/* �t�@�C���I�[�v���G���[ */
#define	IE_NOFILE	3	/* �I�[�v������t�@�C�����Ȃ� */
#define	IE_IDXOVER	4	/* �C���f�b�N�X�t�@�C������������ */
#define	IE_TMPFILE	5	/* �e���|�����t�@�C�������Ȃ� */
#define	IE_NOKEY	6	/* �w�肳�ꂽ�L�[�����݂��Ȃ� */
#define	IE_DBLKEY	7	/* �L�[���d�����Ă���*/
#define	IE_RECNO	8	/* ���R�[�h�ԍ����s�� */
#define	IE_EOF		9	/* �a�n�e�܂��͂d�n�e */
#define	IE_STRFILE	10	/* �\���t�@�C�����������Ȃ� */
#define	IE_READ		11	/* �t�@�C�����������G���[ */
#define	IE_WRITE	12	/* �t�@�C�������������G���[ */
#define	IE_IDXKEY	13	/* �C���f�b�N�X�̃L�[�\�������s�� */