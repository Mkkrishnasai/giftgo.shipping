<?php

namespace App\Imports;

use App\OutboundOrder;
use Maatwebsite\Excel\Concerns\ToModel;

class OutboundOrderImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

    }
}
