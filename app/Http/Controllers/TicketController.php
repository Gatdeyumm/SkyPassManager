<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use App\Events\UserLog;
use App\Notifications\BookNotification;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();
        return view('dashboard', compact('tickets'));
    }

    public function create()
    {
        return view('dashboard');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'from' => 'required',
            'to' => 'required',
            'country' => 'required',
            'city' => 'required',
            'price' => 'required|numeric',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'duration' => 'required',
        ]);

        // Create the ticket
        $ticket = Ticket::create($data);

        $log_entry = Auth::user()->name . " added a ticket ". '"' . $ticket->name . '"';
        event(new UserLog($log_entry));

        return redirect()->route('dashboard');
    }

    public function destroy(Ticket $ticket)
    {
        // Delete the ticket record here
        $ticket->delete();
        $log_entry = Auth::user()->name . " deleted a ticket ". '"' . $ticket->name . '"';
        event(new UserLog($log_entry));

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    public function update(Request $request, Ticket $ticket)
    {
        // Get the current values of the ticket before updating
        $oldName = $ticket->name;
        $oldFrom = $ticket->from;
        $oldTo = $ticket->to;
        $oldCountry = $ticket->country;
        $oldCity = $ticket->city;
        $oldPrice = $ticket->price;
        $oldDepartureTime = $ticket->departure_time;
        $oldArrivalTime = $ticket->arrival_time;
        $oldDuration = $ticket->duration;

        // Validate and update the ticket's data here
        $data = $request->validate([
            'name' => 'required',
            'from' => 'required',
            'to' => 'required',
            'country' => 'required',
            'city' => 'required',
            'price' => 'required|numeric',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'duration' => 'required',
        ]);

        $ticket->update($data);

        $name_updated = false;
        $from_updated = false;
        $to_updated = false;
        $country_updated = false;
        $city_updated = false;
        $price_updated = false;
        $departure_time_updated = false;
        $arrival_time_updated = false;
        $duration_updated = false;

        // Create log entry for name update
        $log_entry_name = Auth::user()->name . " updated a ticket name";
        if ($oldName !== $data['name']) {
            $log_entry_name .= ' from "' . $oldName . '" to "' . $data['name'] . '"';
            $name_updated = true;
        }

        // Create log entry for from update
        $log_entry_from = Auth::user()->name . " updated a ticket from";
        if ($oldFrom !== $data['from']) {
            $log_entry_from .= ' from "' . $oldFrom . '" to "' . $data['from'] . '"';
            $from_updated = true;
        }

        // Create log entry for to update
        $log_entry_to = Auth::user()->name . " updated a ticket to";
        if ($oldTo !== $data['to']) {
            $log_entry_to .= ' from "' . $oldTo . '" to "' . $data['to'] . '"';
            $to_updated = true;
        }

        // Create log entry for country update
        $log_entry_country = Auth::user()->name . " updated a ticket country";
        if ($oldCountry !== $data['country']) {
            $log_entry_country .= ' from "' . $oldCountry . '" to "' . $data['country'] . '"';
            $country_updated = true;
        }

        // Create log entry for city update
        $log_entry_city = Auth::user()->name . " updated a ticket city";
        if ($oldCity !== $data['city']) {
            $log_entry_city .= ' from "' . $oldCity . '" to "' . $data['city'] . '"';
            $city_updated = true;
        }

        // Create log entry for price update
        $log_entry_price = Auth::user()->name . " updated a ticket price";
        if ($oldPrice !== $data['price']) {
            $log_entry_price .= ' from "' . $oldPrice . '" to "' . $data['price'] . '"';
            $price_updated = true;
        }

        // Create log entry for departure_time update
        $log_entry_departure_time = Auth::user()->name . " updated a ticket departure time";
        if ($oldDepartureTime !== $data['departure_time']) {
            $log_entry_departure_time .= ' from "' . $oldDepartureTime . '" to "' . $data['departure_time'] . '"';
            $departure_time_updated = true;
        }

        // Create log entry for arrival_time update
        $log_entry_arrival_time = Auth::user()->name . " updated a ticket arrival time";
        if ($oldArrivalTime !== $data['arrival_time']) {
            $log_entry_arrival_time .= ' from "' . $oldArrivalTime . '" to "' . $data['arrival_time'] . '"';
            $arrival_time_updated = true;
        }

        // Create log entry for duration update
        $log_entry_duration = Auth::user()->name . " updated a ticket duration";
        if ($oldDuration !== $data['duration']) {
            $log_entry_duration .= ' from "' . $oldDuration . '" to "' . $data['duration'] . '"';
            $duration_updated = true;
        }

        if ($name_updated) {
            event(new UserLog($log_entry_name));
        }
        if ($from_updated) {
            event(new UserLog($log_entry_from));
        }
        if ($to_updated) {
            event(new UserLog($log_entry_to));
        }
        if ($country_updated) {
            event(new UserLog($log_entry_country));
        }
        if ($city_updated) {
            event(new UserLog($log_entry_city));
        }
        if ($price_updated) {
            event(new UserLog($log_entry_price));
        }
        if ($departure_time_updated) {
            event(new UserLog($log_entry_departure_time));
        }
        if ($arrival_time_updated) {
            event(new UserLog($log_entry_arrival_time));
        }
        if ($duration_updated) {
            event(new UserLog($log_entry_duration));
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function book(Request $request, Ticket $ticket){
        $user = User::find(2); 

        $user->notify(new BookNotification($ticket));

        return redirect()->route('dashboard')->with('success', 'Thanks for booking! Check your email for details.');
    }

    
}
