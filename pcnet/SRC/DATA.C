/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: �O���ϐ�				*
 *		�t�@�C����	: data.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"

char	systemName[] = "�o�b�|�m�d�s�^�Q";	/* �V�X�e���� */
char	version[] = "Version 1.00A";		/* �o�[�W�����m���D */

/*	�E�B���h�E�N���X��	*/
CHAR	szMainClassName[] = "MAIN_WINDOW";
CHAR	szMonitorClassName[] = "MONITOR_WINDOW";
CHAR	szUserClassName[] = "USER_WINDOW";
CHAR	szBBSClassName[] = "BBS_WINDOW";
CHAR	szMailClassName[] = "MAIL_WINDOW";
CHAR	szTerminalClassName[] = "TERMINAL_WINDOW";

HAB	hab;			/* �A���J�[�u���b�N�n���h�� */
HWND	hwndMain;		/* ���C���E�B���h�E�n���h�� */
HWND	hwndUser;		/* ���[�U�Ǘ��E�B���h�E�n���h�� */
HWND	hwndBBS;		/* �f���Ǘ��E�B���h�E�n���h�� */
HWND	hwndMail;		/* ���[���Ǘ��E�B���h�E�n���h�� */
HWND	clientMonitor[N_CHANNEL];	/* ���j�^�[�E�B���h�E�n���h�� */
HWND	clientTerminal[N_CHANNEL];	/* �^�[�~�i���E�B���h�E�n���h�� */
SAVEDATA	sv = {		/* ���ݒ�f�[�^ */
	{
		{PORT_LOCAL,  0, 0, 0, 0},
		{PORT_MODEM,  4, 1, 0, 0},
		{PORT_NOTUSE, 4, 1, 0, 0},
		{PORT_NOTUSE, 4, 1, 0, 0}
	},
	{
		{"", 0, "", "", 0, "", 0, "", 0},
		{"ATZ", 'R', "RING", "ATA", 'D', "+++", 2, "ATH", 3},
		{"ATZ", 'R', "RING", "ATA", 'D', "+++", 2, "ATH", 3},
		{"ATZ", 'R', "RING", "ATA", 'D', "+++", 2, "ATH", 3}
	},
	{
		"SYS00000",
		"E.EXE",
		"",
		3,
		"",
		'*',
		'*'
	}
};
char	*portName[N_CHANNEL] = {"", "com1", "com2", "com3"};	/* �|�[�g�� */
BOOL	portOpenFlag[N_CHANNEL];	/* �|�[�g�I�[�v���t���O */
HFILE	portHndl[N_CHANNEL];		/* �|�[�g�n���h�� */
HFILE	ch0Hndl;			/* �`���l���O�������݃n���h�� */
USHORT	threadID[N_CHANNEL];		/* �X���b�h�h�c */
BOOL	monitorFlag[N_CHANNEL];		/* ���j�^�[���t���O */
USHORT	terminalMode[N_CHANNEL];	/* �^�[�~�i�����[�h */
FILE	*termXmodemFP[N_CHANNEL];	/* �w�l�n�c�d�l����M�t�@�C���|�C���^*/
BOOL	closeRequest;			/* �S�`���l���N���[�Y�v���t���O */
USHORT	receiveTimer[N_CHANNEL];	/* �������Ď��^�C�}�[�l */
BOOL	conxxxtFlag[N_CHANNEL];		/* �ڑ����t���O */
FILE	*logFp[N_CHANNEL];		/* �ʐM���e�ۑ��t�@�C���|�C���^ */
SHORT	cxScreen;			/* ��ʂ̉������̑傫�� */
SHORT	cyScreen;			/* ��ʂ̏c�����̑傫�� */
SHORT	gcyChar;			/* �����̍��� */
SHORT	gcxAveChar;			/* �����̕��ϕ� */
SHORT	gcxMaxChar;			/* �����̍ő啝 */
BOOL	echoBackFlag[N_CHANNEL];	/* �G�R�[�o�b�N�t���O */
CHAR	cUserID[N_CHANNEL][9];		/* �J�����g���[�U�h�c */
CHAR	cGroup[N_CHANNEL][9];		/* �J�����g���[�U�O���[�v */
CHAR	cUserLevel[N_CHANNEL];		/* �J�����g�����x�� */

/*	�f�[�^�t�@�C��	*/
CHAR	fnEnvData[] = "ENV.DAT";	/* ���ݒ�t�@�C���� */
CHAR	fnOperation[] = "*.OPR";	/* �����`�t�@�C�� */

/*	���b�Z�[�W�t�@�C��	*/
CHAR	fnConxxxtMsg[] = "CONxxxT.MSG";	/* �ڑ������b�Z�[�W */
CHAR	fnLoginMsg[] = "LOGIN.MSG";	/* ���O�C�����b�Z�[�W */

/*	�ʐM�����t�@�C��	*/
CHAR	dnLOGFILE[] = "LOGFILE";	/* �f�B���N�g���� */
CHAR	fnLOGDBF[] = "LOG.DBF";		/* �f�[�^�t�@�C���� */
CHAR	fnLOGNDX[] = "LOG.NDX";		/* �C���f�b�N�X�t�@�C���� */
DB	*logDBp;			/* �f�[�^�x�[�X�Ǘ��|�C���^ */

/*	���[�U�Ǘ��t�@�C��	*/
CHAR	fnUSERDBF[] = "USER.DBF";	/* �f�[�^�t�@�C���� */
CHAR	fnUSERIDNDX[] = "USER_U.NDX";	/* ���[�U�h�c�p�C���f�b�N�X�t�@�C����*/
CHAR	fnFURIGANANDX[] = "USER_F.NDX"; /* �ӂ肪�ȗp�C���f�b�N�X�t�@�C����*/
SHORT	indexUserID;			/* ���[�U�h�c�p�C���f�b�N�X�ԍ� */
SHORT	indexFurigana;			/* �ӂ肪�ȗp�C���f�b�N�X�ԍ� */
DB	*userDBp;			/* �f�[�^�x�[�X�Ǘ��|�C���^ */

/*	�̔����t�@�C��	*/
CHAR	fnSALESDBF[] = "SALES.DBF";	/* �f�[�^�t�@�C���� */
CHAR	fnSALESNDX[] = "SALES.NDX";	/* �C���f�b�N�X�t�@�C���� */
SHORT	indexSales;			/* �C���f�b�N�X�ԍ� */
DB	*salesDBp;			/* �f�[�^�x�[�X�Ǘ��|�C���^ */

/*	�f���Ǘ��t�@�C��	*/
CHAR	dnBBS[] = "BBS";		/* �f�B���N�g���� */
CHAR	fnBBSMDBF[] = "BBSM.DBF";	/* �f�[�^�t�@�C���� */
CHAR	fnBBSMNDX[] = "BBSM.NDX";	/* �C���f�b�N�X�t�@�C���� */
SHORT	indexBBSM;			/* �C���f�b�N�X�ԍ� */
DB	*BBSMDBp;			/* �f�[�^�x�[�X�Ǘ��|�C���^ */

/*	�f�����Ǘ��t�@�C��	*/
CHAR	fnBBSDBF[] = "BBS.DBF";		/* �f�[�^�t�@�C���� */
CHAR	fnBBSNDX[] = "BBS.NDX";		/* �C���f�b�N�X�t�@�C���� */

/*	���[���Ǘ��t�@�C��	*/
CHAR	dnMAIL[] = "MAIL";		/* �f�B���N�g���� */
CHAR	fnMAILDBF[] = "MAIL.DBF";	/* �f�[�^�t�@�C���� */
CHAR	fnMAILANDX[] = "MAIL_A.NDX";	/* �S���[���C���f�b�N�X�t�@�C���� */
CHAR	fnMAILS1NDX[] = "MAIL_S1.NDX";	/* ���M���[�U�h�c���ޯ��̧�ٖ� */
CHAR	fnMAILR1NDX[] = "MAIL_R1.NDX";	/* ��M���[�U�h�c���ޯ��̧�ٖ� */
CHAR	fnMAILS2NDX[] = "MAIL_S2.NDX";	/* ���M���[�U�h�c���ޯ��̧�ٖ� */
CHAR	fnMAILR2NDX[] = "MAIL_R2.NDX";	/* ��M���[�U�h�c���ޯ��̧�ٖ� */
SHORT	indexMailA;			/* �S���[���C���f�b�N�X�ԍ� */
SHORT	indexMailS1;			/* ���M���[�U�h�c�C���f�b�N�X�ԍ��P */
SHORT	indexMailR1;			/* ��M���[�U�h�c�C���f�b�N�X�ԍ��P */
SHORT	indexMailS2;			/* ���M���[�U�h�c�C���f�b�N�X�ԍ��Q */
SHORT	indexMailR2;			/* ��M���[�U�h�c�C���f�b�N�X�ԍ��Q */
DB	*mailDBp;			/* �f�[�^�x�[�X�Ǘ��|�C���^ */

/*	�V�X�e���ُ�I���`�F�b�N�t�@�C��	*/
CHAR	fnFCHECK[] = "PCNET.CHK";