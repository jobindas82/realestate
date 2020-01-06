<div class="menu">
    <ul class="list">

        <!-- Menu Item -->
        <li class="header">Home</li>
        <li class="{{ Request::path() == '/' ? 'active' : '' }}">
            <a href="/">
                <i class="material-icons">dashboard</i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">Contracts & Accounting</li>
        <li class="{{ Route::is('contract.index') || Route::is('contract.create') ? 'active' : '' }}">
            <a href="/contract/index">
                <i class="material-icons">folder</i>
                <span>Leasing & Contracts</span>
            </a>
        </li>
        <li class="{{ Route::is('purchase.index') ? 'active' : '' }}">
            <a href="/">
                <i class="material-icons">history</i>
                <span>Purchase</span>
            </a>
        </li>
        <li class="{{ 
                            Route::is('finance.receipt.index') ||  
                            Route::is('finance.receipt.create') ||  
                            Route::is('finance.payment.index') ||
                            Route::is('finance.payment.create') ||  
                            Route::is('finance.journal.index') ||
                            Route::is('finance.journal.create') ||
                            Route::is('finance.cheque')
                            ? 'active' : '' 
                        }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">euro_symbol</i>
                <span>Financial & Banking</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('finance.receipt.index') ||  Route::is('finance.receipt.create') ? 'active' : '' }}">
                    <a href="/finance/receipt">
                        <span>Receipts</span>
                    </a>
                </li>
                <li class="{{ Route::is('finance.payment.index') ||  Route::is('finance.payment.create') ? 'active' : '' }}">
                    <a href="/finance/payment">
                        <span>Payments</span>
                    </a>
                </li>
                <li class="{{ Route::is('finance.journal.index') ||  Route::is('finance.journal.create') ? 'active' : '' }}">
                    <a href="/finance/journal">
                        <span>Journals</span>
                    </a>
                </li>
                <li class="{{ Route::is('finance.cheque') ? 'active' : '' }}">
                    <a href="/finance/cheques">
                        <span>Cheque Management</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="{{ 
                            Route::is('ledger.group.index') ||  
                            Route::is('ledger.group.create') ||
                            Route::is('ledger.index') ||  
                            Route::is('ledger.create')
                            ? 'active' : '' 
                        }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">view_list</i>
                <span>Ledgers & Groups</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('ledger.group.index') || Route::is('ledger.group.create') ? 'active' : '' }}">
                    <a href="/ledger/groups">
                        <span>Groups</span>
                    </a>
                </li>
                <li class="{{ Route::is('ledger.index') || Route::is('ledger.create') ? 'active' : '' }}">
                    <a href="/ledger">
                        <span>Ledgers</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">Tenants & Properties</li>
        <li class="{{ Route::is('tenant.index') || Route::is('tenant.create') ? 'active' : '' }}">
            <a href="/tenant/index">
                <i class="material-icons">record_voice_over</i>
                <span>Tenants</span>
            </a>
        </li>
        <li class="{{ Route::is('building.index') || Route::is('building.create') ? 'active' : '' }}">
            <a href="/building/index">
                <i class="material-icons">home</i>
                <span>Buildings</span>
            </a>
        </li>
        <li class="{{ 
                            Route::is('masters.flat_type.index') ||  
                            Route::is('masters.flat_type.create') ||    
                            Route::is('masters.construction_type.index') ||  
                            Route::is('masters.construction_type.create') ||    
                            Route::is('masters.country.index') ||  
                            Route::is('masters.country.create') ||  
                            Route::is('masters.location.index') ||  
                            Route::is('masters.location.create') ||  
                            Route::is('masters.job.index') ||  
                            Route::is('masters.job.create') ||  
                            Route::is('masters.tax.index') ||  
                            Route::is('masters.tax.create') ||  
                            Route::is('masters.document_type_index')  
                            ? 'active' : '' 
                        }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">settings</i>
                <span>Masters</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('masters.flat_type.index') || Route::is('masters.flat_type.create') ? 'active' : '' }}">
                    <a href="/masters/flat/index">
                        <span>Flat Type</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.construction_type.index') || Route::is('masters.construction_type.create') ? 'active' : '' }}">
                    <a href="/masters/construction/index">
                        <span>Construction Type</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.country.index') || Route::is('masters.country.create')? 'active' : '' }}">
                    <a href="/masters/country/index">
                        <span>Country</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.location.index') || Route::is('masters.location.create') ? 'active' : '' }}">
                    <a href="/masters/location/index">
                        <span>Location</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.job.index') ||  Route::is('masters.job.create')  ? 'active' : '' }}">
                    <a href="/masters/job/index">
                        <span>Job Type</span>
                    </a>
                </li>
                <li class="{{ Route::is('masters.tax.index') ||  Route::is('masters.tax.create') ? 'active' : '' }}">
                    <a href="/masters/tax/index">
                        <span>Tax Code</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">Facility Management</li>
        <li class="{{ Route::is('fm.tickets') ||  Route::is('fm.tickets.create') ? 'active' : '' }}">
            <a href="/fm/tickets">
                <i class="material-icons">subject</i>
                <span>Tickets</span>
            </a>
        </li>
        <!-- End -->

        <!-- Menu Item -->
        <li class="header">Reports</li>

        <li class="{{ Route::is('reports.building.summary') ||  Route::is('reports.flat.summary') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">layers</i>
                <span>Summary</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('reports.building.summary') ? 'active' : '' }}">
                    <a href="/report/filter/building">
                        <span>All-in-One</span>
                    </a>
                </li>
            </ul>
        </li>

       
        <li class="{{ Route::is('reports.finance.gl') || Route::is('reports.finance.tb') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">assessment</i>
                <span>Financial</span>
            </a>
            <ul class="ml-menu">
                <li class="{{ Route::is('reports.finance.gl') ? 'active' : '' }}">
                    <a href="/report/finance/gl">
                        <span>General Ledger</span>
                    </a>
                </li>
                <li class="{{ Route::is('reports.finance.tb') ? 'active' : '' }}">
                    <a href="/report/finance/tb">
                        <span>Trial balance</span>
                    </a>
                </li>
            </ul>
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