// HaltError.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MmeSend.h"
#include "HaltError.h"


// CHaltError ダイアログ

IMPLEMENT_DYNAMIC(CHaltError, CDialog)

CHaltError::CHaltError(CWnd* pParent, SLasttimeHalt &oLasttimeHalt)
	: CDialog(CHaltError::IDD, pParent)
	, m_oLasttimeHalt(oLasttimeHalt)
	, m_sEditType(_T(""))
	, m_sEditNature(_T(""))
	, m_sEditTaskName(_T(""))
	, m_sEditErrorCode(_T(""))
	, m_sEditTime(_T(""))
{

}

CHaltError::~CHaltError()
{
}

void CHaltError::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_EDIT_SESSION_NO, m_sEditSessionNo);
	DDX_Text(pDX, IDC_EDIT_TYPE, m_sEditType);
	DDX_Text(pDX, IDC_EDIT_NATURE, m_sEditNature);
	DDX_Text(pDX, IDC_EDIT_TASK_NAME, m_sEditTaskName);
	DDX_Text(pDX, IDC_EDIT_ERROR_CODE, m_sEditErrorCode);
	DDX_Text(pDX, IDC_EDIT_TIME, m_sEditTime);
}


BEGIN_MESSAGE_MAP(CHaltError, CDialog)
END_MESSAGE_MAP()


// CHaltError メッセージ ハンドラ

BOOL CHaltError::OnInitDialog()
{
	m_sEditSessionNo.Format("0x%08x", m_oLasttimeHalt.oBitStatus.nSessionNumber);
	m_sEditType.Format("0x%02x", m_oLasttimeHalt.oBitStatus.nType);
	m_sEditNature.Format("0x%08x", m_oLasttimeHalt.oBitStatus.nNature);
	m_sEditTaskName = m_oLasttimeHalt.oHaltInfo.sTaskName;
	m_sEditErrorCode.Format("0x%04x", m_oLasttimeHalt.oHaltInfo.nErrorCode);
	m_sEditTime.Format("0x%08x", m_oLasttimeHalt.oHaltInfo.nErrorTime);

	CDialog::OnInitDialog();

	return TRUE;
}

void CHaltError::OnOK()
{
	UpdateData(TRUE);

	m_oLasttimeHalt.oBitStatus.nSessionNumber = GetInt(m_sEditSessionNo);
	m_oLasttimeHalt.oBitStatus.nType = GetInt(m_sEditType);
	m_oLasttimeHalt.oBitStatus.nNature = GetInt(m_sEditNature);
	m_oLasttimeHalt.oHaltInfo.sTaskName = m_sEditTaskName;
	m_oLasttimeHalt.oHaltInfo.nErrorCode = GetInt(m_sEditErrorCode);
	m_oLasttimeHalt.oHaltInfo.nErrorTime = GetInt(m_sEditTime);

	CDialog::OnOK();
}
