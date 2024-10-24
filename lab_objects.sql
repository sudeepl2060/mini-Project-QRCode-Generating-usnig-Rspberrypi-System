
create database lab_objects;
use lab_objects;

CREATE TABLE colleges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    college_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    FOREIGN KEY (college_id) REFERENCES colleges(id) on delete cascade
);

CREATE TABLE labs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) on delete cascade
);

CREATE TABLE systems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lab_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    FOREIGN KEY (lab_id) REFERENCES labs(id) on delete cascade
);

CREATE TABLE components (
    id INT AUTO_INCREMENT PRIMARY KEY,
    system_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    qr_code_path VARCHAR(255),
    FOREIGN KEY (system_id) REFERENCES systems(id) on delete cascade
);

CREATE TABLE campus_parts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    college_id int,
    name VARCHAR(255) NOT NULL,
    FOREIGN KEY (college_id) REFERENCES colleges(id) on delete cascade
);

CREATE TABLE trees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campus_part_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    details TEXT,
    qr_code_path VARCHAR(255),
    FOREIGN KEY (campus_part_id) REFERENCES campus_parts(id) on delete cascade
);

insert into colleges values (1,"Government Engineering College Hassan");
insert into departments(id,college_id,name) values (1,1,"Civil Engineering"),(2,1,"Computer Science and Engineering"),(3,1,"Electronics and Communication Engineering"),(4,1,"Mechanical Engineering");
insert into labs(id,department_id,name) values (201,2,"Lab 1"),(202,2,"Lab 2"),(203,2,"Infosys Lab"),(204,1,"Lab 1"),(205,1,"Lab 2"),(206,3,"Lab 1"),(207,3,"Lab 2"),(208,4,"CAD Lab");
insert into systems(id,lab_id,name) values (1,201,"System 1"),(2,201,"System 2"),(3,201,"System 3"),(4,202,"System 1"),(5,202,"System 2"),(6,203,"System 1"),(7,203,"System 2"),(8,204,"System 1"),(9,204,"System 2"),(10,205,"System 1"),(11,205,"System 2"),(12,206,"System 1"),(13,206,"System 2"),(14,207,"System 1"),(15,207,"System 2"),(16,208,"System 1"),(17,208,"System 2");
insert into components(system_id,name) values (1,"Mouse"),(1,"Monitor"),(1,"Keyboard"),(1,"CPU"),(2,"Mouse"),(2,"Monitor"),(2,"Keyboard"),(2,"CPU"),(3,"Mouse"),(3,"Monitor"),(3,"Keyboard"),(3,"CPU"),(4,"Mouse"),(4,"Monitor"),(4,"Keyboard"),(4,"CPU"),(5,"Mouse"),(5,"Monitor"),(5,"Keyboard"),(5,"CPU"),(6,"Mouse"),(6,"Monitor"),(6,"Keyboard"),(6,"CPU"),(7,"Mouse"),(7,"Monitor"),(7,"Keyboard"),(7,"CPU"),(8,"Mouse"),(8,"Monitor"),(8,"Keyboard"),(8,"CPU"),(9,"Mouse"),(9,"Monitor"),(9,"Keyboard"),(9,"CPU"),(10,"Mouse"),(10,"Monitor"),(10,"Keyboard"),(10,"CPU"),(11,"Mouse"),(11,"Monitor"),(11,"Keyboard"),(11,"CPU"),(12,"Mouse"),(12,"Monitor"),(12,"Keyboard"),(12,"CPU"),(13,"Mouse"),(13,"Monitor"),(13,"Keyboard"),(13,"CPU"),(14,"Mouse"),(14,"Monitor"),(14,"Keyboard"),(14,"CPU"),(15,"Mouse"),(15,"Monitor"),(15,"Keyboard"),(15,"CPU"),(16,"Mouse"),(16,"Monitor"),(16,"Keyboard"),(16,"CPU"),(17,"Mouse"),(17,"Monitor"),(17,"Keyboard"),(17,"CPU");
insert into campus_parts(id,college_id,name) values (1001,1,"Front part"),(1002,1,"Library campas");
insert into trees(campus_part_id,name,details) values (1001,"Jackfruit tree","Binomial name is Artocarpus heteropyllus, moraceae family"),(1001,"Purplefruit tree","Binomial name is Passiflora edulis, Passifloraceae family"),(1001,"Mango tree","Binomial name is Mangifera indica, Anacardiaceae family"),(1001,"Sapota tree","Binomial name is Manilkara zapota, Sapotaceae family"),(1002,"Jackfruit tree","Binomial name is Artocarpus heteropyllus, moraceae family"),(1002,"Purplefruit tree","Binomial name is Passiflora edulis, Passifloraceae family"),(1002,"Mango tree","Binomial name is Mangifera indica, Anacardiaceae family"),(1002,"Sapota tree","Binomial name is Manilkara zapota, Sapotaceae family");
