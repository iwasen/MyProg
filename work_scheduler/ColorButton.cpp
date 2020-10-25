//*****************************************************************************************************
//  1. �t�@�C����
//		ColorButton.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CColorButton �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2004.12.16 S.Imura(xxxxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "Control.h"
#include "ColorButton.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNAMIC(CColorButton, CButton)

/////////////////////////////////////////////////////////////////////////////
// CColorButton

CColorButton::CColorButton()
{
	// �f�B�t�H���g�J���[
	m_fg = ::GetSysColor(COLOR_BTNTEXT);
	m_bg = ::GetSysColor(COLOR_BTNFACE);
	m_disabled = ::GetSysColor(COLOR_GRAYTEXT);

	m_checkState = 0;

	m_fSizeRatio = 1.4f;
	m_lfWeight = FW_SEMIBOLD;
}

CColorButton::~CColorButton()
{
}


BEGIN_MESSAGE_MAP(CColorButton, CButton)
	//{{AFX_MSG_MAP(CColorButton)
	ON_WM_CREATE()
	//}}AFX_MSG_MAP
	ON_MESSAGE(BM_SETCHECK, OnSetCheck)
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. �֐���
//		DrawItem
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�{�^���`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPDRAWITEMSTRUCT lpDIS	[I]
//----------------------------------------------------------------------------------------------------
//�@4. �߂�l
//----------------------------------------------------------------------------------------------------
//�@5. �����T�v
//		�{�^���̕`��
//			�E�{�^���w�i�̓h�ׂ�
//			�E�t�H�[�J�X���̓_���`��
//			�E�{�^�����̕`��
//			�E�e�L�X�g�̕`��
//----------------------------------------------------------------------------------------------------
//�@6. ���l
//		�I�[�i�[�`��̃{�^���̕\����̊O�ς��ω�����ƁA�t���[�����[�N���Ăяo���܂�
//		CButton::DrawItem�Q��
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2004.12.16 S.Imura(xxxxx) �V�K�쐬
//*****************************************************************************************************
void CColorButton::DrawItem(LPDRAWITEMSTRUCT lpDIS)
{
	CDC* pDC = CDC::FromHandle(lpDIS->hDC);

	UINT state = lpDIS->itemState;
	CRect btnRect;
	btnRect.CopyRect(&lpDIS->rcItem);

	//
	// �{�^���h�ׂ�
	//
	bool bStateButton = false;
	DWORD style = GetStyle();

	if( (style&BS_AUTOCHECKBOX)||(style&BS_AUTORADIOBUTTON)||(style&BS_AUTO3STATE)||
		(style&BS_CHECKBOX)||(style&BS_RADIOBUTTON)||(style&&BS_3STATE) )
	{
		bStateButton = true;
	}

	if(bStateButton && GetCheck()==1)
	{
		int rcol = GetRValue(GetBackGroundColor());
		int gcol = GetGValue(GetBackGroundColor());
		int bcol = GetBValue(GetBackGroundColor());
		rcol += 127;
		gcol += 127;
		bcol += 127;
		if(rcol>255) rcol = 255;
		if(gcol>255) gcol = 255;
		if(bcol>255) bcol = 255;
		pDC->FillSolidRect(&btnRect, RGB(rcol,gcol,bcol));
	}
	else
	{
		pDC->FillSolidRect(&btnRect, GetBackGroundColor());
	}

	//
	// �t�H�[�J�X���̏���
	//
	if (state & ODS_FOCUS)
	{
		CRect focusRect(btnRect);
		focusRect.DeflateRect(4,4);
		pDC->DrawFocusRect(&focusRect);

		pDC->Draw3dRect(btnRect, RGB(0,0,0), RGB(0,0,0));
	}


	// ���̕`��
	if (style & BS_FLAT)	// ���ʃ{�^��
	{
		pDC->Draw3dRect(&btnRect, RGB(0,0,0), RGB(0,0,0));

		BYTE rcol = (GetRValue(GetBackGroundColor())+255)/2;
		BYTE gcol = (GetGValue(GetBackGroundColor())+255)/2;
		BYTE bcol = (GetBValue(GetBackGroundColor())+255)/2;

		CRect wkRect = btnRect;
		wkRect.DeflateRect(2,2);
		pDC->Draw3dRect(&wkRect, RGB(rcol, gcol, bcol), RGB(rcol, gcol, bcol));
	}
	else				// ���̃{�^��
	{
		if (state & ODS_SELECTED)
		{
			pDC->Draw3dRect(&btnRect, RGB(0,0,0), RGB(0,0,0));

		}
		else
		{
			if(bStateButton && GetCheck())
			{
				pDC->Draw3dRect(&btnRect, RGB(0,0,0), RGB(255,255,255));
			}
			else
			{
				pDC->Draw3dRect(&btnRect, RGB(255,255,255), RGB(0,0,0));
			}

			BYTE rcol = GetRValue(GetBackGroundColor())/2;
			BYTE gcol = GetGValue(GetBackGroundColor())/2;
			BYTE bcol = GetBValue(GetBackGroundColor())/2;

			CRect wkRect = btnRect;
			wkRect.DeflateRect(1,1);
			pDC->Draw3dRect(&wkRect, RGB(127+rcol,127+gcol,127+bcol), RGB(rcol,gcol,bcol));

		}
	}

	//
	// �e�L�X�g�̕`��
	//
    const int bufSize = 512;
    TCHAR buffer[bufSize];
    GetWindowText(buffer, bufSize);

	CRect textRect(btnRect);
	COLORREF textcolor = GetTextColor();
	if (state & ODS_SELECTED)
	{
		// �{�^���������̃e�L�X�g��`
		textRect.DeflateRect(2,2,0,0);
	}
	if (state & ODS_DISABLED)
	{
		// ���� �Y���V�Ĕ��F�Ńe�L�X�g�`��i���̓I�Ɂj
		CRect wkRect(textRect);
		wkRect.OffsetRect(1,1);
		DrawButtonText(pDC, wkRect, buffer, RGB(224,224,224));
		textcolor = GetDisabledColor();
    }
	DrawButtonText(pDC, textRect, buffer, textcolor);
}

//*****************************************************************************************************
//  1. �֐���
//		DrawButtonText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�{�^���e�L�X�g�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			*DC			[I]		�f�o�C�X�R���e�L�X�g�ւ̃|�C���^
//		CRect		R			[I]		�e�L�X�g�̈ʒu
//		char		*Buf		[I]		�e�L�X�g
//		COLORREF	TextColor	[I]		�e�L�X�g�F
//----------------------------------------------------------------------------------------------------
//�@4. �߂�l
//----------------------------------------------------------------------------------------------------
//�@5. �����T�v
//		�{�^���e�L�X�g�̕`��
//----------------------------------------------------------------------------------------------------
//�@6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2004.12.16 S.Imura(xxxxx) �V�K�쐬
//*****************************************************************************************************
void CColorButton::DrawButtonText(CDC *DC, CRect R, const char *Buf, COLORREF TextColor)
{
    COLORREF prevColor = DC->SetTextColor(TextColor);
    DC->SetBkMode(TRANSPARENT);
	DC->DrawText(Buf, strlen(Buf), R, DT_CENTER|DT_VCENTER|DT_SINGLELINE);
	DC->SetTextColor(prevColor);
}

/////////////////////////////////////////////////////////////////////////////
// CColorButton ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		PreSubclassWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T�u�N���X�� �O����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//----------------------------------------------------------------------------------------------------
//�@4. �߂�l
//----------------------------------------------------------------------------------------------------
//�@5. �����T�v
//		�{�^���X�^�C���ɃI�[�i�[�h���[��ǉ�
//----------------------------------------------------------------------------------------------------
//�@6. ���l
//		�E�B���h�E���T�u�N���X������O�� �t���[�����[�N���ďo����܂�
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2004.12.16 S.Imura(xxxxx) �V�K�쐬
//*****************************************************************************************************
void CColorButton::PreSubclassWindow()
{
	DWORD style = GetStyle();

	// �I�[�i�[�h���[�X�^�C����ݒ�
	if( ( style & BS_OWNERDRAW ) != BS_OWNERDRAW)
	{
		ModifyStyle( 0, BS_OWNERDRAW);
	}

	// �t�H���g�� �傫�� �������ɂ���
	CFont* pFont2 = GetFont();
	LOGFONT logfont;
	pFont2->GetLogFont(&logfont);
	logfont.lfHeight = (long)(logfont.lfHeight*m_fSizeRatio);
	logfont.lfWeight = m_lfWeight;
	m_Font.CreateFontIndirect(&logfont);

	SetFont(&m_Font, TRUE);

	CButton::PreSubclassWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		OnCreate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		WM_CREATE���b�Z�[�W�ʒm����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//----------------------------------------------------------------------------------------------------
//�@4. �߂�l
//----------------------------------------------------------------------------------------------------
//�@5. �����T�v
//----------------------------------------------------------------------------------------------------
//�@6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2004.12.16 S.Imura(xxxxx) �V�K�쐬
//*****************************************************************************************************
int CColorButton::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CButton::OnCreate(lpCreateStruct) == -1)
		return -1;

	// TODO: ���̈ʒu�ɌŗL�̍쐬�p�R�[�h��ǉ����Ă�������

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		OnSetCheck
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		BM_SETCHECK ���b�Z�[�W�ʒm����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM wParam	[I] �`�F�b�N���
//		LPARAM lParam	[I] 0 ���g�p
//----------------------------------------------------------------------------------------------------
//�@4. �߂�l
//		LRESULT �펞0��ԓ�
//----------------------------------------------------------------------------------------------------
//�@5. �����T�v
//----------------------------------------------------------------------------------------------------
//�@6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2004.12.16 S.Imura(xxxxx) �V�K�쐬
//*****************************************************************************************************
LRESULT CColorButton::OnSetCheck(WPARAM wParam, LPARAM lParam)
{
	if(wParam == BST_CHECKED)
	{
		m_checkState = 1;
	}
	else if(wParam == BST_INDETERMINATE)
	{
		m_checkState = 2;
	}
	else if(wParam == BST_UNCHECKED)
	{
		m_checkState = 0;
	}

	return 0;
}
