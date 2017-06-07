create or replace PACKAGE user_pachet
IS
  FUNCTION login(
      p_username player.username%TYPE, p_password passwords.hash%TYPE) RETURN VARCHAR2;
  FUNCTION insert_suport(v_username VARCHAR2,v_categorie VARCHAR2,v_text VARCHAR2)RETURN VARCHAR2;
  PROCEDURE reset_dificultate;
  PROCEDURE radiaza_conturi;
  PROCEDURE register_user(p_f_name VARCHAR2,p_s_name VARCHAR2,p_username VARCHAR2,p_password VARCHAR2,p_nr_rand VARCHAR2,p_img VARCHAR2,p_email VARCHAR2,p_bday VARCHAR2,p_relation VARCHAR2,t_f_name VARCHAR2,t_s_name VARCHAR2,t_username VARCHAR2,t_password VARCHAR2,t_nr_rand VARCHAR2,t_img VARCHAR2,t_email VARCHAR2);
END user_pachet;

/

create or replace PACKAGE BODY user_pachet
IS

    FUNCTION login(
      p_username player.username%TYPE, p_password passwords.hash%TYPE) RETURN VARCHAR2
    AS
      v_username player.username%TYPE;
      counter_1 INTEGER := -1;
      counter_2 INTEGER := -1;
      counter_3 INTEGER := -1;
      v_hash passwords.hash%TYPE;
      mesaj VARCHAR2(30);
      ok INTEGER := 1;
      
    BEGIN
      SELECT count(*) INTO counter_1 FROM player WHERE username = p_username;
      IF counter_1 > 0 THEN
        ok := 1;
        SELECT username INTO v_username FROM player WHERE username = p_username;
      ELSE
        SELECT count(*) INTO counter_2 FROM tutor WHERE username = p_username;
        IF counter_2 > 0 THEN
          ok := 2;
          SELECT username INTO v_username FROM tutor WHERE username = p_username;
        ELSE
          SELECT count(*) INTO counter_3 FROM admins WHERE username = p_username;
          IF counter_3 > 0 THEN
            ok := 3;
            SELECT username INTO v_username FROM admins WHERE username = p_username;
          ELSE
            return 'i';
          END IF;
        END IF;
      END IF;
      
      SELECT count(*) INTO counter_1 FROM passwords WHERE username = p_username AND hash = p_password;
      IF counter_1 = 0 THEN
        mesaj := 'i';
      ELSE
        IF ok = 1 THEN
          mesaj := 'p';
        ELSIF ok = 2 THEN
          mesaj := 't';
        ELSIF ok = 3 THEN
          mesaj := 'a';
        END IF;
      END IF;
      return mesaj;
    END login;
    
    FUNCTION insert_suport(v_username VARCHAR2,v_categorie VARCHAR2, v_text VARCHAR2)RETURN VARCHAR2 IS 
      v_n_id INTEGER;
      v_count INTEGER;
      BEGIN
           SELECT count(suport_id) into v_count from suport;
           if(v_count = 0) then
              v_n_id := 1;
           else
              SELECT max(suport_id) + 1 into v_n_id from suport;
           end if;
           insert into suport(suport_id, username, categorie, text, data) 
              values (v_n_id, v_username, v_categorie, v_text, TO_CHAR(SYSDATE, 'dd/mm/YYYY'));
           return 'Plangere inserata.';
      END insert_suport;
      
    PROCEDURE reset_dificultate
    AS
    v_id INTEGER;
    BEGIN
      update player_dates set difficulty = 1 where birthday between TO_DATE('01/01/08','dd/mm/yy') and TO_DATE('31/12/11','dd/mm/yy');
      update player_dates set difficulty = 2 where birthday between TO_DATE('01/01/04','dd/mm/yy') and TO_DATE('31/12/07','dd/mm/yy');
      update player_dates set difficulty = 3 where birthday between TO_DATE('01/01/00','dd/mm/yy') and TO_DATE('31/12/03','dd/mm/yy');
    END reset_dificultate;
    
    PROCEDURE radiaza_conturi
    AS
    CURSOR lista_player  IS
       SELECT player_id FROM player;
    v_id INTEGER;
    BEGIN
      OPEN lista_player;
      --ceva ceva
      CLOSE lista_player;   
    END radiaza_conturi;
    
    PROCEDURE register_user(p_f_name VARCHAR2,p_s_name VARCHAR2,p_username VARCHAR2,p_password VARCHAR2,p_nr_rand VARCHAR2,p_img VARCHAR2,p_email VARCHAR2,p_bday VARCHAR2,p_relation VARCHAR2,t_f_name VARCHAR2,t_s_name VARCHAR2,t_username VARCHAR2,t_password VARCHAR2,t_nr_rand VARCHAR2,t_img VARCHAR2,t_email VARCHAR2) AS
      v_p_id integer;
      v_t_id integer;
      v_t_nr integer;
      v_p_nr integer;
    BEGIN
      SELECT count(tutor_id) into v_t_nr from tutor where username = t_username;
      if(v_t_nr > 0) then
        SELECT tutor_id into v_t_id from tutor where username = t_username;
      else
        SELECT count(tutor_id) into v_t_nr from tutor;
        if(v_t_nr = 0)then
          v_t_id := 1;
          insert into tutor values (v_t_id, t_f_name, t_s_name, t_username, t_email, t_img, 1);
          insert into passwords values (t_username, t_password, TO_NUMBER(t_nr_rand));
        else
          SELECT max(tutor_id) + 1 into v_t_id from tutor;
          insert into tutor values (v_t_id, t_f_name, t_s_name, t_username, t_email, t_img, 1);
          insert into passwords values (t_username, t_password, TO_NUMBER(t_nr_rand));
        end if;
      end if;
      
      SELECT count(player_id) into v_p_nr from player;
      if(v_p_nr = 0)then
          v_p_id := 1;
          insert into player values (v_p_id, p_f_name, p_s_name, p_username, p_email, p_img, 1, 0, p_relation, v_t_id);
      else
          SELECT max(player_id) + 1 into v_p_id from player;
          insert into player values (v_p_id, p_f_name, p_s_name, p_username, p_email, p_img, 1, 0, p_relation, v_t_id);
      end if;
      
      insert into player_dates values (v_p_id, TO_DATE(p_bday,'dd/mm/yyyy'), 5, 5, 0);
      
      insert into passwords values (p_username, p_password, TO_NUMBER(p_nr_rand));
      
      update player_dates set difficulty = 1 where birthday between TO_DATE('01/01/08','dd/mm/yy') and TO_DATE('31/12/11','dd/mm/yy');
      update player_dates set difficulty = 2 where birthday between TO_DATE('01/01/04','dd/mm/yy') and TO_DATE('31/12/07','dd/mm/yy');
      update player_dates set difficulty = 3 where birthday between TO_DATE('01/01/00','dd/mm/yy') and TO_DATE('31/12/03','dd/mm/yy');
    END register_user;
    
END user_pachet;