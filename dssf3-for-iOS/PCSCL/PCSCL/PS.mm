#import "PCSCL.h"

CPcserve::CPcserve()
{
	m_pCCB = NULL;
}

CPcserve::~CPcserve()
{
	Disconxxxt();
}

PCCB CPcserve::GetCCB()
{
	return m_pCCB;
}

int CPcserve::Conxxxt(int nChType, LPCTSTR lpServerName, LPCTSTR lpClientID)
{
	short err;

	m_pCCB = PSConxxxt(nChType, (LPSTR)lpServerName, (LPSTR)lpClientID, &err);
	return err;
}

int CPcserve::Disconxxxt()
{
	if (m_pCCB != NULL) {
		PSDisconxxxt(m_pCCB);
		m_pCCB = NULL;
	}

	return 0;
}

int CPcserve::PackIndex(LPCTSTR lpFileName)
{
	if (m_pCCB == NULL)
		return ERROR_CLIENT_NOTCONxxxT;

	PSDBPackIndex(m_pCCB, (LPSTR)lpFileName);
	return PSGetErrCode(m_pCCB);
}
/*
LPCTSTR CPcserve::GetErrorText(int err)
{
	return PSGetErrorText(err);
}
*/
