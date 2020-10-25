
#define	MENU_MANUALWINDOWTITLE_NUM	5

struct MENUCTRLDATA	Menu_ManualWindowTitle[MENU_MANUALWINDOWTITLE_NUM] = {
{	CTRL_TYPE_TITLE,	0,	"W007004",	"T001001",		NULL,			NULL	},
{	CTRL_TYPE_TITLE,	0,	"W007004",	"T001002",		NULL,			NULL	},
{	CTRL_TYPE_TITLE,	0,	"W007004",	"T001003",		NULL,			NULL	},
{	CTRL_TYPE_TITLE,	0,	"W007004",	"T001004",		NULL,			NULL	},
{	              -1,	0,	       "",	       "",		NULL,			NULL	}
};

#define	MENU_MANUALWINDOWPOPUP_NUM	11

struct MENUCTRLDATA	Menu_ManualWindowPopup[MENU_MANUALWINDOWPOPUP_NUM] = {
{	CTRL_TYPE_POPUP,		           IDM_MANUAL_EDIT,	"P007002",	"M001001",		              NULL,			NULL	},
{	CTRL_TYPE_POPUP,		           IDM_MANUAL_MOVE,	"P007002",	"M001002",		              NULL,			NULL	},
{	CTRL_TYPE_POPUP,		       IDM_MANUAL_BUNKATSU,	"P007002",	"M001003",		              NULL,			NULL	},
{	CTRL_TYPE_POPUP,		           IDM_MANUAL_COPY,	"P007002",	"M001004",		              NULL,			NULL	},
{	CTRL_TYPE_POPUP,		          IDM_MANUAL_PASTE,	"P007002",	"M001005",		              NULL,			NULL	},
{	CTRL_TYPE_TOP_POPUP,	                         0,	"P007002",	"M001006",		"çÏã∆é“ÇÃêVãKë}ì¸",			NULL	},
{	CTRL_TYPE_POPUP,		IDM_MANUAL_SAGYOSHA_SAKUJO,	"P007002",	"M001007",		              NULL,			NULL	},
{	CTRL_TYPE_POPUP,		           IDM_MANUAL_DSTR,	"P007002",	"M001008",		              NULL,			NULL	},
{	CTRL_TYPE_POPUP,		   IDM_MANUAL_SAGYOSHA_MAE,	"P007002",	"M002001",		              NULL,			NULL	},
{	CTRL_TYPE_POPUP,		IDM_MANUAL_SAGYOSHA_USHIRO,	"P007002",	"M002002",		              NULL,			NULL	},
{	              -1,		                         0,	       "",	       "",		              NULL,			NULL	}
};

#define	MENU_MANUALWINDOWTEXT_NUM	11

struct MENUCTRLDATA	Menu_ManualWindowText[MENU_MANUALWINDOWTEXT_NUM] = {
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001001",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001002",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001003",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001004",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001005",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001006",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001007",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001008",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001009",		NULL,			NULL	},
{	CTRL_TYPE_STATIC,	0,	"W007005",	"S001010",		NULL,			NULL	},
{	              -1,	0,	       "",	       "",		NULL,			NULL	}
};

#define	MENU_MANUALWINDOWBAR_NUM	4

struct MENUCTRLDATA	Menu_ManualWindowBar[MENU_MANUALWINDOWBAR_NUM] = {
{	CTRL_TYPE_BUTTON,	       IDC_RADIO_GRAPH_KOTEI,	"B007003",	"B001001",		NULL,			NULL	},
{	CTRL_TYPE_BUTTON,	IDC_RADIO_GRAPH_YOSOSAGYOGUN,	"B007003",	"B001002",		NULL,			NULL	},
{	CTRL_TYPE_COMBO,	             IDC_COMBO_KISHU,	"B007003",	"L001003",		NULL,			NULL	},
{	              -1,	                           0,	       "",	       "",		NULL,			NULL	}
};
