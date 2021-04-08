<?php 

$resetSql = 'DROP DATABASE IF EXISTS anime;
CREATE DATABASE anime;
use anime;
CREATE TABLE season_statuses (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    alias VARCHAR(255),
    name VARCHAR(255)
);
CREATE TABLE type (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    alias VARCHAR(255),
    name VARCHAR(255)
);
CREATE TABLE genre (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    alias VARCHAR(255),
    name VARCHAR(255)
);
CREATE TABLE anime_show (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    anime_name VARCHAR(255),
    type_id INT NOT NULL,
    CONSTRAINT fk_type_id FOREIGN KEY (type_id) REFERENCES type(id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE show_has_genre(
    show_id INT NOT NULL,
    CONSTRAINT fk_show_id FOREIGN KEY (show_id) REFERENCES anime_show(id),
    genre_id INT NOT NULL,
    CONSTRAINT fk_genre_id FOREIGN KEY (genre_id) REFERENCES genre(id) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE seasons (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    show_id INT NOT NULL,
    season_status_id INT NOT NULL,
    season_no INT,
    dub_name VARCHAR(255),
    description LONGTEXT,
    episodes INT,
    release_date DATE,
    licensors VARCHAR(255),
    rating DOUBLE,
    link VARCHAR(255),
    CONSTRAINT fk_anime_show_id FOREIGN KEY (show_id) REFERENCES anime_show(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_season_status_id FOREIGN KEY (season_status_id) REFERENCES season_statuses(id) ON UPDATE CASCADE ON DELETE CASCADE
);
INSERT INTO season_statuses(alias, name)
Values("ongoing", "Ongoing"),
    ("complete", "Complete");
INSERT INTO type(alias, name)
VALUES("shonen", "Shonen"),
    ("shojo", "Shojo"),
    ("seinen", "Seinen"),
    ("josei", "Josei"),
    ("kodomomuke", "Kodomomuke");
INSERT INTO genre(alias, name)
VALUES("action", "Action"),
    ("comedy", "Comedy"),
    ("slice_of_life", "Slice-of-Life"),
    ("drama", "Drama"),
    ("psychological", "Psychological"),
    ("history", "History"),
    ("military", "Military"),
    ("supernatural", "Supernatural"),
    ("romance", "Romance");
INSERT INTO anime_show(anime_name, type_id)
Values ("Jujutsu Kaisen", 1);
INSERT INTO seasons(
        show_id,
        season_status_id,
        season_no,
        dub_name,
        description,
        episodes,
        release_date,
        licensors,
        rating,
        link
    )
VALUES (
        1,
        2,
        1,
        "Sorcery Fight",
        "Idly indulging in baseless paranormal activities with the Occult Club, high schooler Yuuji Itadori spends his days at either the clubroom or the hospital, where he visits his bedridden grandfather. However, this leisurely lifestyle soon takes a turn for the strange when he unknowingly encounters a cursed item. Triggering a chain of supernatural occurrences, Yuuji finds himself suddenly thrust into the world of Curses—dreadful beings formed from human malice and negativity—after swallowing the said item, revealed to be a finger belonging to the demon Sukuna Ryoumen, the King of Curses.",
        24,
        "2020/10/03",
        "Unknown",
        10,
        "gogoanime.movie"
    )';