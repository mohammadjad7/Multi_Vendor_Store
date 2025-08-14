@extends('layouts.dashboard')

@section('title', 'Categories')

@section('breadcrumb')
    @parent
    <i class="breadcrumb-item active">Categories</i>
@endsection

@section('content')

    <div class="mb-5">
        <a href="{{ route('categories.create') }}" class="btn btn-sm btn-outline-primary">Create Category</a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif
    @if (session()->has('delete'))
        <div class="alert alert-delete">
            {{ session('delete') }}
        </div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Parent</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td><img src="{{ asset('storage/' . $category->image) }}" alt="#" height="50" width="50"></td>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->parent_id }}</td>
                    <td>{{ $category->created_at }}</td>
                    <td>
                        <div class="action-button">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-info  rounded-pill"><i
                                    class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="post"
                                style="display: inline;">
                                @csrf
                                {{-- Form Method Spoofing --}}
                                {{-- <input type="hidden" name="_method" value="delete"> --}}
                                @method('delete')
                                <button type="submit" class="btn  btn-danger  rounded-pill"><i class="fas fa-trash"></i>
                                    Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="7"> No Categories Defind!!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
