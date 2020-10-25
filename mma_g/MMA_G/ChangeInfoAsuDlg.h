#pragma once

#include "IdbFile.h"
#include "MyCtrl.h"


// CChangeInfoMmeDlg �_�C�A���O

class CChangeInfoAsuDlg : public CDialog
{
	DECLARE_DYNAMIC(CChangeInfoAsuDlg)

public:
	CChangeInfoAsuDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CChangeInfoAsuDlg();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_CHANGE_INFO_ASU };

	CString m_sIdbPathName;
	CIdbFile m_oIdbFile;
	CToolTipCtrl m_tooltip;

	INT_PTR ChangeInfo(const CString &sIdbPathName);

protected:
	BOOL FindFileName(LPCTSTR pExt, CString &sFileName);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	CEditEx m_cEditDownlink;
	CEditEx m_cEditComment;
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
};
