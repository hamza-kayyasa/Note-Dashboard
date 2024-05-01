<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::query()
        ->where('user_id' , request()->user()->id)
        ->orderBy('created_at' , 'desc')->paginate(10);
        return view('note.index' , ['notes' => $notes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('note.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'note' => ['required' , 'string']
        ]);

        $data['user_id'] = request()->user()->id;
        $note = Note::create($data);

        return to_route('note.show' , $note)->with('message' , 'Note created Succesfuly');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        if($note->user_id !== request()->user()->id){
            abort(404);
        }
        return view('note.show' , ['note' => $note]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        if($note->user_id !== request()->user()->id){
            abort(404);
        }
        return view('note.edit' , ['note' => $note]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        if($note->user_id !== request()->user()->id){
            abort(404);
        }
        $data = $request->validate([
            'note' => ['required' , 'string']
        ]);

        $note->update($data);

        return to_route('note.show' , $note)->with('message' , 'Note Updated Successfuly');
    }


    public function search(Request $request){

        if($request->ajax()){
    
            $data=Note::where('id','like','%'.$request->search.'%')
            ->orwhere('note','like','%'.$request->search.'%')
            ->orwhere('user_id','like','%'.$request->search.'%')->get();
    
    
            $output='';
        if(count($data)>0){
    
             $output ='
                <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">note</th>
                    <th scope="col">user_id</th>
                </tr>
                </thead>
                <tbody>';
    
                    foreach($data as $row){
                        $output .='
                        <tr>
                        <th scope="row">'.$row->id.'</th>
                        <td>'.$row->note.'</td>
                        <td>'.$row->user_id.'</td>
                        </tr>
                        ';
                    }
    
    
    
             $output .= '
                 </tbody>
                </table>';
    }
        else{
    
            $output .='No results';
    
        }
    
        return $output;
    
        }
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        if($note->user_id !== request()->user()->id){
            abort(404);
        }
        $note->delete();
        return to_route('note.index')->with('message' , 'Note Deleted Successfuly');
    }
}
