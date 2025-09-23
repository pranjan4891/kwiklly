@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Manage Features</h2>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="panel-body">
                    {{-- Add / Edit Feature --}}
                    <form id="featureForm" action="{{ route('admin.features.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="feature_id" name="feature_id">

                        <div class="form-group">
                            <label for="title">Feature Title</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="icon">Icon</label>
                            <input type="text" id="icon" name="icon" class="form-control" required>
                            <small class="text-muted">Example: &lt;i class="fa fa-cube"&gt;&lt;/i&gt;</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                        </div>

                        <button type="submit" id="submitBtn" class="btn btn-primary">Add Feature</button>
                        <button type="button" id="resetBtn" class="btn btn-secondary" style="display:none;">Cancel</button>
                    </form>

                    <hr>

                    {{-- Feature List --}}
                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Icon</th>
                                <th>Description</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($features as $feature)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $feature->title }}</td>
                                <td>{!! $feature->icon !!}</td>
                                <td>{{ $feature->description }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info editBtn"
                                        data-id="{{ $feature->id }}"
                                        data-title="{{ $feature->title }}"
                                        data-icon="{{ $feature->icon }}"
                                        data-description="{{ $feature->description }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.features.destroy', $feature->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this feature?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let form = document.getElementById('featureForm');
            let submitBtn = document.getElementById('submitBtn');
            let resetBtn = document.getElementById('resetBtn');
            let actionStore = "{{ route('admin.features.store') }}";
            let updateUrl = "{{ route('admin.features.update', ':id') }}"; // placeholder route

            document.querySelectorAll('.editBtn').forEach(btn => {
                btn.addEventListener('click', function () {
                    let id = this.dataset.id;
                    let title = this.dataset.title;
                    let icon = this.dataset.icon;
                    let description = this.dataset.description;

                    // Fill form fields
                    document.getElementById('feature_id').value = id;
                    document.getElementById('title').value = title;
                    document.getElementById('icon').value = icon;
                    document.getElementById('description').value = description;

                    // Replace placeholder with actual ID
                    let actionUpdate = updateUrl.replace(':id', id);
                    form.action = actionUpdate;

                    // Add hidden _method if not already there
                    let existingMethod = form.querySelector('input[name="_method"]');
                    if (!existingMethod) {
                        let methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'POST'; // since update route uses POST in your setup
                        form.appendChild(methodInput);
                    }

                    submitBtn.textContent = "Update Feature";
                    resetBtn.style.display = "inline-block";
                });
            });

            resetBtn.addEventListener('click', function () {
                form.reset();
                document.getElementById('feature_id').value = "";
                form.action = actionStore;
                submitBtn.textContent = "Add Feature";
                this.style.display = "none";

                // Remove _method field if exists
                let existingMethod = form.querySelector('input[name="_method"]');
                if (existingMethod) existingMethod.remove();
            });
        });
    </script>
@endpush

