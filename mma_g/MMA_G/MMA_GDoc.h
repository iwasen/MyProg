// MMA_GDoc.h : CMMA_GDoc �N���X�̃C���^�[�t�F�C�X
//


#pragma once
#include "treedir.h"


class CMMA_GDoc : public CDocument
{
protected: // �V���A��������̂ݍ쐬���܂��B
	CMMA_GDoc();
	DECLARE_DYNCREATE(CMMA_GDoc)

// ����
public:
	int m_nSelectedItem;
	CTreeDir m_TreeDir;

// �I�[�o�[���C�h
public:
	virtual BOOL OnNewDocument();
	virtual void Serialize(CArchive& ar);

// ����
public:
	virtual ~CMMA_GDoc();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:
	void GetCsvData(CTreeCtrl &treeCtrl, HTREEITEM hTree, CStringArray &saCsvData);
	void AddCsvData(CString &csv, CString data);

// �������ꂽ�A���b�Z�[�W���蓖�Ċ֐�
protected:
	DECLARE_MESSAGE_MAP()
	afx_msg void OnDispGraph();
	afx_msg void OnDispDigitalData();
	afx_msg void OnConvert();
	afx_msg void OnOutputCsv();
	afx_msg void OnDelete();
	afx_msg void OnExec();
	afx_msg void OnReload();
	afx_msg void OnDispTempGraph();
	afx_msg void OnChangeInfo();
	afx_msg void OnUpdateChangeInfo(CCmdUI *pCmdUI);
	afx_msg void OnUpdateDispGraph(CCmdUI *pCmdUI);
	afx_msg void OnUpdateDispTempGraph(CCmdUI *pCmdUI);
	afx_msg void OnUpdateDispDigitalData(CCmdUI *pCmdUI);
	afx_msg void OnUpdateOutputCsv(CCmdUI *pCmdUI);
	afx_msg void OnRealtimeStart();
	afx_msg void OnUpdateRealtimeStart(CCmdUI *pCmdUI);
	afx_msg void OnRealtimeStop();
	afx_msg void OnUpdateRealtimeStop(CCmdUI *pCmdUI);
	afx_msg void OnExport();
	afx_msg void OnUpdateDelete(CCmdUI *pCmdUI);
	afx_msg void OnConvertMme();
public:
	afx_msg void OnConvertAsu();
};


