<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;

class EventParticipantController extends Controller
{
    public function index(Event $event)
    {
        $members = Member::where('school_id', $event->school_id)
            ->where('team_id', $event->team_id)
            ->with(['participants' => function ($q) use ($event) {
                $q->where('event_id', $event->id);
            }])
            ->get();

        return view('admin.event_participant', compact('event', 'members'));
    }
}

