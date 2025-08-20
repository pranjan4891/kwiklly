@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Add Attribute Value</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add Value Form --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Add Value</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal tasi-form" method="post" action="{{ route('admin.attribute.values.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-lg-2">Select Attribute *</label>
                            <div class="col-lg-5">
                                <select name="attribute_id" class="form-control" required>
                                    <option value="">--Select--</option>
                                    @foreach($attributes as $attribute)
                                        <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Value *</label>
                            <div class="col-lg-5">
                                <input type="text" name="value" class="form-control" required value="{{ old('value') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Status</label>
                            <div class="col-lg-5">
                                <select class="form-control" name="is_active">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-success">Save</button>
                                <button type="reset" class="btn btn-default">Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Grouped Values --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel-body">
                <table  id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>S No.</th>
                            <th>Attribute Name</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attributeValues as $index => $val)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $val->attribute->name ?? 'N/A' }}</td>
                                <td>{{ $val->value }}</td>
                                <td>
                                    <span class="label label-{{ $val->is_active ? 'success' : 'danger' }}">
                                        {{ $val->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $val->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal{{ $val->id }}">Edit</button>
                                    <a href="{{ route('admin.attribute.value.delete', $val->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $val->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="POST" action="{{ route('admin.attribute.value.update', $val->id) }}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Attribute Value</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Attribute Name</label>
                                                    <select name="attribute_id" class="form-control" required>
                                                        @foreach ($attributes as $attribute)
                                                            <option value="{{ $attribute->id }}" {{ $val->attribute_id == $attribute->id ? 'selected' : '' }}>
                                                                {{ $attribute->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Value</label>
                                                    <input type="text" name="value" class="form-control" value="{{ $val->value }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="is_active" class="form-control">
                                                        <option value="1" {{ $val->is_active ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ !$val->is_active ? 'selected' : '' }}>Inactive</option>
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
@endsection
