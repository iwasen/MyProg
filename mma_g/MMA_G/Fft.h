#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		Fft.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		FFTクラス定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

// 窓関数
#define WF_RECTANGLAR		0
#define WF_TRIANGLAR		1
#define WF_HAMMING			2
#define WF_HANNING			3
#define WF_BLACKMAN			4
#define WF_BLACKMANHARRIS	5
#define WF_FLATTOP			6
#define WF_NUM				7

class CFFT
{
public:
	CFFT();
	~CFFT();

	void FFT(int n, double *fp);
	void IFFT(int n, double *fp);
	void WindowFunc(int type, int size, double *data);
	void SetWindowFuncList(CComboBox &combo, int nDefault);

private:
	void fft_sub(int n, double *fp, int isgn);
	void alloc_work(int n);

	int last_n;		// 前回呼出し時のn
	double *w;
	int *ip;
};
