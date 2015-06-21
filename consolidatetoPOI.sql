
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