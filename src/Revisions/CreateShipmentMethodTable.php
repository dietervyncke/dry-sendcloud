<?php

namespace Tnt\Sendcloud\Revisions;

use Oak\Contracts\Migration\RevisionInterface;
use Tnt\Dbi\TableBuilder;

class CreateShipmentMethodTable extends DatabaseRevision implements RevisionInterface
{
    public function up()
    {
        $this->queryBuilder->table('sendcloud_shipment_method')->create(function (TableBuilder $table) {

            $table->addColumn('id', 'int')->length(11)->primaryKey();
            $table->addColumn('created', 'int')->length(11);
            $table->addColumn('updated', 'int')->length(11);
            $table->addColumn('sort_index', 'int')->length(11);
            $table->addColumn('sendcloud_id', 'int')->length(11);
            $table->addColumn('name', 'varchar')->length(255);
            $table->addColumn('carrier', 'varchar')->length(255);
        });

        $this->execute();
    }

    public function down()
    {
        $this->queryBuilder->table('sendcloud_shipment_method')->drop();
        $this->execute();
    }

    public function describeUp(): string
    {
        return 'Create sendcloud_shipment_method table';
    }

    public function describeDown(): string
    {
        return 'Drop sendcloud_shipment_method table';
    }
}