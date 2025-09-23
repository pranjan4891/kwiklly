@extends('admin.includes.main')

@section('main')
    <div class="wraper container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Add New Page</h2>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('admin.policies.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Page Title</label>
                                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                                </div>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}" readonly>
                                </div>
                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea id="editor" name="content" rows="6" class="form-control">{{ old('content') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" checked>
                                    <label class="form-check-label" for="isActive">Active</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save Page</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- CKEditor CDN --}}
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        // Replace textarea with CKEditor
        CKEDITOR.replace('editor', {
            height: 300,
            removeButtons: 'PasteFromWord'
        });

        // Auto-generate slug from title
        document.getElementById('title').addEventListener('keyup', function() {
            let title = this.value;
            let slug = title.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // remove invalid chars
                .trim()
                .replace(/\s+/g, '-')         // replace spaces with -
                .replace(/-+/g, '-');         // remove multiple -

            document.getElementById('slug').value = slug;
        });
    </script>
@endpush
