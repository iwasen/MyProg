#pragma once

// OptImageEditor.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// COptImageEditor �_�C�A���O

class COptImageEditor : public CPropertyPage
{
	DECLARE_DYNCREATE(COptImageEditor)

// �R���X�g���N�V����
public:
	COptImageEditor();
	~COptImageEditor();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_OPT_IMAGE_EDITOR };
	CStatic	m_cImageEditorIcon;
	CString	m_sImageEditor;
	BOOL	m_bEditDblClick;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	public:
	virtual void OnOK();
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	afx_msg void OnSelectFile();
	virtual BOOL OnInitDialog();
	DECLARE_MESSAGE_MAP()

private:
	void DispIcon();
};
