drop schema if exists communityfund cascade;
create schema communityfund;
set search_path to communityfund;

create table Users(
        uid SERIAL PRIMARY KEY,
        username varchar(32) UNIQUE,
        email varchar(128),
        password varchar(32)
);

create table UserProfile(
        uid integer REFERENCES Users(uid),
        description text
);

create table Project(
        pid SERIAL PRIMARY KEY,
        name varchar(128),
        goal money,
        startdate timestamptz,
        enddate timestamptz 
);

create table ProjectInformation(
        pid integer REFERENCES Project(pid),
        description text,
        product boolean,
        donation boolean
);

create table Funder(
        uid integer REFERENCES Users(uid),
        pid integer REFERENCES Project(pid),
        amount money
);

create table Buyer(
        uid integer REFERENCES Users(uid),
        pid integer REFERENCES Project(pid),
        amount money
);

create table Initiator(
        uid integer REFERENCES Users(uid),
        pid integer REFERENCES Project(pid)
);

create table UserRevew(
        rid SERIAL PRIMARY KEY,
        uid integer REFERENCES Users(uid),
        reviewer integer REFERENCES Users(uid),
        rating int,
        CONSTRAINT rating CHECK (rating IN (0, 1, 2, 3, 4, 5)),
        review varchar(1600)
);

create table ProjectReview(
        rid SERIAL PRIMARY KEY,
        pid integer REFERENCES Project(pid),
        uid integer REFERENCES Users(uid),
        rating int,
        CONSTRAINT rating CHECK (rating IN (0, 1, 2, 3, 4, 5)),
        review varchar(1600)
);

create table Community(
        cid SERIAL PRIMARY KEY,
        name varchar(32) UNIQUE
);

create table UserCommunity(
        cid integer REFERENCES Community(cid),
        uid integer REFERENCES Users(uid)
);

create table ProjectCommunity(
        cid integer REFERENCES Community(cid),
        pid integer REFERENCES Project(pid)
);

create table Friend(
        uid integer REFERENCES Users(uid),
        friend integer REFERENCES Users(uid)
);

create table Admins(
        uid integer REFERENCES Users(uid)
);
