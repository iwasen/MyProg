#pragma once

#include "FileIO.h"

class CSasDoc : public CDocument
{
public:
	void SelectSystem();
	void SelectFolder(int folderID);
	void SelectDB(char type);
	void DeleteItem(long nItemID);
	void ChangeTitle(long nItemID, LPCTSTR pTitle);

	long m_nFolderID;
	int m_nType;

protected: // シリアライズ機能のみから作成します。
	CSasDoc();
	DECLARE_DYNCREATE(CSasDoc)

	virtual BOOL OnNewDocument();
	virtual void Serialize(CArchive& ar);

#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	void ParamCalc(int nCalcData);
	void CalcIR(int nCalcData);
	void CalcAcf(int nCalcData);
	void CalcNms(int nCalcData);
	static BOOL AbortCheck(int nPercent);
	void DeleteFolder(long nFolderID);
	void DeleteData(long nDataID);

	CString m_sFolderName;

	DECLARE_MESSAGE_MAP()
	afx_msg void OnViewUpdate();
	afx_msg void OnParamCalc();
	afx_msg void OnUpdateParamCalc(CCmdUI* pCmdUI);
	afx_msg void OnParamOutput();
	afx_msg void OnUpdateParamOutput(CCmdUI* pCmdUI);
	afx_msg void OnFileSave();
	afx_msg void OnUpdateFileSave(CCmdUI* pCmdUI);
	afx_msg void OnTemplate();
	afx_msg void OnUpdateTemplate(CCmdUI* pCmdUI);
	afx_msg void OnParamCalc2();
};
