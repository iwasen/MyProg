// MenuDispCtrl.cpp: CMenuDispCtrl クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "MenuDispCtrl.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CMenuDispCtrl::CMenuDispCtrl()
{

}

CMenuDispCtrl::~CMenuDispCtrl()
{

}

// メッセージファイル読み込み
BOOL CMenuDispCtrl::ReadMenuFile(LPCTSTR pFileName)
{
	CString sString;
	CStdioFile cFile;

	if (!cFile.Open(pFileName, CFile::modeRead | CFile::shareDenyNone))
		return FALSE;

	while (cFile.ReadString(sString)) {
		if( sString[0] == '#' )
			continue;

		if (sString.GetLength() > 8 && sString[7] == ' ' && sString[15] == ' ' ) {
			CString *pMenu = m_aMenu.AddElement(sString.Left(15));

			CString	str = sString.Mid(16);
			int index = str.Find( "\\n" );
			if( index == -1 )
				*pMenu = str;
			else{
				str.Replace( "\\n", "\n" );
				*pMenu = str;
			}
		}
	}

	return TRUE;
}

CString CMenuDispCtrl::GetMenu(LPCTSTR pMenuNo, LPCTSTR pItemNo)
{
	CString pMenuKey;
	pMenuKey.Format( "%s %s", pMenuNo, pItemNo );

	CString *pMenu = m_aMenu.LookupElement(pMenuKey);
	if (pMenu != NULL)
		return *pMenu;
	else
		return "no menu";
}

void CMenuDispCtrl::SetMenuText(CWnd *pWnd, struct MENUCTRLDATA *CtrlData)
{
	CString	pMenuStr, pMenuTemp, str1, str2;
	CWnd *pCtrlWnd;
	CListBox *pList;
	CComboBox *pCombo;
	CMenu*	pMenu;

	if( pWnd == NULL )
		return;

	int i = 0;
	int j = 0;
	while( CtrlData[i].CtrlType != CTRL_TYPE_NONE ){

		pMenuTemp = g_pSystem->m_cMenuDisp.GetMenu( CtrlData[i].MenuNo, CtrlData[i].ItemNo );
		str1.Empty();
		str2.Empty();
		if( CtrlData[i].str1 != NULL )
			str1 = CtrlData[i].str1;
		if( CtrlData[i].str2 != NULL )
			str2 = CtrlData[i].str2;
		pMenuStr.Format( pMenuTemp, str1, str2 );
		switch( CtrlData[i].CtrlType ){
		case CTRL_TYPE_TITLE:
			pWnd->SetWindowText( pMenuStr );
			break;

		case CTRL_TYPE_STATIC:
		case CTRL_TYPE_BUTTON:
		case CTRL_TYPE_RADIO:
		case CTRL_TYPE_CHECK:
			pCtrlWnd = pWnd->GetDlgItem( CtrlData[i].CtrlID );
			if( pCtrlWnd )
				pCtrlWnd->SetWindowText( pMenuStr );
			break;

		case CTRL_TYPE_LIST:
			pList = (CListBox *)pWnd->GetDlgItem( CtrlData[i].CtrlID );
			if( pList )
				pList->AddString( pMenuStr );
			break;

		case CTRL_TYPE_COMBO:
			pCombo = (CComboBox *)pWnd->GetDlgItem( CtrlData[i].CtrlID );
			if( pCombo )
				pCombo->AddString( pMenuStr );
			break;

		case CTRL_TYPE_TOP_MENU:
			pMenu = pWnd->GetMenu();
			str1 = CtrlData[i].str1;
			pMenu->GetMenuString( j, str2, MF_BYPOSITION );
			while( 1 ){
				if( str1 == str2 ){
					pMenu->ModifyMenu( j, MF_BYPOSITION, MF_STRING, pMenuStr );
					break;
				}
				j++;
				pMenu->GetMenuString( j, str2, MF_BYPOSITION );
			}
			break;
		case CTRL_TYPE_MENU:
			pMenu = pWnd->GetMenu();
			pMenu->ModifyMenu( CtrlData[i].CtrlID, MF_BYCOMMAND, CtrlData[i].CtrlID, pMenuStr );
			break;

		case CTRL_TYPE_TOP_POPUP:
			pMenu = (CMenu *)pWnd;
			str1 = CtrlData[i].str1;
			pMenu->GetMenuString( j, str2, MF_BYPOSITION );
			while( 1 ){
				if( str1 == str2 ){
					pMenu->ModifyMenu( j, MF_BYPOSITION, MF_STRING, pMenuStr );
					break;
				}
				j++;
				pMenu->GetMenuString( j, str2, MF_BYPOSITION );
			}
			break;

		case CTRL_TYPE_POPUP:
			pMenu = (CMenu *)pWnd;
			pMenu->ModifyMenu( CtrlData[i].CtrlID, MF_BYCOMMAND, CtrlData[i].CtrlID, pMenuStr );
			break;

		default:
			break;
		}

		i++;

	}
}


void CMenuDispCtrl::SetListViewInsertColumn(CWnd *pWnd, int id, struct MENUCTRLDATA *CtrlData)
{
	CString	pMenuStr, pMenuTemp, str1, str2;
	CLixxxtrl *pList;

	if( pWnd == NULL )
		return;

	int i = 0;
	int j = 0;
	while( CtrlData[i].CtrlType != CTRL_TYPE_NONE ){

		pMenuTemp = g_pSystem->m_cMenuDisp.GetMenu( CtrlData[i].MenuNo, CtrlData[i].ItemNo );
		str1.Empty();
		str2.Empty();
		if( CtrlData[i].str1 != NULL )
			str1 = CtrlData[i].str1;
		if( CtrlData[i].str2 != NULL )
			str2 = CtrlData[i].str2;
		pMenuStr.Format( pMenuTemp, str1, str2 );

		switch( CtrlData[i].CtrlType ){
		case CTRL_TYPE_LVIEW:
			if( id == CtrlData[i].CtrlID ){
				pList = (CLixxxtrl *)pWnd->GetDlgItem( id );
				pList->InsertColumn( j, pMenuStr );
				j++;
			}
			break;

		default:
			break;
		}

		i++;

	}

}
