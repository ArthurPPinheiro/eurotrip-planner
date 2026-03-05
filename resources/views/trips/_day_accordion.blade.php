@php $dayIsPast = $day->date->lt(today()); @endphp
<div class="accordion-day" id="day-accordion-{{ $day->id }}" style="{{ $dayIsPast ? 'opacity:0.55;filter:grayscale(0.35)' : '' }}">

    {{-- Accordion Trigger --}}
    <span class="accordion-trigger {{ ($open ?? false) ? 'open' : '' }}" onclick="toggleAccordion({{ $day->id }})">
        <div style="flex:1;min-width:0">
            <div style="font-family:'Playfair Display',serif;font-size:1.05rem;margin-bottom:0.25rem">
                {{ $day->date->translatedFormat('l, M d') }}{{ $dayIsPast ? ' · 🏁' : '' }}
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                <span style="font-size:0.78rem;opacity:0.65">{{ __('trips.show.day_number', ['number' => $day->day_number]) }}@if($day->title) · {{ $day->title }}@endif</span>
                @if($day->flights->count() || $day->destinations->count())
                    <div class="day-summary-pills">
                        @foreach($day->flights as $fl)
                            <span class="day-pill">✈ {{ $fl->departure_airport }} → {{ $fl->arrival_airport }}</span>
                        @endforeach
                        @foreach($day->destinations as $d)
                            <span class="day-pill">{{ $d->emoji }} {{ $d->city }}</span>
                        @endforeach
                    </div>
                @else
                    <span style="font-size:0.78rem;opacity:0.45;font-style:italic">{{ __('trips.show.no_cities') }}</span>
                @endif
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:0.75rem;flex-shrink:0">
            @if($day->route?->total_distance_km)
                <span style="font-size:0.75rem;background:rgba(255,255,255,0.15);border-radius:20px;padding:0.15rem 0.55rem;white-space:nowrap">
                    {{ ['car'=>'🚗','bus'=>'🚌','train'=>'🚂'][$day->route->transport_mode] ?? '🗺️' }} {{ number_format($day->route->total_distance_km, 1) }} km
                </span>
            @endif
            @if($day->destinations->flatMap->activities->count())
                <span style="font-size:0.75rem;opacity:0.6">{{ __('general.label.items', ['count' => $day->destinations->flatMap->activities->count()]) }}</span>
            @endif
            <form method="POST" action="{{ route('days.destroy', $day) }}"
                  data-confirm="{{ __('trips.show.confirm_delete_day', ['number' => $day->day_number]) }}"
                  onsubmit="event.stopPropagation()"
                  onclick="event.stopPropagation()"
                  class="day-delete-form">
                @csrf @method('DELETE')
                <button type="submit" title="Delete day" class="day-delete-btn">✕</button>
            </form>
            <span class="accordion-chevron">▼</span>
        </div>
    </span>

    {{-- Accordion Body --}}
    <div id="accordion-body-{{ $day->id }}" class="accordion-body {{ ($open ?? false) ? 'open' : '' }}">
        <div class="accordion-inner">

            {{-- Route Section --}}
            <div id="route-section-{{ $day->id }}">
                @include('trips._route_section', ['day' => $day])
            </div>

            {{-- Flights Section --}}
            <div id="flights-container-{{ $day->id }}">
                @foreach($day->flights as $flight)
                    @include('trips._flight_card', ['flight' => $flight])
                @endforeach
            </div>

            {{-- Add City / Add Flight buttons --}}
            <div style="display:flex;justify-content:flex-end;gap:0.5rem;margin-bottom:1rem">
                <button onclick="openFlightModal({{ $day->id }})" class="btn btn-sm btn-outline" style="border-color:#1e40af;color:#1e40af">{{ __('trips.show.add_flight') }}</button>
                <button onclick="openModal('dest-modal-{{ $day->id }}')" class="btn btn-sm btn-gold">{{ __('trips.show.add_city') }}</button>
            </div>

            {{-- Destinations --}}
            <div id="no-destinations-{{ $day->id }}" style="{{ $day->destinations->isNotEmpty() ? 'display:none;' : '' }}text-align:center;padding:1.5rem 0;color:var(--muted);font-size:0.9rem">
                {{ __('trips.show.no_cities_day') }}
            </div>
            <div id="destinations-list-{{ $day->id }}" style="display:flex;flex-direction:column;gap:1rem">
                @foreach($day->destinations as $dest)
                    @include('trips._destination_card', ['dest' => $dest])
                @endforeach
            </div>

        </div>
    </div>

    {{-- Add Destination Modal --}}
    <div id="dest-modal-{{ $day->id }}" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3>{{ __('trips.modal.add_city_day', ['number' => $day->day_number]) }}</h3>
                <button class="modal-close" onclick="closeModal('dest-modal-{{ $day->id }}')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('destinations.store', $day) }}"
                      data-ajax
                      data-ajax-target="#destinations-list-{{ $day->id }}"
                      data-ajax-insert="beforeend"
                      data-ajax-hide="#no-destinations-{{ $day->id }}"
                      data-ajax-keep-open>
                    @csrf
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">{{ __('trips.modal.city') }}</label>
                            <input type="text" name="city" class="form-control" required placeholder="{{ __('trips.modal.city_placeholder') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('trips.modal.country') }}</label>
                            <input type="text" name="country" class="form-control" required placeholder="{{ __('trips.modal.country_placeholder') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('trips.modal.emoji_flag') }}</label>
                        <input type="text" name="emoji" class="form-control" placeholder="🇫🇷" style="font-size:1.4rem;width:80px;text-align:center">
                    </div>
                    <div class="flex gap-1">
                        <button type="button" onclick="closeModal('dest-modal-{{ $day->id }}')" class="btn btn-outline">{{ __('general.btn.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('trips.modal.add_city_btn') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Route Modal --}}
    <div id="route-modal-{{ $day->id }}" class="modal-backdrop">
        <div class="modal" style="max-width:640px">
            <div class="modal-header">
                <h3>{{ __('routes.modal_title', ['number' => $day->day_number]) }}</h3>
                <button class="modal-close" onclick="closeModal('route-modal-{{ $day->id }}')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST"
                      id="route-form-{{ $day->id }}"
                      action="{{ $day->route ? route('routes.update', $day->route) : route('routes.store', $day) }}"
                      data-ajax
                      data-ajax-handler="handleRouteSuccess"
                      data-ajax-day-id="{{ $day->id }}">
                    @csrf
                    @if($day->route) @method('PUT') @endif

                    <div class="form-group">
                        <label class="form-label">{{ __('routes.transport_mode') }}</label>
                        <div class="flex gap-1" style="flex-wrap:wrap">
                            <button type="button" class="transport-btn" data-mode="car"
                                    onclick="setTransport({{ $day->id }}, 'car')">🚗 {{ __('routes.mode.car') }}</button>
                            <button type="button" class="transport-btn" data-mode="bus"
                                    onclick="setTransport({{ $day->id }}, 'bus')">🚌 {{ __('routes.mode.bus') }}</button>
                            <button type="button" class="transport-btn" data-mode="train"
                                    onclick="setTransport({{ $day->id }}, 'train')">🚂 {{ __('routes.mode.train') }}</button>
                        </div>
                        <input type="hidden" name="transport_mode" id="transport-input-{{ $day->id }}" value="car">
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('routes.stops_label') }} <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:0.8rem">{{ __('routes.stops_hint') }}</span></label>
                        <div id="stops-list-{{ $day->id }}" style="display:flex;flex-direction:column;gap:0.5rem"></div>
                        <button type="button" onclick="addStop({{ $day->id }})" class="btn btn-sm btn-ghost" style="margin-top:0.5rem">{{ __('routes.add_stop') }}</button>
                    </div>

                    <div class="form-group">
                        <div id="route-preview-{{ $day->id }}" class="route-map-preview"></div>
                        <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                            <button type="button" onclick="calculateRoute({{ $day->id }})" class="btn btn-sm btn-outline">{{ __('routes.calculate_route') }}</button>
                            <span id="route-calc-summary-{{ $day->id }}" style="font-size:0.85rem;color:var(--muted)"></span>
                        </div>
                    </div>

                    <input type="hidden" name="total_distance_km" id="total-distance-{{ $day->id }}">
                    <input type="hidden" name="total_duration_minutes" id="total-duration-{{ $day->id }}">

                    <div class="flex gap-1">
                        <button type="button" onclick="closeModal('route-modal-{{ $day->id }}')" class="btn btn-outline">{{ __('general.btn.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('routes.save_route') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Flight Edit Modals --}}
    @foreach($day->flights as $flight)
        @include('trips._flight_edit_modal', ['flight' => $flight, 'day' => $day])
    @endforeach

    {{-- Flight Add Modal --}}
    <div id="flight-add-modal-{{ $day->id }}" class="modal-backdrop">
        <div class="modal" style="max-width:580px">
            <div class="modal-header">
                <h3>{{ __('flights.modal.add_title', ['number' => $day->day_number]) }}</h3>
                <button class="modal-close" onclick="closeModal('flight-add-modal-{{ $day->id }}')">×</button>
            </div>
            <div class="modal-body">
                <form id="flight-add-form-{{ $day->id }}" method="POST" action="{{ route('flights.store', $day) }}"
                      data-ajax
                      data-ajax-handler="handleFlightAddSuccess"
                      data-ajax-day-id="{{ $day->id }}">
                    @csrf
                    @include('trips._flight_form', ['f' => null])
                    <div class="flex gap-1">
                        <button type="button" onclick="closeModal('flight-add-modal-{{ $day->id }}')" class="btn btn-outline">{{ __('general.btn.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('flights.btn.add_flight') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
