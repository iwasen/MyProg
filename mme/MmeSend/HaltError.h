#pragma once
#include "afxwin.h"


// CHaltError �_�C�A���O

class CHaltError : public CDialog
{
	DECLARE_DYNAMIC(CHaltError)

public:
	CHaltError(CWnd* pParent, SLasttimeHalt &oLasttimeHalt);   // �W���R���X�g���N�^
	virtual ~CHaltError();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_HALT_ERROR };

protected:
	SLasttimeHalt &m_oLasttimeHalt;

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	CString m_sEditSessionNo;
	CString m_sEditType;
	CString m_sEditNature;
	CString m_sEditTaskName;
	CString m_sEditErrorCode;
	CString m_sEditTime;
	virtual BOOL OnInitDialog();
	virtual void OnOK();
};
