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
                    <span>Student</span>
                </a>
            </li>

            <!-- Subjects Nav -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.subjects.*') ? '' : 'collapsed' }}" 
                   href="{{ route('admin.subjects.index') }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Subject</span>
                </a>
            </li>

            <!-- Enrollments Nav -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.enrollments.*') ? '' : 'collapsed' }}" 
                   href="{{ route('admin.enrollments.index') }}">
                    <i class="bi bi-person-check"></i>
                    <span>Enrollment</span>
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
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Student Pages</li>

            <!-- Current Subjects -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.enrollment.subjects') ? '' : 'collapsed' }}" 
                   href="{{ route('student.enrollment.subjects') }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Subjects</span>
                </a>
            </li>

            <!-- GWA Summary -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.records.index') ? '' : 'collapsed' }}" 
                   href="{{ route('student.records.index') }}">
                    <i class="bi bi-graph-up"></i>
                    <span>Grades</span>
                </a>
            </li>
        @endif

        <!-- Common Navigation Items for Both Roles -->
        <li class="nav-heading">Account Settings</li>
        
        <!-- Profile Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('profile.edit') }}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>

        <!-- Logout Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Sign Out</span>
            </a>
        </li>

    </ul>
</aside><!-- End Sidebar-->

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to log out?
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Logout</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>