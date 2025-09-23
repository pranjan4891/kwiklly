@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Update Mission & Vision</h2>
                </div>

                <div class="panel-body">
                    <form action="{{ route('admin.mission.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $mission->title ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="editor-description" name="description" class="form-control" rows="5">{{ $mission->description ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" name="image" class="form-control">
                            @if(!empty($mission->image))
                                <img src="{{ asset('public/'.$mission->image) }}" width="150" class="mt-2">
                            @endif
                        </div>

                        <button type="submit" class="btn btn-success">Update</button>
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
</script>

@endpush
