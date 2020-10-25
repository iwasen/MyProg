//*****************************************************************************************************
//  1. ファイル名
//		Fft.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		FFTクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		FFT計算の本体は、http://www.kurims.kyoto-u.ac.jp/~ooura/fft-j.html よりダウンロードした
//		プログラムを利用している。
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "Fft.h"
#include "Globaldata.h"
#include <math.h>

#define PI 3.14159265358979323846

static void cdft(int n, int isgn, double *a, int *ip, double *w);
static void rdft(int n, int isgn, double *a, int *ip, double *w);
static void makewt(int	nw,	int	*ip, double	*w);
static void bitrv2(int	n, int *ip,	double *a);
static void bitrv2conj(int	n, int *ip,	double *a);
static void cftfsub(int n,	double *a, double *w);
static void cftbsub(int n,	double *a, double *w);
static void cft1st(int	n, double *a, double *w);
static void cftmdl(int	n, int l, double *a, double	*w);
static void makect(int nc, int *ip, double *c);
static void bitrv2(int n, int *ip, double *a);
static void rftfsub(int n, double *a, int nc, double *c);
static void rftbsub(int n, double *a, int nc, double *c);

//*****************************************************************************************************
//  1. 関数名
//		CFFT::CFFT
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CFFT::CFFT()
{
	// メンバ変数初期化
	last_n = 0;
	ip = NULL;
	w = NULL;
}

//*****************************************************************************************************
//  1. 関数名
//		CFFT::~CFFT
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		デストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CFFT::~CFFT()
{
	// ワークエリア解放
	FFT(0, NULL);
}

//*****************************************************************************************************
//  1. 関数名
//		CFFT::FFT
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		 FFT実行
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			n			[I] データ個数（２の累乗であること）
//		double		*fp			[I/O] データバッファへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFFT::FFT(int n, double *fp)
{
	fft_sub(n, fp, 1);
}

//*****************************************************************************************************
//  1. 関数名
//		CFFT::IFFT
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		 逆FFT実行
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			n			[I] データ個数（２の累乗であること）
//		double		*fp			[I/O] データバッファへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFFT::IFFT(int n, double *fp)
{
	fft_sub(n, fp, -1);
}

//*****************************************************************************************************
//  1. 関数名
//		CFFT::fft_sub
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		 FFT/逆FFT実行
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			n			[I] データ個数（２の累乗であること）
//		double		*fp			[I/O] データバッファへのポインタ
//		int		isgn			[I] 1：FFT　-1：逆FFT
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFFT::fft_sub(int n, double *fp, int isgn)
{
	alloc_work(n);

	if (n == 0)
		return;

	rdft(n, isgn, fp, ip, w);
}

//*****************************************************************************************************
//  1. 関数名
//		CFFT::alloc_work
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		 ワークエリア確保
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			n			[I] データ個数（２の累乗であること）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFFT::alloc_work(int n)
{
	if (last_n != n) {
		if (ip != NULL) {
			delete [] ip;
			ip = NULL;
		}

		if (w != NULL) {
			delete [] w;
			w = NULL;
		}

		if (n != 0) {
			ip = new int[(int)sqrt((double)n) + 3];
			ip[0] = 0;
			w = new double[n / 2];
		}

		last_n = n;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFFT::WindowFunc
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		 窓関数処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			type		[I] 窓関数の種類
//		int			size		[I] データサイズ
//		double		*data		[I] データバッファへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFFT::WindowFunc(int type, int size, double *data)
{
	int i;
	double m;
	double fTemp;

	switch (type) {
	case WF_RECTANGLAR:
		m = 1;
		break;
	case WF_TRIANGLAR:
		m = sqrt(3.0);
		break;
	case WF_HAMMING:
		m = 1 / sqrt(0.3974);
		break;
	case WF_HANNING:
		m = 1 / sqrt(0.375);
		break;
	case WF_BLACKMAN:
		m = 1 / sqrt(0.3046);
		break;
	case WF_BLACKMANHARRIS:
		m = 1 / sqrt(0.257963355);
		break;
	case WF_FLATTOP:
		m = 1 / sqrt(3.770284);
		break;
	}

	int size1 = size - 1;

	for (i = 0; i < size; i++) {
		switch (type) {
		case WF_RECTANGLAR:
			*data++ *= m;
			break;
		case WF_TRIANGLAR:
			*data++ *= (1.0 - (double)abs(size / 2 - i) / (size / 2)) * m;
			break;
		case WF_HAMMING:
			*data++ *= (0.54 - 0.46 * cos(2 * PI * i / (size1))) * m;
			break;
		case WF_HANNING:
			*data++ *= (0.5 - 0.5 * cos(2 * PI * i / (size1))) * m;
			break;
		case WF_BLACKMAN:
			fTemp = 2 * PI * i / size1;
			*data++ *= (0.42 - 0.5 * cos(fTemp) + 0.08 * cos(2 * fTemp)) * m;
			break;
		case WF_BLACKMANHARRIS:
			fTemp = 2 * PI * i / size1;
			*data++ *= (0.35875 - 0.48829 * cos(fTemp) + 0.14128 * cos(2 * fTemp) - 0.01168 * cos(3 * fTemp)) * m;
			break;
		case WF_FLATTOP:
			fTemp = 2 * PI * i / size1;
			*data++ *= (1 - 1.93 * cos(fTemp) + 1.29 * cos(2 * fTemp) - 0.388 * cos(3 * fTemp) + 0.032 * cos(4 * fTemp)) * m;
			break;
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFFT::SetWindowFuncList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		 窓関数のプルダウン設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CComboBox	&combo			[I] コンボボックス
//		int			nDefault		[I] 初期状態で選択されている窓関数
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFFT::SetWindowFuncList(CComboBox &combo, int nDefault)
{
	for (int i = 0; i < WF_NUM; i++)
		combo.AddString(g_tWindowFunc[i]);

	if (nDefault != -1)
		combo.SetCurSel(nDefault);
}

// 複素FFT
void cdft(int n, int isgn, double *a, int *ip, double *w)
{
	
	if (n >	(ip[0] << 2)) {
		makewt(n >>	2, ip, w);
	}
	if (n >	4) {
		if (isgn >=	0) {
			bitrv2(n, ip + 2, a);
			cftfsub(n, a, w);
		} else {
			bitrv2conj(n, ip + 2, a);
			cftbsub(n, a, w);
		}
	} else if (n ==	4) {
		cftfsub(n, a, w);
	}
}

// 実数FFT
void rdft(int n, int isgn, double *a, int *ip, double *w)
{
    int nw, nc;
    double xi;
    
    nw = ip[0];
    if (n > (nw << 2)) {
        nw = n >> 2;
        makewt(nw, ip, w);
    }
    nc = ip[1];
    if (n > (nc << 2)) {
        nc = n >> 2;
        makect(nc, ip, w + nw);
    }
    if (isgn >= 0) {
        if (n > 4) {
            bitrv2(n, ip + 2, a);
            cftfsub(n, a, w);
            rftfsub(n, a, nc, w + nw);
        } else if (n == 4) {
            cftfsub(n, a, w);
        }
        xi = a[0] - a[1];
        a[0] += a[1];
        a[1] = xi;
    } else {
        a[1] = 0.5 * (a[0] - a[1]);
        a[0] -= a[1];
        if (n > 4) {
            rftbsub(n, a, nc, w + nw);
            bitrv2(n, ip + 2, a);
            cftbsub(n, a, w);
        } else if (n == 4) {
            cftfsub(n, a, w);
        }
    }
}

/* --------	initializing routines -------- */


void makewt(int	nw,	int	*ip, double	*w)
{
	int	j, nwh;
	double delta, x, y;
	
	ip[0] =	nw;
	ip[1] =	1;
	if (nw > 2)	{
		nwh	= nw >>	1;
		delta =	atan(1.0) /	nwh;
		w[0] = 1;
		w[1] = 0;
		w[nwh] = cos(delta * nwh);
		w[nwh +	1] = w[nwh];
		if (nwh	> 2) {
			for	(j = 2;	j <	nwh; j += 2) {
				x =	cos(delta *	j);
				y =	sin(delta *	j);
				w[j] = x;
				w[j	+ 1] = y;
				w[nw - j] =	y;
				w[nw - j + 1] =	x;
			}
			bitrv2(nw, ip +	2, w);
		}
	}
}

void makect(int nc, int *ip, double *c)
{
    int j, nch;
    double delta;
    
    ip[1] = nc;
    if (nc > 1) {
        nch = nc >> 1;
        delta = atan(1.0) / nch;
        c[0] = cos(delta * nch);
        c[nch] = 0.5 * c[0];
        for (j = 1; j < nch; j++) {
            c[j] = 0.5 * cos(delta * j);
            c[nc - j] = 0.5 * sin(delta * j);
        }
    }
}


/* --------	child routines --------	*/


void bitrv2(int	n, int *ip,	double *a)
{
	int	j, j1, k, k1, l, m,	m2;
	double xr, xi, yr, yi;
	
	ip[0] =	0;
	l =	n;
	m =	1;
	while ((m << 3)	< l) {
		l >>= 1;
		for	(j = 0;	j <	m; j++)	{
			ip[m + j] =	ip[j] +	l;
		}
		m <<= 1;
	}
	m2 = 2 * m;
	if ((m << 3) ==	l) {
		for	(k = 0;	k <	m; k++)	{
			for	(j = 0;	j <	k; j++)	{
				j1 = 2 * j + ip[k];
				k1 = 2 * k + ip[j];
				xr = a[j1];
				xi = a[j1 +	1];
				yr = a[k1];
				yi = a[k1 +	1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
				j1 += m2;
				k1 += 2	* m2;
				xr = a[j1];
				xi = a[j1 +	1];
				yr = a[k1];
				yi = a[k1 +	1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
				j1 += m2;
				k1 -= m2;
				xr = a[j1];
				xi = a[j1 +	1];
				yr = a[k1];
				yi = a[k1 +	1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
				j1 += m2;
				k1 += 2	* m2;
				xr = a[j1];
				xi = a[j1 +	1];
				yr = a[k1];
				yi = a[k1 +	1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
			}
			j1 = 2 * k + m2	+ ip[k];
			k1 = j1	+ m2;
			xr = a[j1];
			xi = a[j1 +	1];
			yr = a[k1];
			yi = a[k1 +	1];
			a[j1] =	yr;
			a[j1 + 1] =	yi;
			a[k1] =	xr;
			a[k1 + 1] =	xi;
		}
	} else {
		for	(k = 1;	k <	m; k++)	{
			for	(j = 0;	j <	k; j++)	{
				j1 = 2 * j + ip[k];
				k1 = 2 * k + ip[j];
				xr = a[j1];
				xi = a[j1 +	1];
				yr = a[k1];
				yi = a[k1 +	1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
				j1 += m2;
				k1 += m2;
				xr = a[j1];
				xi = a[j1 +	1];
				yr = a[k1];
				yi = a[k1 +	1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
			}
		}
	}
}


void bitrv2conj(int	n, int *ip,	double *a)
{
	int	j, j1, k, k1, l, m,	m2;
	double xr, xi, yr, yi;
	
	ip[0] =	0;
	l =	n;
	m =	1;
	while ((m << 3)	< l) {
		l >>= 1;
		for	(j = 0;	j <	m; j++)	{
			ip[m + j] =	ip[j] +	l;
		}
		m <<= 1;
	}
	m2 = 2 * m;
	if ((m << 3) ==	l) {
		for	(k = 0;	k <	m; k++)	{
			for	(j = 0;	j <	k; j++)	{
				j1 = 2 * j + ip[k];
				k1 = 2 * k + ip[j];
				xr = a[j1];
				xi = -a[j1 + 1];
				yr = a[k1];
				yi = -a[k1 + 1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
				j1 += m2;
				k1 += 2	* m2;
				xr = a[j1];
				xi = -a[j1 + 1];
				yr = a[k1];
				yi = -a[k1 + 1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
				j1 += m2;
				k1 -= m2;
				xr = a[j1];
				xi = -a[j1 + 1];
				yr = a[k1];
				yi = -a[k1 + 1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
				j1 += m2;
				k1 += 2	* m2;
				xr = a[j1];
				xi = -a[j1 + 1];
				yr = a[k1];
				yi = -a[k1 + 1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
			}
			k1 = 2 * k + ip[k];
			a[k1 + 1] =	-a[k1 +	1];
			j1 = k1	+ m2;
			k1 = j1	+ m2;
			xr = a[j1];
			xi = -a[j1 + 1];
			yr = a[k1];
			yi = -a[k1 + 1];
			a[j1] =	yr;
			a[j1 + 1] =	yi;
			a[k1] =	xr;
			a[k1 + 1] =	xi;
			k1 += m2;
			a[k1 + 1] =	-a[k1 +	1];
		}
	} else {
		a[1] = -a[1];
		a[m2 + 1] =	-a[m2 +	1];
		for	(k = 1;	k <	m; k++)	{
			for	(j = 0;	j <	k; j++)	{
				j1 = 2 * j + ip[k];
				k1 = 2 * k + ip[j];
				xr = a[j1];
				xi = -a[j1 + 1];
				yr = a[k1];
				yi = -a[k1 + 1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
				j1 += m2;
				k1 += m2;
				xr = a[j1];
				xi = -a[j1 + 1];
				yr = a[k1];
				yi = -a[k1 + 1];
				a[j1] =	yr;
				a[j1 + 1] =	yi;
				a[k1] =	xr;
				a[k1 + 1] =	xi;
			}
			k1 = 2 * k + ip[k];
			a[k1 + 1] =	-a[k1 +	1];
			a[k1 + m2 +	1] = -a[k1 + m2	+ 1];
		}
	}
}


void cftfsub(int n,	double *a, double *w)
{
	int	j, j1, j2, j3, l;
	double x0r,	x0i, x1r, x1i, x2r,	x2i, x3r, x3i;
	
	l =	2;
	if (n >	8) {
		cft1st(n, a, w);
		l =	8;
		while ((l << 2)	< n) {
			cftmdl(n, l, a,	w);
			l <<= 2;
		}
	}
	if ((l << 2) ==	n) {
		for	(j = 0;	j <	l; j +=	2) {
			j1 = j + l;
			j2 = j1	+ l;
			j3 = j2	+ l;
			x0r	= a[j] + a[j1];
			x0i	= a[j +	1] + a[j1 +	1];
			x1r	= a[j] - a[j1];
			x1i	= a[j +	1] - a[j1 +	1];
			x2r	= a[j2]	+ a[j3];
			x2i	= a[j2 + 1]	+ a[j3 + 1];
			x3r	= a[j2]	- a[j3];
			x3i	= a[j2 + 1]	- a[j3 + 1];
			a[j] = x0r + x2r;
			a[j	+ 1] = x0i + x2i;
			a[j2] =	x0r	- x2r;
			a[j2 + 1] =	x0i	- x2i;
			a[j1] =	x1r	- x3i;
			a[j1 + 1] =	x1i	+ x3r;
			a[j3] =	x1r	+ x3i;
			a[j3 + 1] =	x1i	- x3r;
		}
	} else {
		for	(j = 0;	j <	l; j +=	2) {
			j1 = j + l;
			x0r	= a[j] - a[j1];
			x0i	= a[j +	1] - a[j1 +	1];
			a[j] +=	a[j1];
			a[j	+ 1] +=	a[j1 + 1];
			a[j1] =	x0r;
			a[j1 + 1] =	x0i;
		}
	}
}


void cftbsub(int n,	double *a, double *w)
{
	void cft1st(int	n, double *a, double *w);
	void cftmdl(int	n, int l, double *a, double	*w);
	int	j, j1, j2, j3, l;
	double x0r,	x0i, x1r, x1i, x2r,	x2i, x3r, x3i;
	
	l =	2;
	if (n >	8) {
		cft1st(n, a, w);
		l =	8;
		while ((l << 2)	< n) {
			cftmdl(n, l, a,	w);
			l <<= 2;
		}
	}
	if ((l << 2) ==	n) {
		for	(j = 0;	j <	l; j +=	2) {
			j1 = j + l;
			j2 = j1	+ l;
			j3 = j2	+ l;
			x0r	= a[j] + a[j1];
			x0i	= -a[j + 1]	- a[j1 + 1];
			x1r	= a[j] - a[j1];
			x1i	= -a[j + 1]	+ a[j1 + 1];
			x2r	= a[j2]	+ a[j3];
			x2i	= a[j2 + 1]	+ a[j3 + 1];
			x3r	= a[j2]	- a[j3];
			x3i	= a[j2 + 1]	- a[j3 + 1];
			a[j] = x0r + x2r;
			a[j	+ 1] = x0i - x2i;
			a[j2] =	x0r	- x2r;
			a[j2 + 1] =	x0i	+ x2i;
			a[j1] =	x1r	- x3i;
			a[j1 + 1] =	x1i	- x3r;
			a[j3] =	x1r	+ x3i;
			a[j3 + 1] =	x1i	+ x3r;
		}
	} else {
		for	(j = 0;	j <	l; j +=	2) {
			j1 = j + l;
			x0r	= a[j] - a[j1];
			x0i	= -a[j + 1]	+ a[j1 + 1];
			a[j] +=	a[j1];
			a[j	+ 1] = -a[j	+ 1] - a[j1	+ 1];
			a[j1] =	x0r;
			a[j1 + 1] =	x0i;
		}
	}
}


void cft1st(int	n, double *a, double *w)
{
	int	j, k1, k2;
	double wk1r, wk1i, wk2r, wk2i, wk3r, wk3i;
	double x0r,	x0i, x1r, x1i, x2r,	x2i, x3r, x3i;
	
	x0r	= a[0] + a[2];
	x0i	= a[1] + a[3];
	x1r	= a[0] - a[2];
	x1i	= a[1] - a[3];
	x2r	= a[4] + a[6];
	x2i	= a[5] + a[7];
	x3r	= a[4] - a[6];
	x3i	= a[5] - a[7];
	a[0] = x0r + x2r;
	a[1] = x0i + x2i;
	a[4] = x0r - x2r;
	a[5] = x0i - x2i;
	a[2] = x1r - x3i;
	a[3] = x1i + x3r;
	a[6] = x1r + x3i;
	a[7] = x1i - x3r;
	wk1r = w[2];
	x0r	= a[8] + a[10];
	x0i	= a[9] + a[11];
	x1r	= a[8] - a[10];
	x1i	= a[9] - a[11];
	x2r	= a[12]	+ a[14];
	x2i	= a[13]	+ a[15];
	x3r	= a[12]	- a[14];
	x3i	= a[13]	- a[15];
	a[8] = x0r + x2r;
	a[9] = x0i + x2i;
	a[12] =	x2i	- x0i;
	a[13] =	x0r	- x2r;
	x0r	= x1r -	x3i;
	x0i	= x1i +	x3r;
	a[10] =	wk1r * (x0r	- x0i);
	a[11] =	wk1r * (x0r	+ x0i);
	x0r	= x3i +	x1r;
	x0i	= x3r -	x1i;
	a[14] =	wk1r * (x0i	- x0r);
	a[15] =	wk1r * (x0i	+ x0r);
	k1 = 0;
	for	(j = 16; j < n;	j += 16) {
		k1 += 2;
		k2 = 2 * k1;
		wk2r = w[k1];
		wk2i = w[k1	+ 1];
		wk1r = w[k2];
		wk1i = w[k2	+ 1];
		wk3r = wk1r	- 2	* wk2i * wk1i;
		wk3i = 2 * wk2i	* wk1r - wk1i;
		x0r	= a[j] + a[j + 2];
		x0i	= a[j +	1] + a[j + 3];
		x1r	= a[j] - a[j + 2];
		x1i	= a[j +	1] - a[j + 3];
		x2r	= a[j +	4] + a[j + 6];
		x2i	= a[j +	5] + a[j + 7];
		x3r	= a[j +	4] - a[j + 6];
		x3i	= a[j +	5] - a[j + 7];
		a[j] = x0r + x2r;
		a[j	+ 1] = x0i + x2i;
		x0r	-= x2r;
		x0i	-= x2i;
		a[j	+ 4] = wk2r	* x0r -	wk2i * x0i;
		a[j	+ 5] = wk2r	* x0i +	wk2i * x0r;
		x0r	= x1r -	x3i;
		x0i	= x1i +	x3r;
		a[j	+ 2] = wk1r	* x0r -	wk1i * x0i;
		a[j	+ 3] = wk1r	* x0i +	wk1i * x0r;
		x0r	= x1r +	x3i;
		x0i	= x1i -	x3r;
		a[j	+ 6] = wk3r	* x0r -	wk3i * x0i;
		a[j	+ 7] = wk3r	* x0i +	wk3i * x0r;
		wk1r = w[k2	+ 2];
		wk1i = w[k2	+ 3];
		wk3r = wk1r	- 2	* wk2r * wk1i;
		wk3i = 2 * wk2r	* wk1r - wk1i;
		x0r	= a[j +	8] + a[j + 10];
		x0i	= a[j +	9] + a[j + 11];
		x1r	= a[j +	8] - a[j + 10];
		x1i	= a[j +	9] - a[j + 11];
		x2r	= a[j +	12]	+ a[j +	14];
		x2i	= a[j +	13]	+ a[j +	15];
		x3r	= a[j +	12]	- a[j +	14];
		x3i	= a[j +	13]	- a[j +	15];
		a[j	+ 8] = x0r + x2r;
		a[j	+ 9] = x0i + x2i;
		x0r	-= x2r;
		x0i	-= x2i;
		a[j	+ 12] =	-wk2i *	x0r	- wk2r * x0i;
		a[j	+ 13] =	-wk2i *	x0i	+ wk2r * x0r;
		x0r	= x1r -	x3i;
		x0i	= x1i +	x3r;
		a[j	+ 10] =	wk1r * x0r - wk1i *	x0i;
		a[j	+ 11] =	wk1r * x0i + wk1i *	x0r;
		x0r	= x1r +	x3i;
		x0i	= x1i -	x3r;
		a[j	+ 14] =	wk3r * x0r - wk3i *	x0i;
		a[j	+ 15] =	wk3r * x0i + wk3i *	x0r;
	}
}


void cftmdl(int	n, int l, double *a, double	*w)
{
	int	j, j1, j2, j3, k, k1, k2, m, m2;
	double wk1r, wk1i, wk2r, wk2i, wk3r, wk3i;
	double x0r,	x0i, x1r, x1i, x2r,	x2i, x3r, x3i;
	
	m =	l << 2;
	for	(j = 0;	j <	l; j +=	2) {
		j1 = j + l;
		j2 = j1	+ l;
		j3 = j2	+ l;
		x0r	= a[j] + a[j1];
		x0i	= a[j +	1] + a[j1 +	1];
		x1r	= a[j] - a[j1];
		x1i	= a[j +	1] - a[j1 +	1];
		x2r	= a[j2]	+ a[j3];
		x2i	= a[j2 + 1]	+ a[j3 + 1];
		x3r	= a[j2]	- a[j3];
		x3i	= a[j2 + 1]	- a[j3 + 1];
		a[j] = x0r + x2r;
		a[j	+ 1] = x0i + x2i;
		a[j2] =	x0r	- x2r;
		a[j2 + 1] =	x0i	- x2i;
		a[j1] =	x1r	- x3i;
		a[j1 + 1] =	x1i	+ x3r;
		a[j3] =	x1r	+ x3i;
		a[j3 + 1] =	x1i	- x3r;
	}
	wk1r = w[2];
	for	(j = m;	j <	l +	m; j +=	2) {
		j1 = j + l;
		j2 = j1	+ l;
		j3 = j2	+ l;
		x0r	= a[j] + a[j1];
		x0i	= a[j +	1] + a[j1 +	1];
		x1r	= a[j] - a[j1];
		x1i	= a[j +	1] - a[j1 +	1];
		x2r	= a[j2]	+ a[j3];
		x2i	= a[j2 + 1]	+ a[j3 + 1];
		x3r	= a[j2]	- a[j3];
		x3i	= a[j2 + 1]	- a[j3 + 1];
		a[j] = x0r + x2r;
		a[j	+ 1] = x0i + x2i;
		a[j2] =	x2i	- x0i;
		a[j2 + 1] =	x0r	- x2r;
		x0r	= x1r -	x3i;
		x0i	= x1i +	x3r;
		a[j1] =	wk1r * (x0r	- x0i);
		a[j1 + 1] =	wk1r * (x0r	+ x0i);
		x0r	= x3i +	x1r;
		x0i	= x3r -	x1i;
		a[j3] =	wk1r * (x0i	- x0r);
		a[j3 + 1] =	wk1r * (x0i	+ x0r);
	}
	k1 = 0;
	m2 = 2 * m;
	for	(k = m2; k < n;	k += m2) {
		k1 += 2;
		k2 = 2 * k1;
		wk2r = w[k1];
		wk2i = w[k1	+ 1];
		wk1r = w[k2];
		wk1i = w[k2	+ 1];
		wk3r = wk1r	- 2	* wk2i * wk1i;
		wk3i = 2 * wk2i	* wk1r - wk1i;
		for	(j = k;	j <	l +	k; j +=	2) {
			j1 = j + l;
			j2 = j1	+ l;
			j3 = j2	+ l;
			x0r	= a[j] + a[j1];
			x0i	= a[j +	1] + a[j1 +	1];
			x1r	= a[j] - a[j1];
			x1i	= a[j +	1] - a[j1 +	1];
			x2r	= a[j2]	+ a[j3];
			x2i	= a[j2 + 1]	+ a[j3 + 1];
			x3r	= a[j2]	- a[j3];
			x3i	= a[j2 + 1]	- a[j3 + 1];
			a[j] = x0r + x2r;
			a[j	+ 1] = x0i + x2i;
			x0r	-= x2r;
			x0i	-= x2i;
			a[j2] =	wk2r * x0r - wk2i *	x0i;
			a[j2 + 1] =	wk2r * x0i + wk2i *	x0r;
			x0r	= x1r -	x3i;
			x0i	= x1i +	x3r;
			a[j1] =	wk1r * x0r - wk1i *	x0i;
			a[j1 + 1] =	wk1r * x0i + wk1i *	x0r;
			x0r	= x1r +	x3i;
			x0i	= x1i -	x3r;
			a[j3] =	wk3r * x0r - wk3i *	x0i;
			a[j3 + 1] =	wk3r * x0i + wk3i *	x0r;
		}
		wk1r = w[k2	+ 2];
		wk1i = w[k2	+ 3];
		wk3r = wk1r	- 2	* wk2r * wk1i;
		wk3i = 2 * wk2r	* wk1r - wk1i;
		for	(j = k + m;	j <	l +	(k + m); j += 2) {
			j1 = j + l;
			j2 = j1	+ l;
			j3 = j2	+ l;
			x0r	= a[j] + a[j1];
			x0i	= a[j +	1] + a[j1 +	1];
			x1r	= a[j] - a[j1];
			x1i	= a[j +	1] - a[j1 +	1];
			x2r	= a[j2]	+ a[j3];
			x2i	= a[j2 + 1]	+ a[j3 + 1];
			x3r	= a[j2]	- a[j3];
			x3i	= a[j2 + 1]	- a[j3 + 1];
			a[j] = x0r + x2r;
			a[j	+ 1] = x0i + x2i;
			x0r	-= x2r;
			x0i	-= x2i;
			a[j2] =	-wk2i *	x0r	- wk2r * x0i;
			a[j2 + 1] =	-wk2i *	x0i	+ wk2r * x0r;
			x0r	= x1r -	x3i;
			x0i	= x1i +	x3r;
			a[j1] =	wk1r * x0r - wk1i *	x0i;
			a[j1 + 1] =	wk1r * x0i + wk1i *	x0r;
			x0r	= x1r +	x3i;
			x0i	= x1i -	x3r;
			a[j3] =	wk3r * x0r - wk3i *	x0i;
			a[j3 + 1] =	wk3r * x0i + wk3i *	x0r;
		}
	}
}

void rftfsub(int n, double *a, int nc, double *c)
{
    int j, k, kk, ks, m;
    double wkr, wki, xr, xi, yr, yi;
    
    m = n >> 1;
    ks = 2 * nc / m;
    kk = 0;
    for (j = 2; j < m; j += 2) {
        k = n - j;
        kk += ks;
        wkr = 0.5 - c[nc - kk];
        wki = c[kk];
        xr = a[j] - a[k];
        xi = a[j + 1] + a[k + 1];
        yr = wkr * xr - wki * xi;
        yi = wkr * xi + wki * xr;
        a[j] -= yr;
        a[j + 1] -= yi;
        a[k] += yr;
        a[k + 1] -= yi;
    }
}

void rftbsub(int n, double *a, int nc, double *c)
{
    int j, k, kk, ks, m;
    double wkr, wki, xr, xi, yr, yi;
    
    a[1] = -a[1];
    m = n >> 1;
    ks = 2 * nc / m;
    kk = 0;
    for (j = 2; j < m; j += 2) {
        k = n - j;
        kk += ks;
        wkr = 0.5 - c[nc - kk];
        wki = c[kk];
        xr = a[j] - a[k];
        xi = a[j + 1] + a[k + 1];
        yr = wkr * xr + wki * xi;
        yi = wkr * xi - wki * xr;
        a[j] -= yr;
        a[j + 1] = yi - a[j + 1];
        a[k] += yr;
        a[k + 1] = yi - a[k + 1];
    }
    a[m + 1] = -a[m + 1];
}
