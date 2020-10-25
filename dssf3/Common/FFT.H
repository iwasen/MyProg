#pragma once
/*
class CFFT
{
public:
	CFFT();
	~CFFT();

	void fft(int n, double x[], double y[]);
	void ifft(int n, double x[], double y[]);

private:
	void fft_sub(int n, double x[], double y[], int isgn);

	int last_n;		// 前回呼出し時のn
	double *a;
	double *w;
	int *ip;
};
*/
class CRFFT
{
public:
	CRFFT();
	~CRFFT();

	void fft(int n, double *fp);
	void ifft(int n, double *fp);

private:
	void fft_sub(int n, double *fp, int isgn);
	void alloc_work(int n);

	int last_n;		// 前回呼出し時のn
	double *a;
	double *w;
	int *ip;
};
