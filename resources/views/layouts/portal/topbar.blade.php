<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="#">{{ request()->tenantModel->name  }}</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="pull-right"><a title="Logout" href="{{ route('portal.logout') }}" class="js-right-sidebar" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons">highlight_off</i></a></li>
                <form id="logout-form" action="{{ route('portal.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>
        </div>
    </div>
</nav>