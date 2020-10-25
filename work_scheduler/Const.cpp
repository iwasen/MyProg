// Const.cpp: CConst クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "Const.h"
#include "Global.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

CConst::CConst()
{
	m_sVersion = "0.1.9.15";
	m_sDefaultFontName = "MS UI Gothic";

	// DPE連携ファイル設定値ファイルから定数を取得
// Modify ... ( CHANGE )
	m_sJido = g_pSystem->GetDPEIndex( 5, 24, 1 );
	m_sHanJido = g_pSystem->GetDPEIndex( 5, 24, 2 );
	m_sShudo = g_pSystem->GetDPEIndex( 5, 24, 3 );
	m_sEngShiseiFlag = g_pSystem->GetDPEIndex( 6, 14, 1 );
	m_sSeiritsuFr = g_pSystem->GetDPEIndex( 6, 13, 1 );
	m_sSeiritsuRr = g_pSystem->GetDPEIndex( 6, 13, 2 );
	m_sToritsuFr = g_pSystem->GetDPEIndex( 6, 13, 3 );
	m_sToritsuRr = g_pSystem->GetDPEIndex( 6, 13, 4 );
	m_sChokuritsuTop = g_pSystem->GetDPEIndex( 6, 13, 5 );
	m_sChokuritsuBottom = g_pSystem->GetDPEIndex( 6, 13, 6 );
	m_sJoryu = g_pSystem->GetDPEIndex( 7, 19, 1 );
	m_sKaryu = g_pSystem->GetDPEIndex( 7, 20, 2 );
	m_sKijun = g_pSystem->GetDPEIndex( 5, 33, 1 );
	m_sKotei = g_pSystem->GetDPEIndex( 5, 33, 2 );

	if( m_sJido == "no item" || m_sHanJido == "no item" || m_sShudo == "no item" ||
		m_sEngShiseiFlag == "no item" || m_sSeiritsuFr == "no item" ||
		m_sSeiritsuRr == "no item" || m_sToritsuFr == "no item" || m_sToritsuRr == "no item" ||
		m_sChokuritsuTop == "no item" || m_sChokuritsuBottom == "no item" ||
		m_sJoryu == "no item" || m_sKaryu == "no item" || m_sKijun == "no item" ||
		m_sKotei == "no item" ){
		g_pSystem->DispMessage( "E001007" );
		exit( 0 );
	}

	if( m_sJido.GetLength() == 0 || m_sHanJido.GetLength() == 0 ||
		m_sShudo.GetLength() == 0 || m_sEngShiseiFlag.GetLength() == 0 ||
		m_sSeiritsuFr.GetLength() == 0 || m_sSeiritsuRr.GetLength() == 0 ||
		m_sToritsuFr.GetLength() == 0 || m_sToritsuRr.GetLength() == 0 ||
		m_sChokuritsuTop.GetLength() == 0 || m_sChokuritsuBottom.GetLength() == 0 ||
		m_sJoryu.GetLength() == 0 || m_sKaryu.GetLength() == 0 ||
		m_sKijun.GetLength() == 0 || m_sKotei.GetLength() == 0 ){
		g_pSystem->DispMessage( "E001007" );
		exit( 0 );
	}
/*	CDPEInterfaceFileArray &aDPEInterface = g_pSystem->m_aDPEInterface;
	m_sJido = aDPEInterface[5].aItem[24].aList[1];
	m_sHanJido = aDPEInterface[5].aItem[24].aList[2];
	m_sShudo = aDPEInterface[5].aItem[24].aList[3];
	m_sEngShiseiFlag = aDPEInterface[6].aItem[13].aList[1];
	m_sSeiritsuFr = aDPEInterface[6].aItem[12].aList[1];
	m_sSeiritsuRr = aDPEInterface[6].aItem[12].aList[2];
	m_sToritsuFr = aDPEInterface[6].aItem[12].aList[3];
	m_sToritsuRr = aDPEInterface[6].aItem[12].aList[4];
	m_sChokuritsuTop = aDPEInterface[6].aItem[12].aList[5];
	m_sChokuritsuBottom = aDPEInterface[6].aItem[12].aList[6];
	m_sJoryu = aDPEInterface[7].aItem[19].aList[1];
	m_sKaryu = aDPEInterface[7].aItem[20].aList[2];
	m_sKijun = aDPEInterface[5].aItem[33].aList[1];
	m_sKotei = aDPEInterface[5].aItem[33].aList[2];*/
// By Y.Itabashi (xxxxx) 2007.02.21
}


