#if !defined(AFX_HEADERCTRLEX_H__0B0A84D0_A720_4732_8873_4898AE2F7115__INCLUDED_)
#define AFX_HEADERCTRLEX_H__0B0A84D0_A720_4732_8873_4898AE2F7115__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// HeaderCtrlEx.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CHeaderCtrlEx �E�B���h�E

class CHeaderCtrlEx : public CHeaderCtrl
{
// �R���X�g���N�V����
public:
	CHeaderCtrlEx();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CHeaderCtrlEx)
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CHeaderCtrlEx();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CHeaderCtrlEx)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
	//}}AFX_MSG
	virtual void DrawItem(LPDRAWITEMSTRUCT lpDrawItemStruct);

	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_HEADERCTRLEX_H__0B0A84D0_A720_4732_8873_4898AE2F7115__INCLUDED_)
