INSERT INTO `lego_setup` ( `basename` , `type` , `userid` , `hostname` , `start_time` , `elapsed_time` , `status` , `timestamp` )
VALUES (
'lego_maintain', 'lego_fe', 'tonysanv', 'ridebicycle.tpcity.corp.yahoo.com', '1363153015', 1431, 'success', now( )
)


select sum(timestamp >= DATE_ADD(CURDATE(), INTERVAL - 3 hour) and status = 'success' and basename = 'lego_maintain' and type = 'lego_fe') as success 
, sum(timestamp >= DATE_ADD(CURDATE(), INTERVAL - 3 hour) and basename = 'lego_maintain' and type = 'lego_fe') as total
from lego_setup

