CREATE TABLE authors (
	id int NOT NULL,
	name varchar(30) NOT NULL,
	web varchar(100) NOT NULL,
	PRIMARY KEY (id)
);


CREATE TABLE tags (
	id int NOT NULL,
	name varchar(20) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE applications (
	id int NOT NULL,
	author_id int NOT NULL,
	title varchar(50) NOT NULL,
	web varchar(100) NOT NULL,
	slogan varchar(100) NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT applications_ibfk_1 FOREIGN KEY (author_id) REFERENCES authors (id)
);

CREATE INDEX application_title ON applications (title);

CREATE TABLE applications_tags (
	application_id int NOT NULL,
	tag_id int NOT NULL,
	PRIMARY KEY (application_id,tag_id),
	CONSTRAINT applications_tags_ibfk_3 FOREIGN KEY (tag_id) REFERENCES tags (id),
	CONSTRAINT applications_tags_ibfk_2 FOREIGN KEY (application_id) REFERENCES applications (id) ON DELETE CASCADE
);

CREATE TABLE blogs (
	id int NOT NULL,
	author_id int NOT NULL,
	name varchar(50) NOT NULL,
	url varchar(100) NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT blogs_ibfk_4 FOREIGN KEY (author_id) REFERENCES authors (id)
);