
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `what2do`.`poi`
  (`id`, `name`, `lon`, `lat`, `time`, `placeid`)
SELECT tf.`id`,
       tf.`name`,
       tf.`lon`,
       tf.`lat`,
       tf.`time`,
       tf.`placeid`
  FROM `what2do`.`places` tf
 WHERE NOT EXISTS(SELECT placeid
                    FROM `what2do`.`poi` tt
                   WHERE tt.placeid = tf.placeid)

SELECT *,
((ACOS(SIN(25.057604 * PI() / 180) * SIN(lat * PI() / 180) + COS(25.057604 * PI() / 180) * COS(lat * PI() / 180) * COS((121.6138876 - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) 
AS `distance`
, HOUR(time) AS 'THISTIME'
, MONTH(NOW()) AS 'SEASON'
FROM `fb574924679` WHERE
DAYOFWEEK(NOW()) = DAYOFWEEK(time)
AND 11+1 > HOUR(time)
AND 11-1 < HOUR(time)
AND MONTH(NOW()) = MONTH(time)
AND place_category = ''
HAVING `distance`<=1
ORDER BY `placeid` ASC