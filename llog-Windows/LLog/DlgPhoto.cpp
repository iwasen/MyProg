// DlgPhoto.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgPhoto.h"
#include "GdiPlusUtil.h"
#include "GpsData.h"
#include "DlgPhotoInfo.h"
#include "General.h"
#include <stdlib.h>
#include <locale.h> 
#include <math.h>

using namespace Gdiplus;

// CDlgPhoto ダイアログ

IMPLEMENT_DYNAMIC(CDlgPhoto, CDlgCommon)

CDlgPhoto::CDlgPhoto(CWnd* pParent /*=NULL*/)
	: CDlgCommon(CDlgPhoto::IDD, pParent)
{
	m_bUpdate = FALSE;
	m_sizeImage = 0;
	m_nCurrentSeqNo = 0;
	m_bOriginal = FALSE;
}

void CDlgPhoto::DoDataExchange(CDataExchange* pDX)
{
	CDlgCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COMBO_PHOTO, m_cComboPhoto);
	DDX_Control(pDX, IDC_STATIC_PHOTO, m_cStaticPhoto);
	DDX_Control(pDX, IDC_EDIT_DATE, m_cEditDate);
	DDX_Control(pDX, IDC_BUTTON_PHOTO_PREV, m_cButtonPhotoPrev);
	DDX_Control(pDX, IDC_BUTTON_PHOTO_NEXT, m_cButtonPhotoNext);
	DDX_Control(pDX, IDC_STATIC_PHOTO_NUM, m_cStaticPhotoNum);
	DDX_Control(pDX, IDC_EDIT_PHOTO_TIME, m_cEditPhotoTime);
	DDX_Control(pDX, IDC_EDIT_PHOTO_FOLDER, m_cEditPhotoFolder);
	DDX_Control(pDX, IDC_EDIT_PHOTO_COMMENT, m_cEditPhotoComment);
	DDX_Control(pDX, IDC_BUTTON_MAP, m_cButtonMap);
	DDX_Control(pDX, IDC_BUTTON_DELETE, m_cButtonDelete);
}


BEGIN_MESSAGE_MAP(CDlgPhoto, CDlgCommon)
	ON_WM_DROPFILES()
	ON_CBN_SELCHANGE(IDC_COMBO_PHOTO, &CDlgPhoto::OnCbnSelchangeComboPhoto)
	ON_BN_CLICKED(IDC_BUTTON_DELETE, &CDlgPhoto::OnBnClickedButtonDelete)
	ON_BN_CLICKED(IDC_BUTTON_MAP, &CDlgPhoto::OnBnClickedButtonMap)
	ON_BN_CLICKED(IDC_BUTTON_PHOTO_PREV, &CDlgPhoto::OnBnClickedButtonPhotoPrev)
	ON_BN_CLICKED(IDC_BUTTON_PHOTO_NEXT, &CDlgPhoto::OnBnClickedButtonPhotoNext)
	ON_WM_GETMINMAXINFO()
	ON_STN_DBLCLK(IDC_STATIC_PHOTO, &CDlgPhoto::OnStnDblclickStaticPhoto)
	ON_WM_SIZE()
	ON_WM_MOUSEWHEEL()
END_MESSAGE_MAP()


// CDlgPhoto メッセージ ハンドラ

BOOL CDlgPhoto::OnInitDialog()
{
	CDlgCommon::OnInitDialog();

	m_cEditDate = m_oDate.Format("%Y/%m/%d");
	DispData();

	DragAcceptFiles(TRUE);

	m_cStaticPhoto.ModifyStyle(0, SS_NOTIFY);

	SetCtlPosition(IDC_COMBO_PHOTO, 1, 0, 1, 0);
	SetCtlPosition(IDC_BUTTON_PHOTO_PREV, 1, 0, 1, 0);
	SetCtlPosition(IDC_BUTTON_PHOTO_NEXT, 1, 0, 1, 0);
	SetCtlPosition(IDC_STATIC_PHOTO_NUM, 1, 0, 1, 0);
	SetCtlPosition(IDC_STATIC_PHOTO, 0, 0, 1, 1);
	SetCtlPosition(IDC_EDIT_PHOTO_COMMENT, 0, 1, 1, 1);
	SetCtlPosition(IDC_BUTTON_DELETE_ALL, 1, 1, 1, 1);
	SetCtlPosition(IDC_BUTTON_DELETE, 1, 1, 1, 1);
	SetCtlPosition(IDC_BUTTON_ORIGINAL, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	return TRUE;
}

void CDlgPhoto::DispData()
{
	CString sSql;
	CDbResult oDbResult;

	sSql.Format("SELECT ph_seq_no,ph_file_name FROM t_photo WHERE ph_date=%s ORDER BY ph_datetime,ph_file_name", m_pDataBase->SqlDate(m_oDate));
	if (m_pDataBase->Select(sSql, oDbResult)) {
		m_cComboPhoto.ResetContent();
		int nRec = oDbResult.GetCount();
		for (int i = 0; i < nRec; i++) {
			m_cComboPhoto.SetItemData(m_cComboPhoto.AddString(oDbResult.GetTextValue(i, "ph_file_name")), oDbResult.GetIntValue(i, "ph_seq_no"));
		}
		if (nRec != 0) {
			if (m_nSelectIndex == -1)
				m_nSelectIndex = 0;
			m_cComboPhoto.SetCurSel(m_nSelectIndex);
		}
		OnCbnSelchangeComboPhoto();
	}

	m_nSelectIndex = -1;
}

void CDlgPhoto::OnDropFiles(HDROP hDropInfo)
{
	CStringArray asPathName;
	CGpsData oGpsData;

	// ドロップされたファイル数を取得
	UINT iCount = ::DragQueryFile(hDropInfo, 0xFFFFFFFF, NULL, 0);
	char filepath[MAX_PATH + 1];
	for (UINT i = 0; i < iCount; i++) {
		// ファイルの絶対パスを取得
		::DragQueryFile(hDropInfo, i, filepath, MAX_PATH + 1);

		FindJpegFile(filepath, asPathName);
	}

	// システムが確保したメモリを解放
	::DragFinish(hDropInfo);

	int nSize = asPathName.GetSize();
//	qsort(asPathName.GetData(), nSize, sizeof(CString), CompareFile);

	CString sSrcFolder;
	CString sDstPath;
	CString sFolderName;
	CString sFileName;
	CString sSaveFileName;
	double fLat, fLon;
	CDbPoint oGpsPoint;
	CString sSql;
	CDbResult oDbResult;
	for (int i = 0; i < nSize; i++) {
		CString &sSrcPath = asPathName[i];

		sSrcFolder = asPathName[i].Left(asPathName[i].ReverseFind('\\'));
		sFolderName = sSrcFolder.Mid(sSrcFolder.ReverseFind('\\') + 1);
		sFileName = ::PathFindFileName(asPathName[i]);

		CTime oTime = GetPhotoDate(sSrcPath);

		CSize sizeImage = CGdiPlusUtil::SaveJpeg(sSrcPath, sFolderName, sFileName, oTime, sSaveFileName);

		oGpsPoint.SetNull();
		sSql.Format("SELECT gl_point_data,gl_point_num FROM t_gps_logger WHERE gl_date=%s", m_pDataBase->SqlDate(oTime));
		if (m_pDataBase->Select(sSql, oDbResult)) {
			int nRec = oDbResult.GetCount();
			for (int i = 0; i < nRec; i++) {
				CDbByteData oCDbByteData;
				oDbResult.GetByteaValue(0, "gl_point_data", oCDbByteData);
				CGpsData oGpsData;
				oGpsData.ReadGpsData(oCDbByteData, oDbResult.GetIntValue(0, "gl_point_num"));

				if (oGpsData.FindGpsPoint(oTime, fLat, fLon)) {
					oGpsPoint.SetData(fLat, fLon);
					break;
				}
			}
		}

		sSql.Format("SELECT ph_seq_no FROM t_photo WHERE ph_date=%s AND ph_folder_name=%s AND ph_file_name=%s",
				m_pDataBase->SqlDate(oTime),
				m_pDataBase->SqlText(sFolderName),
				m_pDataBase->SqlText(sSaveFileName));
		m_pDataBase->Select(sSql, oDbResult);
		if (oDbResult.GetCount() == 0) {
			sSql.Format("INSERT INTO t_photo (ph_date,ph_datetime,ph_folder_name,ph_file_name,ph_width,ph_height,ph_location,ph_location_flag) VALUES (%s,%s,%s,%s,%d,%d,%s,%s)",
					m_pDataBase->SqlDate(oTime),
					m_pDataBase->SqlTimestamp(oTime),
					m_pDataBase->SqlText(sFolderName),
					m_pDataBase->SqlText(sSaveFileName),
					sizeImage.cx,
					sizeImage.cy,
					m_pDataBase->SqlPoint(oGpsPoint),
					m_pDataBase->SqlNumber(oGpsPoint.bNull ? "" : "1"));
			m_pDataBase->Exec(sSql);
		}
		m_bUpdate = TRUE;
	}

	DispData();
}

void CDlgPhoto::FindJpegFile(LPCTSTR pFileName, CStringArray &asFileName)
{
	CFileStatus fs;

	if (CFile::GetStatus(pFileName, fs)) {
		if (fs.m_attribute & 0x10) {
			CFileFind FileFind;

			// リアルタイムデータフォルダ内のファイルを検索
			CString sSearchFile;
			sSearchFile.Format("%s\\*.jpg", pFileName);
			if(!FileFind.FindFile(sSearchFile)) 
				return; 

			// 検索されたバイナリファイルを処理する
			BOOL bContinue = TRUE;
			while(bContinue){ 
				bContinue = FileFind.FindNextFile();

				// 検索されたバイナリファイルを配列に追加する
				FindJpegFile(FileFind.GetFilePath(), asFileName);
			}
		} else {
			if (_stricmp(::PathFindExtension(pFileName), ".jpg") == 0)
				asFileName.Add(pFileName);
		}
	}
}

int CDlgPhoto::CompareFile(const void *arg1, const void *arg2)
{
	CFileStatus fs1, fs2;

	CFile::GetStatus(*(CString *)arg1, fs1);
	CFile::GetStatus(*(CString *)arg2, fs2);

	return (int)(fs1.m_mtime.GetTime() - fs2.m_mtime.GetTime());
}

void CDlgPhoto::OnCbnSelchangeComboPhoto()
{
	CString sPhotoNum;
	CString sPhotoFolder;
	CString sPhotoTime;
	CString sPhotoComment;
	BOOL bMap;
	BOOL bDelete;

	UpdateComment();

	int nIndex = m_cComboPhoto.GetCurSel();
	if (nIndex != CB_ERR) {
		int nSeqNo = m_cComboPhoto.GetItemData(nIndex);

		CString sSql;
		CDbResult oDbResult;

		sSql.Format("SELECT ph_date,ph_folder_name,ph_file_name,ph_datetime,ph_width,ph_height,ph_comment,ph_location FROM t_photo WHERE ph_seq_no=%d", nSeqNo);
		if (m_pDataBase->Select(sSql, oDbResult)) {
			if (oDbResult.GetCount() != 0) {
				m_cStaticPhoto.DispJpeg(oDbResult.GetTextValue(0, "ph_folder_name"), oDbResult.GetTextValue(0, "ph_file_name"), oDbResult.GetDateValue(0, "ph_date"), m_bOriginal);
				m_sizeImage.cx = oDbResult.GetIntValue(0, "ph_width");
				m_sizeImage.cy = oDbResult.GetIntValue(0, "ph_height");
			}
		}

		sPhotoNum.Format("%d/%d", nIndex + 1, m_cComboPhoto.GetCount());
		sPhotoFolder = oDbResult.GetTextValue(0, "ph_folder_name");
		sPhotoTime = CString(oDbResult.GetTextValue(0, "ph_datetime")).Right(8);
		sPhotoComment = oDbResult.GetTextValue(0, "ph_comment");

		m_nCurrentSeqNo = nSeqNo;
		m_sCurrentComment = sPhotoComment;
		bMap = !oDbResult.IsNull(0, "ph_location");
		bDelete = TRUE;
	} else {
		m_cStaticPhoto.Clear();
		m_nCurrentSeqNo = 0;
		bMap = FALSE;
		bDelete = FALSE;
	}

	if (nIndex != CB_ERR && nIndex > 0)
		m_cButtonPhotoPrev.EnableWindow(TRUE);
	else {
		if (CWnd::GetFocus() == &m_cButtonPhotoPrev)
			SetFocus();
		m_cButtonPhotoPrev.EnableWindow(FALSE);
	}

	if (nIndex != CB_ERR && nIndex < m_cComboPhoto.GetCount() - 1)
		m_cButtonPhotoNext.EnableWindow(TRUE);
	else {
		if (CWnd::GetFocus() == &m_cButtonPhotoNext)
			SetFocus();
		m_cButtonPhotoNext.EnableWindow(FALSE);
	}

	m_cStaticPhotoNum.SetWindowText(sPhotoNum);
	m_cEditPhotoFolder = sPhotoFolder;
	m_cEditPhotoTime = sPhotoTime;
	m_cEditPhotoComment = sPhotoComment;
	m_cButtonMap.EnableWindow(bMap);
	m_cButtonDelete.EnableWindow(bDelete);
}

void CDlgPhoto::OnCancel()
{
	UpdateComment();
	EndDialog(m_bUpdate ? IDOK : IDCANCEL);
}

void CDlgPhoto::OnBnClickedButtonDelete()
{
	int nIndex = m_cComboPhoto.GetCurSel();
	if (nIndex == CB_ERR)
		return;

	if (::AfxMessageBox("削除しますか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	BOOL bDeleteFile;
	switch (::AfxMessageBox("写真ファイルも削除しますか？", MB_YESNOCANCEL | MB_ICONQUESTION)) {
	case IDCANCEL:
		return;
	case IDYES:
		bDeleteFile = TRUE;
		break;
	case IDNO:
		bDeleteFile = FALSE;
		break;
	}

	CString sWhere;
	sWhere.Format("ph_seq_no=%d", m_cComboPhoto.GetItemData(nIndex));
	DeletePhoto(sWhere, bDeleteFile);

	m_nSelectIndex = nIndex;
	if (m_nSelectIndex >= m_cComboPhoto.GetCount() - 1)
		m_nSelectIndex--;

	DispData();
}

void CDlgPhoto::OnBnClickedButtonMap()
{
	CString sUrl;

	int nIndex = m_cComboPhoto.GetCurSel();
	if (nIndex != CB_ERR) {
		int nSeqNo = m_cComboPhoto.GetItemData(nIndex);
		sUrl.Format("%s/ph_gmap.php?seq_no=%d", URL_LLOG, nSeqNo);
		::ShellExecute(NULL, "open", sUrl, NULL, NULL, SW_SHOWNORMAL);
	}
}

void CDlgPhoto::OnBnClickedButtonPhotoPrev()
{
	int nIndex = m_cComboPhoto.GetCurSel();
	if (nIndex != CB_ERR && nIndex > 0) {
		m_cComboPhoto.SetCurSel(--nIndex);
		OnCbnSelchangeComboPhoto();

		if (nIndex > 0)
			PreloadPhoto(nIndex - 1);
	}
}

void CDlgPhoto::OnBnClickedButtonPhotoNext()
{
	int nIndex = m_cComboPhoto.GetCurSel();
	if (nIndex != CB_ERR && nIndex < m_cComboPhoto.GetCount() - 1) {
		m_cComboPhoto.SetCurSel(++nIndex);
		OnCbnSelchangeComboPhoto();

		if (nIndex < m_cComboPhoto.GetCount() - 1)
			PreloadPhoto(nIndex + 1);
	}
}

void CDlgPhoto::PreloadPhoto(int nIndex)
{
	CString sSql;
	CDbResult oDbResult;

	int nSeqNo = m_cComboPhoto.GetItemData(nIndex);
	sSql.Format("SELECT ph_date,ph_folder_name,ph_file_name FROM t_photo WHERE ph_seq_no=%d", nSeqNo);
	if (m_pDataBase->Select(sSql, oDbResult)) {
		if (oDbResult.GetCount() != 0) {
			m_cStaticPhoto.PreloadJpeg(oDbResult.GetTextValue(0, "ph_folder_name"), oDbResult.GetTextValue(0, "ph_file_name"), oDbResult.GetDateValue(0, "ph_date"), m_bOriginal);
		}
	}
}

CTime CDlgPhoto::GetPhotoDate(const CString &sPathName)
{
	CString sDate;
	WCHAR wFileName[_MAX_PATH] = {0};
	setlocale(LC_ALL, "");

	//マルチバイト文字をワイド文字に変換する
	size_t nConvertedChars;
	mbstowcs_s(&nConvertedChars, wFileName, _MAX_PATH, sPathName, sPathName.GetLength() + 1);

	Image oImage(wFileName);

	PropertyItem *pi = GetPropertyItem(oImage, PropertyTagExifDTOrig);
	if (pi->type == PropertyTagTypeASCII)
		sDate = (LPCTSTR)pi->value;

	delete[] pi;

	if (!sDate.IsEmpty() && sDate[0] != '0') {
		CTime oTime(atoi(sDate.Left(4)), atoi(sDate.Mid(5, 2)), atoi(sDate.Mid(8, 2)), atoi(sDate.Mid(11, 2)), atoi(sDate.Mid(14, 2)), atoi(sDate.Mid(17, 2)));
		return oTime;
	} else {
		CFileStatus fs;
		CFile::GetStatus(sPathName, fs);
		return fs.m_mtime;
	}
}

void CDlgPhoto::OnGetMinMaxInfo(MINMAXINFO* lpMMI)
{
/*
	if (m_sizeImage.cx != 0 && !m_bOriginal) {
		CRect rectWindow;
		GetWindowRect(rectWindow);

		CRect rectPhoto;
		m_cStaticPhoto.GetClientRect(rectPhoto);

		int cx = m_sizeImage.cx + rectWindow.Width() - rectPhoto.Width();
		int cy = m_sizeImage.cy + rectWindow.Height() - rectPhoto.Height();

		CRect rectDesktop;
		CWnd::GetDesktopWindow()->GetClientRect(rectDesktop);
		int x = (rectDesktop.Width() - cx) / 2;
		int y = (rectDesktop.Height() - cy) / 2;
		if (x < 0)
			x = 0;
		if (y < 0)
			y = 0;

		lpMMI->ptMaxSize = CPoint(cx, cy);
		lpMMI->ptMaxPosition = CPoint(x, y);
	}
*/
	CDlgCommon::OnGetMinMaxInfo(lpMMI);
}

void CDlgPhoto::UpdateComment()
{
	if (m_nCurrentSeqNo != 0) {
		CString sComment = m_cEditPhotoComment;
		if (m_sCurrentComment != sComment) {
			CString sSql;
			sSql.Format("UPDATE t_photo SET ph_comment = %s WHERE ph_seq_no=%d", m_pDataBase->SqlText(sComment), m_nCurrentSeqNo);
			m_pDataBase->Exec(sSql);

			m_sCurrentComment = sComment;
		}
	}
}

void CDlgPhoto::DeletePhoto(CString sWhere, BOOL bDeleteFile)
{
	CString sSql;
	CDbResult oDbResult;

	sSql.Format("SELECT ph_date,ph_seq_no,ph_folder_name,ph_file_name FROM t_photo WHERE %s", sWhere);
	if (m_pDataBase->Select(sSql, oDbResult)) {
		int nRec = oDbResult.GetCount();
		for (int i = 0; i < nRec; i++) {
			sSql.Format("DELETE FROM t_photo WHERE ph_seq_no=%d", oDbResult.GetIntValue(i, "ph_seq_no"));
			m_pDataBase->Exec(sSql);

			if (bDeleteFile) {
				CString path;
				CFileStatus status;

				path = CGeneral::GetOriginalPhotoPath(oDbResult.GetDateValue(i, "ph_date"), oDbResult.GetTextValue(i, "ph_folder_name"), oDbResult.GetTextValue(i, "ph_file_name"));
				if (CFile::GetStatus(path, status))
					CFile::Remove(path);

				path = CGeneral::GetSmallPhotoPath(oDbResult.GetDateValue(i, "ph_date"), oDbResult.GetTextValue(i, "ph_folder_name"), oDbResult.GetTextValue(i, "ph_file_name"));
				if (CFile::GetStatus(path, status))
					CFile::Remove(path);
			}
		}
	}

	m_bUpdate = TRUE;
}

void CDlgPhoto::OnStnDblclickStaticPhoto()
{
	if (m_nCurrentSeqNo == 0)
		return;

	CString sSql;
	CDbResult oDbResult;

	sSql.Format("SELECT ph_date,ph_folder_name,ph_file_name FROM t_photo WHERE ph_seq_no=%d", m_nCurrentSeqNo);
	m_pDataBase->Select(sSql, oDbResult);
	CString sPathName = CGeneral::GetSmallPhotoPath(oDbResult.GetDateValue(0, "ph_date"), oDbResult.GetTextValue(0, "ph_folder_name"), oDbResult.GetTextValue(0, "ph_file_name"));

	CString sDate;
	WCHAR wFileName[_MAX_PATH] = {0};
	setlocale(LC_ALL, "");

	//マルチバイト文字をワイド文字に変換する
	size_t nConvertedChars;
	mbstowcs_s(&nConvertedChars, wFileName, _MAX_PATH, sPathName, sPathName.GetLength() + 1);

	Image oImage(wFileName);

	PropertyItem *pi;

	CDlgPhotoInfo dlg;

	pi = GetPropertyItem(oImage, PropertyTagEquipModel);
	if (pi != NULL) {
		dlg.m_sModelName = (LPCTSTR)pi->value;
		delete[] pi;
	}

	pi = GetPropertyItem(oImage, PropertyTagExifExposureTime);
	if (pi != NULL) {
		dlg.m_sShutterSpeed.Format("1/%.0f 秒", (double)*((int *)pi->value + 1) / (double)*(int *)pi->value);
		delete[] pi;
	} else {
		pi = GetPropertyItem(oImage, PropertyTagExifShutterSpeed);
		if (pi != NULL) {
			dlg.m_sShutterSpeed.Format("1/%.0f 秒", pow(2, (double)*(int *)pi->value / (double)*((int *)pi->value + 1)));
			delete[] pi;
		}
	}

	pi = GetPropertyItem(oImage, PropertyTagExifAperture);
	if (pi != NULL) {
		dlg.m_sAperture.Format("F%.1f", sqrt(pow(2, (double)*(UINT *)pi->value / (double)*((UINT *)pi->value + 1))));
		delete[] pi;
	} else {
		pi = GetPropertyItem(oImage, PropertyTagExifFNumber);
		if (pi != NULL) {
			dlg.m_sAperture.Format("F%.1f", (double)*(int *)pi->value / (double)*((int *)pi->value + 1));
			delete[] pi;
		}
	}

	pi = GetPropertyItem(oImage, PropertyTagExifISOSpeed);
	if (pi != NULL) {
		dlg.m_sIsoSpeed.Format("%d", *(short *)pi->value);
		delete[] pi;
	}

	pi = GetPropertyItem(oImage, PropertyTagExifExposureBias);
	if (pi != NULL) {
		dlg.m_sExposureBias.Format("%.1f EV", (double)*(int *)pi->value / (double)*((int *)pi->value + 1));
		delete[] pi;
	}

	pi = GetPropertyItem(oImage, PropertyTagExifFocalLength);
	if (pi != NULL) {
		dlg.m_sFocalLength.Format("%.1f mm", (double)*(int *)pi->value / (double)*((int *)pi->value + 1));
		delete[] pi;
	}

	dlg.DoModal();
}

PropertyItem *CDlgPhoto::GetPropertyItem(Image &oImage, int nTag)
{
	UINT size = oImage.GetPropertyItemSize(nTag);
	if (size != 0) {
		PropertyItem *pi = new PropertyItem[size];
		if (pi != NULL) {
			oImage.GetPropertyItem(nTag, size, pi);
			return pi;
		}
	}
	return NULL;
}

void CDlgPhoto::OnSize(UINT nType, int cx, int cy)
{
	CDlgCommon::OnSize(nType, cx, cy);

	CRect rcClient;
	GetWindowRect(rcClient);
//	BOOL bOriginal = rcClient.Height() > 800;
	BOOL bOriginal = IsZoomed();

	if (bOriginal != m_bOriginal) {
		m_bOriginal = bOriginal;

		PostMessage(WM_COMMAND, (CBN_SELCHANGE << 16) | m_cComboPhoto.GetDlgCtrlID(), (LPARAM)m_cComboPhoto.GetSafeHwnd());
	}
}

BOOL CDlgPhoto::OnMouseWheel(UINT nFlags, short zDelta, CPoint pt)
{
	if (zDelta > 0)
		OnBnClickedButtonPhotoPrev();
	else if (zDelta < 0)
		OnBnClickedButtonPhotoNext();

	return CDlgCommon::OnMouseWheel(nFlags, zDelta, pt);
}
