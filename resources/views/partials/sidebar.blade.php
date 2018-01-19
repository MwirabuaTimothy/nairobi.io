  <?php
    $active = strrchr($_SERVER['REQUEST_URI'], "/");
    // var_dump($active);
    // die;
  ?>

      <!--sidebar start-->
      <aside>
        <div id="sidebar"  class="nav-collapse ">
          <!-- sidebar menu start-->
          <ul class="sidebar-menu" id="nav-accordion">
            <li class="sub-menu" <?php echo $active == "/index" || $active == "/" ? 'alt="active"' : '';  ?>>
              <a href="#" <?php echo $active == "/index" || $active == "/" ? 'class="active"' : '';  ?>>
               <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
              </a>
              <ul class="sub index">
                <li><a  href="index#summary">
                  <span>Summary</span>
                </a></li>
                <li><a  href="index#sales">
                  <span>Sales/Check-ins</span>
                </a></li>
                <li><a  href="index#redeems">
                  <span>Redeem Promos</span>
                  <span class="label label-warning pull-right mail-info">3 new</span>
                </a></li>
                <li><a  href="index#product-perfomance">
                  <span>Product Perfomance</span>
                </a></li>
                <li><a  href="index#customer-stats">
                  <span>Customer Statistics</span>
                </a></li>
                <li><a  href="index#sales-perfomance">
                  <span>Sales Perfomance</span>
                  <span class="label label-success pull-right mail-info">15%</span>
                </a></li>
              </ul>
            </li>

            <li class="sub-menu" <?php echo $active == "/messages" ? 'alt="active"' : '';  ?>>
              <a href="#" <?php echo $active == "/messages" ? 'class="active"' : '';  ?>>
               <i class="fa fa-envelope"></i>
                <span>Messages</span>
                <span class="label label-primary pull-right mail-info">6 unread</span>
              </a>
              <ul class="sub">
                <li><a  href="messages#all-inbox">
                  <span>All Inbox</span>
                  <span class="label label-default pull-right mail-info">5328</span>
                </a></li>
                <li><a  href="messages#twitter">
                  <span>Twitter</span>
                  <span class="label label-default pull-right mail-info">1815</span>
                </a></li>
                <li><a  href="messages#facebook">
                  <span>Facebook</span>
                  <span class="label label-default pull-right mail-info">1353</span>
                </a></li>
                <li><a  href="messages#starred">
                  <span>Starred</span>
                  <span class="label label-warning pull-right mail-info">135</span>
                </a></li>
                <li><a  href="messages#sent">
                  <span>Sent</span>
                  <span class="label label-default pull-right mail-info">4852</span>
                </a></li>
                <li><a  href="messages#sms-balance">
                  <span>SMS Balance</span>
                  <span class="label label-danger pull-right mail-info">1809</span>
                </a></li>
              </ul>
            </li>

            <li class="sub-menu" <?php echo $active == "/products" ? 'alt="active"' : '';  ?>>
              <a href="#" <?php echo $active == "/products" ? 'class="active"' : '';  ?>>
               <i class="fa fa-tags"></i>
                <span>Our Products</span>
              </a>
              <ul class="sub">
                <li><a  href="products#cat-1">
                  <span>Category 1</span>
                  <span class="label label-default pull-right mail-info">15</span>
                </a></li>
                <li><a  href="products#cat-2">
                  <span>Category 2</span>
                  <span class="label label-default pull-right mail-info">6</span>
                </a></li>
                <li><a  href="products#cat-3">
                  <span>Category 3</span>
                  <span class="label label-default pull-right mail-info">5</span>
                </a></li>
                <li><a  href="products#cat-4">
                  <span>Category 4</span>
                  <span class="label label-default pull-right mail-info">5</span>
                </a></li>
                <li><a  href="products#cat-5">
                  <span>Category 5</span>
                  <span class="label label-default pull-right mail-info">3</span>
                </a></li>
              </ul>
            </li>

            <li class="sub-menu" <?php echo $active == "/promotions" ? 'alt="active"' : '';  ?>>
              <a href="#" <?php echo $active == "/promotions" ? 'class="active"' : '';  ?>>
               <i class="fa fa-gift"></i>
                <span>Our Promotions</span>
              </a>
              <ul class="sub">
                <li><a  href="promotions#one-time">
                  <span>One Time</span>
                  <span class="label label-default pull-right mail-info">6</span>
                </a></li>
                <li><a  href="promotions#daily">
                  <span>Daily</span>
                  <span class="label label-default pull-right mail-info">1</span>
                </a></li>
                <li><a  href="promotions#weekly">
                  <span>Weekly</span>
                  <span class="label label-default pull-right mail-info">1</span>
                </a></li>
                <li><a  href="promotions#monthly">
                  <span>Monthly</span>
                  <span class="label label-default pull-right mail-info">1</span>
                </a></li>
                <li><a  href="promotions#annual">
                  <span>Annual</span>
                  <span class="label label-default pull-right mail-info">3</span>
                </a></li>
              </ul>
            </li>

            <li class="sub-menu" <?php echo $active == "/account" ? 'alt="active"' : '';  ?>>
              <a href="#" <?php echo $active == "/account" ? 'class="active"' : '';  ?>>
               <i class="fa fa-cogs"></i>
                <span>Account Settings</span>
              </a>
            </li>

            <li class="sub-menu" <?php echo $active == "/readers" ? 'alt="active"' : '';  ?>>
              <a href="#" <?php echo $active == "/readers" ? 'class="active"' : '';  ?>>
               <i class="fa fa-bullseye"></i>
                <span>Readers</span>
                <span class="label label-default pull-right mail-info">1</span>
              </a>
            </li>

            <li class="sub-menu disabled" <?php echo $active == "/market" ? 'alt="active"' : '';  ?>>
              <a href="#" <?php echo $active == "/market" ? 'class="active"' : '';  ?>>
               <i class="fa fa-eye"></i>
                <span>Market Intelligence</span>
              </a>
              <ul class="sub">
                <li><a  href="market#businesses">
                  <span>Businesses</span>
                  <span class="label label-default pull-right mail-info">12</span>
                </a></li>
                <li><a  href="market#products">
                  <span>Products</span>
                  <span class="label label-default pull-right mail-info">341</span>
                </a></li>
                <li><a  href="market#promotions">
                  <span>Promotions</span>
                  <span class="label label-default pull-right mail-info">842</span>
                </a></li>
                <li><a  href="market#users">
                  <span>Users</span>
                  <span class="label label-default pull-right mail-info">16127</span>
                </a></li>
              </ul>
            </li>
            <!--multi level menu start-->
            <li class="sub-menu" <?php echo $active == "/help" ? 'alt="active"' : '';  ?>>
              <a href="#" <?php echo $active == "/help" ? 'class="active"' : '';  ?>>
               <i class="fa fa-question-circle"></i>
                <span>Help</span>
              </a>
            </li>
            <!--multi level menu end-->

          </ul>
          <!-- sidebar menu end-->
        </div>
      </aside>
      <!--sidebar end-->
