--sterg toti indecsii creati
ALTER TABLE tutor drop CONSTRAINT tutor_unique1;
DROP INDEX tutor_username;
ALTER TABLE player drop CONSTRAINT player_unique1;
DROP INDEX player_username;
ALTER TABLE admins drop CONSTRAINT admins_unique1;
DROP INDEX admin_username;
DROP INDEX games_cauta;
DROP INDEX achievements_cauta;
DROP INDEX player_stats_total;
/
--indecsi pentru select(clauza where)
CREATE INDEX tutor_username ON tutor (username);
ALTER TABLE tutor ADD CONSTRAINT tutor_unique1 UNIQUE ( username ) ;
--select tutor_id from tutor where username = 'Albu.Moalina.t'

CREATE INDEX player_username ON player (username);
ALTER TABLE player ADD CONSTRAINT player_unique1 UNIQUE ( username ) ;

CREATE INDEX admin_username ON admins (username);
ALTER TABLE admins ADD CONSTRAINT admins_unique1 UNIQUE ( username ) ;
/
--indecsi pentru functii
CREATE INDEX games_cauta ON games (LOWER(name));
CREATE INDEX achievements_cauta ON achievements (LOWER(name));
--SELECT g.game_id, g.name, g.difficulty, g.description, g.instructions, g.domain_id from games g join domains d on d.domain_id = g.domain_id where LOWER(g.name) = 'game_1'
/
--indecsi pentru order by
CREATE INDEX player_stats_total ON player_stats (total_score);
--select player_id, total_score from (select player_id, total_score from player_stats order by total_score desc) where rownum <= 10
/
--indecsi pentru comparare si between
CREATE INDEX player_dates_birthday ON player_dates (birthday);
--update player_dates set difficulty = 1 where birthday between TO_DATE('01/01/08','dd/mm/yy') and TO_DATE('31/12/11','dd/mm/yy')
/
--view pt popularea paginii de profil
CREATE MATERIALIZED VIEW player_profile_view
     BUILD IMMEDIATE
     REFRESH COMPLETE ON COMMIT
     AS SELECT pl.username, pl.last_name, pl.first_name, pl.email, pd.birthday, pd.difficulty, pd.total_time, pa.logged_in, pa.logged_out, pl.ROWID as PL_ROWID, pd.ROWID as PD_ROWID, pa.ROWID as PA_ROWID 
        FROM player pl, player_dates pd, player_activity pa 
        WHERE pl.player_id = pd.player_id and pa.player_id = pl.player_id;
/
--update player_profile_view set username = '1'
execute DBMS_MVIEW.REFRESH( LIST => 'player_profile_view', METHOD => 'C' );
