#pragma once

#include "MyCtrl.h"
#include "DlgCommon.h"


// CDlgBoard �_�C�A���O

class CDlgBoard : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgBoard)

public:
	CDlgBoard(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_DLG_BOARD };

protected:
	void DispData();

	BOOL m_bUpdate;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAdd();
	afx_msg void OnBnClickedButtonDelete();
	afx_msg void OnBnClickedButtonUpdate();
	virtual void OnCancel();
	afx_msg void OnLbnSelchangeListBoard();

	CListBox m_cListBoard;
	CEditEx m_cEditBoard;
};
