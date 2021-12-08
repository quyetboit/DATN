--db
CREATE DATABASE QL_Nhac
USE QL_Nhac

--table
--table THE_LOAI
CREATE TABLE genres
(
	id int AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    thumb varchar(200) DEFAULT 'genres_default.jpg',
    CONSTRAINT PK_genres PRIMARY KEY (id)
)

--table artists
CREATE TABLE artists
(
	id int AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    thumb varchar(200) DEFAULT 'default_artist.jpg',
    descript text,
    CONSTRAINT PK_artists PRIMARY KEY (id)
)

--table songs
CREATE TABLE songs
(
	id int AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    thumb varchar(200) DEFAULT 'default_songs.jpg',
    audio varchar(250) NOT NULL,
    time varchar(10),
    num_plays int DEFAULT 0,
    num_download int DEFAULT 0,
    id_genre int,
    CONSTRAINT PK_songs PRIMARY KEY (id),
    CONSTRAINT FK_Songs_to_genres FOREIGN KEY (id_genre) REFERENCES genres(id)
)

--table detail_songs
CREATE TABLE detail_songs
(
	id_artist int,
    id_song int,
    CONSTRAINT PK_detail_songs PRIMARY KEY (id_artist, id_song),
    CONSTRAINT FK_detail_songs_to_songs FOREIGN KEY (id_song) REFERENCES songs(id),
    CONSTRAINT FK_detail_songs_to_artists FOREIGN KEY (id_artist) REFERENCES artists(id)
)

--table accounts
CREATE TABLE accounts
(
	username varchar(30),
    password_account varchar(200) NOT NULL,
    fullname varchar(50),
    email varchar(100),
    avatar varchar(200) DEFAULT 'default_avatar.jpg',
    CONSTRAINT PK_account PRIMARY KEY (username)
)

--table album
CREATE TABLE albums
(
	id int AUTO_INCREMENT,
    name varchar(100),
    username_accounts varchar(50),
    CONSTRAINT PK_albums PRIMARY KEY (id),
    CONSTRAINT FK_albums_to_accounts FOREIGN KEY (username_accounts) REFERENCES accounts(username)
)

--table detail_album
CREATE TABLE detail_albums
(
	id_song int,
    id_album int,
    CONSTRAINT PK_detail_albums PRIMARY KEY (id_song, id_album),
    CONSTRAINT FK_detail_albums_to_song FOREIGN KEY (id_song) REFERENCES songs(id),
    CONSTRAINT FK_detail_albums_to_albums FOREIGN KEY (id_album) REFERENCES albums(id)
)

--table comments
CREATE TABLE comments
(
	id int AUTO_INCREMENT,
    content text,
    CONSTRAINT PK_comment PRIMARY KEY (id)
)

-- table detail comments
CREATE TABLE detail_comments
(
	username_account varchar(30),
    id_song int,
    id_comment int,
    CONSTRAINT PK_detail_comments PRIMARY KEY (username_account, id_song, id_comment),
    CONSTRAINT FK_comments_to_account FOREIGN KEY (username_account) REFERENCES accounts(username),
    CONSTRAINT FK_comments_to_song FOREIGN KEY (id_song) REFERENCES songs(id),
    CONSTRAINT FK_comments_to_comment FOREIGN KEY (id_comment) REFERENCES comments(id)
)

--table songs_người dùng
CREATE TABLE songs_user
(
	id int AUTO_INCREMENT,
    name varchar(50),
    thumb varchar(250) DEFAULT 'default_song.jpg',
    audio varchar(250) NOT NULL,
    time varchar(10),
    username_account varchar(30),
    CONSTRAINT PK_songs_user PRIMARY KEY (id),
    CONSTRAINT FK_songs_user_to_account FOREIGN KEY (username_account) REFERENCES accounts(username)
)

--admin
CREATE TABLE admin
(
	adminname varchar(30),
    password_admin varchar(200),
    fullname varchar(50),
    email varchar(100),
    avatar varchar(250) DEFAULT 'default_avatar.jpg',
    CONSTRAINT PK_admin PRIMARY KEY (adminname)
)