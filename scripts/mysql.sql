-- @copyright 2014 City of Bloomington, Indiana
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
-- @author Cliff Ingham <inghamn@bloomington.in.gov>
create table users (
	id int unsigned not null primary key auto_increment,
	firstname            varchar(128) not null,
	lastname             varchar(128) not null,
	email                varchar(255) not null,
	username             varchar(40)  not null unique,
	password             varchar(40),
	authenticationMethod varchar(40)  not null,
	role                 varchar(30)  not null
);
