<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->is('instructor/dashboard') ? '' : 'collapsed' }}"
               href="{{ route('instructor.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-heading">My Courses</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-collection-play"></i>
                <span>My Courses</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-plus-circle"></i>
                <span>Create Course</span>
            </a>
        </li>

        <li class="nav-heading">Students</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-people"></i>
                <span>My Students</span>
            </a>
        </li>

        <li class="nav-heading">Account</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>

    </ul>
</aside>
