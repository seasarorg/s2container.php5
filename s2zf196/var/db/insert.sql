insert into accounts values('SMITH');
insert into accounts values('ALLEN');
insert into accounts values('WARD');
insert into accounts values('JONES');
insert into accounts values('MARTIN');

insert into bugs values(101,'this is a bug.', 'open', 'SMITH', 'JONES', NULL);
insert into bugs values(102,'that is a bug.', 'open', 'ALLEN', 'MARTIN', NULL);
insert into bugs values(103,'it is a bug.',   'open', 'WARD', NULL, NULL);

insert into products values(1101,'s2container.php');
insert into products values(1201,'s2erd');
insert into products values(1301,'s2zf');

insert into bugs_products values(101, 1101);
insert into bugs_products values(102, 1101);
insert into bugs_products values(102, 1201);
insert into bugs_products values(103, 1201);
insert into bugs_products values(103, 1301);
insert into bugs_products values(101, 1301);

