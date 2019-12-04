<div class="menu">
    <ul class="list">

        <!-- Menu Item -->
        <li class="header">Home</li>
        <li class="{{ Request::path() == '/' ? 'active' : '' }}">
            <a href="/">
                <i class="material-icons">home</i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">Contracts & Accounting</li>
        <li class="{{ Route::is('leasing.index') ? 'active' : '' }}">
            <a href="/">
                <i class="material-icons">home</i>
                <span>Leasing & Contracts</span>
            </a>
        </li>
        <li class="{{ Route::is('purchase.index') ? 'active' : '' }}">
            <a href="/">
                <i class="material-icons">home</i>
                <span>Purchase</span>
            </a>
        </li>
        <li class="{{ 
                            Route::is('finance.receipts') ||  
                            Route::is('finance.payments') ||  
                            Route::is('finance.journals') 
                            ? 'active' : '' 
                        }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">group</i>
                <span>Financial & Banking</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('finance.receipts') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Receipts</span>
                    </a>
                </li>
                <li class="{{ Route::is('finance.payments') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Payments</span>
                    </a>
                </li>
                <li class="{{ Route::is('finance.journals') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Journals</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="{{ 
                            Route::is('ledger.groups') ||  
                            Route::is('ledger.ledgers') ||  
                            Route::is('ledger.employee')
                            ? 'active' : '' 
                        }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">group</i>
                <span>Ledgers & Groups</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('ledger.groups') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Groups</span>
                    </a>
                </li>
                <li class="{{ Route::is('ledger.ledgers') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Ledgers</span>
                    </a>
                </li>
                <li class="{{ Route::is('ledger.employee') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Employee</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">Tenants & Properties</li>
        <li class="{{ Route::is('tenant.index') ? 'active' : '' }}">
            <a href="/">
                <i class="material-icons">home</i>
                <span>Tenants</span>
            </a>
        </li>
        <li class="{{ 
                            Route::is('building.index') ||  
                            Route::is('flat.index')
                            ? 'active' : '' 
                        }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">group</i>
                <span>Properties</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('building.index') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Buildings</span>
                    </a>
                </li>
                <li class="{{ Route::is('flat.index') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Flats</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="{{ 
                            Route::is('masters.flat_type_index') ||  
                            Route::is('masters.construction_type_index') ||  
                            Route::is('masters.country_index') ||  
                            Route::is('masters.location_index') ||  
                            Route::is('masters.maintenance_type_index') ||  
                            Route::is('masters.tax_code_index') ||  
                            Route::is('masters.document_type_index')  
                            ? 'active' : '' 
                        }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">group</i>
                <span>Masters</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('masters.flat_type_index') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Flat Type</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.construction_type_index') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Construction Type</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.country_index') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Country</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.location_index') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Location</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.maintenance_type_index') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Maintenance Type</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.tax_code_index') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Tax Code</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.document_type_index') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Document Type</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">Facility Management</li>
        <li class="{{ Route::is('fm.tickets') ? 'active' : '' }}">
            <a href="/">
                <i class="material-icons">home</i>
                <span>Tickets</span>
            </a>
        </li>
        <li class="{{ Route::is('fm.jobs') ? 'active' : '' }}">
            <a href="/">
                <i class="material-icons">home</i>
                <span>Jobs</span>
            </a>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">Administration</li>
        <li class="{{ Route::is('users.index') ||  Route::is('users.create') ||  Route::is('users.edit') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">group</i>
                <span>Users & Groups</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('users.index') ||  Route::is('users.create') ||  Route::is('users.edit') ? 'active' : '' }}">
                    <a href="/users/index">
                        <span>Users</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">System Info</li>
        <li>
            <a href="configuration/changelog">
                <i class="material-icons">update</i>
                <span>Changelog</span>
            </a>
        </li>
        <!-- End -->

    </ul>
</div>