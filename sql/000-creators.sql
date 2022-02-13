create table if not exists creators
(
    email    varchar(128) not null,
    password varchar(128) not null,
    ID       int auto_increment
        primary key,
    constraint creators_email_uindex
        unique (email)
);

