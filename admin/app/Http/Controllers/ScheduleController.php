<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Notifications\ScheduleCancelledNotification;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 6; // Set to display 6 items per page
        $schedules = Schedule::orderBy('date')->orderBy('start_time')->paginate($perPage);
        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'slot_limit' => 'required|integer|min:1',
        ]);

        Schedule::create($request->only(['date', 'start_time', 'end_time', 'slot_limit']));

        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        return view('schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'slot_limit' => 'required|integer|min:1',
        ]);

        $schedule->update($request->only(['date', 'start_time', 'end_time', 'slot_limit']));

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted.');
    }

    public function showBulkCreate()
    {
        return view('schedules.bulk-create');
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'interval' => 'required|integer|min:1',
            'slot_limit' => 'required|integer|min:1',


        ]);

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $interval = (int) $request->interval;





        while ($startDate->lte($endDate)) {
            if ($startDate->isWeekday()) {


                $startTime = \Carbon\Carbon::parse($request->start_time);
                $endTime = \Carbon\Carbon::parse($request->end_time);

                while ($startTime->lt($endTime)) {
                    $next = $startTime->copy()->addMinutes($interval);
                    if ($next->gt($endTime)) break;

                    $exists = \App\Models\Schedule::where('date', $startDate->format('Y-m-d'))
                        ->where('start_time', $startTime->format('H:i'))
                        ->exists();

                    if (!$exists) {
                        \App\Models\Schedule::create([
                            'date' => $startDate->format('Y-m-d'),
                            'start_time' => $startTime->format('H:i'),
                            'end_time' => $next->format('H:i'),
                            'slot_limit' => $request->slot_limit,
                        ]);
                    }

                    $startTime = $next;
                }
            }

            $startDate->addDay();
        }

        return redirect()->route('schedules.index')->with('success', 'Bulk schedules created successfully.');
    }


    

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected', []);
        $reason = $request->input('delete_reason') ?: 'Booking cancelled. No reason provided.';

        if (empty($ids)) {
            return redirect()->route('schedules.index')->with('error', 'No schedules selected.');
        }

        $appointments = Appointment::whereIn('schedule_id', $ids)->with('user')->get();

        foreach ($appointments as $appt) {
            $appt->update(['status' => 'cancelled']);
            $appt->user->notify(new ScheduleCancelledNotification($reason));
        }

        Schedule::whereIn('id', $ids)->delete();

        return redirect()->route('schedules.index')->with('success', 'Selected schedules and appointments were processed.');
    }
    



    
}
