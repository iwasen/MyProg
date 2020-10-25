//*****************************************************************************************************
//  1. �t�@�C����
//		Global.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CGlobal �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"
#include "Global.h"
#include "shlwapi.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//*****************************************************************************************************
//  1. �֐���
//		CGlobal::SetComboBoxFromStringArray
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		������z�񂩂�R���{�{�b�N�X�ɐݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CComboBox		&cComboBox			[O] �R���{�{�b�N�X
//		CStringArray	&cStringArray		[I] �R���{�{�b�N�X�ɐݒ肷�镶����z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		������z��̑S�v�f���R���{�{�b�N�X�ɐݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// ������z�񂩂�R���{�{�b�N�X�ɐݒ�
void CGlobal::SetComboBoxFromStringArray(CComboBox &cComboBox, CStringArray &cStringArray)
{
	int nSize = cStringArray.GetSize();
	for (int i = 0; i < nSize; i++)
		cComboBox.AddString(cStringArray[i]);
}

//*****************************************************************************************************
//  1. �֐���
//		CGlobal::SelectComboBox
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���{�{�b�N�X�̎w�肳�ꂽ�������I����Ԃɂ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CComboBox		&cComboBox		[I] �R���{�{�b�N�X
//		LPCTSTR			pStr			[I] �I�����镶����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL			TRUE�F�ݒ肵���CFALSE�F������Ȃ�����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�R���{�{�b�N�X�̒�����w�肳�ꂽ�������I����Ԃɂ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CGlobal::SelectComboBox(CComboBox &cComboBox, LPCTSTR pStr)
{
	int nIndex = cComboBox.FindStringExact(-1, pStr);
	if (nIndex != CB_ERR) {
		cComboBox.SetCurSel(nIndex);
		return TRUE;
	} else
		return FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGlobal::SelectComboBox
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���{�{�b�N�X�̎w�肳�ꂽ�f�[�^��I����Ԃɂ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CComboBox		&cComboBox		[I/O] �R���{�{�b�N�X
//		DWORD			dwData			[I] �I������f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL			TRUE�F�ݒ肵���CFALSE�F������Ȃ�����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�R���{�{�b�N�X�̒�����w�肳�ꂽ�f�[�^�������ڂ�I����Ԃɂ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CGlobal::SelectComboBox(CComboBox &cComboBox, DWORD dwData)
{
	int nCount = cComboBox.GetCount();
	for (int i = 0; i < nCount; i++) {
		if (cComboBox.GetItemData(i) == dwData) {
			cComboBox.SetCurSel(i);
			return TRUE;
		}
	}

	return FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGlobal::FileSaveDlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C���ۑ��_�C�A���O�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pDir			[I] �f�t�H���g�f�B���N�g��
//		LPCTSTR		pFilter			[I] �t�@�C������肷�邽�߂̃t�B���^
//		LPCTSTR		pFName			[I] �f�t�H���g�t�@�C����
//		LPCTSTR		pTitle			[I] �_�C�A���O�^�C�g��
//		LPCTSTR		pDefaultExt		[I] �f�t�H���g�g���q
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		���͂��ꂽ�t�@�C����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�t�@�C���ۑ��_�C�A���O��\�����A�����œ��͂��ꂽ�t�@�C������Ԃ��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CString CGlobal::FileSaveDlg(LPCTSTR pDir, LPCTSTR pFilter, LPCTSTR pFName, LPCTSTR pTitle, LPCTSTR pDefaultExt)
{
	CFileDialog cDlgFileOpen(FALSE, pDefaultExt, pFName, OFN_HIDEREADONLY | OFN_NOCHANGEDIR | OFN_EXPLORER | OFN_OVERWRITEPROMPT, pFilter, AfxGetMainWnd());

#if _MSC_VER <= 1200
	// WIndows2000�ȏ�̓_�C�A���O�̍����ɃV���[�g�J�b�g��\��
	OSVERSIONINFO osvi;
	ZeroMemory(&osvi, sizeof(OSVERSIONINFO));
	osvi.dwOSVersionInfoSize = sizeof(OSVERSIONINFO);
	GetVersionEx(&osvi);
	if (osvi.dwMajorVersion >= 5)
		cDlgFileOpen.m_ofn.lStructSize += 12;
#endif

	cDlgFileOpen.m_ofn.lpstrInitialDir = pDir;
	cDlgFileOpen.m_ofn.lpstrTitle = pTitle;

	if (cDlgFileOpen.DoModal() == IDCANCEL)
		return "";

	return cDlgFileOpen.GetPathName();
}

CString CGlobal::FileOpenDlg(LPCTSTR pDir, LPCTSTR pFilter, LPCTSTR pFName, LPCTSTR pTitle, LPCTSTR pDefaultExt)
{
	CFileDialog cDlgFileOpen(TRUE, pDefaultExt, pFName, OFN_HIDEREADONLY | OFN_NOCHANGEDIR | OFN_EXPLORER, pFilter, AfxGetMainWnd());

#if _MSC_VER <= 1200
	// WIndows2000�ȏ�̓_�C�A���O�̍����ɃV���[�g�J�b�g��\��
	OSVERSIONINFO osvi;
	ZeroMemory(&osvi, sizeof(OSVERSIONINFO));
	osvi.dwOSVersionInfoSize = sizeof(OSVERSIONINFO);
	GetVersionEx(&osvi);
	if (osvi.dwMajorVersion >= 5)
		cDlgFileOpen.m_ofn.lStructSize += 12;
#endif

	cDlgFileOpen.m_ofn.lpstrInitialDir = pDir;
	cDlgFileOpen.m_ofn.lpstrTitle = pTitle;

	if (cDlgFileOpen.DoModal() == IDCANCEL)
		return "";

	return cDlgFileOpen.GetPathName();
}

// �����񃊃\�[�XID���當����擾
CString CGlobal::GetString(UINT nID)
{
	CString sText;

	sText.LoadString(nID);

	return sText;
}

void CGlobal::FindFiles(LPCTSTR pPathName, CStringArray &aPathName)
{
	aPathName.RemoveAll();

	// �f�B���N�g�����̃t�@�C��������
	CFileFind cFileFind;
	BOOL bNext;
	if (cFileFind.FindFile(pPathName)) {
		do {
			bNext = cFileFind.FindNextFile();
			aPathName.Add(cFileFind.GetFilePath());
		} while(bNext);
	}
}

BOOL CGlobal::SelectFolderDialog(LPCSTR pInitFolder, CString &sSelectedFolder)
{
	BROWSEINFO bInfo;
	LPITEMIDLIST pIDList;
	TCHAR szDisplayName[MAX_PATH];
	TCHAR szFullPath[MAX_PATH];

	// �f�t�H���g�̃t�H���_��ini�t�@�C������擾
	CString sDataFolder = pInitFolder;
	if (_fullpath(szFullPath, sDataFolder, sizeof(szFullPath)) != NULL)
		sDataFolder = szFullPath;


	// BROWSEINFO�\���̂ɒl��ݒ�
	bInfo.hwndOwner             = AfxGetMainWnd()->m_hWnd;			// �_�C�A���O�̐e�E�C���h�E�̃n���h��
	bInfo.pidlRoot              = NULL;								// ���[�g�t�H���_������ITEMIDLIST�̃|�C���^ (NULL�̏ꍇ�f�X�N�g�b�v�t�H���_���g���܂��j
	bInfo.pszDisplayName        = szDisplayName;					// �I�����ꂽ�t�H���_�����󂯎��o�b�t�@�̃|�C���^
	bInfo.lpszTitle             = _T("�v���W�F�N�g�t�H���_�̑I��");	// �c���[�r���[�̏㕔�ɕ\������镶����
// Modify ... ( CHANGE )
	bInfo.ulFlags               = BIF_RETURNONLYFSDIRS | 0x0040 | 0x0200;	// 0x0040:BIF_NEWDIALOGSTYLE
																			// 0x0200:BIF_NONEWFOLDERBUTTON
																	// �\�������t�H���_�̎�ނ������t���O
//	bInfo.ulFlags               |= BIF_RETURNONLYFSDIRS;			// �\�������t�H���_�̎�ނ������t���O
// By Y.Itabashi (xxxxx) 2007.03.02
	bInfo.lpfn                  = BrowseCallbackProc;				// BrowseCallbackProc�֐��̃|�C���^
	bInfo.lParam                = (LPARAM)(LPCTSTR)sDataFolder;		// �R�[���o�b�N�֐��ɓn���l

	// �t�H���_�I���_�C�A���O��\��
	pIDList = ::SHBrowseForFolder(&bInfo);
	if (pIDList == NULL)
		return FALSE;

	// ItemIDList���p�X���ɕϊ�
	::SHGetPathFromIDList(pIDList, szDisplayName);

	// IDList�̃|�C���g���Ă��郁�������J��
	::CoTaskMemFree( pIDList );

	// �t�H���_���ۑ�
	sSelectedFolder = szDisplayName;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGlobal::BrowseCallbackProc
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����t�H���_���w�肷�邽�߂̃R�[���o�b�N�֐�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		HWND		hwnd			[I] �E�B���h�E�n���h��
//		UINT		uMsg			[I] �C�x���g�̎��
//		LPARAM		lParam			[I] ���g�p
//		LPARAM		lpData			[I] BROWSEINFO�\���̂ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		0
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�������C�x���g�̂Ƃ��ɏ����t�H���_��I��������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
int CALLBACK CGlobal::BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData)
{
	if (uMsg == BFFM_INITIALIZED)
		::SendMessage(hwnd, BFFM_SETSELECTION, (WPARAM)TRUE, lpData);

	return 0;
}

CString CGlobal::MakePathName(LPCTSTR pFolderName, LPCTSTR pFileName)
{
	CString sFolderName;
	CString sPathName;

	_fullpath(sFolderName.GetBuffer(MAX_PATH), pFolderName, MAX_PATH);
	sFolderName.ReleaseBuffer();

	sPathName.Format("%s\\%s", sFolderName, pFileName);

	return sPathName;
}

CString CGlobal::GetFileNameFromPath(LPCTSTR pPathName)
{
	CString sPathName(pPathName);

	::PathStripPath(sPathName.GetBuffer(0));
	sPathName.ReleaseBuffer();

	return sPathName;
}

CString CGlobal::GetFolderNameFromPath(LPCTSTR pPathName)
{
	CString sPathName(pPathName);

	::PathRemoveFileSpec(sPathName.GetBuffer(0));
	sPathName.ReleaseBuffer();

	return sPathName;
}

CString CGlobal::ChangeFolderName(LPCTSTR pFolderName, LPCTSTR pPathName)
{
	return MakePathName(pFolderName, GetFileNameFromPath(pPathName));
}
