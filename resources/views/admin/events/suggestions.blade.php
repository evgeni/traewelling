@extends('admin.layout')

@section('title', 'Veranstaltungsvorschläge')

@section('content')
    <div class="card">
        <div class="card-body">
            @if($suggestions->count() == 0)
                <p class="font-weight-bold text-danger">Es sind aktuell keine Vorschläge vorhanden. :(</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Organizer</th>
                            <th>Begin</th>
                            <th>End</th>
                            <th>External URL</th>
                            <th>Suggesting user</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suggestions as $event)
                            <tr class="{{$event->begin->isPast() ? 'table-danger' : ''}}">
                                <td>{{$event->name}}</td>
                                <td>{{$event->host}}</td>
                                <td>
                                    {{$event->begin->format('d.m.Y')}}
                                    @if($event->begin->isPast())
                                        <div class="spinner-grow text-danger" style="width: 1rem; height: 1rem;"></div>
                                    @elseif($event->begin->isBefore(\Illuminate\Support\Facades\Date::today()->addDays(3)))
                                        <div class="spinner-grow text-info" style="width: 1rem; height: 1rem;"></div>
                                    @endif
                                </td>
                                <td>{{$event->end->format('d.m.Y')}}</td>
                                <td>{{$event->url}}</td>
                                <td>
                                    @isset($event->user)
                                        <a href="{{route('admin.users.user', ['id' => $event->user->id])}}"
                                           target="_blank">
                                            {{$event->user->username}}
                                        </a>
                                    @endisset
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="{{route('admin.events.suggestions.deny')}}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$event->id}}"/>

                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-success"
                                               href="{{route('admin.events.suggestions.accept', ['id' => $event->id])}}">
                                                Edit & accept
                                            </a>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Decline
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
