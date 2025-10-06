<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;

class CalendarController extends Controller
{
    public function index()
    {
        return response()->json(CalendarEvent::all());
    }

    public function store(Request $request)
    {
        $event = CalendarEvent::create($request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end'   => 'required|date|after_or_equal:start',
        ]));

        return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {
        $event = CalendarEvent::findOrFail($id);
        $event->update($request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end'   => 'required|date|after_or_equal:start',
        ]));

        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = CalendarEvent::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted']);
    }
}
