@if($day->route)
    @php
        $routeStops = $day->route->stops->map(fn($s) => [
            'city' => $s->city, 'country' => $s->country,
            'latitude' => (float)$s->latitude, 'longitude' => (float)$s->longitude
        ])->toArray();
        $rh = intdiv($day->route->total_duration_minutes ?? 0, 60);
        $rm = ($day->route->total_duration_minutes ?? 0) % 60;
        $modeIcon = ['car'=>'🚗','bus'=>'🚌','train'=>'🚂'][$day->route->transport_mode] ?? '🚗';
        $modeLabel = ['car'=>__('routes.mode.car'),'bus'=>__('routes.mode.bus'),'train'=>__('routes.mode.train')][$day->route->transport_mode] ?? __('routes.mode.car');
    @endphp
    <div class="route-card">
        <div class="route-summary-bar">
            <span style="font-weight:600">{{ $modeIcon }} {{ $modeLabel }}</span>
            @if($day->route->total_distance_km)
                <span class="badge badge-blue">📏 {{ number_format($day->route->total_distance_km, 1) }} km</span>
            @endif
            @if($day->route->total_duration_minutes)
                <span class="badge badge-gold">⏱ {{ $rh > 0 ? $rh.'h '.$rm.'min' : $rm.'min' }}</span>
            @endif
        </div>
        <div style="display:flex;align-items:center;gap:0.3rem;flex-wrap:wrap;margin-bottom:0.75rem;font-size:0.85rem;color:var(--muted)">
            @foreach($day->route->stops as $stop)
                @if(!$loop->first)<span style="opacity:0.5">→</span>@endif
                <span>{{ $stop->city }}</span>
            @endforeach
        </div>
        <div class="route-map"
             id="route-map-{{ $day->id }}"
             data-day-id="{{ $day->id }}"
             data-stops="{{ json_encode($routeStops) }}"
             data-mode="{{ $day->route->transport_mode }}"></div>
        <div class="flex gap-1" style="margin-top:0.75rem">
            <button
                data-day="{{ $day->id }}"
                data-stops="{{ json_encode($routeStops) }}"
                data-mode="{{ $day->route->transport_mode }}"
                onclick="openRouteModalFromEl(this)"
                class="btn btn-sm btn-outline">{{ __('routes.edit_route') }}</button>
            <form method="POST" action="{{ route('routes.destroy', $day->route) }}" data-confirm="{{ __('routes.confirm_remove') }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-ghost" style="color:var(--danger)">{{ __('routes.remove_route') }}</button>
            </form>
        </div>
    </div>
@else
    <div style="margin-bottom:1rem">
        <button
            data-day="{{ $day->id }}"
            onclick="openRouteModalFromEl(this)"
            class="btn btn-sm btn-outline">{{ __('routes.add_route') }}</button>
    </div>
@endif
