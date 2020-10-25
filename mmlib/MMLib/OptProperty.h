#pragma once

// OptProperty.h : �w�b�_�[ �t�@�C��
//

#include "OptFolder.h"
#include "OptFont.h"
#include "OptOrder.h"
#include "OptTwain.h"
#include "OptImageEditor.h"

/////////////////////////////////////////////////////////////////////////////
// COptProperty

class COptProperty : public CPropertySheet
{
	DECLARE_DYNAMIC(COptProperty)

// �R���X�g���N�V����
public:
	COptProperty(UINT nIDCaption, CWnd* pParentWnd = NULL, UINT iSelectPage = 0);
	COptProperty(LPCTSTR pszCaption, CWnd* pParentWnd = NULL, UINT iSelectPage = 0);

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

// �C���v�������e�[�V����
public:
	virtual ~COptProperty();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
	DECLARE_MESSAGE_MAP()

public:
	COptFolder m_OptFolder;
	COptFont m_OptFont;
	COptOrder m_OptOrder;
	COptTwain m_OptTwain;
	COptImageEditor m_OptImageEditor;

private:
	void AddPropertyPage();
};
