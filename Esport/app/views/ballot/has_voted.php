
<?php 
if(\Helpers\Session::get("ballot_aktiv")){
    $var = "En afstemning med dig er aktiv lige nu!";
} else {
    $var = "Der er ikke flere aftemninger lige nu!";
}
?>


<div class="container-fluid">
    <h1>Stem på mod!</h1>
</div>

<div class="container">
    <?php echo \Helpers\Messages::messages(); ?>
    <div class="form_container">
        <h3 style="font-style: italic;"><?php echo $var; ?></h3>
    </div>
</div>