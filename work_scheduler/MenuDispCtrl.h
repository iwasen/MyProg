// MenuDispCtrl.h: CMenuDispCtrl クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_MENUDISPCTRL_H__3096E20B_91C6_4AA4_B36A_66BD9CD479E9__INCLUDED_)
#define AFX_MENUDISPCTRL_H__3096E20B_91C6_4AA4_B36A_66BD9CD479E9__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ArrayEx.h"

#define	MAX_STR	256

struct MENUCTRLDATA{
	int		CtrlType;
	int		CtrlID;
	char	MenuNo[8];
	char	ItemNo[8];
	char	str1[MAX_STR];
	char	str2[MAX_STR];
};

#define	CTRL_TYPE_NONE			-1
#define	CTRL_TYPE_TITLE			1
#define CTRL_TYPE_STATIC		2
#define CTRL_TYPE_BUTTON		3
#define CTRL_TYPE_RADIO			4
#define CTRL_TYPE_CHECK			5
#define CTRL_TYPE_LIST			6
#define CTRL_TYPE_COMBO			7
#define CTRL_TYPE_LVIEW			8
#define CTRL_TYPE_LVIEW_LIST	9
#define CTRL_TYPE_TOP_MENU		21
#define CTRL_TYPE_MENU			22
#define CTRL_TYPE_TOP_POPUP		23
#define CTRL_TYPE_POPUP			24

// メニューデータ
typedef CArrayEx2 <CString, CString&> CMenuArray;

class CMenuDispCtrl  
{
public:
	CMenuDispCtrl();
	virtual ~CMenuDispCtrl();

	BOOL ReadMenuFile(LPCTSTR pFileName);
	CString GetMenu(LPCTSTR pMenuNo, LPCTSTR pItemNo);
	void SetMenuText(CWnd *pWnd, struct MENUCTRLDATA *CtrlData);
	void SetListViewInsertColumn(CWnd *pWnd, int id, struct MENUCTRLDATA *CtrlData);

protected:
	CMenuArray m_aMenu;

};

#endif // !defined(AFX_MENUDISPCTRL_H__3096E20B_91C6_4AA4_B36A_66BD9CD479E9__INCLUDED_)
