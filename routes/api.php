<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Admin\AdminActionController;
use App\Http\Controllers\BookRecordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Common Route */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*Public Route*/
Route::group(['middleware'=>['cors','json.response']],function(){
   Route::post('/login',[UserAuthController::class,'userlogin'])->name('login.api');
});

/*Private Route*/

Route::middleware('auth:api')->group(function () {
    Route::post('/logout',[UserAuthController::class,'userlogout'])->name('logout.api');
});
/**********************************************************************************/
/********************************Routes for Authors********************************/
/**********************************************************************************/
/*Route for Add New Book*/
Route::middleware('auth:api')->group(function () {
    Route::post('/author/books/addnewbooks',[BookRecordController::class,'AddNewBook'])->middleware('api.Author')->name('AuthorAddNewBook.api');
});

/*Route to View all Books in the DB*/
Route::middleware('auth:api')->group(function () {
    Route::get('/author/books/viewall',[BookRecordController::class,'ViewAllBooks'])->middleware('api.Author')->name('AuthorViewAllBooks.api');
});

/*Route to View all Books written by the logged in Author*/
Route::middleware('auth:api')->group(function () {
    Route::get('/author/books/viewmybooks',[BookRecordController::class,'ViewMyBooks'])->middleware('api.Author')->name('AuthorViewMyBooks.api');
});

/*Route to Update the Book written by the Logged in Author*/
Route::middleware('auth:api')->group(function () {
    Route::put('/author/books/updatemybooks/{id}',[BookRecordController::class,'updateMyBook'])->middleware('api.Author')->name('AuthorUpdateMyBook.api');
});

/*Route to Delete the Book written by the Logged in Author*/
Route::middleware('auth:api')->group(function () {
    Route::post('/author/books/deletemybook/{id}',[BookRecordController::class,'deleteMyBook'])->middleware('api.Author')->name('AuthorDeleteMyBooks.api');
});

/**********************************************************************************/
/********************************Routes for Admins*********************************/
/**********************************************************************************/

/********************************Admin user CRUD*********************************/

/*Route to View All Authors/users*/
Route::middleware('auth:api')->group(function () {
    Route::get('/admin/author/viewall',[AdminActionController::class,'ViewAllUsers'])->middleware('api.Admin')->name('AdminViewAllAuthors.api');
});

/* Route to View a Particular Author */
Route::middleware('auth:api')->group(function () {
    Route::get('/admin/author/viewall/{id}',[AdminActionController::class,'viewUser'])->middleware('api.Admin')->name('AdminViewAAuthor.api');
});

/* Route to Create a Author */
Route::middleware('auth:api')->group(function () {
    Route::post('/admin/author/add',[AdminActionController::class,'createUser'])->middleware('api.Admin')->name('AdminAddaUser.api');
});

/* Route to Remove a Author */
Route::middleware('auth:api')->group(function () {
    Route::post('/admin/author/remove/{id}',[AdminActionController::class,'deleteUser'])->middleware('api.Admin')->name('AdminRemoveAUser.api');
});

/********************************Admin Books CRUD*********************************/

/* Route to View All Books */
Route::middleware('auth:api')->group(function () {
    Route::get('/admin/books/viewall',[AdminActionController::class,'ViewAllBooks'])->middleware('api.Admin')->name('AdminViewAllBooks.api');
});

/* Route to View A Particular Book */
Route::middleware('auth:api')->group(function () {
    Route::get('/admin/books/author/{id}',[AdminActionController::class,'ViewBooksfromAuthor'])->middleware('api.Admin')->name('AdminViewBooksFromAuthor.api');
});

/* Route to Add New Book */
Route::middleware('auth:api')->group(function () {
    Route::post('/admin/books/addnew',[AdminActionController::class,'AddNewBook'])->middleware('api.Admin')->name('AdminAddANewBook.api');
});

/* Route to update a Book */
Route::middleware('auth:api')->group(function () {
    Route::put('/admin/books/update/{id}',[AdminActionController::class,'updateBook'])->middleware('api.Admin')->name('AdminUpdateBook.api');
});

/* Route to Delete a Book */
Route::middleware('auth:api')->group(function () {
    Route::post('/admin/books/delete/{id}',[AdminActionController::class,'deleteBook'])->middleware('api.Admin')->name('AdminDeleteBook.api');
});

/* Trial Route */
Route::middleware('auth:api')->group(function () {
    Route::post('/author/books/checkacess/{id}',[BookRecordController::class,'checkaccess'])->middleware('api.Author')->name('checkaccess.api');
});