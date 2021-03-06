
#define	MENU_MANUALEDITDLG_NUM	40

struct MENUCTRLDATA	Menu_ManualEditDlg[MENU_MANUALEDITDLG_NUM] = {
{	CTRL_TYPE_TITLE,		                              0,	"D008001",	"T001001",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,		                  IDC_STATIC002,	"D008001",	"S001002",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,		                  IDC_STATIC003,	"D008001",	"S001003",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,		                  IDC_STATIC004,	"D008001",	"S001004",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,		                  IDC_STATIC005,	"D008001",	"S001005",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,		        IDC_BUTTON_KOTEI_TSUIKA,	"D008001",	"B001006",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,		        IDC_BUTTON_KOTEI_SAKUJO,	"D008001",	"B001007",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_KOTEI	»ÌÞ×²Ý¼Ì*/		0,	"D008001",	"V001008",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_KOTEI	HöL*/			0,	"D008001",	"V001009",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_KOTEI	HöÔ*/			0,	"D008001",	"V001010",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_KOTEI	Hö¼Ì*/			0,	"D008001",	"V001011",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_KOTEI	©/è*/				0,	"D008001",	"V001012",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,		                  IDC_STATIC013,	"D008001",	"S001013",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,		IDC_BUTTON_YOSO_SAGYOGUN_TSUIKA,	"D008001",	"B001014",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,		IDC_BUTTON_YOSO_SAGYOGUN_SAKUJO,	"D008001",	"B001015",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYOGUN	No*/		0,	"D008001",	"V001016",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYOGUN	vfìÆQ*/0,	"D008001",	"V001017",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,		                  IDC_STATIC018,	"D008001",	"S001018",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,		   IDC_BUTTON_YOSO_SAGYO_TSUIKA,	"D008001",	"B001019",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,		   IDC_BUTTON_YOSO_SAGYO_SAKUJO,	"D008001",	"B001020",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYO	ìÆNo*/		0,	"D008001",	"V001021",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYO	ìÆàe*/		0,	"D008001",	"V001022",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYO	vfìÆNo*/	0,	"D008001",	"V001023",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYO	M*/				0,	"D008001",	"V001025",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYO	N*/				0,	"D008001",	"V001024",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYO	­¶px*/		0,	"D008001",	"V001026",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYO	JÔñ*/		0,	"D008001",	"V001027",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYO	Åè/Ï®*/		0,	"D008001",	"V001028",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW,/*IDC_LIST_YOSO_SAGYO	î/Hö*/		0,	"D008001",	"V001029",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,		             IDC_BUTTON_PREVIEW,	"D008001",	"B001030",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,		                           IDOK,	"D008001",	"B001031",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,		                       IDCANCEL,	"D008001",	"B001032",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW_LIST,	                 IDC_LIST_KOTEI,	"D008001",	"L002001",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW_LIST,	                 IDC_LIST_KOTEI,	"D008001",	"L002002",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW_LIST,	                 IDC_LIST_KOTEI,	"D008001",	"L002003",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW_LIST,	            IDC_LIST_YOSO_SAGYO,	"D008001",	"L003001",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW_LIST,	            IDC_LIST_YOSO_SAGYO,	"D008001",	"L003002",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW_LIST,	            IDC_LIST_YOSO_SAGYO,	"D008001",	"L004001",		NULL,			NULL	},
{	CTRL_TYPE_LVIEW_LIST,	            IDC_LIST_YOSO_SAGYO,	"D008001",	"L004002",		NULL,			NULL	},
{	                  -1,		                          0,		   "",	       "",		NULL,			NULL	}
};


#define LIST_MANUALEDITKOTEI_NUM	6

struct LIxxxTRLDATA List_ManualEditKotei[LIST_MANUALEDITKOTEI_NUM] = {
	{	0,		 7,		9,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	  0	},
	{	1,		 8,		7,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_NOTEDIT,		"",		0,	0,	  0	},
	{	2,		 9,		7,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_EDITBOX,		"",		0,	0,	 32	},
	{	3,		10,		7,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_EDITBOX,		"",		0,	0,	128	},
	{	4,		11,		5,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_COMBOBOX,	"",		0,	0,	  0	},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		"",		0,	0,	  0	}
};

#define LIST_MANUALEDITYOSOSAGYOUGUN_NUM	3

struct LIxxxTRLDATA List_ManualEditYosoSagyogun[LIST_MANUALEDITYOSOSAGYOUGUN_NUM] = {
	{	0,		15,		4,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		0,	0,	  0	},
	{	1,		16,		22,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_EDITBOX,		"",		0,	0,	128	},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		"",		0,	0,	  0	}
};

#define LIST_MANUALEDITYOSOSAGYOU_NUM	10

struct LIxxxTRLDATA List_ManualEditYosoSagyo[LIST_MANUALEDITYOSOSAGYOU_NUM] = {
	{	0,		20,		4,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_NOTEDIT,		"",		         0,		        0,	  0	},
	{	1,		21,		25,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_EDITBOX,		"",		         0,		        0,	128	},
	{	2,		22,		9,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_COMBOBOX,	"",		         0,		        0,	  0	},
	{	3,		23,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_RNUM,			eINPUTTYPE_EDITBOX,		"",		-9999.9999,		9999.9999,	 10	},
	{	4,		24,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_RNUM,			eINPUTTYPE_EDITBOX,		"",		-9999.9999,		9999.9999,	 10	},
	{	5,		25,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_EDITBOX,		"",		         1,		     9999,	  4	},
	{	6,		26,		8,	LVCFMT_RIGHT,	FALSE,	FALSE,	eITEMTYPE_INUM,			eINPUTTYPE_EDITBOX,		"",		         0,		     9999,	  0	},
	{	7,		27,		8,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_COMBOBOX,	"",		         0,		        0,	  0	},
	{	8,		28,		8,	LVCFMT_LEFT,	FALSE,	FALSE,	eITEMTYPE_STRING,		eINPUTTYPE_COMBOBOX,	"",		         0,		        0,	  0	},
	{	-1,		-1,		0,	            0,	FALSE,	FALSE,	               0,						0,		"",		         0,		        0,	  0	}
};
