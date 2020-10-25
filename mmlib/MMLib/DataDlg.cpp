// DataDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "Item.h"
#include "DataDlg.h"
#include "AutoNumDlg.h"
#include "TextDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CDataDlg ダイアログ


CDataDlg::CDataDlg(CWnd* pParent /*=NULL*/)
	: CDialog(g_nFontSize == 0 ? IDD_DATA : IDD_DATA_LARGE, pParent)
{
	m_sCode = _T("");
	m_sCreator = _T("");
	m_sPictColor = _T("");
	m_sPictDPI = _T("");
	m_sPictHeight = _T("");
	m_sPictWidth = _T("");
	m_sRemark = _T("");
	m_sKeyword = _T("");
	m_sTitle = _T("");

	m_pItemData = NULL;
	m_hDIB = NULL;
	m_bUpdateData = FALSE;
	m_bUpdatePict = FALSE;
	m_bUpdateSound = FALSE;
	m_bUpdateText = FALSE;
	m_pReadFileName = NULL;
	m_nReadFileName = 0;
	m_nReadCount = 0;
	m_hWndNext = NULL;
	m_hWndSel = NULL;
}

CDataDlg::~CDataDlg()
{
	if (m_hDIB != NULL)
		::GlobalFree(m_hDIB);
}


void CDataDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_PICT_WINDOW, m_cPictWindow);
	DDX_Control(pDX, IDC_PICT_CLIPBOARD, m_cPictClipBoard);
	DDX_Control(pDX, IDC_COPY_DATA, m_cCopyData);
	DDX_Control(pDX, IDC_AUTO_ROTATION, m_cAutoRotation);
	DDX_Control(pDX, IDC_AUTO_NUMBERING, m_cAutoNumbering);
	DDX_Control(pDX, IDC_AUTO_SAVE, m_cAutoSave);
	DDX_Control(pDX, IDC_PICT_ERASE, m_cPictErase);
	DDX_Control(pDX, IDCANCEL, m_cCancel);
	DDX_Control(pDX, IDC_PICT_ROTATION, m_cPictRotation);
	DDX_Control(pDX, IDC_PICT_PICTURE, m_cPictPicture);
	DDX_Control(pDX, IDC_SAVE, m_cSave);
	DDX_Control(pDX, IDC_TITLE, m_cTitle);
	DDX_Control(pDX, IDC_REMARK, m_cRemark);
	DDX_Control(pDX, IDC_KEYWORD, m_cKeyword);
	DDX_Control(pDX, IDC_CREATOR, m_cCreator);
	DDX_Control(pDX, IDC_CODE, m_cCode);
	DDX_Text(pDX, IDC_CODE, m_sCode);
	DDX_Text(pDX, IDC_CREATOR, m_sCreator);
	DDX_Text(pDX, IDC_PICT_COLOR, m_sPictColor);
	DDX_Text(pDX, IDC_PICT_DPI, m_sPictDPI);
	DDX_Text(pDX, IDC_PICT_HEIGHT, m_sPictHeight);
	DDX_Text(pDX, IDC_PICT_WIDTH, m_sPictWidth);
	DDX_Text(pDX, IDC_REMARK, m_sRemark);
	DDX_Text(pDX, IDC_KEYWORD, m_sKeyword);
	DDX_Text(pDX, IDC_TITLE, m_sTitle);
}


BEGIN_MESSAGE_MAP(CDataDlg, CDialog)
	ON_BN_CLICKED(IDC_SAVE, OnSave)
	ON_BN_CLICKED(IDC_PICT_CAMERA, OnPictCamera)
	ON_BN_CLICKED(IDC_PICT_SCANNER, OnPictScanner)
	ON_BN_CLICKED(IDC_PICT_FILE, OnPictFile)
	ON_BN_CLICKED(IDC_PICT_ROTATION, OnPictRotation)
	ON_EN_CHANGE(IDC_TITLE, OnChangeTitle)
	ON_EN_CHANGE(IDC_CODE, OnChangeCode)
	ON_EN_CHANGE(IDC_CREATOR, OnChangeCreator)
	ON_EN_CHANGE(IDC_REMARK, OnChangeRemark)
	ON_EN_CHANGE(IDC_KEYWORD, OnChangeKeyword)
	ON_BN_CLICKED(IDC_PICT_ERASE, OnPictErase)
	ON_BN_CLICKED(IDC_AUTO_NUMBERING, OnAutoNumbering)
	ON_BN_CLICKED(IDC_AUTO_SAVE, OnAutoSave)
	ON_BN_CLICKED(IDC_TEXT_DATA, OnTextData)
	ON_BN_CLICKED(IDC_COPY_DATA, OnCopyData)
	ON_BN_CLICKED(IDC_PICT_CLIPBOARD, OnPictClipboard)
	ON_WM_DESTROY()
	ON_WM_MOUSEMOVE()
	ON_WM_LBUTTONUP()
	ON_MESSAGE(WM_FRAMEBMP, OnFrameBmp)
	ON_MESSAGE(WM_SCANBMP, OnScanBmp)
	ON_WM_DRAWCLIPBOARD()
	ON_WM_CHANGECBCHAIN()
	ON_MESSAGE(WM_BUTTON_DOWN, OnPictWindow)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CDataDlg メッセージ ハンドラ

BOOL CDataDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	CString caption;
	caption.Format("%s - [%s]", m_Caption, m_pItemFolder->m_FolderRec.folderName);
	SetWindowText(caption);

	m_cCode.LimitText(LEN_CODE);
	m_cTitle.LimitText(LEN_TITLE);
	m_cRemark.LimitText(LEN_REMARK);
	m_cKeyword.LimitText(LEN_KEYWORD);

	m_cPictWindow.m_bNotifyButtonDown = TRUE;

	if (m_pItemData == NULL) {
		m_sTitle.LoadString(IDS_NO_TITLE);
		m_sCreator = GetDefaultUserName();
		//UpdateData(FALSE);

		AutoNumbering(FALSE);

		if (m_hDIB != NULL) {
			IMAGE_INFO ImageInfo;
			HDIB hDIB = m_hDIB;
			m_hDIB = NULL;
			GetImageInfo(hDIB, &ImageInfo);
			SetPicture(hDIB, &ImageInfo);
		}

		m_cCancel.SetWindowText(GetString(IDS_CLOSE));

		m_cAutoSave = theApp.GetProfileInt(g_SectionSettings, "AutoSave", 0);

		m_cAutoRotation.AddString(GetString(IDS_NO_ROTATION));
		m_cAutoRotation.AddString(GetString(IDS_RIGHT_ROTATION));
		m_cAutoRotation.AddString(GetString(IDS_HALF_ROTATION));
		m_cAutoRotation.AddString(GetString(IDS_LEFT_ROTATION));
		m_cAutoRotation.SetCurSel(0);
		m_cAutoRotation.EnableWindow((BOOL)m_cAutoSave);
	} else {
		DATAREC *pDataRec = &m_pItemData->m_DataRec;
		m_sTitle = pDataRec->title;
		m_sCode = pDataRec->code;
		m_sCreateDate = pDataRec->createDate;
		m_sCreator = pDataRec->creator;
		m_sRemark = pDataRec->remark;
		m_sKeyword = pDataRec->keyword;

		m_pItemData->m_pPcsDB->ReadPictureDataEx(pDataRec->dataID, &m_hDIB, NULL);
		if (m_hDIB != NULL) {
			m_sPictWidth.Format("%d", pDataRec->width);
			m_sPictHeight.Format("%d", pDataRec->height);
			m_sPictColor.Format("%d", pDataRec->bit);
			if (pDataRec->dpi != 0)
				m_sPictDPI.Format("%d", pDataRec->dpi);
			else
				m_sPictDPI.LoadString(IDS_UNKNOWN);

			m_cPictPicture.DrawDIB(m_hDIB);

			m_cAutoSave.EnableWindow(FALSE);
			m_cAutoRotation.EnableWindow(FALSE);
			m_cAutoNumbering.EnableWindow(FALSE);
		}

		m_pItemData->m_pPcsDB->ReadTextDataEx(pDataRec->dataID, &m_sTextData);

		m_cCancel.SetWindowText(GetString(IDS_CANCEL));
	}

	UpdateData(FALSE);

	EnableButton();
	m_cCopyData.EnableWindow(!m_DataRec.title.IsEmpty());

	NextReadFile();

	m_hWndNext = SetClipboardViewer();
	m_cPictClipBoard.EnableWindow(m_cAutoSave ? TRUE : CheckClipBoard());

	return TRUE;
}

void CDataDlg::OnSave() 
{
	if (!m_bUpdateData)
		return;

	CWaitCursor wait;
	UpdateData(TRUE);
	DATAREC dataRec;

	dataRec.creator = m_sCreator;
	dataRec.code = m_sCode;
	dataRec.title = m_sTitle;
	dataRec.createDate = m_sCreateDate;
	dataRec.remark = m_sRemark;
	dataRec.keyword = m_sKeyword;
	dataRec.dpi = m_nDPI;
	dataRec.width = atoi(m_sPictWidth);
	dataRec.height = atoi(m_sPictHeight);
	dataRec.bit = atoi(m_sPictColor);

	if (m_pItemData == NULL) {
		dataRec.folderID = m_pItemFolder->m_FolderRec.folderID;
		if (!m_pItemFolder->m_pPcsDB->AddDataEx(&dataRec))
			return;
	} else {
		dataRec.folderID = m_pItemData->m_DataRec.folderID;
		dataRec.dataID = m_pItemData->m_DataRec.dataID;
		if (!m_pItemData->m_pPcsDB->UpdateDataEx(&dataRec))
			return;
	}

	m_DataRec = dataRec;

	if (m_bUpdatePict) {
		if (m_pItemData == NULL)
			WritePicture(m_pItemFolder->m_pPcsDB, dataRec.dataID, m_hDIB);
		else
			WritePicture(m_pItemData->m_pPcsDB, dataRec.dataID, m_hDIB);

		PictErase();
	}

	if (m_bUpdateText) {
		if (m_pItemData == NULL)
			m_pItemFolder->m_pPcsDB->WriteTextDataEx(dataRec.dataID, &m_sTextData);
		else
			m_pItemData->m_pPcsDB->WriteTextDataEx(dataRec.dataID, &m_sTextData);
	}

	if (m_pItemData == NULL) {
		CAddItemData addData;
		addData.m_pItemFolder = m_pItemFolder;
		addData.m_pDataRec = &dataRec;
		m_pDoc->UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addData);

		ResetUpdateData();

		AutoNumbering(TRUE);

		m_cCopyData.EnableWindow(!m_DataRec.title.IsEmpty());
	} else {
		m_pItemData->m_DataRec = dataRec;
		m_pDoc->UpdateAllViews(NULL, VIEWHINT_UPDATEITEM, m_pItemData);

		EndDialog(IDOK);
	}

	NextReadFile();
}

void CDataDlg::NextReadFile()
{
	HDIB hDIB = NULL;
	IMAGE_INFO ImageInfo;

	if (m_nReadCount < m_nReadFileName) {
		CString &fileName = m_pReadFileName[m_nReadCount++];

		if (MMPictureReadFile(&hDIB, (LPSTR)(LPCTSTR)fileName) == 0) {
			GetImageInfo(hDIB, &ImageInfo);
			UpdateData(TRUE);
			m_sTitle = RemoveDir(fileName);
			m_sTitle = RemoveExt(m_sTitle);
			UpdateData(FALSE);
			SetPicture(hDIB, &ImageInfo);
		}
	}
}

void CDataDlg::OnPictCamera() 
{
	GetPicture(PHOTO_CAMERA);
}

void CDataDlg::OnPictScanner() 
{
	GetPicture(PHOTO_SCANNER);
}

void CDataDlg::OnPictFile() 
{
	GetPicture(PHOTO_FILE);
}

void CDataDlg::OnPictErase() 
{
	PictErase();

	m_bUpdatePict = TRUE;
	SetUpdateData();
}

void CDataDlg::PictErase()
{
	if (m_hDIB) {
		::GlobalFree(m_hDIB);
		m_hDIB = NULL;
		m_cPictPicture.DrawDIB(NULL);
		EnableButton();
	}
}

void CDataDlg::GetPicture(int nMode)
{
	HDIB hDIB = NULL;
	IMAGE_INFO ImageInfo;

	if (m_bUpdatePict) {
		if (AfxMessageBox(IDS_READ_NOTIFY, MB_OKCANCEL) == IDCANCEL)
			return;
	}

	switch (nMode) {
	case PHOTO_CAMERA:
		if (m_cAutoSave) {
			PSCD_GetBmpContinue(m_hWnd);
			hDIB = NULL;
		} else {
			if ((hDIB = PSCD_GetBmp(m_hWnd)) != NULL)
				GetImageInfo(hDIB, &ImageInfo);
		}
		break;
	case PHOTO_SCANNER:
		if (m_cAutoSave) {
			ScanBmpContinue(m_hWnd);
			hDIB = NULL;
		} else
			hDIB = ScanBmp(m_hWnd, &ImageInfo);
		break;
	case PHOTO_FILE:
		{
			CFileDialog dlg(TRUE, "bmp;jpg;png", NULL,
				OFN_HIDEREADONLY,
				GetString(IDS_PICTURE_FILE) + " (*.bmp;*.jpg;*.png)|*.bmp;*.jpg;*.png|All Files (*.*)|*.*||", this);
			char strFileBuf[32767];

			if (m_cAutoSave) {
				dlg.m_ofn.Flags |= OFN_ALLOWMULTISELECT;
				dlg.m_ofn.lpstrTitle = GetString(IDS_SELECT_PICT_FILE_MULTI);
				dlg.m_ofn.lpstrFile = strFileBuf;
				dlg.m_ofn.nMaxFile = sizeof(strFileBuf);
				if (dlg.DoModal() == IDOK) {
					POSITION pos;
					CString pathName;
					CWaitCursor wait;

					pos = dlg.GetStartPosition();
					while (pos != NULL) {
						UpdateWindow();
						pathName = dlg.GetNextPathName(pos);
						if (MMPictureReadFile(&hDIB, (LPSTR)(LPCTSTR)pathName) == 0) {
							m_sTitle = RemoveDir(pathName);
							m_sTitle = RemoveExt(m_sTitle);
							UpdateData(FALSE);
							UpdateWindow();
							OnFrameBmp((WPARAM)hDIB, 0);
						}
					}
					hDIB = NULL;
				} else
					return;
			} else {
				dlg.m_ofn.lpstrTitle = GetString(IDS_SELECT_PICT_FILE);
				if (dlg.DoModal() == IDOK) {
					if (MMPictureReadFile(&hDIB, (LPSTR)(LPCTSTR)dlg.GetPathName()) == 0)
						GetImageInfo(hDIB, &ImageInfo);
					else
						AfxMessageBox(IDS_READ_PICT_FILE_FAIL);
				} else
					return;
			}
		}
		break;
	case PHOTO_CLIPBOARD:
		if (OpenClipboard()) {
			HANDLE hMem;
			if ((hMem = GetClipboardData(CF_BITMAP)) != NULL)
				hDIB = GetDIBFromBmp((HBITMAP)hMem);

			::CloseClipboard();
		}
		break;
	case PHOTO_WINDOW:
		if (m_hWndSel != NULL) {
			CWnd *pWnd = FromHandle(m_hWndSel);

			DrawWindowFrame(pWnd);
			pWnd->SetForegroundWindow();
			pWnd->UpdateWindow();

			hDIB = GetWindowDIB(FromHandle(m_hWndSel));

			if (GetWindowTitle(pWnd, m_sTitle))
				UpdateData(FALSE);
		}
		break;
	}

	if (hDIB == NULL)
		return;

	SetPicture(hDIB, &ImageInfo);
}

LRESULT CDataDlg::OnScanBmp(WPARAM wParam, LPARAM lParam)
{
	HDIB hDIB = (HDIB)wParam;
	int nRotation;

	nRotation = m_cAutoRotation.GetCurSel();
	if (nRotation >= 1 && nRotation <= 3)
		MMPictureRotate2(hDIB, &hDIB, nRotation);

	SetPicture(hDIB, (IMAGE_INFO *)lParam);
	m_cPictPicture.UpdateWindow();

	OnSave();

	return 0;
}

LRESULT CDataDlg::OnFrameBmp(WPARAM wParam, LPARAM lParam)
{
	IMAGE_INFO ImageInfo;
	HDIB hDIB = (HDIB)wParam;
	int nRotation;

	nRotation = m_cAutoRotation.GetCurSel();
	if (nRotation >= 1 && nRotation <= 3)
		MMPictureRotate2(hDIB, &hDIB, nRotation);

	GetImageInfo(hDIB, &ImageInfo);

	SetPicture(hDIB, &ImageInfo);
	m_cPictPicture.UpdateWindow();

	OnSave();

	return 0;
}

void CDataDlg::SetPicture(HDIB hDIB, IMAGE_INFO *pImageInfo)
{
	if (CheckColorMode(hDIB)) {
		if (m_hDIB != NULL)
			GlobalFree(m_hDIB);
		m_hDIB = hDIB;

		UpdateData(TRUE);
		m_sPictWidth.Format("%d", pImageInfo->ImageWidth);
		m_sPictHeight.Format("%d", pImageInfo->ImageLength);
		m_sPictColor.Format("%d", pImageInfo->BitsPerPixel);
		if (pImageInfo->XResolution != 0) {
			LPBITMAPINFO lpBmi;
			lpBmi = (LPBITMAPINFO)::GlobalLock(m_hDIB);
			if (lpBmi->bmiHeader.biXPelsPerMeter == 0) {
				lpBmi->bmiHeader.biXPelsPerMeter = (long)(pImageInfo->XResolution / 0.0254 + 0.5);
				lpBmi->bmiHeader.biYPelsPerMeter = (long)(pImageInfo->YResolution / 0.0254 + 0.5);
			}
			::GlobalUnlock(m_hDIB);

			m_sPictDPI.Format("%d", pImageInfo->XResolution);
		} else
			m_sPictDPI.LoadString(IDS_UNKNOWN);
		UpdateData(FALSE);

		m_nDPI = pImageInfo->XResolution;

		m_cPictPicture.DrawDIB(m_hDIB);

		m_bUpdatePict = TRUE;
		SetUpdateData();
		EnableButton();
	} else
		GlobalFree(hDIB);
}

void CDataDlg::GetImageInfo(HDIB hDIB, IMAGE_INFO *pImageInfo)
{
	LPBITMAPINFO lpBmi;
	lpBmi = (LPBITMAPINFO)::GlobalLock(hDIB);

	pImageInfo->XResolution = (int)(lpBmi->bmiHeader.biXPelsPerMeter * 0.0254 + 0.5);
	pImageInfo->YResolution = (int)(lpBmi->bmiHeader.biYPelsPerMeter * 0.0254 + 0.5);
	pImageInfo->ImageWidth = lpBmi->bmiHeader.biWidth;
	pImageInfo->ImageLength = lpBmi->bmiHeader.biHeight;
	pImageInfo->BitsPerPixel = lpBmi->bmiHeader.biBitCount;
	::GlobalUnlock(hDIB);
}

BOOL CDataDlg::CheckColorMode(HDIB hDIB)
{
	LPBITMAPINFO pBmi;
	BOOL bResult = FALSE;

	pBmi = (LPBITMAPINFO)GlobalLock(hDIB);
	if (pBmi->bmiHeader.biCompression != 0) {
		AfxMessageBox(IDS_ONLY_COMPRESS, MB_ICONEXCLAMATION);
	} else {
		switch (pBmi->bmiHeader.biBitCount) {
		case 1:
		case 4:
		case 8:
		case 24:
			bResult = TRUE;
			break;
		default:
			AfxMessageBox(IDS_VALID_COLOR, MB_ICONEXCLAMATION);
			break;
		}
	}

	GlobalUnlock(hDIB);

	return bResult;
}

void CDataDlg::OnPictRotation() 
{
	if (m_hDIB != NULL) {
		MMPictureRotate2(m_hDIB, &m_hDIB, 1);
		m_cPictPicture.DrawDIB(m_hDIB);

		short width, height, color;
		MMPictureGetInfo(m_hDIB, &width, &height, &color);
		m_sPictWidth.Format("%d", width);
		m_sPictHeight.Format("%d", height);
		UpdateData(FALSE);

		m_bUpdatePict = TRUE;
		SetUpdateData();
	}
}

void CDataDlg::OnOK()
{
	ExitDialog();
}

void CDataDlg::OnCancel() 
{
	ExitDialog();
}

void CDataDlg::ExitDialog()
{
	if (m_bUpdateData) {
		UINT msg;

		if (m_pItemData == NULL)
			msg = IDS_INPUT_DATA_SAVE;
		else
			msg = IDS_UPDATE_DATA_SAVE;

		int rc = AfxMessageBox(msg, MB_ICONQUESTION | MB_YESNOCANCEL);
		switch (rc) {
		case IDYES:
			OnSave();
			EndDialog(IDOK);
			break;
		case IDNO:
			EndDialog(IDCANCEL);
			break;
		}
	} else
		EndDialog(IDCANCEL);
}


void CDataDlg::SetUpdateData()
{
	if (!m_bUpdateData) {
		m_bUpdateData = TRUE;
		m_cSave.EnableWindow(TRUE);
	}
}

void CDataDlg::ResetUpdateData()
{
	if (m_bUpdateData) {
		m_bUpdateData = FALSE;
		m_bUpdatePict = FALSE;
		m_bUpdateSound = FALSE;
		m_bUpdateText = FALSE;
		m_cSave.EnableWindow(FALSE);
	}
}

void CDataDlg::OnChangeTitle() 
{
	SetUpdateData();
}

void CDataDlg::OnChangeCode() 
{
	SetUpdateData();
}

void CDataDlg::OnChangeCreator() 
{
	SetUpdateData();
}

void CDataDlg::OnChangeRemark() 
{
	SetUpdateData();
}

void CDataDlg::OnChangeKeyword() 
{
	SetUpdateData();
}

void CDataDlg::EnableButton()
{
	m_cPictRotation.EnableWindow(m_hDIB != NULL);
	m_cPictErase.EnableWindow(m_hDIB != NULL);
}

void CDataDlg::OnAutoNumbering() 
{
	CAutoNumDlg dlg;

	if (m_pItemFolder->m_FolderRec.codeDef.IsEmpty()) {
		UpdateData(TRUE);
		dlg.m_bAutoNumbering = FALSE;
		dlg.m_sCode = m_sCode;
		dlg.m_lStep = 10;
	} else {
		dlg.m_bAutoNumbering = TRUE;
		dlg.m_sCode = m_pItemFolder->m_FolderRec.codeDef;
		dlg.m_lStep = m_pItemFolder->m_FolderRec.codeStep;
	}

	if (dlg.DoModal() == IDCANCEL)
		return;

	if (dlg.m_bAutoNumbering) {
		m_pItemFolder->m_FolderRec.codeDef = dlg.m_sCode;
		m_pItemFolder->m_FolderRec.codeStep = dlg.m_lStep;
		AutoNumbering(FALSE);
	} else {
		m_pItemFolder->m_FolderRec.codeDef.Empty();
		m_pItemFolder->m_FolderRec.codeStep = 0;
	}
	m_pItemFolder->m_pPcsDB->UpdateFolderEx(&m_pItemFolder->m_FolderRec);
}

void CDataDlg::OnAutoSave() 
{
	BOOL bAutoSave;

	bAutoSave = m_cAutoSave;
	theApp.WriteProfileInt(g_SectionSettings, "AutoSave", bAutoSave);
	m_cAutoRotation.EnableWindow(bAutoSave);

	if (bAutoSave)
		m_cPictClipBoard.EnableWindow(TRUE);
	else {
		m_cPictClipBoard.EnableWindow(CheckClipBoard());
		m_cPictClipBoard.SetCheck(0);
	}
}

void CDataDlg::AutoNumbering(BOOL bNext)
{
	if (m_pItemFolder->m_FolderRec.codeDef.IsEmpty())
		return;

	AutoNextNumbering(m_pItemFolder->m_FolderRec.codeDef, m_pItemFolder->m_FolderRec.codeStep);

	m_sCode = m_pItemFolder->m_FolderRec.codeDef;

	m_pItemFolder->m_pPcsDB->UpdateFolderEx(&m_pItemFolder->m_FolderRec);

	UpdateData(FALSE);
}

void CDataDlg::OnTextData() 
{
	CTextDlg dlg;

	dlg.m_sText = m_sTextData;

	if (dlg.DoModal() == IDOK) {
		m_sTextData = dlg.m_sText;
		m_bUpdateText = TRUE;
		SetUpdateData();
	}
}

void CDataDlg::OnCopyData() 
{
	m_sCode = m_DataRec.code;
	m_sCreator = m_DataRec.creator;
	m_sRemark = m_DataRec.remark;
	m_sKeyword = m_DataRec.keyword;
	m_sTitle = m_DataRec.title;
	UpdateData(FALSE);

	SetUpdateData();
}

void CDataDlg::OnPictClipboard() 
{
	if (!m_cPictClipBoard.GetCheck()) {
		if (m_cAutoSave)
			m_cPictClipBoard.SetCheck(1);
		else
			GetPicture(PHOTO_CLIPBOARD);
	} else
		m_cPictClipBoard.SetCheck(0);
}

void CDataDlg::OnDrawClipboard()
{
	if (m_cPictClipBoard.GetCheck()) {
		GetPicture(PHOTO_CLIPBOARD);
		OnSave();
	} else
		m_cPictClipBoard.EnableWindow(CheckClipBoard());

	if (m_hWndNext)
		::SendMessage(m_hWndNext, WM_DRAWCLIPBOARD, 0, 0);
}

BOOL CDataDlg::CheckClipBoard()
{
	UINT cf = 0;
	BOOL bOK = FALSE;

	if (OpenClipboard()) {
		while (cf = ::EnumClipboardFormats(cf)) {
			if (cf == CF_BITMAP || cf == CF_DIB) {
				bOK = TRUE;
				break;
			}
		}
		::CloseClipboard();
	}

	return bOK;
}

void CDataDlg::OnChangeCbChain(HWND hWndRemove, HWND hWndAfter) 
{
	if (hWndRemove == m_hWndNext)
		m_hWndNext = hWndAfter;

	if (m_hWndNext)
		::SendMessage(m_hWndNext, WM_CHANGECBCHAIN, (WPARAM)hWndRemove, (LPARAM)hWndAfter);
}

void CDataDlg::OnDestroy() 
{
	CDialog::OnDestroy();
	
	ChangeClipboardChain(m_hWndNext);
}

HDIB CDataDlg::GetDIBFromBmp(HBITMAP hBmp)
{
	HDIB hDIB8, hDIB24;
	long nSize8, nSize24;

	MMPictureGetBitmap8(&hDIB8, hBmp);
	MMPictureGetBitmap(&hDIB24, hBmp);

	MMPictureWriteNull(hDIB8, MMTYPE_PNG, &nSize8);
	MMPictureWriteNull(hDIB24, MMTYPE_JPG, &nSize24);

	if (nSize8 < nSize24) {
		MMPictureFree(hDIB24);
		return hDIB8;
	} else {
		MMPictureFree(hDIB8);
		return hDIB24;
	}
}

LRESULT CDataDlg::OnPictWindow(WPARAM wParam, LPARAM lParam) 
{
	if (!m_cPictWindow.GetCheck()) {
		SetCapture();
		HCURSOR hCursor = theApp.LoadCursor(IDC_SELECT_WINDOW);
		SetCursor(hCursor);
		m_cPictWindow.SetCheck(1);
		AfxGetMainWnd()->ShowWindow(SW_HIDE);
		m_hWndSel = NULL;
	} else {
		ReleaseCapture();
		m_cPictWindow.SetCheck(0);
	}

	return 0;
}

void CDataDlg::OnMouseMove(UINT nFlags, CPoint point) 
{
	if (m_cPictWindow.GetCheck()) {
		ClientToScreen(&point);

		CWnd *pWnd = WindowFromPoint(point);

		if (pWnd) {
			CWnd *pWndParent = GetTopParentWindow(pWnd);
			if (pWndParent->m_hWnd == AfxGetMainWnd()->m_hWnd)
				pWnd = NULL;
		}
		if (m_hWndSel != pWnd->GetSafeHwnd()) {
			if (m_hWndSel != NULL)
				DrawWindowFrame(FromHandle(m_hWndSel));

			if (pWnd != NULL)
				DrawWindowFrame(pWnd);

			m_hWndSel = pWnd->GetSafeHwnd();
		}
	}

	CDialog::OnMouseMove(nFlags, point);
}

void CDataDlg::DrawWindowFrame(CWnd *pWnd)
{
	CPen pen(PS_SOLID, 6, RGB(0, 0, 255));

	CDC *pDC = pWnd->GetWindowDC();

	CRect rect;
	pWnd->GetWindowRect(rect);

	int nDrawModeOrg = pDC->SetROP2(R2_NOTXORPEN);
	CPen *pPenOrg = pDC->SelectObject(&pen);

	pDC->Rectangle(0, 0, rect.Width(), rect.Height());

	pDC->SelectObject(pPenOrg);
	pDC->SetROP2(nDrawModeOrg);

	pWnd->ReleaseDC(pDC);
}

void CDataDlg::OnLButtonUp(UINT nFlags, CPoint point) 
{
	if (m_cPictWindow.GetCheck()) {
		ReleaseCapture();

		if (m_hWndSel != NULL) {
			GetPicture(PHOTO_WINDOW);
			if (m_cAutoSave.GetCheck())
				OnSave();
		}

		m_cPictWindow.SetCheck(0);
		AfxGetMainWnd()->ShowWindow(SW_SHOW);
		SetForegroundWindow();
	}

	CDialog::OnLButtonUp(nFlags, point);
}

HDIB CDataDlg::GetWindowDIB(CWnd *pWnd)
{
	CRect rect;
	pWnd->GetWindowRect(&rect);
	int nWidth = rect.Width();
	int nHeight = rect.Height();

	CDC *pDC = pWnd->GetWindowDC();
	CDC DCMem;
	DCMem.CreateCompatibleDC(pDC);

	CBitmap bmp;
	bmp.CreateCompatibleBitmap(pDC, nWidth, nHeight);

	CGdiObject *pOrg = DCMem.SelectObject(&bmp);
	DCMem.BitBlt(0, 0, nWidth, nHeight, pDC, 0, 0, SRCCOPY);
	DCMem.SelectObject(pOrg);

	pWnd->ReleaseDC(pDC);

	HDIB hDIB;
	MMPictureGetBitmap8(&hDIB, bmp);

	return hDIB;
}

CWnd *CDataDlg::GetTopParentWindow(CWnd *pWnd)
{
	CWnd *pWndParent = pWnd;
	while (CWnd *pWnd2 = pWndParent->GetParent())
		pWndParent = pWnd2;

	return pWndParent;
}

BOOL CDataDlg::GetWindowTitle(CWnd *pWnd, CString &title)
{
	CString text;

	while (true) {
		pWnd->GetWindowText(text);
		if (!text.IsEmpty()) {
			title = text;
			return TRUE;
		}

		if ((pWnd = pWnd->GetParent()) == NULL)
			return FALSE;
	}
}