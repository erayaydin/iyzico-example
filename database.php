<?php

return new PDO('mysql:host='.$config['db']['host'].';dbname='.$config['db']['name'].';charset='.$config['db']['charset'], $config['db']['user'], $config['db']['pass'], array(
    PDO::ATTR_EMULATE_PREPARES=>false,
    PDO::MYSQL_ATTR_DIRECT_QUERY=>false,
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
));