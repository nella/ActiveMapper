CREATE TABLE authors(
	`id`	INTEGER	PRIMARY KEY	NOT NULL,
	`name`	VARCHAR	NOT NULL
);

CREATE TABLE articles(
	`id`	INTEGER	PRIMARY KEY	NOT NULL,
	`title`	VARCHAR	NOT NULL,
	`content`	TEXT	NOT NULL,
	`author_id`	INTEGER NOT NULL,
	`create`  DATETIME,
	`price`  DOUBLE,
	`public`  BOOLEAN,
	FOREIGN KEY(author_id) REFERENCES authors(id)
);

CREATE TABLE profiles(
	`author_id`	INTEGER	NOT NULL,
	`web`	VARCHAR	NOT NULL,
	FOREIGN KEY(author_id) REFERENCES authors(id)
);

CREATE TABLE tags(
	`id`	INTEGER	PRIMARY KEY	NOT NULL,
	`name`	VARCHAR	NOT NULL
);

CREATE TABLE articles_tags(
	`article_id`	INTEGER	NOT NULL,
	`tag_id`	INTEGER	NOT NULL,
	FOREIGN KEY(article_id) REFERENCES articles(id),
	FOREIGN KEY(tag_id) REFERENCES tags(id)
);