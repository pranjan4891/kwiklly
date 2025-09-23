@extends('admin.includes.main')

@section('main')

<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Policy Pages List</h3>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Policy Pages List</h3>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="datatable">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policies as $index => $policy)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>



                                        <td>{{ $policy->title ?? 'N/A' }}</td>

                                        <td>{{ $policy->slug ?? 'N/A' }}</td>

                                        <td>
                                            @if ($policy->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td>{{ $policy->created_at->format('d-m-Y') }}</td>

                                        <td>
                                            <a href="{{ route('admin.policies.edit', $policy->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                @if($policies->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">No policies found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {{-- Add pagination if needed --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
