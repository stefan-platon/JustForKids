CREATE OR REPLACE TRIGGER delete_question_answer
after delete on tw_questions
for each row
BEGIN
  delete from questions_answers where questions_answers.question_id = :old.question_id;
END;
/
CREATE OR REPLACE TRIGGER delete_questions
after delete on domains
for each row
BEGIN
  delete from tw_questions where tw_questions.domain_id = :old.domain_id;
END;
/
CREATE OR REPLACE TRIGGER delete_games
after delete on domains
for each row
BEGIN
  delete from games where games.domain_id = :old.domain_id;
END;
/
CREATE OR REPLACE TRIGGER player_stats_from_domains
after delete on domains
for each row
BEGIN
  delete from player_stats where player_stats.domain_id = :old.domain_id;
END;
/
CREATE OR REPLACE TRIGGER player_stats_from_player
after delete on player
for each row
BEGIN
  delete from player_stats where player_stats.player_id = :old.player_id;
END;
/
CREATE OR REPLACE TRIGGER achievements_link_from_player
after delete on player
for each row
BEGIN
  delete from achievements_link where achievements_link.player_id = :old.player_id;
END;
/
CREATE OR REPLACE TRIGGER ach_link_from_achievements
after delete on achievements
for each row
BEGIN
  delete from achievements_link where achievements_link.achievement_id = :old.achievement_id;
END;
/
CREATE OR REPLACE TRIGGER player_activity_from_player
after delete on player
for each row
BEGIN
  delete from player_activity where player_activity.player_id = :old.player_id;
END;
/
CREATE OR REPLACE TRIGGER player_dates_from_player
after delete on player
for each row
BEGIN
  delete from player_dates where player_dates.player_id = :old.player_id;
END;
/
CREATE OR REPLACE TRIGGER player_from_tutor
after delete on tutor
for each row
BEGIN
  delete from player where player.tutor_id = :old.tutor_id;
END;
/
CREATE OR REPLACE TRIGGER tutor_from_player
after delete on player
for each row
BEGIN
  delete from tutor where tutor.tutor_id = :old.tutor_id;
END;