<?php

namespace App\Http\Controllers\Admin;

use App\NdisPricingguide;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PricingGuideController extends Controller
{
    public function loadrecords(Request $request)
    {
        if ($request->ajax()) {

            $data = NdisPricingguide::latest()->get();
            
            return Datatables::of($data)
                // ->addIndexColumn()
                ->make(true);
        }

        return view('admin.pricingguide');
    }
}
