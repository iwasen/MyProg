#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		BaseDraw.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ描画共通基底クラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#define SCALE_X		0
#define SCALE_Y		1
#define SCALE_Z		2
#define SCALE_TEMP	3

class CBaseDraw
{
public:
	CRect m_rectView;
	CRect m_rectScale;

protected:
	double GetLinearScaleStep(double fRange, int nPixel, int *pStep = NULL);
	double GetLogScaleStep(double fValue);
	double GetScaleStartValue(double fMinValue, double fStep);
};
