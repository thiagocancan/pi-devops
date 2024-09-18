<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reminder;

class ReminderController extends Controller
{
    //get all reminders
    public function index($user_id) 
    {
        return response([
            'reminders' => Reminder::orderBy('created_at', 'desc')->with('user:id,name')->where('user_id', $user_id)->get()
        ], 200);
    }

    // show single reminder
    public function show($id) 
    {
        return response([
            'reminder' => Reminder::where('id', $id)->get()
        ], 200);
    }

    // crete a reminder
    public function store(Request $request) 
    {
        // validate filds
        $attrs = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|integer',
            'alert' => 'nullable|date',
            'repeat' => 'nullable|numeric',
            'duration' => 'nullable|integer'
        ]);

        $reminder = Reminder::create([
            'title' => $attrs['title'],
            'description' => $attrs['description'],
            'type' => $attrs['type'],
            'alert' => $attrs['alert'],
            'repeat' => $attrs['repeat'],
            'duration' => $attrs['duration'],
            'user_id' => auth()->user()->id
        ]);

        return response([
            'message' => 'Reminder created.',
            'reminder' => $reminder
        ], 200);
    }

    // update a reminder
    public function update(Request $request, $id) 
    {
        $reminder = Reminder::find($id);

        if(!$reminder)
        {
            return response([
                'message' => 'Reminder not found.'
            ], 403);
        }

        if($reminder->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        // validate filds
        $attrs = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|integer',
            'alert' => 'nullable|string',
            'repeat' => 'nullable|string',
            'duration' => 'nullable|integer'
        ]);

        $reminder->update([
            'title' => $attrs['title'],
            'description' => $attrs['description'],
            'type' => $attrs['type'],
            'alert' => $attrs['alert'],
            'repeat' => $attrs['repeat'],
            'duration' => $attrs['duration']
        ]);

        return response([
            'message' => 'Reminder updated.',
            'reminder' => $reminder
        ], 200);
    }

        // delete reminder
        public function destroy($id) 
        {
            $reminder = Reminder::find($id);
    
            if(!$reminder)
            {
                return response([
                    'message' => 'Reminder not found.'
                ], 403);
            }
    
            if($reminder->user_id != auth()->user()->id)
            {
                return response([
                    'message' => 'Permission denied.'
                ], 403);
            }

            $reminder->delete();
    
            return response([
                'message' => 'Reminder deleted.',
            ], 200);
        }
}
