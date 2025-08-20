@extends('admin.includes.main')
@section('main')
<div class="wraper container-fluid">
   <div class="row">
      <div class="col-md-12">
         <div class="msg-container">
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               <div class="row">
                  <div class="col-sm-6">
                     <h3 class="panel-title">Pincode Locations</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                     <a href="{{route('admin.location.create')}}" class="btn btn-info">+Add</a>
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

                              <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                 <thead>
                                    <tr role="row">
                                       <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="S No.: activate to sort column descending" style="width: 86.2308px;">S No.</th>
                                       <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Pincode: activate to sort column ascending" style="width: 117.231px;">Pincode</th>
                                       <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Pincode: activate to sort column ascending" style="width: 117.231px;">Area Name</th>
                                       <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Place: activate to sort column ascending" style="width: 451.231px;">City</th>
                                       <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="State: activate to sort column ascending" style="width: 140.231px;">State</th>
                                       <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 133px;">Status</th>
                                       <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 133px;">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($locations as $index => $location)
                                    <tr>
                                       <td>{{ $index + 1 }}</td>
                                       <td>{{ $location->pincode }}</td>
                                       <td>{{ $location->place }}</td>
                                       <td>
                                          {{ $location->city->city_name ?? '' }},
                                          {{ $location->state->state_name ?? '' }}
                                       </td>
                                       <td>{{ $location->state->state_name ?? '' }}</td>
                                        <td>
                                            @if($location->is_active)
                                            <span class="label label-success">Active</span>
                                            @else
                                            <span class="label label-danger">Inactive</span>
                                            @endif
                                       </td>
                                       <td>
                                          <a href="{{ route('admin.location.edit', $location->id) }}" class="btn btn-success tooltips" title="Edit">
                                          <i class="fa fa-pencil"></i>
                                          </a>
                                          <form action="{{ route('admin.location.delete', $location->id) }}" method="POST" style="display:inline;">
                                             @csrf

                                             <button class="btn btn-danger tooltips" title="Delete" onclick="return confirm('Are you sure?')">
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
