<div class="menu">
    <ul class="list">
        <!-- Menu Item -->
        <li class="header">Contracts</li>
        <li class="{{ Route::is('portal.home') ? 'active' : '' }}">
            <a href="/portal/home">
                <i class="material-icons">folder</i>
                <span>Contracts</span>
            </a>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">Complaints & Suggestions</li>
        <li class="{{ Route::is('portal.tickets') || Route::is('portal.create.ticket') ? 'active' : '' }}">
            <a href="/portal/tickets">
                <i class="material-icons">subject</i>
                <span>Tickets</span>
            </a>
        </li>
        <!-- End -->
    </ul>
</div>