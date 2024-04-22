<div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg" id="swiper-with-pagination-cards" >
    @foreach (getAllCompanies() as $company)
        <div class="swiper-slide">
            <div class="row">
                <div class="col-12">
                    <h5 class="text-white mb-0 mt-2">{{ $company->name }}</h5>
                </div>
                <div class="row">
                    <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                        <h6 class="text-white mt-0 mt-md-3 mb-3">Company Info</h6>
                        <div class="row">
                            <div class="col-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex mb-4 align-items-center">
                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ count($company->total_employees) }}</p>
                                <p class="mb-0">Active Employees</p>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ count($company->vehicles) }}</p>
                                <p class="mb-0">Vehicles</p>
                                </li>
                            </ul>
                            </div>
                            <div class="col-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex mb-4 align-items-center">
                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ count($company->total_terminated_employees) }}</p>
                                <p class="mb-0">Terminated Employees</p>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ $company->vehicle_percent }}%</p>
                                <p class="mb-0">Vehhicles Percent</p>
                                </li>
                            </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                        @if(!empty($company->favicon))
                            <img
                                src="{{ $company->base_url }}/public/admin/assets/img/favicon/{{ $company->favicon }}"
                                alt="{{ $company->name }}"
                                width="170"
                                class="card-website-analytics-img"
                            />
                        @else
                            <img
                                src="{{ asset('public/admin/default.png') }}"
                                alt="Default Logo"
                                width="170"
                                class="card-website-analytics-img"
                            />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="swiper-pagination"></div>
</div>
