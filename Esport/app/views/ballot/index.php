        <div class="container-fluid">
            <h1>Stem på mod!</h1>
        </div>

        

        <div class="container">
            <?php echo \Helpers\Messages::messages(); ?>
            
            <?php foreach($data['active_ballot'] as $b){ ?>
            <div class="form_container">
                <h1><?php echo $b['name']; ?></h1>
                <form id="succes_form" method="post">
                    <button type="submit" class="btn btn-success">JA!</button>
                    <input type="hidden" name="user_vote" value="yes">
                    <input type="hidden" name="user_ballot" value="<?php echo $b['userId'] ?>">
                </form>
                <form id="danger_form" method="post">
                    <button type="submit" class="btn btn-danger">NEJ!</button>
                    <input type="hidden" name="user_vote" value="no">
                    <input type="hidden" name="user_ballot" value="<?php echo $b['userId'] ?>">
                </form>
            </div>
            <?php } ?>
        </div>
        

        <!--<div class="container">
            <h3 style="font-style: italic;">Der er ikke flere aftemninger lige nu!</h3>
        </div>-->