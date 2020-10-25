/************************************************************************
 *									*
 *		�q�r�|�Q�R�Q�b���C�u����				*
 *									*
 *		����		: �w�b�_�t�@�C��			*
 *		�t�@�C����	: rs232c.h				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

/*	�`���l���ԍ�	*/
#define	RSCH1		0		/* �`���l���P */
#define	RSCH2		1		/* �`���l���Q */
#define	RSCH3		2		/* �`���l���R */

/*	�������[�h	*/
#define	SYNC		0		/* �������[�h */
#define	ASYNC		1		/* �����������[�h */

/*	�S�^����d���[�h	*/
#define	DUPLEX_FULL	0		/* �S��d */
#define	DUPLEX_HALF	1		/* ����d */

/*	�{�[���[�g	*/
#define	BLT_75		0
#define	BLT_150		1
#define	BLT_300		2
#define	BLT_600		3
#define	BLT_1200	4
#define	BLT_2400	5
#define	BLT_4800	6
#define	BLT_9600	7
#define	BLT_19200	8

/*	�p���e�B�[	*/
#define	PARITY_NONE	0x00		/* �p���e�B�[���� */
#define	PARITY_EVEN	0x30		/* �����p���e�B�[ */
#define	PARITY_ODD	0x10		/* ��p���e�B�[ */

/*	�L�����N�^��	*/
#define	CHR_5		0x00		/* �T������ */
#define	CHR_6		0x04		/* �U������ */
#define	CHR_7		0x08		/* �V������ */
#define	CHR_8		0x0c		/* �W������ */

/*	�X�g�b�v�r�b�g��	*/
#define	STP_1		0x40		/* �P�r�b�g */
#define	STP_1_5		0x80		/* �P�D�T�r�b�g */
#define	STP_2		0xc0		/* �Q�r�b�g */

/*	�t���[����	*/
#define	FLOW_NONE	0		/* �t���[���䖳�� */
#define	FLOW_SOFT	1		/* �w�n�m�^�w�n�e�e���� */
#define	FLOW_HARD	2		/* �q�s�r�^�b�s�r���� */

/*	�c�s�q����	*/
#define	DTR_OFF		0
#define	DTR_ON		1

/*	���荞�݃R���g���[���h�^�n�|�[�g�A�h���X	*/
#define	IOP_ICM_EOI	0x00		/* �}�X�^���荞�݃R���g���[�� */
#define	IOP_ICM_IMR	0x02		/* �}�X�^���荞�݃}�X�N���W�X�^ */
#define	IOP_ICS_EOI	0x08		/* �X���[�u���荞�݃R���g���[�� */
#define	IOP_ICS_IMR	0x0a		/* �X���[�u���荞�݃}�X�N���W�X�^ */

/*	�r�h�n�|�b�g�P �h�^�n�|�[�g�A�h���X	*/
#define	IOP_RS1_DAT	0x30		/* �f�[�^�q�^�v���W�X�^ */
#define	IOP_RS1_STS	0x32		/* �X�e�[�^�X���W�X�^ */
#define	IOP_RS1_MOD	0x32		/* ���[�h�^�R�}���h���W�X�^ */
#define	IOP_RS1_SYN	0x32		/* �����L�����N�^���W�X�^ */

/*	�r�h�n�|�b�g�Q �h�^�n�|�[�g�A�h���X	*/
#define	IOP_RS2_IMR	0xb0		/* ���荞�݃}�X�N���W�X�^ */
#define	IOP_RS2_SGN	0xb0		/* ���[�h�V�O�i���^���荞�݃��x�� */
#define	IOP_RS2_DAT	0xb1		/* �f�[�^�q�^�v���W�X�^ */
#define	IOP_RS2_STS	0xb3		/* �X�e�[�^�X���W�X�^ */
#define	IOP_RS2_MOD	0xb3		/* ���[�h�^�R�}���h���W�X�^ */
#define	IOP_RS2_SYN	0xb3		/* �����L�����N�^���W�X�^ */

/*	�r�h�n�|�b�g�R �h�^�n�|�[�g�A�h���X	*/
#define	IOP_RS3_IMR	0xb2		/* ���荞�݃}�X�N���W�X�^ */
#define	IOP_RS3_SGN	0xb2		/* ���[�h�V�O�i���^���荞�݃��x�� */
#define	IOP_RS3_DAT	0xb9		/* �f�[�^�q�^�v���W�X�^ */
#define	IOP_RS3_STS	0xbb		/* �X�e�[�^�X���W�X�^ */
#define	IOP_RS3_MOD	0xbb		/* ���[�h�^�R�}���h���W�X�^ */
#define	IOP_RS3_SYN	0xbb		/* �����L�����N�^���W�X�^ */

/*	�V�X�e���|�[�g	*/
#define	IOP_SYS_SGN	0x33		/* ���[�h�V�O�i�� */
#define	IOP_SYS_RD	0x35		/* �ǂݍ��� */
#define	IOP_SYS_WRT	0x35		/* �������� */
#define	IOP_SYS_SET	0x37		/* �r�b�g�Z�b�g */

/*	�^�C�}�R���g���[���h�^�n�|�[�g�A�h���X	*/
#define	IOP_TMC_CWW	0x77		/* �R���g���[�����[�h���C�g */
#define	IOP_TMC_C2L	0x75		/* �J�E���g���Q���[�h */
#define	IOP_TMC_C0L	0x71		/* �J�E���^���O���[�h */

/*	�r�h�n�R�}���h	*/
#define	CMD_TXE		0x01
#define	CMD_DTR		0x02
#define	CMD_RXE		0x04
#define	CMD_SBRK	0x08
#define	CMD_ER		0x10
#define	CMD_RTS		0x20
#define	CMD_IR		0x40
#define	CMD_EH		0x80

/*	�r�h�n�X�e�[�^�X	*/
#define	STS_TXRDY	0x01
#define	STS_RXRDY	0x02
#define	STS_TXE		0x04
#define	STS_PE		0x08
#define	STS_OE		0x10
#define	STS_FE		0x20
#define	STS_SYNDET	0x40
#define	STS_DSR		0x80

/*	�q�r�|�Q�R�Q�b�M����	*/
#define	SGN_CD		0x20
#define	SGN_CS		0x40
#define	SGN_CI		0x80

/*	�q�r�|�Q�R�Q�b���荞�݃}�X�N	*/
#define	IMR_RXRE	0x01
#define	IMR_TXEE	0x02
#define	IMR_TXRE	0x04

/*	�w�n�m�^�w�n�e�e�R�[�h	*/
#define	XON		0x11
#define	XOFF		0x13

/*	�^��`		*/
#ifndef	u_int
#define	u_int	unsigned int
#endif
#ifndef	u_char
#define	u_char	unsigned char
#endif

/*	�q�r�|�Q�R�Q�b�I�[�v���p�����[�^	*/
struct	rsprm	{
	int	sync_mode;	/* �������[�h */
	int	duplex;		/* ��d���[�h */
	int	blt;		/* �{�[���[�g */
	int	parity;		/* �p���e�B�[ */
	int	chr_len;	/* �L�����N�^�� */
	int	stp_len;	/* �X�g�b�v�r�b�g�� */
	int	flow_ctrl;	/* �t���[���� */
	int	dtr;		/* �c�s�q���� */
	u_int	syn_chr;	/* �����L�����N�^ */
	u_int	buf_size;	/* ��M�o�b�t�@�T�C�Y */
	char	*bufp;		/* ��M�o�b�t�@�|�C���^ */
};
typedef	struct	rsprm	RSPRM;

/*	�֐��v���g�^�C�v	*/
/*	rsx.c	*/
extern	int	rsx_open(int, RSPRM *);
extern	int	rsx_reopen(int, RSPRM *);
extern	void	rsx_close(int);
extern	int	rsx_rxc(int);
extern	int	rsx_get(int);
extern	void	rsx_clear(int);
extern	void	rsx_put(int, int);
extern	void	rsx_send(int, char *, int);
extern	int	rsx_sendreq(int, char *, int);
extern	void	rsx_swait(int);
extern	void	rsx_flow(int, int);
extern	void	rsx_pad(int, char *, int, char *, int);
extern	void	rsx_rcvint(int, int (*)(int));
extern	void	rsx_ehm(int);
extern	int	rsx_dsr(int);
extern	int	rsx_cd(int);
extern	int	rsx_cts(int);
extern	int	rsx_ci(int);
extern	void	rsx_dtr(int, int);
extern	void	rsx_rts(int, int);
extern	void	rsx_break(int, int);
extern	void	rsx_cmd(int, int ,int);
extern	int	rsx_sts(int);

/*	rs1.c	*/
extern	int	rs1_open(RSPRM *);
extern	int	rs1_reopen(RSPRM *);
extern	void	rs1_close(void);
extern	int	rs1_rxc(void);
extern	int	rs1_get(void);
extern	void	rs1_clear(void);
extern	void	rs1_put(int);
extern	void	rs1_send(char *, int);
extern	int	rs1_sendreq(char *, int);
extern	void	rs1_swait(void);
extern	void	rs1_flow(int);
extern	void	rs1_pad(char *, int, char *, int);
extern	void	rs1_rcvint(int (*)(int));
extern	void	rs1_ehm(void);
extern	int	rs1_dsr(void);
extern	int	rs1_cd(void);
extern	int	rs1_cts(void);
extern	int	rs1_ci(void);
extern	void	rs1_dtr(int);
extern	void	rs1_rts(int);
extern	void	rs1_break(int);
extern	void	rs1_cmd(int ,int);
extern	int	rs1_sts(void);

/*	rs2.c	*/
extern	int	rs2_open(RSPRM *);
extern	int	rs2_reopen(RSPRM *);
extern	void	rs2_close(void);
extern	int	rs2_rxc(void);
extern	int	rs2_get(void);
extern	void	rs2_clear(void);
extern	void	rs2_put(int);
extern	void	rs2_send(char *, int);
extern	int	rs2_sendreq(char *, int);
extern	void	rs2_swait(void);
extern	void	rs2_flow(int);
extern	void	rs2_pad(char *, int, char *, int);
extern	void	rs2_rcvint(int (*)(int));
extern	void	rs2_ehm(void);
extern	int	rs2_dsr(void);
extern	int	rs2_cd(void);
extern	int	rs2_cts(void);
extern	int	rs2_ci(void);
extern	void	rs2_dtr(int);
extern	void	rs2_rts(int);
extern	void	rs2_break(int);
extern	void	rs2_cmd(int ,int);
extern	int	rs2_sts(void);

/*	rs3.c	*/
extern	int	rs3_open(RSPRM *);
extern	int	rs3_reopen(RSPRM *);
extern	void	rs3_close(void);
extern	int	rs3_rxc(void);
extern	int	rs3_get(void);
extern	void	rs3_clear(void);
extern	void	rs3_put(int);
extern	void	rs3_send(char *, int);
extern	int	rs3_sendreq(char *, int);
extern	void	rs3_swait(void);
extern	void	rs3_flow(int);
extern	void	rs3_pad(char *, int, char *, int);
extern	void	rs3_rcvint(int (*)(int));
extern	void	rs3_ehm(void);
extern	int	rs3_dsr(void);
extern	int	rs3_cd(void);
extern	int	rs3_cts(void);
extern	int	rs3_ci(void);
extern	void	rs3_dtr(int);
extern	void	rs3_rts(int);
extern	void	rs3_break(int);
extern	void	rs3_cmd(int ,int);
extern	int	rs3_sts(void);

/*	timer.c	*/
extern	int	tm_open(void);
extern	int	tm_set(int, int);
extern	int	tm_check(int);
extern	void	tm_stop(int);
extern	void	tm_close(void);
extern	long	tm_counter;

/*	�R�}���h���W�X�^	*/
extern	u_char	rs1_cmdreg;		/* �b�g�P */
extern	u_char	rs2_cmdreg;		/* �b�g�Q */
extern	u_char	rs3_cmdreg;		/* �b�g�R */

/*	�l�r�|�b�̊֐����s�t�q�a�n�|�b�̊֐��ɒu�������� */
#ifdef	__TURBOC__
#define	_dos_getvect(x)		getvect(x)
#define	_dos_setvect(x,y)	setvect(x,y)
#ifndef	_enable
#define	_enable()		enable()
#endif
#ifndef	_disable
#define	_disable()		disable()
#endif
#endif