create database php_blog;
create database php_blog_test;

create table users (
	id varchar(255) primary key,
    email varchar(255) not null,
    username varchar(255) not null,
    password varchar(255) not null
) Engine = InnoDB;

create table sessions (
	id varchar(255) primary key,
	user_id varchar(155) not null,
    constraint fk_sessions_users
    foreign key (user_id) references users (id)
) engine = InnoDB;

create table blogs (
	id varchar(255) primary key,
    title varchar(255) not null,
    content text not null,
    created_at timestamp not null,
    user_id varchar(255) not null,
    constraint fk_blogs_users
    foreign key (user_id) references users (id)
) engine = InnoDB;

create table comments (
	id varchar(255) primary key,
    content text not null,
    created_at timestamp not null,
    user_id varchar(255) not null,
    blog_id varchar(255) not null,
    constraint fk_comments_users
    foreign key (user_id) references users (id),
    constraint fk_comments_blogs
    foreign key (blog_id) references blogs (id)
) engine = InnoDB;

alter table users
add unique key (email);

desc users;