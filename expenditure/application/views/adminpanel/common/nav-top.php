<?php

echo '      <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>            
			<ul class="nav navbar-top-links navbar-right">
                <li style="margin-top:15px;margin-right:20px;">Welcome ' . $_SESSION['curr_user_name'] . '!! </li><li style="margin-top:15px;">Login at: ' . date('l jS \of F Y h:i:s A') . '</li>
            </ul>';
?>