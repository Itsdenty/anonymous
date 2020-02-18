<aside id="leftsidebar" class="sidebar">
    <ul class="nav">
        <li class="nav-item"><a class="nav-link active" href="#dashboard"><img class="img-responsive" src="{{ asset('SM_Avi.png') }}"></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane stretchRight active" id="dashboard">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info">
                            <div class="detail">
                                <h4>Name</h4>
                            </div>
                        </div>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle header"><span>Subaccount</span></a>
                        <ul class="ml-menu">
                            <li> <a href="ec-dashboard.html">Default</a></li>
                        </ul>
                    </li>
                    <li class="{{ Request::is('campaigns') ? 'active open' : '' }}"> <a href="{{ url('campaigns')}}"><i class="material-icons">alarm</i><span>Campaigns</span></a>
                    </li>
                    <li> <a href="sequences.html"><i class="material-icons">timeline</i><span>Sequences</span> </a>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-assignment"></i><span>SMS/MMS</span> </a>
                        <ul class="ml-menu">
                            <li> <a href="#">Messages</a></li>
                            <li> <a href="#">Bots</a></li>
                            <li> <a href="#">Campaigns</a></li>
                        </ul>
                    </li>
                    <li class="content"> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-assignment"></i><span>Forms</span> </a>
                        <ul class="ml-menu">
                            <li > <a href="basic forms.html">Basic Forms</a></li>
                            <li> <a href="polls.html">Polls</a></li>
                            <li> <a href="quizzes.html">Quizzes</a></li>
                            <li> <a href="calculators.html">Calculators</a></li>
                        </ul>
                    </li>
                    <li> <a href="push-notifications.html"><i class="material-icons">touch_app</i><span>Push Notifications</span> </a>
                    <li> <a href="contacts.html"><i class="material-icons">account_circle</i><span>Contacts</span> </a>
                    </li>
                    <li> <a href="templates.html"><i class="material-icons">view_module</i>><span>Templates</span> </a>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="material-icons">autorenew</i><span>Automation</span> </a>
                        <ul class="ml-menu">
                            <li> <a href="#">Visual Automation</a> </li>
                            <li> <a href="#">RSS Feeds</a> </li>
                        </ul>
                    </li>
                    <li class="content"> <a href="javascript:void(0);" class="menu-toggle"><i class="material-icons">settings</i><span>Settings</span> </a>
                        <ul class="ml-menu">
                            <li><a href="subaccount.html">Subaccount</a> </li>
                            <li><a href="team.html">Team</a> </li>
                            <li class="setting-item {{ Request::is('from_reply') ? 'active open' : '' }}"><a href="{{ url('from_reply') }}">Sender Email Settings</a> </li>
                            <li><a href="sms-mms-settings.html">SMS/MMS Settings</a> </li>
                            <li><a href="integration.html">Integration</a> </li>
                            <li><a href="contact-attribute.html">Contact Attribute</a> </li>
                            <li><a href="account-settings.html">Account Settings</a> </li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-lock"></i><span>Authentication</span> </a>
                        <ul class="ml-menu">
                            <li><a href="sign-in.html">Sign In</a> </li>
                            <li><a href="sign-up.html">Sign Up</a> </li>
                        </ul>
                    </li>
                    <li>
                        <div class="progress-container progress-primary m-t-10">
                            <span class="progress-badge">Activity this Month</span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100" style="width: 67%;">
                                    <span class="progress-value">67%</span>
                                </div>
                            </div>
                        </div>
                        <div class="progress-container progress-info">
                            <span class="progress-badge">Subscription</span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="width: 86%;">
                                    <span class="progress-value">86%</span>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        </div>
    </div>
</aside>