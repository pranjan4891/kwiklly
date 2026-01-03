@extends('web.include.main')
@section('content')
<style>
    .policy-page-section {
        padding: 40px 0;
        min-height: 60vh;
        padding-top: 120px; /* Add padding to prevent title from cutting in header */
    }
    
    .policy-page-section .container h2 {
        font-weight: bold;
        font-size: 32px;
        margin-bottom: 15px;
        color: #000;
        margin-top: 0;
        padding-top: 20px;
    }
    
    .policy-date {
        color: #53433F;
        font-size: 18px;
        margin-bottom: 30px;
        margin-top: 10px;
    }
    
    .policy-content {
        background: #fff;
        border-radius: 8px;
        padding: 40px;
        line-height: 1.8;
        color: #333;
        font-size: 16px;
        text-align: justify;
    }
    
    /* Style content HTML elements */
    .policy-content p {
        margin-bottom: 16px;
        line-height: 1.8;
        color: #333;
        font-size: 16px;
        text-align: justify;
    }
    
    .policy-content h1,
    .policy-content h2,
    .policy-content h3,
    .policy-content h4,
    .policy-content h5,
    .policy-content h6 {
        font-weight: bold;
        margin-top: 24px;
        margin-bottom: 16px;
        color: #000;
        line-height: 1.4;
    }
    
    .policy-content h1 {
        font-size: 28px;
    }
    
    .policy-content h2 {
        font-size: 24px;
    }
    
    .policy-content h3 {
        font-size: 20px;
    }
    
    .policy-content h4 {
        font-size: 18px;
    }
    
    .policy-content h5 {
        font-size: 16px;
    }
    
    .policy-content h6 {
        font-size: 14px;
    }
    
    .policy-content ul,
    .policy-content ol {
        margin-bottom: 16px;
        padding-left: 30px;
        line-height: 1.8;
    }
    
    .policy-content li {
        margin-bottom: 8px;
        line-height: 1.8;
        text-align: justify;
    }
    
    .policy-content div {
        text-align: justify;
    }
    
    .policy-content span {
        text-align: justify;
    }
    
    .policy-content strong,
    .policy-content b {
        font-weight: 600;
        color: #000;
    }
    
    .policy-content a {
        color: #E94412;
        text-decoration: underline;
    }
    
    .policy-content a:hover {
        color: #c7380f;
    }
    
    .policy-content blockquote {
        border-left: 4px solid #E94412;
        padding-left: 20px;
        margin: 20px 0;
        font-style: italic;
        color: #666;
    }
    
    /* Mobile View Styles */
    @media (max-width: 768px) {
        .policy-page-section {
            padding: 20px 0;
            padding-top: 100px; /* Add padding to prevent title from cutting in mobile header */
        }
        
        .policy-page-section .container h2 {
            font-size: 24px;
            margin-bottom: 10px;
            padding: 0 15px;
            margin-top: 0;
            padding-top: 15px;
        }
        
        .policy-date {
            font-size: 14px;
            margin-bottom: 20px;
            padding: 0 15px;
            margin-top: 8px;
        }
        
        .policy-content {
            padding: 20px 15px;
            font-size: 14px;
            line-height: 1.7;
        }
        
        .policy-content p {
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 1.7;
            text-align: justify;
        }
        
        .policy-content h1 {
            font-size: 22px;
            margin-top: 20px;
            margin-bottom: 12px;
        }
        
        .policy-content h2 {
            font-size: 20px;
            margin-top: 18px;
            margin-bottom: 12px;
        }
        
        .policy-content h3 {
            font-size: 18px;
            margin-top: 16px;
            margin-bottom: 10px;
        }
        
        .policy-content h4 {
            font-size: 16px;
            margin-top: 14px;
            margin-bottom: 10px;
        }
        
        .policy-content h5 {
            font-size: 15px;
            margin-top: 12px;
            margin-bottom: 8px;
        }
        
        .policy-content h6 {
            font-size: 14px;
            margin-top: 12px;
            margin-bottom: 8px;
        }
        
        .policy-content ul,
        .policy-content ol {
            margin-bottom: 12px;
            padding-left: 20px;
        }
        
        .policy-content li {
            margin-bottom: 6px;
            font-size: 14px;
            line-height: 1.7;
            text-align: justify;
        }
        
        .policy-content div {
            text-align: justify;
        }
        
        .policy-content span {
            text-align: justify;
        }
        
        .policy-content blockquote {
            padding-left: 15px;
            margin: 15px 0;
            font-size: 13px;
        }
    }
    
    /* Tablet View */
    @media (min-width: 769px) and (max-width: 1024px) {
        .policy-content {
            padding: 30px;
        }
        
        .policy-page-section .container h2 {
            font-size: 28px;
        }
    }
</style>

<section id="{{ $policy->slug }}" class="policy-page-section">
    <div class="container">
       

        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">{{ $policy->title }}</h2>
                <p class="text-center pt-3 policy-date mb-5">Modified on : {{ date('d M, Y', strtotime($policy->updated_at)) }}</p>
                <div class="policy-content">
                    {!! $policy->content !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
