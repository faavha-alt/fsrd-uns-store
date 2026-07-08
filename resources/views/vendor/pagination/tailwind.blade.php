@if ($paginator->hasPages())
<nav style="display:flex; align-items:center; justify-content:space-between; margin-top:24px;">
    <div style="font-size:13px; color:var(--muted);">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} hasil
    </div>
    <div style="display:flex; gap:6px; align-items:center;">

        {{-- Previous --}}
        @if($paginator->onFirstPage())
            <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:1px solid var(--border); background:var(--cream); color:var(--muted); cursor:not-allowed; font-size:14px;">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:1px solid var(--border); background:white; color:var(--ink); text-decoration:none; font-size:14px; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--cerulean)'; this.style.color='var(--cerulean)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--ink)'">‹</a>
        @endif

        {{-- Page Numbers --}}
        @foreach($elements as $element)
            @if(is_string($element))
                <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; color:var(--muted); font-size:13px;">…</span>
            @endif
            @if(is_array($element))
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())
                        <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; background:var(--cerulean); color:white; font-size:13px; font-weight:600; border:1px solid var(--cerulean);">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:1px solid var(--border); background:white; color:var(--ink); text-decoration:none; font-size:13px; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--cerulean)'; this.style.color='var(--cerulean)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--ink)'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:1px solid var(--border); background:white; color:var(--ink); text-decoration:none; font-size:14px; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--cerulean)'; this.style.color='var(--cerulean)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--ink)'">›</a>
        @else
            <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:1px solid var(--border); background:var(--cream); color:var(--muted); cursor:not-allowed; font-size:14px;">›</span>
        @endif

    </div>
</nav>
@endif