/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: �\���̒�`				*
 *		�t�@�C����	: struct.h				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

/*	�ʐM�p�����[�^	*/
typedef	struct	{
	SHORT	conxxxt;	/* �ڑ���� */
	SHORT	baudRate;	/* �{�[���[�g */
	SHORT	chDataBits;	/* �L�����N�^�r�b�g�� */
	SHORT	chStopBits;	/* �X�g�b�v�r�b�g�� */
	SHORT	chParity;	/* �p���e�B�[ */
} COMPARAM;

/*	���f�����	*/
typedef	struct	{
	CHAR	initCmd[21];	/* ���f���������R�}���h */
	CHAR	detectCall;	/* ���M���o���@ */
	CHAR	ringMsg[5];	/* ���M���b�Z�[�W */
	CHAR	conxxxtCmd[11];	/* ���M�R�}���h */
	CHAR	disconxxxt;	/* ����ؒf���@ */
	CHAR	escCode[5];	/* �G�X�P�[�v�R�[�h */
	SHORT	guardTime;	/* �G�X�P�[�v�K�[�h�^�C�� */
	CHAR	discCmd[5];	/* ����ؒf�R�}���h */
	SHORT	dtrOffTime;	/* ����ؒf���̂c�s�q�I�t���� */
} MODEM;

/*	���ݒ�f�[�^	*/
typedef	struct	{
	CHAR	systemID[9];	/* �V�X�e���h�c */
	CHAR	editor[33];	/* �G�f�B�^�t�@�C���� */
	CHAR	edOption[17];	/* �G�f�B�^�I�v�V���� */
	SHORT	timer;		/* �������ؒf���� */
	CHAR	passWord[9];	/* �V�X�e���Ǘ��҃p�X���[�h */
	CHAR	mailFlag;	/* ���[�������ʒm�L���t���O */
	CHAR	logSave;	/* �ʐM���e�ۑ��L���t���O */
} ENVDATA;

/*	���ݒ�t�@�C��	*/
typedef	struct	{
	COMPARAM	comParam[N_CHANNEL];	/* �ʐM�p�����[�^ */
	MODEM		modem[N_CHANNEL];	/* ���f����� */
	ENVDATA		env;			/* ���ݒ�f�[�^ */
} SAVEDATA;

/*	���O�C���ʒm���	*/
typedef	struct	{
	USHORT	chNo;		/* �`���l���ԍ� */
	CHAR	userID[9];	/* ���[�U�h�c */
	struct	{
		USHORT	year;
		CHAR	month;
		CHAR	day;
	} loginDate;		/* ���O�C�����t */
	struct	{
		CHAR	hour;
		CHAR	minutes;
		CHAR	seconds;
	} loginTime;		/* ���O�C������ */
	CHAR	logFile[13];	/* �ʐM���e�ۑ��t�@�C���� */
} LOGIN;

/*	���O�A�E�g�ʒm���	*/
typedef	struct	{
	USHORT	chNo;		/* �`���l���ԍ� */
	struct	{
		CHAR	hour;
		CHAR	minutes;
		CHAR	seconds;
	} logoutTime;		/* ���O�A�E�g���� */
	ULONG	usedTime;	/* �g�p���� */
} LOGOUT;

/*	�ʐM�����t�@�C��	*/
typedef	struct	{
	CHAR	chNo[1];	/* �`���l���ԍ� */
	CHAR	userID[8];	/* ���[�U�h�c */
	CHAR	loginDate[8];	/* ���O�C�����t */
	CHAR	loginTime[8];	/* ���O�C������ */
	CHAR	logoutTime[8];	/* ���O�A�E�g���� */
	CHAR	usedTime[8];	/* �g�p���� */
	CHAR	fileName[12];	/* �ʐM���e�ۑ��t�@�C���� */
} LOGDATA;

/*	���[�U�Ǘ����	*/
typedef	struct	{
	CHAR	userID[8];	/* ���[�U�h�c */
	CHAR	password[8];	/* �p�X���[�h */
	CHAR	group[8];	/* �O���[�v */
	CHAR	userLevel[1];	/* �����x�� */
	CHAR	furikana[16];	/* �ӂ肪�� */
	CHAR	name[16];	/* ���� */
	CHAR	bikou[40];	/* ���l */
	CHAR	sDate[8];	/* �ڑ��J�n�� */
	CHAR	eDate[8];	/* �ŏI�ڑ��� */
	CHAR	cCount[6];	/* �ڑ��� */
	CHAR	cTime[10];	/* �g�[�^���ʐM���� */
	CHAR	logSave[1];	/* �ʐM���e�ۑ��L���t���O */
	CHAR	zipNo[6];	/* �X�֔ԍ� */
	CHAR	address1[40];	/* �Z���P */
	CHAR	address2[40];	/* �Z���Q */
	CHAR	company[40];	/* ��Ж� */
	CHAR	section[40];	/* ���� */
	CHAR	tel[15];	/* �d�b�ԍ� */
} USERDBF;

/*	�̔����	*/
typedef	struct	{
	CHAR	userID[8];	/* ���[�U�h�c */
	CHAR	sexxxn[40];	/* ���i�� */
	CHAR	serial[16];	/* �V���A���ԍ� */
	CHAR	date[8];	/* �w�����t */
	CHAR	price[12];	/* �w�����i */
	CHAR	shop[40];	/* �w���X */
	CHAR	bikou[40];	/* ���l */
} SALESDBF;

/*	�f���Ǘ����	*/
typedef	struct	{
	CHAR	name[8];	/* �f���� */
	CHAR	title[20];	/* �f���^�C�g�� */
	CHAR	date[17];	/* �o�^���t */
	CHAR	type[1];	/* �f���t�@�C���^�C�v */
	CHAR	listSeq[1];	/* �ꗗ�\������ */
	CHAR	userWrite[1];	/* ���[�U�������݋��t���O */
} BBSMDBF;

/*	�f�����Ǘ����	*/
typedef	struct	{
	CHAR	userID[8];	/* �o�^���[�U�h�c */
	CHAR	name[12];	/* �f���t�@�C���� */
	CHAR	date[17];	/* �o�^���t */
	CHAR	title[40];	/* �f�����^�C�g�� */
	CHAR	userLevel[1];	/* �����x�� */
	CHAR	size[8];	/* �t�@�C���T�C�Y */
	CHAR	fileName[12];	/* �i�[�t�@�C���� */
} BBSDBF;

/*	���[���Ǘ����	*/
typedef	struct	{
	CHAR	sUserID[8];	/* ���M�����[�U�h�c */
	CHAR	rUserID[8];	/* ���M�惆�[�U�h�c */
	CHAR	title[40];	/* �^�C�g�� */
	CHAR	sDate[17];	/* ���M���t */
	CHAR	rDate[17];	/* ��M���t */
	CHAR	sDelete[1];	/* ���M���폜�t���O */
	CHAR	rDelete[1];	/* ��M���폜�t���O */
	CHAR	type[1];	/* ��� */
	CHAR	fileName[12];	/* �t�@�C���� */
} MAILDBF;

/*	�f�[�^���̓e�[�u��	*/
typedef	struct	{
	USHORT	type;		/* �t�B�[���h�^�C�v */
	USHORT	id;		/* �t�B�[���h�h�c */
	USHORT	field;		/* �t�B�[���h�� */
	VOID	*ptr;		/* �o�b�t�@�|�C���^ */
} ENTRY_TBL;

/*	�f�[�^�\���e�[�u��	*/
typedef	struct	{
	USHORT	menuID;		/* ���j���[�h�c */
	BOOL	flag;		/* �\���t���O */
	CHAR	*msg;		/* ���ږ� */
	USHORT	length;		/* �\������ */
	CHAR	*ptr;		/* �o�b�t�@�|�C���^ */
	USHORT	fieldLeng;	/* �t�B�[���h�� */
} DISP_TBL;

/*	�t�@�C�������̓p�����[�^	*/
typedef	struct	{
	CHAR	*title;
	CHAR	*bufp;
	USHORT	bufSize;
} INPUTFN;