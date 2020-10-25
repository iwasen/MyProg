// System.cpp: CSystem �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "System.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// ���[�U���X�g�J�����ʒu
#define COL_USERLIST_SHIMEI				1
#define COL_USERLIST_SHOKUBAN			2
#define COL_USERLIST_BUSHO				3
#define COL_USERLIST_JOKENSETTEI_SOSA	4
#define COL_USERLIST_KOJUNKISEI_IHAN	5

// �f�[�^�Ǘ��N���X����������
BOOL CSystem::Initialize()
{
// Modify ... ( CHANGE )
	m_bJapanese = ( ::GetSystemDefaultLangID() == 0x0411 ) ? TRUE : FALSE;
// By Y.Itabashi (xxxxx) 2007.03.05

	// �V�X�e���pini�t�@�C���ǂݍ���
	if (!m_cIniSystem.ReadIniFile()) {
// Modify ... ( CHANGE )
		if( m_bJapanese )
			AfxMessageBox(IDS_ERR_SYSTEM_INI, MB_OK | MB_ICONSTOP);
		else
			AfxMessageBox(IDS_ERR_SYSTEM_INI_ENG, MB_OK | MB_ICONSTOP);
//		AfxMessageBox(IDS_ERR_SYSTEM_INI, MB_OK | MB_ICONSTOP);
// By Y.Itabashi (xxxxx) 2007.03.05
		return FALSE;
	}

	// ���b�Z�[�W�t�@�C���ǂݍ���
// Modify ... ( ADD )
	if (!m_cMessage.ReadMessageFile(m_cIniSystem.m_sMessageFilePath)) {
//	if (!m_cMessage.ReadMessageFile(m_cIniSystem.m_sSystemServerPath)) {
// By Y.Itabashi (xxxxx) 2007.1.24
// Modify ... ( CHANGE )
		if( m_bJapanese )
			AfxMessageBox(IDS_ERR_MESSAGE_FILE, MB_OK | MB_ICONSTOP);
		else
			AfxMessageBox(IDS_ERR_MESSAGE_FILE_ENG, MB_OK | MB_ICONSTOP);
//		AfxMessageBox(IDS_ERR_MESSAGE_FILE, MB_OK | MB_ICONSTOP);
// By Y.Itabashi (xxxxx) 2007.03.05
		return FALSE;
	}

// Modify ... ( ADD )
	// ���b�Z�[�W�t�@�C���ǂݍ���
	if (!m_cMenuDisp.ReadMenuFile(m_cIniSystem.m_sMenuFilePath)) {
		DispMessage("E001005");
		return FALSE;
	}
// By Y.Itabashi (xxxxx) 2007.1.24

	// DPE�A�g�t�@�C���ݒ�l�t�@�C���ǂݍ���
	if (!ReadDPERenkeiFile()) {
		DispMessage("E001006");
		return FALSE;
	}

	return TRUE;
}

// DPE�A�g�t�@�C���ݒ�l�t�@�C���ǂݍ���
BOOL CSystem::ReadDPERenkeiFile()
{
	CExcelLib cExcelLib;
	CExcelData cExcelData;

	if (!cExcelLib.Open(CGlobal::MakePathName(m_cIniSystem.m_sSystemServerPath, "DPEInterfaceDataTemplate.xls")))
		return FALSE;

	// ���[�U���X�g�ǂݍ���
	cExcelData.Read(cExcelLib, 1, 0);

	int nItemNo, nListNo;
	CString sSheetName;
	int nSheetName;
	int nSheetNum = cExcelData.GetSheetNum();
	for (int nSheetNo = 0; nSheetNo < nSheetNum; nSheetNo++) {
		cExcelData.SetCurrentSheet(nSheetNo);
		sSheetName = cExcelData.GetSheetName();
		if (CDataCheck::IsNumber(sSheetName)) {
			nSheetName = atoi(sSheetName);
			if (nSheetName >= m_aDPEInterface.GetSize())
				m_aDPEInterface.SetSize(nSheetName + 1);
			SDPEInterfaceFile &cDPEInterfaceFile = m_aDPEInterface[nSheetName];

			int nUsedRows = cExcelData.GetUsedRows();
			for (int nRow = 2; nRow <= nUsedRows; nRow++) {
				cExcelData.GetElement(nRow, 1, nItemNo);
				if (nItemNo != 0) {
					if (nItemNo >= cDPEInterfaceFile.aItem.GetSize())
						cDPEInterfaceFile.aItem.SetSize(nItemNo + 1);
					SDPEInterfaceItem &cDPEInterfaceItem = cDPEInterfaceFile.aItem[nItemNo];

					cExcelData.GetElement(nRow, 2, nListNo);
					if (nListNo >= cDPEInterfaceItem.aList.GetSize())
						cDPEInterfaceItem.aList.SetSize(nListNo + 1);

					cExcelData.GetElement(nRow, 3, cDPEInterfaceItem.aList[nListNo]);
				}
			}
		}
	}

	return TRUE;
}

// ���[�U���擾
BOOL CSystem::ReadUserInfo(LPCTSTR pShokuban)
{
	CExcelLib cExcelLib;
	CExcelData cUserList;
	CString sShokuban;

	// ���[�U���X�g�t�@�C�����I�[�v������
	if (!cExcelLib.Open(m_cIniSystem.m_sUserListFilePath)) {
		DispMessage("E001001");
		return FALSE;
	}

	// ���[�U���X�g�ǂݍ���
	cUserList.Read(cExcelLib, 1, 1);

	// �w�肳�ꂽ�E�Ԃ����݂��邩�`�F�b�N
	for (int nRow = 3; nRow <= cUserList.GetUsedRows(); nRow++) {
		cUserList.GetElement(nRow, COL_USERLIST_SHOKUBAN, sShokuban);
		if (sShokuban == pShokuban) {
			break;
		}
	}

	// ���݂��Ȃ���΃G���[
	if (nRow > cUserList.GetUsedRows()) {
		DispMessage("E001002");
		return FALSE;
	}

	// ���[�U���X�g���擾
	cUserList.GetElement(nRow, COL_USERLIST_SHIMEI, m_cUserInfo.m_sUserName);
	cUserList.GetElement(nRow, COL_USERLIST_SHOKUBAN, m_cUserInfo.m_sShokuban);
	cUserList.GetElement(nRow, COL_USERLIST_BUSHO, m_cUserInfo.m_sBusho);
	cUserList.GetElement(nRow, COL_USERLIST_JOKENSETTEI_SOSA, m_cUserInfo.m_nJokenSetteiSosa);
	cUserList.GetElement(nRow, COL_USERLIST_KOJUNKISEI_IHAN, m_cUserInfo.m_nKojunKiseiIhan);

	// �f�[�^�`�F�b�N
	if (m_cUserInfo.m_nJokenSetteiSosa != 1 && m_cUserInfo.m_nJokenSetteiSosa != 2)
		m_cUserInfo.m_nJokenSetteiSosa = 1;
	if (m_cUserInfo.m_nKojunKiseiIhan != 0 && m_cUserInfo.m_nKojunKiseiIhan != 1)
		m_cUserInfo.m_nKojunKiseiIhan = 0;

	// ���[�U�pini�t�@�C���ǂݍ���
	if (!m_cIniUser.ReadIniFile(m_cIniSystem.m_sUserInfoPath, pShokuban)) {
		DispMessage("E001003");
		return FALSE;
	}

	return TRUE;
}

// ���[�U���ۑ�
BOOL CSystem::SaveUserInfo()
{
	// ���[�U�pini�t�@�C����������
	if (!m_cIniUser.WriteIniFile(m_cIniSystem.m_sUserInfoPath, m_cUserInfo.m_sShokuban)) {
		DispMessage("E002004");
		return FALSE;
	}

	return TRUE;
}

// ���b�Z�[�W�\��
int CSystem::DispMessage(LPCTSTR pMessageNo, ...)
{
	va_list args;
	va_start(args, pMessageNo);

	CString sMessage;
	sMessage.FormatV(m_cMessage.GetMessage(pMessageNo), args);
	sMessage.Replace("\\n", "\n");

	CString sMessage2;
	sMessage2.Format("%s\n(MessageNo:%s)", sMessage, pMessageNo);

	UINT nFlag;
	switch (*pMessageNo) {
	case 'E':
		nFlag = MB_OK | MB_ICONSTOP;
		break;
	case 'W':
		nFlag = MB_OKCANCEL | MB_ICONEXCLAMATION;
		break;
	case 'I':
		nFlag = MB_OK | MB_ICONINFORMATION;
		break;
	case 'Q':
		nFlag = MB_YESNO | MB_ICONQUESTION;
		break;
	}

	return AfxMessageBox(sMessage2, nFlag);
}

// �v���O���X�o�[�͈̔͂�ݒ肷��
void CSystem::SetProgressBar(int nProcessNum)
{
	m_nProcessNum = nProcessNum;
	m_nProcessCount = 0;
	m_dwTickCount = ::GetTickCount();

	m_pMainDlg->m_cProgressBar.SetRange(0, nProcessNum);
}

void CSystem::StepProgressBar(LPCTSTR pStatusBar)
{
	StepProgressBar(pStatusBar, m_nProcessCount + 1, m_nProcessNum);
}

// �v���O���X�o�[���P�X�e�b�v�i�߂�
void CSystem::StepProgressBar(LPCTSTR pStatusBar, int nProcessCount, int nProcessNum)
{
	// �ŏ��ł�0.1�b�\������
#ifndef _DEBUG
	int nTick = 100 - (::GetTickCount() - m_dwTickCount);
	if (nTick > 0)
		Sleep(nTick);
	m_dwTickCount = ::GetTickCount();
#endif

	// �v���O���X�o�[�\��
	m_nProcessCount++;
	m_pMainDlg->m_cProgressBar.SetPos(m_nProcessCount);

	// �X�e�[�^�X�o�[�\��
	CString sStatusBar;
	sStatusBar.Format("%s (%d/%d)", pStatusBar, nProcessCount, nProcessNum);
	m_pMainDlg->m_cStaticStatus.SetWindowText(sStatusBar);
}

// �v���O���X�o�[����������
void CSystem::ClearProgressBar()
{
	m_pMainDlg->m_cProgressBar.SetPos(0);
	m_pMainDlg->ClearSubMenuStatus();
}

// ���j���[�{�^����L��������
void CSystem::EnableMenu()
{
	m_pMainDlg->EnableMenu();
}

// Modify ... ( ADD )
// DPE�A�g�t�@�C���̎w��t�@�C���̎w�荀�ڂ��擾
CString CSystem::GetDPEIndex( int nFileNo, int nItemNo, int nListNo )
{
	if( nFileNo > m_aDPEInterface.GetSize() )
		return "no item";

	if( nItemNo > g_pSystem->m_aDPEInterface[nFileNo].aItem.GetSize() )
		return "no item";

	if( nListNo > m_aDPEInterface[nFileNo].aItem[nItemNo].aList.GetSize() )
		return "no item";

	return g_pSystem->m_aDPEInterface[nFileNo].aItem[nItemNo].aList[nListNo];
}
// By Y.Itabashi (xxxxx) 2007.02.20