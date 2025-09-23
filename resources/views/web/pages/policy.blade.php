@extends('web.include.main')
@section('content')
<style>
    .container h2{
        font-weight: bold;
    }
    .date{
        color: #53433F;
        font-size: 20px;
    }
    .row h5{
        font-weight: bold;
    }
</style>

<section id="{{ $policy->slug }}" style="margin-top: 10%;">
    <div class="container">
        <h2 class="text-center">{{ $policy->title }}</h2>
        <p class="text-center pt-3 date mb-5" > Modified on : {{ date('d M, Y', strtotime($policy->updated_at)) }} </p>

        <div class="row">
            <div class="col-md-12">
                {!! $policy->content !!}
            </div>
        </div>
    </div>
</section>
@endsection
