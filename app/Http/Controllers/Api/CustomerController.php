<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Customer;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponse::responseWithSuccess('customer list', CustomerResource::collection(Customer::with('sale_records')->get()));
    }

    public function update(Request $request, $customer)
    {
        try {
            $customer = Customer::find($customer);

            if (!$customer) {
                return ApiResponse::responseWithNotFound();
            }
            $customer->name = $request->name;
            $customer->save();
            return ApiResponse::responseWithSuccess('updated successfully');
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($customer)
    {
        try {
            $customer = Customer::find($customer);

            if (!$customer) {
                return ApiResponse::responseWithNotFound();
            }
            $customer->sale_records()->delete();
            $customer->delete();
            return ApiResponse::responseWithSuccess('deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }
}
