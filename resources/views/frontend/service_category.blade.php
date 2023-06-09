{{-- <div class="products-header">
    <div class="products-header-right justify-content-first">

        <div class="mb-3 mx-2">
            <select name="country_id[]" class="form-control select2 country" id="country_id" onchange="countryChange(this)">
                <option value="" disabled selected>Select Country</option>
                @foreach ($countries as $country)
                    <option data-href="{{ route('front-city-load', $country->id) }}" value="{{ $country->id }}">
                        {{ $country->country_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 city-div" style="display:none">
            <select name="city_id[]" id="citylist" class="select2" multiple>
                <option value="all">All City</option>

            </select>
        </div>
    </div>
</div> --}}
<div class=" d-flex justify-content-center py-3">
    <div class="product-search-one">
        <form id="searchForm" class="search-form form-inline search-pill-shape" action="" method="GET">

            @if (!empty(request()->input('sort')))
                <input type="hidden" name="sort" value="{{ request()->input('sort') }}">
            @endif
            @if (!empty(request()->input('minprice')))
                <input type="hidden" name="minprice" value="{{ request()->input('minprice') }}">
            @endif
            @if (!empty(request()->input('maxprice')))
                <input type="hidden" name="maxprice" value="{{ request()->input('maxprice') }}">
            @endif
            <input type="text" id="prod_name" class="col form-control search-field" name="search"
                placeholder="Search For" value="{{ request()->input('search') }}">
            {{-- <div class="select-appearance-none categori-container mx-2" id="catSelectForm">
              <select name="category" class="form-control select2 category_select">
                  <option selected disabled>{{ __('Select Category') }}</option>
                  @foreach (DB::table('categories')->where('language_id', $langg->id)->where('status', 1)->get() as $data)
                      <option value="{{ $data->slug }}"
                          {{ Request::route('category') == $data->slug ? 'selected' : '' }}>
                          {{ $data->name }}
                      </option>
                  @endforeach
              </select>
          </div>
    --}}

            <button type="button" onclick="searchByProductName()"><i
                    class="flaticon-search flat-mini text-white"></i></button>

        </form>
    </div>
    <div class="autocomplete2">
        <div id="myInputautocomplete-list2" class="autocomplete-items"></div>
    </div>
</div>
<div class="products-header d-flex justify-content-between align-items-center py-10 px-20 bg-light md-mt-30">
    <div class="products-header-left d-flex align-items-center">
        {{-- <h6 class="woocommerce-products-header__title page-title"> <strong> {{ __('Services')  }}</strong> </h6>
      <div class="woocommerce-result-count"></div> --}}
        <div class="products-header">
            <div class="products-header-right justify-content-first">

                <div class="mb-3 mx-md-2 mx-sm-0">
                    <select name="country_id[]" class="form-control select2 country" id="country_id"
                        onchange="countryChange(this)">
                        <option value="" disabled selected>Select Country</option>
                        @foreach ($countries as $country)
                            <option data-href="{{ route('front-city-load', $country->id) }}"
                                value="{{ $country->id }}">
                                {{ $country->country_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 city-div" style="display:none">
                    <select name="city_id[]" id="citylist" class="select2" multiple>
                        <option value="all">All City</option>

                    </select>
                </div>
                
                <p class="text-right" id="search-text"> </p>

            </div>
        </div>
    </div>
    <div class="products-header-right text-center">
        <div class="products-view">
            <button class="btn btn-sm  @if ($categoryType=='product') btn-primary @endif" id="productBtn" onclick="filterProducts()">Product</button>
            <button class="btn btn-sm  @if ($categoryType!='product') btn-primary @endif" id="serviceBtn" onclick="filterServices()">Service</button>
            <input type="hidden" value="service" id="searchProduct" name="searchProduct">

        </div>
        <form class="woocommerce-ordering m-0" method="get">
            <select class="custom-select bg-dark text-white custom-select-sm m-0" name="sortby" id="sortby">
                <option selected>Sort </option>
                <option value="date_desc">Latest Product</option>
                <option value="date_asc">Oldest Product</option>
                <option value="price_asc">Lowest Price</option>
                <option value="price_desc">Highest Price</option>
            </select>
            @if ($gs->product_page != null)
                <select id="pageby" name="pageby" class="short-itemby-no">
                    @foreach (explode(',', $gs->product_page) as $element)
                        <option value="{{ $element }}">{{ $element }}</option>
                    @endforeach
                </select>
            @else
                <input type="hidden" id="pageby" name="paged" value="{{ $gs->page_count }}">
                <input type="hidden" name="shop-page-layout" value="left-sidebar">
            @endif
        </form>
        {{-- <form class="woocommerce-ordering" method="get">
            <select name="sort" class="orderby short-item" aria-label="Shop order" id="sortby">
                <option value="date_desc">{{ __('Latest Service') }}</option>
                <option value="date_asc">{{ __('Oldest Service') }}</option>
                <option value="price_asc">{{ __('Lowest Price') }}</option>
                <option value="price_desc">{{ __('Highest Price') }}</option>
            </select>
            @if ($gs->product_page != null)
                <select id="pageby" name="pageby" class="short-itemby-no">
                    @foreach (explode(',', $gs->product_page) as $element)
                        <option value="{{ $element }}">{{ $element }}</option>
                    @endforeach
                </select>
            @else
                <input type="hidden" id="pageby" name="paged" value="{{ $gs->page_count }}">
                <input type="hidden" name="shop-page-layout" value="left-sidebar">
            @endif
        </form> --}}

        <div class="products-view">
            {{-- <a  class="grid-view check_view" data-shopview="grid-view" href="javascript:;"><i class="flaticon-menu-1 flat-mini"></i></a> --}}
            {{-- <a class="list-view check_view" data-shopview="list-view" href="javascript:;"><i
                    class="flaticon-list flat-mini"></i></a> --}}
        </div>
    </div>
</div>
<script></script>
