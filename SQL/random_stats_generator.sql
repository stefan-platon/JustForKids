--populate player_stats with random scores
delete from tests;
delete from player_stats;

/

declare
  i integer;
  j integer;
  v_rand_nr integer;
  v_rand_score integer;
  v_max1 integer := 0;
  v_max2 integer := 0;
  v_max3 integer := 0;
  v_max4 integer := 0;
  v_total_score_1 integer := 0;
  v_total_score_2 integer := 0;
  v_total_score_3 integer := 0;
  v_total_score_4 integer := 0;
  v_nr1 integer := 0;
  v_nr2 integer := 0;
  v_nr3 integer := 0;
  v_nr4 integer := 0;
  v_count integer := 1;
begin
  for i in 1 .. 10000 loop
    v_nr1 := round(dbms_random.value(1,15));
    v_nr2 := round(dbms_random.value(1,15));
    v_nr3 := round(dbms_random.value(1,15));
    v_nr4 := round(dbms_random.value(1,15));
    
    for j in 1 .. v_nr1 loop
      v_rand_score := round(dbms_random.value(1,10));
      insert into tests values (v_count, i, v_rand_score, 1);
      v_count := v_count + 1;
      v_total_score_1 := v_total_score_1 + v_rand_score;
      if v_max1 < v_rand_score then
          v_max1 := v_rand_score;
      end if;
    end loop;
    
    for j in 1 .. v_nr2 loop
      v_rand_score := round(dbms_random.value(1,10));
      insert into tests values (v_count, i, v_rand_score, 2);
      v_count := v_count + 1;
      v_total_score_2 := v_total_score_2 + v_rand_score;
      if v_max2 < v_rand_score then
          v_max2 := v_rand_score;
      end if;
    end loop;
    
    for j in 1 .. v_nr3 loop
      v_rand_score := round(dbms_random.value(1,10));
      insert into tests values (v_count, i, v_rand_score, 3);
      v_count := v_count + 1;
      v_total_score_3 := v_total_score_3 + v_rand_score;
      if v_max3 < v_rand_score then
          v_max3 := v_rand_score;
      end if;
    end loop;
    
    for j in 1 .. v_nr4 loop
      v_rand_score := round(dbms_random.value(1,10));
      insert into tests values (v_count, i, v_rand_score, 4);
      v_count := v_count + 1;
      v_total_score_4 := v_total_score_4 + v_rand_score;
      if v_max4 < v_rand_score then
          v_max4 := v_rand_score;
      end if;
    end loop;
    
    
    insert into player_stats values (i, 1, v_max1, v_total_score_1, v_nr1, TO_DATE(TRUNC(DBMS_RANDOM.VALUE(TO_CHAR(TO_DATE('01/01/17','dd/mm/yy'),'J'),TO_CHAR(sysdate,'J'))),'J'));
    insert into player_stats values (i, 2, v_max2, v_total_score_2, v_nr2, TO_DATE(TRUNC(DBMS_RANDOM.VALUE(TO_CHAR(TO_DATE('01/01/17','dd/mm/yy'),'J'),TO_CHAR(sysdate,'J'))),'J'));
    insert into player_stats values (i, 3, v_max3, v_total_score_3, v_nr3, TO_DATE(TRUNC(DBMS_RANDOM.VALUE(TO_CHAR(TO_DATE('01/01/17','dd/mm/yy'),'J'),TO_CHAR(sysdate,'J'))),'J'));
    insert into player_stats values (i, 4, v_max4, v_total_score_4, v_nr4, TO_DATE(TRUNC(DBMS_RANDOM.VALUE(TO_CHAR(TO_DATE('01/01/17','dd/mm/yy'),'J'),TO_CHAR(sysdate,'J'))),'J'));
    
    v_max1 := 0;
    v_max2 := 0;
    v_max3 := 0;
    v_max4 := 0;
    v_total_score_1 := 0;
    v_total_score_2 := 0;
    v_total_score_3 := 0;
    v_total_score_4 := 0;
    
  end loop;
end;