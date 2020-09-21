<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\bookrecord;
class AdminActionController extends Controller
{
/**********************************************************************************/
/*************************************Author CRUD**********************************/
/**********************************************************************************/
    /*View All Books*/
    public function ViewAllUsers(){
        $users = DB::select('select * from users where type = 0');
        return response($users,200);
    }

    /*View Books that Belong to the Logged In User*/
    public function viewUser(Request $request,$id){        
        $users = DB::select('select * from users where id = ?',[$id]);
        return response($users,200);
    }
    
    /*Create User*/
    public function createUser(Request $request){        
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:6|confirmed',
        ]);
        if($validator->fails()){
            return response(['error'=>$validator->errors()->all()],422);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] =Str::random(10);
        $request['type'] = $request['type'] ? $request['type']  : 0;
        $user = User::create($request->toArray());
        $token = $user->createToken('Password Provider')->accessToken;
        $response=['token'=>$token];
        return response($response, 200);
    }

    /* Update Book based on the user id */
    public function updateUser(Request $request,$id){
        $users = DB::table('users')->find($id);
        $users->name = request('name');
        $users->email = request('email');
        $users->password = request('password');
        $users->update($request->all());
        $response = ['message' =>  'User Updated Successfully'];
        return response($response, 200);
    }

    //Delete Book Details based on the user Id*/
    public function deleteUser(Request $request, $id)
    {      
        User::find($id)->delete();
        $response = ['message' =>  'User Deleted Successfully'];
        return response($response, 200);      
    }
/**********************************************************************************/
/***********************************Books CRUD*************************************/
/**********************************************************************************/

    /*View All Books*/
    public function ViewAllBooks(){
        $books = DB::select('select * from bookrecord');
        return response($books,200);
    }

    /*View Books that Belong to the Logged In User*/
    public function ViewBooksfromAuthor(Request $request, $id){
        
        $books = DB::select('select * from bookrecord where author_id = ?', [$id]);
        if($books == Null){
            $response = ['message' =>  'No Books found, the Author does not have any books yet'];
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
            'author_id'=>$request->get('author_id'),
            'description'=>$request->get('description'),
            'publish_date'=>$request->get('publish_date'),
            'created_by'=>$request->get('created_by'),
            'updated_by'=>$request->get('updated_by'),
            'book_files'=>$request->get('book_files')
        ]);
        $books->save();
        $response = ['message' =>  'Book Added to the Record'];
        return response($response, 200);
    }

    /* Update Book based on the user id */
    public function updateBook(Request $request,$id){        
        $books = bookrecord::find($id);
        $books->description = request('description');
        $books->publish_date = request('publish_date');
        $books->update($request->all());
        $response = ['message' =>  'Book was Updated Successfully'];
        return response($response, 200);        
    }

    //Delete Book Details based on the user Id*/
    public function deleteBook(Request $request, $id)
    {
        bookrecord::find($id)->delete();
        $response = ['message' =>  'Book Deleted Successfully'];
        return response($response, 200);
    }


}
