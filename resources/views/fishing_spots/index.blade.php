@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Fishing Spots</h1>
    <a href="{{ route('spots.create') }}" class="btn btn-primary">Add New Spot</a>
</div>

@if($spots->count())
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($spots as $spot)
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $spot->name }}</h5>
                        <p class="card-text">{{ $spot->description ?? 'No description provided.' }}</p>
                        <p class="card-text">
                            <small class="text-muted">
                                Added by {{ $spot->user->name }} on {{ $spot->created_at->format('d M Y') }}
                            </small>
                        </p>
                        <a href="{{ route('spots.show', $spot) }}" class="btn btn-sm btn-outline-primary">View</a>
                        @can('update', $spot)
                            <a href="{{ route('spots.edit', $spot) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        @endcan
                        @can('delete', $spot)
                            <form action="{{ route('spots.destroy', $spot) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this spot?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $spots->links() }} <!-- pagination links -->
    </div>
@else
    <p>No fishing spots found. <a href="{{ route('spots.create') }}">Add one now</a>.</p>
@endif
@endsection
