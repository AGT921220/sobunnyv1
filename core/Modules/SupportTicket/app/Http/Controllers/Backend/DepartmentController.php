<?php

namespace Modules\SupportTicket\app\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\SupportTicket\app\Models\Department;

class DepartmentController extends Controller
{
    //show and add department
    public function department(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['name'=> 'required|unique:departments|max:191']);
            Department::create([
                'name'=>$request->name,
                'status'=>$request->status,
            ]);
            return back()->with(FlashMsg::item_new(__('New Department Successfully Added')));
        }
        $departments = Department::latest()->get();
        return view('supportticket::backend.department.departments',compact('departments'));
    }


    //edit department
    public function edit_department(Request $request)
    {
        $request->validate([
            'edit_name'=> 'required|max:191|unique:departments,name,'.$request->department_id,
        ]);
        Department::where('id',$request->department_id)->update([
            'name'=>$request->edit_name,
        ]);
        return redirect()->back()->with(FlashMsg::item_new(__('Department Successfully Updated')));
    }


    //status change
    public function change_status($id)
    {
        $department = Department::select('status')->where('id',$id)->first();
        $department->status == 1 ? $status = 0 : $status = 1;
        Department::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(FlashMsg::item_new(__('Status Successfully Changed')));
    }


    //delete department
    public function delete_department($id)
    {
        Department::find($id)->delete();
        return redirect()->back()->with(FlashMsg::error(__('Department Successfully Deleted')));
    }


    //delete bulk action
    public function bulk_action(Request $request){
        Department::whereIn('id',$request->ids)->delete();
        return redirect()->back()->with(FlashMsg::error(__('Selected Department Successfully Deleted')));
    }
}
