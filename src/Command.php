<?php

namespace App;

use Symfony\Component\Console\Command\Command as SynfonyComand;

class Command extends SynfonyComand 
{
    protected $db;

    public function __construct(DatabaseAdapter $db) {
        $this->db = $db;
        parent::__construct();
    }

}