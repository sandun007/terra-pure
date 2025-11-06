<style>
    /* Outer wrapper */
    .sidebar {
        height: 100vh; /* Full viewport height */
        overflow: hidden; /* Prevent double scrollbars */
        position: fixed;
        top: 0;
        left: 0;
        width: 250px; /* Adjust as needed */
        background: #fff;
        z-index: 1000;
    }

    /* Inner scrollable container */
    .sidebar-inner {
        height: 100%;
        overflow-y: auto;
        padding-bottom: 50px; /* Optional: ensures space for last item */
    }

    /* Optional: make sure ul stretches properly */
    .sidebar-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
</style>
<div class="sidebar mt-5" id="sidebar">
    <div class="sidebar-inner">
        <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Order</h6>
                        <ul>
                            <li><a href="{{route('product.index')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                    <span>Pending Packing</span></a></li>
                            <li>
                                <a href="{{route('employee.dashboard')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-plus-square">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                    <span>Packing Order</span></a></li>

                            <li>
                                <a href="{{route('low-stock')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-trending-down">
                                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                        <polyline points="17 18 23 18 23 12"></polyline>
                                    </svg>
                                    <span>Delivered Order</span></a>
                            </li>
                            <li>
                                <a href="{{route('category.create')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-codepen">
                                        <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"></polygon>
                                        <line x1="12" y1="22" x2="12" y2="15.5"></line>
                                        <polyline points="22 8.5 12 15.5 2 8.5"></polyline>
                                        <polyline points="2 15.5 12 8.5 22 15.5"></polyline>
                                        <line x1="12" y1="2" x2="12" y2="8.5"></line>
                                    </svg>
                                    <span>Return Order</span></a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
        <div class="slimScrollBar"
             style="background: rgb(204, 204, 204); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 174.757px;"></div>
        <div class="slimScrollRail"
             style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
    </div>
{{--</div>--}}

<script>
    document.getElementById('toggle-sidebar').addEventListener('click', function () {
        const sidebar = document.getElementById('application-sidebar');
        const content = document.getElementById('main-content');

        sidebar.classList.toggle('sidebar-collapsed');
        content.classList.toggle('content-expanded');
    });


</script>
