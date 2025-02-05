<?php

namespace App\Http\Controllers\Workflow;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin\Factory;
use App\Models\Planning\Status;
use App\Models\Workflow\Quotes;
use App\Services\QuoteCalculator;
use Illuminate\Support\Facades\DB;
use App\Models\Companies\Companies;
use App\Models\Workflow\QuoteLines;

use App\Http\Controllers\Controller;
use App\Models\Companies\CompaniesContacts;
use App\Models\Companies\CompaniesAddresses;
use App\Models\Accounting\AccountingDelivery;
use App\Http\Requests\Workflow\UpdateQuoteRequest;
use App\Models\Accounting\AccountingPaymentMethod;
use App\Models\Accounting\AccountingPaymentConditions;

class QuotesController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        $CurentYear = Carbon::now()->format('Y');

        //Quote data for chart
        $data['quotesDataRate'] = DB::table('quotes')
                                    ->select('statu', DB::raw('count(*) as QuoteCountRate'))
                                    ->groupBy('statu')
                                    ->get();
        //Quote data for chart
        $data['quoteMonthlyRecap'] = DB::table('quote_lines')->selectRaw('
                                                                MONTH(delivery_date) AS month,
                                                                SUM((selling_price * qty)-(selling_price * qty)*(discount/100)) AS quoteSum
                                                            ')
                                                            ->whereYear('created_at', $CurentYear)
                                                            ->groupByRaw('MONTH(delivery_date) ')
                                                            ->get();

        return view('workflow/quotes-index')->with('data',$data);
    }

    /**
     * @param $id
     * @return View
     */
    public function show(Quotes $id)
    {
        $CompanieSelect = Companies::select('id', 'code','label')->where('active', 1)->get();
        $AddressSelect = CompaniesAddresses::select('id', 'label','adress')->get();
        $ContactSelect = CompaniesContacts::select('id', 'first_name','name')->get();
        $AccountingConditionSelect = AccountingPaymentConditions::select('id', 'code','label')->get();
        $AccountingMethodsSelect = AccountingPaymentMethod::select('id', 'code','label')->get();
        $AccountingDeleveriesSelect = AccountingDelivery::select('id', 'code','label')->get();
        $QuoteCalculator = new QuoteCalculator($id);
        $totalPrice = $QuoteCalculator->getTotalPrice();
        $subPrice = $QuoteCalculator->getSubTotal();
        $vatPrice = $QuoteCalculator->getVatTotal();
        $TotalServiceProductTime = $QuoteCalculator->getTotalProductTimeByService();
        $TotalServiceSettingTime = $QuoteCalculator->getTotalSettingTimeByService();
        $TotalServiceCost = $QuoteCalculator->getTotalCostByService();
        $TotalServicePrice = $QuoteCalculator->getTotalPriceByService();
        $previousUrl = route('quotes.show', ['id' => $id->id-1]);
        $nextUrl = route('quotes.show', ['id' => $id->id+1]);

        //DB information mustn't be empty.
        $Factory = Factory::first();
        if(!$Factory){
            return redirect()->route('admin.factory')->with('error', 'Please check factory information');
        }
        
        $Status = Status::select('id')->orderBy('order')->first();
        if(!$Status){
            return redirect()->route('admin.factory')->withErrors('Please add Kanban information before');
        }

        return view('workflow/quotes-show', [
            'Quote' => $id,
            'CompanieSelect' => $CompanieSelect,
            'AddressSelect' => $AddressSelect,
            'ContactSelect' => $ContactSelect,
            'AccountingConditionSelect' => $AccountingConditionSelect,
            'AccountingMethodsSelect' => $AccountingMethodsSelect,
            'AccountingDeleveriesSelect' => $AccountingDeleveriesSelect,
            'Factory' => $Factory,
            'totalPrices' => $totalPrice,
            'subPrice' => $subPrice, 
            'vatPrice' => $vatPrice,
            'TotalServiceProductTime'=> $TotalServiceProductTime,
            'TotalServiceSettingTime'=> $TotalServiceSettingTime,
            'TotalServiceCost'=> $TotalServiceCost,
            'TotalServicePrice'=> $TotalServicePrice,
            'previousUrl' =>  $previousUrl,
            'nextUrl' =>  $nextUrl,
        ]);
    }
    
    /**
     * @param Request $request
     * @return View
     */
    public function update(UpdateQuoteRequest $request)
    {
        $Quote = Quotes::find($request->id);
        $Quote->label=$request->label;
        $Quote->customer_reference=$request->customer_reference;
        $Quote->companies_id=$request->companies_id;
        $Quote->companies_contacts_id=$request->companies_contacts_id;
        $Quote->companies_addresses_id=$request->companies_addresses_id;
        $Quote->validity_date=$request->validity_date;
        $Quote->accounting_payment_conditions_id=$request->accounting_payment_conditions_id;
        $Quote->accounting_payment_methods_id=$request->accounting_payment_methods_id;
        $Quote->accounting_deliveries_id=$request->accounting_deliveries_id;
        $Quote->comment=$request->comment;
        $Quote->save();
        
        return redirect()->route('quotes.show', ['id' =>  $Quote->id])->with('success', 'Successfully updated quote');
    }
}
