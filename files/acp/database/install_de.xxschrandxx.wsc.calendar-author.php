<?php

use wcf\system\database\table\column\IntDatabaseTableColumn;
use wcf\system\database\table\column\ObjectIdDatabaseTableColumn;
use wcf\system\database\table\column\VarcharDatabaseTableColumn;
use wcf\system\database\table\PartialDatabaseTable;

return [
    // calendar1_event
    PartialDatabaseTable::create('calendar1_event')
        ->columns([
            IntDatabaseTableColumn::create('authorID')
                ->length(10),
            VarcharDatabaseTableColumn::create('authorname')
                ->length(255),
        ])
];
