<?php

namespace App\Http\Controllers;
use App\Models\FlashQuestion;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function editflash($id)
    {
        $record = FlashQuestion::find($id);
        //dd($record);
        return view('admin.records.editflash',['record' => $record]);
    }
    
    public function updatequestion(Request $request, $id)
{
    $record = FlashQuestion::find($id);
    $record->update([
        'subject' => $request->input('subject'),
        'question' => $request->input('question'),
        'answer' => $request->input('answer'),
        
    ]);
         $flashData = FlashQuestion::all();
        return view('admin.flashDashboard',['chal' =>  $flashData]);
}

            public function destroy($id)
            {
                $record = FlashQuestion::find($id);
                $record->delete();

                $flashData = FlashQuestion::all();
        return view('admin.flashDashboard',['chal' =>  $flashData]);
            }
            
            public function pfmpflash()
            {
                $flashData = FlashQuestion::all()->where('subject', 'RfMP');
                return view('admin.records.pfmpflash',['chal' =>  $flashData]); 
            }

            public function pgmpflash()
            {
                $flashData = FlashQuestion::all()->where('subject', 'PgMP');
                return view('admin.records.pgmpflash',['chal' =>  $flashData]); 
            }

            public function pmpflash()
            {
                $flashData = FlashQuestion::all()->where('subject', 'PMP');
                return view('admin.records.pmpflash',['chal' =>  $flashData]); 
            }

            public function pmiacpflash()
            {
                $flashData = FlashQuestion::all()->where('subject', 'PMI-ACP');
                return view('admin.records.pmiacpflash',['chal' =>  $flashData]); 
            }

            public function pmirmpflash()
            {
                $flashData = FlashQuestion::all()->where('subject', 'PMI-RMP');
                return view('admin.records.pmirmpflash',['chal' =>  $flashData]); 
            }
}