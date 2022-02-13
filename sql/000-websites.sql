create table if not exists websites
(
    name        varchar(35)                                not null,
    preview     blob                                       null,
    description varchar(600)                               not null,
    creatorID   int                                        not null,
    logo        varchar(120) default '../images/Blank.svg' null,
    visits      int          default 0                     null,
    theme       varchar(21)                                null,
    primary key (name, creatorID),
    constraint websites_creators_ID_fk
        foreign key (creatorID) references creators (ID)
            on update cascade on delete cascade
);

