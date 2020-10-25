// Message.cpp: CMessage �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "Message.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// Modify ... ( DELETE )
// �t�@�C�����̓V�X�e���pINI�t�@�C���ɋL�q
// �t�@�C����
//#define FILENAME_MESSAGE_FILE		"messagelist.msg"
// By Y.Itabashi (xxxxx) 2007.01.24

// Modify ... ( CHANGE )	# �t�@�C�����̓V�X�e���pINI�t�@�C���ɋL�q
// ���b�Z�[�W�t�@�C���ǂݍ���
BOOL CMessage::ReadMessageFile(LPCTSTR pFileName)
//BOOL CMessage::ReadMessageFile(LPCTSTR pFolderName)
{
//	CString sFileName;
	CStdioFile cFile;
	CString sString;

//	sFileName = CGlobal::MakePathName(pFolderName, FILENAME_MESSAGE_FILE);

	if (!cFile.Open(pFileName, CFile::modeRead | CFile::shareDenyNone))
//	if (!cFile.Open(sFileName, CFile::modeRead | CFile::shareDenyNone))
		return FALSE;

	while (cFile.ReadString(sString)) {
		if (sString.GetLength() > 8 && sString[7] == ' ') {
			CString *pMessage = m_aMessage.AddElement(sString.Left(7));
			*pMessage = sString.Mid(8);
		}
	}

	return TRUE;
}
// By Y.Itabashi (xxxxx) 2007.01.24

CString CMessage::GetMessage(LPCTSTR pMessageNo)
{
	CString *pMessage = m_aMessage.LookupElement(pMessageNo);
	if (pMessage != NULL)
		return *pMessage;
	else
		return "no message";
}
