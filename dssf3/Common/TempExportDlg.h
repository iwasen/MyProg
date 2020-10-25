#pragma once

class CTempExportDlg : public CDialog
{
public:
	CTempExportDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	long m_nSelectID;

protected:
	enum { IDD = IDD_TEMP_EXPORT };

	void SetNsTmpList(long nID);
	BOOL ExportTemplate(LPCTSTR pFileName);
	BOOL CheckSelect(long nID);

	CListBox	m_cTemplateList;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnAllSelect();
	afx_msg void OnAllRemove();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
