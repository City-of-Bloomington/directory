create table users (
	userID int unsigned not null primary key auto_increment,
	username varchar(30) not null,
	password varchar(32),
	authenticationMethod varchar(30) not null default 'LDAP'
);

create table userRoles (
	userID int unsigned not null,
	role varchar(30) not null,
	primary key (userID,role),
	foreign key (userID) references users(userID),
	foreign key (role) references roles(role)
);

create table roles (
	role varchar(30) not null primary key
);
insert roles values('Administrator');
