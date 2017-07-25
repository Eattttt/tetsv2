<?php

namespace App\Http\Controllers\Consult;

use App\Model\ConsultsUser;
use App\Model\ConsultType;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Help\HelpController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultController extends Controller
{
    public function index()
    {

        $phone = Auth::User()->phone;
        $consultType = ConsultType::all();
        return view('acsystem.Consult.index',compact(['phone','consultType']));
    }

    public function storePost(Requests\ConsultPostRequest $request)
    {
        $submit_time = date("Y-m-d H:i:s");
        $userId = Auth::User()->id;
        $help = new HelpController;
        $Term = $help->GetYearSemester($submit_time);
        $Info = [
            'user_id'=> $userId,
            'consults_type_id' => $request->get('consults_type_id'),
            'submit_time' => $submit_time,
            'term' => $Term['YearSemester'],
            'state'=>'待协调',
            'meta_description' =>$request->get('meta_description'),
            'phone' =>$request->get('phone'),
        ];
        ConsultsUser::create($Info);
        return redirect('/consult/index')->withSuccess('添加成功！');
    }

    public function consultHistory($userId)
    {
        $historyData= DB::table('consult_user')
            ->leftjoin('consults_type','consult_user.consults_type_id','=','consults_type.id')
            ->where('user_id',$userId)
            ->orderBy('state','desc')->get();
        return $historyData;
    }

    public function modify()
    {
        return view('acsystem.Consult.Admin.modify');
    }

    public function getContent()
    {
        $contents = ConsultType::all();
        return $contents;
    }

    public function create(Requests\ConsultTypeRequest $request)
    {
//        dd($request->all());
        $input = $request->all();
        ConsultType::create($input);
        return redirect('/consult/modify')->withSuccess('添加成功！');
    }

    public function deleteConsult(Request $request)
    {
        $input = $request->all();
        DB::table('consults_type')->whereIn('id',$input['dataArr'])->delete();
        return ('删除成功！');
    }
}
