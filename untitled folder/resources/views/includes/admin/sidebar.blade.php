<div id="sidebar" class="ui left vertical menu visible sidebar">
    <div class="logo-header header">
        <a class="item" href="{{ url('admin/dashboard')}}">
            <img alt="SendMunk Logo" src="{{ asset('SM_Avi.png') }}">
        </a>
    </div>
    <div class="ui accordion">
        <a class="item {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ url('admin/dashboard')}}">
            <i class="tachometer alternate icon"></i> Dashboard
        </a>
        <a class="item {{ Request::is('admin/customers') ? 'active' : '' }}" href="{{ url('admin/customers')}}">
            <i class="users icon"></i> Customers
        </a>
        <a class="item {{ Request::is('admin/register') ? 'active' : '' }}" href="{{ url('admin/register')}}">
            <i class="user icon"></i> Register User
        </a>
        {{-- <a class="item {{ Request::is('admin/generateLicense') ? 'active' : '' }}" href="{{ url('admin/generateLicense') }}">
            <i class="chart pie icon"></i> Generate License
        </a> --}}
        <a class="item {{ Request::is('admin/themes') ? 'active' : '' }}" href="{{ url('admin/themes') }}">
            <i class="affiliatetheme icon"></i> Themes
        </a>
        <span class="title item">
            <span class="header"><i class="dropdown icon"></i> Templates</span>
        </span>
        <div class="content">
            <a class="setting-item item {{ Request::is('admin/templates') ? 'active' : '' }}" href="{{ url('admin/templates')}}">
                <i class="mail icon"></i> Email Templates
            </a>
            <a class="setting-item item {{ Request::is('admin/polltemplates') ? 'active' : '' }}" href="{{ url('admin/polltemplates')}}">
                <i class="paper plane icon"></i> Poll Templates
            </a>
            <a class="setting-item item {{ Request::is('admin/quiztemplates') ? 'active' : '' }}" href="{{ url('admin/quiztemplates')}}">
                <i class="chart pie icon"></i> Quiz Templates
            </a>
            <a class="setting-item item {{ Request::is('admin/calculatortemplates') ? 'active' : '' }}" href="{{ url('admin/calculatortemplates')}}">
                <i class="calculator icon"></i> Calculator Templates
            </a>
        </div>     
        <a class="item" href="{{ url('logout') }}">
            <i class="sign out icon"></i> Sign Out
        </a>
    </div>
</div>