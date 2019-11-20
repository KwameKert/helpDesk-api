<?php

namespace App\Http\Controllers;
use App\Complaint;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $complaints = Complaint::get();
        return collect($complaints);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data =  [
            'cat_id' => $request->cat_id,
            'description' => $request->description,
            'status' => 'Pending',
            'index_no' => $request->indexNumber,
            'respondent_id' => 0
        ];

        $complaint = Complaint::create($data);
        if($complaint){
            return response()->json(['success'=>'true']);
        }else{
            return response()->json(['error'=>'Oops... Complaint not sent']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $complaint =  DB::select( DB::raw(
            "  SELECT
categories.`name`,
complaints.index_no,
complaints.`status`,
complaints.description,
users.phone,
users.`name` AS studentName,
complaints.created_at,
complaints.response
FROM
categories
INNER JOIN complaints ON categories.id = complaints.cat_id
INNER JOIN users ON complaints.index_no = users.index_no
WHERE
complaints.`id` =".$id.""));



        return collect($complaint);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $complaint = Complaint::findOrFail($id);
        $data = [
            'status' => $request->status,
            'response' => $request->response
        ];

        if($complaint->update($data)){
            return response()->json(['success'=>'Complaint Updated Successfully']);
        }else{
            return response()->json(['error'=>'Ooops cannot update complaint']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getStudentComplaints(Request $request){
        $pending_complaints = Complaint::where(['index_no'=>$request->indexNumber,'status'=>'Pending'])->get();
        $processing_complaints = Complaint::where(['index_no'=>$request->indexNumber,'status'=>'Processing'])->get();
        $completed_complaints = Complaint::where(['index_no'=>$request->indexNumber,'status'=>'Resolved'])->get();

      
          $complaints = DB::select( DB::raw(
            "SELECT
categories.`name` as category,
complaints.`description`,
complaints.`id`,
complaints.`status`,
complaints.`created_at`
FROM
complaints
INNER JOIN categories ON complaints.`cat_id` = categories.`id`  WHERE complaints.`index_no`=".$request->indexNumber." AND complaints.`status` <> 'Retracted'
            ORDER BY complaints.`id` DESC"));
       // $complaints = Complaint::where('index_no',$request->indexNumber)->get();
          $response = [
                'complaints' => $complaints,
                'pending_complaints' => count($pending_complaints),
                'processing_complaints' => count($processing_complaints),
                'completed_complaints' => count($completed_complaints)
          ];

        return collect($response);
    }


    public function fetchPendingComplaint(){

        $all_complaints = DB::select(DB::raw("SELECT
            categories.`name`,
            complaints.`index_no`,
            complaints.`status`,
             complaints.`id`,
            complaints.`description`,
            complaints.`created_at`,
            users.`phone`,
            users.`name` as studentName
            FROM
            categories
            INNER JOIN complaints ON categories.`id` = complaints.`cat_id`
            INNER JOIN users ON complaints.`index_no` = users.`index_no`  ORDER BY complaints.id DESC
            "));

        $pending_complaints = DB::select(DB::raw("SELECT
            categories.`name`,
            complaints.`index_no`,
            complaints.`status`,
             complaints.`id`,
            complaints.`description`,
            complaints.`created_at`,
            users.`phone`,
            users.`name` as studentName
            FROM
            categories
            INNER JOIN complaints ON categories.`id` = complaints.`cat_id`
            INNER JOIN users ON complaints.`index_no` = users.`index_no` WHERE complaints.`status` = 'Pending' ORDER BY complaints.id DESC
            "));

         $processing_complaints = DB::select(DB::raw("SELECT
            categories.`name`,
            complaints.`index_no`,
            complaints.`status`,
             complaints.`id`,
            complaints.`created_at`,
            complaints.`description`,
            users.`phone`,
            users.`name` as studentName
            FROM
            categories
            INNER JOIN complaints ON categories.`id` = complaints.`cat_id`
            INNER JOIN users ON complaints.`index_no` = users.`index_no` WHERE complaints.`status` = 'Processing' ORDER BY complaints.id DESC
            "));

          $resolved_complaints = DB::select(DB::raw("SELECT
            categories.`name`,
            complaints.`index_no`,
            complaints.`id`,
            complaints.`status`,
            complaints.`description`,
            complaints.`created_at`,
            users.`phone`,
            users.`name` as studentName
            FROM
            categories
            INNER JOIN complaints ON categories.`id` = complaints.`cat_id`
            INNER JOIN users ON complaints.`index_no` = users.`index_no` WHERE complaints.`status` = 'Resolved' ORDER BY complaints.id DESC
            "));

          $response = [
            'all_complaints_no' => count($all_complaints),
            'all_complaints' => $all_complaints,
           'pending_complaints' => $pending_complaints,
           'pending_complaints_no' => count($pending_complaints),
           'processing_complaints_no' => count($processing_complaints),
           'processing_complaints' => $processing_complaints,
           'resolved_complaints'  => $resolved_complaints,
           'resolved_complaints_no'  => count($resolved_complaints),
          ];

        return collect($response);

    }







}
