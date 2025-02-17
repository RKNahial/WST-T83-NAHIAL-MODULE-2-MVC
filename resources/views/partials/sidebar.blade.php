<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-heading">Pages</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.students.*') ? '' : 'collapsed' }}" href="{{ route('admin.students.index') }}">
                <i class="bi bi-people"></i>
                <span>Students</span>
            </a>
        </li><!-- End Students Nav -->

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.subjects.*') ? '' : 'collapsed' }}" href="{{ route('admin.subjects.index') }}">
                <i class="bi bi-journal-text"></i>
                <span>Subjects</span>
            </a>
        </li><!-- End Subjects Nav -->

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.enrollments.*') ? '' : 'collapsed' }}" href="{{ route('admin.enrollments.index') }}">
                <i class="bi bi-person-check"></i>
                <span>Enrollments</span>
            </a>
        </li><!-- End Enrollments Nav -->

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.grades.*') ? '' : 'collapsed' }}" href="{{ route('admin.grades.index') }}">
                <i class="bi bi-card-list"></i>
                <span>Grades</span>
            </a>
        </li><!-- End Grades Nav -->

        <li class="nav-heading">Settings</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>F.A.Q</span>
            </a>
        </li> -->
        <!-- End F.A.Q Page Nav -->

        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="pages-contact.html">
                <i class="bi bi-envelope"></i>
                <span>Contact</span>
            </a>
        </li> -->
        <!-- End Contact Page Nav -->
    </ul>
</aside><!-- End Sidebar-->