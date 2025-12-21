<style>
    .side-nav {
        position: fixed;
        top: 0;
        right: -250px;
        height: 100%;
        width: 250px;
        background-color: #ffffff;
        color: #000000;
        padding: 20px;
        transition: right 0.3s;
    }

    .side-nav ul {
        list-style: none;
        padding: 0;
    }

    .side-nav li {
        margin-bottom: 10px;
    }

    .side-nav a {
        text-decoration: none;
        color: #000000;
    }

    .side-nav .close-button {
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
    }

    .quick-links-button {
        position: fixed;
        top: 50%;
        right: -40px;
        font-weight: 600;
        transform: translateY(-50%) rotate(90deg);
        cursor: pointer;
        background-color: #fff;
        color: #000000;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        transition: background-color 0.3s, color 0.3s;
    }

    .quick-links-button:hover {
        background-color: #009EF7;
        color: #fff;
    }

    .nav-link.active {
        color: #007AFF !important;
        background-color: #f0f8ff !important;
    }

    .nav-link:hover {
        color: #007AFF !important;
    }

    [data-layout=vertical][data-sidebar-size=sm] .logo span.logo-lg {
        display: none !important;
    }

    [data-layout=vertical][data-sidebar=dark] .navbar-nav .nav-sm .nav-link:hover {
        color: #007AFF !important;
    }

    [data-layout=vertical][data-sidebar=dark] .navbar-nav .nav-sm .nav-link:hover:before {
        background-color: #007AFF !important;
    }
</style>

