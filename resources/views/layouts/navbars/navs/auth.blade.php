<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <a class="navbar-brand" href="#">{{ $page ?? __('Dashboard') }}</a>

        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>

        <div class="collapse navbar-collapse" id="navigation">

            <ul class="navbar-nav ml-auto">


                {{-- <li class="search-bar input-group">
                     <button class="btn btn-link" id="search-button" data-toggle="modal" data-target="#searchModal"><i class="tim-icons icon-zoom-split"></i>
                        <span class="d-lg-none d-md-block">{{ __('Search') }}</span>
                    </button>
                </li> --}}


                @if(in_array(auth()->user()->roles->first()->name, ['Admin']))
                <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="nav"><i class="tim-icons icon-calendar-60"></i><h6 class="title d-inline"> {{\Carbon\Carbon::now()->format('l, jS \\of F, Y')}} </h6>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="nav"><i class="tim-icons icon-time-alarm"></i><h6 class="title d-inline"> <span id="clock"></span></h6>
                    </a>


                </li>




                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <div class="notification d-none d-lg-block d-xl-block"></div>
                        <i class="tim-icons icon-settings"></i>
                        <p class="d-lg-none"> {{ __('Notifications') }} </p>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
                        <li class="nav-link">
                            <a href="#" class="nav-item dropdown-item">{{ __('Department') }}</a>
                        </li>
                        <li class="nav-link">
                            <a href="#" class="nav-item dropdown-item">{{ __('You have 5 more tasks') }}</a>
                        </li>
                        <li class="nav-link">
                            <a href="#" class="nav-item dropdown-item">{{ __('Your friend Michael is in town') }}</a>
                        </li>
                        <li class="nav-link">
                            <a href="{{ route('roles.index')  }}" class="nav-item dropdown-item">{{ __('Manage Roles') }}</a>
                            </li>
                        <li class="nav-link">
                        <a href="{{ route('users.index')  }}" class="nav-item dropdown-item">{{ __('Manage Users') }}</a>
                        </li>
                        <li class="nav-link">
                            <a href="{{ route('users.create')  }}" class="nav-item dropdown-item">{{ __('New User') }}</a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <div class="notification d-none d-lg-block d-xl-block"></div>
                        <i class="tim-icons icon-settings"></i>
                        <p class="d-lg-none"> {{ __('Notifications') }} </p>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
                        <div class="fixed-plugin">
                            <div class="dropdown show-dropdown">
                                <a href="#" data-toggle="dropdown" aria-expanded="false">
                                <i class="tim-icons icon-settings fa-2x"> </i>
                                </a>
                                <ul class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(-231px, -125px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <li class="header-title"> Sidebar Background</li>
                                <li class="adjustments-line">
                                    <a href="javascript:void(0)" class="switch-trigger background-color">
                                    <div class="badge-colors text-center">
                                        <span class="badge filter badge-primary active" data-color="primary"></span>
                                        <span class="badge filter badge-info" data-color="blue"></span>
                                        <span class="badge filter badge-success" data-color="green"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                    </a>
                                </li>
                                <li class="button-container">
                                    <a href="https://www.creative-tim.com/product/white-dashboard-laravel" target="_blank" class="btn btn-primary btn-block btn-round">Download Now</a>
                                    <a href="https://white-dashboard-laravel.creative-tim.com/docs/getting-started/introduction.html" target="_blank" class="btn btn-default btn-block btn-round">
                                    Documentation
                                    </a>
                                    <a href="https://www.creative-tim.com/product/white-dashboard-pro-laravel?_ga=2.135661809.1704876887.1606126897-1480670593.1586171072" target="_blank" class="btn btn-danger btn-block btn-round">
                                        Upgrade to PRO
                                        </a>
                                </li>
                                <li class="header-title">Thank you for 95 shares!</li>
                                <li class="button-container text-center">
                                    <button id="twitter" class="btn btn-round btn-info"><i class="fab fa-twitter"></i> · 45</button>
                                    <button id="facebook" class="btn btn-round btn-info"><i class="fab fa-facebook-f"></i> · 50</button>
                                    <br>
                                    <br>
                                    <a class="github-button btn btn-round btn-default" href="https://github.com/creativetimofficial/white-dashboard-laravel" data-icon="octicon-star" data-size="large" target="_blank" data-show-count="true" aria-label="Star ntkme/github-buttons on GitHub">Star</a>
                                </li>
                                </ul>
                            </div>
                        </div>
                    </ul>
                </li>  --}}
                @endif


                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <div class="photo">
                            @if(Auth::user()->avatar)

                            <img class="avatar" src="assets/img/<?php echo Auth::user()->avatar;?>" alt="">
                            @else
                            <img src="{{ URL::asset('assets/img/default-avatar.png') }}" class="img-responsive">
                            @endif
                        </div>
                        <b class="caret d-none d-lg-block d-xl-block"></b>
                        <p class="d-lg-none">{{ __('Log out') }}</p>
                    </a>
                    <ul class="dropdown-menu dropdown-navbar">
                        <li class="nav-link">
                            <a href="{{ route('profile.edit') }}" class="nav-item dropdown-item">{{ __('My Profile') }}</a>
                        </li>
                        <li class="nav-link">
                        {{-- <a class="nav-item dropdown-item">{{ Auth::user()->hosp_id }}</a> --}}
                        </li>
                        <li class="nav-link">
                            <a href="#" data-toggle="dropdown" aria-expanded="false" class="nav-item dropdown-item">{{ __('Chat Technical') }}</a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li class="nav-link">
                            <a href="{{ route('logout') }}" class="nav-item dropdown-item" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">{{ __('Log out') }}</a>
                        </li>
                    </ul>
                </li>
                <li class="separator d-lg-none"></li>

            </ul>

        </div>
    </div>
</nav>


<div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="{{ __('SEARCH') }}">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                    <i class="tim-icons icon-simple-remove"></i>
              </button>
            </div>
        </div>
    </div>
</div>

@if(\Route::getFacadeRoot()->current()->uri() == 'admission.edit')
    <script src="{{ URL::asset('assets/js/function.js') }}"></script>
@endif


@push('js')

<script type="text/javascript">
    setInterval(startTime, 500);
</script>
@endpush
