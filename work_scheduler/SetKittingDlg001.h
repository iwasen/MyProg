
#define	MENU_SETKITTINGDLG_NUM	29

struct MENUCTRLDATA	Menu_SetKittingDlg[MENU_SETKITTINGDLG_NUM] = {
{	CTRL_TYPE_TITLE,	                                 0,		"D004004",	"T001001",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	                IDC_STATIC_SUBLINE,		"D004004",	"S001002",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	             IDC_BUTTON_ZENSENTAKU,		"D004004",	"B001003",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	               IDC_BUTTON_ZENKAIJO,		"D004004",	"B001004",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_BUHIN,		"D004004",	"V001005",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_BUHIN,		"D004004",	"V001006",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_BUHIN,		"D004004",	"V001007",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_BUHIN,		       "",	       "",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_BUHIN,		       "",	       "",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_BUHIN,		       "",	       "",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_BUHIN,		       "",	       "",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_BUHIN,		       "",	       "",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_KOTEI,		"D004004",	"V001008",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_KOTEI,		"D004004",	"V001009",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	                    IDC_LIST_KOTEI,		"D004004",	"V001010",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	            IDC_LIST_YOSO_SAGYOGUN,		"D004004",	"V001011",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	            IDC_LIST_YOSO_SAGYOGUN,		"D004004",	"V001012",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	            IDC_LIST_YOSO_SAGYOGUN,		"D004004",	"V001013",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	               IDC_LIST_YOSO_SAGYO,		"D004004",	"V001014",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	               IDC_LIST_YOSO_SAGYO,		"D004004",	"V001015",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	IDC_BUTTON_KITTING_TAISHO_BUHINHYO,		"D004004",	"B001016",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	                   IDC_BUTTON_NEXT,		"D004004",	"B001017",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	                   IDC_BUTTON_BACK,		"D004004",	"B001018",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	  IDC_STATIC_KIT_NASHI_GOKEI_JIKAN,		"D004004",	"S001019",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	    IDC_STATIC_KIT_ARI_GOKEI_JIKAN,		"D004004",	"S001020",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	           IDC_STATIC_KIT_SA_JIKAN,		"D004004",	"S001021",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	                              IDOK,		"D004004",	"B001022",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	                          IDCANCEL,		"D004004",	"B001023",		NULL,			NULL	},
{	              -1,	                                 0,		       "",         "",		NULL,			NULL	}
};

#define LIST_SETKITTINGBUHIN_NUM	10

struct LIxxxTRLDATA List_SetKittingBuhin[LIST_SETKITTINGBUHIN_NUM] = {
	{	0,		-1,		3,	LVCFMT_CENTER,	FALSE,	FALSE,	eITEMTYPE_ONOFFCHECK,	eINPUTTYPE_ONOFFCHECK,	"",		0,	0,	0	},
	{	1,		4,		8,	LVCFMT_LEFT,	TRUE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	2,		5,		15,	LVCFMT_LEFT,	TRUE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	3,		6,		20,	LVCFMT_LEFT,	TRUE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	4,		7,		6,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	5,		8,		6,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	6,		9,		6,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	7,		10,		6,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	8,		11,		6,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		"",		0,	0,	0	}
};

#define LIST_SETKITTINGKOTEI_NUM	4

struct LIxxxTRLDATA List_SetKittingKotei[LIST_SETKITTINGKOTEI_NUM] = {
	{	0,		12,		4,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	1,		13,		22,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	2,		14,		8,	LVCFMT_CENTER,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		"",		0,	0,	0	}
};

#define LIST_SETKITTINGYOSOSAGYOUGUN_NUM	4

struct LIxxxTRLDATA List_SetKittingYosoSagyogun[LIST_SETKITTINGYOSOSAGYOUGUN_NUM] = {
	{	0,		15,		4,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	1,		16,		22,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	2,		17,		8,	LVCFMT_CENTER,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		"",		0,	0,	0	}
};

#define LIST_SETKITTINGYOSOSAGYOU_NUM	3

struct LIxxxTRLDATA List_SetKittingYosoSagyo[LIST_SETKITTINGYOSOSAGYOU_NUM] = {
	{	0,		18,		4,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	1,		19,		25,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	0	},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		"",		0,	0,	0	}
};
