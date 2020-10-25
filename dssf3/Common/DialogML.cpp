// DialogML.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "DialogML.h"

/////////////////////////////////////////////////////////////////////////////
// CDialogML �_�C�A���O


CDialogML::CDialogML(UINT nIDTemplate, CWnd* pParent /*=NULL*/)
{
	m_nIDTemplate = nIDTemplate;
	m_pParent = pParent;
}


void CDialogML::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CDialogML, CDialog)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CDialogML ���b�Z�[�W �n���h��

BOOL CDialogML::Create(CWnd *pParentWnd)
{
	if (pParentWnd == NULL)
		pParentWnd = m_pParent;

	return CDialog::Create(m_nIDTemplate, pParentWnd);
}

void CDialogML::OnCancel()
{
}

void CDialogML::OnOK()
{
}

void CDialogML::PostNcDestroy()
{
	delete this;
}

int CDialogML::MessageBox(UINT nIDPrompt, UINT nType)
{
	CString msg;

	msg.LoadString(nIDPrompt);
	return CWnd::MessageBox(msg, NULL, nType);
}
