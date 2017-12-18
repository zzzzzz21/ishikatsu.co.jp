<?php
$con = mysql_connect('localhost', 'ishikatsu', 'iei99MASTER');
mysql_select_db('ishikatsucms', $con);

$sql = <<<EOD
INSERT INTO t_accounts
(
`name`,
`login_id`,
`password`,
`is_admin`,
`is_hide`,
`regdate`,
`upddate`,
`upd_account_id`
) VALUES (
'unitea',
'unitea',
'unitea',
1,
0,
now(),
now(),
1
)
EOD;
$ret = mysql_query($sql, $con);
var_dump($ret, mysql_error());
