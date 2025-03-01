<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        @if(auth()->user()->role === 'admin')
            <!-- Admin Navigation -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Admin Pages</li>

            <!-- Students Nav -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.students.*') ? '' : 'collapsed' }}" 
                   href="{{ route('admin.students.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Students</span>
                </a>
            </li>

            <!-- Subjects Nav -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.subjects.*') ? '' : 'collapsed' }}" 
                   href="{{ route('admin.subjects.index') }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Subjects</span>
                </a>
            </li>

            <!-- Enrollments Nav -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.enrollments.*') ? '' : 'collapsed' }}" 
                   href="{{ route('admin.enrollments.index') }}">
                    <i class="bi bi-person-check"></i>
                    <span>Enrollments</span>
                </a>
            </li>

            <!-- Grades Nav -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.grades.*') ? '' : 'collapsed' }}" 
                   href="{{ route('admin.grades.index') }}">
                    <i class="bi bi-card-list"></i>
                    <span>Grades</span>
                </a>
            </li>

        @elseif(auth()->user()->role === 'student')
            <!-- Student Navigation -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.dashboard') ? '' : 'collapsed' }}" 
                   href="{{ route('student.dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Student Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Student Pages</li>

            <!-- Academic Records Nav -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.records.*') ? '' : 'collapsed' }}"
                   data-bs-target="#academic-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-mortarboard"></i>
                    <span>Academic Records</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="academic-nav" class="nav-content collapse {{ request()->routeIs('student.records.*') ? 'show' : '' }}" 
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('student.records.index') }}" 
                           class="{{ request()->routeIs('student.records.index') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i>
                            <span>GWA Summary</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Enrollment Records Nav -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.enrollment.*') ? '' : 'collapsed' }}" 
                   data-bs-target="#enrollment-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text"></i>
                    <span>My Enrollment</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="enrollment-nav" class="nav-content collapse {{ request()->routeIs('student.enrollment.*') ? 'show' : '' }}" 
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('student.enrollment.subjects') }}" 
                           class="{{ request()->routeIs('student.enrollment.subjects') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i>
                            <span>Current Subjects</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.enrollment.history') }}" 
                           class="{{ request()->routeIs('student.enrollment.history') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i>
                            <span>Enrollment History</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Common Navigation Items for Both Roles -->
        <!-- Profile Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('profile.edit') }}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>

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
        </li>

    </ul>
</aside><!-- End Sidebar-->