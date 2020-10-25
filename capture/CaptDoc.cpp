// CaptDoc.cpp : CCaptureDoc �N���X�̓���̒�`���s���܂��B
//

#include "stdafx.h"
#include "Capture.h"

#include "CaptDoc.h"
#include "IniFile.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc

IMPLEMENT_DYNCREATE(CCaptureDoc, CDocument)

BEGIN_MESSAGE_MAP(CCaptureDoc, CDocument)
	//{{AFX_MSG_MAP(CCaptureDoc)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc �N���X�̍\�z/����

CCaptureDoc::CCaptureDoc()
{
	// TODO: ���̈ʒu�ɂP�񂾂��\�z�p�̃R�[�h��ǉ����Ă��������B

}

CCaptureDoc::~CCaptureDoc()
{
}

BOOL CCaptureDoc::OnNewDocument()
{
	if (!CDocument::OnNewDocument())
		return FALSE;

	SetWindowTitle(iniData.m_StartupMode);	// �^�C�g���\��

	return TRUE;
}

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc �V���A���C�[�[�V����

void CCaptureDoc::Serialize(CArchive& ar)
{
	if (ar.IsStoring())
	{
		// TODO: ���̈ʒu�ɕۑ��p�̃R�[�h��ǉ����Ă��������B
	}
	else
	{
		// TODO: ���̈ʒu�ɓǂݍ��ݗp�̃R�[�h��ǉ����Ă��������B
	}
}

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc �N���X�̐f�f

#ifdef _DEBUG
void CCaptureDoc::AssertValid() const
{
	CDocument::AssertValid();
}

void CCaptureDoc::Dump(CDumpContext& dc) const
{
	CDocument::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CCaptureDoc �R�}���h

// �E�B���h�E�Ɍ��݂̃��[�h��\��
void CCaptureDoc::SetWindowTitle(int mode)
{
	switch (mode) {
	case MODE_AUTO:
		SetTitle("����");
		break;
	case MODE_PREVIEW:
		SetTitle("����ޭ�");
		break;
	case MODE_STOP:
		SetTitle("��~");
		break;
	}
}
