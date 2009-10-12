create table item (
  item_id integer not null
  , item_name character varying(64) not null
  , constraint item_PKC primary key (item_id)
);

create table ordering_detail (
  ordering_id integer not null
  , item_id integer not null
  , ordering_count integer not null
  , constraint ordering_detail_PKC primary key (ordering_id,item_id)
);

create table ordering (
  ordering_id integer not null
  , customer_id integer not null
  , ordering_date date not null
  , constraint ordering_PKC primary key (ordering_id)
);

create table customer (
  customer_id integer not null
  , customer_name character varying(64) not null
  , constraint customer_PKC primary key (customer_id)
);


