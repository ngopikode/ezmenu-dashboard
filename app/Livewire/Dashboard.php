<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $selectedEventId;

    public function mount(): void
    {
        $latestEvent = Event::where('user_id', Auth::id())->latest()->first();
        $this->selectedEventId = $latestEvent?->id;
    }

    public function render(): View|Application|Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user();

        // Optimize query: Select only needed columns and join template
        $events = Event::query()
            ->select([
                'events.id',
                'events.title',
                'events.subdomain',
                'events.event_date',
                'events.is_active',
                'templates.name as template_name'
            ])
            ->leftJoin('templates', 'events.template_id', '=', 'templates.id')
            ->where('events.user_id', $user->id)
            ->orderBy('events.created_at', 'desc')
            ->get();

        $activeCount = $events->where('is_active', true)->count();
        $draftCount = $events->where('is_active', false)->count();

        $selectedEvent = null;
        $stats = [
            'total_invitations' => 0,
            'total_rsvps' => 0,
            'attending' => 0,
            'not_attending' => 0,
            'maybe' => 0,
            'not_responded' => 0,
            'attending_percent' => 0,
            'not_attending_percent' => 0,
            'maybe_percent' => 0,
            'not_responded_percent' => 0,
        ];

        if ($this->selectedEventId) {
            // Fetch selected event with relationships for stats
            // We fetch fresh from DB to get relationships efficiently
            $selectedEvent = Event::with(['rsvps', 'invitations'])
                ->find($this->selectedEventId);

            if ($selectedEvent) {
                $rsvps = $selectedEvent->rsvps;
                $totalInvitations = $selectedEvent->invitations->count();
                $totalRsvps = $rsvps->count();

                $attending = $rsvps->whereIn('attendance', ['yes', 'Hadir'])->count();
                $notAttending = $rsvps->whereIn('attendance', ['no', 'Tidak Hadir', 'Maaf'])->count();
                $maybe = $rsvps->whereIn('attendance', ['maybe', 'Maybe'])->count();

                $notResponded = $totalInvitations - $totalRsvps;
                $notResponded = max(0, $notResponded);

                if ($totalInvitations > 0) {
                    $stats['total_invitations'] = $totalInvitations;
                    $stats['total_rsvps'] = $totalRsvps;
                    $stats['attending'] = $attending;
                    $stats['not_attending'] = $notAttending;
                    $stats['maybe'] = $maybe;
                    $stats['not_responded'] = $notResponded;

                    $stats['attending_percent'] = round(($attending / $totalInvitations) * 100);
                    $stats['not_attending_percent'] = round(($notAttending / $totalInvitations) * 100);
                    $stats['maybe_percent'] = round(($maybe / $totalInvitations) * 100);
                    $stats['not_responded_percent'] = round(($notResponded / $totalInvitations) * 100);
                }
            }
        }

        return view('livewire.dashboard', [
            'user' => $user,
            'events' => $events,
            'activeCount' => $activeCount,
            'draftCount' => $draftCount,
            'selectedEvent' => $selectedEvent,
            'stats' => $stats
        ]);
    }

    public function updatedSelectedEventId()
    {
        // This runs when dropdown changes
    }
}
