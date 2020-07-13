<?php

namespace App\Lib\Import;

use App\[[model_uc]];
use App\Lib\Import\GetDbColumns;
use Illuminate\Support\Facades\DB;

class Import[[model_uc]]
{


    public $fields = [

    [[foreach:columns]]
        "[[i.name]]" => ["name" => "[[i.name]]"],
    [[endforeach]]

//        "created_at" => ["name" => "created_at"],
//        "created_by" => ["name" => "created_by"],
//        "updated_at" => ["name" => "updated_at"],
//        "modified_by" => ["name" => "modified_by"],
//        "purged_by" => ["name" => "purged_by"],
    ];

    public function import($database, $tablename)
    {
        echo "Importing $tablename\n";

        $records = DB::connection($database)->select("select * from " . $tablename);

        $count = 0;
        foreach ($records as $record) {
            //if ($count++ > 5) die;

            $new_rec = $this->clean($record);

            $Org = new [[model_uc]]();
            $Org->add($new_rec);
        }
    }

    function clean($record)
    {
        $data = [];
        foreach ($this->fields as $org_name => $field) {
            $data[$field['name']] = $record->$org_name;
        }

        return $data;
    }

}

