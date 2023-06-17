@extends('layouts.front')
@section('content')
    @include('partials.global.common-header')
    <!-- breadcrumb -->
    <div class="contact-page">
        <div class="content-circle">
            <div class="full-row bg-light overlay-dark py-5"
                style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/' . $gs->breadcrumb_banner) : asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
                <div class="container">
                    <div class="row text-center text-white">
                        <div class="col-12">
                            <h3 class="mb-2 text-white">{{ __('Contact') }}</h3>
                        </div>
                        <div class="col-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                                    <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('uk') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Contact') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- breadcrumb -->
            <!--==================== Contact Section Start ====================-->
            <div class="full-row">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-7">
                            <h3 class="down-line mb-5">{{ __('Send Message') }}</h3>
                            <div class="form-simple mb-5">
                                <form class="contactform" id="contact-form" action="{{ route('front.contact.submit') }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>{{ __('Country') }}:</label>
                                                <select class="form-select form-control bg-white" id="country"
                                                    name="country" aria-label="Default select example">
                                                    <option value="1">UK</option>
                                                    <option value="2">Bangladesh</option>
                                                </select>
                                                <div class="text-danger" id="message"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>{{ __('Deapartment') }}:</label>
                                                <select class="form-select form-control bg-white" id="department"
                                                    name="department" aria-label="department">
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}">{{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>{{ __('Priority') }}:</label>
                                                <select class="form-select form-control bg-white" id="priority"
                                                    name="priority" aria-label="priority">
                                                    <option value="1">High priority</option>
                                                    <option value="2">Medium priority</option>
                                                    <option value="3">Low priority</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>{{ __('Industry') }}:</label>
                                                <select class="form-select form-control bg-white" id="industry"
                                                    name="industry" aria-label="industry">
                                                    @foreach ($industries as $industry)
                                                        <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                                    @endforeach
                                                </select>
                                                {{-- <input type="text" class="form-control bg-white" name="name" placeholder="{{ __('Name *') }}" required=""> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>{{ __('Full Name') }}:</label>
                                                <input type="text" class="form-control bg-white" id="full_name"
                                                    name="full_name" placeholder="{{ __('Name *') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>{{ __('Your Email') }}:</label>
                                                <input type="email"
                                                    class="form-control bg-white @error('email') is-invalid @enderror"
                                                    id="email" name="email" placeholder="{{ __('Email Address ') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label>{{ __('Phone Number') }}:</label>
                                                <div class="d-flex">
                                                    <select class="form-select form-control bg-white rounded-start" id="country_code"
                                                        name="country_code" aria-label="country_code"
                                                        onchange="addCountryCode()" required>
                                                        <option value="+44">UK </option>
                                                        <option value="+880">Bangladesh </option>
                                                    </select>
                                                    <input type="text" class="form-control bg-white rounded-end" id="phone_no"
                                                        name="phone_no" value="+44" placeholder="{{ __('Phone Number *') }}"
                                                        required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label>{{ __('Message') }}:</label>
                                                <textarea class="form-control bg-white" id="message" name="message" rows="8"
                                                    placeholder="{{ __('Your Message *') }}" required=""></textarea>
                                            </div>
                                        </div>

                                        @if ($gs->is_capcha == 1)
                                            <div class="form-input">
                                                {!! NoCaptcha::display() !!}
                                                {!! NoCaptcha::renderJs() !!}
                                                @error('g-recaptcha-response')
                                                    <p class="my-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                        <input type="hidden" name="to" value="{{ $ps->contact_email }}">
                                        <div class="col-md-12 mt-3">
                                            <button id="btn_submit" class="btn btn-primary submit-btn mybtn1"
                                                name="submit" type="submit">{{ __('Send Message') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5">

                            <h3 class="down-line mb-4">{{ __('Get In Touch') }}</h3>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="uk-tab" data-bs-toggle="tab"
                                        data-bs-target="#uk" type="button" role="tab" aria-controls="uk"
                                        aria-selected="true">United Kingdom</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="bangladesh-tab" data-bs-toggle="tab"
                                        data-bs-target="#bangladesh" type="button" role="tab"
                                        aria-controls="bangladesh" aria-selected="false">Bangladesh</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="uk" role="tabpanel"
                                    aria-labelledby="uk-tab">
                                    <div class="d-flex p-4 bg-white">
                                        <ul>
                                            @if ($ps->street != null)
                                                <li class="mb-3 text-dark">
                                                    <strong>{{ __('Office Address') }} :</strong><br> {{ $ps->street }}
                                                </li>
                                            @endif
                                            @if ($ps->phone != null)
                                                <li class="mb-3 text-dark">
                                                    <strong>Contact Number :</strong><br> {{ $ps->phone }}
                                                </li>
                                            @endif
                                            @if ($ps->fax != null)
                                                <li class="mb-3 text-dark">
                                                    <strong>Fax :</strong><br> {{ $ps->fax }}
                                                </li>
                                            @endif
                                            @if ($ps->email != null)
                                                <li class="mb-3 text-dark">
                                                    <strong>{{ __('Email Address') }} :</strong><br>
                                                    <p class="email">{{ $ps->email }}</p>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bangladesh" role="tabpanel"
                                    aria-labelledby="bangladesh-tab">
                                    <div class="d-flex p-4">
                                        <ul>
                                            @if ($ps->street_BD != null)
                                                <li class="mb-3 text-dark">
                                                    <strong>{{ __('Office Address') }} :</strong><br> {{ $ps->street_BD }}
                                                </li>
                                            @endif
                                            @if ($ps->phone_BD != null)
                                                <li class="mb-3 text-dark">
                                                    <strong>Contact Number :</strong><br> {{ $ps->phone_BD }}
                                                </li>
                                            @endif
                                            @if ($ps->fax_BD != null)
                                                <li class="mb-3 text-dark">
                                                    <strong>Fax :</strong><br> {{ $ps->fax_BD }}
                                                </li>
                                            @endif
                                            @if ($ps->contact_email_BD != null)
                                                <li class="mb-3">
                                                    <strong>{{ __('Email Address') }} :</strong><br>
                                                    <p class="email">{{ $ps->contact_email_BD }}</p>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
                <div class="bg-circle-2"></div>
                <div class="bg-circle-1"></div>
                <div class="bg-line-1"></div>
                <div class="bg-line-2"></div>
            </div>

        </div>
    </div>

    <!--======================== Contact Section End ==========================-->
    {{-- @include('partials.global.common-footer') --}}
@endsection
@section('script')
    <script>
        //     function validateForm() {
        //         var flag = false
        //         const contactForm = document.getElementById('contact-form');

        //         const formFields = document.querySelectorAll("#myForm input, #myForm select, #myForm textarea");

        //         console.log('this is it ', formFields); // array of form fields
        //         formFields.forEach(field => {
        //             // do something with the form field
        //             console.log('every field', field);
        //         });
        //         // perform validation on form fields
        //         if ( /* validation error */ ) {
        //             return false;
        //         } else {
        //             return true;
        //         }
        //         return flag;
        //     }

        //     const form = document.querySelector('form');
        //     form.addEventListener('submit', function(event) {
        //         if (!validateForm()) {
        //             event.preventDefault();
        //         }
        //     });
        //
        function addCountryCode() {
            var countryCode = document.getElementById('country_code');
            var phone_no = document.getElementById("phone_no");
            // Update the value of the input field
            phone_no.value = countryCode.value;
        }
    </script>
@endsection
