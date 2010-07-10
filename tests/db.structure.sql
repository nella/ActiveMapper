CREATE TABLE "authors" (
	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	"name" VARCHAR(30) NOT NULL,
	"web" VARCHAR(100) NOT NULL
);


CREATE TABLE "tags" (
	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	"name" VARCHAR(20) NOT NULL
);

CREATE TABLE "applications" (
	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	"author_id" INTEGER NULL,
	"title" VARCHAR(50) NOT NULL,
	"web" VARCHAR(100) NOT NULL,
	"slogan" VARCHAR(100) NOT NULL,
	CONSTRAINT "applications_ibfk_1" FOREIGN KEY ("author_id") REFERENCES "authors" ("id")
);

CREATE INDEX "application_title" ON "applications" ("title");

CREATE TABLE "applications_tags" (
	"application_id" INTEGER NOT NULL,
	"tag_id" INTEGER NOT NULL,
	PRIMARY KEY ("application_id","tag_id"),
	CONSTRAINT "applications_tags_ibfk_3" FOREIGN KEY ("tag_id") REFERENCES "tags" ("id"),
	CONSTRAINT "applications_tags_ibfk_2" FOREIGN KEY ("application_id") REFERENCES "applications" ("id") ON DELETE CASCADE
);

CREATE TABLE "blogs" (
	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	"author_id" INTEGER NULL,
	"name" VARCHAR(50) NOT NULL,
	"url" VARCHAR(100) NOT NULL,
	CONSTRAINT "blogs_ibfk_4" FOREIGN KEY ("author_id") REFERENCES "authors" ("id")
);