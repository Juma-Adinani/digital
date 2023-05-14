CREATE DATABASE digital_permission_db;

USE digital_permission_db;

CREATE TABLE roles(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL
);

##set default roles
INSERT INTO
    roles (name)
VALUES
    ('admin'),
    ('student'),
    ('class supervisor'),
    ('dean of faculty'),
    ('dean of school'),
    ('deputy dean of school');

CREATE TABLE users(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(40) NOT NULL,
    middlename VARCHAR(40) NULL,
    lastname VARCHAR(40) NOT NULL,
    gender ENUM('MALE', 'FEMALE'),
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles (id) ON UPDATE CASCADE
);

CREATE TABLE faculties(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    faculty_code VARCHAR(20) NOT NULL,
    faculty_name VARCHAR(30) NOT NULL
);

##set faculties
INSERT INTO
    faculties (faculty_code, faculty_name)
VALUES
    ('FST', 'FACULTY OF SCIENCE AND TECHNOLOGY'),
    ('FSS', 'FACULTY OF SOCIAL SCIENCE'),
    ('SOB', 'SCHOOL OF BUSINESS'),
    ('FOL', 'FACULTY OF LAW'),
    (
        'SOPAM',
        'SCHOOL OF PUBLIC ADMINISTRATION AND MANAGEMENT'
    );

CREATE TABLE departments (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    dept_code VARCHAR(30) NOT NULL,
    dept_name VARCHAR(30) NOT NULL,
    faculty_id INT NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculties (id) ON UPDATE CASCADE
);

CREATE TABLE programmes (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    program_code VARCHAR(30) NOT NULL,
    programme_name VARCHAR(30) NOT NULL,
    dept_id INT NOT NULL,
    FOREIGN KEY (dept_id) REFERENCES departments (id) ON UPDATE CASCADE
);

CREATE TABLE education_levels(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    level VARCHAR(50) NOT NULL
);

##set default levels of education
INSERT INTO
    education_levels (level)
VALUES
    ('Certificate'),
    ('Degree'),
    ('Masters'),
    ('PhD');

CREATE TABLE students (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    reg_no VARCHAR(50) NOT NULL UNIQUE,
    program_id INT NOT NULL,
    level_id INT NOT NULL,
    year_of_study ENUM('FIRST YEAR', 'SECOND YEAR', 'THIRD YEAR'),
    user_id INT NOT NULL,
    FOREIGN KEY (program_id) REFERENCES programmes (id) ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (level_id) REFERENCES education_levels (id) ON UPDATE CASCADE
);

CREATE TABLE class_supervisors(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (program_id) REFERENCES programmes (id) ON UPDATE CASCADE
);

CREATE TABLE dean_faculties(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    faculty_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (faculty_id) REFERENCES faculties (id) ON UPDATE CASCADE
);

CREATE TABLE reason_types(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL
);

##set default reason_types
INSERT INTO
    reason_types(type)
VALUES
    ('medical reasons'),
    ('social reasons');

CREATE TABLE reasons_for_leave(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    reason TEXT NOT NULL,
    reason_type_id INT NOT NULL,
    supportive_doc TEXT NULL,
    student_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (reason_type_id) REFERENCES reason_types (id) ON UPDATE CASCADE
);

CREATE TABLE session_types(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(100) NOT NULL
);

##set default sessions_to_be_conducted_when_he_or_she_is_away
INSERT INTO
    session_types (type)
VALUES
    ('Period session'),
    ('Tutorial session'),
    ('Assignment'),
    ('Test'),
    ('Examination');

CREATE TABLE away_sessions(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    away_session_uuid VARCHAR(200) NOT NULL UNIQUE,
    session_type_id INT NOT NULL,
    course VARCHAR(10) NOT NULL,
    lecturer VARCHAR(50) NOT NULL,
    commence_date VARCHAR(30) NOT NULL,
    reason_id INT NOT NULL,
    FOREIGN KEY (reason_id) REFERENCES reasons_for_leave (id) ON UPDATE CASCADE
);

CREATE TABLE student_leaves(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    departure_date VARCHAR(50) NOT NULL,
    return_date VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    place_of_visit VARCHAR(60) NOT NULL,
    reason_id INT NOT NULL,
    FOREIGN KEY (reason_id) REFERENCES reasons_for_leave (id) ON UPDATE CASCADE
);

CREATE TABLE supervisor_recommendations(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    s_remarks TEXT NOT NULL,
    s_remark_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    leave_id INT NOT NULL,
    FOREIGN KEY (leave_id) REFERENCES student_leaves (id) ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE dof_recommendations(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    dof_remarks TEXT NOT NULL,
    dof_remark_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    s_remarks_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (s_remarks_id) REFERENCES supervisor_recommendations (id) ON UPDATE CASCADE
);

CREATE TABLE dos_recommendations(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    dos_remarks TEXT NOT NULL,
    dos_remark_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    dof_remarks_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE,
    FOREIGN KEY (dof_remarks_id) REFERENCES dof_recommendations (id) ON UPDATE CASCADE
);

CREATE TABLE student_leave_reports(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    dos_remarks_id INT NOT NULL,
    report_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dos_remarks_id) REFERENCES dos_recommendations (id) ON UPDATE CASCADE
);
