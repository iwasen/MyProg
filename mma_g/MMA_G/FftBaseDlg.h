#pragma once

#include "DialogEx.h"

// CFftBaseDlg �_�C�A���O

class CFftBaseDlg : public CDialogEx
{
	DECLARE_DYNAMIC(CFftBaseDlg)

public:
	CFftBaseDlg(UINT nID, CWnd* pParent);   // �W���R���X�g���N�^
	virtual ~CFftBaseDlg();

protected:
	HWND m_hWndParent;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnDestroy();
};
