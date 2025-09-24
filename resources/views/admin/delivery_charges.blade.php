@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">

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

    {{-- Category List --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Vendor Delivery Charges</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Vendor Name</th>
                                        <th>Charges (Rs.)</th>
                                        <th>Range (km.)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($devileryCharge as $key => $delveryCharge)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $delveryCharge->vendor->business_name }}
                                                <br>
                                                {{ $delveryCharge->vendor->email }}
                                                <br>
                                                {{ $delveryCharge->vendor->phone }}
                                            </td>
                                            <td>{{ $delveryCharge->delivery_charge }}</td>
                                            <td>{{ $delveryCharge->delivery_range }}</td>
                                            <td>
                                                @if ($delveryCharge->status == 1)
                                                    <button class="btn btn-success btn-sm">Approved</button>
                                                @elseif ($delveryCharge->status == 2)
                                                    <button class="btn btn-danger btn-sm">Rejected</button>
                                                @else
                                                    <button class="btn btn-warning btn-sm">Pending</button>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal{{ $delveryCharge->id }}">Edit</button>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $delveryCharge->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $delveryCharge->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.delivery-charges.update', $delveryCharge->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="editModalLabel{{ $delveryCharge->id }}">Edit Delivery Charge</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Delivery Charge (Rs.)</label>
                                                                <input type="text" class="form-control" value="{{ $delveryCharge->delivery_charge }}" readonly>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Delivery Range (km.)</label>
                                                                <input type="text" class="form-control" value="{{ $delveryCharge->delivery_range }}" readonly>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select name="status" class="form-control" required>
                                                                    <option value="0" {{ $delveryCharge->status == 0 ? 'selected' : '' }}>Pending</option>
                                                                    <option value="1" {{ $delveryCharge->status == 1 ? 'selected' : '' }}>Approved</option>
                                                                    <option value="2" {{ $delveryCharge->status == 2 ? 'selected' : '' }}>Rejected</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-success">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Edit Modal -->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
