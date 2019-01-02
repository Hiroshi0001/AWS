tr_wa_fullstripe_payments_ins

BEGIN
insert into wa_swpm_payments_tbl (txn_date,txn_id,subscr_id,email,gateway,status)
values(cast(new.created as date),
	new.stripeCustomerID,
	concat(new.eventID,":",new.description),
	new.email,
	"stripe",
	"completed");
insert into wa_onopod_oneday_tbl (email,oneday_date_first,oneday_date,created)
values(new.email,
	substring_index(new.description,":",-1),
	substring_index(new.description,":",-1),
	cast(new.created as date));
  END
  
  
drop table wa_onopod_oneday_tbl;

create table wa_onopod_oneday_tbl (
id bigint not null auto_increment,
email varchar(200),
oneday_date_first date,
oneday_date date,

created date,
primary key(id));


INSERT INTO `wa_onopod_oneday_tbl` (`id`, `email`, `oneday_date_first`, `oneday_date`, `created`) VALUES
(1, 'aaa@gg.com', '2016-01-14', '2016-01-14', '2017-06-22'),
(2, 'onopod@gmail.com', '2017-06-29', '2017-06-29', '2017-06-22');

drop view wa_onopod_oneday_part_view,wa_onopod_oneday_view;
create view wa_onopod_oneday_part_view as 
select x.person_id,y.event_start_date,concat(date_format(y.event_start_date,'%Y/%m/%d')," (",group_concat(y.event_name separator ","),")") as event_name from wa_em_bookings as x
left join wa_em_events as y on x.event_id = y.event_id
where booking_status in (0,1)
and substring_index(booking_comment,":",1) = "oneday"
group by x.person_id,y.event_start_date;


create view wa_onopod_oneday_view as 
select b.id,
	date_format(a.created,'%Y/%m/%d') as created,
	c.event_name,
	concat(date_format(a.oneday_date_first,'%Y/%m/%d')," - ",
	date_format(adddate(adddate(a.oneday_date_first,interval 1 month),interval -1 day),'%Y/%m/%d')) as oneday_date_range
from wa_onopod_oneday_tbl as a
left join wa_users as b on a.email = b.user_email
left join wa_onopod_oneday_part_view as c on a.oneday_date = c.event_start_date
and b.id = c.person_id
where curdate() < adddate(a.oneday_date_first,interval 1 month)
order by a.oneday_date_first;

