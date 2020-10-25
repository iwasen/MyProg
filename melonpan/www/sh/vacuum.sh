#!/bin/csh

vacuumdb -q -h $MELONPAN_DB -U postgres -z melonpan
vacuumdb -q -h $MELONPAN_DB -U postgres -z -f -t t_mag_dondon melonpan
