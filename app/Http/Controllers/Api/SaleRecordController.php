<?php

namespace App\Http\Controllers\Api;


use Exception;
use App\Models\Book;
use App\Models\Category;
use App\Models\SaleRecord;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRecordRequest;
use App\Http\Resources\SaleRecordResource;
use App\Models\Customer;

class SaleRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $saleRecords=SaleRecord::latest();

        $paidTotal=(clone $saleRecords)->sum('paid');
        $amountTotal=(clone $saleRecords)->sum('amount');
        return ApiResponse::responseWithSuccess('sale record list',[
            'sale_records'=> SaleRecordResource::collection($saleRecords->get()),
            'total_paid'=>$paidTotal,
            'total_amount'=>$amountTotal
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleRecordRequest $request)
    {
        try{
            $customer=Customer::firstOrCreate(['name'=>$request->customer_name]);
            $bookPrice=Book::find($request->book_id)->price;
            $amount=$bookPrice*$request->quantity;

            SaleRecord::create([
                'book_id'=>$request->book_id,
                'customer_id'=>$customer->id,
                'quantity'=>$request->quantity,
                'price'=>$bookPrice,
                'amount'=>$amount,
                'paid'=>$request->paid,
                'due'=>$amount-$request->paid
            ]);
            return ApiResponse::responseWithSuccess('sale record added successfully');
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaleRecordRequest $request,$saleRecord)
    {
        try{
            $customer=Customer::firstOrCreate(['name'=>$request->customer_name]);
            $bookPrice=Book::find($request->book_id)->price;
            $amount=$bookPrice*$request->quantity;

            $saleRecord=SaleRecord::find($saleRecord);
            if(!$saleRecord){
                return ApiResponse::responseWithNotFound();
            }
            $saleRecord->book_id=$request->book_id;
            $saleRecord->customer_id=$customer->id;
            $saleRecord->quantity=$request->quantity;
            $saleRecord->price=$bookPrice;
            $saleRecord->amount=$amount;
            $saleRecord->paid=$request->paid;
            $saleRecord->due=$amount-$request->paid;
            $saleRecord->save();

            return ApiResponse::responseWithSuccess('sale record updated successfully');
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($saleRecord)
    {
        try{
            $saleRecord=SaleRecord::find($saleRecord);
            if(!$saleRecord){
                return ApiResponse::responseWithNotFound();
            }
            $saleRecord->delete();
            return ApiResponse::responseWithSuccess('deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }
}
