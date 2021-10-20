<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Messages;
use App\Sql;
class Get_Msgs extends Controller
{
    public function export_Xls(Request $request)
    {
        $datas = $request->all();
        
        
        $msg = new Messages(date("d-m-Y"));

        $msg->exportCSVDia($datas);
    }
}
