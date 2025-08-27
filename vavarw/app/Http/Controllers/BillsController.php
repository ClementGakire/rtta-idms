<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Roadmap;
use App\Supplier;
use App\Contractor;
use App\Car;
use App\Driver;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BillsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $bills = DB::table('bills')
            ->leftJoin('suppliers', 'bills.supplier_id', 'suppliers.id')
            ->leftJoin('roadmaps', 'bills.roadmap_id', 'roadmaps.id')
            ->select('bills.*', 'suppliers.name as name', 'roadmaps.purchase_order')
            ->get();
            // Build a bills-centered query so the bills table is the primary source of rows.
            // The view expects a `roadmaps` variable with these fields, so we produce the same shape.
            $roadmaps = DB::table('bills')
                ->join('roadmaps', 'bills.roadmap_id', '=', 'roadmaps.id')
               
                ->leftJoin('cars', 'roadmaps.plate', '=', 'cars.id')
                ->leftJoin('contractors', 'roadmaps.contractor_id', '=', 'contractors.id')
                ->leftJoin('drivers', 'roadmaps.driver_id', '=', 'drivers.id')
                ->leftJoin('suppliers', 'cars.supplier_id', '=', 'suppliers.id')
                ->select(
                    'suppliers.name as supplier',
                    'roadmaps.created_on',
                    'roadmaps.received_on',
                    'roadmaps.institution',
                    'cars.plate_number',
                    'roadmaps.purchase_order',
                    'roadmaps.ebm_number',
                    'roadmaps.destination',
                    'roadmaps.amount',
                    DB::raw("COALESCE(
                        (SELECT SUM(c.amount) FROM charges c WHERE c.roadmap = roadmaps.id),
                        (SELECT SUM(c2.amount) FROM charges c2 WHERE c2.roadmap = roadmaps.purchase_order),
                        (SELECT SUM(c3.amount) FROM charges c3 WHERE c3.car_id = roadmaps.plate),
                        0
                    ) as total_charges"),
                    'roadmaps.advance_cash',
                    'roadmaps.advance_fuel'
                )
                ->groupBy(
                    'suppliers.name',
                    'roadmaps.created_on',
                    'roadmaps.received_on',
                    'roadmaps.institution',
                    'cars.plate_number',
                    'roadmaps.purchase_order',
                    'roadmaps.ebm_number',
                    'roadmaps.destination',
                    'roadmaps.amount',
                    'roadmaps.advance_cash',
                    'roadmaps.advance_fuel',
                    'roadmaps.id'
                )
                ->get();
        return view('bills.index')->with('bills', $bills)->with('roadmaps', $roadmaps);
        //return view('projects.index')->with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roadmaps = Roadmap::all();
        $suppliers = Supplier::all();
        return view('bills.create')->with('roadmaps', $roadmaps)->with('suppliers', $suppliers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $images = array();
        if($files = $request->file('files')){
            foreach($files as $file){
                $name=$file->getClientOriginalName();
                $file->move(public_path('images'), $name);
                $images[] = $name;
            }
        }

        $this->validate($request, [
            'files' => 'nullable|max:1999'
        ]);
        $bill = new bill;
        $bill->supplier_id = $request->input('supplier_id');
        $bill->roadmap_id = $request->input('roadmap_id');  
        $bill->amount = $request->input('amount');
        $bill->quoted_amount = $request->input('quoted_amount');
        $bill->payment_date = $request->input('payment_date'); 
        $bill->files = implode("|", $images);
        $bill->user_id = Auth::id();
        $bill->payment_mode = $request->has('payment_mode')
            ? implode(",", $request->input('payment_mode')) 
            : null;
        $bill->save();
        return redirect('/bills')->with('success','bill saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $bills = Bill::find($id);
        return view('bills.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $bill = Bill::find($id);
        $roadmaps = Roadmap::all();
        $suppliers = Supplier::all();
        return view('bills.edit')->with('bill', $bill)->with('roadmaps', $roadmaps)->with('suppliers', $suppliers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $bill =  Bill::find($id);
        $bill->supplier_id = $request->input('supplier_id');
        $bill->roadmap_id = $request->input('roadmap_id');  
        $bill->amount = $request->input('amount');
        $bill->quoted_amount = $request->input('quoted_amount');
        $bill->payment_date = $request->input('payment_date'); 
        $bill->payment_mode = $request->has('payment_mode') 
            ? implode(",", $request->input('payment_mode')) 
            : null;
        $bill->save();
        return redirect('/bills')->with('success','bill edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $bill = Bill::find($id);
        $bill->delete();
        return redirect('/bills')->with('success','supplier deleted');
    }
}
