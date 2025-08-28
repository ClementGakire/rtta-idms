<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Institution;
use App\Payment;
use App\User;
use App\Fuel;
use App\Charge;
use App\Expense;
use App\Supplier;
use App\Bill;
use App\Roadmap;
use DB;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth()->user()->role_id == 'Unspecified'){
          return view('un');
        
        }else{
        $total = DB::table('invoices')->sum('amount');
        $totalSellingPrice = DB::table('roadmaps')
            ->select(DB::raw('SUM(ebm_number  * selling_price) as total_sellingprice'))
            ->value('total_sellingprice');
        $paid = DB::table('payments')->sum('amounts');
        $users = DB::table('users')->count();
        $total_suppliers = DB::table('suppliers')->count();
        $balances = $total - $paid;
        $total_driver_allowance = DB::table('charges')->where('expense_id', 2)->sum('amount');
        $total_tire_expenses = DB::table('charges')->where('expense_id', 4)->sum('amount');
        $total_lubricant_expenses = DB::table('charges')->where('expense_id', 5)->sum('amount');
        $total_traffic_fines_expenses = DB::table('charges')->where('expense_id', 1)->sum('amount');
    $total_expenses = DB::table('charges')->sum('amount');
    $total_fuels = DB::table('fuels')->sum('totalprice');
    $total_amount = DB::table('bills')->sum('amount');
    // Include bills amount as part of total expenses
    $total_expenses = ($total_expenses ?? 0) + ($total_amount ?? 0);
    // margin subtracts total expenses (which now includes bills) and fuels
    $margin_after_all_expenses = $totalSellingPrice - ($total_expenses + $total_fuels);
        $total_number_of_vehicles = DB::table('cars')->count();
        $total_amount_on_spare_parts = DB::table('charges')->where('expense_id', 6)->sum('amount');
        $total_amount_on_vehicle_inspection = DB::table('charges')->where('expense_id', 7)->sum('amount');
        $total_sales = DB::table('roadmaps')->sum('total_amount');
        $total_quoted_amount = DB::table('bills')->sum('quoted_amount');
        $roadmaps = DB::table('roadmaps')
    ->join('users', 'roadmaps.user_id', '=', 'users.id')
    ->leftJoin('pos', 'roadmaps.purchase_order', 'pos.purchase_order')
    ->leftJoin('cars', 'roadmaps.plate', 'cars.id')
    ->leftJoin('contractors', 'roadmaps.contractor_id', 'contractors.id')
    ->leftJoin('drivers', 'roadmaps.driver_id', '=', 'drivers.id')
    ->leftJoin('charges', function($join) {
        $join->on('roadmaps.plate', '=', 'charges.car_id')
             ->on('roadmaps.id', '=', 'charges.roadmap'); // Check both plate and purchase order
    })
    ->leftJoin('suppliers', 'cars.supplier_id', '=', 'suppliers.id') // Join suppliers to cars
    ->select(
        'users.name',
        'cars.plate_number',
        'roadmaps.purchase_order',
        'drivers.name as driver',
        'roadmaps.institution',
        'roadmaps.created_on',
        'roadmaps.received_on',
        'roadmaps.amount',
        'roadmaps.id',
        'roadmaps.ebm_number',
        'roadmaps.odometer_count',
        'roadmaps.operator',
        'roadmaps.selling_price',
        'roadmaps.advance_cash',
        'roadmaps.advance_fuel',
        'roadmaps.status',
        'roadmaps.destination',
        'pos.amounts',
        'contractors.name as contractor',
        DB::raw('SUM(charges.amount) as total_charges'), // Sum of charges
        'suppliers.name as supplier' // Select supplier's name
    )
    ->groupBy(
        'users.name',
        'cars.plate_number',
        'roadmaps.purchase_order',
        'drivers.name',
        'roadmaps.institution',
        'roadmaps.created_on',
        'roadmaps.received_on',
        'roadmaps.amount',
        'roadmaps.id',
        'roadmaps.ebm_number',
        'roadmaps.odometer_count',
        'roadmaps.operator',
        'roadmaps.selling_price',
        'roadmaps.advance_cash',
        'roadmaps.advance_fuel',
        'roadmaps.status',
        'roadmaps.destination',
        'pos.amounts',
        'contractors.name',
        'suppliers.name' // Include supplier's name in groupBy
    )
    ->get();

// Initialize total balance
$total_balance = 0;

foreach ($roadmaps as $roadmap) {
    // Calculate balance for each roadmap
    $totalPrice = ($roadmap->ebm_number * $roadmap->amount);
    $expenses = isset($roadmap->total_charges) ? (float) $roadmap->total_charges : 0.0;
    $adjustedTotal = $totalPrice - $expenses; // total price minus expenses
    $balance = $adjustedTotal - ($roadmap->advance_cash + $roadmap->advance_fuel);
    
    // Add the individual balance to the total balance
    $total_balance += $balance;
}

// Now you have the total balance for all records. Deduct total bills amount as well.
$balance_to_supplier = $total_balance - ($total_amount ?? 0);
    // alternative: $balance_to_supplier = $total_quoted_amount - $total_amount;
       // $reports = DB::table('invoices')
         //   ->select('payments', 'invoices.institution', '=', 'payments.institution')
           // 
         //   ->selectRaw('invoices.institution, sum(payments.amount) as sum, sum(invoices.amount) as total')
           // ->groupBy('invoices.institution')
          //  ->get();

        $reports = DB::select("SELECT i.name, (SELECT SUM(t.amount) FROM invoices t WHERE t.institution = i.name ) as 'total_invoice', (SELECT SUM(p.amounts) FROM payments p WHERE p.institution = i.name ) as 'total_payment' FROM institutions i");
        
        $user = DB::select("SELECT u.id, u.name, (SELECT COUNT(t.amount) FROM invoices t WHERE t.user_id = u.id ) AS 'total_invoices', (SELECT COUNT(p.amounts) FROM payments p WHERE p.user_id = u.id ) AS 'total_payments' FROM users u");
        $expenses = DB::select("SELECT e.id, e.name, (SELECT SUM(c.amount) FROM charges c where c.expense_id = e.id) AS 'total_expense' FROM expenses e");

        // --- Dashboard chart data: Sales last 7 days, Sales last 6 months, Expenses by category ---
        // Last 7 days
        $salesLast7Labels = [];
        $salesLast7Data = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = \Carbon\Carbon::today()->subDays($i);
            $salesLast7Labels[] = $d->format('M d');
            $salesLast7Data[] = (float) DB::table('invoices')->whereDate('created_at', $d)->sum('amount');
        }

        // Last 6 months
        $salesLast6Labels = [];
        $salesLast6Data = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = \Carbon\Carbon::now()->subMonths($i);
            $salesLast6Labels[] = $m->format('M Y');
            $salesLast6Data[] = (float) DB::table('invoices')
                ->whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->sum('amount');
        }

        // Expenses by category (sum of charges grouped by expense name)
        $expenseSums = DB::table('expenses')
            ->leftJoin('charges', 'expenses.id', '=', 'charges.expense_id')
            ->select('expenses.name', DB::raw('COALESCE(SUM(charges.amount),0) as total'))
            ->groupBy('expenses.name')
            ->get();

        $expensesCategoryLabels = $expenseSums->pluck('name')->toArray();
        $expensesCategoryData = $expenseSums->pluck('total')->map(function($v){ return (float) $v; })->toArray();

        if(Auth()->user()->role_id == 3){
             $fuels = DB::table('fuels')
            ->leftJoin('contractors', 'fuels.contractor_id', 'contractors.id')
            ->select('fuels.*', 'contractors.name as contractor')
            ->get();
            $reports = DB::select("SELECT i.name, (SELECT SUM(f.totalprice) FROM fuels f WHERE f.institution = i.name ) as 'total_amount', (SELECT SUM(f.fuel) FROM fuels f WHERE f.institution = i.name ) as 'total_fuel'  FROM institutions i");
             return view('fuel.index')->with('fuels', $fuels)->with('reports', $reports);
         }
            return view('home')
                ->with('balance_to_supplier', $balance_to_supplier)
                ->with('total_sales', $total_sales)
                ->with('total_suppliers', $total_suppliers)
                ->with('total', $total)
                ->with('paid', $paid)
                ->with('balances',$balances)
                ->with(compact('reports', $reports))
                ->with('users', $users)
                ->with('user', $user)
                ->with('total_driver_allowance', $total_driver_allowance)
                ->with('total_tire_expenses', $total_tire_expenses)
                ->with('total_lubricant_expenses', $total_lubricant_expenses)
                ->with('total_traffic_fines_expenses', $total_traffic_fines_expenses)
                ->with('total_expenses', $total_expenses)
                ->with('margin_after_all_expenses', $margin_after_all_expenses)
                ->with('total_number_of_vehicles', $total_number_of_vehicles)
                ->with('total_amount_on_spare_parts', $total_amount_on_spare_parts)
                ->with('total_amount_on_vehicle_inspection', $total_amount_on_vehicle_inspection)
                ->with('expenses', $expenses)
                ->with('totalSellingPrice', $totalSellingPrice)
                ->with('salesLast7Labels', $salesLast7Labels)
                ->with('salesLast7Data', $salesLast7Data)
                ->with('salesLast6Labels', $salesLast6Labels)
                ->with('salesLast6Data', $salesLast6Data)
                ->with('expensesCategoryLabels', $expensesCategoryLabels)
                ->with('expensesCategoryData', $expensesCategoryData);
                                                                                                                                                                                        
        }   

    }
}
