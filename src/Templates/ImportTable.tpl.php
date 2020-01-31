<?php


namespace App\Lib\Import;
use App\Lib\Import\GetDbColumns;

use App\[[model_uc]];
use DB;

class Import[[model_uc]]
{


    var $fields = [

    [[foreach:columns]]
        "[[i.name]]" => ["name" => "[[i.name]]"],
    [[endforeach]]

//        "created_at" => ["name" => "created_at"],
//        "created_by" => ["name" => "created_by"],
//        "updated_at" => ["name" => "updated_at"],
//        "modified_by" => ["name" => "modified_by"],
//        "purged_by" => ["name" => "purged_by"],
    ];

    function import($database, $tablename)
    {

        print "Importing $tablename\n";

        $records = DB::connection($database)->select("select * from " . $tablename);

        $count = 0;
        foreach ($records AS $record) {
            //if ($count++ > 5) die;

            $new_rec = $this->clean($record);

            $Org = new [[model_uc]]();
            $Org->add($new_rec);

        }

    }

    function clean($record) {
        $data = [];
        foreach ($this->fields AS $org_name => $field) {
            $data[$field['name']] = $record->$org_name;
        }

        return $data;
    }

}

