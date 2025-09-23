@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Manage Stats</h2>
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
                    {{-- Add / Edit Stat --}}
                    <form id="statForm" action="{{ route('admin.stats.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="stat_id" name="stat_id">

                        <div class="form-group">
                            <label for="title">Stat Title</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="icon">Icon</label>
                            <input type="text" id="icon" name="icon" class="form-control" required>
                            <small class="text-muted">Example: &lt;i class="fa fa-star"&gt;&lt;/i&gt;</small>
                        </div>

                        <div class="form-group">
                            <label for="value">Value</label>
                            <input type="text" id="value" name="value" class="form-control" required>
                        </div>

                        <button type="submit" id="submitBtn" class="btn btn-primary">Add Stat</button>
                        <button type="button" id="resetBtn" class="btn btn-secondary" style="display:none;">Cancel</button>
                    </form>

                    <hr>

                    {{-- Stats List --}}
                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Value</th>
                                <th>Icon</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats as $stat)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stat->title }}</td>
                                <td>{{ $stat->value }}</td>
                                <td>{!! $stat->icon !!}</td>
                                <td>
                                    <button class="btn btn-sm btn-info editBtn"
                                        data-id="{{ $stat->id }}"
                                        data-title="{{ $stat->title }}"
                                        data-icon="{{ $stat->icon }}"
                                        data-value="{{ $stat->value }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.stats.destroy', $stat->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this stat?')">Delete</button>
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
        let form = document.getElementById('statForm');
        let submitBtn = document.getElementById('submitBtn');
        let resetBtn = document.getElementById('resetBtn');
        let actionStore = "{{ route('admin.stats.store') }}";
        let updateUrl = "{{ route('admin.stats.update', ':id') }}"; // placeholder

        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                let id = this.dataset.id;

                // Replace :id in update route
                let actionUpdate = updateUrl.replace(':id', id);

                // Fill form
                document.getElementById('stat_id').value = id;
                document.getElementById('title').value = this.dataset.title;
                document.getElementById('icon').value = this.dataset.icon;
                document.getElementById('value').value = this.dataset.value;

                // Change form action to update route
                form.action = actionUpdate;

                // Add hidden method input if not exists
                let existingMethod = form.querySelector('input[name="_method"]');
                if (!existingMethod) {
                    let methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'POST'; // since your update route uses POST
                    form.appendChild(methodInput);
                }

                submitBtn.textContent = "Update Stat";
                resetBtn.style.display = "inline-block";
            });
        });

        resetBtn.addEventListener('click', function () {
            form.reset();
            document.getElementById('stat_id').value = "";
            form.action = actionStore;
            submitBtn.textContent = "Add Stat";
            this.style.display = "none";

            let existingMethod = form.querySelector('input[name="_method"]');
            if (existingMethod) existingMethod.remove();
        });
    });
</script>
@endpush


