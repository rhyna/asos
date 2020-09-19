<?php

$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);

return $db->getConn();
