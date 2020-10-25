// ManualDstrDlg.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "workscheduler.h"
#include "DataManager.h"

#include "MenuDispCtrl.h"

#include "ManualDstrDlg001.h"
#include "ManualDstrDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define F_AUTO_CALC	0
#define F_ALL_DATA	1

/////////////////////////////////////////////////////////////////////////////
// CManualDstrDlg �_�C�A���O


CManualDstrDlg::CManualDstrDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CManualDstrDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CManualDstrDlg)
	m_fKosu = 0.0;
	m_fHyojunJikan = 0.0;
	m_fTeikiTenkenJikan = 0.0;
	m_fHinshitsuLoss = 0.0;
	m_fSetsubiFuguaiLoss = 0.0;
	m_fShujukuLoss = 0.0;
	m_fLineTeishiLoss = 0.0;
	m_fHenseiLoss = 0.0;
	m_fHukugouNoritsuBun = 0.0;
	m_fKanriTeishiJikan = 0.0;
	m_fDST = 0.0;
	m_fDSTR = 0.0;
	//}}AFX_DATA_INIT

	m_nKishuId = 0;
	m_bChangeEdit = 0;
}


void CManualDstrDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CManualDstrDlg)
	DDX_Control(pDX, IDC_COMBO_KISHU, m_cComboKishu);
	DDX_Text(pDX, IDC_EDIT001, m_fKosu);
	DDX_Text(pDX, IDC_EDIT002, m_fHyojunJikan);
	DDX_Text(pDX, IDC_EDIT003, m_fTeikiTenkenJikan);
	DDV_MinMaxDouble(pDX, m_fTeikiTenkenJikan, 0., 16666666.);
	DDX_Text(pDX, IDC_EDIT004, m_fHinshitsuLoss);
	DDV_MinMaxDouble(pDX, m_fHinshitsuLoss, 0., 16666666.);
	DDX_Text(pDX, IDC_EDIT005, m_fSetsubiFuguaiLoss);
	DDV_MinMaxDouble(pDX, m_fSetsubiFuguaiLoss, 0., 16666666.);
	DDX_Text(pDX, IDC_EDIT006, m_fShujukuLoss);
	DDV_MinMaxDouble(pDX, m_fShujukuLoss, 0., 16666666.);
	DDX_Text(pDX, IDC_EDIT007, m_fLineTeishiLoss);
	DDV_MinMaxDouble(pDX, m_fLineTeishiLoss, 0., 16666666.);
	DDX_Text(pDX, IDC_EDIT008, m_fHenseiLoss);
	DDX_Text(pDX, IDC_EDIT009, m_fHukugouNoritsuBun);
	DDV_MinMaxDouble(pDX, m_fHukugouNoritsuBun, 0., 16666666.);
	DDX_Text(pDX, IDC_EDIT010, m_fKanriTeishiJikan);
	DDV_MinMaxDouble(pDX, m_fKanriTeishiJikan, 0., 16666666.);
	DDX_Text(pDX, IDC_EDIT011, m_fDST);
	DDV_MinMaxDouble(pDX, m_fDST, 0., 16666666.);
	DDX_Text(pDX, IDC_EDIT012, m_fDSTR);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CManualDstrDlg, CDialog)
	//{{AFX_MSG_MAP(CManualDstrDlg)
	ON_CBN_SELCHANGE(IDC_COMBO_KISHU, OnSelchangeComboKishu)
	ON_BN_CLICKED(IDC_BUTTON_CALC, OnButtonCalc)
	//}}AFX_MSG_MAP
	ON_CONTROL_RANGE(EN_CHANGE, IDC_EDIT001, IDC_EDIT011, OnChangeEdit)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CManualDstrDlg ���b�Z�[�W �n���h��

BOOL CManualDstrDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();
	
	// ���j���[�Ƀ��j���[�t�@�C���̕�������Z�b�g
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_ManualDstrDlg );

	// DSTR�̏��̎擾
	InitDstrInfo();

	// �R���{�{�b�N�X�̃��X�g�@��̍쐬
// Modify ... ( CHANGE )
	SetKishuList( m_nKishuId );
//	SetKishuList( m_pDoc->m_nGraphKishu );
// By Y.Itabashi (xxxxx) 2007.03.02

	// DSTR�̏��̎擾
	SetDstrInfo( F_ALL_DATA );
	
	// ���͕ύX�C�x���g���
	m_bChangeEdit = 1;

	return TRUE;  // �R���g���[���Ƀt�H�[�J�X��ݒ肵�Ȃ��Ƃ��A�߂�l�� TRUE �ƂȂ�܂�
	              // ��O: OCX �v���p�e�B �y�[�W�̖߂�l�� FALSE �ƂȂ�܂�
}


void CManualDstrDlg::SetKishuList( int nKishu ) 
{
	// �@��R���{�{�b�N�X�\��
	for (int i = 0; i < g_pDataManager->GetKishuNum(); i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);
// Modify ... ( CHANGE )
		m_cComboKishu.SetItemData(m_cComboKishu.InsertString(i, pKishu->sKishuName), i);
//		m_cComboKishu.SetItemData(m_cComboKishu.AddString(pKishu->sKishuName), i);
// By Y.Itabashi (xxxxx) 2007.02.19
	}
	m_cComboKishu.SetItemData(g_pDataManager->GetKishuNum(), -1);

	m_cComboKishu.SetCurSel( ( nKishu == -1 ) ? g_pDataManager->GetKishuNum() : nKishu );
}


void CManualDstrDlg::InitDstrInfo() 
{
	SDSTRSanshutsu *pDstr;
	for( int i = 0; i < g_pDataManager->GetKishuNum(); i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);

		pDstr = m_aDstr.AddElement();
		memcpy( (void *)pDstr, (void *)&(pKishu->cDSTRSanshutsu), sizeof(SDSTRSanshutsu) );

		// �W������
		m_fHyojunJikan = pDstr->fHyojunJikan = CalcHyojunjikan( *pDstr, i );

		// �Ґ����X
		m_fHenseiLoss = pDstr->fHenseiLoss = CalcHenseiLoss( *pDstr, i );
	}

	// ���d����
// Modify ... ( CAHNGE )
	pDstr = m_aDstr.AddElement();
	int bSetDSTR = g_pDataManager->GetTotalDSTRSetFlag();
	if( bSetDSTR ){
		SDSTRSanshutsu *pTotalDstr = g_pDataManager->GetTotalDSTRSanshutsu();
		memcpy( (void *)pDstr, (void *)pTotalDstr, sizeof(SDSTRSanshutsu) );
		pDstr->fHyojunJikan = 0;
		pDstr->fHenseiLoss = 0;
	}else
		memset( pDstr, '\0', sizeof(SDSTRSanshutsu) );
// By Y.Itabashi (xxxxx) 2007.02.14

// Modify ... ( CHANGE )
	int nTotalDaisu = g_pDataManager->GetTotalSeisanDaisu( -1 );
//	int nTotalDaisu = g_pDataManager->GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11
	for( i = 0; i < g_pDataManager->GetKishuNum(); i++) {
		int nDaisu = g_pDataManager->GetKishuData(i)->nSeisanDaisu;
// Modify ... ( CHANGE )
		SDSTRSanshutsu &cDstr = m_aDstr[i];
//		SDSTRSanshutsu &cDstr = g_pDataManager->GetKishuData(i)->cDSTRSanshutsu;
// By Y.Itabashi (xxxxx) 2007.02.10

		pDstr->fHyojunJikan			+= ( cDstr.fHyojunJikan * nDaisu / nTotalDaisu );			// �W������
		if( !bSetDSTR ){
		pDstr->fTeikiTenkenJikan	+= ( cDstr.fTeikiTenkenJikan * nDaisu / nTotalDaisu );		// ����_������
		pDstr->fHinshitsuLoss		+= ( cDstr.fHinshitsuLoss * nDaisu / nTotalDaisu );			// �i�����X
		pDstr->fSetsubiFuguaiLoss	+= ( cDstr.fSetsubiFuguaiLoss * nDaisu / nTotalDaisu );		// �ݔ��s����X
		pDstr->fShujukuLoss			+= ( cDstr.fShujukuLoss * nDaisu / nTotalDaisu );			// �K�n���X
		pDstr->fLineTeishiLoss		+= ( cDstr.fLineTeishiLoss * nDaisu / nTotalDaisu );		// ���C����~���X
		}
		pDstr->fHenseiLoss			+= ( cDstr.fHenseiLoss * nDaisu / nTotalDaisu );			// �Ґ����X
	}

// Modify ... ( CHANGE )
	if( !bSetDSTR ){
	pDstr->fHukugouNoritsuBun	= 0;	// �����\����
	pDstr->fKanriTeishiJikan	= 0;	// �Ǘ���~����
	pDstr->fDST					= 0;	// DST
	}
// By Y.Itabashi (xxxxx) 2007.02.14
}

// Modify ... ( CHANGE )
BOOL CManualDstrDlg::GetDstrInfo() 
//void CManualDstrDlg::GetDstrInfo()
{
	if( UpdateData( TRUE ) == 0 )
		return FALSE;
//	UpdateData( TRUE );

	int nKishuId = ( m_nKishuId == -1 ) ? m_aDstr.GetSize()-1 : m_nKishuId;

	m_aDstr[nKishuId].fTeikiTenkenJikan		= m_fTeikiTenkenJikan;		// ����_������
	m_aDstr[nKishuId].fHinshitsuLoss		= m_fHinshitsuLoss;			// �i�����X
	m_aDstr[nKishuId].fSetsubiFuguaiLoss	= m_fSetsubiFuguaiLoss;		// �ݔ��s����X
	m_aDstr[nKishuId].fShujukuLoss			= m_fShujukuLoss;			// �K�n���X
	m_aDstr[nKishuId].fLineTeishiLoss		= m_fLineTeishiLoss;		// ���C����~���X
	m_aDstr[nKishuId].fHukugouNoritsuBun	= m_fHukugouNoritsuBun;		// �����\����
	m_aDstr[nKishuId].fKanriTeishiJikan		= m_fKanriTeishiJikan;		// �Ǘ���~����
	m_aDstr[nKishuId].fDST					= m_fDST;					// DST

	return TRUE;
}
// By Y.Itabashi (xxxxx) 2007.02.19

void CManualDstrDlg::SetDstrInfo( int mode ) 
{
	if( m_aDstr.GetSize() < m_nKishuId )
		return;

	int nKishuId = ( m_nKishuId == -1 ) ? m_aDstr.GetSize()-1 : m_nKishuId;

	SDSTRSanshutsu &pDstr = m_aDstr.GetAt( nKishuId );

	CString str;

	// ���C�����H��
	m_fKosu = CalcKosu( pDstr );
	str.Format( "%.4f", m_fKosu );
	GetDlgItem( IDC_EDIT001 )->SetWindowText( str );

	// �W������
	str.Format( "%.4f", pDstr.fHyojunJikan );
	GetDlgItem( IDC_EDIT002 )->SetWindowText( str );

	// �Ґ����X
	str.Format( "%.4f", pDstr.fHenseiLoss );
	GetDlgItem( IDC_EDIT008 )->SetWindowText( str );

	if( mode == F_AUTO_CALC )
		return;

	// ���͕ύX�C�x���g�������
	m_bChangeEdit = 0;

	// ����_������
	m_fTeikiTenkenJikan = pDstr.fTeikiTenkenJikan;
	str.Format( "%.4f", m_fTeikiTenkenJikan );
	GetDlgItem( IDC_EDIT003 )->SetWindowText( str );

	// �i�����X
	m_fHinshitsuLoss = pDstr.fHinshitsuLoss;
	str.Format( "%.4f", m_fHinshitsuLoss );
	GetDlgItem( IDC_EDIT004 )->SetWindowText( str );

	// �ݔ��s����X
	m_fSetsubiFuguaiLoss = pDstr.fSetsubiFuguaiLoss;
	str.Format( "%.4f", m_fSetsubiFuguaiLoss );
	GetDlgItem( IDC_EDIT005 )->SetWindowText( str );

	// �K�n���X
	m_fShujukuLoss = pDstr.fShujukuLoss;
	str.Format( "%.4f", m_fShujukuLoss );
	GetDlgItem( IDC_EDIT006 )->SetWindowText( str );

	// ���C����~���X
	m_fLineTeishiLoss = pDstr.fLineTeishiLoss;
	str.Format( "%.4f", m_fLineTeishiLoss );
	GetDlgItem( IDC_EDIT007 )->SetWindowText( str );

	// �����\����
	m_fHenseiLoss = pDstr.fHukugouNoritsuBun;
	str.Format( "%.4f", m_fHenseiLoss );
	GetDlgItem( IDC_EDIT009 )->SetWindowText( str );

	// �Ǘ���~����
	m_fHenseiLoss = pDstr.fKanriTeishiJikan;
	str.Format( "%.4f", m_fHenseiLoss );
	GetDlgItem( IDC_EDIT010 )->SetWindowText( str );

	// DST
	m_fDST = pDstr.fDST;
	str.Format( "%.4f", m_fDST );
	GetDlgItem( IDC_EDIT011 )->SetWindowText( str );

	// ���͕ύX�C�x���g���
	m_bChangeEdit = 1;
}

void CManualDstrDlg::OnSelchangeComboKishu() 
{
	GetDstrInfo();

	int nSel = m_cComboKishu.GetCurSel();
	m_nKishuId = m_cComboKishu.GetItemData( nSel );
	
	// DSTR�̏��\��
	SetDstrInfo( F_ALL_DATA );

// Modify ... ( CHANGE )
	// DSTR
	m_fDSTR = 0;
	CString str;
	str.Format( "%.4f", m_fDSTR );
	GetDlgItem( IDC_EDIT012 )->SetWindowText( str );
// By Y.Itabashi (xxxxx) 2007.02.10
}

double CManualDstrDlg::CalcKosu( SDSTRSanshutsu &pDstr ) 
{
	double fKosu;

	fKosu = pDstr.fHyojunJikan + pDstr.fTeikiTenkenJikan + pDstr.fHinshitsuLoss + pDstr.fSetsubiFuguaiLoss
		+ pDstr.fShujukuLoss + pDstr.fLineTeishiLoss + pDstr.fHenseiLoss;

	return fKosu;
}

double CManualDstrDlg::CalcHyojunjikan( SDSTRSanshutsu &pDstr, int nKishuId ) 
{
	double fTotalTime = 0;

	for( int i = 0; i < g_pDataManager->GetSublineNum() ; i++ ){
		SSubline *pSubline = g_pDataManager->GetSublineData( i );

		CKoteiArray aKotei;
		g_pDataManager->GetKoteiArray( i, nKishuId, aKotei );

		for( int j = 0; j < aKotei.GetSize(); j++ ){
			fTotalTime += aKotei[j].fTime;
		}
		
	}

	return fTotalTime;
}

double CManualDstrDlg::CalcHenseiLoss( SDSTRSanshutsu &pDstr, int nKishuId ) 
{
	double fHenseiLoss = 0;

	int nSagyoshaID = -1;
	int nBSagyoshaID = -1;
	for( int i = 0; i < g_pDataManager->GetSublineNum() ; i++ ){
		SSubline *pSubline = g_pDataManager->GetSublineData( i );

		double fTact = pSubline->cSeisanJoken.fTact;
		int nSagyoshaCnt = pSubline->aSagyosha.GetSize();

		CKoteiArray aKotei;
		g_pDataManager->GetKoteiArray( i, nKishuId, aKotei );

		double fTotalTime = 0;
		for( int j = 0; j < aKotei.GetSize(); j++ ){

			fTotalTime += aKotei[j].fTime;
		}

		fHenseiLoss += ( fTact * nSagyoshaCnt - fTotalTime );
		
	}

	return fHenseiLoss;
}

void CManualDstrDlg::OnButtonCalc() 
{
	GetDstrInfo();

	if( m_fDST != 0.0 )
		m_fDSTR = ( m_fKosu + m_fHukugouNoritsuBun + m_fKanriTeishiJikan ) / m_fDST;
	else
		m_fDSTR = 0.0;

	// DSTR
	CString str;
	str.Format( "%.4f", m_fDSTR );
	GetDlgItem( IDC_EDIT012 )->SetWindowText( str );
}

void CManualDstrDlg::OnOK() 
{
// Modify ... ( CHANGE )
	if( GetDstrInfo() == FALSE )
		return;
//	GetDstrInfo();
// By Y.Itabashi (xxxxx) 2007.02.19

	for( int i = 0; i < g_pDataManager->GetKishuNum(); i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);

		SDSTRSanshutsu &pDstr = m_aDstr.GetAt( i );
		memcpy( (void *)&(pKishu->cDSTRSanshutsu), (void *)&pDstr, sizeof(SDSTRSanshutsu) );
	}

// Modify ... ( ADD )
	SDSTRSanshutsu *pTotalDstr = g_pDataManager->GetTotalDSTRSanshutsu();
	memcpy( (void *)pTotalDstr, (void *)&m_aDstr.GetAt( m_aDstr.GetSize()-1 ), sizeof(SDSTRSanshutsu) );
	g_pDataManager->SetTotalDSTRSetFlag( 1 );
// By Y.Itabashi (xxxxx) 2007.02.17
	
	CDialog::OnOK();
}

void CManualDstrDlg::OnCancel() 
{
	m_aDstr.RemoveAll();
	
	CDialog::OnCancel();
}

void CManualDstrDlg::OnChangeEdit( int nID ) 
{
	if( nID == IDC_EDIT001 || nID == IDC_EDIT002 || nID == IDC_EDIT008 )
		return;

	if( m_bChangeEdit == 0 )
		return;

	GetDstrInfo();
	SetDstrInfo( F_AUTO_CALC );
}
