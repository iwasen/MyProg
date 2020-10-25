#pragma once

#include "MyCtrl.h"
#include "TempAcf.h"
#include "TempIAcf.h"

class CTemplateDlg : public CDialog
{
public:
	CTemplateDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

protected:
	enum { IDD = IDD_TEMPLATE };

	void SetNsTmpList(long nID);
	void SaveNoiseTemplate(long nID);

	CMyEdit	m_cNoiseSource;
	CListBox	m_cTemplateList;
	CMyTab	m_cTab;
	CTempAcf m_TempAcf;
	CTempIAcf m_TempIAcf;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnDeleteTemplate();
	afx_msg void OnSelchangeTemplateList();
	afx_msg void OnUpdateTemplate();
	afx_msg void OnImport();
	afx_msg void OnExport();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
