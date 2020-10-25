#pragma once

// MMLibTextView.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CMMLibTextView �r���[

class CMMLibTextView : public CEditView
{
protected:
	CMMLibTextView();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^
	DECLARE_DYNCREATE(CMMLibTextView)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	public:
	virtual DROPEFFECT OnDragOver(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point);
	virtual BOOL OnDrop(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point);
	protected:
	virtual void OnDraw(CDC* pDC);      // ���̃r���[��`�悷�邽�߂ɃI�[�o�[���C�h���܂����B
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);

// �C���v�������e�[�V����
protected:
	virtual ~CMMLibTextView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnChange();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg LRESULT OnGetSelectedItem(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

private:
	CItem *m_pCurrentItem;
	CFont m_Font;
	COleDropTarget m_DropTarget;

	void SaveData(CItem *pItem);
	void DrawItems();
	void DrawFolderText();
	void DrawDataText();
	void DrawOtherText();
	void SetViewFont();
	void LoadTextFile(CFile *pFile);
	BOOL DropFile(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point);
public:
	virtual void OnInitialUpdate();
};
