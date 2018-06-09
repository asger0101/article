<form class="form-inline" method="post">
    <?php echo \Helpers\Messages::messages(); ?>
    <input type="text" name="username" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="Username" required="">
    <input type="password" name="pass" class="form-control" placeholder="Password" required="">

    <input type="submit" name="admin_login_knap" class="btn btn-primary" value="sub">
</form>