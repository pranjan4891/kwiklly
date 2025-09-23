@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Manage About Us</h2>
                </div>

                <div class="panel-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Title --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Page Title</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    value="{{ old('title', $about->title ?? '') }}" required>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="editor-description" name="description" rows="6" class="form-control">{{ old('description', $about->description ?? '') }}</textarea>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" id="image" name="image" class="form-control">
                                @if(!empty($about->image))
                                    <img src="{{ asset('public/'.$about->image) }}" alt="About Image" class="img-thumbnail mt-2" width="150">
                                @endif
                            </div>
                        </div>

                        {{-- Dynamic List Items --}}
                        <div class="col-md-12">
                            <label>List Items</label>
                            <div id="list-items-container">
                                @php
                                    $items = !empty($about->list_items) ? json_decode($about->list_items, true) : [];
                                @endphp

                                @if(!empty($items))
                                    @foreach($items as $item)
                                        <div class="input-group">
                                            <input type="text" name="list_items[]" class="form-control" value="{{ $item }}" style="margin: 5px 5px 5px 0px;display: flex !important;">
                                            <button type="button" class="btn btn-danger remove-item">X</button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group">
                                        <input type="text" name="list_items[]" class="form-control" placeholder="Enter item" style="margin: 5px 5px 5px 0px;display: flex !important;">
                                        <button type="button" class="btn btn-danger remove-item">X</button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary" id="add-item" style="margin: 5px 0">+ Add Item</button>
                        </div>

                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor-description');

    // Add/Remove list items dynamically
    document.getElementById('add-item').addEventListener('click', function() {
        let container = document.getElementById('list-items-container');
        let div = document.createElement('div');
        div.classList.add('input-group');
        div.innerHTML = `
            <input type="text" name="list_items[]" class="form-control" placeholder="Enter item" style="margin: 5px 5px 5px 0px;display: flex !important;">
            <button type="button" class="btn btn-danger remove-item">X</button>
        `;
        container.appendChild(div);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.input-group').remove();
        }
    });
</script>
@endpush
