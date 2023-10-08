<?php

namespace App\Http\Controllers\Api;

use App\Filters\BookFilter;
use App\Helpers\ApiResponse;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use Exception;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BookFilter $filter)
    {
        return ApiResponse::responseWithSuccess('Book list', BookResource::collection(Book::filter($filter)->get()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        try {
            $book = Book::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'author' => $request->author,
                'price' => $request->price,
            ]);
            return ApiResponse::responseWithSuccess('Book added successfully!', new BookResource($book));
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, $book)
    {
        try {
            $book = Book::find($book);

            if (!$book) {
                return ApiResponse::responseWithNotFound();
            }

            $book->category_id = $request->category_id;
            $book->name = $request->name;
            $book->author = $request->author;
            $book->price = $request->price;
            $book->save();
            return ApiResponse::responseWithSuccess('successfully updated', new BookResource($book));
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($book)
    {
        try {

            $book = Book::find($book);

            if (!$book) {
                return ApiResponse::responseWithNotFound();
            }
            $book->delete();
            return ApiResponse::responseWithSuccess('Book deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }
}
