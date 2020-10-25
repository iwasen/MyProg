#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ExcelLib.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CExcelLib,CWorksheet,CExcelData �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

// Excel���ŗp�ӂ���Ă���COM�C���^�[�t�F�C�X���g�p���āAExcel�𑀍삷�邽�߂�
// #import���g�p���ă^�C�v���C�u��������荞��
/*
// ���e�F�����t���̒l��������ϐ��ɂ́A�����t���̌^�ɃL���X�g���Ȃ���΂Ȃ�܂���
#pragma warning( disable : 4146 )	// ���r���h����Warning���\���ɂ��邽��
// ���e�F'name' �������I�ɏ��O���A�^�C�v ���C�u���� 'library' ���C���|�[�g���܂�
#pragma warning( disable : 4192 )	// ���r���h����Warning���\���ɂ��邽��
// ���e�F�R���p�C���̐��� : �s�ԍ��̏o�͂𒆎~���܂��B
#pragma warning( disable : 4049 )	// ���r���h����Warning���\���ɂ��邽��

// �������F�������MSOffic2000�A�C���X�g�[�����C:\Program Files\Microsoft Office
#import "C:\Program Files\Microsoft Office\Office\MSO9.DLL"
#import "C:\Program Files\Common Files\Microsoft Shared\VBA\VBA6\VBE6EXT.OLB"
#import "C:\Program Files\Microsoft Office\Office\EXCEL9.OLB" \
rename("DialogBox", "DialogBoxForExcel") rename( "RGB", "RGBForExcel") no_dual_interfaces
*/

#include "ArrayEx.h"

// �G�N�Z���t�@�C������
class CExcelLib
{
public:
	CExcelLib();
	virtual ~CExcelLib();

	CString m_sErrorMsg;		// �G���[���b�Z�[�W
	int m_nErrorCode;			// �G���[�R�[�h

	BOOL Open(LPCTSTR pFileName);
	BOOL New();
	BOOL Exec(CStringArray &aPathName);
	void Close(BOOL bQuit = TRUE);

	Excel::_ApplicationPtr GetApplication() { return m_pExcel; }
	Excel::WorkbooksPtr GetWorkbooks() { return m_pBooks; }
	Excel::_WorkbookPtr GetWorkbook() { return m_pBook; }
	Excel::WorksheetsPtr GetWorksheets() { return m_pSheets; }

protected:
	Excel::_ApplicationPtr	m_pExcel;		// Excel�A�v���I�u�W�F�N�g�|�C���^
	Excel::WorkbooksPtr		m_pBooks;		// WorkBooks�I�u�W�F�N�g�|�C���^
	Excel::_WorkbookPtr		m_pBook;		// WorkBook�I�u�W�F�N�g�|�C���^
	Excel::WorksheetsPtr	m_pSheets;		// Sheets�I�u�W�F�N�g�|�C���^

	BOOL Initialize();
	void ErrorProc(_com_error &e);
};

// �G�N�Z���z��f�[�^��ێ��E���삷��
class CExcelData
{
public:
	CExcelData();
	void AddSheet(LPCTSTR pSheetName);
	void Create(int nRows, int nCols);
	BOOL Read(CExcelLib &cExcel, int nFirstSheet, int nMaxSheet);
	BOOL Write(CExcelLib &cExcel, LPCTSTR pPathName = NULL);
	void Copy(CExcelData &cSrc);
	void SetCols(int nCols);
// Modify ... ( ADD )
	BOOL SetSheetName(CExcelLib &cExcel, LPCTSTR pPathName = NULL);
// By Y.Itabashi (xxxxx) 2007.02.15
// Modify ... ( ADD )
	BOOL DeleteSheet(CExcelLib &cExcel, LPCTSTR pPathName = NULL);
	void SetDeleteSheet(LPCTSTR pSheetName);
// By Y.Itabashi (xxxxx) 2007.02.19
	void SetRows(int nRows);
	int AddRows(int nAddRows = 1);
	void Clear();
	void GetElement(int nRow, int nCol, _variant_t &vData);
	void GetElement(int nRow, int nCol, CString &sData);
	void GetElement(int nRow, int nCol, double &dData);
	void GetElement(int nRow, int nCol, int &nData);
	void PutElement(int nRow, int nCol, _variant_t &vData);
	void PutElement(int nRow, int nCol, LPCTSTR pData) { PutElement(nRow, nCol, (_variant_t)pData); }
	void PutElement(int nRow, int nCol, double dData) { PutElement(nRow, nCol, (_variant_t)dData); }
	void PutElement(int nRow, int nCol, int nData) { PutElement(nRow, nCol, (_variant_t)(long)nData); }
	int GetUsedCols() { return m_aSheet[m_nCurrentSheetNo].nUsedCols; }
	int GetUsedRows() { return m_aSheet[m_nCurrentSheetNo].nUsedRows; }
	int GetAllocRows() { return m_aSheet[m_nCurrentSheetNo].nAllocRows; }
	COleSafeArray &GetSafeArray() { return m_aSheet[m_nCurrentSheetNo].cSafeArray; }
	void SetCurrentSheet(int nSheetNo) { m_nCurrentSheetNo = nSheetNo; }
	void SetCurrentSheet(LPCTSTR pSheetName) { m_aSheet.LookupElement(pSheetName, m_nCurrentSheetNo); }
	int GetSheetNum() { return m_aSheet.GetSize(); }
	CString GetSheetName() { return m_aSheet[m_nCurrentSheetNo].sSheetName; }
// Modify ... ( ADD )
	int GetCurrentSheetNo()	{ return m_nCurrentSheetNo; }
	void SetCurrentSheetName( CString sSheetName )	{ m_aSheet[m_nCurrentSheetNo].sSheetName = sSheetName; }
// By Y.Itabashi (xxxxx) 2007.02.14

protected:
	struct SSheet{
		CString sSheetName;				// �V�[�g��
		COleSafeArray cSafeArray;		// �G�N�Z���f�[�^�z��
		int nUsedRows;					// �g�p�s��
		int nUsedCols;					// �g�p��
		int nAllocRows;					// �m�ۍs��
// Modify ... ( ADD )
		bool bDeleteFlag;				// �폜�V�[�g
// By Y.Itabashi (xxxxx) 2007.02.19
	};
	typedef CArrayEx2 <SSheet, SSheet&> CSheetArray;

	CSheetArray m_aSheet;
	int m_nCurrentSheetNo;

	void ReadSub(Excel::_WorksheetPtr pWorksheet, SSheet *pSheet);
	void WriteSub(Excel::_WorksheetPtr pWorksheet, SSheet *pSheet);
};
