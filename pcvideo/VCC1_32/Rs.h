/************************************************************************
 *
 *		����		: RS232C�@Win32�@���ʊ֐��w�b�_�t�@�C��
 *		�t�@�C����	: rs.h
 *
 *		1998/11/19		M.HAMA
 *
 ***********************************************************************/

#ifdef __cplusplus
extern "C" {
#endif

/*	�`���l���ԍ�	*/
#define	COM1		0		/* �`���l���P */
#define	COM2		1		/* �`���l���Q */
#define	COM3		2		/* �`���l���R */

/*	�S�^����d���[�h	*/
#define	DUPLEX_FULL	0		/* �S��d */
#define	DUPLEX_HALF	1		/* ����d */

/*	�q�r�|�Q�R�Q�b�I�[�v���p�����[�^	*/
typedef struct	rsprm	{
	int	comno;		/* �b�n�l�ԍ� */
	int	duplex;		/* ��d���[�h */
	int	blt;		/* �{�[���[�g */
	int	parity;		/* �p���e�B�[ */
	int	chr_len;	/* �L�����N�^�� */
	int	stp_len;	/* �X�g�b�v�r�b�g�� */
	int	buf_size;	/* ��M�o�b�t�@�T�C�Y */
} RSPRM, FAR * LPRSPRM;

/*	rs.c		*/
extern	int	RsOpen(LPSTR);
extern	VOID RsClose(VOID);
extern	DWORD	RsGet(VOID);
extern	BOOL RsPut(int);
extern	BOOL RsSend(LPSTR, int);
extern	VOID RsDtr(int);
extern	VOID RsRts(int);
extern	int RsDsr(VOID);
extern	int RsCd(VOID);
extern	int RsCts(VOID);
extern	VOID TmSet(int);
extern	BOOL TmCheck(VOID);
extern	VOID TmWait(int);

#ifdef __cplusplus
}
#endif
