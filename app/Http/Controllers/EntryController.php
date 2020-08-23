<?php

namespace App\Http\Controllers;
use App\Entry;
use Illuminate\Http\Request;

class EntryController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request){
        $valitedData = $request->validate([
            'title' => 'required|min:7|max:100|unique:entries',
            'content' =>'required|min:10|max:3000'
        ]);

        $entry = new Entry();
        $entry->title = $valitedData['title'];
        $entry->content = $valitedData['content'];
        $entry->user_id = auth()->id();
        $entry->save();

        $status = 'Your entry has been published successfully';
        return back()->with(compact('status'));
    }
    public function create(){
        return view('entries.create');
    }
    public function edit(Entry $entry){
        $this->authorize('update',$entry);
        return view('entries.edit', compact('entry'));
    }

    public function update(Request $request, Entry $entry){
        $this->authorize('update',$entry);
        
        $valitedData = $request->validate([
            'title' => 'required|min:7|max:100|unique:entries,id,'.$entry->id,
            'content' =>'required|min:10|max:3000'
        ]);

        $entry->title = $valitedData['title'];
        $entry->content = $valitedData['content'];
        $entry->save();

        $status = 'Your entry has been update successfully';
        return back()->with(compact('status'));
    }
   
}
