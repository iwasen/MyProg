#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		DialogEx .h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�g���_�C�A���O�N���X��`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************


// CDialogEx �_�C�A���O

class CDialogEx : public CDialog
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

	DECLARE_DYNAMIC(CDialogEx)

public:
	CDialogEx(UINT nID, CWnd* pParent);   // �W���R���X�g���N�^

	BOOL DoModeless();
	void SetCtlPosition(UINT nID, double fLeft, double fTop, double fRight, double fBottom);
	void SetCtlPosition(CWnd *pWnd, double fLeft, double fTop, double fRight, double fBottom);
	void SetAllCtlPosition(double fLeft, double fTop, double fRight, double fBottom);
	void CreateStatusBar();
	void SetStatusBar(LPCTSTR pText);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void OnCancel();
	virtual void OnOK();

	DECLARE_MESSAGE_MAP()
	virtual void PostNcDestroy();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg LRESULT OnExitSizeMove(WPARAM wParam, LPARAM lParam);
	afx_msg void OnGetMinMaxInfo(MINMAXINFO* lpMMI);
	virtual BOOL OnInitDialog();

protected:
	UINT m_nID;
	CWnd *m_pParent;
	CDlgCtlPositionArray m_aDlgCtlPosition;
	CSize m_sizeDlg;
	BOOL m_bModeless;
	BOOL m_bStatusBar;
	CStatusBar  m_wndStatusBar;
	BOOL m_bSizeChange;
};
