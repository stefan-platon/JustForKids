-----------------------------------------------------------------------------------------------------------------------------------stergere
ALTER TABLE achievements_link drop CONSTRAINT achievements_player_FK;

ALTER TABLE achievements_link drop CONSTRAINT achievements_ach_FK;

ALTER TABLE questions_answers drop CONSTRAINT questions_answers_FK;

ALTER TABLE tw_questions drop CONSTRAINT questions_domains_FK;

ALTER TABLE games drop CONSTRAINT games_domains_FK;

ALTER TABLE player_stats drop CONSTRAINT player_stats_domains_FK ;

ALTER TABLE player_stats drop CONSTRAINT player_stats_player_FK;

ALTER TABLE player_activity drop CONSTRAINT player_activity_player_FK;

ALTER TABLE player_dates drop CONSTRAINT player_dates_player_FK;

ALTER TABLE player drop CONSTRAINT player_tutor_FK;

ALTER TABLE tests drop CONSTRAINT tests_domains_FK;

drop table player;
drop table player_dates;
drop table player_activity;
drop table achievements;
drop table achievements_link;
drop table player_stats;
drop table passwords;
drop table tutor;
drop table tests;
drop table admins;
drop table domains;
drop table tw_questions;
drop table questions_answers;
drop table games;
drop table news;
drop table updates;
drop table suport;
/
----------------------------------------------------------------------------------------------------------------------------creare
CREATE TABLE news
  (
    news_id   INTEGER NOT NULL ,
    titlu     VARCHAR2(200) NOT NULL,
    continut  VARCHAR2(500) NOT NULL,
    data      date  NOT NULL
  ) ;
ALTER TABLE news ADD CONSTRAINT news_PK PRIMARY KEY ( news_id ) ;

CREATE TABLE updates
  (
    updates_id   INTEGER NOT NULL ,
    titlu     VARCHAR2(200) NOT NULL,
    continut  VARCHAR2(500) NOT NULL,
    data      date  NOT NULL
  ) ;
ALTER TABLE updates ADD CONSTRAINT updates_PK PRIMARY KEY ( updates_id ) ;

CREATE TABLE suport
  (
    suport_id   INTEGER NOT NULL ,
    username    VARCHAR2(100) ,
    categorie   VARCHAR2(100) NOT NULL ,
    text        VARCHAR2(1000) NOT NULL ,
    data        date NOT NULL
  ) ;
ALTER TABLE suport ADD CONSTRAINT suport_PK PRIMARY KEY ( suport_id ) ;

CREATE TABLE achievements_link
  (
    achievement_id   INTEGER NOT NULL ,
    player_id        INTEGER NOT NULL
  ) ;
ALTER TABLE achievements_link ADD CONSTRAINT achievements_link_PK PRIMARY KEY ( achievement_id, player_id ) ;

CREATE TABLE achievements
  (
    achievement_id   INTEGER NOT NULL ,
    name             VARCHAR2 (30) ,
    description      VARCHAR2 (50) ,
    icon_link        VARCHAR2 (100)
  ) ;
ALTER TABLE achievements ADD CONSTRAINT achievements_PK PRIMARY KEY ( achievement_id) ;
ALTER TABLE achievements ADD CONSTRAINT ach_unique1 UNIQUE ( name ) ;

CREATE TABLE passwords
  (
    username        VARCHAR2(30),
    hash            VARCHAR2(3000),
    random_string   VARCHAR2(3000)
  ) ;
ALTER TABLE passwords ADD CONSTRAINT passwords_PK PRIMARY KEY ( username ) ;

CREATE TABLE tests
  (
    test_id       NUMBER,
    player_id     NUMBER,
    score         NUMBER,
    domain_id     NUMBER
  ) ;
ALTER TABLE tests ADD CONSTRAINT tests_PK PRIMARY KEY ( test_id ) ;

CREATE TABLE admins
  (
    admins_id    INTEGER NOT NULL ,
    first_name   VARCHAR2 (30) ,
    last_name    VARCHAR2 (30) ,
    username     VARCHAR2 (30)  NOT NULL,
    email        VARCHAR2 (50) NOT NULL,
    rights       INTEGER
  ) ;
ALTER TABLE admins ADD CONSTRAINT admins_PK PRIMARY KEY ( admins_id ) ;
--ALTER TABLE admins ADD CONSTRAINT admins_unique1 UNIQUE ( username ) ;
ALTER TABLE admins ADD CONSTRAINT admins_unique2 UNIQUE ( email ) ;

CREATE TABLE domains
  (
    domain_id           INTEGER NOT NULL ,
    domain_name         VARCHAR2 (30) ,
    number_of_questions INTEGER ,
    number_of_games     INTEGER ,
    subject_type        VARCHAR2 (30),
    icon_link           VARCHAR2 (100)
  ) ;
ALTER TABLE domains ADD CONSTRAINT domains_PK PRIMARY KEY ( domain_id ) ;
ALTER TABLE domains ADD CONSTRAINT domains_unique1 UNIQUE ( domain_name ) ;

CREATE TABLE games
  (
    game_id            INTEGER NOT NULL ,
    name               VARCHAR2 (30) ,
    difficulty         INTEGER ,
    description        VARCHAR2 (100),
    instructions       VARCHAR2 (300),
    domain_id          INTEGER,
    icon_link          VARCHAR2(100),
    game_link          VARCHAR2(100)
  ) ;
ALTER TABLE games ADD CONSTRAINT games_PK PRIMARY KEY ( game_id ) ;
ALTER TABLE games ADD CONSTRAINT games_unique1 UNIQUE ( name ) ;

CREATE TABLE player
  (
    player_id               INTEGER NOT NULL ,
    first_name              VARCHAR2 (30) ,
    last_name               VARCHAR2 (30) ,
    username                VARCHAR2 (30) NOT NULL,
    email                   VARCHAR2 (50) NOT NULL,
    link                    VARCHAR2 (50) NOT NULL,
    rights                  INTEGER ,
    logged                  CHAR (1),
    relation                VARCHAR2(30),
    tutor_id                INTEGER
  ) ;
ALTER TABLE player ADD CONSTRAINT player_PK PRIMARY KEY ( player_id ) ;
--ALTER TABLE player ADD CONSTRAINT player_unique1 UNIQUE ( username ) ;
ALTER TABLE player ADD CONSTRAINT player_unique2 UNIQUE ( email ) ;

CREATE TABLE player_dates
  (
    player_id              INTEGER NOT NULL ,
    birthday               DATE ,
    difficulty             INTEGER ,
    recommended_difficulty INTEGER ,
    total_time             INTEGER
  ) ;
ALTER TABLE player_dates ADD CONSTRAINT player_dates_PK PRIMARY KEY ( player_id ) ;

CREATE TABLE player_activity
  (
    player_id         INTEGER NOT NULL ,
    logged_in         DATE NOT NULL,
    logged_out        DATE NOT NULL
  ) ;
ALTER TABLE player_activity ADD CONSTRAINT player_activity_PK PRIMARY KEY ( player_id, logged_in ) ;

CREATE TABLE player_stats
  (
    player_id         INTEGER NOT NULL ,
    domain_id         INTEGER NOT NULL ,
    highest_score     INTEGER ,
    total_score       INTEGER ,
    number_of_plays   INTEGER ,
    last_played       DATE
  ) ;
ALTER TABLE player_stats ADD CONSTRAINT player_stats_PK PRIMARY KEY ( domain_id, player_id ) ;

CREATE TABLE tw_questions
  (
    question_id                   INTEGER NOT NULL ,
    domain_id                     INTEGER ,
    question_text                 VARCHAR2 (200) ,
    difficulty                    INTEGER ,
    tip                          VARCHAR2(5)
  ) ;
ALTER TABLE tw_questions ADD CONSTRAINT questions_PK PRIMARY KEY ( question_id ) ;

CREATE TABLE questions_answers
  (
    question_id           INTEGER NOT NULL ,
    answer1               VARCHAR2 (200) ,
    answer2               VARCHAR2 (200) ,
    answer3               VARCHAR2 (200) ,
    correct_answer        VARCHAR2 (200)
  ) ;
ALTER TABLE questions_answers ADD CONSTRAINT questions_answers_PK PRIMARY KEY ( question_id ) ;

CREATE TABLE tutor
  (
    tutor_id            INTEGER NOT NULL ,
    first_name          VARCHAR2 (30) ,
    last_name           VARCHAR2 (30) ,
    username            VARCHAR2 (30) NOT NULL,
    email               VARCHAR2 (50) NOT NULL,
    link                VARCHAR2 (50) NOT NULL,
    rights              INTEGER
  ) ;
ALTER TABLE tutor ADD CONSTRAINT tutor_PK PRIMARY KEY ( tutor_id ) ;
--ALTER TABLE tutor ADD CONSTRAINT tutor_unique1 UNIQUE ( username ) ;
ALTER TABLE tutor ADD CONSTRAINT tutor_unique2 UNIQUE ( email ) ;

ALTER TABLE questions_answers ADD CONSTRAINT questions_answers_FK FOREIGN KEY ( question_id ) REFERENCES tw_questions ( question_id ) ON DELETE CASCADE;

ALTER TABLE tw_questions ADD CONSTRAINT questions_domains_FK FOREIGN KEY ( domain_id ) REFERENCES domains ( domain_id ) ON DELETE CASCADE;

ALTER TABLE games ADD CONSTRAINT games_domains_FK FOREIGN KEY ( domain_id ) REFERENCES domains ( domain_id ) ON DELETE CASCADE;

ALTER TABLE player_stats ADD CONSTRAINT player_stats_domains_FK FOREIGN KEY ( domain_id ) REFERENCES domains ( domain_id ) ON DELETE CASCADE;

ALTER TABLE player_stats ADD CONSTRAINT player_stats_player_FK FOREIGN KEY ( player_id ) REFERENCES player ( player_id ) ON DELETE CASCADE;

ALTER TABLE tests ADD CONSTRAINT tests_domains_FK FOREIGN KEY (domain_id) REFERENCES domains (domain_id) ON DELETE CASCADE;

ALTER TABLE achievements_link ADD CONSTRAINT achievements_player_FK FOREIGN KEY ( player_id ) REFERENCES player ( player_id ) ON DELETE CASCADE;

ALTER TABLE achievements_link ADD CONSTRAINT achievements_ach_FK FOREIGN KEY ( achievement_id ) REFERENCES achievements ( achievement_id ) ON DELETE CASCADE;

ALTER TABLE player_activity ADD CONSTRAINT player_activity_player_FK FOREIGN KEY ( player_id ) REFERENCES player ( player_id ) ON DELETE CASCADE;

ALTER TABLE player_dates ADD CONSTRAINT player_dates_player_FK FOREIGN KEY ( player_id ) REFERENCES player ( player_id ) ON DELETE CASCADE;

ALTER TABLE player ADD CONSTRAINT player_tutor_FK FOREIGN KEY ( tutor_id ) REFERENCES tutor ( tutor_id ) ON DELETE CASCADE;
/
--------------------------------------------------------------------------------------------------------------------------------insert 1

set serveroutput on;

DECLARE

BEGIN
  INSERT INTO achievements(achievement_id,name,description,icon_link) 
                  VALUES(1,'Fresh Meat','Create an account and log in.', 'c:\');
  insert into domains(domain_id, domain_name, number_of_questions, number_of_games, subject_type, icon_link) values (1, 'Istorie', 2, 1, 'Uman','d:\');
  insert into domains(domain_id, domain_name, number_of_questions, number_of_games, subject_type, icon_link) values (2, 'Geografie', 2, 1, 'Uman','d:\');
  insert into domains(domain_id, domain_name, number_of_questions, number_of_games, subject_type, icon_link) values (3, 'Matematica', 2, 1, 'Real','d:\');
  insert into domains(domain_id, domain_name, number_of_questions, number_of_games, subject_type, icon_link) values (4, 'Astronomie', 2, 1, 'Real','d:\');
  
  insert into games(game_id, name, difficulty, description, instructions,domain_id,icon_link,game_link) values (1, 'Game_1', 1, 'Descriere_1', 'Intructiuni_1',1,'d:\','d:\');
  
    --questions
  insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (1, 1, 'In ce an a inceput Primul Razboi Mondial?', 1, '0');
  insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (2, 1, 'In ce zi este Ziua Nationala a Romaniei?', 1, '0');
  
    --questions_answers
  insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (1, '1918', '1916', '1909', '1914');
  insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (2, '11 decembrie', '23 august', '24 ianuarie', '11 decembrie');
  
END;
/
--------------------------------------------------------------------------------------------------------------------------------insert 2

set serveroutput on;

DECLARE
  type vector is VARRAY(500) of VARCHAR2(500);
  nume vector;
  prenume vector;
  t_nume vector;
  t_prenume vector;
  v_id integer:=1;
  t_id integer:=1;
  i NUMBER:=1;
  l NUMBER:=1;
BEGIN
  nume:=vector('Abaza','Adamache','Adamescu','Adamelteanu','Aderca','Adoc','Afrim','Agaparian','Agarbiceanu','Agorbiceanu'
              ,'Albu','Albulescu','Aldulescu','Alexa','Alexandrescu','Alexe','Alexi','Alifantis','Almas','Almasan'
              ,'Aman','Amanar','Andoni','Andreoiu','Andries','Andronic','Angelescu','Anghel','Anghelescu','Anton','Antonescu'
              ,'Apostu','Ardelean','Ardeleanu','Argesanu','Argetoianu','Barca','Barcianu','Barlea','Barloiu','Barna'
              ,'Barsanescu','Barzin','Batraneanu','Bazon','Basescu','Barladeanu','Becali','Becheru','Bechet','Caiac'
              ,'Caiali','Caibulea','Caiceanu','Caimac','Caimacanu','Cain','Caisim','Caisin','Caitin','Cajal','Cal'
              ,'Calabalac','Calafateanu','Calafeteanu','Calagi','Calagiu','Calaican','Calalb','Calance','Calancea'
              ,'Calancia','Calangiu','Calapod','Calapodescu','Calborean','Calboreanu','Calcan','Calcea','Calcisca'
              ,'Calciu','Caldan','Caldaru','Caldes','Caleap','Caledoniu','Calefariu','Dacilescu','Dacinoi','Dadacus'
              ,'Dadai','Dadalan','Dadalau','Dadarlas','Dadarlat','Daduica','Dadulescu','Daduta','Daescu','Daesei'
              ,'Dagadita','Dagalita','Dagalitu','Dahalean','Daian','Daianu','Daiescu','Daineanu','Dalae','Dalalau'
              ,'Dalan','Dalcaran','Dalinda','Damaceanu','Damachianu','Damacus','Damaroiu','Damatar');
  prenume:=vector('Carla','Carmen','Carmina','Carolina','Casandra','Casiana','Caterina','Catinca','Catrina','Catalin'
                 ,'Cedrin','Cezar','Ciprian','Claudiu','Codin','Codrin','Codrut','Cornel','Corneliu','Corvin','Constantin','Cosmin'
                 ,'Costache','Costel','Costin','Crin','Cristea','Cristian','Cristobal','Cristofor','Catrinel','Catalina','Cecilia','Celia'
                 ,'Cerasela','Cezara','Cipriana','Clara','Clarisa','Claudia','Clementina','Cleopatra','Codrina','Codruta','Constantina','Constanta'
                 ,'Consuela','Coralia','Corina','Cornelia','Cosmina','Crenguta','Crina','Cristina','Daciana','Dafina','Daiana','Dalia','Dana','Daniela'
                 ,'Daria','Dariana','Delia','Demetra','Denisa','Despina','Diana','Dida','Didina','Dimitrina','Dina','Dochia','Doina','Domnica','Dora'
                 ,'Doriana','Dorina','Dorli','Draga','Dumitra','Dan','Daniel','Darius','David','Decebal','Denis','Dinu','Dominic','Dorel','Dorian','Dorin'
                 ,'Dorinel','Doru','Dragos','Ducu','Dumitru','Edgar','Edmond','Eduard', 'Emanuel', 'Elena', 'Emil', 'Emilia');
  t_prenume:=vector('Ducu','Dumitru','Edgar','Edmond','Magdalena','Maia','Manuela','Mara','Marcela','Marga','Margareta','Marcheta','Maria','Mariana','Maricica'
                   ,'Marilena','Marina','Marinela','Marioara','Marta','Matilda','Malvina','Masdalina','Moalina','M?rioara','M?riuca','Melania','Melina'
                   ,'Mihaela','Milena','Mina','Minodora','Mioara','Mirabela','Mirela','Mirona','Miruna','Mona','Monalisa','Monica','Nadia','Narcisa'
                   ,'Natalia','Natana','Noemi','Nicoleta','Niculina','Nidia','Nora','Savina','Senziana','Semenica','Severina','Sidonia','Silvia','Silvana'
                   ,'Silviana','Simina','Simona','Smaranda','Sofia','Sonia','Sorana','Sorina','Speransa','Stana','Stanca','Stela','Steliana','Stelu?a','Suzana'
                   ,'Svetlana','Teodor','Teofil','Teohari','Theodor','Tiberiu','Timotei','Titus','Todor','Toma','Traian','Tudor','Valentin','Valeriu','Valter'
                   ,'Vasile','Vasilica','Veniamin','Vicentiu','Victor','Vincentio','Viorel','Visarion','Vlad','Vladimir','Vlaicu','Voicu','Pavel','Patrut','Petre'
                   ,'Petricica','Petrisor','Petru');
  for i in 1..100 loop
    for l in 1..100 loop
      INSERT INTO tutor(tutor_id,first_name,last_name,username,email,link,rights) 
                  VALUES(t_id,nume(i),t_prenume(l),nume(i)||'.'||t_prenume(l)||'.t',nume(i)||'.'||t_prenume(l)||'@gmail.com', 'k', 2);
      INSERT INTO player(player_id,first_name,last_name,username,email,link,rights,logged,relation,tutor_id) 
                  VALUES(v_id,
                  nume(i),
                  prenume(l),
                  nume(i)||'.'||prenume(l),
                  nume(i)||'.'||prenume(l)||'@gmail.com',
                  'k',
                  1,
                  0,
                  'parinte',
                  1);
      INSERT INTO passwords(username,hash, random_string) 
                  VALUES(nume(i)||'.'||prenume(l),
                  nume(i)||'.'||prenume(l),
                  '1');
      INSERT INTO passwords(username,hash, random_string) 
                  VALUES(nume(i)||'.'||t_prenume(l)||'.t',
                  nume(i)||'.'||prenume(l)||'.t',
                  '1');
      INSERT INTO player_dates ( player_id, birthday, difficulty, recommended_difficulty, total_time)
                  VALUES ( v_id, 
                  TO_DATE('01/01/1995','dd/mm/yyyy'), 
                  4, 6, 
                  0);
      INSERT INTO tests(player_id, test_id, score) 
                  VALUES(v_id, v_id, 10);
      INSERT INTO player_stats(player_id, domain_id,highest_score,total_score,number_of_plays,last_played) 
                  VALUES(v_id, 1, 10,10,1,TO_DATE('01/01/1995','dd/mm/yyyy'));
      INSERT INTO player_activity(player_id, logged_in, logged_out) 
                  VALUES(v_id, TO_DATE('01/01/1995','dd/mm/yyyy'),TO_DATE('01/01/1995','dd/mm/yyyy'));
      v_id:=v_id+1;
      t_id:=t_id+1;
    end loop;
  end loop;
  INSERT INTO achievements_link(achievement_id,player_id) VALUES(1,1);
  
  INSERT INTO admins(admins_id,first_name,last_name,username,email,rights) VALUES(1,'Platon','Stefan','stefan_admin','platon.stefan97@gmail.com',3);
  INSERT INTO passwords(username,hash, random_string) VALUES('stefan_admin','7e7c8166ef7aae16dfcd1ee572aee0889319c61b','2105251273');
END;
/
DECLARE
  i integer;
  v_player_name varchar2(30);
  v_min_tutor_id integer;
  v_max_tutor_id integer;
  v_c integer;
BEGIN
  --update tests set test_type = ROUND(DBMS_RANDOM.VALUE(1,2));
  for i in 1 .. 10000 loop
    select min(tutor_id) into v_min_tutor_id from tutor;
    select max(tutor_id) into v_max_tutor_id from tutor;
    update player set tutor_id = round(dbms_random.value(v_min_tutor_id,v_max_tutor_id)) where player_id = i;
  end loop;
  for i in 1 .. 10000 loop
    EXECUTE IMMEDIATE 'select count(tutor_id) from player where tutor_id = :i' into v_c using i;
    if(v_c < 1) then
      EXECUTE IMMEDIATE 'delete from tutor where tutor_id = :i' using i;
    end if;
  end loop;
END;
/
update player_activity set logged_in = TO_DATE(TRUNC(DBMS_RANDOM.VALUE(TO_CHAR(TO_DATE('01/01/17','dd/mm/yy'),'J'),TO_CHAR(TO_DATE('16/05/17','dd/mm/yy'),'J'))),'J');
update player_activity set logged_out = logged_in + ROUND(DBMS_RANDOM.VALUE(0,4));
update player_dates set birthday = TO_DATE(TRUNC(DBMS_RANDOM.VALUE(TO_CHAR(TO_DATE('01/01/00','dd/mm/yy'),'J'),TO_CHAR(TO_DATE('31/12/11','dd/mm/yy'),'J'))),'J');


update player_dates set difficulty = 1 where birthday between TO_DATE('01/01/08','dd/mm/yy') and TO_DATE('31/12/11','dd/mm/yy');
update player_dates set difficulty = 2 where birthday between TO_DATE('01/01/04','dd/mm/yy') and TO_DATE('31/12/07','dd/mm/yy');
update player_dates set difficulty = 3 where birthday between TO_DATE('01/01/00','dd/mm/yy') and TO_DATE('31/12/03','dd/mm/yy');

update player_dates set recommended_difficulty = difficulty;
/
COMMIT;
