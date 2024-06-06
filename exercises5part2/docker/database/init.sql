create database if not exists myDatabase;
use myDatabase;

create table if not exists cars (
    id int primary key auto_increment,
    model varchar(255),
    brand varchar(255),
    price decimal(10, 2),
    year int,
    description text);

insert into cars (model, brand, price, year, description) values ('A3', 'Audi', 35000.00, 2019, 'Audi A3 2019');
insert into cars (model, brand, price, year, description) values ('A4', 'Audi', 40000.00, 2019, 'Audi A4 2019');
insert into cars (model, brand, price, year, description) values ('A5', 'Audi', 45000.00, 2019, 'Audi A5 2019');
insert into cars (model, brand, price, year, description) values ('A6', 'Audi', 50000.00, 2019, 'Audi A6 2019');
insert into cars (model, brand, price, year, description) values ('A7', 'Audi', 55000.00, 2019, 'Audi A7 2019');
insert into cars (model, brand, price, year, description) values ('A8', 'Audi', 60000.00, 2019, 'Audi A8 2019');
insert into cars (model, brand, price, year, description) values ('Q3', 'Audi', 35000.00, 2019, 'Audi Q3 2019');
insert into cars (model, brand, price, year, description) values ('Q5', 'Audi', 40000.00, 2019, 'Audi Q5 2019');
insert into cars (model, brand, price, year, description) values ('Q7', 'Audi', 45000.00, 2019, 'Audi Q7 2019');
insert into cars (model, brand, price, year, description) values ('Q8', 'Audi', 50000.00, 2019, 'Audi Q8 2019');
insert into cars (model, brand, price, year, description) values ('TT', 'Audi', 35000.00, 2019, 'Audi TT 2019');