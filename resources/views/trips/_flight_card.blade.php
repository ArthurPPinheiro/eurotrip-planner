@php
    $cabinLabels = ['economy'=>__('flights.cabin.economy'),'premium_economy'=>__('flights.cabin.premium_economy'),'business'=>__('flights.cabin.business'),'first'=>__('flights.cabin.first')];
    $flDurH = intdiv($flight->duration_minutes ?? 0, 60);
    $flDurM = ($flight->duration_minutes ?? 0) % 60;
@endphp
<div class="flight-card" id="flight-card-{{ $flight->id }}">
    <div class="flight-card-header">
        <div class="flex gap-1" style="align-items:center;flex-wrap:wrap">
            <span style="font-size:1.1rem">✈</span>
            @if($flight->flight_number)<span style="font-weight:700;letter-spacing:0.05em">{{ $flight->flight_number }}</span>@endif
            @if($flight->airline)<span style="opacity:0.8;font-size:0.85rem">{{ $flight->airline }}</span>@endif
        </div>
        <div class="flex gap-1">
            <button onclick="openFlightModal({{ $flight->day_id }}, {{ $flight->id }})" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.25)">{{ __('trips.show.edit') }}</button>
            <form method="POST" action="{{ route('flights.destroy', $flight) }}" data-confirm="{{ __('flights.confirm.remove_flight') }}" onclick="event.stopPropagation()">
                @csrf @method('DELETE')
                <button class="btn btn-sm" style="background:rgba(193,68,14,0.3);color:white;border:1px solid rgba(193,68,14,0.5)">✕</button>
            </form>
        </div>
    </div>
    <div class="flight-route">
        <div class="flight-airport-block">
            <div class="flight-airport-code">{{ strtoupper($flight->departure_airport) }}</div>
            @if($flight->departure_city)<div class="flight-airport-city">{{ $flight->departure_city }}</div>@endif
            @if($flight->departure_time)<div class="flight-time">{{ $flight->departure_time }}</div>@endif
        </div>
        <div class="flight-line">
            @if($flight->duration_minutes)
                <div class="flight-duration-label">{{ $flDurH > 0 ? $flDurH.'h '.$flDurM.'min' : $flDurM.'min' }}</div>
            @endif
            <div class="flight-line-bar"></div>
        </div>
        <div class="flight-airport-block">
            <div class="flight-airport-code">{{ strtoupper($flight->arrival_airport) }}</div>
            @if($flight->arrival_city)<div class="flight-airport-city">{{ $flight->arrival_city }}</div>@endif
            @if($flight->arrival_time)<div class="flight-time">{{ $flight->arrival_time }}</div>@endif
        </div>
    </div>
    <div class="flight-details">
        @if($flight->locator)<span class="badge badge-blue">🎫 {{ strtoupper($flight->locator) }}</span>@endif
        @if($flight->seat)<span class="badge badge-purple">💺 {{ $flight->seat }}</span>@endif
        @if($flight->cabin_class)<span class="badge badge-gold">{{ $cabinLabels[$flight->cabin_class] ?? $flight->cabin_class }}</span>@endif
        @if($flight->notes)<span class="text-sm text-muted">📝 {{ $flight->notes }}</span>@endif
    </div>
</div>
