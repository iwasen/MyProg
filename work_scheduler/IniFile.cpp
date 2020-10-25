// IniFile.cpp: CIniFile �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "IniFile.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//*****************************************************************************************************
//  1. �֐���
//		CIniFile::ReadIniString
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ini�t�@�C�����當����f�[�^���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pSection		[I] �Z�N�V������
//		LPCTSTR		pEntry			[I] �G���g����
//		LPCTSTR		pFileName		[I] ini�t�@�C����
//		LPCTSTR		pDefault		[I] �f�t�H���g�l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�Y���G���g���̃f�[�^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		ini�t�@�C������w�肳�ꂽ�Z�N�V�����A�G���g���̕�����f�[�^�����o���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CString CIniFile::ReadIniString(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, LPCTSTR pDefault)
{
	CString sIniData;

	::GetPrivateProfileString(pSection, pEntry, pDefault, sIniData.GetBuffer(256), 256, pFileName);
	sIniData.ReleaseBuffer();

	int n = sIniData.Find('#');
	if (n > 0)
		sIniData = sIniData.Left(n);

	sIniData.TrimLeft();
	sIniData.TrimRight();

	return sIniData;
}

//*****************************************************************************************************
//  1. �֐���
//		CIniFile::ReadIniInt
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ini�t�@�C�����琮���f�[�^���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pSection		[I] �Z�N�V������
//		LPCTSTR		pEntry			[I] �G���g����
//		LPCTSTR		pFileName		[I] ini�t�@�C����
//		int			nDefault		[I] �f�t�H���g�l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�Y���G���g���̃f�[�^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		ini�t�@�C������w�肳�ꂽ�Z�N�V�����A�G���g���̐����f�[�^�����o���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
int CIniFile::ReadIniInt(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, int nDefault)
{
	CString sDefault;
	sDefault.Format("%d", nDefault);
	CString sIniData = ReadIniString(pSection, pEntry, pFileName, sDefault);

	return atoi(sIniData);
}

//*****************************************************************************************************
//  1. �֐���
//		CIniFile::ReadIniFloat
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ini�t�@�C�����畂�������f�[�^���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pSection		[I] �Z�N�V������
//		LPCTSTR		pEntry			[I] �G���g����
//		LPCTSTR		pFileName		[I] ini�t�@�C����
//		double		fDefault		[I] �f�t�H���g�l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�Y���G���g���̃f�[�^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		ini�t�@�C������w�肳�ꂽ�Z�N�V�����A�G���g���̕��������f�[�^�����o���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
double CIniFile::ReadIniFloat(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, double fDefault)
{
	CString sDefault;
	sDefault.Format("%g", fDefault);
	CString sIniData = ReadIniString(pSection, pEntry, pFileName, sDefault);

	return atof(sIniData);
}

//*****************************************************************************************************
//  1. �֐���
//		CIniFile::ReadIniColor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ini�t�@�C������F�f�[�^���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pSection		[I] �Z�N�V������
//		LPCTSTR		pEntry			[I] �G���g����
//		LPCTSTR		pFileName		[I] ini�t�@�C����
//		COLORREF	rgbDefault		[I] �f�t�H���g�l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		COLORREF		�Y���G���g���̃f�[�^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		ini�t�@�C������w�肳�ꂽ�Z�N�V�����A�G���g���̐F(RGB)�f�[�^�����o���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
COLORREF CIniFile::ReadIniColor(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, COLORREF rgbDefault)
{
	CString sDefault;
	sDefault.Format("%d.%d.%d", GetRValue(rgbDefault), GetGValue(rgbDefault), GetBValue(rgbDefault));
	CString sColor = ReadIniString(pSection, pEntry, pFileName, sDefault);

	int n1, n2;
	COLORREF rgbColor = rgbDefault;
	if ((n1 = sColor.Find('.')) > 0) {
		if ((n2 = sColor.Find('.', n1 + 1)) > 0)
			rgbColor = RGB(atoi(sColor.Left(n1)), atoi(sColor.Mid(n1 + 1, n2 - n1 - 1)), atoi(sColor.Mid(n2 + 1)));
	}

	return rgbColor;
}

//*****************************************************************************************************
//  1. �֐���
//		CIniFile::WriteIniString
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ini�t�@�C���ɕ�����f�[�^����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pSection		[I] �Z�N�V������
//		LPCTSTR		pEntry			[I] �G���g����
//		LPCTSTR		pFileName		[I] ini�t�@�C����
//		LPCTSTR		pData			[I] �������ރf�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		ini�t�@�C���̎w�肳�ꂽ�Z�N�V�����A�G���g���ɕ�����f�[�^���������ށB
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CIniFile::WriteIniString(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, LPCTSTR pData)
{
	return ::WritePrivateProfileString(pSection, pEntry, pData, pFileName);
}

//*****************************************************************************************************
//  1. �֐���
//		CIniFile::WriteIniInt
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ini�t�@�C���ɐ����f�[�^����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pSection		[I] �Z�N�V������
//		LPCTSTR		pEntry			[I] �G���g����
//		LPCTSTR		pFileName		[I] ini�t�@�C����
//		int			nData			[I] �������ރf�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		ini�t�@�C���̎w�肳�ꂽ�Z�N�V�����A�G���g���ɐ����f�[�^���������ށB
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CIniFile::WriteIniInt(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, int nData)
{
	CString sData;

	sData.Format("%d", nData);
	return ::WritePrivateProfileString(pSection, pEntry, sData, pFileName);
}

//*****************************************************************************************************
//  1. �֐���
//		CIniFile::WriteIniFloat
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ini�t�@�C���ɕ��������f�[�^����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pSection		[I] �Z�N�V������
//		LPCTSTR		pEntry			[I] �G���g����
//		LPCTSTR		pFileName		[I] ini�t�@�C����
//		double		fData			[I] �������ރf�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		ini�t�@�C���̎w�肳�ꂽ�Z�N�V�����A�G���g���ɕ��������f�[�^���������ށB
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CIniFile::WriteIniFloat(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, double fData)
{
	CString sData;

	sData.Format("%g", fData);
	return ::WritePrivateProfileString(pSection, pEntry, sData, pFileName);
}

//////////////////////////////////////////////////////////////////////
// CIniSystem �N���X
//////////////////////////////////////////////////////////////////////

BOOL CIniSystem::ReadIniFile()
{
	const CString sIniPathName = "..\\Profile\\System.ini";

	// �t�@�C�����݃`�F�b�N
	CFileStatus cStatus;
	if (!CFile::GetStatus(sIniPathName, cStatus))
		return FALSE;

	m_sSystemServerPath = ReadIniString("Path", "SystemServerPath", sIniPathName);
	m_sPartMatrixPath = ReadIniString("Path", "Part-MatrixPath", sIniPathName);
	m_sEngMatrixPath = ReadIniString("Path", "Eng-MatrixPath", sIniPathName);
	m_sUserInfoPath = ReadIniString("Path", "UserInfoPath", sIniPathName);
	m_sUserListFilePath = ReadIniString("Path", "UserListFilePath", sIniPathName);
	m_sElementTableFilePath = ReadIniString("Path", "ElementTableFilePath", sIniPathName);
	m_sKittingTemplateFilePath = ReadIniString("Path", "KittingTemplateFilePath", sIniPathName);
// Modify ... ( ADD )
	m_sMessageFilePath = ReadIniString("Path", "MessageFilePath", sIniPathName);
	m_sMenuFilePath = ReadIniString("Path", "MenuFilePath", sIniPathName);
// By Y.Itabashi (xxxxx) 2007.01.24

	return TRUE;
}

//////////////////////////////////////////////////////////////////////
// CIniUser �N���X
//////////////////////////////////////////////////////////////////////

BOOL CIniUser::ReadIniFile(LPCTSTR pFolderName, LPCTSTR pShokuban)
{
	CString sIniPathName;

	sIniPathName.Format("%s\\%s\\%s.ini", pFolderName, pShokuban, pShokuban);

	// �t�@�C�����݃`�F�b�N
	CFileStatus cStatus;
	if (!CFile::GetStatus(sIniPathName, cStatus))
		return FALSE;

	m_sDataPath = ReadIniString("Path", "DataPath", sIniPathName);
	//(20070213 xxxxx kuwa) m_nWindowType = ReadIniInt("Window", "WindowType", sIniPathName, 3);
	m_nWindowType = ReadIniInt("Window", "WindowDisplayType", sIniPathName, 3);
	m_nWindowNo1DisplayType = ReadIniInt("Window", "WindowNo1DisplayType", sIniPathName, 1);
	m_nWindowNo2DisplayType = ReadIniInt("Window", "WindowNo2DisplayType", sIniPathName, 2);
	m_nWindowNo3DisplayType = ReadIniInt("Window", "WindowNo3DisplayType", sIniPathName, 3);
	m_rgbProcessGraphColor[0] = ReadIniColor("Color", "ProcessGraphColor1", sIniPathName);
	m_rgbProcessGraphColor[1] = ReadIniColor("Color", "ProcessGraphColor2", sIniPathName);
	m_rgbProcessGraphColor[2] = ReadIniColor("Color", "ProcessGraphColor3", sIniPathName);
	m_rgbProcessGraphColor[3] = ReadIniColor("Color", "ProcessGraphColor4", sIniPathName);
	m_rgbProcessGraphColor[4] = ReadIniColor("Color", "ProcessGraphColor5", sIniPathName);
	m_rgbSublineBarColor = ReadIniColor("Color", "SublineBarColor", sIniPathName, RGB(0,0,0));
	m_rgbKittinglineBarColor = ReadIniColor("Color", "KittinglineBarColor", sIniPathName, RGB(255,0,0));
	m_rgbSelectProcessColor = ReadIniColor("Color", "SelectProcessColor", sIniPathName, RGB(0,0,255));
	m_rgbNewMoveProcessColor = ReadIniColor("Color", "NewMoveProcessColor", sIniPathName, RGB(0,128,0));
	m_rgbKittingCancelProcessColor = ReadIniColor("Color", "KittingCancelProcessColor", sIniPathName, RGB(255,255,0));
// Modify ... ( ADD )
	m_rgbReferenceProcessColor = ReadIniColor("Color", "ReferenceProcessColor", sIniPathName, RGB(128,255,255));
// By Y.Itabashi (xxxxx) 2007.02.27
	m_rgbSelectWorkerColor = ReadIniColor("Color", "SelectWorkerColor", sIniPathName, RGB(0,0,255));
	m_rgbCommonProcessColor = ReadIniColor("Color", "CommonProcessColor", sIniPathName, RGB(255,128,0));
	m_rgbAutoProcessColor = ReadIniColor("Color", "AutoProcessColor", sIniPathName, RGB(0,0,255));
	m_rgbSemiAutoProcessColor = ReadIniColor("Color", "SemiAutoProcessColor", sIniPathName, RGB(255,255,0));
	m_rgbCommonAutoProcessColor = ReadIniColor("Color", "CommonAutoProcessColor", sIniPathName, RGB(0,128,0));
	m_rgbCommonSemiAutoProcessColor = ReadIniColor("Color", "CommonSemiAutoProcessColor", sIniPathName, RGB(255,128,0));
	m_rgbKitSublineBarColor = ReadIniColor("Color", "KitSublineBarColor", sIniPathName, RGB(0,0,128));

	return TRUE;
}

BOOL CIniUser::WriteIniFile(LPCTSTR pFolderName, LPCTSTR pShokuban)
{
	CString sIniPathName;

	sIniPathName.Format("%s\\%s\\%s.ini", pFolderName, pShokuban, pShokuban);

	if (!WriteIniString("Path", "DataPath", sIniPathName, m_sDataPath))
		return FALSE;

	if (!WriteIniInt("Window", "WindowDisplayType", sIniPathName, m_nWindowType))
		return FALSE;	// 2007.02.13 xxxxx kuwa

	if (!WriteIniInt("Window", "WindowNo1DisplayType", sIniPathName, m_nWindowNo1DisplayType))
		return FALSE;	// 2007.02.13 xxxxx kuwa

	if (!WriteIniInt("Window", "WindowNo2DisplayType", sIniPathName, m_nWindowNo2DisplayType))
		return FALSE;	// 2007.02.13 xxxxx kuwa

	if (!WriteIniInt("Window", "WindowNo3DisplayType", sIniPathName, m_nWindowNo3DisplayType))
		return FALSE;	// 2007.02.13 xxxxx kuwa

	return TRUE;
}
