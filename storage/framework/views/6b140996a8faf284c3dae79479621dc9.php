<?php $__env->startSection('title', $trip->name); ?>
<?php $__env->startSection('content'); ?>

<div style="background:linear-gradient(135deg,var(--ink),#2d2d4e);border-radius:16px;padding:2rem 2.5rem;margin-bottom:2rem;position:relative;overflow:hidden;color:white">
    <div style="position:absolute;right:-10px;top:50%;transform:translateY(-50%);font-size:8rem;opacity:0.06">✈</div>
    <div class="flex-between">
        <div>
            <h1 style="font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:0.4rem"><?php echo e($trip->name); ?></h1>
            <?php if($trip->description): ?><p style="opacity:0.7;font-size:0.9rem"><?php echo e($trip->description); ?></p><?php endif; ?>
            <div class="flex gap-2 mt-2" style="font-size:0.85rem;opacity:0.8;flex-wrap:wrap">
                <?php if($trip->start_date): ?><span>📅 <?php echo e($trip->start_date->translatedFormat('M d')); ?> – <?php echo e($trip->end_date?->translatedFormat('M d, Y') ?? '?'); ?></span><?php endif; ?>
                <span>🗓️ <?php echo e(__('trips.show.days_planned', ['count' => $trip->days->count()])); ?></span>
                <span>👥 <?php echo e(__('trips.show.travellers', ['count' => $trip->members->count()])); ?></span>
                <span>⏰ <?php echo e(__('trips.show.in_days', ['count' => $trip->getTimeUntilTrip()])); ?></span>
            </div>
        </div>
        <div class="flex gap-1" style="align-items:flex-start">
            <a href="<?php echo e(route('trips.edit', $trip)); ?>" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.25)"><?php echo e(__('trips.show.edit')); ?></a>
            <a href="<?php echo e(route('trips.index')); ?>" class="btn btn-sm btn-ghost" style="color:rgba(255,255,255,0.6)"><?php echo e(__('trips.show.back')); ?></a>
        </div>
    </div>
    <div class="flex-between mt-3" style="align-items:center">
        <div class="flex gap-1">
            <?php $__currentLoopData = $trip->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="avatar" title="<?php echo e($m->name); ?>" style="background:<?php echo e($m->avatar_color); ?>;width:30px;height:30px;font-size:0.65rem;border:2px solid rgba(255,255,255,0.3)"><?php echo e($m->initials()); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="background:rgba(255,255,255,0.1);border-radius:8px;padding:0.4rem 0.875rem;font-size:0.8rem;color:rgba(255,255,255,0.8)">
            <?php echo e(__('general.label.invite_code')); ?>: <strong style="letter-spacing:0.1em;color:var(--gold-light)"><?php echo e($trip->invite_code); ?></strong>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-1 mb-3">
    <a href="<?php echo e(route('trips.show', $trip)); ?>" class="btn btn-primary btn-sm">🗺️ <?php echo e(__('trips.show.itinerary')); ?></a>
    <a href="<?php echo e(route('documents.index', $trip)); ?>" class="btn btn-outline btn-sm">📂 <?php echo e(__('trips.show.documents')); ?></a>

    <!-- Add Day -->
    <form method="POST" action="<?php echo e(route('trips.addDay', $trip)); ?>"
          data-ajax
          data-ajax-handler="handleAddDaySuccess">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-gold"><?php echo e(__('trips.show.add_day')); ?></button>
    </form>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.accordion-day { border-radius: 12px; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 0.75rem; }
.accordion-trigger {
    width: 100%; background: linear-gradient(90deg, var(--ink), #2d2d4e);
    color: white; border: none; padding: 1rem 1.5rem;
    display: flex; align-items: center; justify-content: space-between;
    cursor: pointer; font-family: inherit; text-align: left; gap: 1rem;
    transition: background 0.2s;
}
.accordion-trigger:hover { background: linear-gradient(90deg, #2d2d4e, #3a3a6e); }
.accordion-trigger.open { background: linear-gradient(90deg, #2d2d4e, #3a3a6e); }
.accordion-chevron {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(255,255,255,0.15); display: flex; align-items: center;
    justify-content: center; font-size: 0.7rem; flex-shrink: 0;
    transition: transform 0.3s ease;
}
.accordion-trigger.open .accordion-chevron { transform: rotate(180deg); }
.accordion-body {
    background: white; max-height: 0; overflow: hidden;
    transition: max-height 0.35s ease, padding 0.2s;
}
.accordion-body.open { max-height: 9999px; }
.accordion-inner { padding: 1.25rem; }
.day-summary-pills { display: flex; gap: 0.4rem; flex-wrap: wrap; }
.day-pill { background: rgba(255,255,255,0.12); border-radius: 20px; padding: 0.15rem 0.6rem; font-size: 0.75rem; }
.day-delete-btn {
    background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.7); border-radius: 6px; padding: 0.2rem 0.55rem;
    cursor: pointer; font-size: 0.8rem; line-height: 1; transition: all 0.2s; font-family: inherit;
}
.day-delete-btn:hover { background: rgba(193,68,14,0.65); border-color: rgba(193,68,14,0.9); color: white; }
/* Route feature */
.route-card { border: 1.5px solid var(--cream); border-radius: 10px; padding: 1rem; margin-bottom: 1rem; background: #fafafa; }
.route-summary-bar { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.6rem; }
.route-map { height: 260px; border-radius: 8px; overflow: hidden; border: none; box-shadow: 0 2px 12px rgba(26,26,46,0.1); isolation: isolate; }
.route-map-preview { height: 200px; border-radius: 8px; overflow: hidden; border: none; box-shadow: 0 2px 8px rgba(26,26,46,0.08); margin-bottom: 0.5rem; isolation: isolate; }
/* Leaflet control styling */
.leaflet-control-zoom a { font-family: 'DM Sans', sans-serif; font-size: 0.9rem; color: var(--ink) !important; border-color: var(--cream) !important; }
.leaflet-control-zoom a:hover { background: var(--cream) !important; }
.leaflet-control-attribution { font-size: 0.65rem !important; background: rgba(255,255,255,0.75) !important; }
.leaflet-popup-content-wrapper { border-radius: 8px !important; box-shadow: 0 4px 16px rgba(26,26,46,0.12) !important; font-family: 'DM Sans', sans-serif !important; font-size: 0.85rem !important; }
.leaflet-popup-tip { display: none !important; }
.transport-btn { padding: 0.4rem 0.875rem; border-radius: 20px; border: 1.5px solid var(--cream); background: white; cursor: pointer; font-family: inherit; font-size: 0.85rem; transition: all 0.2s; }
.transport-btn.active { background: var(--ink); color: white; border-color: var(--ink); }
.transport-btn:hover:not(.active) { border-color: var(--gold); }
.stop-row { background: white; border: 1px solid var(--cream); border-radius: 8px; padding: 0.5rem 0.75rem; }
/* Flight feature */
.flight-card { border: 1.5px solid #dbeafe; border-radius: 10px; overflow: hidden; margin-bottom: 0.75rem; background: white; }
.flight-card-header { background: linear-gradient(90deg, #1e3a5f, #1e40af); color: white; padding: 0.75rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.flight-route { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-bottom: 1px solid #eff6ff; }
.flight-airport-block { text-align: center; min-width: 70px; }
.flight-airport-code { font-size: 1.6rem; font-weight: 700; color: var(--ink); letter-spacing: 0.05em; line-height: 1; }
.flight-airport-city { font-size: 0.72rem; color: var(--muted); margin-top: 0.2rem; }
.flight-time { font-size: 0.85rem; font-weight: 600; color: var(--ink); margin-top: 0.35rem; }
.flight-line { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.25rem; }
.flight-line-bar { width: 100%; height: 2px; background: linear-gradient(90deg, #1e40af, #93c5fd); border-radius: 2px; position: relative; }
.flight-line-bar::before, .flight-line-bar::after { content: ''; position: absolute; top: 50%; transform: translateY(-50%); width: 6px; height: 6px; border-radius: 50%; background: #1e40af; }
.flight-line-bar::before { left: -3px; }
.flight-line-bar::after { right: -3px; }
.flight-duration-label { font-size: 0.72rem; color: var(--muted); }
.flight-details { padding: 0.75rem 1.25rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
</style>
<?php $__env->stopPush(); ?>

<!-- Days -->
<div id="no-days-state" style="<?php echo e($trip->days->isNotEmpty() ? 'display:none' : ''); ?>">
    <div class="card">
        <div class="empty-state">
            <span class="emoji">🗓️</span>
            <p><?php echo e(__('trips.show.no_days')); ?></p>
        </div>
    </div>
</div>

<div id="days-list" style="display:flex;flex-direction:column;gap:0.75rem">
    <?php $__currentLoopData = $trip->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('trips._day_accordion', ['day' => $day, 'open' => $loop->first], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php
    $totalKm = $trip->days->sum(fn($d) => $d->route?->total_distance_km ?? 0);
    $totalMin = $trip->days->sum(fn($d) => $d->route?->total_duration_minutes ?? 0);
?>
<?php if($totalKm > 0): ?>
    <div style="margin-top:0.5rem;padding:0.875rem 1.25rem;background:white;border-radius:12px;box-shadow:var(--shadow);display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap">
        <span style="font-size:0.85rem;color:var(--muted);font-weight:500"><?php echo e(__('trips.show.trip_totals')); ?></span>
        <span class="badge badge-blue" style="font-size:0.85rem;padding:0.3rem 0.75rem">📏 <?php echo e(__('trips.show.km_total', ['km' => number_format($totalKm, 1)])); ?></span>
        <?php if($totalMin > 0): ?>
            <?php $th = intdiv($totalMin, 60); $tm = $totalMin % 60; ?>
            <span class="badge badge-gold" style="font-size:0.85rem;padding:0.3rem 0.75rem">⏱ <?php echo e(__('trips.show.driving', ['time' => $th > 0 ? $th.'h '.$tm.'min' : $tm.'min'])); ?></span>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openFlightModal(dayId, flightId = null) {
    if (flightId) {
        openModal(`flight-edit-modal-${flightId}`);
    } else {
        openModal(`flight-add-modal-${dayId}`);
    }
}

function toggleAccordion(dayId) {
    const trigger = document.querySelector(`[onclick="toggleAccordion(${dayId})"]`);
    const body = document.getElementById(`accordion-body-${dayId}`);
    trigger.classList.toggle('open');
    body.classList.toggle('open');
}

// ─── Route feature ───────────────────────────────────────────────

function haversine(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLon/2)**2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function formatDuration(minutes) {
    const h = Math.floor(minutes / 60);
    const m = Math.round(minutes % 60);
    return h > 0 ? `${h}h ${m}min` : `${m}min`;
}

function setTransport(dayId, mode) {
    document.getElementById(`transport-input-${dayId}`).value = mode;
    document.querySelectorAll(`#route-modal-${dayId} .transport-btn`).forEach(btn => {
        btn.classList.toggle('active', btn.dataset.mode === mode);
    });
}

const stopCounter = {};
const leafletMaps = {};

function addStop(dayId, prefill = null) {
    if (!stopCounter[dayId]) stopCounter[dayId] = 0;
    const idx = stopCounter[dayId]++;
    const list = document.getElementById(`stops-list-${dayId}`);
    const row = document.createElement('div');
    row.className = 'stop-row';
    row.dataset.idx = idx;
    const geocoded = prefill ? '✓' : '';
    const geocodedColor = prefill ? 'var(--accent)' : '';
    row.innerHTML = `
        <div style="display:flex;align-items:center;gap:0.5rem">
            <span class="stop-num" style="width:22px;height:22px;border-radius:50%;background:var(--ink);color:white;display:flex;align-items:center;justify-content:center;font-size:0.7rem;flex-shrink:0;font-weight:600">●</span>
            <input type="text" class="form-control stop-city-input" placeholder="<?php echo e(__('routes.stop_placeholder')); ?>" style="flex:1;padding:0.4rem 0.6rem"
                   value="${prefill?.city || ''}" onblur="geocodeStop(${dayId}, this)">
            <input type="hidden" class="stop-lat" name="stops[${idx}][latitude]" value="${prefill?.latitude || ''}">
            <input type="hidden" class="stop-lng" name="stops[${idx}][longitude]" value="${prefill?.longitude || ''}">
            <input type="hidden" class="stop-country" name="stops[${idx}][country]" value="${prefill?.country || ''}">
            <input type="hidden" class="stop-city-val" name="stops[${idx}][city]" value="${prefill?.city || ''}">
            <span class="stop-status" style="font-size:0.9rem;flex-shrink:0;color:${geocodedColor}">${geocoded}</span>
            <button type="button" onclick="removeStop(this, ${dayId})" class="btn btn-sm btn-ghost" style="color:var(--danger);padding:0.2rem 0.4rem;flex-shrink:0">✕</button>
        </div>
    `;
    list.appendChild(row);
    updateStopNumbers(dayId);
}

function removeStop(btn, dayId) {
    const list = document.getElementById(`stops-list-${dayId}`);
    if (list.querySelectorAll('.stop-row').length <= 2) { alert('<?php echo e(__('routes.alert_min_stops')); ?>'); return; }
    btn.closest('.stop-row').remove();
    updateStopNumbers(dayId);
}

function updateStopNumbers(dayId) {
    document.querySelectorAll(`#stops-list-${dayId} .stop-row`).forEach((row, i) => {
        row.querySelector('.stop-num').textContent = i + 1;
        row.querySelector('.stop-lat').name = `stops[${i}][latitude]`;
        row.querySelector('.stop-lng').name = `stops[${i}][longitude]`;
        row.querySelector('.stop-country').name = `stops[${i}][country]`;
        row.querySelector('.stop-city-val').name = `stops[${i}][city]`;
    });
}

async function geocodeStop(dayId, input) {
    const city = input.value.trim();
    if (!city) return;
    const row = input.closest('.stop-row');
    const statusEl = row.querySelector('.stop-status');
    statusEl.textContent = '⏳'; statusEl.style.color = '';
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(city)}&format=json&limit=1`, {
            headers: { 'Accept-Language': 'en' }
        });
        const data = await res.json();
        if (data.length > 0) {
            const place = data[0];
            row.querySelector('.stop-lat').value = place.lat;
            row.querySelector('.stop-lng').value = place.lon;
            row.querySelector('.stop-city-val').value = city;
            const parts = place.display_name.split(', ');
            row.querySelector('.stop-country').value = parts[parts.length - 1] || '';
            statusEl.textContent = '✓'; statusEl.style.color = 'var(--accent)';
            updatePreviewMap(dayId);
        } else {
            statusEl.textContent = '✗'; statusEl.style.color = 'var(--danger)';
        }
    } catch(e) {
        statusEl.textContent = '✗'; statusEl.style.color = 'var(--danger)';
    }
}

function getModalStops(dayId) {
    const stops = [];
    document.querySelectorAll(`#stops-list-${dayId} .stop-row`).forEach(row => {
        const lat = parseFloat(row.querySelector('.stop-lat').value);
        const lng = parseFloat(row.querySelector('.stop-lng').value);
        const city = row.querySelector('.stop-city-val').value;
        if (!isNaN(lat) && !isNaN(lng)) stops.push({ lat, lng, city });
    });
    return stops;
}

function initPreviewMap(dayId) {
    const mapEl = document.getElementById(`route-preview-${dayId}`);
    if (!mapEl) return null;
    if (leafletMaps[`preview-${dayId}`]) return leafletMaps[`preview-${dayId}`];
    const map = L.map(mapEl, { scrollWheelZoom: false }).setView([48.8566, 2.3522], 5);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OSM</a> © <a href="https://carto.com">CARTO</a>',
        subdomains: 'abcd', maxZoom: 20
    }).addTo(map);
    leafletMaps[`preview-${dayId}`] = map;
    return map;
}

function clearMapLayers(map) {
    map.eachLayer(layer => { if (!(layer instanceof L.TileLayer)) map.removeLayer(layer); });
}

function addStopMarkers(map, stops) {
    stops.forEach((s, i) => {
        L.marker([s.lat, s.lng], {
            icon: L.divIcon({
                html: `<div style="background:#1a1a2e;color:white;border-radius:50%;width:26px;height:26px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;border:2.5px solid white;box-shadow:0 2px 8px rgba(26,26,46,0.35);font-family:'DM Sans',sans-serif">${i+1}</div>`,
                className: '', iconSize: [26,26], iconAnchor: [13,13]
            })
        }).addTo(map).bindPopup(s.city);
    });
}

function updatePreviewMap(dayId) {
    const stops = getModalStops(dayId);
    if (stops.length < 2) return;
    const map = initPreviewMap(dayId);
    if (!map) return;
    clearMapLayers(map);
    const latlngs = stops.map(s => [s.lat, s.lng]);
    L.polyline(latlngs, { color: '#1a1a2e', weight: 2.5, dashArray: '6,5', opacity: 0.7 }).addTo(map);
    addStopMarkers(map, stops);
    map.fitBounds(L.latLngBounds(latlngs), { padding: [28, 28] });
}

async function calculateRoute(dayId) {
    const stops = getModalStops(dayId);
    if (stops.length < 2) { alert('<?php echo e(__('routes.alert_geocode_stops')); ?>'); return; }
    const mode = document.getElementById(`transport-input-${dayId}`).value;
    const summaryEl = document.getElementById(`route-calc-summary-${dayId}`);
    summaryEl.textContent = '<?php echo e(__('routes.calculating')); ?>'; summaryEl.style.color = 'var(--muted)';
    let distKm, durationMin;

    if (mode === 'car') {
        try {
            const coords = stops.map(s => `${s.lng},${s.lat}`).join(';');
            const res = await fetch(`https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`);
            const data = await res.json();
            if (data.code === 'Ok' && data.routes.length > 0) {
                const route = data.routes[0];
                distKm = route.distance / 1000;
                durationMin = route.duration / 60;
                const map = initPreviewMap(dayId);
                clearMapLayers(map);
                const geojsonLayer = L.geoJSON(route.geometry, { style: { color: '#1a1a2e', weight: 3.5, opacity: 0.85 } }).addTo(map);
                addStopMarkers(map, stops);
                map.fitBounds(geojsonLayer.getBounds(), { padding: [20, 20] });
            } else {
                throw new Error('OSRM no route');
            }
        } catch(e) {
            distKm = stops.reduce((sum, s, i) => i === 0 ? 0 : sum + haversine(stops[i-1].lat, stops[i-1].lng, s.lat, s.lng), 0);
            durationMin = distKm / 90 * 60;
            updatePreviewMap(dayId);
        }
    } else {
        distKm = stops.reduce((sum, s, i) => i === 0 ? 0 : sum + haversine(stops[i-1].lat, stops[i-1].lng, s.lat, s.lng), 0);
        durationMin = distKm / (mode === 'train' ? 150 : 70) * 60;
        updatePreviewMap(dayId);
    }

    distKm = Math.round(distKm * 10) / 10;
    durationMin = Math.round(durationMin);
    document.getElementById(`total-distance-${dayId}`).value = distKm;
    document.getElementById(`total-duration-${dayId}`).value = durationMin;
    const modeLabel = { car: '🚗 <?php echo e(__('routes.mode.car')); ?>', bus: '🚌 <?php echo e(__('routes.mode.bus')); ?>', train: '🚂 <?php echo e(__('routes.mode.train')); ?>' }[mode];
    summaryEl.innerHTML = `<strong>${modeLabel}:</strong> ~${distKm} km · ${formatDuration(durationMin)}`;
    summaryEl.style.color = 'var(--accent)';
}

function openRouteModalFromEl(btn) {
    const dayId = btn.dataset.day;
    const stopsJson = btn.dataset.stops;
    const existingStops = stopsJson ? JSON.parse(stopsJson) : null;
    const mode = btn.dataset.mode || 'car';
    openRouteModal(dayId, existingStops, mode);
}

function openRouteModal(dayId, existingStops = null, mode = 'car') {
    const list = document.getElementById(`stops-list-${dayId}`);
    list.innerHTML = '';
    stopCounter[dayId] = 0;

    const summaryEl = document.getElementById(`route-calc-summary-${dayId}`);
    if (summaryEl) { summaryEl.textContent = ''; }

    setTransport(dayId, mode);

    if (existingStops && existingStops.length > 0) {
        existingStops.forEach(stop => addStop(dayId, stop));
    } else {
        addStop(dayId); addStop(dayId);
    }

    openModal(`route-modal-${dayId}`);

    if (leafletMaps[`preview-${dayId}`]) {
        leafletMaps[`preview-${dayId}`].remove();
        delete leafletMaps[`preview-${dayId}`];
    }

    setTimeout(() => {
        initPreviewMap(dayId);
        if (existingStops && existingStops.length >= 2) updatePreviewMap(dayId);
    }, 150);
}

// ─── Display map init (reusable for AJAX route injection) ───────
function initDisplayMap(dayId, stops, mode) {
    const mapEl = document.getElementById(`route-map-${dayId}`);
    if (!mapEl) return;
    if (leafletMaps[`display-${dayId}`]) {
        leafletMaps[`display-${dayId}`].remove();
        delete leafletMaps[`display-${dayId}`];
    }
    const map = L.map(mapEl, { scrollWheelZoom: false }).setView([stops[0].latitude, stops[0].longitude], 6);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OSM</a> © <a href="https://carto.com">CARTO</a>',
        subdomains: 'abcd', maxZoom: 20
    }).addTo(map);
    const latlngs = stops.map(s => [s.latitude, s.longitude]);
    const mappedStops = stops.map(s => ({ lat: s.latitude, lng: s.longitude, city: s.city }));
    if (mode === 'car') {
        const coords = stops.map(s => `${s.longitude},${s.latitude}`).join(';');
        fetch(`https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`)
            .then(r => r.json())
            .then(data => {
                if (data.code === 'Ok' && data.routes.length > 0) {
                    L.geoJSON(data.routes[0].geometry, { style: { color: '#1a1a2e', weight: 3.5, opacity: 0.85 } }).addTo(map);
                } else {
                    L.polyline(latlngs, { color: '#1a1a2e', weight: 2.5, dashArray: '6,5', opacity: 0.7 }).addTo(map);
                }
                addStopMarkers(map, mappedStops);
                map.fitBounds(L.latLngBounds(latlngs), { padding: [28, 28] });
            })
            .catch(() => {
                L.polyline(latlngs, { color: '#1a1a2e', weight: 2.5, dashArray: '6,5', opacity: 0.7 }).addTo(map);
                addStopMarkers(map, mappedStops);
                map.fitBounds(L.latLngBounds(latlngs), { padding: [28, 28] });
            });
    } else {
        L.polyline(latlngs, { color: '#1a1a2e', weight: 2.5, dashArray: '6,5', opacity: 0.7 }).addTo(map);
        addStopMarkers(map, mappedStops);
        map.fitBounds(L.latLngBounds(latlngs), { padding: [28, 28] });
    }
    leafletMaps[`display-${dayId}`] = map;
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.route-map[data-stops]').forEach(mapEl => {
        const stops = JSON.parse(mapEl.dataset.stops);
        const mode = mapEl.dataset.mode;
        const dayId = mapEl.dataset.dayId;
        if (stops.length < 2) return;
        initDisplayMap(dayId, stops, mode);
    });
});

// ─── AJAX custom handlers ────────────────────────────────────────

function handleFlightAddSuccess(json, form) {
    const dayId = form.dataset.ajaxDayId;
    const container = document.getElementById(`flights-container-${dayId}`);
    if (container && json.html) container.insertAdjacentHTML('beforeend', json.html);
    const dayEl = document.getElementById(`day-accordion-${dayId}`);
    if (dayEl && json.modal_html) dayEl.insertAdjacentHTML('beforeend', json.modal_html);
    form.closest('.modal-backdrop').classList.remove('open');
    form.querySelectorAll('input:not([type=hidden]):not([name=_token])').forEach(el => el.value = '');
    form.querySelectorAll('textarea').forEach(el => el.value = '');
    form.querySelectorAll('select').forEach(el => el.selectedIndex = 0);
}

function handleFlightEditSuccess(json, form) {
    const flightCard = document.getElementById(`flight-card-${json.flight.id}`);
    if (flightCard && json.html) flightCard.outerHTML = json.html;
    const editForm = document.getElementById(`flight-edit-form-${json.flight.id}`);
    if (editForm && json.flight) {
        ['departure_airport','arrival_airport','departure_city','arrival_city',
         'departure_time','arrival_time','flight_number','airline',
         'locator','seat','cabin_class','notes','duration_minutes'].forEach(field => {
            const input = editForm.querySelector(`[name="${field}"]`);
            if (input) input.value = json.flight[field] ?? '';
        });
    }
    form.closest('.modal-backdrop').classList.remove('open');
}

function handleRouteSuccess(json, form) {
    const dayId = form.dataset.ajaxDayId;
    const section = document.getElementById(`route-section-${dayId}`);
    if (section && json.html) section.innerHTML = json.html;
    if (json.stops && json.stops.length >= 2) {
        setTimeout(() => initDisplayMap(dayId, json.stops, json.mode), 50);
    }
    if (json.update_url) {
        form.action = json.update_url;
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
    }
    form.closest('.modal-backdrop').classList.remove('open');
}

function handleAddDaySuccess(json, form) {
    const nodays = document.getElementById('no-days-state');
    if (nodays) nodays.style.display = 'none';
    const daysList = document.getElementById('days-list');
    if (daysList && json.html) {
        daysList.insertAdjacentHTML('beforeend', json.html);
        daysList.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/show.blade.php ENDPATH**/ ?>