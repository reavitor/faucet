
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
        <repeat group="@menu" key="@title" value="@link">
            <li {{ @PATH==@link ? "class='active'" : '' }}><a href="{{ @BASE }}{{ @link }}">
                    <check if="@link=='/'"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></check>{{ @title }}</a></li>
        </repeat>        
        </ul>

        <ul class="nav navbar-nav navbar-right">
        <check if="@site_settings.show_admin_link==1 || @SESSION.admin===active">
            <li><a href="{{ @BASE }}/admin" class="navbar-link"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>Admin</a></li>
        </check>            
        <check if="@SESSION.admin===active">
            <li><a href="{{ @BASE }}/logout" class="navbar-link"><span class="glyphicon glyphicon-off" aria-hidden="true"></span>Logout</a></li>
        </check>

        </ul>
    </div>
</nav>