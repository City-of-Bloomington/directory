alter table users rename column authenticationMethod to authentication_method;

alter table emergencyContacts drop employeeId;
alter table emergencyContacts drop employeeNum;
alter table emergencyContacts drop department;
alter table emergencyContacts drop workSite;

alter table emergencyContacts add firstname varchar(32) after username;
alter table emergencyContacts add lastname  varchar(32) after firstame;

alter table emergencyContacts modify firstname varchar(32) not null;
alter table emergencyContacts modify lastname  varchar(32) not null;
