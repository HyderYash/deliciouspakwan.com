<?php
echo '            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <li>
                            <a href="/adminDashboard"><i class="fa fa-home fa-fw"></i> Admin Dashboard</a>
                        </li>';
						if($_SESSION['curr_user_type'] == 'A'){
echo '						<li>
								<a href="/getListing/users"><i class="fa fa-th-list fa-fw"></i> Users</a>
							</li>';
						} 
echo '                  <li>
                            <a href="/getListing/accounts"><i class="fa fa-th-list fa-fw"></i> Accounts</a>
                        </li>
                        <li>
                            <a href="/getListing/category"><i class="fa fa-th-list fa-fw"></i> Categories</a>
                        </li>
                        <li>
                            <a href="/getListing/items"><i class="fa fa-th-list fa-fw"></i> Items</a>
                        </li>
                        <li>
                            <a href="/getListing/colorcodes"><i class="fa fa-th-list fa-fw"></i> Color Codes</a>
                        </li>

                        <li>
                            <a href="/"><i class="fa fa-calendar fa-fw"></i> Go to Expenditure</a>
                        </li>						
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>';
?>