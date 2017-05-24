SELECT to_char(pd.birthday,'DD.MM.YYYY'), pd.difficulty FROM player_dates pd join player p on pd.player_id = p.player_id where p.username = 'Abaza.Carmen'

CREATE OR REPLACE DIRECTORY MY_DIR AS 'C:\\SQL';
/
set serveroutput on;

declare
  my_file UTL_FILE.FILE_TYPE;
  CURSOR tabele IS
    select table_name from user_tables;
  CURSOR indecsi IS
    select index_name from user_indexes where table_owner = 'STUDENT';
  CURSOR triggere IS
    select trigger_name from user_triggers where table_owner = 'STUDENT';
  CURSOR functii IS
    select object_name from user_objects where object_type in ('FUNCTION');
  CURSOR proceduri IS
    select object_name from user_objects where object_type in ('PROCEDURE');
  CURSOR pachete IS
    select object_name from user_objects where object_type in ('PACKAGE');
  CURSOR viewuri IS
    select view_name from user_views;
  v_cursor INTEGER;
  v_string VARCHAR2(10000);
  v_nume VARCHAR2(100);
  v_coloana VARCHAR2(100);
  v_insert VARCHAR2(1000);
  v_stmt varchar2(1000);
  v_rezultat integer;
begin
  my_file := UTL_FILE.FOPEN ('MY_DIR', 'export.sql', 'W', 30000);
  DBMS_METADATA.SET_TRANSFORM_PARAM(dbms_metadata.SESSION_TRANSFORM,'EMIT_SCHEMA',false);
  --creez tabelele
  
  --creez tabele
  OPEN tabele;
    LOOP
        FETCH tabele INTO v_nume;
        EXIT WHEN tabele%NOTFOUND;
        EXECUTE IMMEDIATE 'select DBMS_METADATA.GET_DDL(''TABLE'',''' || v_nume || ''') from DUAL' into v_string;
        UTL_FILE.PUT_LINE(my_file, v_string);
        UTL_FILE.PUT_LINE(my_file, '/');
    END LOOP;
  CLOSE tabele;   
  
  --populez tabelele

  --populez tabela achievements
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR achievements_cursor IS
      select achievement_id, name, description, icon_link from achievements;
    v_id achievements.achievement_id%TYPE;
    v_name achievements.name%TYPE;
    v_description achievements.description%TYPE;
    v_icon_link achievements.icon_link%TYPE;
  begin 
    OPEN achievements_cursor;
    LOOP
        FETCH achievements_cursor INTO v_id, v_name, v_description, v_icon_link;
        EXIT WHEN achievements_cursor%NOTFOUND;
        v_string := 'insert into achievements values('||v_id||','''||v_name||''','''||v_description||''','''||v_icon_link||''');';
        UTL_FILE.PUT_LINE(my_file, v_string);
    END LOOP;
  CLOSE achievements_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'END;');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela achievements_link
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR achievements_id_cursor IS
      select achievement_id, player_id from achievements_link;
    v_id achievements_link.achievement_id%TYPE;
    v_pid achievements_link.player_id%TYPE;
  begin 
    OPEN achievements_id_cursor;
    LOOP
        FETCH achievements_id_cursor INTO v_id, v_pid;
        EXIT WHEN achievements_id_cursor%NOTFOUND;
        v_string := 'insert into achievements_link values('||v_id||','||v_pid||');';
        UTL_FILE.PUT_LINE(my_file, v_string);
    END LOOP;
  CLOSE achievements_id_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'END;');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela admins
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR admins_cursor IS
      select admins_id, first_name, last_name, username, email, rights from admins;
    v_id admins.admins_id%TYPE;
    v_first_name admins.first_name%TYPE;
    v_last_name admins.last_name%TYPE;
    v_username admins.username%TYPE;
    v_email admins.email%TYPE;
    v_rights admins.rights%TYPE;
  begin 
    OPEN admins_cursor;
    LOOP
        FETCH admins_cursor INTO v_id, v_first_name, v_last_name, v_username, v_email, v_rights;
        EXIT WHEN admins_cursor%NOTFOUND;
        v_string := 'insert into admins values('||v_id||','''||v_first_name||''','''||v_last_name||''','''||v_username||''','''||v_email||''','||v_rights||');';
        UTL_FILE.PUT_LINE(my_file, v_string);
    END LOOP;
  CLOSE admins_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'COMMIT;');
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela games
  UTL_FILE.PUT_LINE(my_file, 'SET DEFINE OFF');
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR games_cursor IS
      select game_id, name, difficulty, description, instructions, domain_id, icon_link, game_link from games;
    v_id games.game_id%TYPE;
    v_name games.name%TYPE;
    v_difficulty games.difficulty%TYPE;
    v_description games.description%TYPE;
    v_instructions games.instructions%TYPE;
    v_domain_id games.domain_id%TYPE;
    v_icon_link games.icon_link%TYPE;
    v_game_link games.game_link%TYPE;
    
    variabila integer := 0; 
  begin 
    OPEN games_cursor;
    LOOP
        FETCH games_cursor INTO v_id, v_name, v_difficulty, v_description, v_instructions, v_domain_id, v_icon_link, v_game_link;
        EXIT WHEN games_cursor%NOTFOUND;
        v_string := 'insert into games values('||v_id||','''||v_name||''','||v_difficulty||','''||v_description||''','''||v_instructions||''','||v_domain_id||','''||v_icon_link||''','''||v_game_link||''');';
        UTL_FILE.PUT_LINE(my_file, v_string);
        variabila := variabila + 1;
    END LOOP;
  CLOSE games_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela passwords
  UTL_FILE.PUT_LINE(my_file, 'SET DEFINE OFF');
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR passwords_cursor IS
      select username, hash, random_string from passwords;
    v_username passwords.username%TYPE;
    v_hash passwords.hash%TYPE;
    v_random_string passwords.random_string%TYPE;
    variabila integer := 0; 
  begin 
    OPEN passwords_cursor;
    LOOP
        FETCH passwords_cursor INTO v_username, v_hash, v_random_string;
        EXIT WHEN passwords_cursor%NOTFOUND;
        v_string := 'insert into passwords values('''||v_username||''','''||v_hash||''','''||v_random_string||''');';
        UTL_FILE.PUT_LINE(my_file, v_string);
        variabila := variabila + 1;
        if(mod(variabila, 200) = 0) then
          UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
          UTL_FILE.PUT_LINE(my_file, '/');
          UTL_FILE.PUT_LINE(my_file, 'SET DEFINE OFF');
          UTL_FILE.PUT_LINE(my_file, 'BEGIN');
        end if;
    END LOOP;
  CLOSE passwords_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela domains
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR domains_cursor IS
      select domain_id, domain_name, number_of_questions, number_of_games, subject_type, icon_link from domains;
    v_domain_id domains.domain_id%TYPE;
    v_domain_name domains.domain_name%TYPE;
    v_number_of_questions domains.number_of_questions%TYPE;
    v_number_of_games domains.number_of_games%TYPE;
    v_subject_type domains.subject_type%TYPE;
    v_icon_link domains.icon_link%TYPE;
    variabila integer := 0; 
  begin 
    OPEN domains_cursor;
    LOOP
        FETCH domains_cursor INTO v_domain_id, v_domain_name, v_number_of_questions, v_number_of_games, v_subject_type, v_icon_link;
        EXIT WHEN domains_cursor%NOTFOUND;
        v_string := 'insert into domains values('||v_domain_id||','''||v_domain_name||''','||v_number_of_questions||','||v_number_of_games||','''||v_subject_type||''','''||v_icon_link||''');';
        UTL_FILE.PUT_LINE(my_file, v_string);
        variabila := variabila + 1;
        if(mod(variabila, 200) = 0) then
          UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
          UTL_FILE.PUT_LINE(my_file, '/');
          UTL_FILE.PUT_LINE(my_file, 'BEGIN');
        end if;
    END LOOP;
  CLOSE domains_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela player
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR player_cursor IS
      select player_id, first_name, last_name, username, email, link, rights, logged, relation, tutor_id from player;
    v_player_id player.player_id%TYPE;
    v_first_name player.first_name%TYPE;
    v_last_name player.last_name%TYPE;
    v_username player.username%TYPE;
    v_email player.email%TYPE;
    v_link player.link%TYPE;
    v_rights player.rights%TYPE;
    v_logged player.logged%TYPE;
    v_relation player.relation%TYPE;
    v_tutor_id player.tutor_id%TYPE;
    variabila integer := 0; 
  begin 
    OPEN player_cursor;
    LOOP
        FETCH player_cursor INTO v_player_id, v_first_name, v_last_name, v_username, v_email, v_link, v_rights, v_logged, v_relation, v_tutor_id;
        EXIT WHEN player_cursor%NOTFOUND;
        v_string := 'insert into player values('||v_player_id||','''||v_first_name||''','''||v_last_name||''','''||v_username||''','''||v_email||''','''||v_link||''','||v_rights||','''||v_logged||''','''||v_relation||''','||v_tutor_id||');';
        UTL_FILE.PUT_LINE(my_file, v_string);
        variabila := variabila + 1;
        if(mod(variabila, 200) = 0) then
          UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
          UTL_FILE.PUT_LINE(my_file, '/');
          UTL_FILE.PUT_LINE(my_file, 'BEGIN');
        end if;
    END LOOP;
  CLOSE player_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela player_activity
  UTL_FILE.PUT_LINE(my_file, 'SET DEFINE OFF');
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR player_activity_cursor IS
      select player_id, logged_in, logged_out from player_activity;
    v_player_id player_activity.player_id%TYPE;
    v_logged_in player_activity.logged_in%TYPE;
    v_logged_out player_activity.logged_out%TYPE;
    variabila integer := 0; 
  begin 
    OPEN player_activity_cursor;
    LOOP
        FETCH player_activity_cursor INTO v_player_id, v_logged_in, v_logged_out;
        EXIT WHEN player_activity_cursor%NOTFOUND;
        v_string := 'insert into player_activity values('||v_player_id||','''||v_logged_in||''','''||v_logged_out||''');';
        UTL_FILE.PUT_LINE(my_file, v_string);
        variabila := variabila + 1;
        if(mod(variabila, 200) = 0) then
          UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
          UTL_FILE.PUT_LINE(my_file, '/');
          UTL_FILE.PUT_LINE(my_file, 'SET DEFINE OFF');
          UTL_FILE.PUT_LINE(my_file, 'BEGIN');
        end if;
    END LOOP;
  CLOSE player_activity_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela player_dates
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR player_dates_cursor IS
      select player_id, birthday, difficulty, recommended_difficulty, total_time from player_dates;
    v_player_id player_dates.player_id%TYPE;
    v_birthday player_dates.birthday%TYPE;
    v_difficulty player_dates.difficulty%TYPE;
    v_recommended_difficulty player_dates.recommended_difficulty%TYPE;
    v_total_time player_dates.total_time%TYPE;
  begin 
    OPEN player_dates_cursor;
    LOOP
        FETCH player_dates_cursor INTO v_player_id, v_birthday, v_difficulty, v_recommended_difficulty, v_total_time;
        EXIT WHEN player_dates_cursor%NOTFOUND;
        v_string := 'insert into player_dates values('||v_player_id||','''||v_birthday||''','||v_difficulty||','||v_recommended_difficulty||','||v_total_time||');';
        UTL_FILE.PUT_LINE(my_file, v_string);
    END LOOP;
  CLOSE player_dates_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'COMMIT;');
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela player_stats
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR player_stats_cursor IS
      select player_id, domain_id, highest_score, average_score, number_of_plays, last_played from player_stats;
    v_player_id player_stats.player_id%TYPE;
    v_domain_id player_stats.domain_id%TYPE;
    v_highest_score player_stats.highest_score%TYPE;
    v_average_score player_stats.average_score%TYPE;
    v_number_of_plays player_stats.number_of_plays%TYPE;
    v_last_played player_stats.last_played%TYPE;
  begin 
    OPEN player_stats_cursor;
    LOOP
        FETCH player_stats_cursor INTO v_player_id, v_domain_id, v_highest_score, v_average_score, v_number_of_plays, v_last_played;
        EXIT WHEN player_stats_cursor%NOTFOUND;
        v_string := 'insert into player_stats values('||v_player_id||','||v_domain_id||','||v_highest_score||','||v_average_score||','||v_number_of_plays||','''||v_last_played||''');';
        UTL_FILE.PUT_LINE(my_file, v_string);
    END LOOP;
  CLOSE player_stats_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'COMMIT;');
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela questions_answers
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR questions_answers_cursor IS
      select question_id, answer1, answer2, answer3, correct_answer from questions_answers;
    v_question1 questions_answers.question_id%TYPE;
    v_answer1 questions_answers.answer1%TYPE;
    v_answer2 questions_answers.answer2%TYPE;
    v_answer3 questions_answers.answer3%TYPE;
    v_correct_answer questions_answers.correct_answer%TYPE;
  begin 
    OPEN questions_answers_cursor;
    LOOP
        FETCH questions_answers_cursor INTO v_question1, v_answer1, v_answer2, v_answer3, v_correct_answer;
        EXIT WHEN questions_answers_cursor%NOTFOUND;
        v_string := 'insert into questions_answers values('||v_question1||','''||v_answer1||''','''||v_answer2||''','''||v_answer3||''','''||v_correct_answer||''');';
        UTL_FILE.PUT_LINE(my_file, v_string);
    END LOOP;
  CLOSE questions_answers_cursor;
  end;
  UTL_FILE.PUT_LINE(my_file, 'COMMIT;');
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela tests
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR tests_cursor IS
      select test_id, player_id, score, domain_id from tests;
    v_test_id tests.test_id%TYPE;
    v_player_id tests.player_id%TYPE;
    v_score tests.score%TYPE;
    v_domain_id tests.domain_id%TYPE;
  begin 
    OPEN tests_cursor;
    LOOP
        FETCH tests_cursor INTO v_test_id, v_player_id, v_score, v_domain_id;
        EXIT WHEN tests_cursor%NOTFOUND;
        v_string := 'insert into tests values('||v_test_id||','||v_player_id||','||v_score||','||v_domain_id||');';
        UTL_FILE.PUT_LINE(my_file, v_string);
    END LOOP;
  CLOSE tests_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'END;');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela tutor
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR tutor_cursor IS
      select tutor_id, first_name, last_name, username, email, link, rights from tutor;
    v_tutor_id tutor.tutor_id%TYPE;
    v_first_name tutor.first_name%TYPE;
    v_last_name tutor.last_name%TYPE;
    v_username tutor.username%TYPE;
    v_email tutor.email%TYPE;
    v_link tutor.link%TYPE;
    v_rights tutor.rights%TYPE;
    variabila integer := 0; 
  begin 
    OPEN tutor_cursor;
    LOOP
        FETCH tutor_cursor INTO v_tutor_id, v_first_name, v_last_name, v_username, v_email, v_link, v_rights;
        EXIT WHEN tutor_cursor%NOTFOUND;
        v_string := 'insert into tutor values('||v_tutor_id||','''||v_first_name||''','''||v_last_name||''','''||v_username||''','''||v_email||''','''||v_link||''','||v_rights||');';
        UTL_FILE.PUT_LINE(my_file, v_string);
        variabila := variabila + 1;
        if(mod(variabila, 200) = 0) then
          UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
          UTL_FILE.PUT_LINE(my_file, '/');
          UTL_FILE.PUT_LINE(my_file, 'BEGIN');
        end if;
    END LOOP;
  CLOSE tutor_cursor; 
  end;
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --populez tabela tw_questions
  UTL_FILE.PUT_LINE(my_file, 'BEGIN');
  declare
    CURSOR tw_questions_cursor IS
      select question_id, domain_id, question_id, difficulty, tip from tw_questions;
    v_question_id tw_questions.question_id%TYPE;
    v_domain_id tw_questions.domain_id%TYPE;
    v_question_text tw_questions.question_text%TYPE;
    v_difficulty tw_questions.difficulty%TYPE;
    v_tip tw_questions.tip%TYPE;
  begin 
    OPEN tw_questions_cursor;
    LOOP
        FETCH tw_questions_cursor INTO v_question_id, v_domain_id, v_question_text, v_difficulty, v_tip;
        EXIT WHEN tw_questions_cursor%NOTFOUND;
        v_string := 'insert into tw_questions values('||v_question_id||','||v_domain_id||','''||v_question_text||''','||v_difficulty||','''||v_tip||''');';
        UTL_FILE.PUT_LINE(my_file, v_string);
    END LOOP;
  CLOSE tw_questions_cursor;
  end;
  UTL_FILE.PUT_LINE(my_file, 'COMMIT;');
  UTL_FILE.PUT_LINE(my_file, 'END;---------------------------------------------------------------------------------------------------');
  UTL_FILE.PUT_LINE(my_file, '/');
  
  --creez indecsii
  OPEN indecsi;
    LOOP
        FETCH indecsi INTO v_nume;
        EXIT WHEN indecsi%NOTFOUND;
        EXECUTE IMMEDIATE 'select DBMS_METADATA.GET_DDL(''INDEX'',''' || v_nume|| ''') from DUAL' into v_string;
        UTL_FILE.PUT_LINE(my_file, v_string);
        UTL_FILE.PUT_LINE(my_file, '/');
    END LOOP;
  CLOSE indecsi;  
  
      --creez view-urile
  OPEN viewuri;
    LOOP
        FETCH viewuri INTO v_nume;
        EXIT WHEN viewuri%NOTFOUND;
        EXECUTE IMMEDIATE 'select DBMS_METADATA.GET_DDL(''VIEW'',''' || v_nume || ''') from DUAL' into v_string;
        UTL_FILE.PUT_LINE(my_file, v_string);
        UTL_FILE.PUT_LINE(my_file, '/');
    END LOOP;
  CLOSE viewuri; 
  
  --creez pachete
  OPEN pachete;
    LOOP
        FETCH pachete INTO v_nume;
        EXIT WHEN pachete%NOTFOUND;
        EXECUTE IMMEDIATE 'select DBMS_METADATA.GET_DDL(''PACKAGE'',''' || v_nume || ''') from DUAL' into v_string;
        UTL_FILE.PUT_LINE(my_file, v_string);
        UTL_FILE.PUT_LINE(my_file, '/');
    END LOOP;
  CLOSE pachete; 
  
  --creez functii
  OPEN functii;
    LOOP
        FETCH functii INTO v_nume;
        EXIT WHEN functii%NOTFOUND;
        EXECUTE IMMEDIATE 'select DBMS_METADATA.GET_DDL(''FUNCTION'',''' || v_nume || ''') from DUAL' into v_string;
        UTL_FILE.PUT_LINE(my_file, v_string);
        UTL_FILE.PUT_LINE(my_file, '/');
    END LOOP;
  CLOSE functii; 
  
  --creez proceduri
  OPEN proceduri;
    LOOP
        FETCH proceduri INTO v_nume;
        EXIT WHEN proceduri%NOTFOUND;
        EXECUTE IMMEDIATE 'select DBMS_METADATA.GET_DDL(''PROCEDURE'',''' || v_nume || ''') from DUAL' into v_string;
        UTL_FILE.PUT_LINE(my_file, v_string);
        UTL_FILE.PUT_LINE(my_file, '/');
    END LOOP;
  CLOSE proceduri; 
  
  --creez triggere
  OPEN triggere;
    LOOP
        FETCH triggere INTO v_nume;
        EXIT WHEN triggere%NOTFOUND;
        EXECUTE IMMEDIATE 'select DBMS_METADATA.GET_DDL(''TRIGGER'',''' || v_nume || ''') from DUAL' into v_string;
        UTL_FILE.PUT_LINE(my_file, v_string);
        UTL_FILE.PUT_LINE(my_file, '/');
    END LOOP;
  CLOSE triggere;  
  
  utl_file.fclose(my_file);
end;