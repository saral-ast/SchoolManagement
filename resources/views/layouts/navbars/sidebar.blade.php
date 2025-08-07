<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">BD</a>
            <a href="#" class="simple-text logo-normal">Black Dashboard</a>
        </div>
        <ul class="nav">
            <li @class(['active' => request()->routeIs('dashboard')])>
                <a href="{{ route('dashboard') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="true">
                    <i class="fab fa-laravel"></i>
                    <span class="nav-link-text">Details</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="laravel-examples">
                    <ul class="nav pl-4">
                        
                        @permission('view.admins')
                        <li @class(['active' => request()->routeIs('admin.index')])>
                            <a href="{{ route('admin.index') }}">
                                <i class="tim-icons icon-settings-gear-63"></i>
                                <p>Admin Details</p>
                            </a>
                        </li>
                        @endpermission 
                        @permission('view.teachers')
                        <li @class(['active' => request()->routeIs('teacher.index')])>
                            <a href="{{ route('teacher.index') }}">
                                <i class="tim-icons icon-book-bookmark"></i>
                                <p>Teacher Details</p>
                            </a>
                        </li>
                        @endpermission
                       
                        @permission('view.students')
                        {{-- @dd('ef') --}}
                        <li @class(['active' => request()->routeIs('student.index')])>
                            <a href="{{ route('student.index') }}">
                                <i class="tim-icons icon-badge"></i>
                                <p>Student Details</p>
                            </a>
                        </li>
                        @endpermission 
                        @permission('view.parents')
                        <li @class(['active' => request()->routeIs('parent.index')])>
                            <a href="{{ route('parent.index') }}">
                                <i class="tim-icons icon-components"></i>
                                <p>Parent Details</p>
                            </a>
                        </li>
                        @endpermission 
                        

                    </ul>
                </div>
            </li>

           <li @class(['active' => request()->routeIs('profile.edit')])>
               <a href="{{ route('profile.edit') }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>User Profile</p>
                 </a>             
                </li>
            {{-- <li @class(['active' => request()->routeIs('pages.maps')])>
                <a href="{{ route('pages.maps') }}">
                    <i class="tim-icons icon-pin"></i>
                    <p>Maps</p>
                </a>
            </li> --}}
            
            @permission('view.schedules')
            <li @class(['active' => request()->routeIs('schedule.index') || request()->routeIs('schedule.create')])>
                <a href="{{ route('schedule.index') }}">
                    <i class="tim-icons icon-calendar-60"></i>
                    <p>Time Table</p>
                </a>
            </li>
            @endpermission
            @permission('view.results')
                        <li @class(['active' => request()->routeIs('result.index')])>
                            <a href="{{ route('result.index') }}">
                                <i class="tim-icons icon-components"></i>
                                <p>Results</p>
                            </a>
                        </li>
            @endpermission 
                    </ul>
    </div>
</div>
