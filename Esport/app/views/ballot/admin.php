<?php 




?>
<div class="container">
    <?php echo \Helpers\Messages::messages(); ?>
    <div class="row">
        <div class="col-sm-5">
            <div>
                <h3>Opret ny afstemning!</h3>
            </div>
            <form class="form-inline" method="post">
                <div class="form-group">
                    <select class="form-control" name="select_user">
                        <?php foreach ($data['all_members'] as $b) { ?>
                            
                            <option value="<?php echo $b['id'] ?>">
                                <?php echo $b['firstname'] . " " . $b['lastname']; ?>
                            </option>
                            
                            
                        <?php } ?>
                    </select>
                    <input type="submit" name="opret_ballot" class="btn btn-success" value="Opret afstemning">
                </div>
            </form>
        </div>
        <div class="col-sm-7">
            <h3>Se alle afstemninger!</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Navn</th>
                        <th>Ja</th>
                        <th>Nej</th>
                        <th>Oprettet</th>
                        <th>Aktiver/Deaktiver</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                    foreach($data['all_ballots'] as $value){
                        
                    if($value['ballot_active'] == 1){
                        $row_class = "active";
                        $button = true;
                    } else {
                        $row_class = "not_active";
                        $button = false;
                    }
                    
                    
                    
                    ?>
                <form method="post">
                    <tr class="<?php echo $row_class; ?>">
                        <td><?php echo $value['ballot_name']; ?></td>
                        <td><?php echo $value['vote_yes'][0]['SCORE']; ?></td>
                        <td><?php echo $value['vote_no'][0]['SCORE']; ?></td>
                        <td><?php echo $value['ballot_date']; ?></td>
                        <td>
                            <?php if($button){ ?>
                            <input type="submit" name="deaktiver_ballot" class="btn btn-danger" value="Deaktiver">
                            <input type="hidden" name="ballot_id" value="<?php echo $value['ballot_id']; ?>">
                            <?php } else { ?>
                            <input type="submit" name="aktiver_ballot" class="btn btn-success" value="Aktiver">
                            <input type="hidden" name="ballot_id" value="<?php echo $value['ballot_id']; ?>">
                            <?php } ?>
                        </td>
                    </tr>      
                </form>
                
                <?php 
                
                }
                
                ?>
                </tbody>
            </table>
        </div>

    </div>
</div>