<?php

namespace Tnt\Sendcloud\Revisions;

use dry\db\Connection;
use Tnt\Dbi\QueryBuilder;

abstract class DatabaseRevision
{
    protected $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    protected function execute()
    {
        $this->queryBuilder->build();
        Connection::get()->query($this->queryBuilder->getQuery());
    }
}