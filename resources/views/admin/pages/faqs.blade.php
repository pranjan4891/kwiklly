@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Manage FAQs</h2>
                </div>

                <div class="panel-body">
                    {{-- Add / Edit FAQ --}}
                    <form id="faqForm" action="{{ route('admin.faqs.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="faq_id" name="faq_id">

                        <div class="form-group">
                            <label>Question</label>
                            <input type="text" id="question" name="question" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Answer</label>
                            <textarea id="answer" name="answer" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit" id="submitBtn" class="btn btn-primary">Add FAQ</button>
                        <button type="button" id="resetBtn" class="btn btn-secondary" style="display:none;">Cancel</button>
                    </form>

                    <hr>

                    {{-- FAQs List --}}
                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th width="160">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faqs as $faq)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $faq->question }}</td>
                                <td>{{ $faq->answer }}</td>
                                <td>
                                    <button type="button"
                                        class="btn btn-sm btn-info editBtn"
                                        data-id="{{ $faq->id }}"
                                        data-question="{{ $faq->question }}"
                                        data-answer="{{ $faq->answer }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this FAQ?')">Delete</button>
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
    let form = document.getElementById('faqForm');
    let submitBtn = document.getElementById('submitBtn');
    let resetBtn = document.getElementById('resetBtn');
    let actionStore = "{{ route('admin.faqs.store') }}";
    let updateUrl = "{{ route('admin.faqs.update', ':id') }}"; // placeholder

    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            let id = this.dataset.id;
            let question = this.dataset.question;
            let answer = this.dataset.answer;

            // Fill form
            document.getElementById('faq_id').value = id;
            document.getElementById('question').value = question;
            document.getElementById('answer').value = answer;

            // Change form action
            let actionUpdate = updateUrl.replace(':id', id);
            form.action = actionUpdate;

            // Add _method if not exists
            let existingMethod = form.querySelector('input[name="_method"]');
            if (!existingMethod) {
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'POST'; // your controller expects POST for update
                form.appendChild(methodInput);
            }

            submitBtn.textContent = "Update FAQ";
            resetBtn.style.display = "inline-block";
        });
    });

    resetBtn.addEventListener('click', function () {
        form.reset();
        document.getElementById('faq_id').value = "";
        form.action = actionStore;
        submitBtn.textContent = "Add FAQ";
        this.style.display = "none";

        // Remove _method if exists
        let existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();
    });
});
</script>
@endpush
