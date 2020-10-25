
#define	MENU_MANUALMOVEDLG_NUM	19

struct MENUCTRLDATA	Menu_ManualMoveDlg[MENU_MANUALMOVEDLG_NUM] = {
{	CTRL_TYPE_TITLE,	                      0,	"D008002",	"T001001",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	          IDC_STATIC002,	"D008002",	"S001002",		NULL,			NULL	},
{	CTRL_TYPE_RADIO,	        IDC_RADIO_KOTEI,	"D008002",	"R001003",		NULL,			NULL	},
{	CTRL_TYPE_RADIO,	IDC_RADIO_YOSO_SAGYOGUN,	"D008002",	"R001004",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	              IDC_LIST1,	"D008002",	"V001005",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	              IDC_LIST1,	"D008002",	"V001006",		NULL,			NULL	},
// Modify ... ( ADD )
{	CTRL_TYPE_LVIEW,	              IDC_LIST1,	"D008002",	"V001014",		NULL,			NULL	},
// By Y.Itabashi (xxxxx) 2007.02.07
{	CTRL_TYPE_STATIC,	 IDC_STATIC_TOTAL_TIME1,	"D008002",	"S001007",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	          IDC_STATIC008,	"D008002",	"S001002",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	              IDC_LIST2,	"D008002",	"V001008",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	              IDC_LIST2,	"D008002",	"V001009",		NULL,			NULL	},
// Modify ... ( ADD )
{	CTRL_TYPE_LVIEW,	              IDC_LIST2,	"D008002",	"V001015",		NULL,			NULL	},
// By Y.Itabashi (xxxxx) 2007.02.07
{	CTRL_TYPE_STATIC,	 IDC_STATIC_TOTAL_TIME2,	"D008002",	"S001010",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	     IDC_BUTTON_PREVIEW,	"D008002",	"B001011",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	      IDC_BUTTON_SETTEI,	"D008002",	"B001012",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	               IDCANCEL,	"D008002",	"B001013",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	              IDC_LIST1,	"D008002",	"V002001",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	              IDC_LIST1,	"D008002",	"V003001",		NULL,			NULL	},
{	              -1,	                      0,	       "",	       "",		NULL,			NULL	}
};

#define LIST_MANUALMOVEDLGLIST1_NUM 4

struct LIxxxTRLDATA List_ManualMoveDlgList1[LIST_MANUALMOVEDLGLIST1_NUM] = {
	{	0,		4,		25,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",			0,	0,	0	},
	{	1,		5,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_RNUM,			eINPUTTYPE_NOTEDIT,		"%.6f",		0,	0,	0	},
	{	2,		6,		8,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",			0,	0,	0	},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		"",			0,	0,	0	}
};

#define LIST_MANUALMOVEDLGLIST2_NUM 4

struct LIxxxTRLDATA List_ManualMoveDlgList2[LIST_MANUALMOVEDLGLIST2_NUM] = {
	{	0,		9,		25,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",			0,	0,	0	},
	{	1,		10,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_RNUM,			eINPUTTYPE_NOTEDIT,		"%.6f",		0,	0,	0	},
	{	2,		11,		8,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",			0,	0,	0	},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		"",			0,	0,	0	}
};
