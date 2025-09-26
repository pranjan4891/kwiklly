@extends('web.include.main')
@section('content')
    <section>
        <div class="container log-in-container form-section">
            <div class="log-in-box">
            <h4 class="mb-4 fw-bold">Complete Your Profile</h4>

            <form action="{{ route('update.profile.save') }}" method="POST">
                @csrf
                <div class="mb-3">
                <input type="text" name="name" class="form-control log-in-form-control"
                        placeholder="Enter Full Name" value="{{ old('name', auth()->user()->name) }}" required>
                @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                <input type="email" name="email" class="form-control log-in-form-control"
                        placeholder="Enter Email" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn log-in-btn w-100 py-2">Save</button>
            </form>
            </div>
        </div>
    </section>
@endsection
