@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
   <div class="row">
      <div class="col-md-12">
         <div class="msg-container"></div>

         <div class="panel panel-default">
            <div class="panel-heading">
               <div class="row">
                  <div class="col-sm-6">
                     <h3 class="panel-title">Branches</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                     <a href="{{ route('admin.branch.create') }}" class="btn btn-info">+ Add Branch</a>
                  </div>
                  <div class="col-sm-12" style="margin-top: 10px;">

                     {{-- Display validation errors --}}
                     @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                     @endif

                     {{-- Display success or error messages --}}
                     @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                     @endif
                     @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                     @endif
                  </div>
               </div>
            </div>

            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid">
                            <thead>
                            <tr role="row">
                                <th>S No.</th>
                                <th>Branch Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($branches as $index => $branch)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->email }}</td>
                                <td>{{ $branch->phone }}</td>
                                <td>{{ $branch->area }}, {{ $branch->city->city_name ?? '' }}, {{ $branch->state->state_name ?? '' }}, {{ $branch->postal_code }}</td>
                                <td>
                                    @if($branch->is_active)
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- <a href="{{ route('admin.branch.edit', $branch->id) }}" class="btn btn-success tooltips" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a> --}}
                                    <form action="{{ route('admin.branch.delete', $branch->id) }}" method="POST" style="display:inline;">
                                        @csrf

                                        <button class="btn btn-danger tooltips" title="Delete" onclick="return confirm('Are you sure want to delete this branch?')">
                                        <i class="fa fa-trash"></i>
                                        </button>
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
      </div>
   </div>
</div>
@endsection
