//*****************************************************************************************************
//  1. �t�@�C����
//		AutoConv.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����H�w�l�ϊ��N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2013.01.31 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "AutoConv.h"
#include "MMA_G.h"
#include "GlobalData.h"
#include "Complement.h"
#include "General.h"
#include "GraphFrm.h"
#include "Define.h"

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::CAutoConv
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CAutoConv::CAutoConv()
{
	// �����H�w�l�ϊ��t�H���_�p�X�ݒ�`�F�b�N
	if (gAutoConvPath[0] == '\0')
		return;

	m_bExit = FALSE;
	m_hMainWnd = NULL;
	m_hThread = AfxBeginThread(AutoConvertThreadEntry, this, THREAD_PRIORITY_LOWEST)->m_hThread;
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::~CAutoConv
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CAutoConv::~CAutoConv()
{
	// ���[�J�X���b�h�X���b�h�̏I����҂�
	m_bExit = TRUE;
	m_oEvent.SetEvent();
	::WaitForSingleObject(m_hThread, 1000);
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::AutoConvertThreadEntry
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����H�w�l�ϊ��`�F�b�N�X���b�h�G���g��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPVOID		pParam			[I] �X���b�h�ɓn���ꂽ�p�����[�^�iCAutoConv�ւ̃|�C���^�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
UINT CAutoConv::AutoConvertThreadEntry(LPVOID pParam)
{
	((CAutoConv *)pParam)->CheckAutoConvert();
	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::CheckAutoConvert
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����H�w�l�ϊ��`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CAutoConv::CheckAutoConvert()
{
	// mai�t�@�C�������p�X
	CString sMaiFilePath;
	sMaiFilePath.Format("%s\\*.mai", gAutoConvPath);

	while (!m_bExit) {
		// mai�t�@�C��������
		CFileFind oFileFind;
		BOOL bFind = oFileFind.FindFile(sMaiFilePath);
		while (bFind) {
			bFind = oFileFind.FindNextFile();

			CMaiFile oMaiFile;

			if (GetNewestMaiFile(oMaiFile, oFileFind.GetFileName())) {
				CString sMaiPathName;
				sMaiPathName.Format("%s\\%s", gAutoConvPath, oMaiFile.m_maiFname);
				if (oMaiFile.ReadMaiFile2(sMaiPathName, FALSE)) {
					if (CheckDataFileExist(oMaiFile)) {
						if (m_hMainWnd != NULL) {
							::SendMessage(m_hMainWnd, WM_USER_AUTO_CONV, (WPARAM)&oMaiFile, (LPARAM)&sMaiPathName);
							break;
						}
					}
				}
			}
		}

		m_oEvent.Lock(1000);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::ExecAutoConvert
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����H�w�l�ϊ����s����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CMaiFile		*pMaiFile			[I] mai�t�@�C���f�[�^
//		CString			*pMaiFilePath		[I] mai�t�@�C���p�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CAutoConv::ExecAutoConvert(CMaiFile *pMaiFile, CString *pMaiFilePath)
{
	if (ConvertProc(*pMaiFile, *pMaiFilePath)) {
		MoveDataFiles(*pMaiFile, "�����ς݃f�[�^");
		((CFrameWnd *)AfxGetMainWnd())->GetActiveDocument()->UpdateAllViews(NULL);

		// �����x�O���t�\��
		CGraphFrame* pFrame = new CGraphFrame;
		if (!pFrame->DispGraph(pMaiFile->m_Idb.m_sIdbPath, GRAPH_ACCEL, DATA_TYPE_MMA))
			delete pFrame;
	} else
		MoveDataFiles(*pMaiFile, "�G���[�f�[�^");
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::GetNewestMaiFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ŐV��mai�t�@�C���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CMaiFile		&oMaiFile			[I] mai�t�@�C���f�[�^
//		CString			&sFileName			[I] mai�t�@�C����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CAutoConv::GetNewestMaiFile(CMaiFile &oMaiFile, const CString &sFileName)
{
	// �S�Ă�mai�t�@�C�����擾
	GetMaiFiles(oMaiFile, sFileName);
	INT_PTR nCount = oMaiFile.m_Name.GetSize();

	// �R�s�[���łȂ����Ƃ��m���߂邽��200ms��ɍēx�擾
	Sleep(200);
	GetMaiFiles(oMaiFile, sFileName);

	// �ŐV��mai�t�@�C�����擾
	if (nCount != 0 && nCount == oMaiFile.m_Name.GetSize()) {
		CString sTemp;
		int n = 0;
		for (int i = 0; i < oMaiFile.m_Name.GetSize(); i++) {
			if (oMaiFile.m_Name.GetAt(i).Mid(30, 14) > sTemp) {
				sTemp = oMaiFile.m_Name[i].Mid(30, 14);
				n = i;
			}
		}

		// �ŐV��mai�t�@�C����ۑ�
		oMaiFile.m_maiFname = oMaiFile.m_Name[n];
		return TRUE;
	}

	return FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::GetMaiFiles
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�S�Ă�mai�t�@�C���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CMaiFile		&oMaiFile			[I] mai�t�@�C���f�[�^
//		CString			&sFileName			[I] mai�t�@�C����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CAutoConv::GetMaiFiles(CMaiFile &oMaiFile, const CString &sFileName)
{
	CString sMaiFilePath;
	sMaiFilePath.Format("%s\\%s*.mai", gAutoConvPath, sFileName.Left(30));

	oMaiFile.m_Name.RemoveAll();
	CFileFind oFileFind;
	BOOL bFind = oFileFind.FindFile(sMaiFilePath);
	while (bFind) {
		bFind = oFileFind.FindNextFile();

		oMaiFile.m_Name.Add(oFileFind.GetFileName());
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::CheckDataFileExist
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�Sbin�t�@�C�����݃`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CMaiFile		&oMaiFile			[I] mai�t�@�C���f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F�S�đ��݂���@FALSE�F���݂��Ȃ����̂�����
//*****************************************************************************************************
BOOL CAutoConv::CheckDataFileExist(CMaiFile &oMaiFile)
{
	if (!CheckBinFileExist(oMaiFile.m_BinName0))
		return FALSE;

	if (!CheckBinFileExist(oMaiFile.m_BinName1))
		return FALSE;

	if (!CheckBinFileExist(oMaiFile.m_BinName2))
		return FALSE;

	if (!CheckBinFileExist(oMaiFile.m_BinName3))
		return FALSE;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::CheckBinFileExist
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�Sbin�t�@�C�����݃`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CStringArray		&aBinFiles			[I] bin�t�@�C����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F�S�đ��݂���@FALSE�F���݂��Ȃ����̂�����
//*****************************************************************************************************
BOOL CAutoConv::CheckBinFileExist(const CStringArray &aBinFiles)
{
	CFile file;
	CString sBinPath;

	for (int i = 0; i < aBinFiles.GetCount(); i++) {
		sBinPath.Format("%s\\%s", gAutoConvPath, aBinFiles[i]);
		if (!file.Open(sBinPath, CFile::modeRead | CFile::shareDenyWrite))
			return FALSE;

		file.Close();
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::ConvertProc
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H�w�l�ϊ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CMaiFile		&oMaiFile			[I] mai�t�@�C���f�[�^
//		CString			&sMaiPathName		[I] mai�t�@�C���p�X��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CAutoConv::ConvertProc(CMaiFile &oMaiFile, const CString &sMaiPathName)
{
	// mai�t�@�C������e���ڂ��擾
	if (!oMaiFile.GetItems())
		return FALSE;

	int nSensorIndex = GetSensorIndex(oMaiFile.m_RsuId);
	if (nSensorIndex == -1) {
		CGeneral::Alert("rack_pos.def��RSU ID���o�^����Ă��܂���B");
		return FALSE;
	}

	if (g_pSensorInfo[nSensorIndex].category == NULL || g_pSensorInfo[nSensorIndex].category[0] == '\0') {
		CGeneral::Alert("rack_pos.def�ɃJ�e�S�����o�^����Ă��܂���B");
		return FALSE;
	}
	oMaiFile.m_Idb.m_sCategory = g_pSensorInfo[nSensorIndex].category;

	if (g_pSensorInfo[nSensorIndex].measurement_kind == NULL || g_pSensorInfo[nSensorIndex].measurement_kind[0] == '\0') {
		CGeneral::Alert("rack_pos.def�Ɍv����ނ��o�^����Ă��܂���B");
		return FALSE;
	}
	oMaiFile.m_Idb.m_sMeasurementKind = g_pSensorInfo[nSensorIndex].measurement_kind;

	CDataDateTime start, end;
	oMaiFile.GetStartEnd(&start, &end);

	CString str;
	CString	pos, period;
	pos = g_pSensorInfo[nSensorIndex].dat;
	period.Format("%04d%02d%02d%02d%02d%02d-%04d%02d%02d%02d%02d%02d"
		, start.m_YY
		, start.m_MM
		, start.m_DD
		, start.m_hh
		, start.m_mm
		, start.m_ss
		, end.m_YY
		, end.m_MM
		, end.m_DD
		, end.m_hh
		, end.m_mm
		, end.m_ss);
	if (oMaiFile.mkDir(pos, oMaiFile.m_Idb.m_sCategory, oMaiFile.m_Idb.m_sMeasurementKind))
		return FALSE;

	// �Z���T�[IDX
	oMaiFile.m_Idb.SetSensorIdx(nSensorIndex);
	// �f�[�^�͈�
	oMaiFile.m_Idb.SetPeriod(period);
	// �l�b�g���[�NID�iUNIT ID��ʂS���j
	oMaiFile.m_Idb.SetNetworkid(oMaiFile.m_NetworkId);
	// ���j�b�gID�iUNIT ID���ʂS���j
	oMaiFile.m_Idb.SetRsuId(oMaiFile.m_RsuId);
	// �Z���T�[ID
	oMaiFile.m_Idb.SetSensorId(oMaiFile.m_SensorId);
	// �^�C���[��
	str.Format("%04d%02d%02d%02d%02d%02d"
		, start.m_YY
		, start.m_MM
		, start.m_DD
		, start.m_hh
		, start.m_mm
		, start.m_ss);
	oMaiFile.m_Idb.SetTimeZero(str);
	// �T���v�����[�g
	oMaiFile.m_Idb.SetSampleRate(oMaiFile.m_SampleRate);
	// �ۑ��p�X��
	oMaiFile.m_Idb.SetPath(oMaiFile.m_ConvDataPath);
	// Sensor Co Sys
	oMaiFile.m_Idb.SetSensorcosys(pos);
	// Data Co Sys
	oMaiFile.m_Idb.SetDatacosys(pos);
	// �J�e�S��
	oMaiFile.m_Idb.SetCategory(oMaiFile.m_Idb.m_sCategory);
	// �v�����
	oMaiFile.m_Idb.SetMeasurementKind(oMaiFile.m_Idb.m_sMeasurementKind);

	// �X�^�[�g�^�C��YYYY�i�N�jMM(��)DD(��)SSSSSSSS�i�~���b�j
	oMaiFile.m_Idb.SetStartTime(
		start.m_YY
		, start.m_MM
		, start.m_DD
		, (start.m_hh * 60 * 60 + start.m_mm * 60 + start.m_ss) * 1000);

	// ���͊J�n���Ԃ������n��
	oMaiFile.m_In_startTime = start;

	// ��ԏ���
	Complement complement;
	if (complement.ComplementData(gAutoConvPath, oMaiFile.m_maiFname)) {
		// Data Quality Measure�Ƀ��b�Z�[�W�ǉ�
		oMaiFile.m_Idb.m_dataquality = "Termperature Data Error was found and complemented.";

		// mai�t�@�C���ēǂݍ���
		oMaiFile.ReadMaiFile2(sMaiPathName, FALSE);
	}

	// �w�莞���̏o�̓t�@�C������������
	oMaiFile.WriteData(gAutoConvPath, 0, end - start);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::GetSensorIndex
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�Z���T�[�C���f�b�N�X�擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int				nRsuId				[I] RSU ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
int CAutoConv::GetSensorIndex(int nRsuId)
{
	if (g_pSensorInfo != NULL) {
		for (int i = 0; g_pSensorInfo[i].idx != -1; i++) {
			if (g_pSensorInfo[i].rsu_id == nRsuId) {
				if (g_pSensorInfo[i].category == NULL || g_pSensorInfo[i].category[0] == '\0') {
					CGeneral::Alert("rack_pos.def�ɃJ�e�S�����o�^����Ă��܂���B");
					return -1;
				}

				if (g_pSensorInfo[i].measurement_kind == NULL || g_pSensorInfo[i].measurement_kind[0] == '\0') {
					CGeneral::Alert("rack_pos.def�Ɍv����ނ��o�^����Ă��܂���B");
					return -1;
				}

				return i;
			}
		}
	}

	CGeneral::Alert("rack_pos.def��RSU ID���o�^����Ă��܂���B");

	return -1;
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::MoveDataFiles
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�t�@�C���ړ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CMaiFile		&oMaiFile			[I] mai�t�@�C���f�[�^
//		CString			sFolder				[I] �ړ���t�H���_
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CAutoConv::MoveDataFiles(const CMaiFile &oMaiFile, const CString sFolder)
{
	// �f�B���N�g���쐬
	CString sMoveFolder;
	sMoveFolder.Format("%s\\%s", gAutoConvPath, sFolder);
	::CreateDirectory(sMoveFolder, NULL);

	// mai�t�@�C���ړ�
	for (int i = 0; i < oMaiFile.m_Name.GetSize(); i++) {
		CString sFromMaiPath;
		CString sToMaiPath;
		sFromMaiPath.Format("%s\\%s", gAutoConvPath, oMaiFile.m_Name[i]);
		sToMaiPath.Format("%s\\%s", sMoveFolder, oMaiFile.m_Name[i]);
		::DeleteFile(sToMaiPath);
		::MoveFile(sFromMaiPath, sToMaiPath);
	}

	// bin�t�@�C���폜
	MoveBinFiles(oMaiFile.m_BinName0, sMoveFolder);
	MoveBinFiles(oMaiFile.m_BinName1, sMoveFolder);
	MoveBinFiles(oMaiFile.m_BinName2, sMoveFolder);
	MoveBinFiles(oMaiFile.m_BinName3, sMoveFolder);
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoConv::MoveBinFiles
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		bin�t�@�C���ړ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CStringArray	&aBinFiles			[I] bin�t�@�C����
//		CString			&sMoveFolder		[I] �ړ���t�H���_
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CAutoConv::MoveBinFiles(const CStringArray &aBinFiles, const CString &sMoveFolder)
{
	for (int i = 0; i < aBinFiles.GetSize(); i++) {
		// bin�t�@�C���폜
		CString sFromBinPath;
		CString sToBinPath;
		sFromBinPath.Format("%s\\%s", gAutoConvPath, aBinFiles[i]);
		sToBinPath.Format("%s\\%s", sMoveFolder, aBinFiles[i]);
		::DeleteFile(sToBinPath);
		::MoveFile(sFromBinPath, sToBinPath);
	}
}
