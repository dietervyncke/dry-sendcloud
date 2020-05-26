<?php

namespace Tnt\Sendcloud\Revisions;

use Oak\Contracts\Migration\RevisionInterface;
use Tnt\Dbi\TableBuilder;

class CreateParcelTable extends DatabaseRevision implements RevisionInterface
{
    public function up()
    {
        $this->queryBuilder->table('sendcloud_parcel')->create(function (TableBuilder $table) {

            $table->addColumn('id', 'int')->length(11)->primaryKey();
            $table->addColumn('created', 'int')->length(11);
            $table->addColumn('updated', 'int')->length(11);
            $table->addColumn('sendcloud_id', 'int')->length(11);
            $table->addColumn('name', 'varchar')->length(255);
            $table->addColumn('address', 'varchar')->length(255);
            $table->addColumn('city', 'varchar')->length(255);
            $table->addColumn('postal_code', 'varchar')->length(255);
            $table->addColumn('telephone', 'varchar')->length(255);
            $table->addColumn('email', 'varchar')->length(255);
            $table->addColumn('tracking_number', 'varchar')->length(255);
            $table->addColumn('status', 'int')->length(11);
            $table->addColumn('country', 'varchar')->length(255);
            $table->addColumn('is_return', 'tinyint')->length(1);
            $table->addColumn('label', 'int')->length(11)->null();
            $table->addColumn('shipment_method', 'int')->length(11)->null();

            $table->addForeignKey('label', 'sendcloud_parcel');
            $table->addForeignKey('shipment_method', 'sendcloud_shipment_method');
        });

        $this->execute();
    }

    public function down()
    {
        $this->queryBuilder->table('sendcloud_parcel')->drop();
        $this->execute();
    }

    public function describeUp(): string
    {
        return 'Create sendcloud_parcel table';
    }

    public function describeDown(): string
    {
        return 'Drop sendcloud_parcel table';
    }
}