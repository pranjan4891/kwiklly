@extends('admin.includes.main')

@section('main')
    <div class="wraper container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Edit Page</h2>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Page Title</label>
                                    <input type="text" name="title" id="title" class="form-control"
                                           value="{{ old('title', $page->title) }}" required>
                                </div>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                           value="{{ old('slug', $page->slug) }}" readonly>
                                </div>
                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea id="editor" name="content" rows="6" class="form-control">{{ old('content', $page->content) }}</textarea>
                                </div>
                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                        id="isActive" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Active</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Update Page</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- CKEditor --}}
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor', {
            height: 300,
            removeButtons: 'PasteFromWord'
        });

        // Auto-generate slug from title
        function slugify(text) {
            return text
                .toString()
                .toLowerCase()
                .trim()
                .replace(/[\s\W-]+/g, '-')   // Replace spaces & non-word chars with -
                .replace(/^-+|-+$/g, '');    // Remove leading/trailing hyphens
        }

        document.getElementById('title').addEventListener('input', function () {
            let slugInput = document.getElementById('slug');
            slugInput.value = slugify(this.value);
        });
    </script>
@endpush
