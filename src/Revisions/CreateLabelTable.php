<?php

namespace Tnt\Sendcloud\Revisions;

use Oak\Contracts\Migration\RevisionInterface;
use Tnt\Dbi\TableBuilder;

class CreateLabelTable extends DatabaseRevision implements RevisionInterface
{
    public function up()
    {
        $this->queryBuilder->table('sendcloud_label')->create(function (TableBuilder $table) {

            $table->addColumn('id', 'int')->length(11)->primaryKey();
            $table->addColumn('created', 'int')->length(11);
            $table->addColumn('updated', 'int')->length(11);
            $table->addColumn('normal_printer', 'text');
            $table->addColumn('label_printer', 'varchar')->length(255);
        });

        $this->execute();
    }

    public function down()
    {
        $this->queryBuilder->table('sendcloud_label')->drop();
        $this->execute();
    }

    public function describeUp(): string
    {
        return 'Create sendcloud_label table';
    }

    public function describeDown(): string
    {
        return 'Drop sendcloud_label table';
    }
}