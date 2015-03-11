drop schema if exists communityfund cascade;
create schema communityfund;
set search_path to communityfund;

create table users(
        uid SERIAL PRIMARY KEY,
        email varchar(128),
        password varchar(32)
);

create table userposts(
        uid integer REFERENCES Users(uid),
        username varchar(32) UNIQUE,
        image varchar(256)
);

create table userprofile(
        uid integer REFERENCES Users(uid),
        description text
);

create table project(
        pid SERIAL PRIMARY KEY,
        name varchar(32),
        image varchar(256),
        description text,
        goal money,
        startdate timestamptz,
        enddate timestamptz,
        product boolean,
        donation boolean
);

create table funder(
        uid integer REFERENCES Users(uid),
        pid integer REFERENCES Project(pid),
        amount money
);

create table buyer(
        uid integer REFERENCES Users(uid),
        pid integer REFERENCES Project(pid),
        amount money
);

create table initiator(
        uid integer REFERENCES Users(uid),
        pid integer REFERENCES Project(pid)
);

create table userreview(
        rid SERIAL PRIMARY KEY,
        uid integer REFERENCES Users(uid),
        reviewer integer REFERENCES Users(uid),
        rating int,
        CONSTRAINT rating CHECK (rating IN (0, 1, 2, 3, 4, 5)),
        review varchar(1600)
);

create table projectreview(
        rid SERIAL PRIMARY KEY,
        pid integer REFERENCES Project(pid),
        reviewer integer REFERENCES Users(uid),
        rating int,
        CONSTRAINT rating CHECK (rating IN (0, 1, 2, 3, 4, 5)),
        review varchar(1600)
);

create table community(
        cid SERIAL PRIMARY KEY,
        name varchar(32) UNIQUE
);

create table usercommunity(
        cid integer REFERENCES Community(cid),
        uid integer REFERENCES Users(uid)
);

create table projectcommunity(
        cid integer REFERENCES Community(cid),
        pid integer REFERENCES Project(pid)
);

create table friend(
        uid integer REFERENCES Users(uid),
        friend integer REFERENCES Users(uid)
);

create table admins(
        uid integer REFERENCES Users(uid)
);
