/***********	dp_calc.c	***********/
	{1, 10},		/* 356 �f�W�^���l�̍X�V���[�g */
	{-422, 423},		/* 357 �~�T�C���V���{���E�I�t�Z�b�g��(�w���W)*/
	{-422, 423}		/* 358 �~�T�C���V���{���E�I�t�Z�b�g��(�x���W)*/

int	data_check(int dno)
{
	int	error, n;
	short	high, low;

	if (dno == 27) {
		/* �E�H�[�j���O�P�C�Q */
		high = (input_data[27] >> 8) & 0x00ff;
		low = input_data[27] & 0x00ff;
		error = (CHECK(high, 0, 1) || CHECK(low, 0, 1));
	} else if (dno == 28) {
		/* �E�H�[�j���O�R�C�S */
		high = (input_data[28] >> 8) & 0x00ff;
		low = input_data[28] & 0x00ff;
		error = (CHECK(high, 0, 1) || CHECK(low, 0, 1));
	} else if (dno == 29) {
		/* �~�T�C���V���{���̎�ށC�~�T�C���T�m�X�e�[�^�X */
		high = (input_data[29] >> 8) & 0x00ff;
		low = input_data[29] & 0x00ff;
		error = (CHECK(high, 0, 3) || CHECK(low, 0, 1));
	}else if (dno <= 45) {
		/* �Q�V�C�Q�W�C�Q�X�������ԍ��O�`�S�T�܂ł̃f�[�^ */
		error = (CHECK(input_data[dno], data_check_tbl1[dno][0],
						data_check_tbl1[dno][1]));
	} else if (dno <= 345) {
		/* ��Q���f�[�^�i�ԍ��S�U�`�R�S�T�j */
		n = (dno - 46) % 3;
		error = (CHECK(input_data[dno], data_check_tbl2[n][0],
						data_check_tbl2[n][1]));
*	} else if (dno <= 358) {
*		/* �ԍ��R�S�U�`�R�T�W�܂ł̃f�[�^ */
		n = dno - 346;
		error = (CHECK(input_data[dno], data_check_tbl3[n][0],
						data_check_tbl3[n][1]));
	} else
		error = 0;

	if (error)
		data_error_flag = 1;	/* �f�[�^�G���[�t���O�Z�b�g */

	return(error);
}
/***********	inpdata.c	***********/
static	void	dp_missile_symbol(void)
{
	long	x, y;

	/* �f�[�^�`�F�b�N */
	if (data_check(7) || data_check(357) || data_check(358))
		return;

	/* �Z���T�e�n�u���O�̎��͕\�����Ȃ� */
	if (input_data[7] == 0)
		return;

	/* �~�T�C���̂w�C�x���W�v�Z */
	x = (long)dot_video * input_data[30] / input_data[7] + input_data[357];
	y = (long)dot_video * input_data[31] / input_data[7] - input_data[358];

	/* ��ʓ��ɂȂ��ꍇ�\�����Ȃ� */
	if (x > 423 || x < -423 || y > 423 || y < -423)
		return;

	/* �~�T�C���ʒu�i�o�w�O�T�C�o�x�O�T�j�̐ݒ� */
	dp.PX05 = DOT_TO_DP(x);
	dp.PY05 = DOT_TO_DP(y);

	/* �o�^�`�r�b�g�ݒ� */
	SET_PABIT(PA_K005);
}

static	void	dp_missile_sonzai1(void)
{
	long	x, y;

	/* �ԍ��Q�X�̏�ʂW���������Q�̎��͕\�����Ȃ� */
	if ((input_data[29] & 0xff00) == 0x200)
		return;

	/* �}�X�O�K�͈̔͂ɂȂ��ꍇ�͕\�����Ȃ� */
	if (input_data[30] > 9000 || input_data[30] < -9000)
		return;

	/* �f�[�^�`�F�b�N */
	if (data_check(7) || data_check(8) || data_check(9) ||
					data_check(357) || data_check(358))
		return;

	/* �Z���T�e�n�u���O�̎��͕\�����Ȃ� */
	if (input_data[7] == 0)
		return;

	/* �~�T�C���V���{���Əd�Ȃ�ꍇ�͕\�����Ȃ� */
	x = (long)dot_video * input_data[30] / input_data[7] - input_data[8] +
						input_data[357];
	y = (long)dot_video * input_data[31] / input_data[7] + input_data[9] -
						input_data[358];
	if (DOT_TO_MM(sqrt(x * x + y * y)) <= 1800)
		return;

	/* �p�x(R06)�̐ݒ� */
	dp.R06 = atan((short)y, (short)x);

	/* �o�^�`�r�b�g�ݒ� */
	SET_PABIT(PA_K006);
}

static	void	dp_missile_hyouji(void)
{
	long	x, y;
	short	l;

	/* �ԍ��Q�X�̏�ʂW���������P�̎��͕\�����Ȃ� */
	if ((input_data[29] & 0xff00) == 0x100)
		return;

	/* �f�[�^�`�F�b�N */
	if (data_check(7) || data_check(8) || data_check(9) ||
					data_check(357) || data_check(358))
		return;

	/* �Z���T�e�n�u���O�̎��͕\�����Ȃ� */
	if (input_data[7] == 0)
		return;

	/* �~�T�C���ʒu�̂w�C�x���W�v�Z */
	x = (long)dot_video * input_data[30] / input_data[7] + input_data[357];
	y = (long)dot_video * input_data[31] / input_data[7] - input_data[358];

	/* ��ʓ��ɂȂ��ꍇ�\�����Ȃ� */
	if (x > 423 || x < -423 || y > 423 || y < -423)
		return;

	/* ���e�B�N������̑��Έʒu�ɕϊ� */
	x -= input_data[8];
	y += input_data[9];

	/* �����v�Z */
	l = DOT_TO_MM(sqrt(x * x + y * y)) - 300;

	/* �������O�ȉ��̏ꍇ�͕\�����Ȃ� */
	if (l < 0)
		return;

	/* ���̒���(LX08)�̐ݒ� */
	dp.LX08 = MM_TO_DP(l);

	/* �p�x(R08)�̐ݒ� */
	dp.R08 = atan((short)y, (short)x);

	/* �o�^�`�r�b�g�ݒ� */
	SET_PABIT(PA_K008);
}

static	void	dp_missile_sonzai2(void)
{
	register long	x, y, rx, ry, tx, ty, len;

	/* �ԍ��Q�X�̏�ʂW���������P�̎��͕\�����Ȃ� */
	if ((input_data[29] & 0xff00) == 0x100)
		return;

	/* �f�[�^�`�F�b�N */
	if (data_check(7) || data_check(8) || data_check(9) ||
					data_check(357) || data_check(358))
		return;

	/* �Z���T�e�n�u���O�̎��͕\�����Ȃ� */
	if (input_data[7] == 0)
		return;

	/* �}�X�O�K�͈̔͂ɂȂ��ꍇ�͕\�����Ȃ� */
	if (input_data[30] > 9000 || input_data[30] < -9000)
		return;

	/* �~�T�C���ʒu�̂w�C�x���W�v�Z */
	x = (long)dot_video * input_data[30] / input_data[7] + input_data[357];
	y = (long)dot_video * input_data[31] / input_data[7] - input_data[358];

	/* ��ʓ��ɂ���ꍇ�\�����Ȃ� */
	if (x <= 423 && x >= -423 && y <= 423 && y >= -423)
		return;

	/* ���e�B�N������̑��Έʒu�ɕϊ� */
	rx = x - input_data[8];
	ry = y + input_data[9];

	/* �p�x(R07)�̐ݒ� */
	dp.R07 = atan((short)ry, (short)rx);

	/* �w�C�x�����̈ʒu�̐�Βl�����߂� */
	x = ABS(x);
	y = ABS(y);
	rx = ABS(rx);
	ry = ABS(ry);

	/* �w�����̒�������ʓ��i�S�O�O�h�b�g�ȓ��j�Ɏ��߂� */
	if (x > 400) {
		tx = rx - (x - 400);
		ty = ry * tx / rx;
		y -= ry - ty;
		rx = tx;
		ry = ty;
	}

	/* �x�����̒�������ʓ��i�R�Q�O�h�b�g�ȓ��j�Ɏ��߂� */
	if (y > 320) {
		tx = ry - (y - 320);
		rx = rx * tx / ry;
		ry = tx;
	}

	/* ���������߂� */
	len = DOT_TO_MM(sqrt(rx * rx + ry * ry));

	/* �������S�D�O�T�����i���̒����j���Z���ꍇ�͕\�����Ȃ� */
	if (len < 405)
		return;

	/* ����(LX07)�̐ݒ� */
	dp.LX07 = MM_TO_DP(len - 405);

	/* �o�^�`�r�b�g�ݒ� */
	SET_PABIT(PA_K007);
}

static	void	dp_obst_kyori(void)
{
	short	s1, s2;

	static	ushort	legend[5] = {
		FONT8_O, FONT8_B, FONT8_S, FONT8_T, FONT8_SP
	};

	/* �f�[�^�`�F�b�N */
	if (data_check(44) || data_check(45) ||
				data_check(354) || data_check(355))
		return;

	/* ���W�F���h�Z�b�g */
	COPY_WORD(&dp.A141[0], legend, 5);

	if (digital_update_flag) {
		/* �����Z�b�g */
		if (input_data[45] == 0) {
			SET_WORD(&dp.A141[5], FONT8_SP, 4);
			dp.C140 = COLOR_YELLOW;		/* �� */
		} else {
			tonumL(input_data[45], &dp.A141[5], 3, font8_tbl,
								FONT8_SP);

			/* �c��̃G���A�ɃX�y�[�X���Z�b�g */
			dp.A141[8] = FONT8_SP;

			/* �������l�P�C�Q���o�� */
			s1 = input_data[354];
			s2 = input_data[355];
			if (s2 <= s1)
				s2 = 1000;

			/* �F�R�[�h�Z�b�g */
			if (input_data[45] < s1)
				dp.C140 = COLOR_RED;		/* �� */
			else if (input_data[45] < s2)
				dp.C140 = COLOR_YELLOW;		/* �� */
			else
				dp.C140 = COLOR_GREEN;		/* �� */
		}
	}

	/* �o�^�`�r�b�g�ݒ� */
	SET_PABIT(PA_KD140);
}