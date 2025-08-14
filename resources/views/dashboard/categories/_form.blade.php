@if ($errors->any())
    <div class="alert alert-danger">
        <h3>Error Occured!</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <label for="">Category Name</label>
    <input type="text" name="name" @class(['form-control', 'is-invalid' => $errors->has('name')]) value="{{ old('name', $category->name) }}">
    @error('name')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="form-group">
    <label for="">Description</label>
    <textarea type="text" name="description" class="form-control"
        value="{{ old('description', $category->description) }}"></textarea>
</div>

<div class="form-group">
    <label for="">Category Parent</label>
    <select type="text" name="parent_id" class="form-control form-select">
        <option value="">Primary Category</option>
        @foreach ($parents as $parent)
            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="">Image</label>
    <input type="file" name="image" class="form-control" accept="image/*">
    @if ($category->image)
        <img src="{{ asset('storage/' . $category->image) }}" alt="#" height="70">
    @endif
</div>

<div class="form-group">

    <label for="">Status</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" value="active" @checked(old('status', $category->status) == 'active')>
        <label class="form-check-label">
            Active
        </label>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" value="archived" @checked(old('status', $category->status) == 'archived')>
        <label class="form-check-label">
            Archived
        </label>
    </div>

</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
</div>
