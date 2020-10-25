-- (note) melonpan=# createlang plpgsql melonpan
--        [postgres@WS06 postgres]$ psql -d melonpan -f mailladdr_comp.sql
DROP FUNCTION mailladdr_comp();
CREATE FUNCTION mailladdr_comp() RETURNS integer AS '

DECLARE

r RECORD;
seq_no integer := 0;

BEGIN

	delete from s_mailaddr;
	
	-- toatl reader count
	FOR r IN select mag_id, count(*) as cnt from t_mailaddr group by mag_id LOOP EXIT WHEN NOT FOUND;
--		RAISE NOTICE ''mag_id=%  cnt=%'', r.mag_id, r.cnt;
		seq_no := seq_no + 1;
		INSERT INTO s_mailaddr(smail_id, mag_id, s_key, s_name, s_count, s_update)
				 values(seq_no, r.mag_id, ''ALL'', ''ALL'', r.cnt, CURRENT_TIMESTAMP);
	END LOOP;

	-- for sex distinction
	FOR r IN select mag_id, reader_sex, count(*) as cnt from t_mailaddr join m_melonpa 
				using(reader_id) group by t_mailaddr.mag_id, reader_sex LOOP EXIT WHEN NOT FOUND;
		seq_no := seq_no + 1;
		INSERT INTO s_mailaddr(smail_id, mag_id, s_key, s_name, s_count, s_update)
				 values(seq_no, r.mag_id, ''SEX'', r.reader_sex , r.cnt, CURRENT_TIMESTAMP);
	END LOOP;

	-- for marriage
	FOR r IN select mag_id, reader_mrg, count(*) as cnt from t_mailaddr join m_melonpa
		 		using(reader_id) group by t_mailaddr.mag_id ,reader_mrg LOOP EXIT WHEN NOT FOUND;
		seq_no := seq_no + 1;
		INSERT INTO s_mailaddr(smail_id, mag_id, s_key, s_name, s_count, s_update)
				 values(seq_no, r.mag_id, ''MRG'', r.reader_mrg , r.cnt, CURRENT_TIMESTAMP);
	END LOOP;

	-- for occupation
	FOR r IN select mag_id, reader_occup, count(*) as cnt from t_mailaddr join m_melonpa
		 		using(reader_id) group by t_mailaddr.mag_id ,reader_occup LOOP EXIT WHEN NOT FOUND;
		seq_no := seq_no + 1;
		INSERT INTO s_mailaddr(smail_id, mag_id, s_key, s_name, s_count, s_update)
				 values(seq_no, r.mag_id, ''OCC'', r.reader_occup, r.cnt, CURRENT_TIMESTAMP);
	END LOOP;

	-- for cancellation reason
	FOR r IN select mag_id, reason, count(*) as cnt from t_delreason group by mag_id, reason LOOP EXIT WHEN NOT FOUND;
		seq_no := seq_no + 1;
		INSERT INTO s_mailaddr(smail_id, mag_id, s_key, s_name, s_count, s_update)
				 values(seq_no, r.mag_id, ''ERR'', r.reason, r.cnt, CURRENT_TIMESTAMP);
	END LOOP;

	RETURN NULL;

END;
' LANGUAGE 'plpgsql';
