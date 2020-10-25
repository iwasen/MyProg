/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: ���ʃw�b�_�t�@�C��			*
 *		�t�@�C����	: pc_cns.h				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <memory.h>
#include <dos.h>
#include <signal.h>
#include <conio.h>

/*	���ꃁ�b�Z�[�W�ԍ�	*/
#define	MN_KAIWA	'8'	/* ��b�������b�Z�[�W (8xxx) */
#define	MN_ABORT	"9990"	/* �A�{�[�g��M���b�Z�[�W */
#define	MN_NORMAL	"9990"	/* �R�}���h����I�� */
#define	MN_ERROR	"9991"	/* �R�}���h�ُ�I�� */

/*	�^��`		*/
typedef	unsigned int	u_int;
typedef	unsigned char	u_char;

/*	�p�X���T�C�Y	*/
#define	SZ_PATH		64

/*	�L�[�R�[�h	*/
#define	KEY_SHIFT	0x100
#define	KEY_FUNC	0x600
#define	KEY_HELP	0x01
#define	KEY_BS		0x08
#define	KEY_TAB		0x09
#define	KEY_SFT_TAB	(KEY_SHIFT|KEY_TAB)
#define	KEY_HOME	0x0b
#define	KEY_CLR		0x0c
#define	KEY_CR		0x0d
#define	KEY_ESC		0x1b
#define	KEY_SFT_ESC	(KEY_SHIFT|KEY_ESC)
#define	KEY_RIGHT	0x1c
#define	KEY_LEFT	0x1d
#define	KEY_UP		0x1e
#define	KEY_DOWN	0x1f
#define	KEY_DEL		0x7f
#define	KEY_INS		0x11
#define	KEY_F1		(KEY_FUNC|'1')
#define	KEY_F2		(KEY_FUNC|'2')
#define	KEY_F3		(KEY_FUNC|'3')
#define	KEY_F4		(KEY_FUNC|'4')
#define	KEY_F5		(KEY_FUNC|'5')
#define	KEY_F6		(KEY_FUNC|'6')
#define	KEY_F7		(KEY_FUNC|'7')
#define	KEY_F8		(KEY_FUNC|'8')
#define	KEY_F9		(KEY_FUNC|'9')
#define	KEY_F10		(KEY_FUNC|'0')
#define	KEY_MOUSE	0x700

/*	�t�@���N�V�����L�[�ݒ�R�[�h	*/
#define	FUNC1		0x001
#define	FUNC2		0x002
#define	FUNC3		0x004
#define	FUNC4		0x008
#define	FUNC5		0x010
#define	FUNC6		0x020
#define	FUNC7		0x040
#define	FUNC8		0x080
#define	FUNC9		0x100
#define	FUNC10		0x200

/*	�J���[�R�[�h	*/
#define	C_BLACK		0
#define	C_BLUE		1
#define	C_RED		2
#define	C_MAGENTA	3
#define	C_GREEN		4
#define	C_CYAN		5
#define	C_YELLOW	6
#define	C_WHITE		7

#define	C_WAKU		0x100	/* �g */
#define	C_OFFLINE	0x101	/* �I�t���C���\�� */
#define	C_ONLINE	0x102	/* �I�����C���\�� */
#define	C_GUIDE		0x103	/* ���� */
#define	C_CNSMSG	0x104	/* �R���\�[�����b�Z�[�W */
#define	C_CRM		0x105	/* �R�}���h���� */
#define	C_ANMA		0x106	/* �������b�Z�[�W�` */
#define	C_ANMB		0x107	/* �������b�Z�[�W�a */
#define	C_ERR		0x108	/* �G���[�\�� */

/*	���b�Z�[�W���	*/
#define	MSG_ANM		1	/* �������b�Z�[�W */
#define	MSG_CRM		2	/* �R�}���h�������b�Z�[�W */
#define	MSG_CNS		3	/* �R���\�[�����b�Z�[�W */
#define	MSG_CNSR	4	/* �R���\�[�����b�Z�[�W �i�ԕ\���j*/

/*	�������b�Z�[�W�ً}�x	*/
#define	MSG_CLASS_DFLT	0	/* �ȗ� */
#define	MSG_CLASS_A1	1	/* �ŋً} */
#define	MSG_CLASS_A2	2	/* �ً} */
#define	MSG_CLASS_B	3	/* �ʏ� */

/*	�A�g���r���[�g		*/
#define	A_BLINK		0x02	/* �u�����N */
#define	A_REVERSE	0x04	/* ���o�[�X */
#define	A_ULINE		0x08	/* �A���_�[���C�� */
#define	A_BLUE		0x20	/* �� */
#define	A_RED		0x40	/* �� */
#define	A_MAGENTA	0x60	/* �� */
#define	A_GREEN		0x80	/* �� */
#define	A_CYAN		0xa0	/* ���F */
#define	A_YELLOW	0xc0	/* ���F */
#define	A_WHITE		0xe0	/* �� */

/*	�Z�O�����g�A�h���X	*/

#define	SEG_TVRAM	0xa000		/* �e�L�X�g�u�q�`�l�Z�O�����g */
#define	SEG_ATTR	0xa200		/* �A�g���r���[�g�Z�O�����g */
#define	SEG_GVRAM	0xa800		/* �O���t�B�b�N�u�q�`�l�Z�O�����g */

/*	�R�}���h�I���t�@�C���L�[���[�h	*/
#define	KWD_TTL	1
#define	KWD_COM	2
#define	KWD_PRM	3
#define	KWD_SEL	4
#define	KWD_NUM	5
#define	KWD_NAM	6
#define	KWD_MSG	7

/*	���[�J�����b�Z�[�W	*/
#define	LOC_MSG_START	1
#define	LOC_MSG_CONxxxT	10
#define	LOC_MSG_DISCON	11
#define	LOC_MSG_NOSSG	12
#define	LOC_MSG_NORESP	13
#define	LOC_MSG_CAB	14
#define	LOC_MSG_ERLINE	15
#define	LOC_MSG_ERRM	16
#define	LOC_MSG_RESTART	17
#define	LOC_MSG_1DISC	18
#define	LOC_MSG_NORMAL	100
#define	LOC_MSG_ERPRM	101
#define	LOC_MSG_EREXT	102
#define	LOC_MSG_ERLEN	103
#define	LOC_MSG_ERPATH	104
#define	LOC_MSG_ERFILE	105
#define	LOC_MSG_ERCON	106
#define	LOC_MSG_ERNOFILE	107
#define	LOC_MSG_ERCMF	110
#define	LOC_MSG_MKCMF	200
#define	LOC_MSG_CLOSE	201
#define	LOC_MSG_END	202

/*	�M�����	*/
#define	SIG_CN		0x01
#define	SIG_CNA		0x02
#define	SIG_SR		0x03
#define	SIG_STSR	0x04
#define	SIG_SRA		0x05
#define	SIG_RA		0x06
#define	SIG_RAA		0x07
#define	SIG_CQ		0x08
#define	SIG_CQA		0x09
#define	SIG_AB		0x0a
#define	SIG_SD		0x0b
#define	SIG_RD		0x0c
#define	SIG_AK		0x0d
#define	SIG_AKA		0x0e
#define	TIMEOUT		-1

/*	�R���\�[���r�f�t�@�C��	*/
struct	csg	{
	struct	c_nsg	{	/* �V�X�e���� */
		char	sys_name[9];	/* �S�̂̃V�X�e���� */
		char	grf_file[SZ_PATH];	/* �G��ʃt�@�C���� */
		char	online_id;	/* �R�}���h�h�c�i�I�����C���j*/
		char	offline_id;	/* �R�}���h�h�c�i�I�t���C���j*/
	} nsg;

	struct	c_fsg	{	/* �t�@�C���� */
		char	amsg_file[13];	/* �������b�Z�[�W�t�@�C���� */
		char	cms_file[5][13];	/* �R�}���h�I���t�@�C���� */
		char	cmsg_file[13];	/* �R�}���h�������b�Z�[�W�t�@�C���� */
		char	loc_file[13];	/* ���[�J���R�}���h�I���t�@�C���� */
		char	lmsg_file[13];	/* ۰�ٺ���މ������b�Z�[�W�t�@�C���� */
	} fsg;

	struct	c_col	{	/* ��ʂ̐F�w�� */
		int	c_waku;		/* �g */
		int	c_offline;	/* �I�t���C���\�� */
		int	c_online;	/* �I�����C���\�� */
		int	c_guide;	/* ���� */
		int	c_cnsmsg;	/* �R���\�[�����b�Z�[�W */
		int	c_crm;		/* �R�}���h���� */
		int	c_msga;		/* �������b�Z�[�W�` */
		int	c_msgb;		/* �������b�Z�[�W�a */
		int	c_err;		/* �G���[�\�� */
	} col;

	struct	{	/* ۰�كR�}���h�������b�Z�[�W�̏o�͋@����̐ݒ� */
		int	dsp;		/* ��ʕ\���L��^���� */
		int	prn;		/* �v�����^�o�͗L��^���� */
		int	fil;		/* �t�@�C���o�͗L��^���� */
		char	fname[SZ_PATH];	/* �t�@�C���o�͗L�莞�̃t�@�C���� */
	} lrm;

	struct	c_opt	{	/* �I�v�V���� */
		int	rs232c;		/* �q�r�Q�R�Q�b������x */
		int	printer;	/* �v�����^�[�@�� */
		int	cmd_to;		/* �R�}���h���̓^�C���A�E�g���� */
		int	cmd_err;	/* �R�}���h�t�@�C�����s�G���[���u */
		int	comment;	/* �R�����g�̎����}�� */
	} opt;
};
typedef	struct	csg	CSG;

/*	�V�X�e���r�f�t�@�C��	*/
struct	ssg	{
	struct	{	/* �ʃV�X�e���� */
		char	sys_name[7];	/* �ʃV�X�e���� */
		char	grf_file[SZ_PATH];	/* �G��ʃt�@�C���� */
		int	conxxxtion;	/* �R�l�N�V�����ԍ� */
		int	kanshi;		/* �������Ď����� */
	} nsg;

	struct	{	/* �V�X�e���萔 */
		int	syc_flg;	/* �V�X�e���萔�w��t���O */
		int	syc_val;	/* �V�X�e���萔 */
	} syc[10];

	struct	{	/* �������b�Z�[�W�o�͋@����̐ݒ� */
		int	dsp;		/* ��ʕ\���L��^���� */
		int	prn;		/* �v�����^�o�͗L��^���� */
		int	fil;		/* �t�@�C���o�͗L��^���� */
		char	fname[SZ_PATH];	/* �t�@�C���o�͗L�莞�̃t�@�C���� */
	} anm;

	struct	{	/* �R�}���h�������b�Z�[�W�̏o�͋@����̐ݒ� */
		int	dsp;		/* ��ʕ\���L��^���� */
		int	prn;		/* �v�����^�o�͗L��^���� */
		int	fil;		/* �t�@�C���o�͗L��^���� */
		char	fname[SZ_PATH];	/* �t�@�C���o�͗L�莞�̃t�@�C���� */
	} crm;

	struct	{	/* �R�}���h�t�@�C���ǂ݂����p�X�� */
		char	cmd_path[SZ_PATH]; /* �R�}���h�t�@�C���ǂ݂����p�X */
	} cmf;

	struct	{	/* �莞�N���R�}���h */
		char	command[80];	/* �R�}���h */
	} aec[5];
};
typedef	struct	ssg	SSG;

/*	�I�����ڃe�[�u��	*/
struct	sel_tbl	{
	int	n;		/* �I�����ڐ� */
	char	*ptr[1][2];	/* �I������ */
};
typedef	struct	sel_tbl	SEL_TBL;

/*	���l���ڃe�[�u��	*/
struct	num_tbl	{
	int	min_flg;	/* �ŏ��l�w��t���O */
	u_int	min;		/* �ŏ��l */
	int	min_syc;	/* �V�X�e���萔 */
	int	max_flg;	/* �ő�l�w��t���O */
	u_int	max;		/* �ő�l */
	int	max_syc;	/* �V�X�e���萔 */
	char	*prmid;		/* �p�����[�^���ʎq */
	char	*sel;		/* �I������ */
};
typedef	struct	num_tbl	NUM_TBL;

/*	���̍��ڃe�[�u��	*/
struct	nam_tbl	{
	int	length;		/* �ő啶���� */
	char	*sel;		/* �I������ */
	char	*ext1;		/* �g���q */
	char	*ext2;		/* �g���q */
};
typedef	struct	nam_tbl	NAM_TBL;

/*	�������ڃe�[�u��	*/
struct	gud_tbl	{
	int	length;		/* �ő啶���� */
};
typedef	struct	gud_tbl	GUD_TBL;

/*	�p�����[�^�e�[�u��	*/
struct	prt_tbl {
	int	type;		/* �p�����[�^��� */
	char	*guide;		/* �p�����[�^���� */
	union	{
		SEL_TBL	*sel;
		NUM_TBL	*num;
		NAM_TBL	*nam;
		GUD_TBL	*gud;
	} ptr;
};	
typedef	struct	prt_tbl	PRT_TBL;

/*	�p�����[�^��ރe�[�u��	*/
struct	prm_tbl	{
	char	*name;		/* �p�����[�^�� */
	char	*guide;		/* �p�����[�^���� */
	int	n;		/* �p�����[�^�� */
	PRT_TBL	*ptr;		/* �p�����[�^�e�[�u���|�C���^ */
};
typedef	struct	prm_tbl	PRM_TBL;

/*	�R�}���h�e�[�u��	*/
struct	cmd_tbl	{
	char	*name;		/* �R�}���h�� */
	char	*guide;		/* �R�}���h���� */
	int	n;		/* �p�����[�^�̎�ނ̌� */
	PRM_TBL	*ptr;		/* �p�����[�^��ރe�[�u���|�C���^ */
};
typedef	struct	cmd_tbl	CMD_TBL;

/*	�R�}���h�I���e�[�u��	*/
struct	cms_tbl	{
	char	*title;		/* �^�C�g���� */
	char	*guide;		/* ������ */
	int	n;		/* �R�}���h�� */
	CMD_TBL	*ptr;		/* �R�}���h�e�[�u���|�C���^ */
};
typedef	struct	cms_tbl	CMS_TBL;

/*	���b�Z�[�W�e�[�u��	*/
struct	msg_tbl	{
	int	n_msg;		/* ���b�Z�[�W�� */
	FILE	*fp;		/* ���b�Z�[�W�t�@�C���|�C���^ */
	struct	msg_no	{
		int	msg_no;		/* ���b�Z�[�W�ԍ� */
		long	offset;		/* �t�@�C���I�t�Z�b�g */
	} *msg;
};
typedef	struct	msg_tbl	MSG_TBL;

/*	���b�Z�[�W�ϕ����	*/
struct	kahenbu	{
	int	kahenbu_no;	/* �ϕ��m���D */
	int	type;		/* �O�F���l�^�@�P�F�����^ */
};
typedef	struct	kahenbu	KAHENBU;

/*	�L���[		*/
struct	queue	{
	void	*data;		/* �f�[�^�|�C���^ */
	struct	queue	*chain;	/* �`�F�C���|�C���^ */
};
typedef	struct	queue	QUEUE;

/*	���b�Z�[�W�L���[	*/
struct	msg_que	{
	int	type;		/* ���b�Z�[�W�^�C�v */
	char	*msg;		/* ���b�Z�[�W�f�[�^ */
};
typedef	struct	msg_que MSG_QUE;

/*	�R�}���h�t�@�C�����	*/
struct	cmf_tbl	{
	char	name[9];	/* �R�}���h�t�@�C���� */
	char	guide[80];	/* �@�\�T�v */
};
typedef	struct	cmf_tbl	CMF_TBL;

/*	�Œ蕔��ʕ\���f�[�^	*/
struct	scrdat	{
	int	pos_x;			/* �\�����ʒu */
	int	pos_y;			/* �\���s�ʒu */
	char	*data;			/* �\���f�[�^ */
};
typedef	struct scrdat SCRDAT;

/*	�r���\���e�[�u��		*/
struct	line	{
	int	flag;			/* �t���O�@=0:���@=1:�g  =-1:�I�� */
	int	pos_x1;			/* �J�n���ʒu */
	int	pos_y1;			/* �J�n�s�ʒu */
	int	pos_x2;			/* �I�����ʒu */
	int	pos_y2;			/* �I���s�ʒu */
};
typedef	struct	line	LINE;

/*	�}�E�X���	*/
struct	mouse	{
	int	x;			/* �w���W */
	int	y;			/* �x���W */
	int	len;			/* ���� */
	int	s_code;			/* ���ʃR�[�h */
	int	l_code;
	int	r_code;
};
typedef	struct	mouse	MOUSE;

/*	�t�@���N�V�����L�[�ݒ�f�[�^	*/

struct	key_buf				/*  bdos format of func-key buffer  */ 
{
	char	fun[20][16];
	char	cur[11][6];
};

/*	�O���֐���`	*/
extern	void	prog1(void);
extern	void	prog2(void);
extern	void	prog3(void);
extern	void	prog4(void);
extern	void	prog5(void);
extern	void	prog6(void);
extern	void	prog6c(void);
extern	void	prog7(void);
extern	void	prog8(void);
extern	void	prog9(void);

/*	main.c	*/
extern	void	initialize(void);
extern	void	final(void);

/*	console.c	*/
extern	void	dspstr(int, int, char *, int);
extern	int	dspstr2(int, int, int, char *, int);
extern	void	dspnstr(int, int, char *, int, int);
extern	void	xprintf(int, int, int, char *, ...);
extern	void	dspscr(SCRDAT *, int);
extern	void	dspspc(int, int, int);
extern	void	clrtoeol(void);
extern	void	dspline(LINE *, int);
extern	int	t_color_code(int);
extern	int	g_color_code(int);
extern	void	dsp_field(int, int, int, int);
extern	int	inkey(void);
extern	int	getchr(void);
extern	void	set_keybuf(char *);
extern	int	inputs(char *, int, int, int, int, int);
extern	int	inputm(char *, int, int, int, int, int);
extern	void	m_reg(MOUSE *, int);
extern	void	attrib(int, int, int, int, int);
extern	void	dspgrf(char *);
extern	void	setfunc(int);
extern	void	dsp_msgenv(void);
extern	void	grfputs(int, int, char *, int, char *);
extern	void	hcopy(int, int, int, int);

/*	sub.c	*/
extern	void	exec_prog(int);
extern	void	beep(void);
extern	void	alxxx_mj(void);
extern	void	alxxx_mn(void);
extern	int	strcount(char *);
extern	void	*talloc(int);
extern	void	en_queue(QUEUE **, void *,int);
extern	void	*de_queue(QUEUE **);

/*	cmf.c	*/
extern	void	find_cmf(void);
extern	void	read_cmf(void);
extern	void	write_cmf(void);
extern	void	enq_cmf(char *);
extern	char	*deq_cmf(void);
extern	char	*make_cmfpath(char *);
extern	int	start_cmf(char *);
extern	void	exec_cmf(void);

/*	cms.c	*/
extern	void	read_cmdfile(void);
extern	CMS_TBL	*search_cms(char *);

/*	msg.c	*/
extern	void	restore_scr(void);
extern	void	save_scr(void);
extern	void	out_msg(void);
extern	void	dsp_msg(char *, int);
extern	int	 next_line(int);
extern	void	enq_msg(int, char *);
extern	MSG_QUE	*deq_msg(void);
extern	void	free_msg(MSG_QUE *);
extern	void	read_msgfile(void);
extern	char 	*cnv_msg(char *, MSG_TBL *, int *);
extern	void	dsp_loc_msg(int, ...);

/*	bios.c	*/
extern	void	t_loc(int,int);
extern	void	t_color(int);
extern	void	t_cls(void);
extern	void	t_csron(void);
extern	void	t_csroff(void);
extern	void	t_getfont(int,char *);
extern	void	t_setfont(int,char *);
extern	void	t_fget(struct key_buf *);
extern	void	t_fput(struct key_buf *);
extern	int	g_init(void);
extern	int	g_screen(int,int,int,int);
extern	int	g_color(int,int,int,int);
extern	int	g_cls(void);
extern	int	g_line(int, int, int, int, int, int, int, ...);
extern	int	m_init(void);
extern	void	m_csron(void);
extern	void	m_csroff(void);
extern	void	m_pos(int *,int *,int *,int *);
extern	void	m_loc(int,int);
extern	void	timset(int);
extern	int	far _interrupt tm_int(void);
extern	void	timreset(void);
extern	void	rs1_open(int,int,int);
extern	int	rs1_rxc(void);
extern	int	rs1_get(void);
extern	void	rs1_put(int);
extern	void	rs1_close(void);
extern	void	lputc(int);

/*	sg.c	*/
extern	int	find_csg(void);
extern	void	read_csg(void);
extern	void	write_csg(void);
extern	int	find_ssg(int);
extern	void	read_ssg(void);
extern	void	write_ssg(void);

/*	com.c	*/
extern	void	rs_open(void);
extern	int	conxxxt(int);
extern	void	disconxxxt(void);
extern	void	send_sig(int);
extern	void	send_sd(char *);
extern	int	receive(void);

/*	local.c	*/
extern	int	exec_local(char *);
extern	void	edit_loc_cmd(void);

/*	data.c	*/
extern	char	csg_file[13];
extern	CSG	csg_default;
extern	CSG	csg;
extern	char	ssg_file[13];
extern	SSG	ssg;
extern	char	csg_grf[21216];
extern	char	ssg_grf[21216];
extern	char	cfg_grf[21216];
extern	CMS_TBL	*cmstbl[5];
extern	CMS_TBL	*loctbl;
extern	CMS_TBL	*cms_current;
extern	CMD_TBL	*cmd_current;
extern	PRM_TBL	*prm_current;
extern	MSG_TBL	*anm_msg;
extern	MSG_TBL	*crm_msg;
extern	MSG_TBL	*loc_msg;
extern	int	online;
extern	int	local;
extern	int	mkcmd;
extern	int	prog;
extern	int	old_prog;
extern	char	cmdbuf[160];
extern	int	csr_y;
extern	QUEUE	*msg_que;
extern	char	t_save_buf[80*2*18];
extern	char	a_save_buf[80*2*18];
extern	int	mouse;
extern	int	loc_seq;
extern	char	cmf_name[13];
extern	char	cmf_guide[80];
extern	CMF_TBL	*cmf_tbl;
extern	int	n_cmf;
extern	QUEUE	*cmf_que;
extern	int	opt_t;
extern	char	password[11];
extern	int	stop_flag;
extern	int	disconxxxt_request;
extern	char	rcv_buf[1024+1];
extern	int	tm_ovf;
extern	int	exec_cmf_flag;
extern	FILE	*fp_cmf;
extern	char	ab_msg[];
extern	struct key_buf key_data;

/*	guide.c	*/
extern	char	msg10[];
extern	char	msg20[];
extern	char	msg30[];
extern	char	msg40[];
extern	char	msg100[];
extern	char	msg110[];
extern	char	msg120[];
extern	char	msg130[];
extern	char	msg140[];
extern	char	msg150[];
extern	char	msg160[];
extern	char	msg200[];
extern	char	msg210[];
extern	char	msg220[];
extern	char	msg230[];
extern	char	msg300[];
extern	char	msg301[];
extern	char	msg310[];
extern	char	msg320[];
extern	char	msg400[];
extern	char	msg401[];
extern	char	msg410[];
extern	char	msg500[];
extern	char	msg501[];
extern	char	msg510[];
extern	char	msg520[];
extern	char	msg530[];
extern	char	msg540[];
extern	char	msg600[];
extern	char	msg601[];
extern	char	msg610[];
extern	char	msg620[];
extern	char	msg650[];
extern	char	msg660[];
extern	char	msg670[];
extern	char	msg680[];
extern	char	msg700[];
extern	char	msg701[];
extern	char	msg710[];
extern	char	msg720[];
extern	char	msg800[];
extern	char	msg810[];
extern	char	msg820[];
extern	char	msg900[];
extern	char	msg910[];
extern	char	msg920[];
extern	char	err10[];