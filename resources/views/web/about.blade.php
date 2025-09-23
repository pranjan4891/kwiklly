@extends('web.include.main')
@section('content')

<style>
    .about-section {
      padding: 40px 20px;
    }

    .about-heading {
      font-weight: 700;
      margin-bottom: 30px;
      margin-top: 55px;
    }

    .about-list {
      list-style: none;
      padding-left: 0;
    }

    .about-list li {
      position: relative;
      padding-left: 25px;
      margin-bottom: 10px;
    }

    .about-list li::before {
      content: "\f00c";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      position: absolute;
      left: 0;
      color: green;
    }

    .about-image,
    .about-img-thought {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .about-img-container {
      height: 100%;
    }
    .stats-section {
    background: #fff6f4;
    }
    .stat-icon {
    height: 40px;
    margin-bottom: 10px;
    }

    .stat-title {
    font-weight: 700;
    font-size: 18px;
    margin-bottom: 5px;
    color: #000;
    }

    .stat-desc {
    font-size: 14px;
    color: #333;
    }
    .stat-item .fa {
    font-size: 32px;
    padding-bottom: 10px;
}
.faq-item {
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-bottom: 15px;
}

.faq-header {
  cursor: pointer;
}

.faq-toggle-icon {
  font-size: 20px;
  color: #000;
}

.faq-header.active {
  background-color: #f9f9f9;
}

.faq-header.active span {
  color: #198754; /* Bootstrap green */
}

.accordion-body {
  padding: 20px;
  background: #fff;
  font-size: 15px;
}
    @media (min-width: 768px) {
      .about-img-top-right {
        padding-left: 0;
        padding-right: 0;
        padding-bottom: 0px;
      }

      .about-img-bottom-left {
        padding-left: 0;
        padding-right: 0;
        padding-top: 0px;
      }
      .stat-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 5px;
        color: #000;
        }
        .stat-desc {
        font-size: 11px;
        color: #333;
        }
    }

    .about-text {
      font-size: 15px;
      color: #333;
    }
  </style>


<!-- first section start  -->
<section class="extrapadding">
  <div class="container-fluid about-section">
    <div class="row gx-0">
      <!-- About Image Right -->
      <div class="col-md-6 order-1 order-md-2 about-img-top-right">
        @if($mission && $mission->image)
          <img src="{{ asset('public/' . $mission->image) }}" alt="Mission & Vision" class="about-image" />
        @else
          <img src="{{ asset('public/assets/website/images/about2.png') }}" alt="Mission & Vision" class="about-image" />
        @endif
      </div>

      <!-- Mission & Vision Text -->
      <div class="col-md-6 px-4 order-2 order-md-1">
        <h3 class="about-heading">{{ $mission->title ?? 'Mission & Vision' }}</h3>
        <p class="about-text">{!! $mission->description ?? '' !!}</p>
      </div>

      <!-- About Image Left -->
      <div class="col-md-6 about-img-bottom-left">
        @if($about && $about->image)
          <img src="{{ asset('public/' . $about->image) }}" alt="About Us" class="about-img-thought" />
        @else
          <img src="{{ asset('public/assets/website/images/about1.png') }}" alt="About Us" class="about-img-thought" />
        @endif
      </div>

      <!-- About Us Text -->
      <div class="col-md-6 px-4">
        <h3 class="about-heading">{{ $about->title ?? 'About Us' }}</h3>
        <p class="about-text">{!! $about->description ?? '' !!}</p>

        @if(!empty($about->list_items))
          <ul class="about-list">
            @foreach($about->list_items as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>
</section>
<!-- first section end  -->

<!-- second section start (Stats) -->
<section class="py-4 stats-section">
  <div class="container">
    <div class="row d-none d-md-flex text-center justify-content-between align-items-center">
      @foreach($stats as $stat)
        <div class="col-md-4">
          <div class="stat-item">
            {!! $stat->icon ?? '<i class="fa fa-cube"></i>' !!}
            <h5 class="stat-title">{{ $stat->value }}</h5>
            <p class="stat-desc">{{ $stat->title }}</p>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Mobile slider -->
    <div class="aboutusd owl-carousel d-block d-md-none">
      @foreach($stats as $stat)
        <div class="stat-item text-center">
           {!! $stat->icon ?? '<i class="fa fa-cube"></i>' !!}
          <h5 class="stat-title">{{ $stat->value }}</h5>
          <p class="stat-desc">{{ $stat->title }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
<!-- second section end -->

<!-- third section start (Features) -->
<section>
  <div class="container mt-5">
    <h2 class="why">Why Choose Us</h2>
    <div class="row mt-4">
      @foreach($features as $feature)
        <div class="col-md-4 col-6 mt-4">
          <div class="feature-box1">

             {!! $feature->icon ?? '<i class="fa fa-cube"></i>' !!}
          </div>
          <div class="feature-heading">{{ $feature->title }}</div>
          <p class="feature-description">{{ $feature->description }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
<!-- third section end -->

<!-- fourth section (FAQs - dynamic if you have a Faq model) -->
@if(isset($faqs) && count($faqs) > 0)
<section>
  <div class="container py-5">
    <h2 class="text-center fw-bold mb-4">Frequently Asked Questions</h2>
    <div class="accordion" id="faqAccordion">
      @foreach($faqs as $faq)
      <div class="accordion-item faq-item">
        <div class="d-flex justify-content-between align-items-center p-3 faq-header {{ $loop->first ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
          <span class="fw-bold {{ $loop->first ? 'text-success' : '' }}">{{ $faq->question }}</span>
          <i class="fas {{ $loop->first ? 'fa-eye-slash' : 'fa-eye' }} faq-toggle-icon"></i>
        </div>
        <div id="faq{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
          <div class="accordion-body">
            {{ $faq->answer }}
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif
<!-- fourth section end -->

<script>
  $(document).ready(function () {
    $(".aboutusd").owlCarousel({
      loop: false,
      margin: 15,
      nav: false,
      dots: true,
      responsive: { 0: { items: 1.5 } }
    });
  });
</script>

<script>
  // Toggle Eye and Eye-Slash
  document.querySelectorAll('.faq-header').forEach(header => {
    header.addEventListener('click', function() {
      const icon = this.querySelector('.faq-toggle-icon');
      const isOpen = this.classList.contains('active');
      document.querySelectorAll('.faq-header').forEach(h => {
        h.classList.remove('active');
        h.querySelector('.faq-toggle-icon').classList.replace('fa-eye-slash', 'fa-eye');
      });
      if (!isOpen) {
        this.classList.add('active');
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      }
    });
  });
</script>
@endsection
