/************************************************************************
 *									*
 *		�������g�\�����u�@�r�^�f�@�b�o�t�v���O����		*
 *									*
 *		����		: �q�r�|�Q�R�Q�b����M���荞�ݏ���	*
 *		�t�@�C����	: intrs2.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intrs2

#include "sg_cpu.h"

/*	�����֐�	*/
static	void	receive_CHA(void);
static	void	send_CHA(void);
static	void	receive_CHB(void);
static	void	send_CHB(void);

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b���荞�ݏ���
 |
 |	void	intrs2()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intrs2(void)
{
	register byte	isr;

	for (;;) {
		/* ���荞�݃X�e�[�^�X���W�X�^�ǂݍ��� */
		isr = *REG_DUART_ISR;

		if (isr & rs_IMR & BIT1)	/* �b�g�|�`��M���荞�݁H */
			receive_CHA();
		else if (isr & rs_IMR & BIT0)	/* �b�g�|�`���M���荞�݁H */
			send_CHA();
		else if (isr & rs_IMR & BIT5)	/* �b�g�|�a��M���荞�݁H */
			receive_CHB();
		else if (isr & rs_IMR & BIT4)	/* �b�g�|�a���M���荞�݁H */
			send_CHB();
		else
			break;
	}
}
#pragma	INTERRUPT-

/*=======================================================================
 |
 |		�b�g�|�`��M���荞�ݏ���
 |
 |	void	receive_CHA()
 |
 =======================================================================*/
static	void	receive_CHA(void)
{
	register byte	sra, ch;

	while ((sra = *REG_DUART_SRA) & BIT0) {
		if (sra & (BIT4 | BIT5 | BIT6)) {	/* ��M�G���[�L��H */
			/* �o�b�t�@���f�[�^�ǂݎ̂� */
			ch = *REG_DUART_RHRA;

			/* ��M�J�E���^�N���A */
			rsa_rcvcounter = 0;
			continue;
		}
		/* ��M�f�[�^�ǂݍ��� */
		ch = *REG_DUART_RHRA;

		switch (rsa_rcvcounter) {
		case 0:		/* ��P�o�C�g */
			switch (ch) {
			case '#':	/* �a�h�s���� */
			case '*':	/* �X�V���x�\�� */
				rsa_rcvchar = ch;

				/* �����ԃ^�C�}�[�Z�b�g */
				tmc_rschar = 0;
				tmf_rschar = TIMER_START;

				/* ��M�J�E���^�Z�b�g */
				rsa_rcvcounter = 1;
				break;
			}
			break;
		case 1:		/* ��Q�o�C�g */
			switch (rsa_rcvchar) {
			case '#':	/* �a�h�s���� */
				if (bit_status == BIT_START && ch == 0x00) {
					bit_status = BIT_NORMAL;

					/* �a�h�s���ʎ�M�Ď��^�C�}�[��~ */
					tmf_dubit = TIMER_STOP;
				}
				break;
			case '*':	/* �X�V���x�\�� */
				rate_dsp_flag = ch & 0x01;
				break;
			}

			/* �����ԃ^�C�}�[��~ */
			tmf_rschar = TIMER_STOP;

			/* ��M�J�E���^�N���A */
			rsa_rcvcounter = 0;
			break;
		}
	}
}

/*=======================================================================
 |
 |		�b�g�|�`���M���荞�ݏ���
 |
 |	void	send_CHA()
 |
 =======================================================================*/
static	void	send_CHA(void)
{
	if (rsa_sndwptr == rsa_sndrptr) {
		rs_IMR &= ~BIT0;
		*REG_DUART_IMR = rs_IMR;
	} else {
		*REG_DUART_THRA = rsa_sndbuf[rsa_sndrptr++];
		if (rsa_sndrptr == RSA_SBUFSIZE)
			rsa_sndrptr = 0;
	}
}

/*=======================================================================
 |
 |		�b�g�|�a��M���荞�ݏ���
 |
 |	void	receive_CHB()
 |
 =======================================================================*/
static	void	receive_CHB(void)
{
	register byte	srb, ch;
	char	echo[1];

	while ((srb = *REG_DUART_SRB) & BIT0) {
		if (srb & (BIT4 | BIT5 | BIT6)) {	/* ��M�G���[�L��H */
			/* �o�b�t�@���f�[�^�ǂݎ̂� */
			ch = *REG_DUART_RHRB;
			continue;
		}
		/* ��M�f�[�^�ǂݍ��� */
		ch = *REG_DUART_RHRB;
		echo[0] = ch;
		rsb_send(echo, 1);
		if (ch == 0x0d) {
			rsb_rcvbuf[rsb_rcvcounter] = '\0';
			if (debug_flag)
				debug_command();
			else {
				if (rsb_rcvbuf[0] == 'Y' &&
							rsb_rcvbuf[1] == 0) {
					debug_flag = 1;
					rsb_send("\x0d\x0a>", 3);
				}
			}
			rsb_rcvcounter = 0;
		} else if (ch == 0x08) {
			if (rsb_rcvcounter != 0)
				rsb_rcvcounter--;
		} else if (rsb_rcvcounter < RSB_RBUFSIZE - 1) {
			if (ch >= 0x20)
				rsb_rcvbuf[rsb_rcvcounter++] = ch;
		}
	}
}

/*=======================================================================
 |
 |		�b�g�|�a���M���荞�ݏ���
 |
 |	void	send_CHB()
 |
 =======================================================================*/
static	void	send_CHB(void)
{
	if (rsb_sndwptr == rsb_sndrptr) {
		rs_IMR &= ~BIT4;
		*REG_DUART_IMR = rs_IMR;
	} else {
		*REG_DUART_THRB = rsb_sndbuf[rsb_sndrptr++];
		if (rsb_sndrptr == RSB_SBUFSIZE)
			rsb_sndrptr = 0;
	}
}