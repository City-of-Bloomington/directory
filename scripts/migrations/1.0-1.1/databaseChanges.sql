create table emergencyContacts (
    id int unsigned not null primary key auto_increment,
    username varchar(40) not null unique,
    employeeId  int unsigned unique,
    employeeNum int unsigned unique,
    department varchar(128),
    workSite   varchar(128),
    email_1 varchar(255),
    email_2 varchar(255),
    email_3 varchar(255),
    sms_1   varchar(32),
    sms_2   varchar(32),
    phone_1 varchar(32),
    phone_2 varchar(32),
    phone_3 varchar(32),
    tty_1   varchar(32)
);

load data local infile '/tmp/contacts.csv' into table emergencyContacts
fields terminated by '|'
(username, employeeNum, department, workSite);
