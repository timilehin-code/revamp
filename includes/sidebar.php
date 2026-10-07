<aside class="sidebar">
    <div class="brand">
        <span class="brand-mark">T</span><span class="brand-name">Dashboard</span>
    </div>
    <nav class="nav">
        <button class="nav-btn active" data-view="home">
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">
                <path d="M3 11l9-7 9 7" />
                <path d="M5 10v10h14V10" />
            </svg>
            <a href="../admin/" class="label">Home Page</a>
        </button>
        <button class="nav-btn" data-view="posts">
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">
                <rect x="3" y="4" width="18" height="16" rx="2" />
                <path d="M7 9h10M7 13h10M7 17h6" />
            </svg>
            <a href="../admin/blogs" class="label">Blog Posts</a>
        </button>
        <button class="nav-btn" data-view="new">
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">
                <path d="M12 5v14M5 12h14" />
            </svg>
            <a href="../admin/create-blog" class="label">New Post</a>
        </button>
    </nav>
    <div class="sidebar-foot text-danger d-flex align-items-center gap-2">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-power">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M7 6a7.75 7.75 0 1 0 10 0" />
            <path d="M12 4l0 8" />
        </svg>
        <a href="/revamp/admin/logout" class="text-danger"> Log out</a>
    </div>
</aside>