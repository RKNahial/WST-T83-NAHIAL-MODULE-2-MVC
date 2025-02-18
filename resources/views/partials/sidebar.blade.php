<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}" 
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-heading">Pages</li>

        <!-- Students Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.students.*') ? '' : 'collapsed' }}" 
               href="{{ route('admin.students.index') }}">
                <i class="bi bi-people"></i>
                <span>Students</span>
            </a>
        </li><!-- End Students Nav -->

        <!-- Subjects Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.subjects.*') ? '' : 'collapsed' }}" 
               href="{{ route('admin.subjects.index') }}">
                <i class="bi bi-journal-text"></i>
                <span>Subjects</span>
            </a>
        </li><!-- End Subjects Nav -->

        <!-- Enrollments Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.enrollments.*') ? '' : 'collapsed' }}" 
               href="{{ route('admin.enrollments.index') }}">
                <i class="bi bi-person-check"></i>
                <span>Enrollments</span>
            </a>
        </li><!-- End Enrollments Nav -->

        <!-- Grades Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.grades.*') ? '' : 'collapsed' }}" 
               href="{{ route('admin.grades.index') }}">
                <i class="bi bi-card-list"></i>
                <span>Grades</span>
            </a>
        </li><!-- End Grades Nav -->

        <li class="nav-heading">Settings</li>

        <!-- Profile Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('profile.edit') }}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Profile Nav -->

        <!-- Logout Nav -->
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a class="nav-link collapsed" href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Sign Out</span>
                </a>
            </form>
        </li><!-- End Logout Nav -->

    </ul>
</aside><!-- End Sidebar-->