// TempExportDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "comres.h"
#include "Common.h"
#include "FileIO.h"
#include "TempExportDlg.h"
#include "Help\CommonHelp.h"

/////////////////////////////////////////////////////////////////////////////
// CTempExportDlg ダイアログ


CTempExportDlg::CTempExportDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CTempExportDlg::IDD, pParent)
{
	m_nSelectID = -1;
}


void CTempExportDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_TEMPLATE_LIST, m_cTemplateList);
}


BEGIN_MESSAGE_MAP(CTempExportDlg, CDialog)
	ON_BN_CLICKED(IDC_ALL_SELECT, OnAllSelect)
	ON_BN_CLICKED(IDC_ALL_REMOVE, OnAllRemove)
	ON_WM_HELPINFO()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CTempExportDlg メッセージ ハンドラ


BOOL CTempExportDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	SetNsTmpList(m_nSelectID);

	return TRUE;
}

void CTempExportDlg::OnOK()
{
	CFileDialog fileDlg(FALSE, "nst", "template.nst",
			OFN_OVERWRITEPROMPT | OFN_HIDEREADONLY | OFN_NOCHANGEDIR,
			"Template File (*.nst)|*.nst||", this, 0);

	if (fileDlg.DoModal() == IDCANCEL)
		return;

	if (ExportTemplate(fileDlg.GetPathName()))
		EndDialog(IDOK);
}

void CTempExportDlg::SetNsTmpList(long nID)
{
	CDbNsTmp dbNsTmp;
	DbNsTmpRec dbNsTmpRec;
	int i, nIndex;
	CString str;

	if (!dbNsTmp.Open())
		return;

	for (i = 0; ; i++) {
		if (!dbNsTmp.ReadRecNext(&dbNsTmpRec, NULL))
			break;

		nIndex = m_cTemplateList.AddString(dbNsTmpRec.sName);
		m_cTemplateList.SetItemData(nIndex, dbNsTmpRec.nNsTmpID);

		if (nID == -1 || dbNsTmpRec.nNsTmpID == nID)
			m_cTemplateList.SetSel(nIndex);
	}
}

void CTempExportDlg::OnAllSelect()
{
	int nCount;
	int i;

	nCount = m_cTemplateList.GetCount();
	for (i = 0; i < nCount; i++)
		m_cTemplateList.SetSel(i);
}

void CTempExportDlg::OnAllRemove()
{
	int nCount;
	int i;

	nCount = m_cTemplateList.GetCount();
	for (i = 0; i < nCount; i++)
		m_cTemplateList.SetSel(i, FALSE);
}

BOOL CTempExportDlg::ExportTemplate(LPCTSTR pFileName)
{
	CDbNsTmp dbNsTmp;
	DbNsTmpRec dbNsTmpRec;
	NsTmpData nsTmpData;
	char name[LEN_NSTMPNAME + 1];
	CFile exportFile;

	if (!dbNsTmp.Open()) {
		AfxMessageBox(IDS_ERR_EXPORT_FILE);
		return FALSE;
	}

	if (!exportFile.Open(pFileName, CFile::modeCreate | CFile::modeWrite | CFile::shareExclusive))
		return FALSE;

	while (dbNsTmp.ReadRecNext(&dbNsTmpRec, &nsTmpData)) {
		if (CheckSelect(dbNsTmpRec.nNsTmpID)) {
			memset(name, 0, sizeof(name));
			strcpy_s(name, dbNsTmpRec.sName);
			exportFile.Write(name, sizeof(name));
			exportFile.Write(&nsTmpData, sizeof(nsTmpData));
		}
	}

	return TRUE;
}

BOOL CTempExportDlg::CheckSelect(long nID)
{
	int nCount;
	int i;

	nCount = m_cTemplateList.GetCount();
	for (i = 0; i < nCount; i++) {
		if (m_cTemplateList.GetItemData(i) == (DWORD)nID) {
			if (m_cTemplateList.GetSel(i) > 0)
				return TRUE;
			else
				break;
		}
	}

	return FALSE;
}

BOOL CTempExportDlg::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDOK, IDH_TEMP_EXPORT_OK,
		IDCANCEL, IDH_TEMP_EXPORT_CANCEL,
		IDC_TEMPLATE_LIST, IDH_TEMP_EXPORT_TEMPLATE_LIST,
		IDC_ALL_SELECT, IDH_TEMP_EXPORT_ALL_SELECT,
		IDC_ALL_REMOVE, IDH_TEMP_EXPORT_ALL_REMOVE,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}
