<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Models\Backend\Category;
use App\Models\Backend\ChildCategory;
use App\Models\Backend\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChildCategoryController extends Controller
{
    public function index(Request $request){
        $child_categories = ChildCategory::with('category', 'subcategory')->latest()->paginate(10);
        if(!empty($request->input('search_title'))){
            $search = $request->input('search_title');
            $child_categories = ChildCategory::with('category', 'subcategory')
                ->where('name', 'LIKE', '%' . $search . '%')
                ->latest()
                ->paginate(10);
        }

        return view('backend.pages.child-category.index', compact('child_categories'));
    }

    public function addNewChildcategory(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'name'=> 'required|max:191|unique:sub_categories',
                'slug'=> 'max:191|unique:sub_categories',
                'category_id'=> 'required',
                'sub_category_id'=> 'required',
            ]);

            $request->slug=='' ? $slug = Str::slug($request->name) : $slug = $request->slug;
            $child_category =  ChildCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'image' => $request->image,
            ]);

            // category meta data add
            $Metas = [
                'meta_title'=> purify_html($request->meta_title),
                'meta_tags'=> purify_html($request->meta_tags),
                'meta_description'=> purify_html($request->meta_description),

                'facebook_meta_tags'=> purify_html($request->facebook_meta_tags),
                'facebook_meta_description'=> purify_html($request->facebook_meta_description),
                'facebook_meta_image'=> $request->facebook_meta_image,
                'twitter_meta_tags'=> purify_html($request->twitter_meta_tags),
                'twitter_meta_description'=> purify_html($request->twitter_meta_description),
                'twitter_meta_image'=> $request->twitter_meta_image,
            ];
            $child_category->metaData()->create($Metas);
            return redirect()->back()->with(FlashMsg::item_new(__('Age Added')));
        }
        $categories = Category::all();
        $sub_categories = SubCategory::all();
        return view('backend.pages.child-category.add_child_category',compact('categories', 'sub_categories'));
    }

    // get sub category for select
    public function getSubCategory(Request $request)
    {
        $sub_categories = Subcategory::where('category_id', $request->category_id)->get();
        return response()->json([
            'status' => 'success',
            'sub_categories' => $sub_categories,
        ]);
    }


    public function changeStatus($id){
        $category = ChildCategory::select('status')->where('id',$id)->first();
        if($category->status==1){
            $status = 0;
        }else{
            $status = 1;
        }
        ChildCategory::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(FlashMsg::item_new('Status Change Success'));
    }

    public function editChildCategory(Request $request, $id = NULL)
    {
        if($request->isMethod('post')) {
            $request->validate(
                [
                    'name' => 'required|max:191|unique:child_categories,name,' . $request->id,
                    'category_id' => 'required',
                    'sub_category_id' => 'required',
                    'slug' => 'max:191|unique:child_categories,slug,' . $request->id,
                ],
                [
                    'name.unique' => __('Age Already Exists.'),
                    'slug.unique' => __('Slug Already Exists.'),
                ]
            );

            $old_slug = ChildCategory::select('slug')->where('id', $request->id)->first();
            $old_image = ChildCategory::select('image')->where('id', $request->id)->first();

            ChildCategory::where('id', $request->id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'slug' => $request->slug ?? $old_slug->slug,
                'image' => $request->image ?? $old_image->image,
            ]);

            // category meta data add
            $child_category_meta_update = ChildCategory::findOrFail($id);
            $Metas = [
                'meta_title'=> purify_html($request->meta_title),
                'meta_tags'=> purify_html($request->meta_tags),
                'meta_description'=> purify_html($request->meta_description),

                'facebook_meta_tags' => purify_html($request->facebook_meta_tags),
                'facebook_meta_description' => purify_html($request->facebook_meta_description),
                'facebook_meta_image' => $request->facebook_meta_image,

                'twitter_meta_tags' => purify_html($request->twitter_meta_tags),
                'twitter_meta_description' => purify_html($request->twitter_meta_description),
                'twitter_meta_image' => $request->twitter_meta_image,
            ];

            if(is_null($child_category_meta_update->metaData()->first())){
                $child_category_meta_update->metaData()->create($Metas);
            }else{
                $child_category_meta_update->metaData()->update($Metas);
            }

            return redirect()->back()->with(FlashMsg::item_new('Age Update Success'));
        }

        $child_category = ChildCategory::find($id);
        $sub_categories = Subcategory::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        return view('backend.pages.child-category.edit_child_category',compact('child_category', 'categories', 'sub_categories'));
    }


    public function deleteChildcategory($id){
        ChildCategory::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_new('Age Deleted Success'));
    }

    public function bulkAction(Request $request){
        ChildCategory::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    // category select change to sub category in modal data
    public function getSubcategoryByCategoryId(Request $request){
        $category_id = $request->category_id;
        $subcategories = Subcategory::where('category_id',$category_id)->where('status',1)->get();
        $data = '';
        foreach ($subcategories as $sub){
            $id = $sub->id;
            $name = $sub->name;
            $data.= <<<ITEM
       <option value="{$id}">{$name}</option>
ITEM;
        }
        return response()->json(['markup' => $data]);
    }

    public function searchChildCategory(Request $request)
    {
        $child_categories = ChildCategory::where('name', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $child_categories->total() >= 1 ? view('backend.pages.child-category.search-child-category', compact('child_categories'))->render() : response()->json(['status'=>__('nothing')]);
    }
    function paginate(Request $request)
    {
        if($request->ajax()){
            if($request->string_search == ''){
                $child_categories = ChildCategory::latest()->paginate(10);
            }else{
                $child_categories = ChildCategory::where('name', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
            }
            return view('backend.pages.child-category.search-child-category', compact('child_categories'))->render();
        }
    }
}
