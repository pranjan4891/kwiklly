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

    {{-- Contact List --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Contacts Enquiry Lists</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Name/Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $key => $contact)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <strong>{{ $contact->name }}</strong><br>
                                                {{ $contact->email }}
                                            </td>
                                            <td>{{ $contact->subject }}</td>
                                            <td>{{ $contact->message }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#replyModal{{ $contact->id }}">
                                                    <i class="fa fa-reply"></i> Reply
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Reply Modal -->
                                        <div class="modal fade" id="replyModal{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel{{ $contact->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.contactus.reply', $contact->id) }}" method="POST">
                                                        @csrf

                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="replyModalLabel{{ $contact->id }}">
                                                                Reply to {{ $contact->name }}
                                                            </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Customer Message</label>
                                                                <textarea class="form-control" readonly>{{ $contact->message }}</textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Your Reply</label>
                                                                <textarea name="reply" class="form-control" rows="4" required></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-success">Send Reply</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Reply Modal -->
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
