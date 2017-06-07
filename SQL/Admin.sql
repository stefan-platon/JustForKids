CREATE OR REPLACE PACKAGE admin_pachet AS
        FUNCTION insert_intrebare(v_domeniu VARCHAR2,v_text VARCHAR2,v_dif VARCHAR2,v_tip VARCHAR2,v_r1 VARCHAR2,v_r2 VARCHAR2,v_r3 VARCHAR2,v_rc VARCHAR2)RETURN VARCHAR2;
        FUNCTION insert_domeniu(v_nume VARCHAR2,v_tip VARCHAR2,v_link VARCHAR2)RETURN VARCHAR2;
        FUNCTION insert_realizare(v_nume VARCHAR2,v_descr VARCHAR2,v_link VARCHAR2)RETURN VARCHAR2;
        FUNCTION insert_joc(v_nume VARCHAR2,v_dif VARCHAR2,v_descr VARCHAR2,v_instr VARCHAR2,v_domeniu VARCHAR2,i_link VARCHAR2,g_link VARCHAR2)RETURN VARCHAR2;
        FUNCTION insert_stire(v_titlu VARCHAR2,v_content VARCHAR2)RETURN VARCHAR2;
        FUNCTION insert_update(v_titlu VARCHAR2,v_content VARCHAR2)RETURN VARCHAR2;
        FUNCTION update_intrebare(v_id VARCHAR2, v_domeniu VARCHAR2,v_text VARCHAR2,v_dif VARCHAR2,v_tip VARCHAR2,v_r1 VARCHAR2,v_r2 VARCHAR2,v_r3 VARCHAR2,v_rc VARCHAR2)RETURN VARCHAR2;
        FUNCTION sterge_intrebare(v_id VARCHAR2)RETURN VARCHAR2;
        FUNCTION sterge_domeniu(v_id VARCHAR2)RETURN VARCHAR2;
        FUNCTION sterge_realizare(v_id VARCHAR2)RETURN VARCHAR2;
        FUNCTION sterge_joc(v_id VARCHAR2)RETURN VARCHAR2;
END admin_pachet;
/
CREATE OR REPLACE PACKAGE BODY admin_pachet AS
      FUNCTION insert_intrebare(v_domeniu VARCHAR2,v_text VARCHAR2,v_dif VARCHAR2,v_tip VARCHAR2,v_r1 VARCHAR2,v_r2 VARCHAR2,v_r3 VARCHAR2,v_rc VARCHAR2)RETURN VARCHAR2 IS 
      v_dom_int INTEGER;
      v_dif_int INTEGER;
      v_q_id INTEGER;
      v_count INTEGER;
      BEGIN
           v_dom_int := TO_NUMBER(v_domeniu);
           v_dif_int := TO_NUMBER(v_dif);
           SELECT count(question_id) into v_count from tw_questions;
           if(v_count = 0) then
              v_q_id := 1;
           else
              SELECT max(question_id) + 1 into v_q_id from tw_questions;
           end if;
           insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
              values (v_q_id, v_dom_int, v_text, v_dif_int, v_tip);
           insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
              values (v_q_id, v_r1, v_r2, v_r3, v_rc);
           return 'Intrebare inserata.';
      END insert_intrebare;
      
      FUNCTION insert_domeniu(v_nume VARCHAR2,v_tip VARCHAR2,v_link VARCHAR2)RETURN VARCHAR2 IS 
      v_d_id INTEGER;
      v_count INTEGER;
      BEGIN
           SELECT count(domain_id) into v_count from domains;
           if(v_count = 0) then
              v_d_id := 1;
           else
              SELECT max(domain_id) + 1 into v_d_id from domains;
           end if;
           insert into domains(domain_id, domain_name, number_of_questions, number_of_games, subject_type, icon_link) 
              values (v_d_id, v_nume, 0, 0, v_tip, v_link);
           return 'Domeniu inserat.';
      END insert_domeniu;
      
      FUNCTION insert_realizare(v_nume VARCHAR2,v_descr VARCHAR2,v_link VARCHAR2)RETURN VARCHAR2 IS 
      v_d_id INTEGER;
      v_count INTEGER;
      BEGIN
           SELECT count(achievement_id) into v_count from achievements;
           if(v_count = 0) then
              v_d_id := 1;
           else
              SELECT max(achievement_id) + 1 into v_d_id from achievements;
           end if;
           insert into achievements(achievement_id, name, description, icon_link) 
              values (v_d_id, v_nume, v_descr, v_link);
           return 'Realizare inserata.';
      END insert_realizare;
      
      FUNCTION insert_joc(v_nume VARCHAR2,v_dif VARCHAR2,v_descr VARCHAR2,v_instr VARCHAR2,v_domeniu VARCHAR2,i_link VARCHAR2,g_link VARCHAR2)RETURN VARCHAR2 IS 
      v_dom_int INTEGER;
      v_dif_int INTEGER;
      v_j_id INTEGER;
      v_count INTEGER;
      BEGIN
           v_dom_int := TO_NUMBER(v_domeniu);
           v_dif_int := TO_NUMBER(v_dif);
           SELECT count(game_id) into v_count from games;
           if(v_count = 0) then
              v_j_id := 1;
           else
              SELECT max(game_id) + 1 into v_j_id from games;
           end if;
           insert into games(game_id, name, difficulty, description, instructions, domain_id, icon_link, game_link)
              values (v_j_id, v_nume, v_dif_int, v_descr, v_instr, v_dom_int, i_link, g_link);
           return 'Joc inserat.';
      END insert_joc;
      
      FUNCTION insert_stire(v_titlu VARCHAR2,v_content VARCHAR2)RETURN VARCHAR2 IS 
      v_n_id INTEGER;
      v_count INTEGER;
      BEGIN
           SELECT count(news_id) into v_count from news;
           if(v_count = 0) then
              v_n_id := 1;
           else
              SELECT max(news_id) + 1 into v_n_id from news;
           end if;
           insert into news(news_id, titlu, continut, data) 
              values (v_n_id, v_titlu, v_content, TO_CHAR(SYSDATE, 'dd/mm/YYYY'));
           return 'Stire inserata.';
      END insert_stire;
      
      FUNCTION insert_update(v_titlu VARCHAR2,v_content VARCHAR2)RETURN VARCHAR2 IS 
      v_u_id INTEGER;
      v_count INTEGER;
      BEGIN
           SELECT count(updates_id) into v_count from updates;
           if(v_count = 0) then
              v_u_id := 1;
           else
              SELECT max(updates_id) + 1 into v_u_id from updates;
           end if;
           insert into updates(updates_id, titlu, continut, data) 
              values (v_u_id, v_titlu, v_content, TO_CHAR(SYSDATE, 'dd/mm/YYYY'));
           return 'Update inserat.';
      END insert_update;
      
      FUNCTION update_intrebare(v_id VARCHAR2,v_domeniu VARCHAR2,v_text VARCHAR2,v_dif VARCHAR2,v_tip VARCHAR2,v_r1 VARCHAR2,v_r2 VARCHAR2,v_r3 VARCHAR2,v_rc VARCHAR2)RETURN VARCHAR2 IS 
      v_dom_int INTEGER;
      v_dif_int INTEGER;
      v_q_id INTEGER;
      BEGIN
           v_q_id := TO_NUMBER(v_id);
           v_dom_int := TO_NUMBER(v_domeniu);
           v_dif_int := TO_NUMBER(v_dif);
           UPDATE tw_questions SET domain_id = v_dom_int,question_text = v_text,difficulty = v_dif_int,tip = v_tip WHERE question_id = v_q_id;
           UPDATE questions_answers SET answer1 = v_r1,answer2 = v_r2,answer3 = v_r3,correct_answer = v_rc WHERE question_id = v_q_id;
           return 'Intrebare actualizata.';
      END update_intrebare;
      
      FUNCTION sterge_intrebare(v_id VARCHAR2)RETURN VARCHAR2 IS 
      v_q_id INTEGER;
      v_id_max INTEGER;
      BEGIN
           v_q_id := TO_NUMBER(v_id);
           SELECT max(question_id) into v_id_max from tw_questions; 
           if v_id_max < v_q_id then
              return 'Nu s-a gasit intrebarea!';
           end if;
           DELETE FROM tw_questions WHERE question_id = v_q_id;
           return 'Intrebare stearsa.';
      END sterge_intrebare;
      
      FUNCTION sterge_domeniu(v_id VARCHAR2)RETURN VARCHAR2 IS 
      v_d_id INTEGER;
      v_id_max INTEGER;
      BEGIN
           v_d_id := TO_NUMBER(v_id);
           SELECT max(domain_id) into v_id_max from domains; 
           if v_id_max < v_d_id then
              return 'Nu s-a gasit domeniul!';
           end if;
           DELETE FROM domains WHERE domain_id = v_d_id;
           return 'Domeniu sters.';
      END sterge_domeniu;
      
      FUNCTION sterge_realizare(v_id VARCHAR2)RETURN VARCHAR2 IS 
      v_r_id INTEGER;
      v_id_max INTEGER;
      BEGIN
           v_r_id := TO_NUMBER(v_id);
           SELECT max(achievement_id) into v_id_max from achievements; 
           if v_id_max < v_r_id then
              return 'Nu s-a gasit realizarea!';
           end if;
           DELETE FROM achievements WHERE achievement_id = v_r_id;
           return 'Realizare stearsa.';
      END sterge_realizare;
      
      FUNCTION sterge_joc(v_id VARCHAR2)RETURN VARCHAR2 IS 
      v_j_id INTEGER;
      v_id_max INTEGER;
      BEGIN
           v_j_id := TO_NUMBER(v_id);
           SELECT max(game_id) into v_id_max from games; 
           if v_id_max < v_j_id then
              return 'Nu s-a gasit jocul!';
           end if;
           DELETE FROM games WHERE game_id = v_j_id;
           return 'Joc sters.';
      END sterge_joc;
END admin_pachet;