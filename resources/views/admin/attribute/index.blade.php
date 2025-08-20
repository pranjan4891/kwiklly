@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Add Attribute</h3>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add Attribute Form --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Add Attribute</h3>
                </div>
                <div class="panel-body">
                    <div class="form">
                        <form class="form-horizontal tasi-form" method="post" action="{{ route('admin.attribute.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="control-label col-lg-2">Attribute Name *</label>
                                <div class="col-lg-5">
                                    <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="is_active" class="control-label col-lg-2">Status</label>
                                <div class="col-lg-5">
                                    <select class="form-control" name="is_active" id="is_active">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button class="btn btn-success" type="submit">Save</button>
                                    <button class="btn btn-default" type="reset">Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Attribute List --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Attribute List</h3>
                </div>
                <div class="panel-body">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>S No.</th>
                                <th>Attribute Name</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $key => $attribute)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $attribute->name }}</td>
                                    <td>
                                        <span class="label label-{{ $attribute->is_active ? 'success' : 'danger' }}">
                                            {{ $attribute->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $attribute->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <button class="btn btn-success" data-toggle="modal" data-target="#editModal{{ $attribute->id }}">Edit</button>
                                        <a href="{{ route('admin.attribute.delete', $attribute->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this attribute?')">Delete</a>
                                    </td>
                                </tr>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $attribute->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $attribute->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form method="POST" action="{{ route('admin.attribute.update', $attribute->id) }}">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Attribute</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Attribute Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $attribute->name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select name="is_active" class="form-control">
                                                            <option value="1" {{ $attribute->is_active ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ !$attribute->is_active ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
