--intrebarile
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (1, 1, 'In ce an a inceput Primul Razboi Mondial?', 1, '0');
/
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (2, 1, 'In ce an s-a destramat Uniunea Sovietica?', 1, '0');
/
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (3, 1, 'In ce zi a lunii decembrie este Ziua Nationala a Romaniei?', 1, '0');
/
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (4, 1, 'In ce an a inceput Al Doilea Razboi Mondial?', 1, '0');
/
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (5, 1, 'In ce an a fost revolutia in urma careia a cazut comunismul in Romania?', 1, '0');
/
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (6, 1, 'In ce an a fost semnat Pactul Ribbentrop-Molotov?', 1, '0');
/
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (7, 1, 'In ce an a abdicat Domnitorul Alexandru Ioan Cuza?', 1, '0');
/
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (8, 1, 'In ce an a intrat in vigoare Tratatul de la Trianon?', 1, '0');
/
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (9, 1, 'Aproximativ in ce an inainte de Hristos a fost fondat Regatul Roman?', 1, '0');
/
insert into tw_questions(question_id, domain_id, question_text, difficulty, tip)
  values (10, 1, 'Cati ani a condus Augustus, primul imparat al Romei, Imperiul Roman?', 1, '0');
/
--raspunsurile
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (1, '1918', '1916', '1909', '1914');/
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (2, '1999', '1989', '1919', '1991');/
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (3, '10', '6', '25', '1');/
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (4, '1949', '1945', '1936', '1939');/
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (5, '1999', '1945', '1991', '1989');/
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (6, '1941', '1936', '1945', '1939');/
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (7, '1857', '1860', '1859', '1866');/
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (8, '1918', '1919', '1920', '1921');/
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (9, '754', '752', '172', '753');/
insert into questions_answers(question_id, answer1, answer2, answer3, correct_answer)
  values (10, '41', '36', '39', '40');
COMMIT;