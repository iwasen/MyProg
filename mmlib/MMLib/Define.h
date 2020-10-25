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

#define	FN_FOLDER_DBF	"FOLDER3.DBF"		// フォルダ管理 data
#define FN_FOLDER_BIN	"FOLDER3.BIN"		// フォルダ管理バイナリファイル
#define	FN_FOLDER_NDX1	"FOLDER1.EDX"		// フォルダ管理 index
#define	FN_FOLDER_NDX2	"FOLDER2.EDX"		// フォルダ管理 index

#define	FN_DATA_DBF		"MMDATA2.DBF"		// データファイル管理 data
#define	FN_DATA_BIN		"MMDATA2.BIN"		// データバイナリファイル
#define	FN_DATA_NDX1	"MMDATA1.EDX"		// データファイル管理 index
#define	FN_DATA_NDX2	"MMDATA2.EDX"		// データファイル管理 index

#define	FN_FIND_DBF		"FIND1.DBF"			// 検索フォルダ管理 data
#define	FN_FIND_NDX		"FIND1.EDX"			// 検索フォルダ管理 index

#define FN_KEYWORD_DBF	"KEYWORD.DBF"		// 検索用キーワード

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
	char	folderID[LEN_ID];			// フォルダＩＤ
	char	parentFolderID[LEN_ID];		// 親フォルダＩＤ
	char	folderType[LEN_FOLDERTYPE];	// フォルダタイプ
	char	folderName[LEN_FOLDERNAME];	// フォルダ名
	char	createDate[LEN_DATE];		// フォルダ作成年月日時分
	char	updateDate[LEN_DATE];		// フォルダ更新年月日時分
	char	creator[LEN_CREATOR];		// 作成者
	char	remark[LEN_REMARK];			// 備考
	char	keyword[LEN_KEYWORD];		// キーワード
	char	updateCount[LEN_UCOUNT];	// 更新カウンタ
	char	codeDef[LEN_CODE];			// デフォルトデータコード
	char	codeStep[LEN_CODESTEP];		// コード更新ステップ
};

struct FOLDERREC {
	CString	folderID;			// フォルダＩＤ
	CString	parentFolderID;		// 親フォルダＩＤ
	CString	folderType;			// フォルダタイプ
	CString	folderName;			// フォルダ名
	CString	createDate;			// フォルダ作成年月日時分
	CString	updateDate;			// フォルダ更新年月日時分
	CString	creator;			// 作成者
	CString	remark;				// 備考
	CString	keyword;			// キーワード
	long updateCount;			// 更新カウンタ
	CString	codeDef;			// デフォルトデータコード
	long codeStep;				// コード更新ステップ
};

struct DATABUF {
	char	folderID[LEN_ID];			// フォルダＩＤ
	char	dataID[LEN_ID];				// 画像ＩＤ
	char	createDate[LEN_DATE];		// 撮影年月日時分
	char	updateDate[LEN_DATE];		// 更新年月日時分
	char	creator[LEN_CREATOR];		// 作成者
	char	code[LEN_CODE];				// コード
	char	title[LEN_TITLE];			// 画像タイトル
	char	remark[LEN_REMARK];			// 備考
	char	keyword[LEN_KEYWORD];		// キーワード
	char	dpi[LEN_DPI];				// DPI
	char	width[LEN_WIDTH];			// 画像の幅
	char	height[LEN_HEIGHT];			// 画像の高さ
	char	bit[LEN_BIT];				// 画像のビット数
	char	updateCount[LEN_UCOUNT];	// 更新カウンタ
};

struct DATAREC {
	CString	folderID;			// フォルダＩＤ
	CString	dataID;				// 画像ＩＤ
	CString	createDate;			// 撮影年月日時分
	CString	updateDate;			// 更新年月日時分
	CString	creator;			// 作成者
	CString	code;				// コード
	CString	title;				// 画像タイトル
	CString	remark;				// 備考
	CString	keyword;			// キーワード
	long dpi;					// DPI
	long width;					// 画像の幅
	long height;				// 画像の高さ
	long bit;					// 画像のビット数
	long updateCount;			// 更新カウンタ
};

struct FINDBUF {
	char	findID[LEN_ID];				// 検索データＩＤ
	char	libraryID[LEN_ID];			// 検索対象ライブラリＩＤ
	char	findType[LEN_FINDTYPE];		// 検索種別
	char	folderName[LEN_TITLE];		// フォルダ名称
	struct {
		char	createDateFrom[LEN_DATE];	// 撮影年月日時分From
		char	createDateTo[LEN_DATE];		// 撮影年月日時分To
		char	createDateBefore[LEN_DATE];	// 撮影年月日時分Before
		char	updateDateFrom[LEN_DATE];	// 更新年月日時分From
		char	updateDateTo[LEN_DATE];		// 更新年月日時分To
		char	updateDateBefore[LEN_DATE];	// 更新年月日時分Before
		char	creator[LEN_CREATOR];		// 作成者
		char	code[LEN_CODE];				// コード
		char	title[LEN_TITLE];			// 画像タイトル
		char	remark[LEN_REMARK];			// 備考
		char	keyword[LEN_KEYWORD];		// キーワード
		char	text[LEN_TEXT];				// テキストデータ
		char	matching[LEN_MATCHING];		// マッチングオプション
	} conditions[N_FIND_CONDITIONS];
};

struct FIND_CONDITION {
	CString	createDateFrom;				// 撮影年月日時分From
	CString	createDateTo;				// 撮影年月日時分To
	CString	createDateBefore;			// 撮影年月日時分Before
	CString	updateDateFrom;				// 更新年月日時分From
	CString	updateDateTo;				// 更新年月日時分To
	CString	updateDateBefore;			// 更新年月日時分Before
	CString	creator;					// 作成者
	CString	code;						// コード
	CString	title;						// 画像タイトル
	CString	remark;						// 備考
	CString	keyword;					// キーワード
	CString	text;						// テキストデータ
	CString matching;					// マッチングオプション
};

struct FINDREC {
	CString findID;						// 検索データＩＤ
	CString	libraryID;					// 検索対象ライブラリＩＤ
	CString	findType;					// 検索種別
	CString	folderName;					// フォルダ名称
	FIND_CONDITION conditions[N_FIND_CONDITIONS];	// 検索条件
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
