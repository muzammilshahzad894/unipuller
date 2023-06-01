@extends('layouts.front')
@section('content')
    <div class="services-page">

        @includeIf('partials.global.common-header')
        <!-- breadcrumb -->
        <!-- <div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/' . $gs->breadcrumb_banner) : asset('assets/front/images/service.jpg') }}); background-position: center center; background-size: cover;"> -->
        <div class="full-row bg-light overlay-dark py-5"
            style="background-image: url({{ asset('assets/front/images/service.jpg') }}); background-position: center center; background-size: cover;">
            <div class="container">
                <div class="row text-center text-white">
                    <div class="col-12">
                        <h3 class="mb-2 text-white">{{ __('Services') }}</h3>
                    </div>
                    <div class="col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                                <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('Services') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb -->
        {{-- There are two product page. you have to give condition here --}}
        <div class="full-row">
            <div class="container">
                <div class="row">
                    @includeIf('partials.catalog.service-catalog')
                    <div class="col-xl-9">
                        <div class="mb-4 d-xl-none">
                            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                        @includeIf('frontend.service_category')
                        <div class="showing-products pt-30 pb-50 border-2 border-bottom border-light" id="ajaxContent">
                            @if ($categoryType == 'product')
                                @if (count($prods) > 0)
                                @includeIf('partials.product.product-different-view')
                                @include('frontend.pagination.service')
                            @else
                                <div class="card">
                                    <div class="card-body">
                                        <div class="page-center">
                                            <h4 class="text-center">{{ __('No Service Found.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @else
                                @if (count($services) > 0)
                                    @includeIf('partials.service.service-different-view')
                                    @include('frontend.pagination.service')
                                @else
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="page-center">
                                                <h4 class="text-center">{{ __('No Service Found.') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div id="overlay"></div>
        <div class="modal" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Country </h5>
                        {{-- <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <select name="country_id[]" class="form-control select2 country" id="country_id_modal"
                                onchange="countryChangeModal(this)" required>
                                <option value="" disabled selected>Select Country</option>
                                @foreach ($countries as $country)
                                    <option data-href="{{ route('front-city-load', $country->id) }}"
                                        value="{{ $country->id }}">
                                        {{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="city-div-modal" style="display:none">
                            <select name="city_id[]" id="citylist_modal" class="select2" multiple>
                                <option value="all">All City</option>

                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p class="text-left" id="search-modal-text"> </p>
                        {{-- <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button> --}}
                        <button type="button" class="btn btn-primary close" id="search_filter_modal_btn"
                            data-bs-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- @includeIf('partials.product.grid') --}}
        {{-- @includeIf('partials.global.common-footer') --}}
    @endsection
    @section('script')
        <script>
            //s added by huma 
            $(document).ready(function() {
                // Show the modal
                $("#overlay").show();
                $("#myModal").show();

                // Close the modal when the close button is clicked
                $(".close").click(function() {
                    $("#myModal").hide();
                    $("#overlay").hide();

                });
            });

            function searchCategory() {
                var type = document.getElementById("selectType").value;
                // Change the action attribute (form path)
                if (type == 'service') {
                    document.getElementById("cites").style.display = "block"
                }
            }
            //e added by huma 



            let check_view = '';
            $(document).on('click', '.check_view', function() {
                check_view = $(this).attr('data-shopview');
                filter();
                $('.check_view').removeClass('active');
                $(this).addClass('active');


            });

            function filterProducts() {
                document.getElementById('searchProduct').value = 'product';
                document.getElementById('productBtn').classList.add("btn-primary");
                document.getElementById('serviceBtn').classList.remove("btn-primary");
                $(".ajax-loader").show();
                filter();
            }

            function filterServices() {
                document.getElementById('searchProduct').value = 'service';
                document.getElementById('productBtn').classList.remove("btn-primary");
                document.getElementById('serviceBtn').classList.add("btn-primary");
                $(".ajax-loader").show();
                filter();

            }

            function searchByProductName() {
                document.getElementById('searchProduct').value = 'product';
                document.getElementById('productBtn').classList.add("btn-primary");
                document.getElementById('serviceBtn').classList.remove("btn-primary");
                $(".ajax-loader").show();
                filter();
            }
            // when dynamic attribute changes
            $(".attribute-input, #sortby, #pageby, #country_id, #country_id_modal, #citylist_modal, #citylist").on('change',
                function() {
                    $(".ajax-loader").show();
                    filter();
                });

            function countryChangeModal(data) {
                var link = $(data).find(':selected').attr('data-href');
                if (link != "") {
                    $(".city-div-modal").css("display", "block");
                    $('#citylist_modal').load(link);
                    $("#search_filter_modal_btn").css("display", "block");

                    $('#citylist_modal').prop('disabled', false);
                    $(".select2").select2();
                } else {
                    $(".city-div_modal").css("display", "none");
                }
            }

            function countryChange(data) {
                var link = $(data).find(':selected').attr('data-href');
                if (link != "") {
                    $(".city-div").css("display", "block");

                    $('#citylist').load(link);
                    $('#citylist').prop('disabled', false);
                    $(".select2").select2();
                } else {
                    $(".city-div").css("display", "none");
                }
            }

            function getCountry() {
                let countryArray
                var countryId = $('#country_id').val()
                var countryModal = $('#country_id_modal').val()
                if (countryId != null) {
                    countryArray = countryId
                } else {
                    countryArray = countryModal
                }
                return countryArray
            }

            function getCities() {
                let cityArray
                var cityList = $('#citylist').val()
                var cityListModal = $('#citylist_modal').val()
                if (cityList != '') {
                    cityArray = cityList
                } else {
                    cityArray = cityListModal
                }
                return cityArray
            }



            function filter() {
                let filterlink = '';
                var searchText = 'search-modal-text';
                if ($("#prod_name").val() != '') {
                    if (filterlink == '') {
                        filterlink +=
                            '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                            '?search=' + $("#prod_name").val();
                    } else {
                        filterlink += '&search=' + $("#prod_name").val();
                    }
                }

                $(".attribute-input").each(function() {
                    if ($(this).is(':checked')) {

                        if (filterlink == '') {
                            filterlink +=
                                '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                                '?' + $(this).attr('name') + '=' + $(this).val();
                        } else {
                            filterlink += '&' + encodeURI($(this).attr('name')) + '=' + $(this).val();

                        }
                    }
                });

                if ($("#sortby").val() != '') {
                    if (filterlink == '') {
                        filterlink +=
                            '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                            '?' + $("#sortby").attr('name') + '=' + $("#sortby").val();
                    } else {
                        filterlink += '&' + $("#sortby").attr('name') + '=' + $("#sortby").val();
                    }
                }

                if ($("#min_price").val() != '') {
                    if (filterlink == '') {
                        filterlink +=
                            '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                            '?' + $("#min_price").attr('name') + '=' + $("#min_price").val();
                    } else {
                        filterlink += '&' + $("#min_price").attr('name') + '=' + $("#min_price").val();
                    }
                }

                if ($("#max_price").val() != '') {
                    if (filterlink == '') {
                        filterlink +=
                            '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                            '?' + $("#max_price").attr('name') + '=' + $("#max_price").val();
                    } else {
                        filterlink += '&' + $("#max_price").attr('name') + '=' + $("#max_price").val();
                    }
                }


                if ($("#pageby").val() != '') {
                    if (filterlink == '') {
                        filterlink +=
                            '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                            '?' + $("#pageby").attr('name') + '=' + $("#pageby").val();
                    } else {
                        filterlink += '&' + $("#pageby").attr('name') + '=' + $("#pageby").val();
                    }
                }

                var countryArray = getCountry();
                if (countryArray && countryArray.length > 0) {
                    if (filterlink == '') {
                        searchText = 'search-text';
                        filterlink +=
                            '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                            '?' + 'country_id[]' + '=' + countryArray;
                    } else {
                        filterlink += '&' + 'country_id[]' + '=' + countryArray;
                    }
                }
                var cityArray = getCities()
                console.log('cityArray', cityArray);
                if (cityArray && cityArray.length > 0) {
                    if (filterlink == '') {
                        searchText = 'search-text';
                        filterlink +=
                            '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                            '?' + 'city_id[]' + '=' + cityArray;
                    } else {
                        filterlink += '&' + 'city_id[]' + '=' + cityArray;
                    }
                }
                if ($("#searchProduct").val() != '') {
                    if (filterlink == '') {
                        filterlink +=
                            '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}' +
                            '?' + $("#searchProduct").attr('name') + '=' + $("#searchProduct").val();
                    } else {
                        filterlink += '&' + $("#searchProduct").attr('name') + '=' + $("#searchProduct").val();
                    }
                }

                if (check_view) {

                    filterlink += '&view_check=' + check_view;
                }
                console.log('filterlink', filterlink);

                document.getElementById(searchText).innerText = "Searching...";
                // $("#ajaxContent").load(encodeURI(filterlink), function(data) {
                //     // add query string to pagination
                //     console.log(' i m here');
                //     document.getElementById(searchText).innerText = "";
                //     addToPagination();
                //     $(".ajax-loader").fadeOut(1000);

                //     // $("#prod_name").val("")
                // });
                $.ajax({
                    url: encodeURI(filterlink),
                    method: 'GET',
                    success: function(data) {
                        // Handle the response
                        console.log("I'm here");
                        document.getElementById(searchText).innerText = "";
                        addToPagination();
                        $(".ajax-loader").fadeOut(1000);
                        // $("#prod_name").val("");

                        // Update the content of ajaxContent
                        $("#ajaxContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        console.error(error);
                    }
                });
            }

            //   append parameters to pagination links
            function addToPagination() {


                // add to attributes in pagination links
                $('ul.pagination li a').each(function() {
                    let url = $(this).attr('href');
                    let queryString = '?' + url.split('?')[1]; // "?page=1234...."

                    let urlParams = new URLSearchParams(queryString);
                    let page = urlParams.get('page'); // value of 'page' parameter

                    let fullUrl =
                        '{{ route('front.service_category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}?page=' +
                        page + '&search=' + '{{ urlencode(request()->input('search')) }}';

                    $(".attribute-input").each(function() {
                        if ($(this).is(':checked')) {
                            fullUrl += '&' + encodeURI($(this).attr('name')) + '=' + encodeURI($(this).val());
                        }
                    });

                    if ($("#sortby").val() != '') {
                        fullUrl += '&sort=' + encodeURI($("#sortby").val());
                    }

                    if ($("#min_price").val() != '') {
                        fullUrl += '&min=' + encodeURI($("#min_price").val());
                    }

                    if ($("#max_price").val() != '') {
                        fullUrl += '&max=' + encodeURI($("#max_price").val());
                    }

                    if ($("#pageby").val() != '') {
                        fullUrl += '&pageby=' + encodeURI($("#pageby").val());
                    }


                    $(this).attr('href', fullUrl);
                });
            }
        </script>
        <script type="text/javascript">
            (function($) {
                "use strict";

                $(function() {
                    $("#slider-range").slider({
                        range: true,
                        orientation: "horizontal",
                        min: {{ $gs->min_price }},
                        max: {{ $gs->max_price }},
                        values: [{{ isset($_GET['min']) ? $_GET['min'] : $gs->min_price }},
                            {{ isset($_GET['max']) ? $_GET['max'] : $gs->max_price }}
                        ],
                        step: 1,

                        slide: function(event, ui) {
                            if (ui.values[0] == ui.values[1]) {
                                return false;
                            }

                            $("#min_price").val(ui.values[0]);
                            $("#max_price").val(ui.values[1]);
                        }
                    });

                    $("#min_price").val($("#slider-range").slider("values", 0));
                    $("#max_price").val($("#slider-range").slider("values", 1));

                });

            })(jQuery);
        </script>
    @endsection
