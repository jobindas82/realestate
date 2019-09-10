<div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="{{ Request::path() == '/' ? 'active' : '' }}">
                        <a href="/">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
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
                    <li class="header">System Info</li>
                    <li>
                        <a href="pages/changelogs.html">
                            <i class="material-icons">update</i>
                            <span>Changelogs</span>
                        </a>
                    </li>
                </ul>
            </div>
