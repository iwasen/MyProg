// Microsoft Visual C++ �Ŏ����������ꂽ IDispatch ���b�v �N���X

// ����: ���̃t�@�C���̓��e��ҏW���Ȃ��ł��������B ���̃N���X���ēx
//  Microsoft Visual C++ �Ő������ꂽ�ꍇ�A�ύX���㏑�����܂��B


#include "stdafx.h"
#include "spreadsheet.h"

/////////////////////////////////////////////////////////////////////////////
// CSpreadSheet

IMPLEMENT_DYNCREATE(CSpreadSheet, CWnd)

/////////////////////////////////////////////////////////////////////////////
// CSpreadSheet �v���p�e�B

long CSpreadSheet::GetActiveCol()
{
	long result;
	GetProperty(0x2, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetActiveCol(long propVal)
{
	SetProperty(0x2, VT_I4, propVal);
}

long CSpreadSheet::GetActiveRow()
{
	long result;
	GetProperty(0x3, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetActiveRow(long propVal)
{
	SetProperty(0x3, VT_I4, propVal);
}

BOOL CSpreadSheet::GetAllowCellOverflow()
{
	BOOL result;
	GetProperty(0x4, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAllowCellOverflow(BOOL propVal)
{
	SetProperty(0x4, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetAllowDragDrop()
{
	BOOL result;
	GetProperty(0x5, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAllowDragDrop(BOOL propVal)
{
	SetProperty(0x5, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetAllowMultiBlocks()
{
	BOOL result;
	GetProperty(0x6, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAllowMultiBlocks(BOOL propVal)
{
	SetProperty(0x6, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetAllowUserFormulas()
{
	BOOL result;
	GetProperty(0x7, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAllowUserFormulas(BOOL propVal)
{
	SetProperty(0x7, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetArrowsExitEditMode()
{
	BOOL result;
	GetProperty(0x8, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetArrowsExitEditMode(BOOL propVal)
{
	SetProperty(0x8, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetAutoCalc()
{
	BOOL result;
	GetProperty(0x9, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAutoCalc(BOOL propVal)
{
	SetProperty(0x9, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetAutoClipboard()
{
	BOOL result;
	GetProperty(0xa, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAutoClipboard(BOOL propVal)
{
	SetProperty(0xa, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetAutoSize()
{
	BOOL result;
	GetProperty(0xb, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAutoSize(BOOL propVal)
{
	SetProperty(0xb, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetBlockMode()
{
	BOOL result;
	GetProperty(0xc, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetBlockMode(BOOL propVal)
{
	SetProperty(0xc, VT_BOOL, propVal);
}

long CSpreadSheet::GetButtonDrawMode()
{
	long result;
	GetProperty(0xd, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetButtonDrawMode(long propVal)
{
	SetProperty(0xd, VT_I4, propVal);
}

long CSpreadSheet::GetCellType()
{
	long result;
	GetProperty(0x12, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetCellType(long propVal)
{
	SetProperty(0x12, VT_I4, propVal);
}

BOOL CSpreadSheet::GetChangeMade()
{
	BOOL result;
	GetProperty(0x13, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetChangeMade(BOOL propVal)
{
	SetProperty(0x13, VT_BOOL, propVal);
}

CString CSpreadSheet::GetClip()
{
	CString result;
	GetProperty(0x14, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetClip(LPCTSTR propVal)
{
	SetProperty(0x14, VT_BSTR, propVal);
}

CString CSpreadSheet::GetClipValue()
{
	CString result;
	GetProperty(0x15, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetClipValue(LPCTSTR propVal)
{
	SetProperty(0x15, VT_BSTR, propVal);
}

long CSpreadSheet::GetCol()
{
	long result;
	GetProperty(0x16, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetCol(long propVal)
{
	SetProperty(0x16, VT_I4, propVal);
}

long CSpreadSheet::GetCol2()
{
	long result;
	GetProperty(0x17, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetCol2(long propVal)
{
	SetProperty(0x17, VT_I4, propVal);
}

BOOL CSpreadSheet::GetColHidden()
{
	BOOL result;
	GetProperty(0x19, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetColHidden(BOOL propVal)
{
	SetProperty(0x19, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetColPageBreak()
{
	BOOL result;
	GetProperty(0x1a, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetColPageBreak(BOOL propVal)
{
	SetProperty(0x1a, VT_BOOL, propVal);
}

long CSpreadSheet::GetColsFrozen()
{
	long result;
	GetProperty(0x1b, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetColsFrozen(long propVal)
{
	SetProperty(0x1b, VT_I4, propVal);
}

long CSpreadSheet::GetCursorStyle()
{
	long result;
	GetProperty(0x1c, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetCursorStyle(long propVal)
{
	SetProperty(0x1c, VT_I4, propVal);
}

long CSpreadSheet::GetCursorType()
{
	long result;
	GetProperty(0x1d, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetCursorType(long propVal)
{
	SetProperty(0x1d, VT_I4, propVal);
}

BOOL CSpreadSheet::GetDataChanged()
{
	BOOL result;
	GetProperty(0x1e, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetDataChanged(BOOL propVal)
{
	SetProperty(0x1e, VT_BOOL, propVal);
}

long CSpreadSheet::GetDataColCnt()
{
	long result;
	GetProperty(0x1f, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetDataColCnt(long propVal)
{
	SetProperty(0x1f, VT_I4, propVal);
}

CString CSpreadSheet::GetDataField()
{
	CString result;
	GetProperty(0x20, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetDataField(LPCTSTR propVal)
{
	SetProperty(0x20, VT_BSTR, propVal);
}

BOOL CSpreadSheet::GetDataFillEvent()
{
	BOOL result;
	GetProperty(0x21, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetDataFillEvent(BOOL propVal)
{
	SetProperty(0x21, VT_BOOL, propVal);
}

long CSpreadSheet::GetDataRowCnt()
{
	long result;
	GetProperty(0x22, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetDataRowCnt(long propVal)
{
	SetProperty(0x22, VT_I4, propVal);
}

LPUNKNOWN CSpreadSheet::GetDataSource()
{
	LPUNKNOWN result;
	GetProperty(0x23, VT_UNKNOWN, (void*)&result);
	return result;
}

void CSpreadSheet::SetDataSource(LPUNKNOWN propVal)
{
	SetProperty(0x23, VT_UNKNOWN, propVal);
}

BOOL CSpreadSheet::GetDAutoCellTypes()
{
	BOOL result;
	GetProperty(0x24, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetDAutoCellTypes(BOOL propVal)
{
	SetProperty(0x24, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetDAutoFill()
{
	BOOL result;
	GetProperty(0x25, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetDAutoFill(BOOL propVal)
{
	SetProperty(0x25, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetDAutoHeadings()
{
	BOOL result;
	GetProperty(0x26, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetDAutoHeadings(BOOL propVal)
{
	SetProperty(0x26, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetDAutoSave()
{
	BOOL result;
	GetProperty(0x27, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetDAutoSave(BOOL propVal)
{
	SetProperty(0x27, VT_BOOL, propVal);
}

long CSpreadSheet::GetDAutoSizeCols()
{
	long result;
	GetProperty(0x28, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetDAutoSizeCols(long propVal)
{
	SetProperty(0x28, VT_I4, propVal);
}

BOOL CSpreadSheet::GetDInformActiveRowChange()
{
	BOOL result;
	GetProperty(0x2b, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetDInformActiveRowChange(BOOL propVal)
{
	SetProperty(0x2b, VT_BOOL, propVal);
}

long CSpreadSheet::GetEditEnterAction()
{
	long result;
	GetProperty(0x2e, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetEditEnterAction(long propVal)
{
	SetProperty(0x2e, VT_I4, propVal);
}

BOOL CSpreadSheet::GetEditMode()
{
	BOOL result;
	GetProperty(0x2f, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetEditMode(BOOL propVal)
{
	SetProperty(0x2f, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetEditModePermanent()
{
	BOOL result;
	GetProperty(0x30, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetEditModePermanent(BOOL propVal)
{
	SetProperty(0x30, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetEditModeReplace()
{
	BOOL result;
	GetProperty(0x31, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetEditModeReplace(BOOL propVal)
{
	SetProperty(0x31, VT_BOOL, propVal);
}

CString CSpreadSheet::GetFormula()
{
	CString result;
	GetProperty(0x36, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetFormula(LPCTSTR propVal)
{
	SetProperty(0x36, VT_BSTR, propVal);
}

unsigned long CSpreadSheet::GetGrayAreaBackColor()
{
	unsigned long result;
	GetProperty(0x37, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetGrayAreaBackColor(unsigned long propVal)
{
	SetProperty(0x37, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetGridColor()
{
	unsigned long result;
	GetProperty(0x38, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetGridColor(unsigned long propVal)
{
	SetProperty(0x38, VT_I4, propVal);
}

BOOL CSpreadSheet::GetGridShowHoriz()
{
	BOOL result;
	GetProperty(0x39, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetGridShowHoriz(BOOL propVal)
{
	SetProperty(0x39, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetGridShowVert()
{
	BOOL result;
	GetProperty(0x3a, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetGridShowVert(BOOL propVal)
{
	SetProperty(0x3a, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetGridSolid()
{
	BOOL result;
	GetProperty(0x3b, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetGridSolid(BOOL propVal)
{
	SetProperty(0x3b, VT_BOOL, propVal);
}

long CSpreadSheet::GetHDCPrinter()
{
	long result;
	GetProperty(0x3c, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetHDCPrinter(long propVal)
{
	SetProperty(0x3c, VT_I4, propVal);
}

short CSpreadSheet::GetInterfaceDesigner()
{
	short result;
	GetProperty(0x3d, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetInterfaceDesigner(short propVal)
{
	SetProperty(0x3d, VT_I2, propVal);
}

BOOL CSpreadSheet::GetIsBlockSelected()
{
	BOOL result;
	GetProperty(0x3e, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetIsBlockSelected(BOOL propVal)
{
	SetProperty(0x3e, VT_BOOL, propVal);
}

long CSpreadSheet::GetLeftCol()
{
	long result;
	GetProperty(0x3f, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetLeftCol(long propVal)
{
	SetProperty(0x3f, VT_I4, propVal);
}

BOOL CSpreadSheet::GetLock()
{
	BOOL result;
	GetProperty(0x40, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetLock(BOOL propVal)
{
	SetProperty(0x40, VT_BOOL, propVal);
}

unsigned long CSpreadSheet::GetLockBackColor()
{
	unsigned long result;
	GetProperty(0x41, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetLockBackColor(unsigned long propVal)
{
	SetProperty(0x41, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetLockForeColor()
{
	unsigned long result;
	GetProperty(0x42, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetLockForeColor(unsigned long propVal)
{
	SetProperty(0x42, VT_I4, propVal);
}

long CSpreadSheet::GetMaxCols()
{
	long result;
	GetProperty(0x43, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetMaxCols(long propVal)
{
	SetProperty(0x43, VT_I4, propVal);
}

long CSpreadSheet::GetMaxRows()
{
	long result;
	GetProperty(0x44, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetMaxRows(long propVal)
{
	SetProperty(0x44, VT_I4, propVal);
}

double CSpreadSheet::GetMaxTextCellHeight()
{
	double result;
	GetProperty(0x45, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetMaxTextCellHeight(double propVal)
{
	SetProperty(0x45, VT_R8, propVal);
}

double CSpreadSheet::GetMaxTextCellWidth()
{
	double result;
	GetProperty(0x46, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetMaxTextCellWidth(double propVal)
{
	SetProperty(0x46, VT_R8, propVal);
}

BOOL CSpreadSheet::GetMoveActiveOnFocus()
{
	BOOL result;
	GetProperty(0x47, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetMoveActiveOnFocus(BOOL propVal)
{
	SetProperty(0x47, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetNoBeep()
{
	BOOL result;
	GetProperty(0x4a, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetNoBeep(BOOL propVal)
{
	SetProperty(0x4a, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetNoBorder()
{
	BOOL result;
	GetProperty(0x4b, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetNoBorder(BOOL propVal)
{
	SetProperty(0x4b, VT_BOOL, propVal);
}

long CSpreadSheet::GetOperationMode()
{
	long result;
	GetProperty(0x4c, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetOperationMode(long propVal)
{
	SetProperty(0x4c, VT_I4, propVal);
}

CString CSpreadSheet::GetPrintAbortMsg()
{
	CString result;
	GetProperty(0x4e, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintAbortMsg(LPCTSTR propVal)
{
	SetProperty(0x4e, VT_BSTR, propVal);
}

BOOL CSpreadSheet::GetPrintBorder()
{
	BOOL result;
	GetProperty(0x4f, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintBorder(BOOL propVal)
{
	SetProperty(0x4f, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetPrintColHeaders()
{
	BOOL result;
	GetProperty(0x50, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintColHeaders(BOOL propVal)
{
	SetProperty(0x50, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetPrintColor()
{
	BOOL result;
	GetProperty(0x51, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintColor(BOOL propVal)
{
	SetProperty(0x51, VT_BOOL, propVal);
}

CString CSpreadSheet::GetPrintFooter()
{
	CString result;
	GetProperty(0x52, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintFooter(LPCTSTR propVal)
{
	SetProperty(0x52, VT_BSTR, propVal);
}

BOOL CSpreadSheet::GetPrintGrid()
{
	BOOL result;
	GetProperty(0x53, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintGrid(BOOL propVal)
{
	SetProperty(0x53, VT_BOOL, propVal);
}

CString CSpreadSheet::GetPrintHeader()
{
	CString result;
	GetProperty(0x54, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintHeader(LPCTSTR propVal)
{
	SetProperty(0x54, VT_BSTR, propVal);
}

CString CSpreadSheet::GetPrintJobName()
{
	CString result;
	GetProperty(0x55, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintJobName(LPCTSTR propVal)
{
	SetProperty(0x55, VT_BSTR, propVal);
}

long CSpreadSheet::GetPrintMarginBottom()
{
	long result;
	GetProperty(0x56, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintMarginBottom(long propVal)
{
	SetProperty(0x56, VT_I4, propVal);
}

long CSpreadSheet::GetPrintMarginLeft()
{
	long result;
	GetProperty(0x57, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintMarginLeft(long propVal)
{
	SetProperty(0x57, VT_I4, propVal);
}

long CSpreadSheet::GetPrintMarginRight()
{
	long result;
	GetProperty(0x58, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintMarginRight(long propVal)
{
	SetProperty(0x58, VT_I4, propVal);
}

long CSpreadSheet::GetPrintMarginTop()
{
	long result;
	GetProperty(0x59, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintMarginTop(long propVal)
{
	SetProperty(0x59, VT_I4, propVal);
}

short CSpreadSheet::GetPrintPageEnd()
{
	short result;
	GetProperty(0x5a, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintPageEnd(short propVal)
{
	SetProperty(0x5a, VT_I2, propVal);
}

short CSpreadSheet::GetPrintPageStart()
{
	short result;
	GetProperty(0x5b, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintPageStart(short propVal)
{
	SetProperty(0x5b, VT_I2, propVal);
}

BOOL CSpreadSheet::GetPrintRowHeaders()
{
	BOOL result;
	GetProperty(0x5c, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintRowHeaders(BOOL propVal)
{
	SetProperty(0x5c, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetPrintShadows()
{
	BOOL result;
	GetProperty(0x5d, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintShadows(BOOL propVal)
{
	SetProperty(0x5d, VT_BOOL, propVal);
}

long CSpreadSheet::GetPrintType()
{
	long result;
	GetProperty(0x5e, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintType(long propVal)
{
	SetProperty(0x5e, VT_I4, propVal);
}

BOOL CSpreadSheet::GetPrintUseDataMax()
{
	BOOL result;
	GetProperty(0x5f, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintUseDataMax(BOOL propVal)
{
	SetProperty(0x5f, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetProcessTab()
{
	BOOL result;
	GetProperty(0x60, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetProcessTab(BOOL propVal)
{
	SetProperty(0x60, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetProtect()
{
	BOOL result;
	GetProperty(0x61, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetProtect(BOOL propVal)
{
	SetProperty(0x61, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetReDraw()
{
	BOOL result;
	GetProperty(0x62, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetReDraw(BOOL propVal)
{
	SetProperty(0x62, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetRestrictCols()
{
	BOOL result;
	GetProperty(0x63, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetRestrictCols(BOOL propVal)
{
	SetProperty(0x63, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetRestrictRows()
{
	BOOL result;
	GetProperty(0x64, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetRestrictRows(BOOL propVal)
{
	SetProperty(0x64, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetRetainSelBlock()
{
	BOOL result;
	GetProperty(0x65, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetRetainSelBlock(BOOL propVal)
{
	SetProperty(0x65, VT_BOOL, propVal);
}

long CSpreadSheet::GetRow()
{
	long result;
	GetProperty(0x66, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetRow(long propVal)
{
	SetProperty(0x66, VT_I4, propVal);
}

long CSpreadSheet::GetRow2()
{
	long result;
	GetProperty(0x67, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetRow2(long propVal)
{
	SetProperty(0x67, VT_I4, propVal);
}

BOOL CSpreadSheet::GetRowHidden()
{
	BOOL result;
	GetProperty(0x69, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetRowHidden(BOOL propVal)
{
	SetProperty(0x69, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetRowPageBreak()
{
	BOOL result;
	GetProperty(0x6a, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetRowPageBreak(BOOL propVal)
{
	SetProperty(0x6a, VT_BOOL, propVal);
}

long CSpreadSheet::GetRowsFrozen()
{
	long result;
	GetProperty(0x6b, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetRowsFrozen(long propVal)
{
	SetProperty(0x6b, VT_I4, propVal);
}

BOOL CSpreadSheet::GetScrollBarExtMode()
{
	BOOL result;
	GetProperty(0x6c, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetScrollBarExtMode(BOOL propVal)
{
	SetProperty(0x6c, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetScrollBxxxaxAlign()
{
	BOOL result;
	GetProperty(0x6d, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetScrollBxxxaxAlign(BOOL propVal)
{
	SetProperty(0x6d, VT_BOOL, propVal);
}

long CSpreadSheet::GetScrollBars()
{
	long result;
	GetProperty(0x6e, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetScrollBars(long propVal)
{
	SetProperty(0x6e, VT_I4, propVal);
}

BOOL CSpreadSheet::GetScrollBarShowMax()
{
	BOOL result;
	GetProperty(0x6f, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetScrollBarShowMax(BOOL propVal)
{
	SetProperty(0x6f, VT_BOOL, propVal);
}

long CSpreadSheet::GetSelBlockCol()
{
	long result;
	GetProperty(0x70, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelBlockCol(long propVal)
{
	SetProperty(0x70, VT_I4, propVal);
}

long CSpreadSheet::GetSelBlockCol2()
{
	long result;
	GetProperty(0x71, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelBlockCol2(long propVal)
{
	SetProperty(0x71, VT_I4, propVal);
}

long CSpreadSheet::GetSelBlockRow()
{
	long result;
	GetProperty(0x72, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelBlockRow(long propVal)
{
	SetProperty(0x72, VT_I4, propVal);
}

long CSpreadSheet::GetSelBlockRow2()
{
	long result;
	GetProperty(0x73, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelBlockRow2(long propVal)
{
	SetProperty(0x73, VT_I4, propVal);
}

long CSpreadSheet::GetSelectBlockOptions()
{
	long result;
	GetProperty(0x74, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelectBlockOptions(long propVal)
{
	SetProperty(0x74, VT_I4, propVal);
}

long CSpreadSheet::GetSelLength()
{
	long result;
	GetProperty(0x75, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelLength(long propVal)
{
	SetProperty(0x75, VT_I4, propVal);
}

long CSpreadSheet::GetSelModeIndex()
{
	long result;
	GetProperty(0x76, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelModeIndex(long propVal)
{
	SetProperty(0x76, VT_I4, propVal);
}

BOOL CSpreadSheet::GetSelModeSelected()
{
	BOOL result;
	GetProperty(0x78, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelModeSelected(BOOL propVal)
{
	SetProperty(0x78, VT_BOOL, propVal);
}

long CSpreadSheet::GetSelStart()
{
	long result;
	GetProperty(0x79, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelStart(long propVal)
{
	SetProperty(0x79, VT_I4, propVal);
}

CString CSpreadSheet::GetSelText()
{
	CString result;
	GetProperty(0x7a, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelText(LPCTSTR propVal)
{
	SetProperty(0x7a, VT_BSTR, propVal);
}

unsigned long CSpreadSheet::GetShadowColor()
{
	unsigned long result;
	GetProperty(0x7b, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetShadowColor(unsigned long propVal)
{
	SetProperty(0x7b, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetShadowDark()
{
	unsigned long result;
	GetProperty(0x7c, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetShadowDark(unsigned long propVal)
{
	SetProperty(0x7c, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetShadowText()
{
	unsigned long result;
	GetProperty(0x7d, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetShadowText(unsigned long propVal)
{
	SetProperty(0x7d, VT_I4, propVal);
}

long CSpreadSheet::GetStartingColNumber()
{
	long result;
	GetProperty(0x7f, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetStartingColNumber(long propVal)
{
	SetProperty(0x7f, VT_I4, propVal);
}

long CSpreadSheet::GetStartingRowNumber()
{
	long result;
	GetProperty(0x80, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetStartingRowNumber(long propVal)
{
	SetProperty(0x80, VT_I4, propVal);
}

long CSpreadSheet::GetTopRow()
{
	long result;
	GetProperty(0x81, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTopRow(long propVal)
{
	SetProperty(0x81, VT_I4, propVal);
}

long CSpreadSheet::GetTypeButtonAlign()
{
	long result;
	GetProperty(0x82, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonAlign(long propVal)
{
	SetProperty(0x82, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetTypeButtonBorderColor()
{
	unsigned long result;
	GetProperty(0x83, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonBorderColor(unsigned long propVal)
{
	SetProperty(0x83, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetTypeButtonColor()
{
	unsigned long result;
	GetProperty(0x84, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonColor(unsigned long propVal)
{
	SetProperty(0x84, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetTypeButtonDarkColor()
{
	unsigned long result;
	GetProperty(0x85, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonDarkColor(unsigned long propVal)
{
	SetProperty(0x85, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetTypeButtonLightColor()
{
	unsigned long result;
	GetProperty(0x86, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonLightColor(unsigned long propVal)
{
	SetProperty(0x86, VT_I4, propVal);
}

LPDISPATCH CSpreadSheet::GetTypeButtonPicture()
{
	LPDISPATCH result;
	GetProperty(0x87, VT_DISPATCH, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonPicture(LPDISPATCH propVal)
{
	SetProperty(0x87, VT_DISPATCH, propVal);
}

LPDISPATCH CSpreadSheet::GetTypeButtonPictureDown()
{
	LPDISPATCH result;
	GetProperty(0x88, VT_DISPATCH, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonPictureDown(LPDISPATCH propVal)
{
	SetProperty(0x88, VT_DISPATCH, propVal);
}

short CSpreadSheet::GetTypeButtonShadowSize()
{
	short result;
	GetProperty(0x89, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonShadowSize(short propVal)
{
	SetProperty(0x89, VT_I2, propVal);
}

CString CSpreadSheet::GetTypeButtonText()
{
	CString result;
	GetProperty(0x8a, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonText(LPCTSTR propVal)
{
	SetProperty(0x8a, VT_BSTR, propVal);
}

unsigned long CSpreadSheet::GetTypeButtonTextColor()
{
	unsigned long result;
	GetProperty(0x8b, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonTextColor(unsigned long propVal)
{
	SetProperty(0x8b, VT_I4, propVal);
}

long CSpreadSheet::GetTypeButtonType()
{
	long result;
	GetProperty(0x8c, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeButtonType(long propVal)
{
	SetProperty(0x8c, VT_I4, propVal);
}

BOOL CSpreadSheet::GetTypeCheckCenter()
{
	BOOL result;
	GetProperty(0x8d, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCheckCenter(BOOL propVal)
{
	SetProperty(0x8d, VT_BOOL, propVal);
}

CString CSpreadSheet::GetTypeCheckText()
{
	CString result;
	GetProperty(0x8e, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCheckText(LPCTSTR propVal)
{
	SetProperty(0x8e, VT_BSTR, propVal);
}

long CSpreadSheet::GetTypeCheckTextAlign()
{
	long result;
	GetProperty(0x8f, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCheckTextAlign(long propVal)
{
	SetProperty(0x8f, VT_I4, propVal);
}

short CSpreadSheet::GetTypeComboBoxCount()
{
	short result;
	GetProperty(0x90, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxCount(short propVal)
{
	SetProperty(0x90, VT_I2, propVal);
}

short CSpreadSheet::GetTypeComboBoxCurSel()
{
	short result;
	GetProperty(0x91, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxCurSel(short propVal)
{
	SetProperty(0x91, VT_I2, propVal);
}

BOOL CSpreadSheet::GetTypeComboBoxEditable()
{
	BOOL result;
	GetProperty(0x92, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxEditable(BOOL propVal)
{
	SetProperty(0x92, VT_BOOL, propVal);
}

short CSpreadSheet::GetTypeComboBoxIndex()
{
	short result;
	GetProperty(0x93, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxIndex(short propVal)
{
	SetProperty(0x93, VT_I2, propVal);
}

CString CSpreadSheet::GetTypeComboBoxList()
{
	CString result;
	GetProperty(0x94, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxList(LPCTSTR propVal)
{
	SetProperty(0x94, VT_BSTR, propVal);
}

CString CSpreadSheet::GetTypeComboBoxString()
{
	CString result;
	GetProperty(0x95, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxString(LPCTSTR propVal)
{
	SetProperty(0x95, VT_BSTR, propVal);
}

BOOL CSpreadSheet::GetTypeDateCentury()
{
	BOOL result;
	GetProperty(0x96, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeDateCentury(BOOL propVal)
{
	SetProperty(0x96, VT_BOOL, propVal);
}

long CSpreadSheet::GetTypeDateFormat()
{
	long result;
	GetProperty(0x97, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeDateFormat(long propVal)
{
	SetProperty(0x97, VT_I4, propVal);
}

CString CSpreadSheet::GetTypeDateMax()
{
	CString result;
	GetProperty(0x98, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeDateMax(LPCTSTR propVal)
{
	SetProperty(0x98, VT_BSTR, propVal);
}

CString CSpreadSheet::GetTypeDateMin()
{
	CString result;
	GetProperty(0x99, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeDateMin(LPCTSTR propVal)
{
	SetProperty(0x99, VT_BSTR, propVal);
}

short CSpreadSheet::GetTypeDateSeparator()
{
	short result;
	GetProperty(0x9a, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeDateSeparator(short propVal)
{
	SetProperty(0x9a, VT_I2, propVal);
}

long CSpreadSheet::GetTypeEditCharCase()
{
	long result;
	GetProperty(0x9b, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeEditCharCase(long propVal)
{
	SetProperty(0x9b, VT_I4, propVal);
}

long CSpreadSheet::GetTypeEditCharSet()
{
	long result;
	GetProperty(0x9c, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeEditCharSet(long propVal)
{
	SetProperty(0x9c, VT_I4, propVal);
}

BOOL CSpreadSheet::GetTypeEditMultiLine()
{
	BOOL result;
	GetProperty(0x9e, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeEditMultiLine(BOOL propVal)
{
	SetProperty(0x9e, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetTypeEditPassword()
{
	BOOL result;
	GetProperty(0x9f, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeEditPassword(BOOL propVal)
{
	SetProperty(0x9f, VT_BOOL, propVal);
}

long CSpreadSheet::GetTypeHAlign()
{
	long result;
	GetProperty(0xa8, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeHAlign(long propVal)
{
	SetProperty(0xa8, VT_I4, propVal);
}

long CSpreadSheet::GetTypeOwnerDrawStyle()
{
	long result;
	GetProperty(0xad, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeOwnerDrawStyle(long propVal)
{
	SetProperty(0xad, VT_I4, propVal);
}

CString CSpreadSheet::GetTypePicDefaultText()
{
	CString result;
	GetProperty(0xae, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePicDefaultText(LPCTSTR propVal)
{
	SetProperty(0xae, VT_BSTR, propVal);
}

CString CSpreadSheet::GetTypePicMask()
{
	CString result;
	GetProperty(0xaf, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePicMask(LPCTSTR propVal)
{
	SetProperty(0xaf, VT_BSTR, propVal);
}

BOOL CSpreadSheet::GetTypePictCenter()
{
	BOOL result;
	GetProperty(0xb0, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePictCenter(BOOL propVal)
{
	SetProperty(0xb0, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetTypePictMaintainScale()
{
	BOOL result;
	GetProperty(0xb1, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePictMaintainScale(BOOL propVal)
{
	SetProperty(0xb1, VT_BOOL, propVal);
}

LPDISPATCH CSpreadSheet::GetTypePictPicture()
{
	LPDISPATCH result;
	GetProperty(0xb2, VT_DISPATCH, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePictPicture(LPDISPATCH propVal)
{
	SetProperty(0xb2, VT_DISPATCH, propVal);
}

BOOL CSpreadSheet::GetTypePictStretch()
{
	BOOL result;
	GetProperty(0xb3, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePictStretch(BOOL propVal)
{
	SetProperty(0xb3, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetTypeSpin()
{
	BOOL result;
	GetProperty(0xb4, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeSpin(BOOL propVal)
{
	SetProperty(0xb4, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetTypeTextPrefix()
{
	BOOL result;
	GetProperty(0xb6, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTextPrefix(BOOL propVal)
{
	SetProperty(0xb6, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetTypeTextShadow()
{
	BOOL result;
	GetProperty(0xb7, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTextShadow(BOOL propVal)
{
	SetProperty(0xb7, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetTypeTextShadowIn()
{
	BOOL result;
	GetProperty(0xb8, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTextShadowIn(BOOL propVal)
{
	SetProperty(0xb8, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetTypeTextWordWrap()
{
	BOOL result;
	GetProperty(0xb9, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTextWordWrap(BOOL propVal)
{
	SetProperty(0xb9, VT_BOOL, propVal);
}

long CSpreadSheet::GetTypeTime24Hour()
{
	long result;
	GetProperty(0xba, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTime24Hour(long propVal)
{
	SetProperty(0xba, VT_I4, propVal);
}

CString CSpreadSheet::GetTypeTimeMax()
{
	CString result;
	GetProperty(0xbb, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTimeMax(LPCTSTR propVal)
{
	SetProperty(0xbb, VT_BSTR, propVal);
}

CString CSpreadSheet::GetTypeTimeMin()
{
	CString result;
	GetProperty(0xbc, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTimeMin(LPCTSTR propVal)
{
	SetProperty(0xbc, VT_BSTR, propVal);
}

BOOL CSpreadSheet::GetTypeTimeSeconds()
{
	BOOL result;
	GetProperty(0xbd, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTimeSeconds(BOOL propVal)
{
	SetProperty(0xbd, VT_BOOL, propVal);
}

short CSpreadSheet::GetTypeTimeSeparator()
{
	short result;
	GetProperty(0xbe, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTimeSeparator(short propVal)
{
	SetProperty(0xbe, VT_I2, propVal);
}

long CSpreadSheet::GetUnitType()
{
	long result;
	GetProperty(0xbf, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetUnitType(long propVal)
{
	SetProperty(0xbf, VT_I4, propVal);
}

long CSpreadSheet::GetUserResize()
{
	long result;
	GetProperty(0xc0, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetUserResize(long propVal)
{
	SetProperty(0xc0, VT_I4, propVal);
}

long CSpreadSheet::GetUserResizeCol()
{
	long result;
	GetProperty(0xc1, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetUserResizeCol(long propVal)
{
	SetProperty(0xc1, VT_I4, propVal);
}

long CSpreadSheet::GetUserResizeRow()
{
	long result;
	GetProperty(0xc2, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetUserResizeRow(long propVal)
{
	SetProperty(0xc2, VT_I4, propVal);
}

CString CSpreadSheet::GetValue()
{
	CString result;
	GetProperty(0xc3, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetValue(LPCTSTR propVal)
{
	SetProperty(0xc3, VT_BSTR, propVal);
}

long CSpreadSheet::GetVirtualCurRowCount()
{
	long result;
	GetProperty(0xc4, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetVirtualCurRowCount(long propVal)
{
	SetProperty(0xc4, VT_I4, propVal);
}

long CSpreadSheet::GetVirtualCurTop()
{
	long result;
	GetProperty(0xc5, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetVirtualCurTop(long propVal)
{
	SetProperty(0xc5, VT_I4, propVal);
}

long CSpreadSheet::GetVirtualMaxRows()
{
	long result;
	GetProperty(0xc6, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetVirtualMaxRows(long propVal)
{
	SetProperty(0xc6, VT_I4, propVal);
}

BOOL CSpreadSheet::GetVirtualMode()
{
	BOOL result;
	GetProperty(0xc7, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetVirtualMode(BOOL propVal)
{
	SetProperty(0xc7, VT_BOOL, propVal);
}

long CSpreadSheet::GetVirtualOverlap()
{
	long result;
	GetProperty(0xc8, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetVirtualOverlap(long propVal)
{
	SetProperty(0xc8, VT_I4, propVal);
}

long CSpreadSheet::GetVirtualRows()
{
	long result;
	GetProperty(0xc9, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetVirtualRows(long propVal)
{
	SetProperty(0xc9, VT_I4, propVal);
}

BOOL CSpreadSheet::GetVirtualScrollBuffer()
{
	BOOL result;
	GetProperty(0xca, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetVirtualScrollBuffer(BOOL propVal)
{
	SetProperty(0xca, VT_BOOL, propVal);
}

long CSpreadSheet::GetVisibleCols()
{
	long result;
	GetProperty(0xcb, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetVisibleCols(long propVal)
{
	SetProperty(0xcb, VT_I4, propVal);
}

long CSpreadSheet::GetVisibleRows()
{
	long result;
	GetProperty(0xcc, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetVisibleRows(long propVal)
{
	SetProperty(0xcc, VT_I4, propVal);
}

BOOL CSpreadSheet::GetVScrollSpecial()
{
	BOOL result;
	GetProperty(0xcd, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetVScrollSpecial(BOOL propVal)
{
	SetProperty(0xcd, VT_BOOL, propVal);
}

long CSpreadSheet::GetVScrollSpecialType()
{
	long result;
	GetProperty(0xce, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetVScrollSpecialType(long propVal)
{
	SetProperty(0xce, VT_I4, propVal);
}

BOOL CSpreadSheet::GetFontBold()
{
	BOOL result;
	GetProperty(0xcf, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetFontBold(BOOL propVal)
{
	SetProperty(0xcf, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetFontItalic()
{
	BOOL result;
	GetProperty(0xd0, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetFontItalic(BOOL propVal)
{
	SetProperty(0xd0, VT_BOOL, propVal);
}

CString CSpreadSheet::GetFontName()
{
	CString result;
	GetProperty(0xd1, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetFontName(LPCTSTR propVal)
{
	SetProperty(0xd1, VT_BSTR, propVal);
}

float CSpreadSheet::GetFontSize()
{
	float result;
	GetProperty(0xd2, VT_R4, (void*)&result);
	return result;
}

void CSpreadSheet::SetFontSize(float propVal)
{
	SetProperty(0xd2, VT_R4, propVal);
}

BOOL CSpreadSheet::GetFontStrikethru()
{
	BOOL result;
	GetProperty(0xd3, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetFontStrikethru(BOOL propVal)
{
	SetProperty(0xd3, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetFontUnderline()
{
	BOOL result;
	GetProperty(0xd4, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetFontUnderline(BOOL propVal)
{
	SetProperty(0xd4, VT_BOOL, propVal);
}

LPDISPATCH CSpreadSheet::GetCursorIcon()
{
	LPDISPATCH result;
	GetProperty(0xd5, VT_DISPATCH, (void*)&result);
	return result;
}

void CSpreadSheet::SetCursorIcon(LPDISPATCH propVal)
{
	SetProperty(0xd5, VT_DISPATCH, propVal);
}

long CSpreadSheet::GetPrintOrientation()
{
	long result;
	GetProperty(0xd6, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintOrientation(long propVal)
{
	SetProperty(0xd6, VT_I4, propVal);
}

long CSpreadSheet::GetBackColorStyle()
{
	long result;
	GetProperty(0xd7, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetBackColorStyle(long propVal)
{
	SetProperty(0xd7, VT_I4, propVal);
}

BOOL CSpreadSheet::GetFormulaSync()
{
	BOOL result;
	GetProperty(0xd8, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetFormulaSync(BOOL propVal)
{
	SetProperty(0xd8, VT_BOOL, propVal);
}

long CSpreadSheet::GetTypeCheckType()
{
	long result;
	GetProperty(0x110, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCheckType(long propVal)
{
	SetProperty(0x110, VT_I4, propVal);
}

long CSpreadSheet::GetAppearance()
{
	long result;
	GetProperty(0x111, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetAppearance(long propVal)
{
	SetProperty(0x111, VT_I4, propVal);
}

short CSpreadSheet::GetTypeComboBoxMaxDrop()
{
	short result;
	GetProperty(0x112, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxMaxDrop(short propVal)
{
	SetProperty(0x112, VT_I2, propVal);
}

short CSpreadSheet::GetTypeMaxEditLen()
{
	short result;
	GetProperty(0x113, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeMaxEditLen(short propVal)
{
	SetProperty(0x113, VT_I2, propVal);
}

short CSpreadSheet::GetTypeComboBoxWidth()
{
	short result;
	GetProperty(0x114, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxWidth(short propVal)
{
	SetProperty(0x114, VT_I2, propVal);
}

long CSpreadSheet::GetTextTip()
{
	long result;
	GetProperty(0x115, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTextTip(long propVal)
{
	SetProperty(0x115, VT_I4, propVal);
}

long CSpreadSheet::GetTextTipDelay()
{
	long result;
	GetProperty(0x116, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTextTipDelay(long propVal)
{
	SetProperty(0x116, VT_I4, propVal);
}

long CSpreadSheet::GetScrollBarTrack()
{
	long result;
	GetProperty(0x117, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetScrollBarTrack(long propVal)
{
	SetProperty(0x117, VT_I4, propVal);
}

long CSpreadSheet::GetTypeVAlign()
{
	long result;
	GetProperty(0x118, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeVAlign(long propVal)
{
	SetProperty(0x118, VT_I4, propVal);
}

long CSpreadSheet::GetClipboardOptions()
{
	long result;
	GetProperty(0x119, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetClipboardOptions(long propVal)
{
	SetProperty(0x119, VT_I4, propVal);
}

long CSpreadSheet::GetPrintPageOrder()
{
	long result;
	GetProperty(0x11b, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintPageOrder(long propVal)
{
	SetProperty(0x11b, VT_I4, propVal);
}

long CSpreadSheet::GetPrintPageCount()
{
	long result;
	GetProperty(0x11c, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintPageCount(long propVal)
{
	SetProperty(0x11c, VT_I4, propVal);
}

long CSpreadSheet::GetPrintFirstPageNumber()
{
	long result;
	GetProperty(0x11d, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintFirstPageNumber(long propVal)
{
	SetProperty(0x11d, VT_I4, propVal);
}

long CSpreadSheet::GetPrintNextPageBreakCol()
{
	long result;
	GetProperty(0x11e, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintNextPageBreakCol(long propVal)
{
	SetProperty(0x11e, VT_I4, propVal);
}

long CSpreadSheet::GetPrintNextPageBreakRow()
{
	long result;
	GetProperty(0x11f, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintNextPageBreakRow(long propVal)
{
	SetProperty(0x11f, VT_I4, propVal);
}

long CSpreadSheet::GetTypeComboBoxhWnd()
{
	long result;
	GetProperty(0x120, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxhWnd(long propVal)
{
	SetProperty(0x120, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetSelBackColor()
{
	unsigned long result;
	GetProperty(0x121, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelBackColor(unsigned long propVal)
{
	SetProperty(0x121, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetSelForeColor()
{
	unsigned long result;
	GetProperty(0x122, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelForeColor(unsigned long propVal)
{
	SetProperty(0x122, VT_I4, propVal);
}

short CSpreadSheet::GetTwoDigitYexxxax()
{
	short result;
	GetProperty(0x123, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTwoDigitYexxxax(short propVal)
{
	SetProperty(0x123, VT_I2, propVal);
}

long CSpreadSheet::GetOLEDropMode()
{
	long result;
	GetProperty(0x13b, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetOLEDropMode(long propVal)
{
	SetProperty(0x13b, VT_I4, propVal);
}

CString CSpreadSheet::GetDataMember()
{
	CString result;
	GetProperty(0x13d, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetDataMember(LPCTSTR propVal)
{
	SetProperty(0x13d, VT_BSTR, propVal);
}

CString CSpreadSheet::GetCellNote()
{
	CString result;
	GetProperty(0x13e, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetCellNote(LPCTSTR propVal)
{
	SetProperty(0x13e, VT_BSTR, propVal);
}

long CSpreadSheet::GetUserColAction()
{
	long result;
	GetProperty(0x13f, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetUserColAction(long propVal)
{
	SetProperty(0x13f, VT_I4, propVal);
}

long CSpreadSheet::GetShowScrollTips()
{
	long result;
	GetProperty(0x140, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetShowScrollTips(long propVal)
{
	SetProperty(0x140, VT_I4, propVal);
}

long CSpreadSheet::GetCellNoteIndicator()
{
	long result;
	GetProperty(0x141, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetCellNoteIndicator(long propVal)
{
	SetProperty(0x141, VT_I4, propVal);
}

BOOL CSpreadSheet::GetTypeEllipses()
{
	BOOL result;
	GetProperty(0x14a, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeEllipses(BOOL propVal)
{
	SetProperty(0x14a, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetScriptEnhanced()
{
	BOOL result;
	GetProperty(0x150, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetScriptEnhanced(BOOL propVal)
{
	SetProperty(0x150, VT_BOOL, propVal);
}

long CSpreadSheet::GetCxxxerge()
{
	long result;
	GetProperty(0x151, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetCxxxerge(long propVal)
{
	SetProperty(0x151, VT_I4, propVal);
}

long CSpreadSheet::GetRowMerge()
{
	long result;
	GetProperty(0x152, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetRowMerge(long propVal)
{
	SetProperty(0x152, VT_I4, propVal);
}

CString CSpreadSheet::GetColID()
{
	CString result;
	GetProperty(0x153, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetColID(LPCTSTR propVal)
{
	SetProperty(0x153, VT_BSTR, propVal);
}

long CSpreadSheet::GetRowHeaderCols()
{
	long result;
	GetProperty(0x154, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetRowHeaderCols(long propVal)
{
	SetProperty(0x154, VT_I4, propVal);
}

long CSpreadSheet::GetRowHeadersAutoTextIndex()
{
	long result;
	GetProperty(0x155, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetRowHeadersAutoTextIndex(long propVal)
{
	SetProperty(0x155, VT_I4, propVal);
}

long CSpreadSheet::GetColHeaderRows()
{
	long result;
	GetProperty(0x156, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetColHeaderRows(long propVal)
{
	SetProperty(0x156, VT_I4, propVal);
}

long CSpreadSheet::GetColHeadersAutoTextIndex()
{
	long result;
	GetProperty(0x157, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetColHeadersAutoTextIndex(long propVal)
{
	SetProperty(0x157, VT_I4, propVal);
}

long CSpreadSheet::GetColHeadersUserSortIndex()
{
	long result;
	GetProperty(0x158, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetColHeadersUserSortIndex(long propVal)
{
	SetProperty(0x158, VT_I4, propVal);
}

long CSpreadSheet::GetTypeComboBoxAutoSearch()
{
	long result;
	GetProperty(0x159, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeComboBoxAutoSearch(long propVal)
{
	SetProperty(0x159, VT_I4, propVal);
}

double CSpreadSheet::GetTypeSpinInc()
{
	double result;
	GetProperty(0x15a, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeSpinInc(double propVal)
{
	SetProperty(0x15a, VT_R8, propVal);
}

BOOL CSpreadSheet::GetTypeSpinWrap()
{
	BOOL result;
	GetProperty(0x15b, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeSpinWrap(BOOL propVal)
{
	SetProperty(0x15b, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetTypeNegRed()
{
	BOOL result;
	GetProperty(0x15c, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeNegRed(BOOL propVal)
{
	SetProperty(0x15c, VT_BOOL, propVal);
}

double CSpreadSheet::GetTypeCurrencyMin()
{
	double result;
	GetProperty(0x15d, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencyMin(double propVal)
{
	SetProperty(0x15d, VT_R8, propVal);
}

double CSpreadSheet::GetTypeCurrencyMax()
{
	double result;
	GetProperty(0x15e, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencyMax(double propVal)
{
	SetProperty(0x15e, VT_R8, propVal);
}

CString CSpreadSheet::GetTypeCurrencyDecimal()
{
	CString result;
	GetProperty(0x15f, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencyDecimal(LPCTSTR propVal)
{
	SetProperty(0x15f, VT_BSTR, propVal);
}

CString CSpreadSheet::GetTypeCurrencySeparator()
{
	CString result;
	GetProperty(0x160, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencySeparator(LPCTSTR propVal)
{
	SetProperty(0x160, VT_BSTR, propVal);
}

CString CSpreadSheet::GetTypeCurrencySymbol()
{
	CString result;
	GetProperty(0x161, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencySymbol(LPCTSTR propVal)
{
	SetProperty(0x161, VT_BSTR, propVal);
}

long CSpreadSheet::GetTypeCurrencyLeadingZero()
{
	long result;
	GetProperty(0x162, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencyLeadingZero(long propVal)
{
	SetProperty(0x162, VT_I4, propVal);
}

short CSpreadSheet::GetTypeCurrencyDecPlaces()
{
	short result;
	GetProperty(0x163, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencyDecPlaces(short propVal)
{
	SetProperty(0x163, VT_I2, propVal);
}

long CSpreadSheet::GetTypeCurrencyNegStyle()
{
	long result;
	GetProperty(0x164, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencyNegStyle(long propVal)
{
	SetProperty(0x164, VT_I4, propVal);
}

long CSpreadSheet::GetTypeCurrencyPosStyle()
{
	long result;
	GetProperty(0x165, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencyPosStyle(long propVal)
{
	SetProperty(0x165, VT_I4, propVal);
}

BOOL CSpreadSheet::GetTypeCurrencyShowSep()
{
	BOOL result;
	GetProperty(0x166, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencyShowSep(BOOL propVal)
{
	SetProperty(0x166, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetTypeCurrencyShowSymbol()
{
	BOOL result;
	GetProperty(0x167, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeCurrencyShowSymbol(BOOL propVal)
{
	SetProperty(0x167, VT_BOOL, propVal);
}

double CSpreadSheet::GetTypeNumberMin()
{
	double result;
	GetProperty(0x168, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeNumberMin(double propVal)
{
	SetProperty(0x168, VT_R8, propVal);
}

double CSpreadSheet::GetTypeNumberMax()
{
	double result;
	GetProperty(0x169, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeNumberMax(double propVal)
{
	SetProperty(0x169, VT_R8, propVal);
}

CString CSpreadSheet::GetTypeNumberDecimal()
{
	CString result;
	GetProperty(0x16a, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeNumberDecimal(LPCTSTR propVal)
{
	SetProperty(0x16a, VT_BSTR, propVal);
}

CString CSpreadSheet::GetTypeNumberSeparator()
{
	CString result;
	GetProperty(0x16b, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeNumberSeparator(LPCTSTR propVal)
{
	SetProperty(0x16b, VT_BSTR, propVal);
}

long CSpreadSheet::GetTypeNumberLeadingZero()
{
	long result;
	GetProperty(0x16c, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeNumberLeadingZero(long propVal)
{
	SetProperty(0x16c, VT_I4, propVal);
}

short CSpreadSheet::GetTypeNumberDecPlaces()
{
	short result;
	GetProperty(0x16d, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeNumberDecPlaces(short propVal)
{
	SetProperty(0x16d, VT_I2, propVal);
}

long CSpreadSheet::GetTypeNumberNegStyle()
{
	long result;
	GetProperty(0x16e, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeNumberNegStyle(long propVal)
{
	SetProperty(0x16e, VT_I4, propVal);
}

BOOL CSpreadSheet::GetTypeNumberShowSep()
{
	BOOL result;
	GetProperty(0x16f, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeNumberShowSep(BOOL propVal)
{
	SetProperty(0x16f, VT_BOOL, propVal);
}

double CSpreadSheet::GetTypePercentMin()
{
	double result;
	GetProperty(0x170, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePercentMin(double propVal)
{
	SetProperty(0x170, VT_R8, propVal);
}

double CSpreadSheet::GetTypePercentMax()
{
	double result;
	GetProperty(0x171, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePercentMax(double propVal)
{
	SetProperty(0x171, VT_R8, propVal);
}

CString CSpreadSheet::GetTypePercentDecimal()
{
	CString result;
	GetProperty(0x172, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePercentDecimal(LPCTSTR propVal)
{
	SetProperty(0x172, VT_BSTR, propVal);
}

short CSpreadSheet::GetTypePercentDecPlaces()
{
	short result;
	GetProperty(0x173, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePercentDecPlaces(short propVal)
{
	SetProperty(0x173, VT_I2, propVal);
}

long CSpreadSheet::GetTypePercentNegStyle()
{
	long result;
	GetProperty(0x174, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePercentNegStyle(long propVal)
{
	SetProperty(0x174, VT_I4, propVal);
}

long CSpreadSheet::GetTypeTextOrient()
{
	long result;
	GetProperty(0x175, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeTextOrient(long propVal)
{
	SetProperty(0x175, VT_I4, propVal);
}

CString CSpreadSheet::GetCellTag()
{
	CString result;
	GetProperty(0x176, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetCellTag(LPCTSTR propVal)
{
	SetProperty(0x176, VT_BSTR, propVal);
}

BOOL CSpreadSheet::GetAllowEditOverflow()
{
	BOOL result;
	GetProperty(0x177, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAllowEditOverflow(BOOL propVal)
{
	SetProperty(0x177, VT_BOOL, propVal);
}

long CSpreadSheet::GetColHeadersAutoText()
{
	long result;
	GetProperty(0x178, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetColHeadersAutoText(long propVal)
{
	SetProperty(0x178, VT_I4, propVal);
}

BOOL CSpreadSheet::GetColHeadersShow()
{
	BOOL result;
	GetProperty(0x179, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetColHeadersShow(BOOL propVal)
{
	SetProperty(0x179, VT_BOOL, propVal);
}

long CSpreadSheet::GetRowHeadersAutoText()
{
	long result;
	GetProperty(0x17a, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetRowHeadersAutoText(long propVal)
{
	SetProperty(0x17a, VT_I4, propVal);
}

BOOL CSpreadSheet::GetRowHeadersShow()
{
	BOOL result;
	GetProperty(0x17b, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetRowHeadersShow(BOOL propVal)
{
	SetProperty(0x17b, VT_BOOL, propVal);
}

long CSpreadSheet::GetTypePercentLeadingZero()
{
	long result;
	GetProperty(0x17c, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypePercentLeadingZero(long propVal)
{
	SetProperty(0x17c, VT_I4, propVal);
}

short CSpreadSheet::GetSheetCount()
{
	short result;
	GetProperty(0x17d, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetSheetCount(short propVal)
{
	SetProperty(0x17d, VT_I2, propVal);
}

short CSpreadSheet::GetActiveSheet()
{
	short result;
	GetProperty(0x17e, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetActiveSheet(short propVal)
{
	SetProperty(0x17e, VT_I2, propVal);
}

short CSpreadSheet::GetTabStripLeftSheet()
{
	short result;
	GetProperty(0x17f, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTabStripLeftSheet(short propVal)
{
	SetProperty(0x17f, VT_I2, propVal);
}

LPDISPATCH CSpreadSheet::GetTabStripFont()
{
	LPDISPATCH result;
	GetProperty(0x180, VT_DISPATCH, (void*)&result);
	return result;
}

void CSpreadSheet::SetTabStripFont(LPDISPATCH propVal)
{
	SetProperty(0x180, VT_DISPATCH, propVal);
}

CString CSpreadSheet::GetSheetName()
{
	CString result;
	GetProperty(0x181, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetSheetName(LPCTSTR propVal)
{
	SetProperty(0x181, VT_BSTR, propVal);
}

long CSpreadSheet::GetTabStripPolicy()
{
	long result;
	GetProperty(0x182, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetTabStripPolicy(long propVal)
{
	SetProperty(0x182, VT_I4, propVal);
}

double CSpreadSheet::GetTabStripRatio()
{
	double result;
	GetProperty(0x183, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTabStripRatio(double propVal)
{
	SetProperty(0x183, VT_R8, propVal);
}

double CSpreadSheet::GetPrintZoomFactor()
{
	double result;
	GetProperty(0x184, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintZoomFactor(double propVal)
{
	SetProperty(0x184, VT_R8, propVal);
}

short CSpreadSheet::GetScrollBarWidth()
{
	short result;
	GetProperty(0x185, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetScrollBarWidth(short propVal)
{
	SetProperty(0x185, VT_I2, propVal);
}

short CSpreadSheet::GetScrollBarHeight()
{
	short result;
	GetProperty(0x186, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetScrollBarHeight(short propVal)
{
	SetProperty(0x186, VT_I2, propVal);
}

long CSpreadSheet::GetActiveCellHighlightStyle()
{
	long result;
	GetProperty(0x187, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetActiveCellHighlightStyle(long propVal)
{
	SetProperty(0x187, VT_I4, propVal);
}

long CSpreadSheet::GetPrintScalingMethod()
{
	long result;
	GetProperty(0x188, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintScalingMethod(long propVal)
{
	SetProperty(0x188, VT_I4, propVal);
}

short CSpreadSheet::GetPrintBestFitPagesWide()
{
	short result;
	GetProperty(0x189, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintBestFitPagesWide(short propVal)
{
	SetProperty(0x189, VT_I2, propVal);
}

short CSpreadSheet::GetPrintBestFitPagesTall()
{
	short result;
	GetProperty(0x18a, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintBestFitPagesTall(short propVal)
{
	SetProperty(0x18a, VT_I2, propVal);
}

BOOL CSpreadSheet::GetPrintCenterOnPageH()
{
	BOOL result;
	GetProperty(0x18b, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintCenterOnPageH(BOOL propVal)
{
	SetProperty(0x18b, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetPrintCenterOnPageV()
{
	BOOL result;
	GetProperty(0x18c, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetPrintCenterOnPageV(BOOL propVal)
{
	SetProperty(0x18c, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetAllowCxxxove()
{
	BOOL result;
	GetProperty(0x18d, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAllowCxxxove(BOOL propVal)
{
	SetProperty(0x18d, VT_BOOL, propVal);
}

BOOL CSpreadSheet::GetAllowRowMove()
{
	BOOL result;
	GetProperty(0x18e, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetAllowRowMove(BOOL propVal)
{
	SetProperty(0x18e, VT_BOOL, propVal);
}

double CSpreadSheet::GetTypeScientificMin()
{
	double result;
	GetProperty(0x18f, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeScientificMin(double propVal)
{
	SetProperty(0x18f, VT_R8, propVal);
}

double CSpreadSheet::GetTypeScientificMax()
{
	double result;
	GetProperty(0x190, VT_R8, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeScientificMax(double propVal)
{
	SetProperty(0x190, VT_R8, propVal);
}

CString CSpreadSheet::GetTypeScientificDecimal()
{
	CString result;
	GetProperty(0x191, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeScientificDecimal(LPCTSTR propVal)
{
	SetProperty(0x191, VT_BSTR, propVal);
}

short CSpreadSheet::GetTypeScientificDecPlaces()
{
	short result;
	GetProperty(0x192, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetTypeScientificDecPlaces(short propVal)
{
	SetProperty(0x192, VT_I2, propVal);
}

unsigned long CSpreadSheet::GetScrollBarHColor()
{
	unsigned long result;
	GetProperty(0x193, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetScrollBarHColor(unsigned long propVal)
{
	SetProperty(0x193, VT_I4, propVal);
}

unsigned long CSpreadSheet::GetScrollBarVColor()
{
	unsigned long result;
	GetProperty(0x194, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetScrollBarVColor(unsigned long propVal)
{
	SetProperty(0x194, VT_I4, propVal);
}

short CSpreadSheet::GetSheet()
{
	short result;
	GetProperty(0x195, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetSheet(short propVal)
{
	SetProperty(0x195, VT_I2, propVal);
}

short CSpreadSheet::GetSheetSendingEvent()
{
	short result;
	GetProperty(0x196, VT_I2, (void*)&result);
	return result;
}

void CSpreadSheet::SetSheetSendingEvent(short propVal)
{
	SetProperty(0x196, VT_I2, propVal);
}

BOOL CSpreadSheet::GetSheetVisible()
{
	BOOL result;
	GetProperty(0x197, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetSheetVisible(BOOL propVal)
{
	SetProperty(0x197, VT_BOOL, propVal);
}

long CSpreadSheet::GetSelectionCount()
{
	long result;
	GetProperty(0x1e9, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetSelectionCount(long propVal)
{
	SetProperty(0x1e9, VT_I4, propVal);
}

OLE_COLOR CSpreadSheet::GetBackColor()
{
	OLE_COLOR result;
	GetProperty(DISPID_BACKCOLOR, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetBackColor(OLE_COLOR propVal)
{
	SetProperty(DISPID_BACKCOLOR, VT_I4, propVal);
}

short CSpreadSheet::GetBorderStyle()
{
	short result;
	GetProperty(DISPID_BORDERSTYLE, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetBorderStyle(short propVal)
{
	SetProperty(DISPID_BORDERSTYLE, VT_I4, propVal);
}

BOOL CSpreadSheet::GetEnabled()
{
	BOOL result;
	GetProperty(DISPID_ENABLED, VT_BOOL, (void*)&result);
	return result;
}

void CSpreadSheet::SetEnabled(BOOL propVal)
{
	SetProperty(DISPID_ENABLED, VT_BOOL, propVal);
}

LPFONTDISP CSpreadSheet::GetFont()
{
	LPFONTDISP result;
	GetProperty(DISPID_FONT, VT_DISPATCH, (void*)&result);
	return result;
}

void CSpreadSheet::SetFont(LPFONTDISP propVal)
{
	SetProperty(DISPID_FONT, VT_DISPATCH, propVal);
}

OLE_COLOR CSpreadSheet::GetForeColor()
{
	OLE_COLOR result;
	GetProperty(DISPID_FORECOLOR, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetForeColor(OLE_COLOR propVal)
{
	SetProperty(DISPID_FORECOLOR, VT_I4, propVal);
}

OLE_HANDLE CSpreadSheet::GetHWnd()
{
	OLE_HANDLE result;
	GetProperty(DISPID_HWND, VT_I4, (void*)&result);
	return result;
}

void CSpreadSheet::SetHWnd(OLE_HANDLE propVal)
{
	SetProperty(DISPID_HWND, VT_I4, propVal);
}

CString CSpreadSheet::GetText()
{
	CString result;
	GetProperty(DISPID_TEXT, VT_BSTR, (void*)&result);
	return result;
}

void CSpreadSheet::SetText(LPCTSTR propVal)
{
	SetProperty(DISPID_TEXT, VT_BSTR, propVal);
}

/////////////////////////////////////////////////////////////////////////////
// CSpreadSheet �I�y���[�V����

double CSpreadSheet::GetColWidth(long lCol)
{
	double result;
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0xd9, DISPATCH_PROPERTYGET, VT_R8, (void*)&result, pxxxs,
		lCol);
	return result;
}

void CSpreadSheet::SetColWidth(long lCol, double newValue)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_R8;
	InvokeHelper(0xd9, DISPATCH_PROPERTYPUT, VT_EMPTY, NULL, pxxxs,
		 lCol, newValue);
}

double CSpreadSheet::GetMaxTextColWidth(long lCol)
{
	double result;
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0xda, DISPATCH_PROPERTYGET, VT_R8, (void*)&result, pxxxs,
		lCol);
	return result;
}

void CSpreadSheet::SetMaxTextColWidth(long lCol, double newValue)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_R8;
	InvokeHelper(0xda, DISPATCH_PROPERTYPUT, VT_EMPTY, NULL, pxxxs,
		 lCol, newValue);
}

double CSpreadSheet::GetMaxTextRowHeight(long lRow)
{
	double result;
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0xdb, DISPATCH_PROPERTYGET, VT_R8, (void*)&result, pxxxs,
		lRow);
	return result;
}

void CSpreadSheet::SetMaxTextRowHeight(long lRow, double newValue)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_R8;
	InvokeHelper(0xdb, DISPATCH_PROPERTYPUT, VT_EMPTY, NULL, pxxxs,
		 lRow, newValue);
}

double CSpreadSheet::GetRowHeight(long lRow)
{
	double result;
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0xdc, DISPATCH_PROPERTYGET, VT_R8, (void*)&result, pxxxs,
		lRow);
	return result;
}

void CSpreadSheet::SetRowHeight(long lRow, double newValue)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_R8;
	InvokeHelper(0xdc, DISPATCH_PROPERTYPUT, VT_EMPTY, NULL, pxxxs,
		 lRow, newValue);
}

long CSpreadSheet::GetSortKey(short nIndex)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I2;
	InvokeHelper(0xdd, DISPATCH_PROPERTYGET, VT_I4, (void*)&result, pxxxs,
		nIndex);
	return result;
}

void CSpreadSheet::SetSortKey(short nIndex, long nNewValue)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_I4;
	InvokeHelper(0xdd, DISPATCH_PROPERTYPUT, VT_EMPTY, NULL, pxxxs,
		 nIndex, nNewValue);
}

long CSpreadSheet::GetSortKeyOrder(short nIndex)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I2;
	InvokeHelper(0xde, DISPATCH_PROPERTYGET, VT_I4, (void*)&result, pxxxs,
		nIndex);
	return result;
}

void CSpreadSheet::SetSortKeyOrder(short nIndex, long nNewValue)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_I4;
	InvokeHelper(0xde, DISPATCH_PROPERTYPUT, VT_EMPTY, NULL, pxxxs,
		 nIndex, nNewValue);
}

LPDISPATCH CSpreadSheet::GetTypeCheckPicture(short Index)
{
	LPDISPATCH result;
	static BYTE pxxxs[] =
		VTS_I2;
	InvokeHelper(0xdf, DISPATCH_PROPERTYGET, VT_DISPATCH, (void*)&result, pxxxs,
		Index);
	return result;
}

void CSpreadSheet::SetTypeCheckPicture(short Index, LPDISPATCH newValue)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_DISPATCH;
	InvokeHelper(0xdf, DISPATCH_PROPERTYPUT, VT_EMPTY, NULL, pxxxs,
		 Index, newValue);
}

BOOL CSpreadSheet::AddCustomFunction(LPCTSTR FunctionName, short ParameterCnt)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_I2;
	InvokeHelper(0xe0, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FunctionName, ParameterCnt);
	return result;
}

BOOL CSpreadSheet::AddCustomFunctionExt(LPCTSTR FunctionName, short MinParamCnt, short MaxParamCnt, long Flags)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_I2 VTS_I2 VTS_I4;
	InvokeHelper(0xe1, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FunctionName, MinParamCnt, MaxParamCnt, Flags);
	return result;
}

void CSpreadSheet::CFGetCellParam(short Param, long* Col, long* Row)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_PI4 VTS_PI4;
	InvokeHelper(0xe2, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Param, Col, Row);
}

double CSpreadSheet::CFGetDoubleParam(short Param)
{
	double result;
	static BYTE pxxxs[] =
		VTS_I2;
	InvokeHelper(0xe3, DISPATCH_METHOD, VT_R8, (void*)&result, pxxxs,
		Param);
	return result;
}

double CSpreadSheet::CFGetDoubleParamExt(short Param, double* ParamValue)
{
	double result;
	static BYTE pxxxs[] =
		VTS_I2 VTS_PR8;
	InvokeHelper(0xe4, DISPATCH_METHOD, VT_R8, (void*)&result, pxxxs,
		Param, ParamValue);
	return result;
}

long CSpreadSheet::CFGetLongParam(short Param)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I2;
	InvokeHelper(0xe5, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		Param);
	return result;
}

BOOL CSpreadSheet::CFGetParamInfo(short Param, long* Type, long* Status)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I2 VTS_PI4 VTS_PI4;
	InvokeHelper(0xe6, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Param, Type, Status);
	return result;
}

void CSpreadSheet::CFGetRangeParam(short Param, long* Col, long* Row, long* Col2, long* Row2)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_PI4 VTS_PI4 VTS_PI4 VTS_PI4;
	InvokeHelper(0xe7, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Param, Col, Row, Col2, Row2);
}

CString CSpreadSheet::CFGetStringParam(short Param)
{
	CString result;
	static BYTE pxxxs[] =
		VTS_I2;
	InvokeHelper(0xe8, DISPATCH_METHOD, VT_BSTR, (void*)&result, pxxxs,
		Param);
	return result;
}

void CSpreadSheet::CFSetResult(const VARIANT& Var)
{
	static BYTE pxxxs[] =
		VTS_VARIANT;
	InvokeHelper(0xe9, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 &Var);
}

CString CSpreadSheet::ColNumberToLetter(long HeaderNumber)
{
	CString result;
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0xea, DISPATCH_METHOD, VT_BSTR, (void*)&result, pxxxs,
		HeaderNumber);
	return result;
}

void CSpreadSheet::ColWidthToTwips(float Width, long* Twips)
{
	static BYTE pxxxs[] =
		VTS_R4 VTS_PI4;
	InvokeHelper(0xeb, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Width, Twips);
}

void CSpreadSheet::GetBottomRightCell(long* Col, long* Row)
{
	static BYTE pxxxs[] =
		VTS_PI4 VTS_PI4;
	InvokeHelper(0xec, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Row);
}

BOOL CSpreadSheet::GetCellDirtyFlag(long Col, long Row)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0xed, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row);
	return result;
}

void CSpreadSheet::GetCellFromScreenCoord(long* Col, long* Row, long x, long y)
{
	static BYTE pxxxs[] =
		VTS_PI4 VTS_PI4 VTS_I4 VTS_I4;
	InvokeHelper(0xee, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Row, x, y);
}

BOOL CSpreadSheet::GetCellPos(long Col, long Row, long* x, long* y, long* Width, long* Height)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PI4 VTS_PI4 VTS_PI4 VTS_PI4;
	InvokeHelper(0xef, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, x, y, Width, Height);
	return result;
}

void CSpreadSheet::GetClientArea(long* Width, long* Height)
{
	static BYTE pxxxs[] =
		VTS_PI4 VTS_PI4;
	InvokeHelper(0xf0, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Width, Height);
}

long CSpreadSheet::GetColItemData(long Col)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0xf1, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		Col);
	return result;
}

CString CSpreadSheet::QueryCustomName(LPCTSTR Name)
{
	CString result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0xf2, DISPATCH_METHOD, VT_BSTR, (void*)&result, pxxxs,
		Name);
	return result;
}

CString CSpreadSheet::GetCustomName(LPCTSTR Name)
{
	CString result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0xf3, DISPATCH_METHOD, VT_BSTR, (void*)&result, pxxxs,
		Name);
	return result;
}

BOOL CSpreadSheet::GetDataFillData(VARIANT* Var, short VarType)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_I2;
	InvokeHelper(0xf4, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Var, VarType);
	return result;
}

void CSpreadSheet::GetFirstValidCell(long* Col, long* Row)
{
	static BYTE pxxxs[] =
		VTS_PI4 VTS_PI4;
	InvokeHelper(0xf5, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Row);
}

long CSpreadSheet::GetItemData()
{
	long result;
	InvokeHelper(0xf6, DISPATCH_METHOD, VT_I4, (void*)&result, NULL);
	return result;
}

BOOL CSpreadSheet::GetIteration(short* MaxIterations, double* MaxChange)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_PI2 VTS_PR8;
	InvokeHelper(0xf7, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		MaxIterations, MaxChange);
	return result;
}

void CSpreadSheet::GetLastValidCell(long* Col, long* Row)
{
	static BYTE pxxxs[] =
		VTS_PI4 VTS_PI4;
	InvokeHelper(0xf8, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Row);
}

long CSpreadSheet::GetMultiSelItem(long SelPrev)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0xf9, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		SelPrev);
	return result;
}

long CSpreadSheet::GetRefStyle()
{
	long result;
	InvokeHelper(0xfa, DISPATCH_METHOD, VT_I4, (void*)&result, NULL);
	return result;
}

long CSpreadSheet::GetRowItemData(long Row)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0xfb, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		Row);
	return result;
}

BOOL CSpreadSheet::GetText(long Col, long Row, VARIANT* Var)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PVARIANT;
	InvokeHelper(0xfc, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Var);
	return result;
}

BOOL CSpreadSheet::IsCellSelected(long Col, long Row)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0xfd, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row);
	return result;
}

BOOL CSpreadSheet::IsFormulaValid(LPCTSTR Formula)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0xfe, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Formula);
	return result;
}

BOOL CSpreadSheet::IsVisible(long Col, long Row, BOOL Partial)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_BOOL;
	InvokeHelper(0xff, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Partial);
	return result;
}

BOOL CSpreadSheet::LoadFromFile(LPCTSTR FileName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0x100, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName);
	return result;
}

BOOL CSpreadSheet::LoadTabFile(LPCTSTR FileName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0x101, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName);
	return result;
}

void CSpreadSheet::RowHeightToTwips(long Row, float Height, long* Twips)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_R4 VTS_PI4;
	InvokeHelper(0x102, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Row, Height, Twips);
}

BOOL CSpreadSheet::SaveTabFile(LPCTSTR FileName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0x103, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName);
	return result;
}

BOOL CSpreadSheet::SaveToFile(LPCTSTR FileName, BOOL DataOnly)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BOOL;
	InvokeHelper(0x104, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, DataOnly);
	return result;
}

BOOL CSpreadSheet::SetCellDirtyFlag(long Col, long Row, BOOL Dirty)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_BOOL;
	InvokeHelper(0x105, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Dirty);
	return result;
}

void CSpreadSheet::SetColItemData(long Col, long Value)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x106, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Value);
}

BOOL CSpreadSheet::SetCustomName(LPCTSTR Name, LPCTSTR Value)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR;
	InvokeHelper(0x107, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Name, Value);
	return result;
}

BOOL CSpreadSheet::SetDataFillData(const VARIANT& Var)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_VARIANT;
	InvokeHelper(0x108, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		&Var);
	return result;
}

void CSpreadSheet::SetItemData(long Value)
{
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0x109, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Value);
}

void CSpreadSheet::SetIteration(BOOL Iteration, short MaxIterations, double MaxChange)
{
	static BYTE pxxxs[] =
		VTS_BOOL VTS_I2 VTS_R8;
	InvokeHelper(0x10a, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Iteration, MaxIterations, MaxChange);
}

void CSpreadSheet::SetRefStyle(long RefStyle)
{
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0x10b, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 RefStyle);
}

void CSpreadSheet::SetRowItemData(long Row, long Value)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x10c, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Row, Value);
}

void CSpreadSheet::SetText(long Col, long Row, const VARIANT& Var)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_VARIANT;
	InvokeHelper(0x10d, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Row, &Var);
}

void CSpreadSheet::TwipsToColWidth(long Twips, float* ColWidth)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_PR4;
	InvokeHelper(0x10e, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Twips, ColWidth);
}

void CSpreadSheet::TwipsToRowHeight(long Row, long Twips, float* RowHeight)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PR4;
	InvokeHelper(0x10f, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Row, Twips, RowHeight);
}

BOOL CSpreadSheet::GetActionKey(long Action, BOOL* fShift, BOOL* fCtrl, short* Key)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_PBOOL VTS_PBOOL VTS_PI2;
	InvokeHelper(0x124, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Action, fShift, fCtrl, Key);
	return result;
}

BOOL CSpreadSheet::SetActionKey(long Action, BOOL fShift, BOOL fCtrl, short Key)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_BOOL VTS_BOOL VTS_I2;
	InvokeHelper(0x125, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Action, fShift, fCtrl, Key);
	return result;
}

BOOL CSpreadSheet::GetOddEvenRowColor(long* clrBackOdd, long* clrForeOdd, long* clrBackEven, long* clrForeEven)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_PI4 VTS_PI4 VTS_PI4 VTS_PI4;
	InvokeHelper(0x126, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		clrBackOdd, clrForeOdd, clrBackEven, clrForeEven);
	return result;
}

BOOL CSpreadSheet::SetOddEvenRowColor(long clrBackOdd, long clrForeOdd, long clrBackEven, long clrForeEven)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x127, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		clrBackOdd, clrForeOdd, clrBackEven, clrForeEven);
	return result;
}

BOOL CSpreadSheet::GetFloat(long Col, long Row, double* Value)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PR8;
	InvokeHelper(0x128, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Value);
	return result;
}

BOOL CSpreadSheet::SetFloat(long Col, long Row, double Value)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_R8;
	InvokeHelper(0x129, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Value);
	return result;
}

BOOL CSpreadSheet::GetInteger(long Col, long Row, long* Value)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PI4;
	InvokeHelper(0x12a, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Value);
	return result;
}

BOOL CSpreadSheet::SetInteger(long Col, long Row, long Value)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x12b, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Value);
	return result;
}

void CSpreadSheet::SetCalText(LPCTSTR ShortDays, LPCTSTR LongDays, LPCTSTR ShortMonths, LPCTSTR LongMonths, LPCTSTR OkText, LPCTSTR CancelText)
{
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR;
	InvokeHelper(0x12c, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 ShortDays, LongDays, ShortMonths, LongMonths, OkText, CancelText);
}

BOOL CSpreadSheet::GetTextTipAppearance(BSTR* FontName, short* FontSize, BOOL* FontBold, BOOL* FontItalic, long* BackColor, long* ForeColor)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_PBSTR VTS_PI2 VTS_PBOOL VTS_PBOOL VTS_PI4 VTS_PI4;
	InvokeHelper(0x12d, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FontName, FontSize, FontBold, FontItalic, BackColor, ForeColor);
	return result;
}

BOOL CSpreadSheet::SetTextTipAppearance(LPCTSTR FontName, short FontSize, BOOL FontBold, BOOL FontItalic, long BackColor, long ForeColor)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_I2 VTS_BOOL VTS_BOOL VTS_I4 VTS_I4;
	InvokeHelper(0x12e, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FontName, FontSize, FontBold, FontItalic, BackColor, ForeColor);
	return result;
}

BOOL CSpreadSheet::ExportToHTML(LPCTSTR FileName, BOOL AppendFlag, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BOOL VTS_BSTR;
	InvokeHelper(0x12f, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, AppendFlag, LogFile);
	return result;
}

BOOL CSpreadSheet::ExportRangeToHTML(long Col, long Row, long Col2, long Row2, LPCTSTR FileName, BOOL AppendFlag, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_BSTR VTS_BOOL VTS_BSTR;
	InvokeHelper(0x130, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Col2, Row2, FileName, AppendFlag, LogFile);
	return result;
}

BOOL CSpreadSheet::GetArray(long ColLeft, long RowTop, const VARIANT& VarArray)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_VARIANT;
	InvokeHelper(0x131, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		ColLeft, RowTop, &VarArray);
	return result;
}

BOOL CSpreadSheet::SetArray(long ColLeft, long RowTop, const VARIANT& VarArray)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_VARIANT;
	InvokeHelper(0x132, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		ColLeft, RowTop, &VarArray);
	return result;
}

short CSpreadSheet::IsExcelFile(LPCTSTR FileName)
{
	short result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0x133, DISPATCH_METHOD, VT_I2, (void*)&result, pxxxs,
		FileName);
	return result;
}

BOOL CSpreadSheet::GetExcelSheetList(LPCTSTR FileName, const VARIANT& VarArray, short* Lixxxount, LPCTSTR LogFileName, short* WorkbookHandle, BOOL Replace)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_VARIANT VTS_PI2 VTS_BSTR VTS_PI2 VTS_BOOL;
	InvokeHelper(0x134, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, &VarArray, Lixxxount, LogFileName, WorkbookHandle, Replace);
	return result;
}

BOOL CSpreadSheet::ImportExcelSheet(short WorkbookHandle, const VARIANT& Sheet)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I2 VTS_VARIANT;
	InvokeHelper(0x135, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		WorkbookHandle, &Sheet);
	return result;
}

BOOL CSpreadSheet::ExportToExcel(LPCTSTR FileName, LPCTSTR SheetName, LPCTSTR LogFileName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_BSTR;
	InvokeHelper(0x136, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, SheetName, LogFileName);
	return result;
}

BOOL CSpreadSheet::EnumCustomFunction(LPCTSTR PrevFuncName, BSTR* FuncName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_PBSTR;
	InvokeHelper(0x137, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		PrevFuncName, FuncName);
	return result;
}

BOOL CSpreadSheet::GetCustomFunction(LPCTSTR FuncName, short* MinArgs, short* MaxArgs, long* Flags)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_PI2 VTS_PI2 VTS_PI4;
	InvokeHelper(0x138, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FuncName, MinArgs, MaxArgs, Flags);
	return result;
}

BOOL CSpreadSheet::RemoveCustomFunction(LPCTSTR FuncName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0x139, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FuncName);
	return result;
}

BOOL CSpreadSheet::ReCalcCell(long Col, long Row)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x13a, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row);
	return result;
}

void CSpreadSheet::OLEDrag()
{
	InvokeHelper(0x13c, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

long CSpreadSheet::GetColUserSortIndicator(long lCol)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I4;
	InvokeHelper(0x142, DISPATCH_PROPERTYGET, VT_I4, (void*)&result, pxxxs,
		lCol);
	return result;
}

void CSpreadSheet::SetColUserSortIndicator(long lCol, long nNewValue)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x142, DISPATCH_PROPERTYPUT, VT_EMPTY, NULL, pxxxs,
		 lCol, nNewValue);
}

BOOL CSpreadSheet::IsFetchCellNote()
{
	BOOL result;
	InvokeHelper(0x143, DISPATCH_METHOD, VT_BOOL, (void*)&result, NULL);
	return result;
}

BOOL CSpreadSheet::ExportToXML(LPCTSTR FileName, LPCTSTR Root, LPCTSTR Collection, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_BSTR VTS_I4 VTS_BSTR;
	InvokeHelper(0x144, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, Root, Collection, Flags, LogFile);
	return result;
}

BOOL CSpreadSheet::ExportRangeToXML(long Col, long Row, long Col2, long Row2, LPCTSTR FileName, LPCTSTR Root, LPCTSTR Collection, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_BSTR VTS_BSTR VTS_BSTR VTS_I4 VTS_BSTR;
	InvokeHelper(0x145, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Col2, Row2, FileName, Root, Collection, Flags, LogFile);
	return result;
}

BOOL CSpreadSheet::ExportToXMLBuffer(LPCTSTR Root, LPCTSTR Collection, BSTR* Buff, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_PBSTR VTS_I4 VTS_BSTR;
	InvokeHelper(0x146, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Root, Collection, Buff, Flags, LogFile);
	return result;
}

BOOL CSpreadSheet::ExportRangeToXMLBuffer(long Col, long Row, long Col2, long Row2, LPCTSTR Root, LPCTSTR Collection, BSTR* Buff, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_BSTR VTS_BSTR VTS_PBSTR VTS_I4 VTS_BSTR;
	InvokeHelper(0x147, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Col2, Row2, Root, Collection, Buff, Flags, LogFile);
	return result;
}

BOOL CSpreadSheet::OwnerPrintDraw(long hDC, long Left, long Top, long Right, long Bottom, short Page)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I2;
	InvokeHelper(0x148, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		hDC, Left, Top, Right, Bottom, Page);
	return result;
}

BOOL CSpreadSheet::OwnerPrintPageCount(long hDC, long Left, long Top, long Right, long Bottom, short* PageCount)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_PI2;
	InvokeHelper(0x149, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		hDC, Left, Top, Right, Bottom, PageCount);
	return result;
}

BOOL CSpreadSheet::ExportRangeToTextFile(long Col, long Row, long Col2, long Row2, LPCTSTR FileName, LPCTSTR CellDelim, LPCTSTR ColDelim, LPCTSTR RowDelim, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR VTS_I4 VTS_BSTR;
	InvokeHelper(0x14b, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Col2, Row2, FileName, CellDelim, ColDelim, RowDelim, Flags, LogFile);
	return result;
}

BOOL CSpreadSheet::ExportToTextFile(LPCTSTR FileName, LPCTSTR CellDelim, LPCTSTR ColDelim, LPCTSTR RowDelim, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR VTS_I4 VTS_BSTR;
	InvokeHelper(0x14c, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, CellDelim, ColDelim, RowDelim, Flags, LogFile);
	return result;
}

BOOL CSpreadSheet::LoadTextFile(LPCTSTR FileName, LPCTSTR CellDelim, LPCTSTR ColDelim, LPCTSTR RowDelim, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR VTS_I4 VTS_BSTR;
	InvokeHelper(0x14d, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, CellDelim, ColDelim, RowDelim, Flags, LogFile);
	return result;
}

void CSpreadSheet::SetCalTextOverride(LPCTSTR ShortDays, LPCTSTR LongDays, LPCTSTR ShortMonths, LPCTSTR LongMonths, LPCTSTR OkText, LPCTSTR CancelText)
{
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR;
	InvokeHelper(0x14e, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 ShortDays, LongDays, ShortMonths, LongMonths, OkText, CancelText);
}

void CSpreadSheet::GetCalTextOverride(BSTR* ShortDays, short* LenShortDays, BSTR* LongDays, short* LenLongDays, BSTR* ShortMonths, short* LenShortMonths, BSTR* LongMonths, short* LenLongMonths, BSTR* OkText, short* LenOkText, BSTR* CancelText,
		short* LenCancelText)
{
	static BYTE pxxxs[] =
		VTS_PBSTR VTS_PI2 VTS_PBSTR VTS_PI2 VTS_PBSTR VTS_PI2 VTS_PBSTR VTS_PI2 VTS_PBSTR VTS_PI2 VTS_PBSTR VTS_PI2;
	InvokeHelper(0x14f, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 ShortDays, LenShortDays, LongDays, LenLongDays, ShortMonths, LenShortMonths, LongMonths, LenLongMonths, OkText, LenOkText, CancelText, LenCancelText);
}

void CSpreadSheet::ScriptCFGetCellParam(short Param, VARIANT* Col, VARIANT* Row)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1b3, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Param, Col, Row);
}

double CSpreadSheet::ScriptCFGetDoubleParamExt(short Param, VARIANT* ParamValue)
{
	double result;
	static BYTE pxxxs[] =
		VTS_I2 VTS_PVARIANT;
	InvokeHelper(0x1b4, DISPATCH_METHOD, VT_R8, (void*)&result, pxxxs,
		Param, ParamValue);
	return result;
}

BOOL CSpreadSheet::ScriptCFGetParamInfo(short Param, VARIANT* Type, VARIANT* Status)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I2 VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1b5, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Param, Type, Status);
	return result;
}

void CSpreadSheet::ScriptCFGetRangeParam(short Param, VARIANT* Col, VARIANT* Row, VARIANT* Col2, VARIANT* Row2)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1b6, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Param, Col, Row, Col2, Row2);
}

void CSpreadSheet::ScriptColWidthToTwips(float Width, VARIANT* Twips)
{
	static BYTE pxxxs[] =
		VTS_R4 VTS_PVARIANT;
	InvokeHelper(0x1b7, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Width, Twips);
}

void CSpreadSheet::ScriptGetBottomRightCell(VARIANT* Col, VARIANT* Row)
{
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1b8, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Row);
}

void CSpreadSheet::ScriptGetCellFromScreenCoord(VARIANT* Col, VARIANT* Row, long x, long y)
{
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT VTS_I4 VTS_I4;
	InvokeHelper(0x1b9, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Row, x, y);
}

BOOL CSpreadSheet::ScriptGetCellPos(long Col, long Row, VARIANT* x, VARIANT* y, VARIANT* Width, VARIANT* Height)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1ba, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, x, y, Width, Height);
	return result;
}

void CSpreadSheet::ScriptGetClientArea(VARIANT* Width, VARIANT* Height)
{
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1bb, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Width, Height);
}

void CSpreadSheet::ScriptGetFirstValidCell(VARIANT* Col, VARIANT* Row)
{
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1bc, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Row);
}

BOOL CSpreadSheet::ScriptGetIteration(VARIANT* MaxIterations, VARIANT* MaxChange)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1bd, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		MaxIterations, MaxChange);
	return result;
}

void CSpreadSheet::ScriptGetLastValidCell(VARIANT* Col, VARIANT* Row)
{
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1be, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Col, Row);
}

void CSpreadSheet::ScriptRowHeightToTwips(long Row, float Height, VARIANT* Twips)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_R4 VTS_PVARIANT;
	InvokeHelper(0x1bf, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Row, Height, Twips);
}

void CSpreadSheet::ScriptTwipsToColWidth(long Twips, VARIANT* ColWidth)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_PVARIANT;
	InvokeHelper(0x1c0, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Twips, ColWidth);
}

void CSpreadSheet::ScriptTwipsToRowHeight(long Row, long Twips, VARIANT* RowHeight)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PVARIANT;
	InvokeHelper(0x1c1, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Row, Twips, RowHeight);
}

BOOL CSpreadSheet::ScriptGetActionKey(short Action, VARIANT* fShift, VARIANT* fCtrl, VARIANT* Key)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I2 VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1c2, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Action, fShift, fCtrl, Key);
	return result;
}

BOOL CSpreadSheet::ScriptGetOddEvenRowColor(VARIANT* clrBackOdd, VARIANT* clrForeOdd, VARIANT* clrBackEven, VARIANT* clrForeEven)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1c3, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		clrBackOdd, clrForeOdd, clrBackEven, clrForeEven);
	return result;
}

BOOL CSpreadSheet::ScriptGetFloat(long Col, long Row, VARIANT* Value)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PVARIANT;
	InvokeHelper(0x1c4, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Value);
	return result;
}

BOOL CSpreadSheet::ScriptGetInteger(long Col, long Row, VARIANT* Value)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PVARIANT;
	InvokeHelper(0x1c5, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Value);
	return result;
}

BOOL CSpreadSheet::ScriptGetTextTipAppearance(VARIANT* FontName, VARIANT* FontSize, VARIANT* FontBold, VARIANT* FontItalic, VARIANT* BackColor, VARIANT* ForeColor)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1c6, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FontName, FontSize, FontBold, FontItalic, BackColor, ForeColor);
	return result;
}

BOOL CSpreadSheet::ScriptGetExcelSheetList(LPCTSTR FileName, const VARIANT& VarArray, VARIANT* Lixxxount, LPCTSTR LogFileName, VARIANT* WorkbookHandle, BOOL Replace)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_VARIANT VTS_PVARIANT VTS_BSTR VTS_PVARIANT VTS_BOOL;
	InvokeHelper(0x1c7, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, &VarArray, Lixxxount, LogFileName, WorkbookHandle, Replace);
	return result;
}

BOOL CSpreadSheet::ScriptEnumCustomFunction(LPCTSTR PrevFuncName, VARIANT* FuncName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_PVARIANT;
	InvokeHelper(0x1c8, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		PrevFuncName, FuncName);
	return result;
}

BOOL CSpreadSheet::ScriptGetCustomFunction(LPCTSTR FuncName, VARIANT* MinArgs, VARIANT* MaxArgs, VARIANT* Flags)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1c9, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FuncName, MinArgs, MaxArgs, Flags);
	return result;
}

BOOL CSpreadSheet::ScriptExportToXMLBuffer(LPCTSTR Root, LPCTSTR Collection, VARIANT* Buff, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_PVARIANT VTS_I4 VTS_BSTR;
	InvokeHelper(0x1ca, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Root, Collection, Buff, Flags, LogFile);
	return result;
}

BOOL CSpreadSheet::ScriptExportRangeToXMLBuffer(long Col, long Row, long Col2, long Row2, LPCTSTR Root, LPCTSTR Collection, VARIANT* Buff, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_BSTR VTS_BSTR VTS_PVARIANT VTS_I4 VTS_BSTR;
	InvokeHelper(0x1cb, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Col2, Row2, Root, Collection, Buff, Flags, LogFile);
	return result;
}

long CSpreadSheet::ScriptOwnerPrintPageCount(long hDC, long Left, long Top, long Right, long Bottom, VARIANT* PageCount)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_PVARIANT;
	InvokeHelper(0x1cc, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		hDC, Left, Top, Right, Bottom, PageCount);
	return result;
}

void CSpreadSheet::ScriptGetCalTextOverride(VARIANT* ShortDays, VARIANT* LenShortDays, VARIANT* LongDays, VARIANT* LenLongDays, VARIANT* ShortMonths, VARIANT* LenShortMonths, VARIANT* LongMonths, VARIANT* LenLongMonths, VARIANT* OkText, VARIANT* LenOkText,
		VARIANT* CancelText, VARIANT* LenCancelText)
{
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1cd, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 ShortDays, LenShortDays, LongDays, LenLongDays, ShortMonths, LenShortMonths, LongMonths, LenLongMonths, OkText, LenOkText, CancelText, LenCancelText);
}

void CSpreadSheet::PrintSheet(VARIANT* Flags)
{
	static BYTE pxxxs[] =
		VTS_PVARIANT;
	InvokeHelper(0x1ce, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Flags);
}

void CSpreadSheet::AddSelection(long lCol, long lRow, long lCol2, long lRow2)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1cf, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, lCol2, lRow2);
}

void CSpreadSheet::ClearRange(long lCol, long lRow, long lCol2, long lRow2, BOOL bDataOnly)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_BOOL;
	InvokeHelper(0x1d0, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, lCol2, lRow2, bDataOnly);
}

void CSpreadSheet::ClearSelection()
{
	InvokeHelper(0x1d1, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

void CSpreadSheet::ClipboardCopy()
{
	InvokeHelper(0x1d2, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

void CSpreadSheet::ClipboardCut()
{
	InvokeHelper(0x1d3, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

void CSpreadSheet::ClipboardPaste()
{
	InvokeHelper(0x1d4, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

void CSpreadSheet::CopyRange(long lCol, long lRow, long lCol2, long lRow2, long lColDest, long lRowDest)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1d5, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, lCol2, lRow2, lColDest, lRowDest);
}

void CSpreadSheet::DeleteCols(long lCol, long lNumCols)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x1d6, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lNumCols);
}

void CSpreadSheet::DeleteRows(long lRow, long lNumRows)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x1d7, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lRow, lNumRows);
}

void CSpreadSheet::DataSave()
{
	InvokeHelper(0x1d8, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

void CSpreadSheet::GetCellBorder(long lCol, long lRow, long nIndex, VARIANT* pcrColor, VARIANT* pnStyle)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1d9, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, nIndex, pcrColor, pnStyle);
}

void CSpreadSheet::GetSelection(long lIndex, VARIANT* plCol, VARIANT* plRow, VARIANT* plCol2, VARIANT* plRow2)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1da, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lIndex, plCol, plRow, plCol2, plRow2);
}

void CSpreadSheet::InsertCols(long lCol, long lNumCols)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x1db, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lNumCols);
}

void CSpreadSheet::InsertRows(long lRow, long lNumRows)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x1dc, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lRow, lNumRows);
}

void CSpreadSheet::MoveRange(long lCol, long lRow, long lCol2, long lRow2, long lColDest, long lRowDest)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1dd, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, lCol2, lRow2, lColDest, lRowDest);
}

void CSpreadSheet::ReCalc()
{
	InvokeHelper(0x1de, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

void CSpreadSheet::Reset()
{
	InvokeHelper(0x1df, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

void CSpreadSheet::SetActiveCell(long lCol, long lRow)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x1e0, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow);
}

void CSpreadSheet::SetCellBorder(long lCol, long lRow, long lCol2, long lRow2, long nIndex, unsigned long crColor, long nStyle)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1e1, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, lCol2, lRow2, nIndex, crColor, nStyle);
}

void CSpreadSheet::SetSelection(long lCol, long lRow, long lCol2, long lRow2)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1e2, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, lCol2, lRow2);
}

void CSpreadSheet::ShowCell(long lCol, long lRow, long nPosition)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1e3, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, nPosition);
}

BOOL CSpreadSheet::Sort(long lCol, long lRow, long lCol2, long lRow2, long nSortBy, VARIANT* SortKeys, VARIANT* SortKeyOrders)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1e4, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		lCol, lRow, lCol2, lRow2, nSortBy, SortKeys, SortKeyOrders);
	return result;
}

void CSpreadSheet::SwapRange(long lCol, long lRow, long lCol2, long lRow2, long lColDest, long lRowDest)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1e5, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, lCol2, lRow2, lColDest, lRowDest);
}

void CSpreadSheet::TypeComboBoxClear(long lCol, long lRow)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x1e6, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow);
}

void CSpreadSheet::TypeComboBoxRemoveItem(long lCol, long lRow, short nIndex)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I2;
	InvokeHelper(0x1e7, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow, nIndex);
}

void CSpreadSheet::VirtualRefresh()
{
	InvokeHelper(0x1e8, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

BOOL CSpreadSheet::AddCellSpan(long lCol, long lRow, long lNumCols, long lNumRows)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1ea, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		lCol, lRow, lNumCols, lNumRows);
	return result;
}

long CSpreadSheet::GetCellSpan(long lCol, long lRow, VARIANT* pvColAnchor, VARIANT* pvRowAnchor, VARIANT* pvNumCols, VARIANT* pvNumRows)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x1eb, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		lCol, lRow, pvColAnchor, pvRowAnchor, pvNumCols, pvNumRows);
	return result;
}

void CSpreadSheet::RemoveCellSpan(long lCol, long lRow)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4;
	InvokeHelper(0x1ec, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lRow);
}

long CSpreadSheet::GetColFromID(LPCTSTR ColID)
{
	long result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0x1ed, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		ColID);
	return result;
}

void CSpreadSheet::CopyColRange(long lCol, long lCol2, long lColDest)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1ee, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lCol2, lColDest);
}

void CSpreadSheet::CopyRowRange(long lRow, long lRow2, long lRowDest)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1ef, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lRow, lRow2, lRowDest);
}

void CSpreadSheet::MoveColRange(long lCol, long lCol2, long lColDest)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1f0, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lCol2, lColDest);
}

void CSpreadSheet::MoveRowRange(long lRow, long lRow2, long lRowDest)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1f1, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lRow, lRow2, lRowDest);
}

void CSpreadSheet::SwapColRange(long lCol, long lCol2, long lColDest)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1f2, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lCol, lCol2, lColDest);
}

void CSpreadSheet::SwapRowRange(long lRow, long lRow2, long lRowDest)
{
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4;
	InvokeHelper(0x1f3, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 lRow, lRow2, lRowDest);
}

long CSpreadSheet::SearchCol(long lCol, long lRowStart, long lRowEnd, LPCTSTR Text, long SearchFlags)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_BSTR VTS_I4;
	InvokeHelper(0x1f4, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		lCol, lRowStart, lRowEnd, Text, SearchFlags);
	return result;
}

long CSpreadSheet::SearchRow(long lRow, long lColStart, long lColEnd, LPCTSTR Text, long SearchFlags)
{
	long result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_BSTR VTS_I4;
	InvokeHelper(0x1f5, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		lRow, lColStart, lColEnd, Text, SearchFlags);
	return result;
}

BOOL CSpreadSheet::GetEventEnabled(short EventID)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I2;
	InvokeHelper(0x1f6, DISPATCH_PROPERTYGET, VT_BOOL, (void*)&result, pxxxs,
		EventID);
	return result;
}

void CSpreadSheet::SetEventEnabled(short EventID, BOOL bNewValue)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_BOOL;
	InvokeHelper(0x1f6, DISPATCH_PROPERTYPUT, VT_EMPTY, NULL, pxxxs,
		 EventID, bNewValue);
}

BOOL CSpreadSheet::ExportRangeToTextFileU(long Col, long Row, long Col2, long Row2, LPCTSTR FileName, LPCTSTR CellDelim, LPCTSTR ColDelim, LPCTSTR RowDelim, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_I4 VTS_I4 VTS_I4 VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR VTS_I4 VTS_BSTR;
	InvokeHelper(0x1f7, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Col, Row, Col2, Row2, FileName, CellDelim, ColDelim, RowDelim, Flags, LogFile);
	return result;
}

BOOL CSpreadSheet::ExportToTextFileU(LPCTSTR FileName, LPCTSTR CellDelim, LPCTSTR ColDelim, LPCTSTR RowDelim, long Flags, LPCTSTR LogFile)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_BSTR VTS_BSTR VTS_I4 VTS_BSTR;
	InvokeHelper(0x1f8, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, CellDelim, ColDelim, RowDelim, Flags, LogFile);
	return result;
}

BOOL CSpreadSheet::SaveTabFileU(LPCTSTR FileName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0x1f9, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName);
	return result;
}

long CSpreadSheet::ColLetterToNumber(LPCTSTR HeaderLetter)
{
	long result;
	static BYTE pxxxs[] =
		VTS_BSTR;
	InvokeHelper(0x1fa, DISPATCH_METHOD, VT_I4, (void*)&result, pxxxs,
		HeaderLetter);
	return result;
}

void CSpreadSheet::DataRefresh()
{
	InvokeHelper(0x1fb, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

BOOL CSpreadSheet::ImportExcelBook(LPCTSTR FileName, LPCTSTR LogFileName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR;
	InvokeHelper(0x1fc, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, LogFileName);
	return result;
}

BOOL CSpreadSheet::ExportExcelBook(LPCTSTR FileName, LPCTSTR LogFileName)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR;
	InvokeHelper(0x1fd, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, LogFileName);
	return result;
}

BOOL CSpreadSheet::InsertSheets(short SheetIndex, short SheetCnt)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I2 VTS_I2;
	InvokeHelper(0x1fe, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		SheetIndex, SheetCnt);
	return result;
}

BOOL CSpreadSheet::DeleteSheets(short SheetIndex, short SheetCnt)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I2 VTS_I2;
	InvokeHelper(0x1ff, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		SheetIndex, SheetCnt);
	return result;
}

BOOL CSpreadSheet::MoveSheets(short SheetSrc, short SheetCnt, short SheetDest)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I2 VTS_I2 VTS_I2;
	InvokeHelper(0x200, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		SheetSrc, SheetCnt, SheetDest);
	return result;
}

BOOL CSpreadSheet::ResetSheet(short Sheet)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_I2;
	InvokeHelper(0x201, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Sheet);
	return result;
}

LPDISPATCH CSpreadSheet::LoadPicture(LPCTSTR FileName, long PictType)
{
	LPDISPATCH result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_I4;
	InvokeHelper(0x202, DISPATCH_METHOD, VT_DISPATCH, (void*)&result, pxxxs,
		FileName, PictType);
	return result;
}

LPDISPATCH CSpreadSheet::LoadResPicture(long hInstance, LPCTSTR ResourceName, LPCTSTR ResourceType, long PictType)
{
	LPDISPATCH result;
	static BYTE pxxxs[] =
		VTS_I4 VTS_BSTR VTS_BSTR VTS_I4;
	InvokeHelper(0x203, DISPATCH_METHOD, VT_DISPATCH, (void*)&result, pxxxs,
		hInstance, ResourceName, ResourceType, PictType);
	return result;
}

LPDISPATCH CSpreadSheet::LoadPictureBuffer(VARIANT* Buffer, VARIANT* Size, long PictType)
{
	LPDISPATCH result;
	static BYTE pxxxs[] =
		VTS_PVARIANT VTS_PVARIANT VTS_I4;
	InvokeHelper(0x204, DISPATCH_METHOD, VT_DISPATCH, (void*)&result, pxxxs,
		Buffer, Size, PictType);
	return result;
}

BOOL CSpreadSheet::SavePicture(LPDISPATCH Picture, LPCTSTR FileName, long PictType)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_DISPATCH VTS_BSTR VTS_I4;
	InvokeHelper(0x205, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Picture, FileName, PictType);
	return result;
}

BOOL CSpreadSheet::SavePictureBuffer(LPDISPATCH Picture, long PictType, VARIANT* Buffer, VARIANT* Size)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_DISPATCH VTS_I4 VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x206, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		Picture, PictType, Buffer, Size);
	return result;
}

void CSpreadSheet::CFGetCellParamExt(short Param, short* Sheet, long* Col, long* Row)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_PI2 VTS_PI4 VTS_PI4;
	InvokeHelper(0x207, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Param, Sheet, Col, Row);
}

void CSpreadSheet::CFGetRangeParamExt(short Param, short* Sheet, long* Col, long* Row, long* Col2, long* Row2)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_PI2 VTS_PI4 VTS_PI4 VTS_PI4 VTS_PI4;
	InvokeHelper(0x208, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Param, Sheet, Col, Row, Col2, Row2);
}

void CSpreadSheet::ScriptCFGetCellParamExt(short Param, VARIANT* Sheet, VARIANT* Col, VARIANT* Row)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x209, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Param, Sheet, Col, Row);
}

void CSpreadSheet::ScriptCFGetRangeParamExt(short Param, VARIANT* Sheet, VARIANT* Col, VARIANT* Row, VARIANT* Col2, VARIANT* Row2)
{
	static BYTE pxxxs[] =
		VTS_I2 VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT;
	InvokeHelper(0x20a, DISPATCH_METHOD, VT_EMPTY, NULL, pxxxs,
		 Param, Sheet, Col, Row, Col2, Row2);
}

BOOL CSpreadSheet::ExportToExcelEx(LPCTSTR FileName, LPCTSTR SheetName, LPCTSTR LogFileName, long Flags)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_BSTR VTS_I4;
	InvokeHelper(0x20b, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, SheetName, LogFileName, Flags);
	return result;
}

BOOL CSpreadSheet::ExportExcelBookEx(LPCTSTR FileName, LPCTSTR LogFileName, long Flags)
{
	BOOL result;
	static BYTE pxxxs[] =
		VTS_BSTR VTS_BSTR VTS_I4;
	InvokeHelper(0x20c, DISPATCH_METHOD, VT_BOOL, (void*)&result, pxxxs,
		FileName, LogFileName, Flags);
	return result;
}

void CSpreadSheet::Refresh()
{
	InvokeHelper(DISPID_REFRESH, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}

void CSpreadSheet::AboutBox()
{
	InvokeHelper(0xfffffdd8, DISPATCH_METHOD, VT_EMPTY, NULL, NULL);
}
