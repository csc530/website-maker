create table if not exists pages
(
    name       varchar(50)    not null,
    siteName   varchar(35)    not null,
    content    varchar(10000) null,
    pageNumber int            not null,
    creatorID  int            not null,
    primary key (name, siteName, creatorID),
    constraint pages_creators_ID_fk
        foreign key (creatorID) references creators (ID)
            on update cascade on delete cascade,
    constraint pages_websites_name_fk
        foreign key (siteName) references websites (name)
            on update cascade on delete cascade
);

