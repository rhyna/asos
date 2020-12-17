<?php

class Database
{
    protected $db_host;
    protected $db_name;
    protected $db_user;
    protected $db_pass;

    public function __construct(string $host, string $name, string $user, string $pass)
    {
        $this->db_host = $host;
        $this->db_name = $name;
        $this->db_user = $user;
        $this->db_pass = $pass;
    }

    /**
     * @return PDO
     */
    public function getConn(): PDO
    {
        $dsn = 'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . ';charset=utf8';

        $db = new PDO($dsn, $this->db_user, $this->db_pass);

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }
}