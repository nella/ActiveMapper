INSERT INTO authors (id, name, web) VALUES (1, 'Jakub Vrana', 'http://www.vrana.cz/');
INSERT INTO authors (id, name, web) VALUES (2, 'David Grudl', 'http://davidgrudl.com/');
INSERT INTO authors (id, name, web) VALUES (3, 'Patrik Votoček', 'http://patrik.votocek.cz/');
INSERT INTO authors (id, name, web) VALUES (4, 'Jan Novák', 'http://example.com/');
INSERT INTO authors (id, name, web) VALUES (5, 'Tomáš Marný', 'http://example.com/');

INSERT INTO tags (id, name) VALUES (1, 'PHP');
INSERT INTO tags (id, name) VALUES (2, 'MySQL');
INSERT INTO tags (id, name) VALUES (3, 'SQLite');
INSERT INTO tags (id, name) VALUES (4, 'JavaScript');

INSERT INTO applications (id, author_id, title, web, slogan) VALUES (1, 1, 'Adminer', 'http://www.adminer.org/', 'Database management in single PHP file');
INSERT INTO applications (id, author_id, title, web, slogan) VALUES (2, 1, 'JUSH', 'http://jush.sourceforge.net/', 'JavaScript Syntax Highlighter');
INSERT INTO applications (id, author_id, title, web, slogan) VALUES (3, 2, 'Nette', 'http://nettephp.com/', 'Nette Framework for PHP 5');
INSERT INTO applications (id, author_id, title, web, slogan) VALUES (4, 2, 'Dibi', 'http://dibiphp.com/', 'Database Abstraction Library for PHP 5');
INSERT INTO applications (id, author_id, title, web, slogan) VALUES (5, 3, 'Nella', 'http://nellacms.com/', 'Tiny & simple CMS based Nette Framework');
INSERT INTO applications (id, author_id, title, web, slogan) VALUES (6, 3, 'ActiveMapper', 'http://addons.nette.org/cs/active-mapper', 'Another dibi ORM');

INSERT INTO applications_tags (application_id, tag_id) VALUES (1, 1);
INSERT INTO applications_tags (application_id, tag_id) VALUES (3, 1);
INSERT INTO applications_tags (application_id, tag_id) VALUES (4, 1);
INSERT INTO applications_tags (application_id, tag_id) VALUES (1, 2);
INSERT INTO applications_tags (application_id, tag_id) VALUES (4, 2);
INSERT INTO applications_tags (application_id, tag_id) VALUES (6, 1);
INSERT INTO applications_tags (application_id, tag_id) VALUES (6, 2);
INSERT INTO applications_tags (application_id, tag_id) VALUES (6, 3);
INSERT INTO applications_tags (application_id, tag_id) VALUES (5, 1);
INSERT INTO applications_tags (application_id, tag_id) VALUES (5, 2);
INSERT INTO applications_tags (application_id, tag_id) VALUES (5, 4);
INSERT INTO applications_tags (application_id, tag_id) VALUES (2, 4);

INSERT INTO blogs (id, author_id, name, url) VALUES (1, 1, 'PHP triky', 'http://php.vrana.cz');
INSERT INTO blogs (id, author_id, name, url) VALUES (2, 2, 'LaTrine', 'http://latrine.cz');
INSERT INTO blogs (id, author_id, name, url) VALUES (3, 3, 'Osobní WeBlog Patrika Votočka', 'http://www.vrtak-cz.net');