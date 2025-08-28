@extends('layouts.dashboard')

@section('title', 'Products')

@section('breadcrumb')
    @parent
    <i class="breadcrumb-item active">Products</i>
@endsection

@section('content')

    <div class="mb-5">
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-outline-primary">Create product</a>
        {{-- <a href="{{ route('Products.trash') }}" class="btn btn-sm btn-outline-dark">Trash</a> --}}
    </div>

    <x-alert type="success" />
    <x-alert type="info" />
    <x-alert type="delete" />

    <form action="{{ URL::current() }}" method="get" class="d-flex">

        <x-form.input name="name" placeholder="Name" :value="request('name')" />

        <select name="status" class="form-control">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="archived" @selected(request('status') == 'archived')>Archived</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>

    </form>

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Store</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td><img src="{{ asset('storage/' . $product->image) }}" alt="#" height="50" width="50">
                    </td>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->store->name }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->created_at }}</td>
                    <td>
                        <div class="action-button">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info rounded-pill"><i
                                    class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="post"
                                style="display: inline;">
                                @csrf
                                {{-- Form Method Spoofing --}}
                                {{-- <input type="hidden" name="_method" value="delete"> --}}
                                @method('delete')
                                <button type="submit" class="btn btn-danger rounded-pill"><i class="fas fa-trash"></i>
                                    Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="9"> No Products Defined!!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $products->withQueryString()->links() }}
    {{-- {{ $Products->withQueryString()->links('folder/file you make it {view pagination}') }} --}}
@endsection
