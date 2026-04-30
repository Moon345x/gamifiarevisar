CREATE DATABASE  IF NOT EXISTS dataapp;
use dataapp;

CREATE TABLE client 
(
    id_student INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    mail VARCHAR(50) NOT NULL,
    passwrd VARCHAR(60) NOT NULL,
    type_user INT(1) NOT NULL
);

CREATE TABLE institution
(
    id_institut INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ins_name TEXT NOT NULL,
    ins_description VARCHAR(50) 
);

CREATE TABLE quiz_general
(
    id_student INT NOT NULL,
    isntitucion VARCHAR(50),
    genero VARCHAR(10),
    grado VARCHAR(20),
    r_edad VARCHAR(10),
    PRIMARY KEY (id_student),
    FOREIGN KEY (id_student) REFERENCES client(id_student)

);


CREATE TABLE plataform_rate
(
    id_student INT NOT NULL,
    plataform VARCHAR(20) NOT NULL,
    PRIMARY KEY (id_student,plataform),
    FOREIGN KEY (id_student) REFERENCES client(id_student)
);

CREATE TABLE quiz_learn_styles
(
    id_student INT NOT NULL, 
    p1 VARCHAR(2), 
    p2 VARCHAR(2),
    p3 VARCHAR(2),
    p4 VARCHAR(2),
    p5 VARCHAR(2),
    p6 VARCHAR(2),
    p7 VARCHAR(2),
    p8 VARCHAR(2),
    p9 VARCHAR(2),
    p10 VARCHAR(2),
    p11 VARCHAR(2),
    p12 VARCHAR(2),
    p13 VARCHAR(2),
    p14 VARCHAR(2),
    p15 VARCHAR(2),
    p16 VARCHAR(2),
    p17 VARCHAR(2),
    p18 VARCHAR(2),
    p19 VARCHAR(2),
    p20 VARCHAR(2),
    p21 VARCHAR(2),
    p22 VARCHAR(2),
    p23 VARCHAR(2),
    p24 VARCHAR(2),
    p25 VARCHAR(2),
    p26 VARCHAR(2),
    p27 VARCHAR(2),
    p28 VARCHAR(2),
    p29 VARCHAR(2),
    p30 VARCHAR(2),
    p31 VARCHAR(2),
    p32 VARCHAR(2),
    p33 VARCHAR(2),
    p34 VARCHAR(2),
    p35 VARCHAR(2),
    p36 VARCHAR(2),
    p37 VARCHAR(2),
    p38 VARCHAR(2),
    p39 VARCHAR(2),
    p40 VARCHAR(2),
    p41 VARCHAR(2),
    p42 VARCHAR(2),
    p43 VARCHAR(2),
    p44 VARCHAR(2),

    PRIMARY KEY (id_student),
    FOREIGN KEY (id_student) REFERENCES client(id_student)
);

CREATE TABLE quiz_learn_styles_rs
(
    id_student INT NOT NULL,
    perception VARCHAR(6) NOT NULL,
    perception_val INT(3) NOT NULL,
    input VARCHAR(6) NOT NULL,
    input_val INT(3) NOT NULL,
    processes VARCHAR(6) NOT NULL,
    processes_val INT(3) NOT NULL,
    understand VARCHAR(6) NOT NULL,
    understand_val INT(3) NOT NULL,
    
    PRIMARY KEY (id_student),
    FOREIGN KEY (id_student) REFERENCES client(id_student)
);

CREATE TABLE quiz_type_players
(
    id_student INT NOT NULL,
    p1 int(3),
    p2 int(3),
    p3 int(3),
    p4 int(3),
    p5 int(3),
    p6 int(3),
    p7 int(3),
    p8 int(3),
    p9 int(3),
    p10 int(3),
    p11 int(3),
    p12 int(3),
    p13 int(3),
    p14 int(3),
    p15 int(3),
    p16 int(3),
    p17 int(3),
    p18 int(3),
    p19 int(3),
    p20 int(3),
    p21 int(3),
    p22 int(3),
    p23 int(3),
    p24 int(3),

    PRIMARY KEY (id_student),
    FOREIGN KEY (id_student) REFERENCES client(id_student)

);

CREATE TABLE quiz_type_players_rs
(
    id_student INT NOT NULL,
    
    philanthrop DECIMAL(12,8) NOT NULL, 
    socialiser DECIMAL(12,8) NOT NULL,
    free_spirit DECIMAL(12,8) NOT NULL,
    achiever DECIMAL(12,8) NOT NULL,
    player DECIMAL(12,8) NOT NULL, 
    disruptor DECIMAL(12,8) NOT NULL,

    PRIMARY KEY (id_student),
    FOREIGN KEY (id_student) REFERENCES client(id_student)
);

INSERT INTO client (username, mail, passwrd, type_user) VALUES ( 'admin', 'manejodedatos2020@gmail.com', '123456789admin.', 1);

