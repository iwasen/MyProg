/************************************************************************
 *									*
 *		�`�b�d�r�@�r�^�f�@�b�o�t�v���O����			*
 *									*
 *		����		: �p���[�I������			*
 *		�t�@�C����	: init.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=init

#include "sg_cpu.h"

/*	�����֐�	*/
static	void	check_pid(void);
static	void	set_VIC068(void);

/*=======================================================================
 |
 |		�p���[�I������
 |
 |	void	power_on()
 |
 =======================================================================*/
void	power_on(void)
{
	register word	*p;

	check_pid();		/* �v���Z�b�T�h�c�`�F�b�N */

//	*REG_WDT = 0x9d;	/* �k�d�c�_�� */

	/* �r�q�`�l�[���N���A */
//	for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
//		*p = 0;

	/* �f�o�b�O���[�h�`�F�b�N */
	if (debug_flag & DBF_DEBUGMODE)
		debug_mode = DBM_VT;

	set_VIC068();		/* �u�h�b�O�U�W�R���g���[�����W�X�^�ݒ� */
	set_DUART();		/* �c�t�`�q�s�R���g���[�����W�X�^�ݒ� */

	sg_mode = SGM_PBIT;	/* POWER ON BIT �J�n */

	rom_check();		/* �d�d�o�q�n�l�T���`�F�b�N */
	ram_check();		/* �r�q�`�l�`�F�b�N */
	cpu_check();		/* �b�o�t�`�F�b�N */

	/* �q�`�o�[���N���A */
	for (p = GSC_RAP; p < GSC_RAP_END; p++)
		*p = 0;

	if (cpu == CPU_1)
		mbibit();	/* �l�a�h�{�[�h�a�h�s�`�F�b�N�J�n */

	/* ���荞�݃}�X�N���� */
	mskclr();
	int_enable();

	/* �f�r�b���W�X�^�Z�b�g */
//	*REG_GSC_OUTPUT = 0x0001;
//	*REG_GSC_OPRATE = 0x2000;

	sg_main();		/* ���C������ */
}

/*=======================================================================
 |
 |		�v���Z�b�T�h�c�`�F�b�N
 |
 |	void	check_pid()
 |
 =======================================================================*/
static	void	check_pid(void)
{
/*@@@@@@@@@@@@*/
extern	word	test_gsc[];
cpu = CPU_1;
adr_gsc = (char *)test_gsc;
return;
/*@@@@@@@@@@@@*/
	switch (*REG_STATUS & 0x0f) {
	case 0x0f:
		cpu = CPU_1;			/* �b�o�t�ԍ��Z�b�g */
		adr_gsc = (char *)VME_GSC1;	/* �f�r�b�A�h���X�Z�b�g */
		break;
	case 0x0e:
		cpu = CPU_2;			/* �b�o�t�ԍ��Z�b�g */
		adr_gsc = (char *)VME_GSC2;	/* �f�r�b�A�h���X�Z�b�g */
		break;
	case 0x0d:
		cpu = CPU_3;			/* �b�o�t�ԍ��Z�b�g */
		adr_gsc = (char *)VME_GSC3;	/* �f�r�b�A�h���X�Z�b�g */
		break;
	default:
		halt_cpu("ILLEGAL PROCESSOR ID", 100);
		break;
	}
}

/*=======================================================================
 |
 |		�u�h�b�O�U�W�R���g���[�����W�X�^�ݒ�
 |
 |	void	set_VIC068()
 |
 =======================================================================*/
static	void	set_VIC068(void)
{
	/*	�u�h�b�O�U�W�R���g���[�����W�X�^�ݒ�l (MASTER CPU) */
	static	BYTE_REG_DATA	CPU1_VIC068[] = {
	        {REG_VIC_EGIVBR, 0x50, 100},
	        {REG_VIC_ICGSIVBR, 0x58, 100},
	        {REG_VIC_ICMSIVBR, 0x5C, 100},
	        {REG_VIC_LIVBR,  0x40, 100},
	        {REG_VIC_LICR1,  0xb2, 100},
	        {REG_VIC_LICR2,  0xb1, 100},
	        {REG_VIC_VIVR1,  0x48, 100},
	        {REG_VIC_VIVR2,  0x49, 100},
	        {REG_VIC_VIVR3,  0x4a, 100},
	        {REG_VIC_VIVR4,  0x4b, 100},
	        {REG_VIC_VIVR5,  0x4c, 100},
	        {REG_VIC_VIVR6,  0x4d, 100},
	        {REG_VIC_VIVR7,  0x4e, 100},
	        {REG_VIC_SS0CR0, 0xc0, 100},
	        {REG_VIC_SS1CR0, 0x00, 100},
	        {REG_VIC_LBTR,   0x00, 100},
	        {REG_VIC_TTR,    0x6a, 100},
	        {REG_VIC_RCR,    0x00, 100},
	        {REG_VIC_BESR,   0x80, 100},
	        {REG_VIC_ARCR,   0x60, 100}
	};

	/*	�u�h�b�O�U�W�R���g���[�����W�X�^�ݒ�l (SLAVE CPU) */
	static	BYTE_REG_DATA	CPU23_VIC068[] = {
	        {REG_VIC_EGIVBR, 0x50, 100},
	        {REG_VIC_ICGSIVBR, 0x58, 100},
	        {REG_VIC_ICMSIVBR, 0x5C, 100},
	        {REG_VIC_LIVBR,  0x40, 100},
	        {REG_VIC_LICR1,  0xb2, 100},
	        {REG_VIC_LICR2,  0xb1, 100},
	        {REG_VIC_VIVR1,  0x48, 100},
	        {REG_VIC_VIVR2,  0x49, 100},
	        {REG_VIC_VIVR3,  0x4a, 100},
	        {REG_VIC_VIVR4,  0x4b, 100},
	        {REG_VIC_VIVR5,  0x4c, 100},
	        {REG_VIC_VIVR6,  0x4d, 100},
	        {REG_VIC_VIVR7,  0x4e, 100},
	        {REG_VIC_SS0CR0, 0xc0, 100},
	        {REG_VIC_SS1CR0, 0x00, 100},
	        {REG_VIC_LBTR,   0x00, 100},
	        {REG_VIC_TTR,    0x6a, 100},
	        {REG_VIC_RCR,    0x00, 100},
	        {REG_VIC_BESR,   0x00, 100},
	        {REG_VIC_ARCR,   0x60, 100}
	};

	switch (cpu) {
	case CPU_1:
		write_byte_register(CPU1_VIC068,
			sizeof(CPU1_VIC068) / sizeof(BYTE_REG_DATA));
		break;
	default:
		write_byte_register(CPU23_VIC068,
			sizeof(CPU23_VIC068) / sizeof(BYTE_REG_DATA));
		break;
	}
}

/*=======================================================================
 |
 |		�c�t�`�q�s�R���g���[�����W�X�^�̐ݒ�
 |
 |	void	set_DUART()
 |
 =======================================================================*/
void	set_DUART(void)
{
	/*	�c�t�`�q�s�R���g���[�����W�X�^�ݒ�l */
	static	BYTE_REG_DATA	DUART[] = {
		{REG_DUART_CRA,  0x0a, 100},
		{REG_DUART_CRA,  0x10, 100},
		{REG_DUART_CRA,  0x20, 100},
		{REG_DUART_CRA,  0x30, 100},
		{REG_DUART_CRA,  0x40, 100},
		{REG_DUART_CRA,  0x50, 100},
		{REG_DUART_MR1A, 0x07, 100},
		{REG_DUART_MR2A, 0x0f, 100},
		{REG_DUART_CSRA, 0xbb, 100},
		{REG_DUART_CRA,  0x05, 100},
		{REG_DUART_CRB,  0x0a, 100},
		{REG_DUART_CRB,  0x10, 100},
		{REG_DUART_CRB,  0x20, 100},
		{REG_DUART_CRB,  0x30, 100},
		{REG_DUART_CRB,  0x40, 100},
		{REG_DUART_CRB,  0x50, 100},
		{REG_DUART_MR1B, 0x17, 100},
		{REG_DUART_MR2B, 0x07, 100},
		{REG_DUART_CSRB, 0xbb, 100}
	};

	/* DUART ������ */
	write_byte_register(DUART,
			sizeof(DUART) / sizeof(BYTE_REG_DATA));

	/* �f�o�b�O���[�h�`�F�b�N */
	if (debug_mode == DBM_VT) {
		/* �b�g�|�a�i�u�s�j��M�� */
//		*REG_DUART_CRB = 0x05;
		rs_IMR = 0x22;
//		*REG_DUART_IMR = rs_IMR;
	} else {
		/* �b�g�|�a�i�u�s�j��M�s�� */
//		*REG_DUART_CRB = 0x02;
		rs_IMR = 0x02;
//		*REG_DUART_IMR = rs_IMR;
	}
}
