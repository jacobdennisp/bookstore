<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\bookrecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BookRecordController extends Controller
{
    /*View All Books*/
    public function ViewAllBooks(){
        $books = DB::select('select * from bookrecord');
        return response($books,200);
    }

    /*View Books that Belong to the Logged In User*/
    public function ViewMyBooks(Request $request){
        $author_id = $request->user()->id;
        $books = DB::select('select * from bookrecord where author_id = ?', [$author_id]);
        if($books == Null){
            $response = ['message' =>  'No Books found, You have not added any Books yet'];
            return response($response, 404);
        } else {
            return response($books, 200);
        }
    }

    /*Create a New Book that belongs to the Loggedin User*/
    public function AddNewBook(Request $request) {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'publish_date'=>'required',
        ]);
        $books = new bookrecord([
            'name'=> $request->get('name'),
            'author_id'=>$request->user()->id,
            'description'=>$request->get('description'),
            'publish_date'=>$request->get('publish_date'),
            'created_by'=>$request->user()->name,
            'updated_by'=>$request->user()->name,
            'book_files'=>$request->get('publish_date')
        ]);
        $books->save();
        $response = ['message' =>  'Book Added to the Record'];
        return response($response, 200);
    }

    /* Update Book based on the user id */
    public function updateMyBook(Request $request,$id){
        
        $data = bookrecord::find($id);
        $authorid=$data -> author_id;
        
        $editoraccess = $request->user()->id;      
            
        if ($editoraccess != $authorid){
            $response = ['message' =>  'You do not have Access to This Book'];
            return response($response, 401);     
        } else  {
           
            $books = bookrecord::find($id);
            $books->name = request('name');
            $books->description = request('description');
            $books->publish_date = request('publish_date');
            $books->save();
            $books->update($request->all());
            $response = ['message' =>  'Book was Updated Successfully'];
            return response($response, 200);   
        }
    }

    //Delete Book Details based on the user Id*/
    public function deleteMyBook(Request $request, $id)
    {
        $data = bookrecord::find($id);
        $authorid=$data -> author_id;
        
        $editoraccess = $request->user()->id;      
            
        if ($editoraccess != $authorid){
            $response = ['message' =>  'You do not have Access to This Book'];
            return response($response, 401); 
        } else {
            bookrecord::find($id)->delete();
            $response = ['message' =>  'Book Deleted Successfully'];
            return response($response, 200);
        }
    }

    //Check access based on user id
    public function checkaccess(Request $request, $id)
    {    
        $data = bookrecord::find($id);
        $authorid=$data -> author_id;        
        $editoraccess = $request->user()->id;              

        if ($editoraccess != $authorid) {
            $response = ['message' =>  'You Do Not have access to this Book'];
            return response($response, 401);
        } else {
            
            $response = ['message' =>  'You have access to this Book'];
            return response($response, 200);
        }
    }
    
}
