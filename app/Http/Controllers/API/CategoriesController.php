<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{

    use GeneralTrait;

    public function index(){
        $categories = Category::selection()-> get();
        //return response()->json($categories);
        return $this->returnData('categories', $categories , 'success');

    }


    public function categoryById(Request $request){
        $category = Category::selection()-> find($request -> id);
        if(!$category) {
            return $this->returnError('001', 'This Category Not Found');
        }
        else {
            return $this->returnData('category', $category , 'success');
        }

        //return response()->json($category);
    }

    public function changeStatus(Request $request){

         Category::where('id' ,$request -> id)->update(['active' =>$request -> active]);
         return $this->returnSuccessMessage('Active Change Successfully');

    }





}

