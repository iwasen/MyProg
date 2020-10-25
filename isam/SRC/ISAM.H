/************************************************************************
 *									*
 *		�h�r�`�l���C�u����					*
 *									*
 *		����		: ���ʃw�b�_�t�@�C��			*
 *		�t�@�C����	: isam.h				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#define	BLOCK_SIZE	512	/* �C���f�b�N�X�t�@�C���̃u���b�N�T�C�Y */
#define	MAX_IDX		10	/* �����ɃI�[�v���ł���C���f�b�N�X�t�@�C����*/

/*	�^��`		*/
#ifndef	u_int
#define	u_int	unsigned int
#endif
#ifndef	u_char
#define	u_char	unsigned char
#endif
#ifndef	u_long
#define	u_long	unsigned long
#endif

/*	�C���f�b�N�X�i�m�c�w�j�t�@�C���̃w�b�_	*/
struct	idx_h	{
	long	root;		/* ���[�g�̃u���b�N�m���D */
	long	n_block;	/* �u���b�N�� */
	long	dummy1;		/* */
	int	l_key;		/* �L�[�̒��� */
	int	order;		/* ���� */
	int	type;		/* �f�[�^��� */
	int	l_rec;		/* �P���R�[�h�̒��� */
	int	dummy2;		/* */
	char	dummy3;		/* */
	char	uniq;		/* ���j�[�N�t���O */
	char	index[100];	/* �C���f�b�N�X�\���� */
	char	work[388];	/* ���[�N�G���A */
};
typedef	struct	idx_h	IDX_H;

/*	�C���f�b�N�X�u���b�N���	*/
struct	idx_b	{
	char	block[BLOCK_SIZE];	/* �u���b�N��� */
	char	work[104];	/* �ǉ��p���[�N�G���A */
	long	bno;		/* �u���b�N�m���D */
	int	cp;		/* �J�����g�|�C���^ */
	struct	idx_b	*fwp;	/* �`�F�C���|�C���^ */
	struct	idx_b	*bwp;	/* �`�F�C���|�C���^ */
};
typedef	struct	idx_b	IDX_B;

/*	�C���f�b�N�X�t�@�C���Ǘ����	*/
struct	idx	{
	char	file_name[80];	/* �t�@�C���� */
	int	fh;		/* �t�@�C���n���h�� */
	IDX_H	*ihp;		/* �w�b�_���|�C���^ */
	IDX_B	*bp;		/* �J�����g�u���b�N���|�C���^ */
	IDX_B	*top;		/* �u���b�N���|�C���^ */
	IDX_B	*bottom;	/* �u���b�N���|�C���^ */
	char	*keyp;		/* �L�[�|�C���^ */
	int	w_flag;		/* �w�b�_�������݃t���O */
};
typedef	struct	idx	IDX;

/*	�f�[�^�i�c�a�e�j�t�@�C���̃w�b�_	*/
struct	dbf_h	{
	char	flag;		/* �f�[�^�x�[�X�e�L�X�g�t�@�C���ۗL�t���O */
	char	date[3];	/* �ŏI�X�V�� */
	long	n_rec;		/* ���R�[�h�� */
	int	l_header;	/* �w�b�_���̒��� */
	int	l_rec;		/* �P���R�[�h�̒��� */
	char	reserve[20];	/* �V�X�e���\��̈� */
};
typedef	struct	dbf_h	DBF_H;

/*	�t�B�[���h���	*/
struct	field	{
	char	name[11];	/* �t�B�[���h�� */
	char	type;		/* �t�B�[���h�`�� */
	char	far *fad;	/* �t�B�[���h�����e�̃��������A�h���X */
	u_char	l_field;	/* �t�B�[���h�� */
	u_char	l_dec;		/* �����ʌ��� */
	char	reserve1[5];	/* �V�X�e���\��̈� */
	char	flag;		/* SET FIELDS ON/OFF �̐ݒ�t���O */
	char	reserve2[8];	/* �V�X�e���\��̈� */
};
typedef	struct	field	FIELD;

/*	�f�[�^�t�@�C���Ǘ����	*/
struct	dbf	{
	char	file_name[80];	/* �t�@�C���� */
	int	fh;		/* �t�@�C���n���h�� */
	DBF_H	*dhp;		/* �w�b�_���ւ̃|�C���^ */
	FIELD	*flp;		/* �t�B�[���h���ւ̃|�C���^ */
	int	n_field;	/* �t�B�[���h�� */
	char	*rbp;		/* ���R�[�h�o�b�t�@�ւ̃|�C���^ */
	long	rno;		/* �J�����g���R�[�h�m���D */
	int	update;		/* �X�V�t���O */
	int	w_flag;		/* �w�b�_�������݃t���O */
};
typedef	struct	dbf	DBF;

/*	�f�[�^�x�[�X�Ǘ����	*/
struct	db	{
	DBF	*dp;		/* �f�[�^�t�@�C����� */
	IDX	*ip[MAX_IDX+1];	/* �C���f�b�N�X�t�@�C����� */
	int	master;		/* �}�X�^�[�C���f�b�N�X */
	int	n_idx;		/* �C���f�b�N�X�t�@�C���� */
	long	rno;		/* �J�����g���R�[�h�m���D */
	int	bof;		/* �a�n�e�t���O */
	int	eof;		/* �d�n�e�t���O */
	struct	db	*chain;	/* �`�F�C���|�C���^ */
};
typedef	struct	db	DB;

/*	�f�[�^�t�@�C���쐬���	*/
struct	dbf_i	{
	char	name[11];	/* �t�B�[���h�� */
	char	type;		/* �t�B�[���h�`�� */
	u_char	l_field;	/* �t�B�[���h�� */
	u_char	l_dec;		/* �����ʌ��� */
};
typedef	struct	dbf_i	DBF_I;

/*	�֐���`	*/
extern	DB	*db_open(char *);
extern	DB	*db_create(char *, DBF_I *, int);
extern	DB	*db_create_from(char *, char *);
extern	void	db_close(DB *);
extern	void	db_close_all(void);
extern	void	db_flush(DB *);
extern	void	db_flush_all(void);
extern	int	db_index(DB *, char *);
extern	int	db_idx_create(DB *, char *, char *, int);
extern	void	db_chgidx(DB *, int);
extern	void	db_search(DB *, char *, int);
extern	long	db_count(DB *, char *, int);
extern	void	db_store(DB *, char *);
extern	void	db_update(DB *, char *);
extern	void	db_delete(DB *);
extern	void	db_delete2(DB *);
extern	void	db_recall(DB *);
extern	void	db_top(DB *);
extern	void	db_bottom(DB *);
extern	void	db_set(DB *, long);
extern	void	db_skip(DB *, long);
extern	void	db_reindex(DB *);
extern	void	db_pack(DB *);
extern	void	db_read(DB *, char *);
extern	void	db_copy(DB *, DB *);
extern	int	db_check_deleted(DB *);

extern	DBF	*dbf_open(char *);
extern	DBF	*dbf_create(char *, DBF_I *, int);
extern	void	dbf_read(DBF *, long);
extern	void	dbf_write(DBF *, long);
extern	void	dbf_close(DBF *);
extern	void	dbf_flush(DBF *);
extern	void	dbf_delete(DBF *, long);
extern	void	dbf_recall(DBF *, long);
extern	void	dbf_pack(DBF *);
extern	int	dbf_check_deleted(DBF *, long);
extern	void	dbf_copy(DBF *, DBF *);

extern	IDX	*idx_open(char *);
extern	IDX	*idx_create(char *, char *, int);
extern	void	idx_setinfo(IDX *, int, int);
extern	IDX_B	*idx_read(IDX *, long);
extern	IDX_B	*idx_getblk(IDX *, long);
extern	void	idx_write(IDX *, IDX_B *);
extern	void	idx_close(IDX *);
extern	void	idx_flush(IDX *);
extern	long	idx_search(IDX *, void *, int);
extern	int	idx_compare(IDX *, void *, int);
extern	void	idx_store(IDX *, char *, long);
extern	void	idx_delete(IDX *);
extern	long	idx_next(IDX *);
extern	long	idx_back(IDX *);
extern	long	idx_top(IDX *);
extern	long	idx_bottom(IDX *);
extern	long	idx_count(IDX *, void *, int);

extern	void	dbf_sort(DBF *, char *, char *);
extern	void	idx_make(DBF *, IDX *);

extern	long	db_reccount(DB *);
extern	int	db_bof(DB *);
extern	char	*db_dbf(DB *);
extern	int	db_eof(DB *);
extern	char	*db_field(DB *, int);
extern	char	*db_ndx(DB *, int);
extern	long	db_recno(DB *);
extern	int	db_recsize(DB *);
extern	void	set_deleted(int);

extern	void	db_err_handler(void (*)());
extern	void	db_err(int);
extern	double	natof(char *, int);
extern	int	natoi(char *, int);
extern	int	get_key(DBF *, IDX_H *, char *);
extern	FIELD	*get_field(DBF *, char *);

/*	�f�[�^��`	*/
extern	int	_db_err_code;		/* �G���[�R�[�h */
extern	int	_db_set_deleted;	/* �폜���R�[�h�����t���O */

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