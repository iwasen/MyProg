#pragma once

#include "MyCtrl.h"
#include "DlgCommon.h"

// CDlgComment �_�C�A���O

class CDlgComment : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgComment)

public:
	CDlgComment(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_DLG_COMMENT };

	int m_nSeqNo;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();

	CEditEx m_cEditDate;
	CEditEx m_cEditComment;
};
