<!-- Start Sidebar Area -->
<div class="side-menu-area">
    <div class="side-menu-logo bg-linear">
        <a href="{{route('user.dashboard')}}" class="navbar-brand d-flex align-items-center">
            <span>
                <img src="{{asset($web->logo2)}}" alt="image" style="width:150px;">
            </span>
        </a>

        <div class="burger-menu d-none d-lg-block">
            <span class="top-bar"></span>
            <span class="middle-bar"></span>
            <span class="bottom-bar"></span>
        </div>

        <div class="responsive-burger-menu d-block d-lg-none">
            <span class="top-bar"></span>
            <span class="middle-bar"></span>
            <span class="bottom-bar"></span>
        </div>
    </div>

    <nav class="sidebar-nav placeholder-glow" data-simplebar>
       @if($user->completedProfile==1)

            <ul id="sidebar-menu" class="sidebar-menu">
                <li class="nav-item-title">MENU</li>
                <li class="mm-active">
                    <a href="{{route('user.dashboard')}}" class="box-style">
                        <i class="ri-dashboard-3-line"></i>
                        <span class="menu-title">Overview</span>
                    </a>
                </li>

                <li class="nav-item-title">APPS</li>

                <li>
                    <a href="{{route('user.school.index')}}" class="box-style">
                        <i class="ri-file-history-line"></i>
                        <span class="menu-title">School</span>
                    </a>
                </li>


                <li>
                    <a href="{{route('user.dashboard')}}" class="box-style">
                        <i class="ri-wallet-2-line"></i>
                        <span class="menu-title">Wallet</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('user.dashboard')}}" class="box-style">
                        <i class="ri-account-box-line"></i>
                        <span class="menu-title">HR</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('user.dashboard')}}" class="box-style">
                        <i class="ri-apps-2-line"></i>
                        <span class="menu-title">Subscriptions</span>
                    </a>
                </li>
            </ul>


            <div class="dark-bar">
                <a href="#" class="d-flex align-items-center">
                    <span class="dark-title">Enable Dark Theme</span>
                </a>

                <div class="form-check form-switch">
                    <input type="checkbox" class="checkbox" id="darkSwitch">
                </div>
            </div>
        @else
            <ul id="sidebar-menu" class="sidebar-menu placeholder-glow">
                <li class="mm-active">
                    <a href="{{route('user.dashboard')}}" class="box-style">
                        <i class="ri-dashboard-3-line"></i>
                        <span class="menu-title">Overview</span>
                    </a>
                </li>
                <li class="post">
                    <div class="avatar placeholder"></div>
                    <div class="line placeholder"></div>
                    <div class="line placeholder"></div>
                </li>
                <li class="post">
                    <div class="avatar placeholder"></div>
                    <div class="line placeholder"></div>
                    <div class="line placeholder"></div>
                </li>
                <li class="post">
                    <div class="avatar placeholder"></div>
                    <div class="line placeholder"></div>
                    <div class="line placeholder"></div>
                </li>
                <li class="post">
                    <div class="avatar placeholder"></div>
                    <div class="line placeholder"></div>
                    <div class="line placeholder"></div>
                </li>
                <li class="post">
                    <div class="avatar placeholder"></div>
                    <div class="line placeholder"></div>
                    <div class="line placeholder"></div>
                </li>

            </ul>

            <div class="dark-bar">
                <a href="#" class="d-flex align-items-center">
                    <span class="dark-title">Enable Dark Theme</span>
                </a>

                <div class="form-check form-switch">
                    <input type="checkbox" class="checkbox" id="darkSwitch">
                </div>
            </div>
        @endif
    </nav>
</div>
<!-- End Sidebar Area -->

<!-- Start Main Content Area -->
<div class="main-content d-flex flex-column">
    <div class="container-fluid">
        <nav class="navbar main-top-navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="responsive-burger-menu d-block d-lg-none">
                    <span class="top-bar"></span>
                    <span class="middle-bar"></span>
                    <span class="bottom-bar"></span>
                </div>


                <ul class="navbar-nav ms-auto mb-lg-0">
                    <li class="nav-item">
                        <a href="#" class="nav-link ri-fullscreen-btn" id="fullscreen-button">
                            <i class="ri-fullscreen-line"></i>
                        </a>
                    </li>

                    <li class="nav-item dropdown apps-box">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="ri-function-line"></i>
                        </button>

                        <div class="dropdown-menu">
                            <div class="dropdown-header d-flex justify-content-between align-items-center bg-linear">
                                <span class="title d-inline-block">Stores</span>
                                <span class="edit-btn d-inline-block">Edit</span>
                            </div>

                            <div class="dropdown-body" data-simplebar>
                                <div class="d-flex flex-wrap align-items-center">

                                    <a href="#" class="dropdown-item">
                                        <img src="{{asset('dashboard/images/apps/icon-account.png')}}" alt="image">
                                        <span class="d-block mb-0">Account</span>
                                    </a>

                                </div>
                            </div>

                            <div class="dropdown-footer">
                                <a href="#" class="dropdown-item">View All</a>
                            </div>
                        </div>
                    </li>


                    <li class="nav-item notification-box dropdown">
                        <div class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="notification-btn">
                                <i class="ri-notification-2-line"></i>
                                <span class="badge">6</span>
                            </div>
                        </div>

                        <div class="dropdown-menu">
                            <div class="dropdown-header d-flex justify-content-between align-items-center bg-linear">
                                <span class="title d-inline-block">6 New Notifications</span>
                                <span class="mark-all-btn d-inline-block">Mark all as read</span>
                            </div>

                            <div class="dropdown-body" data-simplebar>

                                <a href="inbox" class="dropdown-item d-flex align-items-center">
                                    <div class="icon">
                                        <i class='bx bx-message-rounded-dots'></i>
                                    </div>

                                    <div class="content">
                                        <span class="d-block">Just sent a new message!</span>
                                        <p class="sub-text mb-0">2 sec ago</p>
                                    </div>
                                </a>

                            </div>

                            <div class="dropdown-footer">
                                <a href="inbox" class="dropdown-item">View All</a>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown profile-nav-item">
                        <a class="nav-link dropdown-toggle avatar" href="#" id="navbarDropdown-4"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{$user->name}}&size=50&rounded=true&background=random"
                                 alt="Images">
                            <h3>{{$user->name}}</h3>
                            <span>{{$user->reference}}</span>
                        </a>

                        <div class="dropdown-menu">
                            <div class="dropdown-header d-flex flex-column align-items-center">
                                <div class="figure mb-3">
                                    <img src="https://ui-avatars.com/api/?name={{$user->name}}&size=50&rounded=true&background=random"
                                         class="rounded-circle" alt="image">
                                </div>

                                <div class="info text-center d-md-none">
                                    <span class="name">{{$user->name}}</span>
                                    <p class="mb-3 email">
                                        {{$user->reference}}
                                    </p>
                                </div>
                            </div>

                            <div class="dropdown-body">
                                <ul class="profile-nav p-0 pt-3">
                                    <li class="nav-item">
                                        <a href="profile" class="nav-link">
                                            <i class="ri-user-line"></i>
                                            <span>Profile</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="inbox" class="nav-link">
                                            <i class="ri-ticket-2-fill"></i>
                                            <span>Support Ticket</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="settings" class="nav-link">
                                            <i class="ri-settings-5-line"></i>
                                            <span>Settings</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="dropdown-footer">
                                <ul class="profile-nav">
                                    <li class="nav-item">
                                        <a href="{{route('user.logout')}}" class="nav-link">
                                            <i class="ri-login-circle-line"></i>
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ri-settings-5-line"></i>
                        </a>
                    </li>
                    -->
                </ul>
            </div>
        </nav>
    </div>
    <div class="mb-2 mt-3">
        <a href="javascript: history.go(-1)"><i class="bx bx-arrow-to-left"></i> Go back</a>
    </div>
