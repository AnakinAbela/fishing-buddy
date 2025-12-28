@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Catch Logs</h1>
    <a href="{{ route('catches.create') }}" class="btn btn-primary">Log New Catch</a>
</div>

<form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-sm-4 col-md-3">
        <label class="form-label mb-1">Sort by</label>
        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="latest" {{ request('sort','latest')==='latest' ? 'selected' : '' }}>Newest</option>
            <option value="heaviest" {{ request('sort')==='heaviest' ? 'selected' : '' }}>Heaviest</option>
            <option value="longest" {{ request('sort')==='longest' ? 'selected' : '' }}>Longest</option>
        </select>
    </div>
    <div class="col-sm-4 col-md-3">
        <label class="form-label mb-1">Filter</label>
        <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="all" {{ request('filter','all')==='all' ? 'selected' : '' }}>All catches</option>
            <option value="mine" {{ request('filter')==='mine' ? 'selected' : '' }}>My catches</option>
            <option value="friends" {{ request('filter')==='friends' ? 'selected' : '' }}>Friends</option>
        </select>
    </div>
</form>

@if($catches->count())
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($catches as $catch)
            <div class="col">
                <div class="card h-100">
                    @if($catch->photo_path)
                        <img src="{{ asset('storage/' . $catch->photo_path) }}" class="card-img-top" alt="Catch photo">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $catch->species }}</h5>

                        <p class="card-text">
                            @if($catch->weight_kg)
                                Weight: {{ $catch->weight_kg }} kg<br>
                            @endif
                            @if($catch->length_cm)
                                Length: {{ $catch->length_cm }} cm<br>
                            @endif
                        </p>

                        <p class="card-text">
                            <small class="text-muted">
                                Logged by {{ $catch->user->name }} on {{ $catch->created_at->format('d M Y') }}
                            </small>
                        </p>

                        <a href="{{ route('catches.show', $catch) }}" class="btn btn-sm btn-outline-primary">View</a>
                        <a href="{{ route('catches.edit', $catch) }}" class="btn btn-sm btn-outline-secondary">Edit</a>

                        <form action="{{ route('catches.destroy', $catch) }}" method="POST" class="d-inline-block"
                              onsubmit="return confirm('Delete this catch?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $catches->links() }}
    </div>
@else
    <p>No catches logged yet. <a href="{{ route('catches.create') }}">Log your first catch</a>.</p>
@endif
@endsection
