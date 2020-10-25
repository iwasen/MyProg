main()
{
	rs1_open(5, 0x4e, 0x27);

	for (;;) {
		if (kbhit())
			rs1_put(getch());
		if (rs1_rxc())
			putch(rs1_get());
	}
}
