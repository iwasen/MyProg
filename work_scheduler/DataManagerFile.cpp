//*****************************************************************************************************
//  1. �t�@�C����
//		DataManagerEdit.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CDataManager �N���X�̃C���v�������e�[�V�����i�t�@�C���֘A�j
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"
#include "DataManager.h"
#include "Shlwapi.h"
#include <sys/types.h>
#include <sys/stat.h>
#include <stdio.h>
#include <errno.h>

// �H���K���}�g���N�X�J�������
struct SColInfo {
	int nCol;
	CString sKishuName;
	CString sSublineName;
	CString sKoteiNo;
	CString sKoteiName;
	CString sYosoSagyogunName;
};
typedef CArrayEx2 <SColInfo, SColInfo&> CColInfoArray;

// �@�햼���o���
struct SKishuName {
	CString sKishuName;
	int nKishuNum;
};

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::ReadData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�t�@�C���ǂݍ��ݏ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pFolderName		[I] �f�[�^�t�H���_��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����CFALSE:�G���[
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�f�[�^�t�H���_���̊e�G�N�Z���t�@�C��������ϐ��ɓǂݍ��ށB
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CDataManager::ReadFile(LPCTSTR pFolderName)
{
// Modify ... ( CHANGE )
	g_pSystem->SetProgressBar(14);
//	g_pSystem->SetProgressBar(12);
// By Y.Itabashi (xxxxx) 2007.02.15

	g_pSystem->m_cIniUser.m_sDataPath = pFolderName;

	BOOL bResult = ReadFileSub();
	if (!bResult) {
		delete g_pDataManager;
		g_pDataManager = new CDataManager;
	}

	g_pSystem->ClearProgressBar();

// Modify ... ( ADD )
	m_sKittingKoteiKigo = KITTING_KOTEI_KIGO;
// By Y.Itabasshi (xxxxx) 2007.03.07

	return bResult;
}

BOOL CDataManager::ReadFileSub()
{
	CWaitCursor cWaitCursor;

	// �t�@�C���A�N�Z�X�`�F�b�N
	if (!CheckProjectFile())
		return FALSE;

	// �t�@�C���ǂݍ���
	if (!ReadProjectFile())
		return FALSE;

	// �v�f��ƕ\����Ə��Ƀ\�[�g
//	SortYosoSagyogun();

	// ���ʍH���t���O�ݒ�
	SetKyotsuKoteiFlag();

	// �X�e�[�^�X�ݒ�
	m_nStatus = max(m_nStatus, STATUS_READ_DATA);
	if (m_nYear != 0 && m_nMonth != 0) {
		int nSublineNum = m_aSubline.GetSize();
		for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
			if (m_aSubline[nSublineId].cSeisanJoken.bSaved) {
				m_nStatus = max(m_nStatus, STATUS_SEISAN_JOKEN);
				break;
			}
		}
	}

	return TRUE;
}

// �t�@�C���A�N�Z�X�`�F�b�N
BOOL CDataManager::CheckProjectFile()
{
	g_pSystem->m_bReadOnly = FALSE;

	// ���Y�䐔���
	if (!m_eSeisanDaisu.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath)) 
		return FALSE;

	// �Ζ��̐����
	if (!m_eKinmuTaisei.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath)) 
		return FALSE;

	// ���i�\���H�������N���
	if (!m_eBuhinhyoLink.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
		return FALSE;

	// DSTR�Z�o�p���
	if (!m_eDSTRSanshutsu.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
		return FALSE;

	// �v�f��ƕ\
	if (!m_eYosoSagyoHyo.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
		return FALSE;

	// ���C���K���}�g���N�X
	CStringArray aPathName;
	CGlobal::FindFiles(CGlobal::MakePathName(g_pSystem->m_cIniUser.m_sDataPath, "*_" + m_eLineKiseiMatrix.m_sFileName), aPathName);
	if (aPathName.GetSize() == 0) {
		CString sInputFile;
		sInputFile = CGlobal::FileOpenDlg(NULL, "*_e-mtx.xls|*_e-mtx.xls|Excel Files (*.xls)|*.xls|All Files (*.*)|*.*||", NULL, NULL, ".xls");
		if (sInputFile.IsEmpty())
			return FALSE;
		::CopyFile(sInputFile, CGlobal::MakePathName(g_pSystem->m_cIniUser.m_sDataPath, CGlobal::GetFileNameFromPath(sInputFile)), TRUE);
	}
	if (!m_eLineKiseiMatrix.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath)) {
		return FALSE;
	}

	// �^���K���}�g���N�X�t�@�C��
	int nKatashikiNum = m_aKatashiki.GetSize();
	for (int i = 0; i < nKatashikiNum; i++) {
		if (!m_aKatashiki[i].eKatashikiKiseiMatrix.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
			return FALSE;
	}

	// �L�b�g���e���v���[�g
	m_eKittingTemplate.m_sFileName = CGlobal::GetFileNameFromPath(g_pSystem->m_cIniSystem.m_sKittingTemplateFilePath);
	CopyFromServer(CGlobal::GetFolderNameFromPath(g_pSystem->m_cIniSystem.m_sKittingTemplateFilePath), m_eKittingTemplate.m_sFileName);
	if (!m_eKittingTemplate.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
		return FALSE;

	// ���Y�����ݒ�t�@�C��
	if (!m_eSeisanJoken.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
		return FALSE;

	// ���i�L�b�g�����t�@�C��
	if (!m_eBuhinKitting.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
		return FALSE;

	// ���ʍH�����t�@�C��
	if (!m_eKyotsuKotei.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
		return FALSE;

	// �L�b�g�ΏۊO���i���X�g
	if (!m_eKittingTaishogai.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
		return FALSE;

	if (g_pSystem->m_bReadOnly) {
		if (g_pSystem->DispMessage("W004004") == IDCANCEL)
			return FALSE;
	}

	return TRUE;
}

BOOL CDataManager::CopyFromServer(LPCTSTR pFileName)
{
	return CopyFromServer(g_pSystem->m_cIniSystem.m_sSystemServerPath, pFileName);
}

BOOL CDataManager::CopyFromServer(LPCTSTR pServerFolder, LPCTSTR pFileName)
{
	// �v���W�F�N�g�t�H���_�̃p�X������
	CString sProjectPath = CGlobal::MakePathName(g_pSystem->m_cIniUser.m_sDataPath, pFileName);

	// �v���W�F�N�g�t�H���_�Ƀt�@�C�����������牽�����Ȃ�
	CStringArray aProjectFile;
	CGlobal::FindFiles(sProjectPath, aProjectFile);
	if (aProjectFile.GetSize() != 0)
		return TRUE;

	// �T�[�o�Ƀt�@�C�������������牽�����Ȃ�
	CStringArray aServerFile;
	CGlobal::FindFiles(CGlobal::MakePathName(pServerFolder, pFileName), aServerFile);
	if (aServerFile.GetSize() == 0)
		return TRUE;

	if (aServerFile.GetSize() > 1) {
		CStringArrayEx aFileName;
		int nSize = aServerFile.GetSize();
		for (int i = 0; i < nSize; i++)
			aFileName.Add(CGlobal::GetFileNameFromPath(aServerFile[i]));
		g_pSystem->DispMessage("E004002", aFileName.Join(","));
		return FALSE;
	}

	// �T�[�o����v���W�F�N�g�t�H���_�Ƀt�@�C���R�s�[
	::CopyFile(aServerFile[0], CGlobal::MakePathName(g_pSystem->m_cIniUser.m_sDataPath, ::PathFindFileName(aServerFile[0])), TRUE);

	return TRUE;
}

BOOL CDataManager::ReadProjectFile()
{
// Modify ... ( ADD )
	// �v�f��ƃe���v���[�g��ǂݍ���
	CExcelYosoSagyoTemplate cYosoTemp;
	if (!g_pDataManager->ReadYosoSagyoTemplate()){
		g_pSystem->DispMessage("E004013", cYosoTemp.m_sFileName);
		return FALSE;
	}
// By Y.Itabashi (xxxxx) 2007.02.16

	// �v�f��ƕ\�t�@�C��
	DispReadProcess(m_eYosoSagyoHyo);
	if (!ReadYosoSagyoHyo()) {
		g_pSystem->DispMessage("E004013", m_eYosoSagyoHyo.m_sFileName); // 2007/02/10 xxxxx kuwa	
		return FALSE;
	}

	// ���Y�䐔���t�@�C��
	DispReadProcess(m_eSeisanDaisu);
	if (!ReadSeisanDaisu()) {
		g_pSystem->DispMessage("E004013", m_eSeisanDaisu.m_sFileName); // 2007/02/10 xxxxx kuwa			
		return FALSE;
	}

	// �Ζ��̐����t�@�C��
	DispReadProcess(m_eKinmuTaisei);
	if (!ReadKinmuTaisei()) {
		g_pSystem->DispMessage("E004013", m_eKinmuTaisei.m_sFileName); // 2007/02/10 xxxxx kuwa
		return FALSE;
	}

	// ���i�\���H�������N���t�@�C��
	DispReadProcess(m_eBuhinhyoLink);
	if (!ReadBuhinhyoLink()) {
		g_pSystem->DispMessage("E004013", m_eBuhinhyoLink.m_sFileName); // 2007/02/10 xxxxx kuwa
		return FALSE;
	}

	// DSTR�Z�o�p���t�@�C��
	DispReadProcess(m_eDSTRSanshutsu);
	if (!ReadDSTRSanshutsu()) {
		g_pSystem->DispMessage("E004013", m_eDSTRSanshutsu.m_sFileName); // 2007/02/10 xxxxx kuwa
		return FALSE;
	}

// Modify ... ( ADD )
	// �^���K���ᔽ�t�@�C��
	DispReadProcess(m_eKatashikiError);
	if (!ReadKatashikiError()) {
		g_pSystem->DispMessage("E004013", m_eKatashikiError.m_sFileName);
		return FALSE;
	}

	// ���C���K���ᔽ�t�@�C��
	DispReadProcess(m_eLineError);
	if (!ReadLineError()) {
		g_pSystem->DispMessage("E004013", m_eLineError.m_sFileName);
		return FALSE;
	}
// By Y.Itabashi (xxxxx) 2007.02.14

	// ���C���K���}�g���N�X�t�@�C��
	DispReadProcess(m_eLineKiseiMatrix);
	if (!ReadLineKiseiMatrix()) {
		g_pSystem->DispMessage("E004013", m_eLineKiseiMatrix.m_sFileName); // 2007/02/10 xxxxx kuwa
		return FALSE;
	}

	// �^���K���}�g���N�X�t�@�C��
	if (m_aKatashiki.GetSize() != 0) {
		DispReadProcess(m_aKatashiki[0].eKatashikiKiseiMatrix);
		if (!ReadKatashikiKiseiMatrix()) {
			g_pSystem->DispMessage("E004013", m_aKatashiki[0].eKatashikiKiseiMatrix.m_sFileName); // 2007/02/10 xxxxx kuwa
			return FALSE;
		}
	}

	// �L�b�g���e���v���[�g�t�@�C��
	DispReadProcess(m_eKittingTemplate);
	if (!ReadKittingTemplate()) {
		g_pSystem->DispMessage("E004013", m_eKittingTemplate.m_sFileName); // 2007/02/10 xxxxx kuwa
		return FALSE;
	}

	// ���Y�����ݒ�t�@�C��
	DispReadProcess(m_eSeisanJoken);
	if (!ReadSeisanJoken()) {
		g_pSystem->DispMessage("E004013", m_eSeisanJoken.m_sFileName); // 2007/02/10 xxxxx kuwa
		return FALSE;
	}

	// ���i�L�b�g�����t�@�C��
	DispReadProcess(m_eBuhinKitting);
	if (!ReadBuhinKitting()) {
		g_pSystem->DispMessage("E004013", m_eSeisanJoken.m_sFileName); // 2007/02/10 xxxxx kuwa
		return FALSE;
	}

	// ���ʍH�����t�@�C��
	DispReadProcess(m_eKyotsuKotei);
	if (!ReadKyotsuKotei()) {
		g_pSystem->DispMessage("E004013", m_eKyotsuKotei.m_sFileName); // 2007/02/10 xxxxx kuwa
		return FALSE;
	}

	// �L�b�g�ΏۊO���i���X�g�t�@�C��
	DispReadProcess(m_eKittingTaishogai);
	if (!ReadKittingTaishogai()) {
		g_pSystem->DispMessage("E004013", m_eKittingTaishogai.m_sFileName); // 2007/02/10 xxxxx kuwa
		return FALSE;
	}

	return TRUE;
}

void CDataManager::DispReadProcess(CExcelFile &cExcelFile)
{
	CString sStatusBar;

	sStatusBar.Format("%s��ǂݍ��ݒ�", cExcelFile.m_sPathName.IsEmpty() ? cExcelFile.m_sFileName : cExcelFile.GetFileName());
	g_pSystem->StepProgressBar(sStatusBar);
}

// �v�f��ƕ\�t�@�C���ǂݍ���
BOOL CDataManager::ReadYosoSagyoHyo()
{
	// �v�f��ƕ\�t�@�C���ǂݍ���
	if (!m_eYosoSagyoHyo.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eYosoSagyoHyo.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	// �T�u���C�����擾
	if (!ExtractSubline())
		return FALSE;

	// �@����擾
	if (!ExtractKishuName())
		return FALSE;

	// �v�f��ƌQ�擾
	if (!ExtractYosoSagyogun())
		return FALSE;

	// �v�f��ƕ\�f�[�^�`�F�b�N
	if (!CheckYosoSagyoHyo())
		return FALSE;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::ExtractSublineData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T�u���C���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƕ\����T�u���C�������擾���A�T�u���C���f�[�^(m_aSubline)�ɐݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CDataManager::ExtractSubline()
{
	CString sSublineName;
	CString sKoteiKigo;

	// �����̃T�u���C���f�[�^���N���A
	m_aSubline.RemoveAll();

	// �T�u���C�����Ƃɍ�Ɨv�f���܂Ƃ߂�
	int nUsedRows = m_eYosoSagyoHyo.GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.eColSublineName, sSublineName);

		if (sSublineName.Left(3) == "KIT")
			sSublineName = sSublineName.Mid(3);

		if (!sSublineName.IsEmpty()) {
			SSubline *pSubline = m_aSubline.LookupElement(sSublineName);
			if (pSubline == NULL) {
				pSubline = m_aSubline.AddElement(sSublineName);
				pSubline->sSublineName = sSublineName;
				m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.eColKoteiKigo, sKoteiKigo);
				pSubline->sSagyoshaName = sKoteiKigo.Right(1);
			}

			pSubline->aRowArray.Add(nRow);
		}
	}

	if (m_aSubline.GetSize() > MAX_SUBLINE) {
		g_pSystem->DispMessage("E004009");
		return FALSE;
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::ExtractKishuName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@�햼�擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƕ\����@�햼���擾���A�@��f�[�^(m_aKishu)�ɐݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CDataManager::ExtractKishuName()
{
	int i, j;
	int nSize;
	CString sKishuName;
	CArrayEx2 <SKishuName, SKishuName&> aKishuName;

	for (i = 0; i < 4; i++) {
		CKishuColsArray &aKishuCols = m_eYosoSagyoHyo.m_aKishuCols[i].aKishuCols;
		nSize = aKishuCols.GetSize();
		for (j = 0; j < nSize; j++) {
			CString &sKishuName = aKishuCols[j].sKishuName;
			SKishuName *pKishuName = aKishuName.LookupElement(sKishuName);
			if (pKishuName == NULL) {
				pKishuName = aKishuName.AddElement(sKishuName);
				pKishuName->sKishuName = sKishuName;
				pKishuName->nKishuNum = 0;
			}
			pKishuName->nKishuNum++;
		}
	}

	nSize = aKishuName.GetSize();
	for (i = 0; i < nSize; i++) {
		if (aKishuName[i].nKishuNum == 4) {
			SKishu *pKishu = m_aKishu.AddElement(aKishuName[i].sKishuName);
			pKishu->sKishuName = aKishuName[i].sKishuName;
		}
	}

	if (m_aKishu.GetSize() > MAX_KISHU) {
		g_pSystem->DispMessage("E004008");
		return FALSE;
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::ExtractYosoSagyogun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƌQ�f�[�^�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƕ\����v�f��ƌQ���Ƃ̃f�[�^�𒊏o���A�v�f��ƌQ�f�[�^(m_aYosoSagyogun)�ɐݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CDataManager::ExtractYosoSagyogun()
{
	// �L�b�g����Ǝ҃f�[�^���N���A
	m_aKittingSagyosha.RemoveAll();

	int nSubline = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSubline; nSublineId++) {
		if (!ExtractYosoSagyogunSub(nSublineId))
			return FALSE;
	}

	return TRUE;
}

BOOL CDataManager::ExtractYosoSagyogunSub(int nSublineId, int nStartRowIndex)
{
	int nRow;
	CString sKoteiKigo;
	CString sKoteiName;
	CString sYosoSagyogunName;
	CString sSagyosha;
	CString sSagyojun;
	CString sSagyoNaiyo;
	CString sJidoYosoSagyogun;
	CString sJidoKotei;
	CString sYosoSagyoName;
	CString sKey;
	double fTime;
	int nKishuId;
	int nSagyoshaId;
	SYosoSagyogun *pYosoSagyogun;
	int nRowIndex;
	int nRowsNum;
	SSubline &cSubline = m_aSubline[nSublineId];
	CStringArrayEx *pSagyosha;
	CYosoSagyogunArray *pYosoSagyogunArray;
	SKishuCols *pKishuCols;

	if (nStartRowIndex == 0) {
		// ��Ǝ҃f�[�^���N���A
		cSubline.aSagyosha.RemoveAll();

		// �v�f��ƌQ�f�[�^���N���A
		cSubline.aYosoSagyogun.RemoveAll();
		cSubline.aYosoSagyogunKit.RemoveAll();
	}

	// �@�퐔�擾
	int nKishuNum = GetKishuNum();

	// ���Y�@��t���O�G���A�m��
	cSubline.aSeisanKishuFlag.SetSize(nKishuNum);

	// �v�f��Ƃ�v�f��ƌQ���Ƃɂ܂Ƃ߂�
	nRowsNum = cSubline.aRowArray.GetSize();
	for (nRowIndex = nStartRowIndex; nRowIndex < nRowsNum; nRowIndex++) {
		nRow = cSubline.aRowArray[nRowIndex];

		// �G�N�Z���f�[�^����擾
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKoteiKigo), sKoteiKigo);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKoteiName), sKoteiName);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColYosoSagyogunName), sYosoSagyogunName);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColJidoShudoYosoSagyogun), sJidoYosoSagyogun);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColJidoShudoKotei), sJidoKotei);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColDaiatariYosoSagyoJikan), fTime);

		ConvertHankaku(sKoteiName);
		ConvertHankaku(sYosoSagyogunName);

		CheckCopyCount(sKoteiName);
		CheckCopyCount(sYosoSagyogunName);

// Modify ... ( CHANGE )
		if (sKoteiKigo == m_sKittingKoteiKigo) {
//		if (sKoteiKigo == KITTING_KOTEI_KIGO) {
// By Y.Itabasshi (xxxxx) 2007.03.07
			pYosoSagyogunArray = &cSubline.aYosoSagyogunKit;
			pSagyosha = &m_aKittingSagyosha;
			cSubline.bSetKitting = TRUE;
		} else {
			pYosoSagyogunArray = &cSubline.aYosoSagyogun;
			pSagyosha = &cSubline.aSagyosha;
		}

		// �v�f��ƌQ�f�[�^�擾
		sKey = sKoteiName + sYosoSagyogunName;
		pYosoSagyogun = pYosoSagyogunArray->LookupElement(sKey);
		if (pYosoSagyogun == NULL) {
			// �v�f��ƌQ��ǉ�
			pYosoSagyogun = pYosoSagyogunArray->AddElement(sKey);

			// �v�f��ƌQ�f�[�^��ݒ�
			pYosoSagyogun->sKoteiKigo = sKoteiKigo;
			m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKoteiNo), pYosoSagyogun->sKoteiNo);
			pYosoSagyogun->sKoteiName = sKoteiName;
			pYosoSagyogun->sYosoSagyogunName = sYosoSagyogunName;
			pYosoSagyogun->sJidoYosoSagyogun = sJidoYosoSagyogun;
			pYosoSagyogun->sJidoKotei = sJidoKotei;
			pYosoSagyogun->aKishuSagyo.SetSize(nKishuNum);

			// �@��ɂ�郋�[�v
			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = pYosoSagyogun->aKishuSagyo[nKishuId];

				// ��Ǝ҂𒊏o
				pKishuCols = m_eYosoSagyoHyo.m_aKishuCols[0].aKishuCols.LookupElement(m_aKishu[nKishuId].sKishuName);
				m_eYosoSagyoHyo.GetElement(nRow, pKishuCols->nCol, sSagyosha);

				// ��ƎҊ��t
				if (!sSagyosha.IsEmpty()) {
					nSagyoshaId = pSagyosha->AddUnique(sSagyosha);
					cKishuSagyo.nSagyoshaId = nSagyoshaId;
				} else
					cKishuSagyo.nSagyoshaId = -1;

				// ��Ə��擾
				pKishuCols = m_eYosoSagyoHyo.m_aKishuCols[1].aKishuCols.LookupElement(m_aKishu[nKishuId].sKishuName);
				m_eYosoSagyoHyo.GetElement(nRow, pKishuCols->nCol, sSagyojun);
				cKishuSagyo.nSagyojun = atoi(sSagyojun);

				// ���Y�@��t���O�ݒ�
				if (!sSagyojun.IsEmpty())
					cSubline.aSeisanKishuFlag[nKishuId] = 1;
			}
		}

		// �v�f��ƕʂ̃f�[�^��ݒ�
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColSagyoNaiyo), sYosoSagyoName);
		SYosoSagyo *pYosoSagyo = pYosoSagyogun->aYosoSagyo.AddElement();
		pYosoSagyo->sYosoSagyoName = sYosoSagyoName;
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColYosoSagyoNo), pYosoSagyo->sYosoSagyoNo);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColSagyoNo), pYosoSagyo->nSagyoNo);
		pYosoSagyo->fTime = fTime;
		pYosoSagyo->nRow = nRow;
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColSakujoFlag), pYosoSagyo->sDeleteFlag);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColTsuikaFlag), pYosoSagyo->sInsertFlag);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColEnzanShiki), pYosoSagyo->sEnzanShiki);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColS1), pYosoSagyo->fS1);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColS2), pYosoSagyo->fS2);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eCxxx), pYosoSagyo->fM);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColN), pYosoSagyo->fN);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColEnzangoSagyoJikan), pYosoSagyo->fEnzangoSagyoJikan);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColHassexxxndo), pYosoSagyo->nHassexxxndo);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKurikaeshiKaisu), pYosoSagyo->nKurikaeshiKaisu);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKoteibuHendobu), pYosoSagyo->sKoteibuHendobu);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColSagyoFlag), pYosoSagyo->sSagyoFlag);
		m_eYosoSagyoHyo.GetElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKijunKotei), pYosoSagyo->nKijunKotei);

		if (pYosoSagyo->sDeleteFlag == "3")
			pYosoSagyo->bKittingDelete = TRUE;
	}

	// ��Ǝ҂��ݒ肳��Ă���΁A���t�ς݃t���O�Z�b�g
	if (cSubline.aSagyosha.GetSize() != 0) {
		cSubline.bSagyoshaWaritsuke = TRUE;
// --Delete 2007/03/07 aizawa �s��(83)�i�ďC���j
//		RenumberSagyoshaId(nSublineId);
// --Add 2007/03/06 aizawa �s��(83)
//		RenameSagyoshaName(nSublineId);
// --Add
// --Delete
	}

	return TRUE;
}

// �v�f��ƕ\�f�[�^�`�F�b�N
BOOL CDataManager::CheckYosoSagyoHyo()
{
	CIntArrayEx aYosoSagyogunJunban;
	CArrayEx2 <CIntArrayEx, CIntArrayEx&> aYosoSagyoNo;
	CString sKey;
// Modify ... ( ADD )
	BOOL bOK011 = TRUE;
	BOOL bOK012 = TRUE;
// By Y.Itabashi (xxxxx) 2007.02.20

	// �@�퐔�擾
	int nKishuNum = GetKishuNum();

	// �T�u���C���ɂ�郋�[�v
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		// ����v�f��ƌQ���Ԃ̃`�F�b�N
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			aYosoSagyogunJunban.RemoveAll();
			int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
			for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
				SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];

				if (!cYosoSagyogun.CheckDeleted()) {
					SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
					if (cKishuSagyo.nSagyojun != 0) {
						if (aYosoSagyogunJunban.Find(cKishuSagyo.nSagyojun) != -1) {
// Modify ... ( CHANGE )
							CString sData = g_pSystem->m_cMessage.GetMessage( "E004015" );
							CString sLog;
							sLog.Format(sData, cSubline.sSublineName, m_aKishu[nKishuId].sKishuName, 
								cYosoSagyogun.sKoteiNo, cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
							sLog += "\n";
							bOK011 = FALSE;
							CExcelFile::ErrorFileLog(sLog);
//							g_pSystem->DispMessage("E004011");
//							return FALSE;
// By Y.Itabashi (xxxxx) 2007.02.20
						}
						aYosoSagyogunJunban.Add(cKishuSagyo.nSagyojun);
					}
				}
			}
		}

		// ����H�����ɓ���v�f��ƌQ�������݂��Ȃ����`�F�b�N
		aYosoSagyoNo.RemoveAll();
		int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];

			sKey = cYosoSagyogun.sKoteiName + cYosoSagyogun.sYosoSagyogunName;
			CIntArrayEx *pYosoSagyoNo = aYosoSagyoNo.LookupElement(sKey);
			if (pYosoSagyoNo == NULL)
				pYosoSagyoNo = aYosoSagyoNo.AddElement(sKey);

			int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
			for (int nYosoSagyoId = 0; nYosoSagyoId < nYosoSagyoNum; nYosoSagyoId++) {
				SYosoSagyo &cYosoSagyo = cYosoSagyogun.aYosoSagyo[nYosoSagyoId];

				if (cYosoSagyo.sDeleteFlag.IsEmpty()) {
					if (pYosoSagyoNo->Find(cYosoSagyo.nSagyoNo) != -1) {
// Modify ... ( CHANGE )
						CString sData = g_pSystem->m_cMessage.GetMessage( "E004016" );
						CString sLog;
						sLog.Format(sData, cSubline.sSublineName, cYosoSagyogun.sKoteiNo, 
							cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
						sLog += "\n";
						bOK012 = FALSE;
						CExcelFile::ErrorFileLog(sLog);
//						g_pSystem->DispMessage("E004012");
//						return FALSE;
// By Y.Itabashi (xxxxx) 2007.02.20
					}
					pYosoSagyoNo->Add(cYosoSagyo.nSagyoNo);
				}
			}
		}
	}

// Modify ... ( ADD )
	if( bOK011 == FALSE )
		g_pSystem->DispMessage("E004011");
	if( bOK012 == FALSE )
		g_pSystem->DispMessage("E004012");
	if( !bOK011 || !bOK012 )
		return FALSE;
// By Y.Itabashi (xxxxx) 2007.02.20
	
	return TRUE;
}

// �R�s�[�J�E���^�`�F�b�N
void CDataManager::CheckCopyCount(const CString &sName)
{
	if (sName.Left(5) == "COPY(") {
		int nPos = sName.Find(')', 5);
		if (nPos != -1) {
			int nCopyCount = atoi(sName.Mid(5, nPos - 5));
			if (nCopyCount != 0)
				m_nCopyCount = max(nCopyCount, m_nCopyCount);
		}
	}
}

// ���Y�䐔���t�@�C���ǂݍ���
BOOL CDataManager::ReadSeisanDaisu()
{
	if (!m_eSeisanDaisu.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eSeisanDaisu.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	CString sKishu;
	CString sYexxxonth;
	int nKishuId;
	int nSeidanDaisu;

	// �@�푶�݃`�F�b�N�t���O
	BOOL aKishiExist[MAX_KISHU];
	memset(aKishiExist, 0, sizeof(aKishiExist));

	int nUsedRows = m_eSeisanDaisu.GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		m_eSeisanDaisu.GetElement(nRow, m_eSeisanDaisu.eColKishu, sKishu);
		m_eSeisanDaisu.GetElement(nRow, m_eSeisanDaisu.eColNengetsu, sYexxxonth);

		SSeisanDaisu *pSeisanDaisu = m_aSeisanDaisu.LookupElement(sYexxxonth);
		if (pSeisanDaisu == NULL) {
			pSeisanDaisu = m_aSeisanDaisu.AddElement(sYexxxonth);
			pSeisanDaisu->sYexxxonth = sYexxxonth;
			pSeisanDaisu->aSeisanDaisu.SetSize(GetKishuNum());
		}

		if (m_aKishu.LookupElement(sKishu, nKishuId) != NULL) {
			m_eSeisanDaisu.GetElement(nRow, m_eSeisanDaisu.eColSeisanDaisu, nSeidanDaisu);
			pSeisanDaisu->aSeisanDaisu[nKishuId] = nSeidanDaisu;
			aKishiExist[nKishuId] = TRUE;
		}
	}

	// �@�푶�݃`�F�b�N
	int nKishuNum = m_aKishu.GetSize();
	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		if (!aKishiExist[nKishuId]) {
			if (g_pSystem->DispMessage("W004006") == IDCANCEL)
				return FALSE;
		}
	}

	// ���Y�䐔�ݒ�
	if (m_aSeisanDaisu.GetSize() == 1)
		SetSeisanDaisu(m_aSeisanDaisu[0], FALSE);

	return TRUE;
}

// �Ζ��̐����t�@�C���ǂݍ���
BOOL CDataManager::ReadKinmuTaisei()
{
	if (!m_eKinmuTaisei.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eKinmuTaisei.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	CString sSublineName;
	int nSublineId;

	// �T�u���C�����݃`�F�b�N�t���O
	BOOL aSublineExist[MAX_SUBLINE];
	memset(aSublineExist, 0, sizeof(aSublineExist));

	int nUsedRows = m_eKinmuTaisei.GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		m_eKinmuTaisei.GetElement(nRow, m_eKinmuTaisei.eColSublineName, sSublineName);
		SSubline *pSubline = m_aSubline.LookupElement(sSublineName, nSublineId);
		if (pSubline != NULL) {
			m_eKinmuTaisei.GetElement(nRow, m_eKinmuTaisei.eColGekkanKadoJikan, pSubline->cSeisanJoken.fKadoJikan);
			m_eKinmuTaisei.GetElement(nRow, m_eKinmuTaisei.eColKyoseiKudoFlag, pSubline->cSeisanJoken.bKyoseiKudo);
			m_eKinmuTaisei.GetElement(nRow, m_eKinmuTaisei.eColKadoritsu, pSubline->cSeisanJoken.fKadoRitsu);

			// �ڕW�^�N�g�ݒ�
			int nSeisanDaisu = GetSeisanDaisu(nSublineId);
// Modify ... ( CHANGE ) �ڕW�^�N�g�͉ғ������|����
			if (nSeisanDaisu != 0)
				pSubline->cSeisanJoken.fTact = (pSubline->cSeisanJoken.fKadoJikan * 60) * (pSubline->cSeisanJoken.fKadoRitsu / 100) / nSeisanDaisu;
// By Y.Itabashi (xxxxx) 2007.02.09


			aSublineExist[nSublineId] = TRUE;
		}
	}

	// �T�u���C�����݃`�F�b�N
	int nSublineNum = m_aSubline.GetSize();
	for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		if (!aSublineExist[nSublineId]) {
			if (g_pSystem->DispMessage("W004007") == IDCANCEL)
				return FALSE;
		}
	}

	return TRUE;
}

// ���Y�����ݒ�t�@�C���ǂݍ���
BOOL CDataManager::ReadSeisanJoken()
{
	if (!m_eSeisanJoken.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eSeisanJoken.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	CString sSublineName;

	int nUsedRows = m_eSeisanJoken.GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		m_eSeisanJoken.GetElement(nRow, m_eSeisanJoken.eColSublineName, sSublineName);
		SSubline *pSubline = m_aSubline.LookupElement(sSublineName);
		if (pSubline != NULL) {
			m_eSeisanJoken.GetElement(nRow, m_eSeisanJoken.eColKinmuTaisei, pSubline->cSeisanJoken.fKinmuTaisei);
			m_eSeisanJoken.GetElement(nRow, m_eSeisanJoken.eColKyusokuJikan, pSubline->cSeisanJoken.nKyusokuJikan);
			m_eSeisanJoken.GetElement(nRow, m_eSeisanJoken.eColKadoNissu, pSubline->cSeisanJoken.nKadoNissu);
			m_eSeisanJoken.GetElement(nRow, m_eSeisanJoken.eColKotaiKinmu, pSubline->cSeisanJoken.nKotaiKinmu);
			m_eSeisanJoken.GetElement(nRow, m_eSeisanJoken.eColJogen, pSubline->cSeisanJoken.fJogen);
			m_eSeisanJoken.GetElement(nRow, m_eSeisanJoken.eColKagen, pSubline->cSeisanJoken.fKagen);
			pSubline->cSeisanJoken.bSaved = TRUE;
		}
	}

	return TRUE;
}

// DSTR�Z�o�p���t�@�C���ǂݍ���
BOOL CDataManager::ReadDSTRSanshutsu()
{
	if (!m_eDSTRSanshutsu.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eDSTRSanshutsu.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	CString sKishuName;

// Modify ... ( ADD )
	m_aTotalDSTRSanshutsu = 0;
// By Y.Itabashi (xxxxx) 2007.02.14
	int nUsedRows = m_eDSTRSanshutsu.GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColKishuName, sKishuName);
// Modify ... ( ADD )
// Modify ... ( CHANGE )
		if( sKishuName == g_pSystem->GetDPEIndex( 4, 1, 1 ) ){
//		if( sKishuName == g_pSystem->m_aDPEInterface[4].aItem[1].aList[1] ){
// By Y.Itabashi (xxxxx) 2007.02.20
			m_aTotalDSTRSanshutsu = 1;
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColHyojunJikan, m_cTotalDSTRSanshutsu.fHyojunJikan);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColTeikiTenkenJikan, m_cTotalDSTRSanshutsu.fTeikiTenkenJikan);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColHinshitsuLoss, m_cTotalDSTRSanshutsu.fHinshitsuLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColSetsubiFuguaiLoss, m_cTotalDSTRSanshutsu.fSetsubiFuguaiLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColShujukuLoss, m_cTotalDSTRSanshutsu.fShujukuLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColLineTeishiLoss, m_cTotalDSTRSanshutsu.fLineTeishiLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColHenseiLoss, m_cTotalDSTRSanshutsu.fHenseiLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColHukugouNoritsuBun, m_cTotalDSTRSanshutsu.fHukugouNoritsuBun);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColKanriTeishiJikan, m_cTotalDSTRSanshutsu.fKanriTeishiJikan);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColDST, m_cTotalDSTRSanshutsu.fDST);
			continue;
		}
// By Y.Itabashi (xxxxx) 2007.02.14
		SKishu *pKishu = m_aKishu.LookupElement(sKishuName);
		if (pKishu != NULL) {
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColHyojunJikan, pKishu->cDSTRSanshutsu.fHyojunJikan);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColTeikiTenkenJikan, pKishu->cDSTRSanshutsu.fTeikiTenkenJikan);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColHinshitsuLoss, pKishu->cDSTRSanshutsu.fHinshitsuLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColSetsubiFuguaiLoss, pKishu->cDSTRSanshutsu.fSetsubiFuguaiLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColShujukuLoss, pKishu->cDSTRSanshutsu.fShujukuLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColLineTeishiLoss, pKishu->cDSTRSanshutsu.fLineTeishiLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColHenseiLoss, pKishu->cDSTRSanshutsu.fHenseiLoss);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColHukugouNoritsuBun, pKishu->cDSTRSanshutsu.fHukugouNoritsuBun);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColKanriTeishiJikan, pKishu->cDSTRSanshutsu.fKanriTeishiJikan);
			m_eDSTRSanshutsu.GetElement(nRow, m_eDSTRSanshutsu.eColDST, pKishu->cDSTRSanshutsu.fDST);
		}
	}

	return TRUE;
}

// ���i�\���H�������N���t�@�C���ǂݍ���
BOOL CDataManager::ReadBuhinhyoLink()
{
	if (!m_eBuhinhyoLink.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eBuhinhyoLink.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	CString sYosoSagyogunName;

	int nSheetNum = m_eBuhinhyoLink.GetSheetNum();
	for (int nSheetNo = 0; nSheetNo < nSheetNum; nSheetNo++) {
		m_eBuhinhyoLink.SetCurrentSheet(nSheetNo);
		int nUsedCols = m_eBuhinhyoLink.GetUsedCols();
		int nUsedRows = m_eBuhinhyoLink.GetUsedRows();
		for (int nRow = 3; nRow <= nUsedRows; nRow++) {
			SBuhinhyoLink *pBuhinhyoLink = m_aBuhinhyoLink.AddElement();
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColKatashiki, pBuhinhyoLink->sKatashiki);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColKishu, pBuhinhyoLink->sKishu);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColBlockNo, pBuhinhyoLink->sBlockNo);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColBuhinNo, pBuhinhyoLink->sBuhinNo);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColBuhinName, pBuhinhyoLink->sBuhinName);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColPC, pBuhinhyoLink->nPC);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColBoltFlag, pBuhinhyoLink->bBoltFlag);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColLinkSakiSublineName, pBuhinhyoLink->sSublineName);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColLinkSakiKoteiKigo, pBuhinhyoLink->sKoteiKigo);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColLinkSakiKoteiNo, pBuhinhyoLink->sKoteiNo);
			m_eBuhinhyoLink.GetElement(nRow, m_eBuhinhyoLink.eColLinkSakiKoteiName, pBuhinhyoLink->sKoteiName);
			ConvertHankaku(pBuhinhyoLink->sKoteiName);

			for (int nCol = m_eBuhinhyoLink.eColLinkSakiYosoSagyogunName; nCol <= nUsedCols; nCol++) {
				m_eBuhinhyoLink.GetElement(nRow, nCol, sYosoSagyogunName);
				ConvertHankaku(sYosoSagyogunName);
				if (!sYosoSagyogunName.IsEmpty())
					pBuhinhyoLink->aYosoSagyogunName.Add(sYosoSagyogunName);
			}

			SKishu *pKishu = m_aKishu.LookupElement(pBuhinhyoLink->sKishu);
			if (pKishu != NULL && pKishu->sKatashiki.IsEmpty())
				pKishu->sKatashiki = pBuhinhyoLink->sKatashiki;
		}
	}

	// �^���擾
	int nKishuNum = m_aKishu.GetSize();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		SKishu &cKishu = m_aKishu[nKishuId];
		if (!cKishu.sKatashiki.IsEmpty()) {
			SKatashiki *pKatashiki = m_aKatashiki.LookupElement(cKishu.sKatashiki);
			if (pKatashiki == NULL) {
				pKatashiki = m_aKatashiki.AddElement(cKishu.sKatashiki);
				pKatashiki->sKatashiki = cKishu.sKatashiki;

				CString sFileName;
				sFileName.Format("%s_%s", pKatashiki->sKatashiki, pKatashiki->eKatashikiKiseiMatrix.m_sFileName);
				pKatashiki->eKatashikiKiseiMatrix.m_sFileName = sFileName;
			}
		}
	}

	return TRUE;
}

// ���C���K���}�g���N�X�t�@�C���ǂݍ���
BOOL CDataManager::ReadLineKiseiMatrix()
{
	if (!m_eLineKiseiMatrix.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eLineKiseiMatrix.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	int nRow;
	CString sSublineName;
	CString sKoteiNo;
	CString sKoteiName;
	CString sYosoSagyogunName;
	CString sKisei;

	m_aLineKisei.RemoveAll();

	// ���C���K���}�g���N�X�擾
	int nUsedRows = m_eLineKiseiMatrix.GetUsedRows();
	for (nRow = 3; nRow <= nUsedRows; nRow += 2) {
		m_eLineKiseiMatrix.GetElement(nRow, m_eLineKiseiMatrix.eColSublineName, sSublineName);
		m_eLineKiseiMatrix.GetElement(nRow, m_eLineKiseiMatrix.eColKoteiNo, sKoteiNo);
		m_eLineKiseiMatrix.GetElement(nRow, m_eLineKiseiMatrix.eColKoteiName, sKoteiName);
		m_eLineKiseiMatrix.GetElement(nRow, m_eLineKiseiMatrix.eColYosoSagyogunName, sYosoSagyogunName);

		ConvertHankaku(sKoteiName);
		ConvertHankaku(sYosoSagyogunName);
		sYosoSagyogunName = RemoveUnderbarDigit(sYosoSagyogunName);

		SLineKisei *pLineKisei = m_aLineKisei.AddElement(sSublineName + sKoteiNo + sKoteiName + sYosoSagyogunName);
		pLineKisei->sSublineName = sSublineName;
		pLineKisei->sKoteiNo = sKoteiNo;
		pLineKisei->sKoteiName = sKoteiName;
		pLineKisei->sYosoSagyogunName = sYosoSagyogunName;

		// ENG�p���K��
		m_eLineKiseiMatrix.GetElement(nRow, m_eLineKiseiMatrix.eColEngGenjo, pLineKisei->sEngGenjo);
		pLineKisei->bEngSeiritsuFr = GetLineKiseiFlag(nRow, m_eLineKiseiMatrix.eColEngSeiritsuFr);
		pLineKisei->bEngSeiritsuRr = GetLineKiseiFlag(nRow, m_eLineKiseiMatrix.eColEngSeiritsuRr);
		pLineKisei->bEngToritsuFr = GetLineKiseiFlag(nRow, m_eLineKiseiMatrix.eColEngToritsuFr);
		pLineKisei->bEngToritsuRr = GetLineKiseiFlag(nRow, m_eLineKiseiMatrix.eColEngToritsuRr);
		pLineKisei->bEngChokuritsuTop = GetLineKiseiFlag(nRow, m_eLineKiseiMatrix.eColEngChokuritsuTop);
		pLineKisei->bEngChokuritsuBottom = GetLineKiseiFlag(nRow, m_eLineKiseiMatrix.eColEngChokuritsuBottom);

// --Add 2007/03/02 aizawa �s��D-147
		// �s�ʒu�ۑ�
		pLineKisei->nRow = nRow;
// --Add
	}

	return TRUE;
}

BOOL CDataManager::GetLineKiseiFlag(int nRow, int nCol)
{
	CString sEngShisei;

	m_eLineKiseiMatrix.GetElement(nRow, nCol, sEngShisei);
	return sEngShisei == g_pConst->m_sEngShiseiFlag;
}

// �^���K���}�g���N�X�t�@�C���ǂݍ���
BOOL CDataManager::ReadKatashikiKiseiMatrix()
{
	BOOL bReadOnly = g_pSystem->m_bReadOnly;

	m_aKatashikiKisei.RemoveAll();

	// �{���T�u���C�����N���A
	int nKishuNum = m_aKishu.GetSize();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
		m_aKishu[nKishuId].aHonryuSublineId.RemoveAll();

	int nKatashikiNum = m_aKatashiki.GetSize();
	for (int i = 0; i < nKatashikiNum; i++) {
		SKatashiki &cKatashiki = m_aKatashiki[i];

		CopyFromServer(g_pSystem->m_cIniSystem.m_sEngMatrixPath, cKatashiki.eKatashikiKiseiMatrix.m_sFileName);
		if (!cKatashiki.eKatashikiKiseiMatrix.AccessCheck(g_pSystem->m_cIniUser.m_sDataPath))
			return FALSE;
//#ifndef _DEBUG
		if (!ReadKatashikiKiseiMatrixSub1(m_aKatashiki[i].eKatashikiKiseiMatrix))
			return FALSE;
//#endif
	}

	// �K�����e�`�F�b�N
	if (!CheckKatashikiKiseiMatrix()) {
		g_pSystem->DispMessage("E004010");
		return FALSE;
	}

	if (!bReadOnly && g_pSystem->m_bReadOnly) {
		if (g_pSystem->DispMessage("W004004") == IDCANCEL)
			return FALSE;
	}

	return TRUE;
}

BOOL CDataManager::ReadKatashikiKiseiMatrixSub1(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix)
{
	if (!cExcelKatashikiKiseiMatrix.ReadFile(1))
		return FALSE;

	// �{���T�u���C���擾
	CString sSublineName, sKishuName;
	cExcelKatashikiKiseiMatrix.GetElement(2, 3, sSublineName);
	cExcelKatashikiKiseiMatrix.GetElement(2, 4, sKishuName);
	SKishu *pKishu = m_aKishu.LookupElement(sKishuName);
	if (pKishu != NULL) {
		CStringArrayEx aSublineName;
		aSublineName.Split("/", sSublineName);

		int nSize = aSublineName.GetSize();
		for (int i = 0; i < nSize; i++) {
			int nSublineId;
			if (m_aSubline.LookupElement(aSublineName[i], nSublineId) != NULL)
				pKishu->aHonryuSublineId.Add(nSublineId);
		}
	}

	int nSheetNum = cExcelKatashikiKiseiMatrix.GetSheetNum();
	for (int nSheet = 1; nSheet < nSheetNum; nSheet++) {
		cExcelKatashikiKiseiMatrix.SetCurrentSheet(nSheet);

// Modify ... ( CHANGE )
		if( ReadKatashikiKiseiMatrixSub2(cExcelKatashikiKiseiMatrix) == FALSE )
			return FALSE;
//		ReadKatashikiKiseiMatrixSub2(cExcelKatashikiKiseiMatrix);
// By Y.Itabashi (xxxxx) 2007.02.20
	}

	return TRUE;
}

// Modify ... ( CHANGE )
BOOL CDataManager::ReadKatashikiKiseiMatrixSub2(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix)
//void CDataManager::ReadKatashikiKiseiMatrixSub2(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix)
{
	CString sMatrixNo;

	int nUsedRows = cExcelKatashikiKiseiMatrix.GetUsedRows();
	for (int nRow = 1; nRow < nUsedRows; nRow++) {
		cExcelKatashikiKiseiMatrix.GetElement(nRow, 1, sMatrixNo);
		if (!sMatrixNo.IsEmpty() && sMatrixNo.Left(1) == "#" && atoi(sMatrixNo.Mid(1)) > 0){
			if( ReadKatashikiKiseiMatrixSub3(cExcelKatashikiKiseiMatrix, nRow) == FALSE )
				return FALSE;
		}
	}

	return TRUE;
}
// By Y.Itabashi (xxxxx) 2007.02.20

// Modify ... ( CHANGE )
BOOL CDataManager::ReadKatashikiKiseiMatrixSub3(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix, int nRow)
//void CDataManager::ReadKatashikiKiseiMatrixSub3(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix, int nRow)
// By Y.Itabashi (xxxxx) 2007.02.20
{
	int nCol;
	CString sKishuName;
	CString sSublineName;
	CString sKoteiNo;
	CString sKoteiName;
	CString sYosoSagyogunName;
	CString sNo;
	CString sKisei;
	CString sKeyRow;
	CString sKeyCol;
	long nUsedRows;
	long nUsedCols;

	// �s���A�񐔎擾
	nUsedRows = cExcelKatashikiKiseiMatrix.GetUsedRows();
	nUsedCols = cExcelKatashikiKiseiMatrix.GetUsedCols();

// Modify ... ( CHANGE )

	// KEY���ڂ̑��݃`�F�b�N
	CString sHeaderKotei = g_pSystem->GetDPEIndex( 7, 16, 0 );
	CString sHeaderYosoSagyogun = g_pSystem->GetDPEIndex( 7, 17, 0 );
	cExcelKatashikiKiseiMatrix.GetElement(nRow, cExcelKatashikiKiseiMatrix.eColNo, sNo);
	// ��̃`�F�b�N
	cExcelKatashikiKiseiMatrix.GetElement(nRow+cExcelKatashikiKiseiMatrix.eColYosoSagyogunName,
		cExcelKatashikiKiseiMatrix.eRowKoteiName+1, sKoteiName);
	if( sHeaderKotei != sKoteiName ){
		g_pSystem->DispMessage( "E004003", cExcelKatashikiKiseiMatrix.m_sFileName, sHeaderKotei );
		return FALSE;
	}

	cExcelKatashikiKiseiMatrix.GetElement(nRow+cExcelKatashikiKiseiMatrix.eColYosoSagyogunName,
		cExcelKatashikiKiseiMatrix.eRowYosoSagyogunName+1, sYosoSagyogunName);
	if( sHeaderYosoSagyogun != sYosoSagyogunName ){
		g_pSystem->DispMessage( "E004003", cExcelKatashikiKiseiMatrix.m_sFileName, sHeaderYosoSagyogun );
		return FALSE;
	}

	// �s�̃`�F�b�N
	cExcelKatashikiKiseiMatrix.GetElement(nRow+cExcelKatashikiKiseiMatrix.eRowKoteiName,
		cExcelKatashikiKiseiMatrix.eColReserve2, sKoteiName);
	if( sHeaderKotei != sKoteiName ){
		g_pSystem->DispMessage( "E004003", cExcelKatashikiKiseiMatrix.m_sFileName, sHeaderKotei );
		return FALSE;
	}

	cExcelKatashikiKiseiMatrix.GetElement(nRow+cExcelKatashikiKiseiMatrix.eRowYosoSagyogunName,
		cExcelKatashikiKiseiMatrix.eColReserve2, sYosoSagyogunName);
	if( sHeaderYosoSagyogun != sYosoSagyogunName ){
		g_pSystem->DispMessage( "E004003", cExcelKatashikiKiseiMatrix.m_sFileName, sHeaderYosoSagyogun );
		return FALSE;
	}
// By Y.Itabashi (xxxxx) 2007.02.20

	// ����擾
	CColInfoArray aColInfo;
	for (nCol = cExcelKatashikiKiseiMatrix.eColKisei; nCol <= nUsedCols; nCol++) {
		cExcelKatashikiKiseiMatrix.GetElement(nRow + cExcelKatashikiKiseiMatrix.eRowShashuName, nCol, sKishuName);
		cExcelKatashikiKiseiMatrix.GetElement(nRow + cExcelKatashikiKiseiMatrix.eRowSublineName, nCol, sSublineName);
		cExcelKatashikiKiseiMatrix.GetElement(nRow + cExcelKatashikiKiseiMatrix.eRowKoteiNo, nCol, sKoteiNo);
		cExcelKatashikiKiseiMatrix.GetElement(nRow + cExcelKatashikiKiseiMatrix.eRowKoteiName, nCol, sKoteiName);
		cExcelKatashikiKiseiMatrix.GetElement(nRow + cExcelKatashikiKiseiMatrix.eRowYosoSagyogunName, nCol, sYosoSagyogunName);

		ConvertHankaku(sKoteiName);
		ConvertHankaku(sYosoSagyogunName);
		sYosoSagyogunName = RemoveUnderbarDigit(sYosoSagyogunName);

		if (!sSublineName.IsEmpty()){
// Modify ... ( CHANGE )
			// �K���̎擾�̍ہA�H���ԍ��͖�������
			sKeyCol = sKishuName + sSublineName + sKoteiName + sYosoSagyogunName;
//			sKeyCol = sKishuName + sSublineName + sKoteiNo + sKoteiName + sYosoSagyogunName;
// By Y.Itabashi (xxxxx) 2007.02.10
			if (aColInfo.LookupElement(sKeyCol) == NULL) {
				SColInfo *pColInfo = aColInfo.AddElement(sKeyCol);
				pColInfo->nCol = nCol;
				pColInfo->sKishuName = sKishuName;
				pColInfo->sSublineName = sSublineName;
				pColInfo->sKoteiNo = sKoteiNo;
				pColInfo->sKoteiName = sKoteiName;
				pColInfo->sYosoSagyogunName = sYosoSagyogunName;
			}
		}
	}

	int nColInfo = aColInfo.GetSize();

	// �^���K���}�g���N�X�擾
	for (nRow += cExcelKatashikiKiseiMatrix.eRowKisei; nRow <= nUsedRows; nRow += 2) {
		cExcelKatashikiKiseiMatrix.GetElement(nRow, cExcelKatashikiKiseiMatrix.eColNo, sNo);
		if (sNo.IsEmpty())
			break;

		cExcelKatashikiKiseiMatrix.GetElement(nRow, cExcelKatashikiKiseiMatrix.eColShashuName, sKishuName);
		cExcelKatashikiKiseiMatrix.GetElement(nRow, cExcelKatashikiKiseiMatrix.eColSublineName, sSublineName);
		cExcelKatashikiKiseiMatrix.GetElement(nRow, cExcelKatashikiKiseiMatrix.eColKoteiNo, sKoteiNo);
		cExcelKatashikiKiseiMatrix.GetElement(nRow, cExcelKatashikiKiseiMatrix.eColKoteiName, sKoteiName);
		cExcelKatashikiKiseiMatrix.GetElement(nRow, cExcelKatashikiKiseiMatrix.eColYosoSagyogunName, sYosoSagyogunName);

		ConvertHankaku(sKoteiName);
		ConvertHankaku(sYosoSagyogunName);
		sYosoSagyogunName = RemoveUnderbarDigit(sYosoSagyogunName);

// Modify ... ( CHANGE )
		// �K���̎擾�̍ہA�H���ԍ��͖�������
		sKeyRow = sKishuName + sSublineName + sKoteiName + sYosoSagyogunName;
//		sKeyRow = sKishuName + sSublineName + sKoteiNo + sKoteiName + sYosoSagyogunName;
// By Y.Itabashi (xxxxx) 2007.02.10
		SKatashikiKisei *pKatashikiKisei = m_aKatashikiKisei.LookupElement(sKeyRow);
		if (pKatashikiKisei == NULL) {
			pKatashikiKisei = m_aKatashikiKisei.AddElement(sKeyRow);
			pKatashikiKisei->sKishuName = sKishuName;
			pKatashikiKisei->sSublineName = sSublineName;
			pKatashikiKisei->sKoteiNo = sKoteiNo;
			pKatashikiKisei->sKoteiName = sKoteiName;
			pKatashikiKisei->sYosoSagyogunName = sYosoSagyogunName;
		}

		// ��̃��[�v
		for (int i = 0; i < nColInfo; i++) {
			SColInfo &cColInfo = aColInfo[i];
			cExcelKatashikiKiseiMatrix.GetElement(nRow, cColInfo.nCol, sKisei);
			if (sKisei == g_pConst->m_sJoryu || sKisei == g_pConst->m_sKaryu) {
// Modify ... ( CHANGE )
				// �K���̎擾�̍ہA�H���ԍ��͖�������
				sKeyCol = cColInfo.sKishuName + cColInfo.sSublineName + cColInfo.sKoteiName + cColInfo.sYosoSagyogunName;
//				sKeyCol = cColInfo.sKishuName + cColInfo.sSublineName + cColInfo.sKoteiNo + cColInfo.sKoteiName + cColInfo.sYosoSagyogunName;
// By Y.Itabashi (xxxxx) 2007.02.10
				SKatashikiKiseiCol *pKatashikiKiseiCol = pKatashikiKisei->aKatashikiKiseiCol.LookupElement(sKeyCol);
				if (pKatashikiKiseiCol == NULL) {
					pKatashikiKiseiCol = pKatashikiKisei->aKatashikiKiseiCol.AddElement(sKeyCol);
					pKatashikiKiseiCol->sKishuName = cColInfo.sKishuName;
					pKatashikiKiseiCol->sSublineName = cColInfo.sSublineName;
					pKatashikiKiseiCol->sKoteiNo = cColInfo.sKoteiNo;
					pKatashikiKiseiCol->sKoteiName = cColInfo.sKoteiName;
					pKatashikiKiseiCol->sYosoSagyogunName = cColInfo.sYosoSagyogunName;
					pKatashikiKiseiCol->sKisei = sKisei;
					cExcelKatashikiKiseiMatrix.GetElement(nRow + 1, cColInfo.nCol, pKatashikiKiseiCol->sRiyu);
				}
			}
		}
	}

	return TRUE;
}

BOOL CDataManager::CheckKatashikiKiseiMatrix()
{
	int i, j;
	CString sKeyRow;
	CString sKeyCol;
	BOOL bOK = TRUE;

	// �Ώۈʒu�ɔ��΂̋K����ݒ�
	int nRowSize = m_aKatashikiKisei.GetSize();
	for (i = 0; i < nRowSize; i++) {
		SKatashikiKisei &cKatashikiKisei = m_aKatashikiKisei[i];
// Modify ... ( CHANGE )
		sKeyRow = cKatashikiKisei.sKishuName + cKatashikiKisei.sSublineName + cKatashikiKisei.sKoteiName + cKatashikiKisei.sYosoSagyogunName;
//		sKeyRow = cKatashikiKisei.sKishuName + cKatashikiKisei.sSublineName + cKatashikiKisei.sKoteiNo + cKatashikiKisei.sKoteiName + cKatashikiKisei.sYosoSagyogunName;
// By Y.Itabashi (xxxxx) 2007.02.12
		int nColSize = cKatashikiKisei.aKatashikiKiseiCol.GetSize();
		for (j = 0; j < nColSize; j++) {
			SKatashikiKiseiCol &cKatashikiKiseiCol = cKatashikiKisei.aKatashikiKiseiCol[j];
// Modify ... ( CHANGE )
			sKeyCol = cKatashikiKiseiCol.sKishuName + cKatashikiKiseiCol.sSublineName + cKatashikiKiseiCol.sKoteiName + cKatashikiKiseiCol.sYosoSagyogunName;
//			sKeyCol = cKatashikiKiseiCol.sKishuName + cKatashikiKiseiCol.sSublineName + cKatashikiKiseiCol.sKoteiNo + cKatashikiKiseiCol.sKoteiName + cKatashikiKiseiCol.sYosoSagyogunName;
// By Y.Itabashi (xxxxx) 2007.02.12
			SKatashikiKisei *pKatashikiKisei = m_aKatashikiKisei.LookupElement(sKeyCol);
			if (pKatashikiKisei != NULL) {
				SKatashikiKiseiCol *pKatashikiKiseiCol = pKatashikiKisei->aKatashikiKiseiCol.LookupElement(sKeyRow);
				if (pKatashikiKiseiCol == NULL) {
					pKatashikiKiseiCol = pKatashikiKisei->aKatashikiKiseiCol.AddElement(sKeyRow);
					pKatashikiKiseiCol->sKishuName = cKatashikiKisei.sKishuName;
					pKatashikiKiseiCol->sSublineName = cKatashikiKisei.sSublineName;
					pKatashikiKiseiCol->sKoteiNo = cKatashikiKisei.sKoteiNo;
					pKatashikiKiseiCol->sKoteiName = cKatashikiKisei.sKoteiName;
					pKatashikiKiseiCol->sYosoSagyogunName = cKatashikiKisei.sYosoSagyogunName;
					pKatashikiKiseiCol->sKisei = (cKatashikiKiseiCol.sKisei == g_pConst->m_sJoryu) ? g_pConst->m_sKaryu : g_pConst->m_sJoryu;
					pKatashikiKiseiCol->sRiyu = cKatashikiKiseiCol.sRiyu;
				} else {
					// �Ώ̈ʒu�̋K���������Ȃ�G���[
					if (pKatashikiKiseiCol->sKisei == cKatashikiKiseiCol.sKisei) {
						CString sLog;
						sLog.Format("e-mtx.xls\tsKeyRow=%s\tsKeyCol=%s\t%s\n", sKeyRow, sKeyCol, cKatashikiKiseiCol.sKisei);
// Modify ... ( ADD )
						SKatashikiError cKatashikiError;
						cKatashikiError.sKishuName = cKatashikiKisei.sKishuName;
						cKatashikiError.sSublineFrom = cKatashikiKisei.sSublineName;
						cKatashikiError.sKoteiNoFrom = cKatashikiKisei.sKoteiNo;
						cKatashikiError.sKoteiNameFrom = cKatashikiKisei.sKoteiName;
						cKatashikiError.sYosoSagyogunNameFrom = cKatashikiKisei.sYosoSagyogunName;
						cKatashikiError.sSublineTo = cKatashikiKiseiCol.sSublineName;
						cKatashikiError.sKoteiNoTo = cKatashikiKiseiCol.sKoteiNo;
						cKatashikiError.sKoteiNameTo = cKatashikiKiseiCol.sKoteiName;
						cKatashikiError.sYosoSagyogunNameTo = cKatashikiKiseiCol.sYosoSagyogunName;
						cKatashikiError.sRiyuNo = "E004014";

						m_aKatashikiError.Add( cKatashikiError );
// By Y.Itabashi (xxxxx) 2007.02.14
						CExcelFile::ErrorFileLog(sLog);
						bOK = FALSE;
					}
				}
			}
		}
	}

// Modify ... ( ADD )
	if( bOK == FALSE ){
		// �^���K���ᔽ�t�@�C��
		SaveKatashikiError(g_pSystem->m_cIniUser.m_sDataPath, "E002002");
	}
// By Y.Itabashi (xxxxx) 2007.02.14

	return bOK;
}

// �L�b�g���e���v���[�g�t�@�C���ǂݍ���
BOOL CDataManager::ReadKittingTemplate()
{
	if (!m_eKittingTemplate.ReadFile())
		return FALSE;

	// �v�f��ƕ\�Ɠ����񐔂łȂ���΃G���[
	if (m_eKittingTemplate.GetUsedCols() != m_eYosoSagyoHyo.GetUsedCols()) {
		g_pSystem->DispMessage("E004003", m_eKittingTemplate.GetFileName());
		return FALSE;
	}

	// �f�[�^�`�F�b�N
	if (!m_eKittingTemplate.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	return TRUE;
}

// ���i�L�b�g�����t�@�C���ǂݍ���
BOOL CDataManager::ReadBuhinKitting()
{
	if (!m_eBuhinKitting.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eBuhinKitting.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	CString sSublineName;
	CString sBuhinNo;

	int nUsedRows = m_eBuhinKitting.GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		m_eBuhinKitting.GetElement(nRow, m_eBuhinKitting.eColSublineName, sSublineName);
		SSubline *pSubline = m_aSubline.LookupElement(sSublineName);
		if (pSubline != NULL) {
			m_eBuhinKitting.GetElement(nRow, m_eBuhinKitting.eColBuhinNo, sBuhinNo);
			SBuhinKitting *pBuhinKitting = pSubline->aBuhinKitting.AddElement(sBuhinNo);
			m_eBuhinKitting.GetElement(nRow, m_eBuhinKitting.eColBlock, pBuhinKitting->sBlockNo);
			m_eBuhinKitting.GetElement(nRow, m_eBuhinKitting.eColBuhinNo, pBuhinKitting->sBuhinNo);
			m_eBuhinKitting.GetElement(nRow, m_eBuhinKitting.eColName, pBuhinKitting->sBuhinName);
			m_eBuhinKitting.GetElement(nRow, m_eBuhinKitting.eColKitingFlag, pBuhinKitting->bKittingFlag);
		}
	}

	// �v�f��ƕ\�ɃL�b�g���ɂ��폜�t���O���Z�b�g
	SetKittingDeleteFlag();

	return TRUE;
}

// ���ʍH�����t�@�C���ǂݍ���
BOOL CDataManager::ReadKyotsuKotei()
{
	if (!m_eKyotsuKotei.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eKyotsuKotei.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	CString sSublineName;
	CString sKoteiName;
	CString sYosoSagyogunName;
	int nKishuId;

	int nUsedRows = m_eKyotsuKotei.GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		m_eKyotsuKotei.GetElement(nRow, m_eKyotsuKotei.eColSublineName, sSublineName);
		SSubline *pSubline = m_aSubline.LookupElement(sSublineName);
		if (pSubline != NULL) {
			m_eKyotsuKotei.GetElement(nRow, m_eKyotsuKotei.eColKoteiName, sKoteiName);
			m_eKyotsuKotei.GetElement(nRow, m_eKyotsuKotei.eColYosoSagyogunName, sYosoSagyogunName);

			ConvertHankaku(sKoteiName);
			ConvertHankaku(sYosoSagyogunName);

			SYosoSagyogun *pYosoSagyogun = pSubline->aYosoSagyogun.LookupElement(sKoteiName + sYosoSagyogunName);
			if (pYosoSagyogun != NULL) {
				int nKishuCols = m_eKyotsuKotei.m_aKishuCols[0].aKishuCols.GetSize();
				for (int i = 0; i < nKishuCols; i++) {
					if (m_aKishu.LookupElement(m_eKyotsuKotei.m_aKishuCols[0].aKishuCols[i].sKishuName, nKishuId) != NULL)
						m_eKyotsuKotei.GetElement(nRow, m_eKyotsuKotei.m_aKishuCols[0].aKishuCols[i].nCol, pYosoSagyogun->aKishuSagyo[nKishuId].nGroupNo);
				}
			}
		}
	}

	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SetDefaultGroupNo(nSublineId);

// --Add 2007/03/07 aizawa �s��(83)�i�ďC���j
		if (m_aSubline[nSublineId].bSagyoshaWaritsuke) {
			RenumberSagyoshaId(nSublineId);
			RenameSagyoshaName(nSublineId);
		}
// --Add
	}

	return TRUE;
}

// �L�b�g�ΏۊO���i���X�g�t�@�C���ǂݍ���
BOOL CDataManager::ReadKittingTaishogai()
{
	if (!m_eKittingTaishogai.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!m_eKittingTaishogai.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	CString sBuhinNo;

	int nUsedRows = m_eKittingTaishogai.GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		m_eKittingTaishogai.GetElement(nRow, m_eKittingTaishogai.eColTaishoGaiBuhinNo, sBuhinNo);
		SKittingTaishogai *pKittingTaishogai = m_aKittingTaishogai.LookupElement(sBuhinNo);
		if (pKittingTaishogai == NULL) {
			pKittingTaishogai = m_aKittingTaishogai.AddElement(sBuhinNo);
			pKittingTaishogai->sBuhinNo = sBuhinNo;
		}
	}

	return TRUE;
}

// �v�f��ƃe���v���[�g�ǂݍ���
BOOL CDataManager::ReadYosoSagyoTemplate()
{
	CExcelYosoSagyoTemplate eYosoSagyoTemplate;

	eYosoSagyoTemplate.m_sPathName = g_pSystem->m_cIniSystem.m_sElementTableFilePath;
	if (!eYosoSagyoTemplate.ReadFile())
		return FALSE;

	// �f�[�^�`�F�b�N
	if (!eYosoSagyoTemplate.FileDataCheck()) {
		if (g_pSystem->DispMessage("W004005") == IDCANCEL)
			return FALSE;
	}

	CString sYosoSagyoNo;

	int nUsedRows = eYosoSagyoTemplate.GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColYosoSagyoNo, sYosoSagyoNo);
		SYosoSagyoTemplate *pYosoSagyoTemplate = m_aYosoSagyoTemplate.AddElement(sYosoSagyoNo);

		pYosoSagyoTemplate->sYosoSagyoNo = sYosoSagyoNo;
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColSagyoNaiyo, pYosoSagyoTemplate->sSagyoNaiyo);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColEnzanShiki, pYosoSagyoTemplate->sEnzanShiki);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColS1, pYosoSagyoTemplate->fS1);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColS2, pYosoSagyoTemplate->fS2);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eCxxx, pYosoSagyoTemplate->fM);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColN, pYosoSagyoTemplate->fN);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColEnzangoSagyoJikan, pYosoSagyoTemplate->fEnzangoSagyoJikan);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColHassexxxndo, pYosoSagyoTemplate->nHassexxxndo);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColKurikaeshiKaisu, pYosoSagyoTemplate->nKurikaeshiKaisu);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColDaiatariYosoSagyoJikan, pYosoSagyoTemplate->fDaiatariSagyoJikan);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColComment, pYosoSagyoTemplate->sComment);
		eYosoSagyoTemplate.GetElement(nRow, eYosoSagyoTemplate.eColSagyoFlag, pYosoSagyoTemplate->sSagyoFlag);
	}

	return TRUE;
}

// �^���K���ᔽ�t�@�C���ǂݍ���
BOOL CDataManager::ReadKatashikiError()
{
	m_eKatashikiError.m_sFolderName = g_pSystem->m_cIniUser.m_sDataPath;

	if (!m_eKatashikiError.ReadFile())
		return TRUE;

	CString sSublineName;

	for( int nKishuID = 0; nKishuID < m_aKishu.GetSize(); nKishuID++ ){

		CString sKatashiki = m_aKishu[nKishuID].sKatashiki;
		m_eKatashikiError.SetCurrentSheet( sKatashiki );

		int nUsedRows = m_eKatashikiError.GetUsedRows();
		for (int nRow = 3; nRow <= nUsedRows; nRow++) {
			SKatashikiError cError;

			cError.nKishuID = nKishuID;
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColDay, cError.sDay );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColTime, cError.sTime );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColSublineFrom, cError.sSublineFrom );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColKoteiNoFrom, cError.sKoteiNoFrom );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColKoteiNameFrom, cError.sKoteiNameFrom );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColYosoSagyogunNameFrom, cError.sYosoSagyogunNameFrom );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColSublineTo, cError.sSublineTo );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColKoteiNoTo, cError.sKoteiNoTo );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColKoteiNameTo, cError.sKoteiNameTo );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColYosoSagyogunNameTo, cError.sYosoSagyogunNameTo );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColSublineNg, cError.sSublineNg );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColKoteiNoNg, cError.sKoteiNoNg );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColKoteiNameNg, cError.sKoteiNameNg );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColYosoSagyogunNameNg, cError.sYosoSagyogunNameNg );
			m_eKatashikiError.GetElement( nRow, m_eKatashikiError.eColRiyu, cError.sRiyuMsg );

			m_aKatashikiError.Add( cError );
		}
	}

// Modify ... ( ADD )
	m_eKatashikiError.nErrorDataSize = m_aKatashikiError.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.19

	return TRUE;
}

// ���C���K���ᔽ�t�@�C���ǂݍ���
BOOL CDataManager::ReadLineError()
{
	m_eLineError.m_sFolderName = g_pSystem->m_cIniUser.m_sDataPath;

	if (!m_eLineError.ReadFile())
		return TRUE;

	CString sSublineName;

	for( int nKishuID = 0; nKishuID < m_aKishu.GetSize(); nKishuID++ ){

		CString sKatashiki = m_aKishu[nKishuID].sKatashiki;
		m_eLineError.SetCurrentSheet( sKatashiki );

		int nUsedRows = m_eLineError.GetUsedRows();
		for (int nRow = 3; nRow <= nUsedRows; nRow++) {
			SLineError cError;

			cError.nKishuID = nKishuID;
			m_eLineError.GetElement( nRow, m_eLineError.eColDay, cError.sDay );
			m_eLineError.GetElement( nRow, m_eLineError.eColTime, cError.sTime );
			m_eLineError.GetElement( nRow, m_eLineError.eColSublineFrom, cError.sSublineFrom );
			m_eLineError.GetElement( nRow, m_eLineError.eColKoteiNoFrom, cError.sKoteiNoFrom );
			m_eLineError.GetElement( nRow, m_eLineError.eColKoteiNameFrom, cError.sKoteiNameFrom );
			m_eLineError.GetElement( nRow, m_eLineError.eColYosoSagyogunNameFrom, cError.sYosoSagyogunNameFrom );
			m_eLineError.GetElement( nRow, m_eLineError.eColSublineTo, cError.sSublineTo );
			m_eLineError.GetElement( nRow, m_eLineError.eColKoteiNoToB, cError.sKoteiNoToB );
			m_eLineError.GetElement( nRow, m_eLineError.eColKoteiNameToB, cError.sKoteiNameToB );
			m_eLineError.GetElement( nRow, m_eLineError.eColYosoSagyogunNameToB, cError.sYosoSagyogunNameToB );
			m_eLineError.GetElement( nRow, m_eLineError.eColKoteiNoToA, cError.sKoteiNoToA );
			m_eLineError.GetElement( nRow, m_eLineError.eColKoteiNameToA, cError.sKoteiNameToA );
			m_eLineError.GetElement( nRow, m_eLineError.eColYosoSagyogunNameToA, cError.sYosoSagyogunNameToA );
			m_eLineError.GetElement( nRow, m_eLineError.eColRiyu, cError.sRiyuMsg );

			m_aLineError.Add( cError );
		}
	}

// Modify ... ( ADD )
	m_eLineError.nErrorDataSize = m_aLineError.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.19

	return TRUE;
}

// ���O��t���ĕۑ�
BOOL CDataManager::SaveAsFile(LPCTSTR pFolderName)
{
// Modify ... ( CHANGE )
	g_pSystem->SetProgressBar(16);
//	g_pSystem->SetProgressBar(14);
// By Y.Itabashi (xxxxx) 2007.02.15

	BOOL bResult = SaveAsFileSub(pFolderName);

	g_pSystem->ClearProgressBar();

	return bResult;
}

BOOL CDataManager::SaveAsFileSub(LPCTSTR pFolderName)
{
	CWaitCursor cWaitCursor;

	// �ۑ����Ԑݒ�
	g_pSystem->m_cSaveTime = CTime::GetCurrentTime();

	// �v���W�F�N�g�t�H���_�쐬
	if (!MakeFolder(pFolderName))
		return FALSE;

	// �t�@�C���ۑ�
	if (!SaveProjectFile(pFolderName, "E002002"))
		return FALSE;

	// �t�@�C���R�s�[
	if (!CopyProjectFile(pFolderName))
		return FALSE;

	g_pSystem->m_cIniUser.m_sDataPath = pFolderName;

	// ���[�UIni�t�@�C��
	if (!g_pSystem->SaveUserInfo())
		return FALSE;

	// �ۑ���̃t�@�C�������b�N����
	CheckProjectFile();

	m_bUpdate = FALSE;

	return TRUE;
}

// �㏑���ۑ�
BOOL CDataManager::SaveFile()
{
// Modify ... ( CHANGE )
// --Change 2007/03/02 aizawa �s��D-147
//	g_pSystem->SetProgressBar(9);
	g_pSystem->SetProgressBar(10);
// --Change
//	g_pSystem->SetProgressBar(7);
// By Y.Itabashi (xxxxx) 2007.02.15

	BOOL bResult = SaveFileSub();
	

	g_pSystem->ClearProgressBar();

	return bResult;
}

BOOL CDataManager::SaveFileSub()
{
	CWaitCursor cWaitCursor;

	// �t�@�C���ۑ�
	if (!SaveProjectFile(NULL, "E003002"))
		return FALSE;

	// ���[�UIni�t�@�C��
	if (!g_pSystem->SaveUserInfo())
		return FALSE;

	m_bUpdate = FALSE;

	return TRUE;
}

// �t�@�C���ۑ�
BOOL CDataManager::SaveProjectFile(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	// ���Y�䐔���t�@�C��
	DispSaveProcess(m_eSeisanDaisu);
	if (!SaveSeisanDaisu(pFolderName, pErrCode))
		return FALSE;

	// �Ζ��̐����t�@�C��
	DispSaveProcess(m_eKinmuTaisei);
	if (!SaveKinmuTaisei(pFolderName, pErrCode))
		return FALSE;

	// DSTR�Z�o�p���t�@�C��
	DispSaveProcess(m_eDSTRSanshutsu);
	if (!SaveDSTRSanshutsu(pFolderName, pErrCode))
		return FALSE;

	// �v�f��ƕ\�t�@�C��
	DispSaveProcess(m_eYosoSagyoHyo);
	if (!SaveYosoSagyoHyo(pFolderName, pErrCode))
		return FALSE;

	// ���Y�����ݒ�t�@�C��
	DispSaveProcess(m_eSeisanJoken);
	if (!SaveSeisanJoken(pFolderName, pErrCode))
		return FALSE;

	// �L�b�g�����i�t�@�C��
	DispSaveProcess(m_eBuhinKitting);
	if (!SaveBuhinKitting(pFolderName, pErrCode))
		return FALSE;

	// ���ʍH���ݒ�t�@�C��
	DispSaveProcess(m_eKyotsuKotei);
	if (!SaveKyotsuKotei(pFolderName, pErrCode))
		return FALSE;

// Modify ... ( ADD )
	// �^���K���ᔽ�t�@�C��
	DispSaveProcess(m_eKatashikiError);
	if (!SaveKatashikiError(pFolderName, "E002002"))
		return FALSE;

	// ���C���K���ᔽ�t�@�C��
	DispSaveProcess(m_eLineError);
	if (!SaveLineError(pFolderName, "E002002"))
		return FALSE;
// By Y.Itabashi (xxxxx) 2007.02.14

// --Add 2007/03/02 aizawa �s��D-147
	// ���C���K���}�g���N�X�t�@�C��
	DispSaveProcess(m_eLineKiseiMatrix);
	if (!SaveLineKiseiMatrix(pFolderName, pErrCode))
		return FALSE;
// --Add

	return TRUE;
}

void CDataManager::DispSaveProcess(CExcelFile &cExcelFile)
{
	CString sStatusBar;

	sStatusBar.Format("%s��ۑ���", cExcelFile.GetFileName());
	g_pSystem->StepProgressBar(sStatusBar);
}

// �v���W�F�N�g�t�H���_�쐬
BOOL CDataManager::MakeFolder(LPCTSTR pFolderName)
{
	// �t�H���_���݃`�F�b�N
	if (::PathFileExists(pFolderName)) {
		g_pSystem->DispMessage("E002007");
		return FALSE;
	}

	// �v���W�F�N�g�t�H���_�쐬
	if (!::CreateDirectory(pFolderName, NULL)) {
		g_pSystem->DispMessage("E002003", pFolderName);
		return FALSE;
	} else { // 2007/02/10 xxxxx kuwa
        struct _stat buf;
        int result;
        result = _stat( pFolderName, &buf );
		if (result == -1 ) {
			g_pSystem->DispMessage("E002003", pFolderName);
			return FALSE;
		}
	}

	return TRUE;
}

// �v���W�F�N�g�t�@�C���R�s�[
BOOL CDataManager::CopyProjectFile(LPCTSTR pFolderName)
{
	// ���i�\���H�������N���t�@�C��
	DispSaveProcess(m_eBuhinhyoLink);
	if (!::CopyFile(m_eBuhinhyoLink.m_sPathName, CGlobal::MakePathName(pFolderName, m_eBuhinhyoLink.GetNewFileName()), TRUE)) {
		g_pSystem->DispMessage("E002001", m_eBuhinhyoLink.GetFileName());
		return FALSE;
	}

// --Delete 2007/03/02 aizawa �s��D-147
	// ���C���K���}�g���N�X
//	DispSaveProcess(m_eLineKiseiMatrix);
//	if (!::CopyFile(m_eLineKiseiMatrix.m_sPathName, CGlobal::ChangeFolderName(pFolderName, m_eLineKiseiMatrix.m_sPathName), TRUE)) {
//		g_pSystem->DispMessage("E002001", m_eLineKiseiMatrix.GetFileName());
//		return FALSE;
//	}
// --Delete

	// �^���K���}�g���N�X
	int nKatashikiNum = m_aKatashiki.GetSize();
	if (nKatashikiNum != 0) {
		DispSaveProcess(m_aKatashiki[0].eKatashikiKiseiMatrix);
		for (int i = 0; i < nKatashikiNum; i++) {
			CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix = m_aKatashiki[i].eKatashikiKiseiMatrix;
			if (!cExcelKatashikiKiseiMatrix.m_sPathName.IsEmpty()) {
				if (!::CopyFile(cExcelKatashikiKiseiMatrix.m_sPathName, CGlobal::ChangeFolderName(pFolderName, cExcelKatashikiKiseiMatrix.m_sPathName), TRUE)) {
					g_pSystem->DispMessage("E002001", cExcelKatashikiKiseiMatrix.GetFileName());
					return FALSE;
				}
			}
		}
	}

	// �L�b�g��ƃe���v���[�g
	DispSaveProcess(m_eKittingTemplate);
	if (!::CopyFile(m_eKittingTemplate.m_sPathName, CGlobal::ChangeFolderName(pFolderName, m_eKittingTemplate.m_sPathName), TRUE)) {
		g_pSystem->DispMessage("E002001", m_eKittingTemplate.GetFileName());
		return FALSE;
	}

	// �L�b�g�ΏۊO���i���X�g
	DispSaveProcess(m_eKittingTaishogai);
	if (::PathFileExists(m_eKittingTaishogai.m_sPathName)) {
		if (!::CopyFile(m_eKittingTaishogai.m_sPathName, CGlobal::ChangeFolderName(pFolderName, m_eKittingTaishogai.m_sPathName), TRUE)) {
			g_pSystem->DispMessage("E002001", m_eKittingTaishogai.GetFileName());
			return FALSE;
		}
	}

// Modify ... ( CHANGE )
	// ���C���K���ᔽ�����t�@�C��
	DispSaveProcess(m_eLineError);
	::CopyFile(m_eLineError.m_sPathName, CGlobal::ChangeFolderName(pFolderName, m_eLineError.m_sPathName), TRUE);

	// �^���K���ᔽ�����t�@�C��
	DispSaveProcess(m_eKatashikiError);
	::CopyFile(m_eKatashikiError.m_sPathName, CGlobal::ChangeFolderName(pFolderName, m_eKatashikiError.m_sPathName), TRUE);
// By Y.Itabashi (xxxxx) 2007.02.16

	return TRUE;
}

// ���Y�䐔���t�@�C���ۑ�
BOOL CDataManager::SaveSeisanDaisu(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	m_eSeisanDaisu.SetRows(2);

	int nSize = GetSeisanDaisuNum();
	for (int i = 0; i < nSize; i++) {
		SSeisanDaisu &cSeisanDaisu = m_aSeisanDaisu[i];
		int nKishuNum = GetKishuNum();
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKishu &cKishu = m_aKishu[nKishuId];
			int nRow = m_eSeisanDaisu.AddRows();
			m_eSeisanDaisu.PutElement(nRow, m_eSeisanDaisu.eColKishu, cKishu.sKishuName);
			m_eSeisanDaisu.PutElement(nRow, m_eSeisanDaisu.eColNengetsu, cSeisanDaisu.sYexxxonth);
			m_eSeisanDaisu.PutElement(nRow, m_eSeisanDaisu.eColSeisanDaisu, cSeisanDaisu.aSeisanDaisu[nKishuId]);
		}
	}

	return m_eSeisanDaisu.WriteFile(pFolderName, pErrCode);
}

// �Ζ��̐����t�@�C���ۑ�
BOOL CDataManager::SaveKinmuTaisei(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];
		int nRow = m_eKinmuTaisei.GetRow(1, cSubline.sSublineName);
		m_eKinmuTaisei.PutElement(nRow, m_eKinmuTaisei.eColGekkanKadoJikan, cSubline.cSeisanJoken.fKadoJikan);
		m_eKinmuTaisei.PutElement(nRow, m_eKinmuTaisei.eColKyoseiKudoFlag, cSubline.cSeisanJoken.bKyoseiKudo);
		m_eKinmuTaisei.PutElement(nRow, m_eKinmuTaisei.eColKadoritsu, cSubline.cSeisanJoken.fKadoRitsu);
	}

	return m_eKinmuTaisei.WriteFile(pFolderName, pErrCode);
}

// DSTR�Z�o�p���t�@�C���ۑ�
BOOL CDataManager::SaveDSTRSanshutsu(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	m_eDSTRSanshutsu.SetRows(2);
	m_eDSTRSanshutsu.SetCols(15);
	m_eDSTRSanshutsu.SetHeader();

	int nKishuNum = m_aKishu.GetSize();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		SKishu &cKishu = m_aKishu[nKishuId];

		int nRow = m_eDSTRSanshutsu.AddRows();
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColKishuName, cKishu.sKishuName);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColHyojunJikan, cKishu.cDSTRSanshutsu.fHyojunJikan);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColTeikiTenkenJikan, cKishu.cDSTRSanshutsu.fTeikiTenkenJikan);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColHinshitsuLoss, cKishu.cDSTRSanshutsu.fHinshitsuLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColSetsubiFuguaiLoss, cKishu.cDSTRSanshutsu.fSetsubiFuguaiLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColShujukuLoss, cKishu.cDSTRSanshutsu.fShujukuLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColLineTeishiLoss, cKishu.cDSTRSanshutsu.fLineTeishiLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColHenseiLoss, cKishu.cDSTRSanshutsu.fHenseiLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColHukugouNoritsuBun, cKishu.cDSTRSanshutsu.fHukugouNoritsuBun);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColKanriTeishiJikan, cKishu.cDSTRSanshutsu.fKanriTeishiJikan);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColDST, cKishu.cDSTRSanshutsu.fDST);
	}

// Modify ... ( CHANGE )
	if( m_aTotalDSTRSanshutsu ){
		int nRow = m_eDSTRSanshutsu.AddRows();
// Modify ... ( CHANGE )
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColKishuName, g_pSystem->GetDPEIndex( 4, 1, 1 ) );
//		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColKishuName, g_pSystem->m_aDPEInterface[4].aItem[1].aList[1]);
// By Y.Itabashi (xxxxx) 2007.02.20
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColHyojunJikan, m_cTotalDSTRSanshutsu.fHyojunJikan);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColTeikiTenkenJikan, m_cTotalDSTRSanshutsu.fTeikiTenkenJikan);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColHinshitsuLoss, m_cTotalDSTRSanshutsu.fHinshitsuLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColSetsubiFuguaiLoss, m_cTotalDSTRSanshutsu.fSetsubiFuguaiLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColShujukuLoss, m_cTotalDSTRSanshutsu.fShujukuLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColLineTeishiLoss, m_cTotalDSTRSanshutsu.fLineTeishiLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColHenseiLoss, m_cTotalDSTRSanshutsu.fHenseiLoss);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColHukugouNoritsuBun, m_cTotalDSTRSanshutsu.fHukugouNoritsuBun);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColKanriTeishiJikan, m_cTotalDSTRSanshutsu.fKanriTeishiJikan);
		m_eDSTRSanshutsu.PutElement(nRow, m_eDSTRSanshutsu.eColDST, m_cTotalDSTRSanshutsu.fDST);
	}
// By Y.Itabashi (xxxxx) 2007.02.14

	return m_eDSTRSanshutsu.WriteFile(pFolderName, pErrCode);
}

// �v�f��ƕ\�t�@�C���ۑ�
BOOL CDataManager::SaveYosoSagyoHyo(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	UpdateYosoSagyoHyo();
	return m_eYosoSagyoHyo.WriteFile(pFolderName, pErrCode);
}

// �^���K���ᔽ�t�@�C���ۑ�
BOOL CDataManager::SaveKatashikiError(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	BOOL bWrite = TRUE;

	CTime time = CTime::GetCurrentTime();
	CString sDay, sTime;

// Modify ... ( ADD )
	CStringArray aPathName;
	CString sFilePath = CGlobal::MakePathName( g_pSystem->m_cIniUser.m_sDataPath, m_eKatashikiError.m_sFileName );
	CGlobal::FindFiles( sFilePath, aPathName);
	if (aPathName.GetSize() == 0){
		m_eKatashikiError.nErrorDataSize = 0;
		m_eKatashikiError.m_sPathName.Empty();
	}

	if( m_eKatashikiError.nErrorDataSize == m_aKatashikiError.GetSize() )
		return TRUE;
// By Y.Itabashi (xxxxx) 2007.02.19

// Modify ... ( ADD )
	if( pFolderName == NULL )
		m_eKatashikiError.m_sFolderName = g_pSystem->m_cIniUser.m_sDataPath;
// By Y.Itabashi (xxxxx) 2007.02.16

	sDay.Format( "%04d/%02d/%02d", time.GetYear(), time.GetMonth(), time.GetDay() );
	sTime.Format( "%02d:%02d:%02d", time.GetHour(), time.GetMinute(), time.GetSecond() );

	// �V�[�g�쐬
	for( int nKishuID = 0; nKishuID < m_aKishu.GetSize(); nKishuID++ ){

		CString sKatashiki = m_aKishu[nKishuID].sKatashiki;

		if( m_eKatashikiError.GetSheetNum() == 1 && nKishuID == 0 ){
			m_eKatashikiError.SetCurrentSheetName( sKatashiki );
		}else{
			BOOL bFindSheet = 0;
			for( int nSheet = 0; nSheet < m_eKatashikiError.GetSheetNum(); nSheet++ ){
				m_eKatashikiError.SetCurrentSheet( nSheet );
				CString sSheet = m_eKatashikiError.GetSheetName();
				if( sSheet == sKatashiki ){
					bFindSheet = 1;
					break;
				}
			}
			if( !bFindSheet ){
				m_eKatashikiError.AddSheet( sKatashiki );
				m_eKatashikiError.SetCurrentSheet( sKatashiki );
			}
		}

		m_eKatashikiError.SetRows(2);
		m_eKatashikiError.SetCols(15);
		m_eKatashikiError.SetHeader();
	}

	// �f�[�^�o��
	int nDataNum = m_aKatashikiError.GetSize();
	for( nKishuID = 0; nKishuID < m_aKishu.GetSize(); nKishuID++ ){

		CString sKishu = m_aKishu[nKishuID].sKishuName;
		CString sKatashiki = m_aKishu[nKishuID].sKatashiki;
// Modify ... ( CHANGE )
		for( int nSheet = 0; nSheet < m_eKatashikiError.GetSheetNum(); nSheet++ ){
//for( int nSheet = 0; nSheet < m_eKatashikiError.GetSheetNum() && m_eKatashikiError.GetCurrentSheetNo() > 0; nSheet++ ){
// By Y.Itabashi (xxxxx) 2007.02.16
			m_eKatashikiError.SetCurrentSheet( nSheet );
			CString sSheet = m_eKatashikiError.GetSheetName();
			if( sSheet == sKatashiki )
				break;
		}

		for (int icnt = 0; icnt < nDataNum; icnt++) {

			if( m_aKatashikiError[icnt].sKishuName != sKishu &&
				m_aKatashikiError[icnt].nKishuID != nKishuID )
				continue;

			int nRow = m_eKatashikiError.AddRows();

			if( m_aKatashikiError[icnt].sDay.IsEmpty() ){
				m_aKatashikiError[icnt].sDay = sDay;
				m_aKatashikiError[icnt].sTime = sTime;
			}
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColDay, m_aKatashikiError[icnt].sDay );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColTime, m_aKatashikiError[icnt].sTime );

			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColSublineFrom, m_aKatashikiError[icnt].sSublineFrom );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColKoteiNoFrom, m_aKatashikiError[icnt].sKoteiNoFrom );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColKoteiNameFrom, m_aKatashikiError[icnt].sKoteiNameFrom );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColYosoSagyogunNameFrom, m_aKatashikiError[icnt].sYosoSagyogunNameFrom );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColSublineTo, m_aKatashikiError[icnt].sSublineTo );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColKoteiNoTo, m_aKatashikiError[icnt].sKoteiNoTo );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColKoteiNameTo, m_aKatashikiError[icnt].sKoteiNameTo );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColYosoSagyogunNameTo, m_aKatashikiError[icnt].sYosoSagyogunNameTo );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColSublineNg, m_aKatashikiError[icnt].sSublineNg );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColKoteiNoNg, m_aKatashikiError[icnt].sKoteiNoNg );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColKoteiNameNg, m_aKatashikiError[icnt].sKoteiNameNg );
			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColYosoSagyogunNameNg, m_aKatashikiError[icnt].sYosoSagyogunNameNg );
			
			if ( m_aKatashikiError[icnt].sRiyuMsg.GetLength() == 0 )
				m_aKatashikiError[icnt].sRiyuMsg = g_pSystem->m_cMessage.GetMessage( m_aKatashikiError[icnt].sRiyuNo );

			m_eKatashikiError.PutElement( nRow, m_eKatashikiError.eColRiyu, m_aKatashikiError[icnt].sRiyuMsg );
		}

	}

	return m_eKatashikiError.WriteFile(pFolderName, pErrCode);
}

// �^���K���ᔽ�t�@�C���ۑ�
BOOL CDataManager::SaveLineError(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	BOOL bWrite = TRUE;

	CTime time = CTime::GetCurrentTime();
	CString sDay, sTime;

// Modify ... ( ADD )
	CStringArray aPathName;
	CString sFilePath = CGlobal::MakePathName( g_pSystem->m_cIniUser.m_sDataPath, m_eKatashikiError.m_sFileName );
	CGlobal::FindFiles( sFilePath, aPathName);
	if (aPathName.GetSize() == 0){
		m_eLineError.nErrorDataSize = 0;
		m_eLineError.m_sPathName.Empty();
	}

	if( m_eLineError.nErrorDataSize == m_aLineError.GetSize() )
		return TRUE;
// By Y.Itabashi (xxxxx) 2007.02.19

// Modify ... ( ADD )
	if( pFolderName == NULL )
		m_eLineError.m_sFolderName = g_pSystem->m_cIniUser.m_sDataPath;
// By Y.Itabashi (xxxxx) 2007.02.16

	sDay.Format( "%04d/%02d/%02d", time.GetYear(), time.GetMonth(), time.GetDay() );
	sTime.Format( "%02d:%02d:%02d", time.GetHour(), time.GetMinute(), time.GetSecond() );


	// �V�[�g�쐬
	for( int nKishuID = 0; nKishuID < m_aKishu.GetSize(); nKishuID++ ){
		CString sKatashiki = m_aKishu[nKishuID].sKatashiki;
		if( m_eLineError.GetSheetNum() == 1 && nKishuID == 0 ){
			m_eLineError.SetCurrentSheetName( sKatashiki );
		}else{
			BOOL bFindSheet = 0;
			for( int nSheet = 0; nSheet < m_eLineError.GetSheetNum(); nSheet++ ){
				m_eLineError.SetCurrentSheet( nSheet );
				CString sSheet = m_eLineError.GetSheetName();
				if( sSheet == sKatashiki ){
					bFindSheet = 1;
					break;
				}
			}
			if( !bFindSheet ){
				m_eLineError.AddSheet( sKatashiki );
				m_eLineError.SetCurrentSheet( sKatashiki );
			}
		}

		m_eLineError.SetRows(2);
		m_eLineError.SetCols(14);
		m_eLineError.SetHeader();
	}

	// �f�[�^�o��
	int nDataNum = m_aLineError.GetSize();
	for( nKishuID = 0; nKishuID < m_aKishu.GetSize(); nKishuID++ ){

		CString sKatashiki = m_aKishu[nKishuID].sKatashiki;
		for( int nSheet = 0; nSheet < m_eLineError.GetSheetNum(); nSheet++ ){
			m_eLineError.SetCurrentSheet( nSheet );
			CString sSheet = m_eLineError.GetSheetName();
			if( sSheet == sKatashiki )
				break;
		}

		for (int icnt = 0; icnt < nDataNum; icnt++) {

			if( m_aLineError[icnt].nKishuID != nKishuID )
				continue;

			int nRow = m_eLineError.AddRows();

			if( m_aLineError[icnt].sDay.IsEmpty() ){
				m_aLineError[icnt].sDay = sDay;
				m_aLineError[icnt].sTime = sTime;
			}
			m_eLineError.PutElement( nRow, m_eLineError.eColDay, m_aLineError[icnt].sDay );
			m_eLineError.PutElement( nRow, m_eLineError.eColTime, m_aLineError[icnt].sTime );

			m_eLineError.PutElement( nRow, m_eLineError.eColSublineFrom, m_aLineError[icnt].sSublineFrom );
			m_eLineError.PutElement( nRow, m_eLineError.eColKoteiNoFrom, m_aLineError[icnt].sKoteiNoFrom );
			m_eLineError.PutElement( nRow, m_eLineError.eColKoteiNameFrom, m_aLineError[icnt].sKoteiNameFrom );
			m_eLineError.PutElement( nRow, m_eLineError.eColYosoSagyogunNameFrom, m_aLineError[icnt].sYosoSagyogunNameFrom );
			m_eLineError.PutElement( nRow, m_eLineError.eColSublineTo, m_aLineError[icnt].sSublineTo );
			m_eLineError.PutElement( nRow, m_eLineError.eColKoteiNoToB, m_aLineError[icnt].sKoteiNoToB );
			m_eLineError.PutElement( nRow, m_eLineError.eColKoteiNameToB, m_aLineError[icnt].sKoteiNameToB );
			m_eLineError.PutElement( nRow, m_eLineError.eColYosoSagyogunNameToB, m_aLineError[icnt].sYosoSagyogunNameToB );
			m_eLineError.PutElement( nRow, m_eLineError.eColKoteiNoToA, m_aLineError[icnt].sKoteiNoToA );
			m_eLineError.PutElement( nRow, m_eLineError.eColKoteiNameToA, m_aLineError[icnt].sKoteiNameToA );
			m_eLineError.PutElement( nRow, m_eLineError.eColYosoSagyogunNameToA, m_aLineError[icnt].sYosoSagyogunNameToA );
			
			if ( m_aLineError[icnt].sRiyuMsg.GetLength() == 0 )
				m_aLineError[icnt].sRiyuMsg = g_pSystem->m_cMessage.GetMessage( m_aLineError[icnt].sRiyuNo );

			m_eLineError.PutElement( nRow, m_eLineError.eColRiyu, m_aLineError[icnt].sRiyuMsg );
		}

	}

	return m_eLineError.WriteFile(pFolderName, pErrCode);
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::UpdateYosoSagyoHyo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƃf�[�^�X�V
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƌQ�f�[�^(m_aYosoSagyogun)����v�f��ƕ\�f�[�^(m_eYosoSagyoHyo)���X�V����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::UpdateYosoSagyoHyo()
{
	int nSublineId;
	int nRow, nCol;
	_variant_t vData;
	CIntArrayEx aSagyojun;

	// �񐔎擾
	long nUsedCols = m_eYosoSagyoHyo.GetUsedCols();

	// SafeArray����
	CExcelData cTempArray;
	cTempArray.Create(m_eYosoSagyoHyo.GetUsedRows(), nUsedCols);

	// �w�b�_�s�R�s�[
	for (nRow = 1; nRow <= 2; nRow++) {
		for (nCol = 1; nCol <= nUsedCols; nCol++) {
			m_eYosoSagyoHyo.GetElement(nRow, nCol, vData);
			cTempArray.PutElement(nRow, nCol, vData);
		}
	}

	// �@�퐔�擾
	int nKishuNum = GetKishuNum();

	int nSublineNum = m_aSubline.GetSize();
	for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		// �@��ʂɗv�f��Ə��Ԃ�U�蒼��
		CSagyojunArray aSagyojun;
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			GetSagyojun(nSublineId, nKishuId, aSagyojun);
			OrderBySagyojun(nSublineId, aSagyojun);
		}

		// �G�N�Z���f�[�^��v�f��ƌQ�f�[�^�ōX�V
		UpdateYosoSagyoHyoSub(cSubline.sSublineName, cSubline.aYosoSagyogun, cSubline.aSagyosha, cTempArray, nRow);
	}

	for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		// �G�N�Z���f�[�^���L�b�g���v�f��ƌQ�f�[�^�ōX�V
		UpdateYosoSagyoHyoSub("KIT" + cSubline.sSublineName, cSubline.aYosoSagyogunKit, m_aKittingSagyosha, cTempArray, nRow);
	}

	// ���ʂ����̕ϐ��ɖ߂�
	m_eYosoSagyoHyo.Copy(cTempArray);
}

void CDataManager::UpdateYosoSagyoHyoSub(CString &sSublineName, CYosoSagyogunArray &aYosoSagyogun, CStringArray &aSagyosha, CExcelData &cTempArray, int &nRow)
{
	SKishuCols *pKishuCols;
	long nUsedCols = m_eYosoSagyoHyo.GetUsedCols();
	_variant_t vData;
	CString sDeleteFlag;

	// �@�퐔�擾
	int nKishuNum = GetKishuNum();

	// �G�N�Z���f�[�^��v�f��ƌQ�f�[�^�ōX�V
	int nYosoSagyogunNum = aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[nYosoSagyogunId];
		int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();

		// �v�f��Ƃ��X�V
		int nYosoSagyoNo = 1;
		for (int i = 0; i < nYosoSagyoNum; i++) {
			SYosoSagyo &cYosoSagyo = cYosoSagyogun.aYosoSagyo[i];

			// �폜�t���O�ƒǉ��t���O�̗������Z�b�g����Ă���ꍇ�͕ۑ����Ȃ�
			if (cYosoSagyo.sDeleteFlag.IsEmpty() || cYosoSagyo.sInsertFlag.IsEmpty()) {
				int nRow2 = cYosoSagyo.nRow;

				cTempArray.SetRows(nRow);

				for (int nCol = 1; nCol <= nUsedCols; nCol++) {
					m_eYosoSagyoHyo.GetElement(nRow2, nCol, vData);
					cTempArray.PutElement(nRow, nCol, vData);
				}

				// �L�b�g���ɂ��폜�t���O�ݒ�
				sDeleteFlag = cYosoSagyo.sDeleteFlag;
				if (sDeleteFlag.IsEmpty() && cYosoSagyo.bKittingDelete)
					sDeleteFlag = "3";

				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColSakujoFlag), sDeleteFlag);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColTsuikaFlag), cYosoSagyo.sInsertFlag);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColSublineName), sSublineName);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKoteiKigo), cYosoSagyogun.sKoteiKigo);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKoteiNo), cYosoSagyogun.sKoteiNo);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKoteiName), cYosoSagyogun.sKoteiName);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColYosoSagyogunName), cYosoSagyogun.sYosoSagyogunName);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColJidoShudoYosoSagyogun), cYosoSagyogun.sJidoYosoSagyogun);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColSagyoNo), nYosoSagyoNo++);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColSagyoNaiyo), cYosoSagyo.sYosoSagyoName);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColYosoSagyoNo), cYosoSagyo.sYosoSagyoNo);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColEnzanShiki), cYosoSagyo.sEnzanShiki);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColS1), cYosoSagyo.fS1);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColS2), cYosoSagyo.fS2);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eCxxx), cYosoSagyo.fM);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColN), cYosoSagyo.fN);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColEnzangoSagyoJikan), cYosoSagyo.fEnzangoSagyoJikan);
				if (cYosoSagyo.nHassexxxndo == 0)
					cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColHassexxxndo), "");
				else
					cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColHassexxxndo), cYosoSagyo.nHassexxxndo);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKurikaeshiKaisu), cYosoSagyo.nKurikaeshiKaisu);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColDaiatariYosoSagyoJikan), cYosoSagyo.fTime);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColJidoShudoKotei), cYosoSagyogun.sJidoKotei);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKoteibuHendobu), cYosoSagyo.sKoteibuHendobu);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColSagyoFlag), cYosoSagyo.sSagyoFlag);
				cTempArray.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(m_eYosoSagyoHyo.eColKijunKotei), cYosoSagyo.nKijunKotei);

				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
					SKishuSagyo cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

					pKishuCols = m_eYosoSagyoHyo.m_aKishuCols[0].aKishuCols.LookupElement(m_aKishu[nKishuId].sKishuName);
					cTempArray.PutElement(nRow, pKishuCols->nCol, cKishuSagyo.nSagyoshaId == -1 ? "" : aSagyosha[cKishuSagyo.nSagyoshaId]);

					pKishuCols = m_eYosoSagyoHyo.m_aKishuCols[1].aKishuCols.LookupElement(m_aKishu[nKishuId].sKishuName);
					if (cKishuSagyo.nSagyojun != 0)
						cTempArray.PutElement(nRow, pKishuCols->nCol, cKishuSagyo.nSagyojun);
					else
						cTempArray.PutElement(nRow, pKishuCols->nCol, "");
				}

				cYosoSagyo.nRow = nRow++;
			}
		}
	}
}

// ���Y�����ݒ�t�@�C���ۑ�
BOOL CDataManager::SaveSeisanJoken(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];
		int nRow = m_eSeisanJoken.GetRow(1, cSubline.sSublineName);
		m_eSeisanJoken.PutElement(nRow, m_eSeisanJoken.eColKinmuTaisei, cSubline.cSeisanJoken.fKinmuTaisei);
		m_eSeisanJoken.PutElement(nRow, m_eSeisanJoken.eColKyusokuJikan, cSubline.cSeisanJoken.nKyusokuJikan);
		m_eSeisanJoken.PutElement(nRow, m_eSeisanJoken.eColKadoNissu, cSubline.cSeisanJoken.nKadoNissu);
		m_eSeisanJoken.PutElement(nRow, m_eSeisanJoken.eColKotaiKinmu, cSubline.cSeisanJoken.nKotaiKinmu);
		m_eSeisanJoken.PutElement(nRow, m_eSeisanJoken.eColJogen, cSubline.cSeisanJoken.fJogen);
		m_eSeisanJoken.PutElement(nRow, m_eSeisanJoken.eColKagen, cSubline.cSeisanJoken.fKagen);
	}

	return m_eSeisanJoken.WriteFile(pFolderName, pErrCode);
}

// �L�b�g�����i�t�@�C���ۑ�
BOOL CDataManager::SaveBuhinKitting(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	m_eBuhinKitting.ClearAllData();

	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];
//		if (cSubline.bSetKitting) {
			int nSize = cSubline.aBuhinKitting.GetSize();
			for (int i = 0; i < nSize; i++) {
				SBuhinKitting &cBuhinKitting = cSubline.aBuhinKitting[i];
				int nRow = m_eBuhinKitting.AddRows();
				m_eBuhinKitting.PutElement(nRow, m_eBuhinKitting.eColSublineName, cSubline.sSublineName);
				m_eBuhinKitting.PutElement(nRow, m_eBuhinKitting.eColBlock, cBuhinKitting.sBlockNo);
				m_eBuhinKitting.PutElement(nRow, m_eBuhinKitting.eColBuhinNo, cBuhinKitting.sBuhinNo);
				m_eBuhinKitting.PutElement(nRow, m_eBuhinKitting.eColName, cBuhinKitting.sBuhinName);
				m_eBuhinKitting.PutElement(nRow, m_eBuhinKitting.eColKitingFlag, cBuhinKitting.bKittingFlag ? "1" : "");
			}
//		}
	}

	return m_eBuhinKitting.WriteFile(pFolderName, pErrCode);
}

// ���ʍH���ݒ�t�@�C���ۑ�
BOOL CDataManager::SaveKyotsuKotei(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	int nSublineId;
	int nKishuId;
	int nSublineNum = m_aSubline.GetSize();
	CString sColumnName;

	int nKishuNum = m_aKishu.GetSize();
	m_eKyotsuKotei.Create(2, m_eKyotsuKotei.eColKyotsuKoteiGroupNo + nKishuNum - 1);
	m_eKyotsuKotei.SetHeader();
	m_eKyotsuKotei.GetElement(2, m_eKyotsuKotei.eColKyotsuKoteiGroupNo, sColumnName);
	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
		m_eKyotsuKotei.PutElement(2, m_eKyotsuKotei.eColKyotsuKoteiGroupNo + nKishuId, sColumnName + ":" + m_aKishu[nKishuId].sKishuName);

	int nRow = 3;
	for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
			if (!cYosoSagyogun.CheckDeleted()) {
				nRow = m_eKyotsuKotei.AddRows();
				m_eKyotsuKotei.PutElement(nRow, m_eKyotsuKotei.eColSublineName, cSubline.sSublineName);
				m_eKyotsuKotei.PutElement(nRow, m_eKyotsuKotei.eColKoteiName, cYosoSagyogun.sKoteiName);
				m_eKyotsuKotei.PutElement(nRow, m_eKyotsuKotei.eColYosoSagyogunName, cYosoSagyogun.sYosoSagyogunName);
				m_eKyotsuKotei.PutElement(nRow, m_eKyotsuKotei.eColJodoShudo, cYosoSagyogun.sJidoYosoSagyogun.IsEmpty() ? g_pConst->m_sShudo : cYosoSagyogun.sJidoYosoSagyogun);

				for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
					if (cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo != 0)
						m_eKyotsuKotei.PutElement(nRow, m_eKyotsuKotei.eColKyotsuKoteiGroupNo + nKishuId, cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo);
				}
			}
		}
	}

	return m_eKyotsuKotei.WriteFile(pFolderName, pErrCode);
}

BOOL CDataManager::SaveKittingTaishoBuhin(LPCTSTR sPathName)
{
	CExcelKittingTaishoBuhin cExcelKittingTaishoBuhin;
	int nKishuId;

	int nKishuNum = m_aKishu.GetSize();
	cExcelKittingTaishoBuhin.Create(2, cExcelKittingTaishoBuhin.eColPC + nKishuNum - 1);
	cExcelKittingTaishoBuhin.m_sFileName = CGlobal::GetFileNameFromPath(sPathName);
	cExcelKittingTaishoBuhin.SetHeader();

	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
		cExcelKittingTaishoBuhin.PutElement(2, cExcelKittingTaishoBuhin.eColPC + nKishuId, m_aKishu[nKishuId].sKishuName);

	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];
//		if (cSubline.bSetKitting) {
			int nSize = cSubline.aKittingBuhin.GetSize();
			for (int i = 0; i < nSize; i++) {
				SKittingBuhin &cKittingBuhin = cSubline.aKittingBuhin[i];
				if (cKittingBuhin.bCheck) {
					int nRow = cExcelKittingTaishoBuhin.AddRows();
					cExcelKittingTaishoBuhin.PutElement(nRow, cExcelKittingTaishoBuhin.eColSublineName, cSubline.sSublineName);
					cExcelKittingTaishoBuhin.PutElement(nRow, cExcelKittingTaishoBuhin.eColBlock, cKittingBuhin.sBlockNo);
					cExcelKittingTaishoBuhin.PutElement(nRow, cExcelKittingTaishoBuhin.eColBuhinNo, cKittingBuhin.sBuhinNo);
					cExcelKittingTaishoBuhin.PutElement(nRow, cExcelKittingTaishoBuhin.eColBuhinName, cKittingBuhin.sBuhinName);

					for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
						cExcelKittingTaishoBuhin.PutElement(nRow, cExcelKittingTaishoBuhin.eColPC + nKishuId, cKittingBuhin.aPC[nKishuId]);
				}
			}
//		}
	}

	return cExcelKittingTaishoBuhin.WriteFile(CGlobal::GetFolderNameFromPath(sPathName), NULL);
}

// --Add 2007/03/02 aizawa �s��D-147
// ���C���K���}�g���N�X�t�@�C���ۑ�
BOOL CDataManager::SaveLineKiseiMatrix(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	int nSize = m_aLineKisei.GetSize();
	for (int i = 0; i < nSize; i++) {
		SLineKisei &cLineKisei = m_aLineKisei[i];
		m_eLineKiseiMatrix.PutElement(cLineKisei.nRow, m_eLineKiseiMatrix.eColSublineName, cLineKisei.sSublineName);
	}

	return m_eLineKiseiMatrix.WriteFile(pFolderName, pErrCode);
}
// --Add

// Modify ... ( ADD )
BOOL CDataManager::ExportGraphTemplate(LPCTSTR sPathName)
{

	BOOL bWrite = TRUE;
	m_eExportGraphTemplate.m_sTemplateName = "GraphTemplate.xls";
	CString m_sExportGraphTemplateFilePath = CGlobal::MakePathName( g_pSystem->m_cIniSystem.m_sSystemServerPath, "GraphTemplate.xls" );
	CString m_sCheckGraphTemplateFilePath = CGlobal::MakePathName( g_pSystem->m_cIniUser.m_sDataPath, "GraphTemplate.xls" );

// Modify ... ( CHANGE )
	// �v���W�F�N�g���Ƀe���v���[�g�����݂��Ă���ꍇ�́A�폜
	CStringArray aPathName;
	CGlobal::FindFiles( m_sCheckGraphTemplateFilePath, aPathName);
	if (aPathName.GetSize() != 0){
		remove( m_sCheckGraphTemplateFilePath );
	}
// By Y.Itabashi (xxxxx) 2007.02.19

	// �T�[�o����e���v���[�g���R�s�[
	CGlobal::FindFiles( m_sExportGraphTemplateFilePath, aPathName);
	if (aPathName.GetSize() == 0){
		g_pSystem->DispMessage("E012001", NULL);
		return FALSE;
	}

	CopyFromServer(CGlobal::GetFolderNameFromPath(m_sExportGraphTemplateFilePath),
		m_eExportGraphTemplate.m_sTemplateName);

// Modify ... ( ADD )
	// �ǂݎ���p�̏ꍇ�́A�ǂݎ���p����������
	CFile cGraphFile;
	CFileStatus cStatus;
	cGraphFile.GetStatus( m_sCheckGraphTemplateFilePath, cStatus );
	if( cStatus.m_attribute |= 0x01 ){
		cStatus.m_attribute = 0x20;
		cGraphFile.SetStatus( m_sCheckGraphTemplateFilePath, cStatus );
	}
// By Y.Itabashi (xxxxx) 2007.03.02
	
	CExcelExportGraphTemplate cExcelGraphTemplate;
	cExcelGraphTemplate.m_sTemplateName = m_eExportGraphTemplate.m_sTemplateName;

	// �O���t�f�[�^�쐬
	bWrite = ExportGraphTemplateSub1(cExcelGraphTemplate, sPathName);
	if( bWrite == FALSE )
		return bWrite;

	cExcelGraphTemplate.SetCurrentSheet( 0 );
	CString sName = cExcelGraphTemplate.GetSheetName();

	// ���t��ƕ\�쐬
	bWrite = ExportGraphTemplateSub2(cExcelGraphTemplate, sPathName);
	if( bWrite == FALSE )
		return bWrite;

	cExcelGraphTemplate.SetCurrentSheet( 0 );
	sName = cExcelGraphTemplate.GetSheetName();

	// �t�@�C���o��
	bWrite = cExcelGraphTemplate.WriteFile(CGlobal::GetFolderNameFromPath(sPathName), "E012002");
	if( bWrite == FALSE )
		return bWrite;

	// �t�@�C�����̕ύX
// Modify ... ( CHANGE )
	int nRet = rename( cExcelGraphTemplate.m_sPathName, sPathName );
	if( nRet != 0 && errno == EEXIST ){
		remove( sPathName );
		rename( cExcelGraphTemplate.m_sPathName, sPathName );
	}
//	rename( cExcelGraphTemplate.m_sPathName, sPathName );
// By Y.Itabashi (xxxxx) 2007.02.20

	return bWrite;
}
// By K.Kimura (xxxxx) 2..7.02.08

// Modify ... ( ADD )
BOOL CDataManager::ExportGraphTemplateSub1(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR sPathName)
{

	// �p�l��
	// �E�����o���t�@�C����
	// �E�J�����g�V�[�g�̕ύX�iSetCurrentSheet()�ŕύX�ł��Ȃ��H�j
	// �E�T�u���C�����̃e���v���[�g�V�[�g��PutElement()
	// �E�s�v�ȃT�u���C���̃V�[�g�폜
	// �EDPE�A�g�Ƃ̃}�b�`���O
	// �E��ޖ��̒l��������
	// �E���t��ƕ\�o��

	// �R�s�[�����t�@�C���ō�Ƃ��s��
	cExcelGraphTemplate.m_sPathName.Format( "%s\\%s",
		g_pSystem->m_cIniUser.m_sDataPath, m_eExportGraphTemplate.m_sTemplateName );
	cExcelGraphTemplate.ReadFile();

	int nSublineNum		= m_aSubline.GetSize();
	int nExportSubline	= 0;		// �o�̓T�u���C����
	int nExportKishu	= 0;
	int nExportKishuCnt	= 0;
	int nKishuNum		= 0;
	for ( int iKishu=0; iKishu < MAX_KISHU; iKishu++ ){
		if ( m_eExportGraphTemplate.IsCheckedKishu(iKishu) )
			nKishuNum++;
	}

	for ( int iSublineId = 0; iSublineId < nSublineNum; iSublineId++ ) {

		// �o�̓`�F�b�N����
		if ( m_eExportGraphTemplate.IsCheckedSubline(iSublineId) ) {

			// �T�u���C�����̃f�[�^�����o��
			SSubline &cSubline = m_aSubline[iSublineId];

			// �e���v���[�g�t�@�C��
			CString sheet_name;
			sheet_name.Format( "SUB%d", nExportSubline+1 );
			cExcelGraphTemplate.SetCurrentSheet( sheet_name );
			
			int nFirstRow = cExcelGraphTemplate.GetUsedRows();
			int	nRow = 2;

			// ��ƎҖ�
			int nSagyoshaNum = cSubline.aSagyosha.GetSize();
			for ( int iSagyoshaId = 0; iSagyoshaId < nSagyoshaNum; iSagyoshaId++ ) {

				if ( nRow >= nFirstRow ) 
					nRow = cExcelGraphTemplate.AddRows();
				else
					nRow++;
				CString &sSagyosha = cSubline.aSagyosha[iSagyoshaId];

				// �p�C���F�J�����ԍ���enu��`
				cExcelGraphTemplate.PutElement( nRow, 1, sSagyosha );
				cExcelGraphTemplate.PutElement( nRow, 2, cSubline.cSeisanJoken.fTact );

				int nKishuNum = m_aKishu.GetSize();
				nExportKishuCnt	= 0;
				for ( int iKishuId = 0; iKishuId < nKishuNum; iKishuId++ ) {

					if( m_eExportGraphTemplate.IsCheckedKishu(iKishuId) )
						nExportKishuCnt++;

					// �o�̓`�F�b�N����
					if ( m_eExportGraphTemplate.IsCheckedKishu(iKishuId) ||
						 m_eExportGraphTemplate.IsCheckedZenkishu() ||
						 m_eExportGraphTemplate.IsCheckedKajuHeikin() ) {

						cExcelGraphTemplate.PutElement( 2, 3+nExportKishu, m_aKishu[iKishuId].sKishuName );
						cExcelGraphTemplate.PutElement( nRow, 3+nExportKishu, GetSagyoJikan( iSublineId, iSagyoshaId, iKishuId ) );
						cExcelGraphTemplate.PutElement( nRow, 8+nExportKishu, GetSagyoJikan( iSublineId, iSagyoshaId, iKishuId )/double(nKishuNum) );
						nExportKishu++;
					}
				}
				for ( iKishu=nExportKishu; iKishu<MAX_KISHU; iKishu++ ) {
					cExcelGraphTemplate.PutElement( 2, 3+iKishu, "" );
					cExcelGraphTemplate.PutElement( nRow, 3+iKishu, 0 );
					cExcelGraphTemplate.PutElement( nRow, 8+iKishu, 0 );
				}
				nExportKishu = 0;
			}

// Modify ... ( ADD )
			// �V�[�g���ύX
			sheet_name.Format( "%s", cSubline.sSublineName );
			cExcelGraphTemplate.SetCurrentSheetName( sheet_name );
// By Y.Itabashi (xxxxx) 2007.03.07

			// �O���t�^�C�g���쐬
			CString sTitle;
			if( g_pSystem->m_cUserInfo.m_nJokenSetteiSosa != 2 ){
// Modify ... ( CHANGE )
				CString sData = g_pSystem->GetDPEIndex( 15, 16, 0 );
//				CString sData = g_pSystem->m_aDPEInterface[15].aItem[16].aList[0];
// By Y.Itabashi (xxxxx) 2007.02.20
// Modify ... ( CHANGE )
				sTitle.Format( sData, cSubline.sSublineName, cSubline.cSeisanJoken.fKadoRitsu,
					cSubline.cSeisanJoken.fTact, cSubline.aSagyosha.GetSize(), GetSeisanDaisu(iSublineId) );
//				sTitle.Format( sData, cSubline.sSublineName, cSubline.cSeisanJoken.fKadoRitsu, cSubline.cSeisanJoken.fTact );
// By Y.Itabashi (xxxxx) 2007.03.07
			}else{
// Modify ... ( CHANGE )
				CString sData = g_pSystem->GetDPEIndex( 15, 17, 0 );
//				CString sData = g_pSystem->m_aDPEInterface[15].aItem[17].aList[0];
// By Y.Itabashi (xxxxx) 2007.02.20
				int nShiftBase;
				if( cSubline.cSeisanJoken.nKotaiKinmu * cSubline.cSeisanJoken.nKadoNissu == 0 )
					nShiftBase = 0;
				else
					nShiftBase = (int)( ( GetSeisanDaisu(iSublineId) /
						( cSubline.cSeisanJoken.nKotaiKinmu * cSubline.cSeisanJoken.nKadoNissu ) ) + 0.5 );
// Modify ... ( CHANGE )
				sTitle.Format( sData, cSubline.sSublineName, cSubline.cSeisanJoken.fKadoRitsu,
					nShiftBase, cSubline.cSeisanJoken.fTact, cSubline.aSagyosha.GetSize(), GetSeisanDaisu(iSublineId) );
//				sTitle.Format( sData, cSubline.sSublineName, cSubline.cSeisanJoken.fKadoRitsu,
//					nShiftBase, cSubline.cSeisanJoken.fTact );
// By Y.Itabashi (xxxxx) 2007.03.07
			}

			sheet_name;
// Modify ... ( CHANGE )
			for( int nKishu = 0; nKishu < MAX_KISHU; nKishu++ ){

				if( !m_eExportGraphTemplate.IsCheckedKishu( nKishu ) ){
					sheet_name.Format( "SUB%d_%d", nExportSubline+1, nKishu+1 );
					cExcelGraphTemplate.SetDeleteSheet( sheet_name );
					continue;
				}

				// �O���t�^�C�g��
				sheet_name.Format( "SUB%d_%d", nExportSubline+1, nKishu+1 );
				cExcelGraphTemplate.SetCurrentSheet( sheet_name );
				cExcelGraphTemplate.SetRows(2);
				cExcelGraphTemplate.SetCols(2);

				cExcelGraphTemplate.PutElement( 1, 1, sTitle );

				// �V�[�g���ύX
				sheet_name.Format( "%s_%s", cSubline.sSublineName, m_aKishu[nKishu].sKishuName );
				cExcelGraphTemplate.SetCurrentSheetName( sheet_name );
			}

/*			for( nKishu = nExportKishuCnt; nKishu < MAX_KISHU; nKishu++ ){
				// �O���t�V�[�g�̍폜
				sheet_name.Format( "SUB%d_%d", nExportSubline+1, nKishu+1 );
				cExcelGraphTemplate.SetDeleteSheet( sheet_name );
			}*/
// By Y.Itabashi (xxxxx) 2007.03.07

			sheet_name.Format( "SUB%d_AVERAGE", nExportSubline+1 );
			if( m_eExportGraphTemplate.IsCheckedKajuHeikin() ){
				// �O���t�^�C�g��
				cExcelGraphTemplate.SetCurrentSheet( sheet_name );
				cExcelGraphTemplate.SetRows(2);
				cExcelGraphTemplate.SetCols(2);

				cExcelGraphTemplate.PutElement( 1, 1, sTitle );

// Modify ... ( ADD )
				// �V�[�g���ύX
				sheet_name.Format( "%s_AVERAGE", cSubline.sSublineName );
				cExcelGraphTemplate.SetCurrentSheetName( sheet_name );
// By Y.Itabashi (xxxxx) 2007.03.07
			}else{
				// �O���t�V�[�g�̍폜
				cExcelGraphTemplate.SetDeleteSheet( sheet_name );
			}

			sheet_name.Format( "SUB%d_ALL", nExportSubline+1 );
			if( m_eExportGraphTemplate.IsCheckedZenkishu() ){
				// �O���t�^�C�g��
				cExcelGraphTemplate.SetCurrentSheet( sheet_name );
				cExcelGraphTemplate.SetRows(2);
				cExcelGraphTemplate.SetCols(2);

				cExcelGraphTemplate.PutElement( 1, 1, sTitle );

// Modify ... ( ADD )
				// �V�[�g���ύX
				sheet_name.Format( "%s_ALL", cSubline.sSublineName );
				cExcelGraphTemplate.SetCurrentSheetName( sheet_name );
// By Y.Itabashi (xxxxx) 2007.03.07
			}else{
				// �O���t�V�[�g�̍폜
				cExcelGraphTemplate.SetDeleteSheet( sheet_name );
			}

			nExportSubline++;
		}
	}

	for( ; nExportSubline < MAX_SUBLINE; nExportSubline++ ){
		CString sheet_name;
		sheet_name.Format( "SUB%d", nExportSubline+1 );

		cExcelGraphTemplate.SetDeleteSheet( sheet_name );

		// �O���t�V�[�g�̍폜
		for( int nKishu = 0; nKishu < MAX_KISHU; nKishu++ ){
			sheet_name.Format( "SUB%d_%d", nExportSubline+1, nKishu+1 );
			cExcelGraphTemplate.SetDeleteSheet( sheet_name );
		}
		sheet_name.Format( "SUB%d_AVERAGE", nExportSubline+1 );
		cExcelGraphTemplate.SetDeleteSheet( sheet_name );
		sheet_name.Format( "SUB%d_ALL", nExportSubline+1 );
		cExcelGraphTemplate.SetDeleteSheet( sheet_name );
	}

	return TRUE;
}

BOOL CDataManager::ExportGraphTemplateSub2(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR sPathName)
{

	if( !m_eExportGraphTemplate.IsCheckedWaritsuke() ){
// Modify ... ( CHANGE )
		CString sheet_name = g_pSystem->GetDPEIndex( 15, 1, 0 );
//		CString sheet_name = g_pSystem->m_aDPEInterface[15].aItem[1].aList[0];
// By Y.Itabashi (xxxxx) 2007.02.20
		cExcelGraphTemplate.SetDeleteSheet( sheet_name );
		return TRUE;
	}

	// ���t��ƕ\�o��
	int nKishuNum = m_aKishu.GetSize();

	BOOL bWrite;

	// �\�P
	bWrite = ExportGraphTemplateWaritsuke1( cExcelGraphTemplate, sPathName );
	if( !bWrite )	return FALSE;

	// �\�Q
	bWrite = ExportGraphTemplateWaritsuke2( cExcelGraphTemplate, sPathName );
	if( !bWrite )	return FALSE;

	return bWrite;
}
// By K.Fujii (xxxxx) 2007.02.16

// Modify ... ( ADD )
BOOL CDataManager::ExportGraphTemplateWaritsuke1(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR sPathName)
{
	// ���t��ƕ\�o��
	int nKishuNum = m_aKishu.GetSize();

	// �\�P
#define GRAPH_COL_HYO1	6
#define GRAPH_TITLE_NO	1

	CString sheet_name;
	CString data;
// Modify ... ( CHANGE )
	cExcelGraphTemplate.SetCurrentSheet( g_pSystem->GetDPEIndex( 15, GRAPH_TITLE_NO, 0 ) );
//	cExcelGraphTemplate.SetCurrentSheet( g_pSystem->m_aDPEInterface[15].aItem[GRAPH_TITLE_NO].aList[0] );
// By Y.Itabashi (xxxxx) 2007.02.20
	cExcelGraphTemplate.SetRows(3);
	cExcelGraphTemplate.SetCols(nKishuNum*GRAPH_COL_HYO1 + 1);

	// �w�b�_�[
// Modify ... ( CHANGE )
	CString sHeader = g_pSystem->GetDPEIndex( 15, 2, 0 );
	cExcelGraphTemplate.PutElement( 2, cExcelGraphTemplate.eColSublineName, sHeader );
	for( int nCol = 2; nCol <= GRAPH_COL_HYO1+1; nCol++ ){
		int nExportKishuCnt = 0;
		for ( int iKishuId = 0; iKishuId < nKishuNum; iKishuId++) {

			if( m_eExportGraphTemplate.IsCheckedZenkishu() == 0 &&
				m_eExportGraphTemplate.IsCheckedKajuHeikin() == 0 &&
				m_eExportGraphTemplate.IsCheckedKishu(iKishuId) == 0 )
				continue;

			if( nCol == 3 || nCol == 6 )
				sHeader = g_pSystem->GetDPEIndex( 15, nCol+1, 0 ) + m_aKishu[iKishuId].sKishuName;
			else
				sHeader = g_pSystem->GetDPEIndex( 15, nCol+1, 0 );
			cExcelGraphTemplate.PutElement( 2, nExportKishuCnt*GRAPH_COL_HYO1+nCol, sHeader );
			nExportKishuCnt++;
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.20
	
	int nSublineNum = m_aSubline.GetSize();

	// �f�[�^�쐬
	int nRow = 0;
	int nSagyoshaStartRow = 2;
	for ( int iSublineId = 0; iSublineId < nSublineNum; iSublineId++ ) {

		if( m_eExportGraphTemplate.IsCheckedSubline(iSublineId) == 0 )
			continue;

		SSubline &cSubline = m_aSubline[iSublineId];

		int nSagyoshaNum = cSubline.aSagyosha.GetSize();
		int nSagyoshaMaxRow = 0;
		for ( int iSagyoshaId = 0; iSagyoshaId < nSagyoshaNum; iSagyoshaId++) {
			CString &sSagyosha = cSubline.aSagyosha[iSagyoshaId];

// Modify ... ( CHANGE )
			int bSublineWrite = TRUE;
			int nExportKishuCnt = 0;
			for ( int iKishuId = 0; iKishuId < nKishuNum; iKishuId++) {

				if( m_eExportGraphTemplate.IsCheckedZenkishu() == 0 &&
					m_eExportGraphTemplate.IsCheckedKajuHeikin() == 0 &&
					m_eExportGraphTemplate.IsCheckedKishu(iKishuId) == 0 )
					continue;

				CIntArrayEx aYosoSagyogunId;
				g_pDataManager->FindSagyoWariate(iSublineId, iSagyoshaId, iKishuId, aYosoSagyogunId);

				int nSize = aYosoSagyogunId.GetSize();
				for (int i = 0; i < nSize; i++) {

					int nYosoSagyogunId = aYosoSagyogunId[i];
					SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];

// Modify ... ( DELETE )
//					int nSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
//					for( int nSagyo = 0; nSagyo < nSagyoNum; nSagyo++ ){
// By Y.Itabashi (xxxxx) 2007.03.07

						if( bSublineWrite )
							nRow = cExcelGraphTemplate.AddRows();
						else if( nSagyoshaMaxRow == nRow ){
							nRow = cExcelGraphTemplate.AddRows();
							bSublineWrite = TRUE;
						}else
							nRow++;

						// �T�u���C����
						if( bSublineWrite )
							cExcelGraphTemplate.PutElement( nRow, cExcelGraphTemplate.eColSublineName, cSubline.sSublineName );

						// ��Ǝ�
						cExcelGraphTemplate.PutElement( nRow, nExportKishuCnt*GRAPH_COL_HYO1+cExcelGraphTemplate.eColSagyosha, sSagyosha );

// Modify ... ( CHANGE )
						// ��Ɠ��e
						cExcelGraphTemplate.PutElement( nRow, nExportKishuCnt*GRAPH_COL_HYO1+cExcelGraphTemplate.eColSagyoNaiyo,
							cYosoSagyogun.sYosoSagyogunName );
//						cExcelGraphTemplate.PutElement( nRow, nExportKishuCnt*GRAPH_COL_HYO1+cExcelGraphTemplate.eColSagyoNaiyo,
//							cYosoSagyogun.aYosoSagyo[nSagyo].sYosoSagyoName );
// By Y.Itabashi (xxxxx) 2007.03.07

						// �����E�蓮
						cExcelGraphTemplate.PutElement( nRow, nExportKishuCnt*GRAPH_COL_HYO1+cExcelGraphTemplate.eColJidoShudoKotei, cYosoSagyogun.sJidoKotei );

						// �p��
						SLineKisei *pLineKisei = FindLineKisei(iSublineId, cYosoSagyogun.sKoteiNo,
							cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
						if( pLineKisei )
							cExcelGraphTemplate.PutElement( nRow, nExportKishuCnt*GRAPH_COL_HYO1+cExcelGraphTemplate.eColShisei, pLineKisei->sEngGenjo );
						else
							cExcelGraphTemplate.PutElement( nRow, nExportKishuCnt*GRAPH_COL_HYO1+cExcelGraphTemplate.eColShisei, "" );

						// �H��
						cExcelGraphTemplate.PutElement( nRow, nExportKishuCnt*GRAPH_COL_HYO1+cExcelGraphTemplate.eColKousu, cYosoSagyogun.GetTime() );

						// ���l
						cExcelGraphTemplate.PutElement( nRow, nExportKishuCnt*GRAPH_COL_HYO1+cExcelGraphTemplate.eColBikou, "" );
					}

// Modify ... ( DELETE )
//				}
// By Y.Itabashi (xxxxx) 2007.03.07

				nExportKishuCnt++;

				nSagyoshaMaxRow = nSagyoshaMaxRow < nRow ? nRow : nSagyoshaMaxRow;
				nRow = nSagyoshaStartRow;
				bSublineWrite = FALSE;
			}
// By Y.Itabashi (xxxxx) 2007.02.19

			nSagyoshaStartRow = nSagyoshaMaxRow;
		}
	}

	return TRUE;
}

BOOL CDataManager::ExportGraphTemplateWaritsuke2(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR sPathName)
{
	enum HeaderNo {
		eHeaderSublineName = 9,
			eHeaderSagyosha = 10,
			eHeaderKishu = 11,
			eHeaderHiritsu = 12,
			eHeaderTotal = 13,
			eHeaderDiff = 14,
			eHeaderJujitsudo = 15,
	};

	int nKishuNum = m_aKishu.GetSize();
	int nSublineNum = m_aSubline.GetSize();

	// �w�b�_�[
	int nCol = 1;
	int nRow = cExcelGraphTemplate.AddRows();

	cExcelGraphTemplate.PutElement( nRow, nCol, "" );
	nRow = cExcelGraphTemplate.AddRows();
// Modify ... ( CHANGE )
	CString sHeader = g_pSystem->GetDPEIndex( 15, eHeaderSublineName, 0 );
//	CString sHeader = g_pSystem->m_aDPEInterface[15].aItem[eHeaderSublineName].aList[0];
	cExcelGraphTemplate.PutElement( nRow, nCol, sHeader );
	nCol++;
	sHeader = g_pSystem->GetDPEIndex( 15, eHeaderSagyosha, 0 );
//	sHeader = g_pSystem->m_aDPEInterface[15].aItem[eHeaderSagyosha].aList[0];
	cExcelGraphTemplate.PutElement( nRow, nCol, sHeader );
	nCol++;
	int nExportKishuCnt = 0;
	for ( int iKishuId = 0; iKishuId < nKishuNum; iKishuId++) {
		if( m_eExportGraphTemplate.IsCheckedZenkishu() == 0 &&
			m_eExportGraphTemplate.IsCheckedKajuHeikin() == 0 &&
			m_eExportGraphTemplate.IsCheckedKishu(iKishuId) == 0 )
			continue;

		sHeader = m_aKishu[iKishuId].sKishuName;
		cExcelGraphTemplate.PutElement( nRow, nCol, sHeader );
		nCol++;
		nExportKishuCnt++;
	}
	for ( iKishuId = 0; iKishuId < nKishuNum; iKishuId++) {
		if( m_eExportGraphTemplate.IsCheckedZenkishu() == 0 &&
			m_eExportGraphTemplate.IsCheckedKajuHeikin() == 0 &&
			m_eExportGraphTemplate.IsCheckedKishu(iKishuId) == 0 )
			continue;

		sHeader = g_pSystem->GetDPEIndex( 15, eHeaderHiritsu, 0 ) + m_aKishu[iKishuId].sKishuName;
//		sHeader = g_pSystem->m_aDPEInterface[15].aItem[eHeaderHiritsu].aList[0] + m_aKishu[iKishuId].sKishuName;
		cExcelGraphTemplate.PutElement( nRow, nCol, sHeader );
		nCol++;
	}
	sHeader = g_pSystem->GetDPEIndex( 15, eHeaderTotal, 0 );
//	sHeader = g_pSystem->m_aDPEInterface[15].aItem[eHeaderTotal].aList[0];
	cExcelGraphTemplate.PutElement( nRow, nCol, sHeader );
	nCol++;
	sHeader = g_pSystem->GetDPEIndex( 15, eHeaderDiff, 0 );
	cExcelGraphTemplate.PutElement( nRow, nCol, sHeader );
//	cExcelGraphTemplate.PutElement( nRow, nCol, sHeader );
	nCol++;
	sHeader = g_pSystem->GetDPEIndex( 15, eHeaderJujitsudo, 0 );
//	sHeader = g_pSystem->m_aDPEInterface[15].aItem[eHeaderJujitsudo].aList[0];
	cExcelGraphTemplate.PutElement( nRow, nCol, sHeader );
// By Y.Itabashi (xxxxx) 2007.02.20

	// �f�[�^�Z�b�g
	CString sData;
	for ( int iSublineId = 0; iSublineId < nSublineNum; iSublineId++ ) {

		if( m_eExportGraphTemplate.IsCheckedSubline(iSublineId) == 0 )
			continue;

		SSubline &cSubline = m_aSubline[iSublineId];
		int m_nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( iSublineId );

		int nSagyoshaNum = cSubline.aSagyosha.GetSize();
		int nSagyoshaMaxRow = 0;
		for ( int iSagyoshaId = 0; iSagyoshaId < nSagyoshaNum; iSagyoshaId++) {
			CString &sSagyosha = cSubline.aSagyosha[iSagyoshaId];

			int nCol = 1;
			nRow = cExcelGraphTemplate.AddRows();

			// �^�N�g
// Modify ... ( CHANGE )
			sData.Format( "%.4f", cSubline.cSeisanJoken.fTact );
			cExcelGraphTemplate.PutElement( nRow, nCol, sData );
// By Y.Itabashi (xxxxx) 2007.02.19
			nCol++;

			// ��Ǝ�
			cExcelGraphTemplate.PutElement( nRow, nCol, sSagyosha );
			nCol++;

			double fTime, fHiritsu;
			double fTotalHiritsu = 0;
			for ( int iKishuId = 0; iKishuId < nKishuNum; iKishuId++) {

				if( m_eExportGraphTemplate.IsCheckedZenkishu() == 0 &&
					m_eExportGraphTemplate.IsCheckedKajuHeikin() == 0 &&
					m_eExportGraphTemplate.IsCheckedKishu(iKishuId) == 0 )
					continue;

				SKishu &pKishu = m_aKishu[iKishuId];

				CIntArrayEx aYosoSagyogunId;
				g_pDataManager->FindSagyoWariate(iSublineId, iSagyoshaId, iKishuId, aYosoSagyogunId);

				fTime = 0;
				for (int i = 0; i < aYosoSagyogunId.GetSize(); i++) {

					int nYosoSagyogunId = aYosoSagyogunId[i];
					SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
					fTime += cYosoSagyogun.GetTime();
				}

				// ��Ǝ���
				sData.Format( "%.4f", fTime );
				cExcelGraphTemplate.PutElement( nRow, nCol, sData );

				// �䗦
				fHiritsu = fTime * pKishu.nSeisanDaisu / m_nTotalSeisanDaisu;
				sData.Empty();
				sData.Format( "%.4f", fHiritsu );
				cExcelGraphTemplate.PutElement( nRow, nCol+nExportKishuCnt, sData );		// Modify ... By Y.Itabashi (xxxxx) 2007.02.19

				fTotalHiritsu += fHiritsu;
				nCol++;
			}

			nCol += nExportKishuCnt;														// Modify ... By Y.Itabashi (xxxxx) 2007.02.19

			// �䗦���v
			sData.Empty();
			sData.Format( "%.4f", fTotalHiritsu );
			cExcelGraphTemplate.PutElement( nRow, nCol, sData );
			nCol++;

			// �^�N�g�Ƃ̍���
			double fDiff = cSubline.cSeisanJoken.fTact - fTotalHiritsu;
			sData.Empty();
			sData.Format( "%.4f", fDiff );
			cExcelGraphTemplate.PutElement( nRow, nCol, sData );
			nCol++;

			// �[���x
			double fJujitsu = fTotalHiritsu / cSubline.cSeisanJoken.fTact * 100;
			sData.Empty();
			sData.Format( "%.1f", fJujitsu );
			cExcelGraphTemplate.PutElement( nRow, nCol, sData );
			nCol++;
		}
	}

	return TRUE;
}
// By Y.Itabshi (xxxxx) 2007.02.19

// Modify ... ( ADD )
void CDataManager::SetExportCondition( bool *aCheckedSublineId, bool *aCheckedKishuId, bool bCheckedWaritsuke, bool bCheckedZenkishu, bool bCheckedKajuHeikin )
{

	m_eExportGraphTemplate.SetExportCondition( aCheckedSublineId, aCheckedKishuId, bCheckedWaritsuke, bCheckedZenkishu, bCheckedKajuHeikin );

}
// By K.Kimura (xxxxx) 2007.02.16

// Modify ... ( ADD )
void CDataManager::SaveKojunKiseiError()
{

// Modify ... ( ADD )
	if( g_pSystem->m_cUserInfo.m_nKojunKiseiIhan != 1 )
		return;
// By Y.Itabashi (xxxxx) 2007.02.28

	SaveKatashikiError( NULL, "E002002" );
	SaveLineError( NULL, "E002002" );
}

void CDataManager::AddKojunKiseiError(SKatashikiError cKatashikiError, SLineError cLineError)
{
	m_aKatashikiError.Add( cKatashikiError );
	m_aLineError.Add( cLineError );
}
// By Y.Itabashi (xxxxx) 2007.02.19

// �S�p�̃A���_�[�o�[�A�����𔼊p�ɕϊ�
void CDataManager::ConvertHankaku(CString &sName)
{
	sName.Replace("�Q", "_");
	sName.Replace("�O", "0");
	sName.Replace("�P", "1");
	sName.Replace("�Q", "2");
	sName.Replace("�R", "3");
	sName.Replace("�S", "4");
	sName.Replace("�T", "5");
	sName.Replace("�U", "6");
	sName.Replace("�V", "7");
	sName.Replace("�W", "8");
	sName.Replace("�X", "9");
}
