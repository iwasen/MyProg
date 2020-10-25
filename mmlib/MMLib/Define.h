#pragma once

#define CACHEMODE_DIRECT	0
#define	CACHEMODE_CACHE		1
#define CACHEMODE_OFFLINE	2

#define ITEM_NONE		0x00
#define ITEM_ROOT		0x01
#define ITEM_INBOX		0x02
#define ITEM_FOLDER		0x04
#define ITEM_DATA		0x08
#define ITEM_PENDING	0x10
#define ITEM_FIND		0x20

#define IMAGE_ROOT			0
#define IMAGE_LIBRARY		1
#define IMAGE_PENDING		2
#define IMAGE_INBOX			3
#define IMAGE_INBOX_OPEN	4
#define IMAGE_GFOLDER		5
#define IMAGE_GFOLDER_OPEN	6
#define IMAGE_DFOLDER		7
#define IMAGE_DFOLDER_OPEN	8
#define IMAGE_FFOLDER		9
#define IMAGE_FFOLDER_OPEN	10
#define IMAGE_FDATA			11
#define IMAGE_FDATA_OPEN	12
#define IMAGE_DATA			13

#define ADDITEM_LIBRARY		1
#define ADDITEM_FOLDER		2
#define ADDITEM_DATA		3
#define ADDITEM_PENDING		4
#define ADDITEM_FIND		5

#define	FN_FOLDER_DBF	"FOLDER3.DBF"		// �t�H���_�Ǘ� data
#define FN_FOLDER_BIN	"FOLDER3.BIN"		// �t�H���_�Ǘ��o�C�i���t�@�C��
#define	FN_FOLDER_NDX1	"FOLDER1.EDX"		// �t�H���_�Ǘ� index
#define	FN_FOLDER_NDX2	"FOLDER2.EDX"		// �t�H���_�Ǘ� index

#define	FN_DATA_DBF		"MMDATA2.DBF"		// �f�[�^�t�@�C���Ǘ� data
#define	FN_DATA_BIN		"MMDATA2.BIN"		// �f�[�^�o�C�i���t�@�C��
#define	FN_DATA_NDX1	"MMDATA1.EDX"		// �f�[�^�t�@�C���Ǘ� index
#define	FN_DATA_NDX2	"MMDATA2.EDX"		// �f�[�^�t�@�C���Ǘ� index

#define	FN_FIND_DBF		"FIND1.DBF"			// �����t�H���_�Ǘ� data
#define	FN_FIND_NDX		"FIND1.EDX"			// �����t�H���_�Ǘ� index

#define FN_KEYWORD_DBF	"KEYWORD.DBF"		// �����p�L�[���[�h

#define FTYPE_LIBRARY	'L'
#define FTYPE_GFOLDER	'G'
#define FTYPE_DFOLDER	'D'

#define FIND_TYPE_FOLDER	'F'
#define FIND_TYPE_DATA		'D'
#define FIND_TYPE_SIMPLE	'S'

#define FID_LIBRARY		"F0000000"

#define LEN_CREATOR		20
#define	LEN_ID			8
#define	LEN_FOLDERNAME	40
#define LEN_FOLDERTYPE	1
#define	LEN_RECT		4
#define	LEN_ICON		1
#define	LEN_DATE		12
#define	LEN_CODE		20
#define	LEN_TITLE		40
#define	LEN_REMARK		60
#define	LEN_KEYWORD		100
#define LEN_TEXT		100
#define LEN_DPI			4
#define LEN_WIDTH		5
#define LEN_HEIGHT		5
#define LEN_BIT			2
#define LEN_UCOUNT		6
#define LEN_MATCHING	8
#define LEN_FINDTYPE	1
#define LEN_CODESTEP	8
#define LEN_PASSWORD	8
#define LEN_PERMISSION	1

#define FORM_FIELD	"FORM"
#define PICT1_FIELD	"PICT1"
#define PICT2_FIELD	"PICT2"
#define TEXT_FIELD	"TEXT"
#define SOUND_FIELD "SOUND"
#define MOVIE_FIELD "MOVIE"

#define VIEWHINT_ADDITEM		1
#define VIEWHINT_SELECTITEM		2
#define VIEWHINT_OPENITEM		3
#define VIEWHINT_CLOSEITEM		4
#define VIEWHINT_REMOVEITEM		5
#define VIEWHINT_UPDATEITEM		6
#define VIEWHINT_RELOADITEM		7
#define VIEWHINT_REFINDITEM		8
#define VIEWHINT_DRAWALL		9
#define VIEWHINT_SETFONT		10
#define VIEWHINT_UPDATEWINDOW	11
#define VIEWHINT_SELECTALL		12
#define VIEWHINT_DRAWLIST		13
#define VIEWHINT_ENLARGE		14
#define VIEWHINT_REDUCE			15
#define VIEWHINT_RROTATION		16
#define VIEWHINT_LROTATION		17
#define VIEWHINT_DRAWPICT		18
#define VIEWHINT_SETPICTRATIO	19
#define VIEWHINT_SETSMALLSIZE	20
#define VIEWHINT_PICTFRAME		21
#define VIEWHINT_SORTITEM		22
#define VIEWHINT_SETTEXT		23
#define VIEWHINT_TEXTFILE		24
#define VIEWHINT_MODIFYDATA		25
#define VIEWHINT_SAVEDATA		26
#define VIEWHINT_SELECTDATA		27
#define VIEWHINT_EDITPICT		28

#define VIEWMODE_PICT		0
#define VIEWMODE_LIST		1
#define VIEWMODE_DETAIL		2

#define WM_GETSELECTEDITEM	(WM_USER + 100)
#define WM_UPDATEPICT		(WM_USER + 101)
#define WM_MYCOMMAND		(WM_USER + 2000)
#define WM_BUTTON_DOWN		(WM_USER + 2001)
#define WM_CHECK_LICENSE1	(WM_USER + 1005)
#define WM_CHECK_LICENSE2	(WM_USER + 1006)
#define WM_REGIST_LICENSE	(WM_USER + 1007)

#define PICT_WIDTH			80
#define PICT_HEIGHT			60
#define PICT_FRAME			5
#define PICT_TEXT			30
#define PICT_MARGIN			20

#define SMALL_NORMAL		0
#define SMALL_HISPD			1
#define SMALL_HIRES			2

#define PHOTO_NULL			0
#define PHOTO_CAMERA		1
#define	PHOTO_SCANNER		2
#define	PHOTO_FILE			3
#define PHOTO_CLIPBOARD		4
#define PHOTO_WINDOW		5

#define DETAIL_NAME			0
#define DETAIL_CODE			1
#define DETAIL_CDATE		2
#define DETAIL_UDATE		3
#define DETAIL_CREATOR		4
#define DETAIL_REMARK		5
#define DETAIL_KEYWORD		6
#define DETAIL_SERVER		7
#define DETAIL_DIRECTORY	8
#define DETAIL_FIRSTDATA	9
#define DETAIL_NUM			10

#define DETAIL_NONE			-1
#define DETAIL_LIBRARY		0
#define DETAIL_FOLDER		1
#define DETAIL_DATA			2

#define AUTOSIZE_NONE		0
#define AUTOSIZE_WIDTH		1
#define AUTOSIZE_HEIGHT		2
#define AUTOSIZE_BOTH		3

#define MAXUSERNAME	64

#define MAX_RECENT	10

#define N_FIND_CONDITIONS	5

#define DIALOGFONT_NORMAL	0
#define DIALOGFONT_LARGE	1

#define SRCFILETYPE_MML		0x01
#define SRCFILETYPE_PICT	0x02

#define TEXTMODE_TITLE_CODE	0
#define TEXTMODE_TITLE_ONLY	1

#define PERMISSION_READ		0x01
#define PERMISSION_ADD		0x02
#define PERMISSION_UPDATE	0x04
#define PERMISSION_DELETE	0x08
#define PERMISSION_FULL		0x0f

#define PICTFILE_BMP		0x01
#define PICTFILE_GIF		0x02
#define PICTFILE_JPG		0x04
#define PICTFILE_FAX		0x08
#define PICTFILE_PNG		0x10
#define PICTFILE_ALL		0xff
#define PICTFILE_TRANSDEF	(PICTFILE_BMP | PICTFILE_JPG)
#define PICTFILE_DEFAULT	(PICTFILE_BMP | PICTFILE_PNG | PICTFILE_JPG)

#define PROGRAM_ERROR		{CString msg; msg.Format("Program Error. File=%s Line=%d", __FILE__, __LINE__); AfxMessageBox(msg);}

#define PRODUCT_CODE	"MMLIB"

#pragma pack(4)

struct FOLDERBUF {
	char	folderID[LEN_ID];			// �t�H���_�h�c
	char	parentFolderID[LEN_ID];		// �e�t�H���_�h�c
	char	folderType[LEN_FOLDERTYPE];	// �t�H���_�^�C�v
	char	folderName[LEN_FOLDERNAME];	// �t�H���_��
	char	createDate[LEN_DATE];		// �t�H���_�쐬�N��������
	char	updateDate[LEN_DATE];		// �t�H���_�X�V�N��������
	char	creator[LEN_CREATOR];		// �쐬��
	char	remark[LEN_REMARK];			// ���l
	char	keyword[LEN_KEYWORD];		// �L�[���[�h
	char	updateCount[LEN_UCOUNT];	// �X�V�J�E���^
	char	codeDef[LEN_CODE];			// �f�t�H���g�f�[�^�R�[�h
	char	codeStep[LEN_CODESTEP];		// �R�[�h�X�V�X�e�b�v
};

struct FOLDERREC {
	CString	folderID;			// �t�H���_�h�c
	CString	parentFolderID;		// �e�t�H���_�h�c
	CString	folderType;			// �t�H���_�^�C�v
	CString	folderName;			// �t�H���_��
	CString	createDate;			// �t�H���_�쐬�N��������
	CString	updateDate;			// �t�H���_�X�V�N��������
	CString	creator;			// �쐬��
	CString	remark;				// ���l
	CString	keyword;			// �L�[���[�h
	long updateCount;			// �X�V�J�E���^
	CString	codeDef;			// �f�t�H���g�f�[�^�R�[�h
	long codeStep;				// �R�[�h�X�V�X�e�b�v
};

struct DATABUF {
	char	folderID[LEN_ID];			// �t�H���_�h�c
	char	dataID[LEN_ID];				// �摜�h�c
	char	createDate[LEN_DATE];		// �B�e�N��������
	char	updateDate[LEN_DATE];		// �X�V�N��������
	char	creator[LEN_CREATOR];		// �쐬��
	char	code[LEN_CODE];				// �R�[�h
	char	title[LEN_TITLE];			// �摜�^�C�g��
	char	remark[LEN_REMARK];			// ���l
	char	keyword[LEN_KEYWORD];		// �L�[���[�h
	char	dpi[LEN_DPI];				// DPI
	char	width[LEN_WIDTH];			// �摜�̕�
	char	height[LEN_HEIGHT];			// �摜�̍���
	char	bit[LEN_BIT];				// �摜�̃r�b�g��
	char	updateCount[LEN_UCOUNT];	// �X�V�J�E���^
};

struct DATAREC {
	CString	folderID;			// �t�H���_�h�c
	CString	dataID;				// �摜�h�c
	CString	createDate;			// �B�e�N��������
	CString	updateDate;			// �X�V�N��������
	CString	creator;			// �쐬��
	CString	code;				// �R�[�h
	CString	title;				// �摜�^�C�g��
	CString	remark;				// ���l
	CString	keyword;			// �L�[���[�h
	long dpi;					// DPI
	long width;					// �摜�̕�
	long height;				// �摜�̍���
	long bit;					// �摜�̃r�b�g��
	long updateCount;			// �X�V�J�E���^
};

struct FINDBUF {
	char	findID[LEN_ID];				// �����f�[�^�h�c
	char	libraryID[LEN_ID];			// �����Ώۃ��C�u�����h�c
	char	findType[LEN_FINDTYPE];		// �������
	char	folderName[LEN_TITLE];		// �t�H���_����
	struct {
		char	createDateFrom[LEN_DATE];	// �B�e�N��������From
		char	createDateTo[LEN_DATE];		// �B�e�N��������To
		char	createDateBefore[LEN_DATE];	// �B�e�N��������Before
		char	updateDateFrom[LEN_DATE];	// �X�V�N��������From
		char	updateDateTo[LEN_DATE];		// �X�V�N��������To
		char	updateDateBefore[LEN_DATE];	// �X�V�N��������Before
		char	creator[LEN_CREATOR];		// �쐬��
		char	code[LEN_CODE];				// �R�[�h
		char	title[LEN_TITLE];			// �摜�^�C�g��
		char	remark[LEN_REMARK];			// ���l
		char	keyword[LEN_KEYWORD];		// �L�[���[�h
		char	text[LEN_TEXT];				// �e�L�X�g�f�[�^
		char	matching[LEN_MATCHING];		// �}�b�`���O�I�v�V����
	} conditions[N_FIND_CONDITIONS];
};

struct FIND_CONDITION {
	CString	createDateFrom;				// �B�e�N��������From
	CString	createDateTo;				// �B�e�N��������To
	CString	createDateBefore;			// �B�e�N��������Before
	CString	updateDateFrom;				// �X�V�N��������From
	CString	updateDateTo;				// �X�V�N��������To
	CString	updateDateBefore;			// �X�V�N��������Before
	CString	creator;					// �쐬��
	CString	code;						// �R�[�h
	CString	title;						// �摜�^�C�g��
	CString	remark;						// ���l
	CString	keyword;					// �L�[���[�h
	CString	text;						// �e�L�X�g�f�[�^
	CString matching;					// �}�b�`���O�I�v�V����
};

struct FINDREC {
	CString findID;						// �����f�[�^�h�c
	CString	libraryID;					// �����Ώۃ��C�u�����h�c
	CString	findType;					// �������
	CString	folderName;					// �t�H���_����
	FIND_CONDITION conditions[N_FIND_CONDITIONS];	// ��������
};

struct KEYWORDBUF {
	char	libraryID[LEN_ID];
	char	libraryName[LEN_FOLDERNAME];
	char	idType[1];
	char	id[LEN_ID];
	char	folderType[LEN_FOLDERTYPE];
	char	title[LEN_TITLE];
	char	creator[LEN_CREATOR];
	char	createDate[LEN_DATE];
	char	remark[LEN_REMARK];
	char	keyword[LEN_KEYWORD];
//	char	text[LEN_TEXT];
};

struct KEYWORDREC {
	CString	libraryID;
	CString libraryName;
	CString	idType;
	CString	id;
	CString	folderType;
	CString	title;
	CString creator;
	CString	createDate;
	CString	remark;
	CString	keyword;
	CString	text;
};

#pragma pack()
