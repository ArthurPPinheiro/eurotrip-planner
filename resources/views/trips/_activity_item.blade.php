<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;padding:0.6rem 0;border-bottom:1px solid var(--cream)">
    <div class="flex gap-1" style="align-items:flex-start;flex:1">
        <span style="font-size:1.1rem;flex-shrink:0;margin-top:2px">{{ $act->typeIcon() }}</span>
        <div style="flex:1">
            <div style="font-weight:500;font-size:0.9rem">{{ $act->title }}</div>
            @if($act->description)<div class="text-sm text-muted">{{ $act->description }}</div>@endif
            <div class="flex gap-1 mt-1" style="flex-wrap:wrap;align-items:center">
                @if($act->time)<span class="badge badge-blue">🕐 {{ $act->time }}</span>@endif
                @if($act->address)<span class="text-sm text-muted">📍 {{ $act->address }}</span>@endif
                @if($act->price)<span class="badge badge-green">{{ $act->currency }} {{ number_format($act->price, 2) }}</span>@endif
                @if($act->link)<a href="{{ $act->link }}" target="_blank" class="text-sm" style="color:var(--accent)">🔗 Link</a>@endif
            </div>
            <div class="text-sm text-muted mt-1">{{ __('general.label.added_by', ['name' => $act->author->name]) }}</div>
        </div>
    </div>
    <form method="POST" action="{{ route('activities.destroy', $act) }}" data-confirm="{{ __('trips.show.confirm_remove_this') }}">
        @csrf @method('DELETE')
        <button class="btn btn-sm btn-ghost" style="color:var(--danger);padding:0.2rem 0.5rem">✕</button>
    </form>
</div>
