// System.h: CSystem クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_SYSTEM_H__3FC7F44D_031B_4F2F_8CC8_3EA0247DB2CB__INCLUDED_)
#define AFX_SYSTEM_H__3FC7F44D_031B_4F2F_8CC8_3EA0247DB2CB__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "IniFile.h"
#include "UserInfo.h"
#include "Message.h"
#include "MenuDispCtrl.h"	// By Y.Itabashi (xxxxx) 2007.1.24 
#include "WorkSchedulerDlg.h"

struct SEditWindowInfo {
	BOOL bSaved;
	CRect rectMain;
	WINDOWPLACEMENT wpChild1;
	WINDOWPLACEMENT wpChild2;
	WINDOWPLACEMENT wpChild3;

	SEditWindowInfo() {
		bSaved = FALSE;
	}
};

struct SDPEInterfaceItem {
	CStringArray aList;
};
struct SDPEInterfaceFile {
	CArray <SDPEInterfaceItem, SDPEInterfaceItem&> aItem;
};
typedef CArray <SDPEInterfaceFile, SDPEInterfaceFile&> CDPEInterfaceFileArray;

class CSystem
{
public:
	BOOL Initialize();
	BOOL ReadUserInfo(LPCTSTR pShokuban);
	BOOL SaveUserInfo();
	int DispMessage(LPCTSTR pMessageNo, ...);
	void SetProgressBar(int nProcessNum);
	void StepProgressBar(LPCTSTR pStatusBar);
	void StepProgressBar(LPCTSTR pStatusBar, int nProcessCount, int nProcessNum);
	void ClearProgressBar();
	void EnableMenu();
// Modify ... ( ADD )
	CString GetDPEIndex( int nFileNo, int nItemNo, int nListNo );
// By Y.Itabashi (xxxxx) 2007.02.20
	CIniSystem m_cIniSystem;
	CIniUser m_cIniUser;
	CUserInfo m_cUserInfo;
	CMessage m_cMessage;
	CMenuDispCtrl m_cMenuDisp;		// By Y.Itabashi (xxxxx) 2007.1.24 
	SEditWindowInfo m_cEditWindowInfo;
	CDPEInterfaceFileArray m_aDPEInterface;
	CWorkSchedulerDlg *m_pMainDlg;
	CTime m_cSaveTime;
	BOOL m_bReadOnly;
	int m_nProcessNum;
	int m_nProcessCount;
	DWORD m_dwTickCount;

	int	m_Save_mode;  //2007/02/10 xxxxx kuwa
// Modify ... ( ADD )
	BOOL	m_bJapanese;
// By Y.Itabashi (xxxxx) 2007.03.05

protected:
	BOOL ReadDPERenkeiFile();
};

#endif // !defined(AFX_SYSTEM_H__3FC7F44D_031B_4F2F_8CC8_3EA0247DB2CB__INCLUDED_)
