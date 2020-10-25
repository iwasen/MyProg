
#define	MENU_SETSEISANJOKEN1_NUM	17

struct MENUCTRLDATA	Menu_SetSeisanJoken1Dlg[MENU_SETSEISANJOKEN1_NUM] = {
{	CTRL_TYPE_TITLE,	                       0,	"D004002", "T001001",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	IDC_STATIC002,				"D004002", "S001002",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	IDC_STATIC_YEAR,			"D004002", "S001003",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	IDC_STATIC_MONTH,			"D004002", "S001004",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	IDC_STATIC_SEISAN_DAISU,	"D004002", "S001005",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	IDC_BUTTON_IKO_JOGEN,		"D004002", "B001006",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	IDC_BUTTON_IKO_KAGEN,		"D004002", "B001007",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	             IDC_LIST001,	"D004002", "V001008",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	             IDC_LIST001,	"D004002", "V001009",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	             IDC_LIST001,	"D004002", "V001010",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	             IDC_LIST001,	"D004002", "V001011",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	             IDC_LIST001,	"D004002", "V001012",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	             IDC_LIST001,	"D004002", "V001013",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,	             IDC_LIST001,	"D004002", "V001014",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	IDC_BUTTON_SETTEI,			"D004002", "B001015",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	IDCANCEL			,		"D004002", "B001016",		NULL,			NULL	},
{	              -1,	                       0,	       "",        "",		NULL,			NULL	}
};

#define LIST_SETSEISANJOKEN1_NUM	8

struct LIxxxTRLDATA List_SetSeisanJoken1Dlg[LIST_SETSEISANJOKEN1_NUM] = {
	{	0,		7,		10,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		     "",	0,	   0,	0		},
	{	1,		8,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_RNUM,			eINPUTTYPE_EDITBOX,		"%6.2f",	0,	   0,	6		},
	{	2,		9,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_RNUM,			eINPUTTYPE_EDITBOX,		"%5.1f",	0,	 100,	5		},
	{	3,		10,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_RNUM,			eINPUTTYPE_NOTEDIT,		"%7.3f",	0,	   0,	0		},
	{	4,		11,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_RNUM,			eINPUTTYPE_EDITBOX,		"%4.1f",	0,	99.9,	4		},
	{	5,		12,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_RNUM,			eINPUTTYPE_EDITBOX,		"%4.1f",	0,	99.9,	4		},
	{	6,		13,		8,	LVCFMT_CENTER,	FALSE,	FALSE,	eITEMTYPE_ONOFFCHECK,	eINPUTTYPE_ONOFFCHECK,	     "",	0,	   0,	0		},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		     "",	0,	   0,	0		}
};
