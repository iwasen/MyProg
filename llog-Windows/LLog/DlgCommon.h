#pragma once

#include "DataBase.h"

// CDlgCommon ダイアログ

class CDlgCommon : public CDialog
{
	struct SDlgCtlPosition {
		HWND hWnd;
		CRect rectCtl;
		double fLeft;
		double fTop;
		double fRight;
		double fBottom;
	};
	typedef CArray <SDlgCtlPosition, SDlgCtlPosition&> CDlgCtlPositionArray;

	DECLARE_DYNAMIC(CDlgCommon)

protected:
	CDataBase *m_pDataBase;
	CTime m_oDate;
	CDlgCtlPositionArray m_aDlgCtlPosition;
	CSize m_sizeDlg;
	BOOL m_bSizeChange;

public:
	CDlgCommon(UINT nID, CWnd* pParent);   // 標準コンストラクタ

	UINT DispDialog(CDataBase &oDataBase, CTime oDate);
	void SetCtlPosition(UINT nID, double fLeft, double fTop, double fRight, double fBottom);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg LRESULT OnExitSizeMove(WPARAM wParam, LPARAM lParam);
	virtual BOOL OnInitDialog();
	afx_msg void OnGetMinMaxInfo(MINMAXINFO* lpMMI);
};
