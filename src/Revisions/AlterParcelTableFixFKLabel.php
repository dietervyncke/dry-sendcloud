<?php

namespace Tnt\Sendcloud\Revisions;

use Oak\Contracts\Migration\RevisionInterface;
use Tnt\Dbi\TableBuilder;

class AlterParcelTableFixFKLabel extends DatabaseRevision implements RevisionInterface
{
    public function up()
    {
        $this->queryBuilder->table('sendcloud_parcel')->alter(function (TableBuilder $table) {

            $table->dropForeignKey('label', 'sendcloud_parcel');
            $table->addForeignKey('label', 'sendcloud_label');
        });

        $this->execute();
    }

    public function down()
    {
        $this->queryBuilder->table('sendcloud_parcel')->alter(function (TableBuilder $table) {

            $table->dropForeignKey('label', 'sendcloud_label');
            $table->addForeignKey('label', 'sendcloud_parcel');
        });

        $this->execute();
    }

    public function describeUp(): string
    {
        return 'Alter sendcloud_parcel table change FK';
    }

    public function describeDown(): string
    {
        return 'Alter sendcloud_parcel table change FK';
    }
}