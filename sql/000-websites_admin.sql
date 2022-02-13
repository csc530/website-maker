create table if not exists websites_admin
(
    siteName varchar(35)  not null,
    creator  int          not null,
    admin    varchar(128) not null,
    constraint websites_admin_creators_ID_fk
        foreign key (creator) references creators (ID)
            on update cascade on delete cascade,
    constraint websites_admin_creators_email_fk_2
        foreign key (admin) references creators (email)
            on update cascade on delete cascade,
    constraint websites_admin_websites_name_fk
        foreign key (siteName) references websites (name)
            on update cascade on delete cascade
);

