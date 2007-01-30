create table users (
	id int unsigned not null primary key auto_increment,
	username varchar(30) not null,
	password varchar(32),
	authenticationMethod varchar(30) not null default 'LDAP'
);

create table roles (
	id int unsigned not null primary key auto_increment,
	role varchar(30) not null
);
insert roles values(null,'Administrator');

create table user_roles (
	user_id int unsigned not null,
	role_id int unsigned not null,
	primary key (user_id,role_id),
	foreign key (user_id) references users(id),
	foreign key (role_id) references roles(id)
);
