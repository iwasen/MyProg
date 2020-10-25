#pragma once

class CDialogML : public CDialog
{
public:
	CDialogML(UINT nIDTemplate, CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	BOOL Create(CWnd *pParentWnd = NULL);

protected:
	int MessageBox(UINT nIDPrompt, UINT nType = MB_OK);

	UINT m_nIDTemplate;
	CWnd *m_pParent;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void PostNcDestroy();
	virtual void OnCancel();
	virtual void OnOK();
};
