// ChangeInfoDlg.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MMA_G.h"
#include "ChangeInfoDlg.h"
#include "GlobalData.h"
#include "General.h"
#include <io.h>


// CChangeInfoDlg ダイアログ

IMPLEMENT_DYNAMIC(CChangeInfoDlg, CDialog)

CChangeInfoDlg::CChangeInfoDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CChangeInfoDlg::IDD, pParent)
{

}

CChangeInfoDlg::~CChangeInfoDlg()
{
}

void CChangeInfoDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COMBO_SENSOR, m_cComboSensor);
	DDX_Control(pDX, IDC_EDIT_DOWNLINK, m_cEditDownlink);
	DDX_Control(pDX, IDC_EDIT_QUALITY, m_cEditQuality);
	DDX_Control(pDX, IDC_EDIT_ISS, m_cEditIss);
	DDX_Control(pDX, IDC_EDIT_ISS2, m_cEditIss2);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_cEditComment);
	DDX_Control(pDX, IDC_COMBO_CATEGORY, m_cComboCategory);
	DDX_Control(pDX, IDC_COMBO_MEASUREMENT_KIND, m_cComboMeasurementKind);
}


BEGIN_MESSAGE_MAP(CChangeInfoDlg, CDialog)
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDOK, "設定された内容にデータファイルを書き換えます。"},
	{IDCANCEL, "このダイアログを閉じます。"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoDlg::ChangeInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		情報変更ダイアログ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString		&sIdbPathName			[I] Idbファイルパス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		INT_PTR		ダイアログの返値
//*****************************************************************************************************
INT_PTR CChangeInfoDlg::ChangeInfo(const CString &sIdbPathName)
{
	m_sIdbPathName = sIdbPathName;

	return DoModal();
}

// CChangeInfoDlg メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CChangeInfoDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// idbファイル読み込み
	m_oIdbFile.Read(m_sIdbPathName);

	// センサ設定位置コンボボックス設定
	for (int i = 0; g_pSensorInfo[i].idx != -1; i++) {
		m_cComboSensor.SetItemData(m_cComboSensor.InsertString(g_pSensorInfo[i].idx, g_pSensorInfo[i].sel_dat), i);
		if (m_oIdbFile.m_sensorcosys == g_pSensorInfo[i].dat)
			m_cComboSensor.SetCurSel(g_pSensorInfo[i].idx);
	}

	// ISS Confを分解
	CStringArray saIss;
	CString sIss;
	CString sIss2;
	CGeneral::StrSplit(m_oIdbFile.m_issconfig, ",", saIss);
	if (saIss.GetSize() >= 2) {
		int nPos;
		nPos = saIss[0].Find(':');
		if (nPos != -1)
			sIss = saIss[0].Mid(nPos + 1).Trim();
		nPos = saIss[1].Find(':');
		if (nPos != -1)
			sIss2 = saIss[1].Mid(nPos + 1).Trim();
	}

	// ダイアログに表示
	m_cEditDownlink = m_oIdbFile.m_downlink;
	m_cEditQuality = m_oIdbFile.m_dataquality;
	m_cEditIss = sIss;
	m_cEditIss2 = sIss2;
	m_cEditComment = m_oIdbFile.m_comment;
	CGeneral::SetComboCategory(m_cComboCategory, m_oIdbFile.m_sCategory);
	CGeneral::SetComboMeasurementKind(m_cComboMeasurementKind, m_oIdbFile.m_sMeasurementKind);

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		OKボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CChangeInfoDlg::OnOK()
{
	CString str;

	// センサ設定位置入力チェック
	if (m_cComboSensor.GetCurSel() == -1) {
		CGeneral::Alert("センサ設定位置を選択してください。");
		return;
	}

	// カテゴリ入力チェック
	m_cComboCategory.GetWindowText(str);
	if (str.IsEmpty()) {
		CGeneral::Alert("カテゴリを選択してください。");
		return;
	}
	if (!CGeneral::CheckFileName("カテゴリ", str))
		return;

	// 計測種類入力チェック
	m_cComboMeasurementKind.GetWindowText(str);
	if (str.IsEmpty()) {
		CGeneral::Alert("計測種類を選択してください。");
		return;
	}
	if (!CGeneral::CheckFileName("計測種類", str))
		return;

	// 実行確認
	if (AfxMessageBox("設定した内容に情報を変更しますか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// ダイアログからidbデータに設定
	m_oIdbFile.m_sensorcosys = g_pSensorInfo[m_cComboSensor.GetItemData(m_cComboSensor.GetCurSel())].dat;
// 2009/07/07 Version 3.02 appended by m.hama ->
	m_oIdbFile.SetSensorIdx((int)m_cComboSensor.GetItemData(m_cComboSensor.GetCurSel()));
	m_oIdbFile.m_datacosys = m_oIdbFile.m_sensorcosys;
// <- 2009/07/07 Version 3.02 appended by m.hama
	m_oIdbFile.m_downlink = m_cEditDownlink;
	m_oIdbFile.m_dataquality = m_cEditQuality;
	if (!m_cEditIss.IsEmpty() || !m_cEditIss2.IsEmpty())
		m_oIdbFile.m_issconfig.Format("Increment: %s, Flight: %s", (CString)m_cEditIss, (CString)m_cEditIss2);
	else
		m_oIdbFile.m_issconfig.Empty();
	m_oIdbFile.m_comment = m_cEditComment;
	m_cComboCategory.GetWindowText(m_oIdbFile.m_sCategory);
	m_cComboMeasurementKind.GetWindowText(m_oIdbFile.m_sMeasurementKind);

	// フォルダ名更新
	if (!ChangeFolder())
		return;

	// idbファイル更新
	CString sFileName;
	if (FindFileName("idb", sFileName))
		m_oIdbFile.Write(sFileName.Left(sFileName.GetLength() - 4));

	// XMLファイル更新
// 2009/07/07 Version 3.02 modified by m.hama ->
//	if (FindFileName("header", sFileName)) {
//		m_oIdbFile.SetTimeZeroXML(sFileName.Left(23));
//		m_oIdbFile.WriteXml(sFileName.Left(sFileName.GetLength() - 7));
//	}
	WriteXmlFile();
// <- 2009/07/07 Version 3.02 modified by m.hama

	AfxMessageBox("情報を変更しました。", MB_OK | MB_ICONINFORMATION);

	CDialog::OnOK();
}

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoDlg::ChangeFolder
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フォルダ名を変更する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CChangeInfoDlg::ChangeFolder()
{
	CStringArray saPath;
	CString sNewPath;

	// 旧パス名のセンサ位置を変更して新しいパス名を作成
	CGeneral::StrSplit(m_sIdbPathName, "\\", saPath);
	CString sMaiFileName;
	if (saPath[saPath.GetSize() - 2].Right(4).MakeLower() == ".mai")
		sMaiFileName = saPath[saPath.GetSize() - 2];
	else if (saPath[saPath.GetSize() - 1].Right(4).MakeLower() == ".mai")
		sMaiFileName = saPath[saPath.GetSize() - 1];
	CGeneral::StrSplit(gRootData, "\\", saPath);
	saPath.Add(m_oIdbFile.m_sensorcosys);
	saPath.Add(m_oIdbFile.m_sCategory);
	saPath.Add(m_oIdbFile.m_sMeasurementKind);
	saPath.Add(sMaiFileName);
	CGeneral::StrJoin(saPath, "\\", sNewPath);

	// 旧パス名と同じなら何もしない
	if (sNewPath == m_sIdbPathName)
		return TRUE;

	// 旧パス名にワイルドカードを付加（末尾に'\0'も付加）
	CString sOldPath;
	sOldPath.Format("%s\\*%c", m_sIdbPathName, '\0');

	// ファイル移動処理
	SHFILEOPSTRUCT sf;
	memset(&sf, 0, sizeof(sf));
	sf.hwnd = m_hWnd;
	sf.wFunc = FO_MOVE;
	sf.pFrom = sOldPath;
	sf.pTo = sNewPath;
	sf.fFlags = FOF_SILENT | FOF_NOCONFIRMATION | FOF_NOCONFIRMMKDIR;
	if (SHFileOperation(&sf) != 0) {
		CGeneral::Alert("ファイルの移動に失敗しました。");
		return FALSE;
	}

	// 旧パスを削除
	CGeneral::StrSplit(m_sIdbPathName, "\\", saPath);
	for (int i = 0; i < 3; i++) {
		CGeneral::StrJoin(saPath, "\\", sOldPath);
		if (!::RemoveDirectory(sOldPath))
			break;

		saPath.RemoveAt(saPath.GetSize() - 1);
	}

	// 新しいパスを保存
	m_sIdbPathName = sNewPath;
	m_oIdbFile.m_path = sNewPath;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoDlg::FindFileName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定された拡張子のファイル名検索処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pExt			[I] 拡張子
//		CString		&sFileName		[O] 検索されたファイル名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CChangeInfoDlg::FindFileName(LPCTSTR pExt, CString &sFileName)
{
	CString sPath;
	struct _finddata_t c_file;
    intptr_t hFile;

	sPath.Format("%s\\*.%s", m_sIdbPathName, pExt);
	if ((hFile = _findfirst(sPath, &c_file )) != -1L) {
		sFileName = c_file.name;
		_findclose(hFile);
		return TRUE;
	}

	return FALSE;
}

// 2009/07/07 Version 3.02 appended by m.hama ->
//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoDlg::WriteXmlFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定された拡張子のファイル名検索処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//*****************************************************************************************************
void	CChangeInfoDlg::WriteXmlFile(void)
{
	CString sPath, sFileName;
	struct _finddata_t c_file;
    intptr_t hFile;

	sPath.Format("%s\\*.header", m_sIdbPathName);
	if ((hFile = _findfirst(sPath, &c_file )) != -1L) {
		do {
			sFileName = c_file.name;
			m_oIdbFile.WriteXml2(sFileName.Left(sFileName.GetLength() - 7));
		} while(_findnext( hFile, &c_file) == 0);
		_findclose(hFile);
	}
}
// <- 2009/07/07 Version 3.02 appended by m.hama


//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoDlg::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウメッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		MSG*	pMsg			[I] MSG構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	親クラスの返値をそのまま返す
//*****************************************************************************************************
BOOL CChangeInfoDlg::PreTranslateMessage(MSG* pMsg)
{
	// ツールチップを表示するための処理
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialog::PreTranslateMessage(pMsg);
}
