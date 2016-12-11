<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="A demo blog">
        <meta name="author" content="Carmencito Berdon">
        <!--<link rel="icon" href="../../favicon.ico">-->

        <title><?php echo config_item('sitename') ?></title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(config_item('theme_url') . 'plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?php echo base_url(config_item('theme_url') . 'css/ie10-viewport-bug-workaround.css') ?>" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url(config_item('theme_url') . 'css/blog.css') ?>" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link href="<?php echo base_url(config_item('theme_url') . 'plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="<?php echo base_url(config_item('theme_url') . 'js/ie8-responsive-file-warning.js') ?>"></script><![endif]-->
        <script src="<?php echo base_url(config_item('theme_url') . 'js/ie-emulation-modes-warning.js') ?>"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="blog-masthead">
            <div class="container">
                <nav class="blog-nav">
                    <a class="blog-nav-item <?php echo strcasecmp(get_controllername(), 'home') == 0 ? 'active' : '' ?>" href="<?php echo site_url('home') ?>">Home</a>
                    
                    <?php if (is_loggedin() === true) : ?>
                        <a class="blog-nav-item <?php echo strcasecmp(get_controllername(), 'home') != 0 ? 'active' : '' ?>" href="<?php echo site_url('user') ?>">Dashboard</a>
                    <?php endif; ?>

                    <?php if (!empty($google_auth_url)) : ?>
                        <a href="<?php echo $google_auth_url ?>" class="pull-right google-signin-button"></a>
                    <?php endif; ?>
                        
                    <?php if (is_loggedin() === true && !empty($userdata)) : ?>
                        <div class="blog-nav-item google-profile-icon pull-right">
                            <?php if ( ! empty($userdata['picture_url'])) : ?>
                            <img src="<?php echo $userdata['picture_url'] ?>" width="30" />
                            <?php endif; ?>
                            Welcome <b><?php echo $userdata['first_name'] ?>!</b>
                            (<a href="<?php echo site_url('user/authentication/logout') ?>">Logout</a>)
                        </div>
                    <?php endif; ?>
                </nav>
            </div>
        </div>

        <div class="container">

            <div class="blog-header">
                <h1 class="blog-title"><?php echo config_item('sitename') ?></h1>
                <p class="lead blog-description">A demo blog</p>
            </div>

            <div class="row">

                <div class="<?php echo strcasecmp(get_controllername(), 'home') != 0 ? 'col-sm-12' : 'col-sm-8' ?> blog-main">

                    <?php echo!empty($content) ? $content : '' ?>

                    <?php if ( ! empty($pagination)) : ?>
                    <nav>
                        <ul class="pager">
                            <?php if ( ! empty($pagination['previous_page'])) : ?>
                            <li><a href="<?php echo $pagination['base_url'].$pagination['previous_page'] ?>">Previous</a></li>
                            <?php endif; ?>
                            
                            <?php if ( ! empty($pagination['next_page'])) : ?>
                            <li><a href="<?php echo $pagination['base_url'].$pagination['next_page'] ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>

                </div><!-- /.blog-main -->

                <?php if (strcasecmp(get_controllername(), 'home') == 0) : ?>
                    <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
                        <div class="sidebar-module sidebar-module-inset">
                            <h4>About</h4>
                            <p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
                        </div>
                    </div><!-- /.blog-sidebar -->
                <?php endif; ?>

            </div><!-- /.row -->

        </div><!-- /.container -->

        <footer class="blog-footer">
            <p>&copy; Carmencito Berdon 2016</p>
        </footer>


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url(config_item('theme_url') . 'js/vendor/jquery.min.js') ?>"><\/script>')</script>
        <script src="<?php echo base_url(config_item('theme_url') . 'plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?php echo base_url(config_item('theme_url') . 'js/ie10-viewport-bug-workaround.js') ?>"></script>
        <script src="<?php echo base_url(config_item('theme_url') . 'js/blog.js') ?>"></script>
    </body>
</html>