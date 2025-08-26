<?php

namespace App\Http\Controllers;

use App\Reception;
use App\Roadmap;
use App\Contractor;
use App\Institution;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptionsController extends Controller
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
        $receptions = DB::table('receptions')
            ->leftJoin('roadmaps', 'receptions.roadmap_number', '=', 'roadmaps.roadmap_number')
            ->leftJoin('contractors', 'roadmaps.contractor_id', 'contractors.id')
            ->leftJoin('cars', 'roadmaps.plate', '=', 'cars.id')
            ->leftJoin('drivers', 'roadmaps.driver_id', 'drivers.id')
            ->leftJoin('suppliers', 'cars.supplier_id', '=', 'suppliers.id')
            ->select(
                'receptions.ebm',
                'receptions.id',
                'receptions.transmission',
                'receptions.messenger',
                'receptions.messenger_phone',
                'receptions.created_at',
                'roadmaps.roadmap_number as roadmap_number',
                'contractors.name as contractor_name',
                'roadmaps.purchase_order',
                'roadmaps.institution',
                'roadmaps.ebm_number',
                'roadmaps.created_on',
                'roadmaps.received_on',
                'roadmaps.odometer_start',
                'roadmaps.odometer_end',
                'roadmaps.operator',
                'roadmaps.destination',
                'contractors.name as contractor',
                'drivers.name as driver',
                'drivers.phone_number',
                'suppliers.name as supplier',
                'cars.plate_number as plate_number' // use plate_number instead of car_id
            )
            ->get();
       
        return view('receptions.index')->with('receptions', $receptions);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    // provide purchase orders and institutions similar to Roadmap create
    $roadmaps = Roadmap::orderBy('purchase_order', 'asc')->get();
    $institutions = Institution::orderBy('name', 'asc')->get();
    $contractors = Contractor::orderBy('name', 'asc')->get();
    $suppliers = Supplier::orderBy('name', 'asc')->get();

    return view('receptions.create')->with('roadmaps', $roadmaps)->with('institutions', $institutions)->with('contractors', $contractors)->with('suppliers', $suppliers);
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

        $reception = new Reception;
        $reception->user_id = Auth()->user()->id;
        $reception->purchase_order = $request->input('purchase_order');
        $reception->roadmap_number = $request->input('roadmap_number');
        $reception->contractor = $request->input('contractor');
        $reception->institution = $request->input('institution');
        $reception->number_of_days = $request->input('number_of_days');
        $reception->starting_date = $request->input('starting_date');
        $reception->ending_date = $request->input('ending_date');
        $reception->operator = $request->input('operator');
        $reception->destination = $request->input('destination');
        $reception->plate_number = $request->input('plate_number');
        $reception->ebm = $request->input('ebm');
        $reception->files = implode("|", $images);
        $reception->messenger = $request->input('messenger');
        $reception->messenger_phone = $request->input('messenger_phone');
        $reception->supplier = $request->input('supplier');
        $reception->save();

        return redirect('/receptions')->with('success','Reception saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reception = DB::table('receptions')
            ->leftJoin('roadmaps', 'receptions.roadmap_number', '=', 'roadmaps.roadmap_number')
            ->leftJoin('contractors', 'roadmaps.contractor_id', '=', 'contractors.id')
            ->leftJoin('cars', 'roadmaps.car_id', '=', 'cars.id')
            ->select(
                'receptions.*',
                'roadmaps.roadmap_number',
                'roadmaps.purchase_order',
                'roadmaps.institution',
                'contractors.name as contractor_name',
                'cars.plate_number'
            )
            ->where('receptions.id', $id)
            ->first();
    
        if (!$reception) {
            abort(404);
        }
    
        $files = $reception->files ? explode("|", $reception->files) : [];
        return view('receptions.show', compact('reception', 'files'));
    }
    
    public function edit($id)
    {
        $reception = Reception::findOrFail($id);
        $roadmaps = Roadmap::orderBy('purchase_order', 'asc')->get();
        $institutions = Institution::orderBy('name', 'asc')->get();
        $contractors = Contractor::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        
        return view('receptions.edit')
               ->with('reception', $reception)
               ->with('roadmaps', $roadmaps)
               ->with('institutions', $institutions)
               ->with('contractors', $contractors)
               ->with('suppliers', $suppliers);
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
        $images = array();
        if($files = $request->file('files')){
            foreach($files as $file){
                $name = $file->getClientOriginalName();
                $file->move(public_path('images'), $name);
                $images[] = $name;
            }
        }

        $this->validate($request, [
            'files' => 'nullable|max:1999'
        ]);

        $reception = Reception::findOrFail($id);
        $reception->user_id = Auth()->user()->id;
        $reception->purchase_order = $request->input('purchase_order');
        $reception->roadmap_number = $request->input('roadmap_number');
        $reception->contractor = $request->input('contractor');
        $reception->institution = $request->input('institution');
        $reception->number_of_days = $request->input('number_of_days');
        $reception->starting_date = $request->input('starting_date');
        $reception->ending_date = $request->input('ending_date');
        $reception->operator = $request->input('operator');
        $reception->destination = $request->input('destination');
        $reception->plate_number = $request->input('plate_number');
        $reception->ebm = $request->input('ebm');
        $reception->files = implode("|", $images);
        $reception->messenger = $request->input('messenger');
        $reception->messenger_phone = $request->input('messenger_phone');
        $reception->supplier = $request->input('supplier');
        $reception->save();

        return redirect('/receptions')->with('success','Reception updated');
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
    $reception = Reception::findOrFail($id);
    $reception->delete();
    return redirect('/receptions')->with('success','Reception deleted');
    }

    /**
     * Mark reception as sent to controller (sets transmission = 'Sent')
     */
    public function sendToController(Request $request, $id)
    {
        // Use query builder update by numeric id to avoid model primaryKey mismatch
        DB::table('receptions')->where('id', $id)->update(['transmission' => 'Sent']);
        return redirect('/receptions')->with('success', 'Reception sent to controller');
    }

    /**
     * Controllers index: show receptions that were sent to controller
     */
    public function controllersIndex()
    {
        $receptions = DB::table('receptions')
            ->leftJoin('roadmaps', 'receptions.roadmap_number', '=', 'roadmaps.roadmap_number')
            ->leftJoin('contractors', 'roadmaps.contractor_id', 'contractors.id')
            ->leftJoin('cars', 'roadmaps.plate', '=', 'cars.id')
            ->leftJoin('drivers', 'roadmaps.driver_id', 'drivers.id')
            ->leftJoin('users', 'receptions.approved_by', '=', 'users.id')
            ->select(
                'receptions.id',
                'receptions.ebm',
                'receptions.approved_by',
                'receptions.updated_at as approved_at',
                'receptions.transmission',
                'receptions.messenger',
                'receptions.messenger_phone',
                'receptions.supplier',
                'users.name as approver_name',
                'roadmaps.roadmap_number as roadmap_number',
                'contractors.name as contractor_name',
                'roadmaps.purchase_order',
                'roadmaps.institution',
                'roadmaps.ebm_number',
                'roadmaps.created_on',
                'roadmaps.received_on',
                'roadmaps.odometer_start',
                'roadmaps.odometer_end',
                'roadmaps.operator',
                'roadmaps.destination',
                'roadmaps.total_amount',
                'roadmaps.selling_price',
                'contractors.name as contractor',
                'drivers.name as driver',
                'drivers.phone_number',
                'cars.plate_number as plate_number' // use plate_number instead of car_id
            )
            ->get();
        
        return view('controllers.index')->with('receptions', $receptions);
    }

    /**
     * Approve a reception (set transmission = 'Approved')
     */
    public function approve(Request $request, $id)
    {
        // reuse sendToController behavior if needed, then set Approved
        DB::table('receptions')->where('id', $id)->update([
            'transmission' => 'Approved',
            'approved_by' => Auth()->user()->id,
            'updated_at' => now()
        ]);
        return redirect('/controllers')->with('success', 'Reception approved');
    }

    /**
     * Deny a reception (set transmission = 'Denied')
     */
    public function deny(Request $request, $id)
    {
        DB::table('receptions')->where('id', $id)->update(['transmission' => 'Denied']);
        return redirect('/controllers')->with('success', 'Reception denied');
    }
}
