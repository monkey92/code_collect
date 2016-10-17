#查询结果加排名序号
select 
(@k:=@k+1) as range,t1.openid as openid,t1.count as count 
from (select openid,count(*) as count from tg_case group by openid order by count desc) as t1 ,(select @k:=0) as t2 ;
#查询某个人的排名
select t3.range
form (select 
(@k:=@k+1) as range,t1.openid as openid,t1.count as count
from (select openid,count(*) as count from tg_case group by openid order by count desc) as t1 ,(select @k:=0) as t2 ;) as t3 
where  t3.openid = 'openid_str';