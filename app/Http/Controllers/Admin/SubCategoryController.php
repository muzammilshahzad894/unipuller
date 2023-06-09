<?php

namespace App\Http\Controllers\Admin;

use App\{
    Models\Category,
    Models\Subcategory
};
use Illuminate\Http\Request;
use Validator;
use Datatables;

class SubCategoryController extends AdminBaseController
{
    //*** JSON Request
    public function datatables()
    {
         $datas = Subcategory::latest('id')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('category', function(Subcategory $data) {
                                return $data->category->name;
                            })
                            ->addColumn('status', function(Subcategory $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-subcat-status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>'.__("Activated").'</option><<option data-val="0" value="'. route('admin-subcat-status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>'.__("Deactivated").'</option>/select></div>';
                            })
                            ->addColumn('attributes', function(Subcategory $data) {
                                $buttons = '<div class="action-list"><a data-href="' . route('admin-attr-createForSubcategory', $data->id) . '" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>'.__("Create").'</a>';
                                if ($data->attributes()->count() > 0) {
                                  $buttons .= '<a href="' . route('admin-attr-manage', $data->id) .'?type=subcategory' . '" class="edit"> <i class="fas fa-edit"></i>'.__("Manage").'</a>';
                                }
                                $buttons .= '</div>';

                                return $buttons;
                            })
                            ->addColumn('action', function(Subcategory $data) {
                                return '<div class="action-list"><a data-href="' . route('admin-subcat-edit',$data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>'.__('Edit').'</a><a href="javascript:;" data-href="' . route('admin-subcat-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            })
                            ->rawColumns(['status','attributes','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index(){
        return view('admin.subcategory.index');
    }

    //*** GET Request
    public function create()
    {
      	$cats = Category::all();
        return view('admin.subcategory.create',compact('cats'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'slug' => 'unique:subcategories|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'slug.unique' => __('This slug has already been taken.'),
            'slug.regex' => __('Slug Must Not Have Any Special Characters.')
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Subcategory();
        $input = $request->all();
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
    	$cats = Category::all();
        $data = Subcategory::findOrFail($id);
        return view('admin.subcategory.edit',compact('data','cats'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'slug' => 'unique:subcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'slug.unique' => __('This slug has already been taken.'),
            'slug.regex' => __('Slug Must Not Have Any Special Characters.')
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Subcategory::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Status
    public function status($id1,$id2)
    {
        $data = Subcategory::findOrFail($id1);
        $data->status = $id2;
        $data->update();
        //--- Redirect Section
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function load($id)
    {
        $cat = Category::findOrFail($id);
        return view('load.subcategory',compact('cat'));
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Subcategory::findOrFail($id);


        if($data->attributes->count()>0)
        {
        //--- Redirect Section
        $msg = 'Remove the Attributes first !';
        return response()->json($msg);
        //--- Redirect Section Ends
        }
        if($data->childs->count()>0)
        {
        //--- Redirect Section
        $msg = 'Remove the Child Categories first !';
        return response()->json($msg);
        //--- Redirect Section Ends
        }
        if($data->products->count()>0)
        {
        //--- Redirect Section
        $msg = 'Remove the products first !';
        return response()->json($msg);
        //--- Redirect Section Ends
        }


        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function import()
    {
        return view('admin.subcategory.subcategory_csv');
    }

    //*** POST Request
    public function importSubmit(Request $request)
    {
        // dd($request->csvfile);
        $log = "";
        //--- Validation Section
        $rules = [
            'csvfile'      => 'required|mimes:csv,txt',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $filename = '';
        if ($file = $request->file('csvfile')) {
            $filename = time() . '-' . $file->getClientOriginalExtension();
            $file->move('assets/temp_files', $filename);
        }

        $file = fopen(public_path('assets/temp_files/' . $filename), "r");
        $i = 1;
        while (($line = fgetcsv($file)) !== FALSE) {
            
            if ($i != 1) {
                // dd($request->csvfile);
                $category = Category::where('name', $line[0])->first();
                if ($category) {
                    if (!Subcategory::where('category_id', $category->id)->where('name', $line[1])->exists()) {
                        $data = new Subcategory();
                        $input['category_id'] = $category->id;
                        $input['name'] = $line[1];
                        $input['slug'] = \Str::slug($line[1]);

                        $input['status'] = 1;
                        $input['language_id'] = 1;
                        
                        $data->fill($input)->save();
                    } else {
                        $log .= "<br>" . __('Row No') . ": " . $i . " - " . __('Duplicate Sub Category!') . "<br>";
                    }
                } else {
                    $data = new Category();
                    $input['name'] = $line[0];
                    $input['slug'] = \Str::slug($line[0]);

                    $input['status'] = 1;
                    $input['language_id'] = 1;

                    $data->fill($input)->save();

                    //--- Logic Section
                    $data2 = new Subcategory();
                    $input2['category_id'] = $data->id;
                    $input2['name'] = $line[1];
                    $input2['slug'] = \Str::slug($line[1]);

                    $input2['status'] = 1;
                    $input2['language_id'] = 1;
                    // Save Data
                    $data2->fill($input2)->save();
                }
            }

            $i++;
        }
        fclose($file);

        //--- Redirect Section
        $msg = __('Bulk Sub Category File Imported Successfully.') . $log;
        return response()->json($msg);
    }
}
