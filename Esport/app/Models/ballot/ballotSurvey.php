<?php
namespace Models\ballot;

use Core\Model;
use Models\Users\Users;

class ballotSurvey extends Model
{
    /**
     * Initialize
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function getBallot() {
        $ballots = array();
        $active_ballots_for_user = array();
        $exist = array();
        $count = 1;
        
        $ballots = $this->db->select("SELECT * FROM " . PREFIX . "ballot WHERE `active` = 1;");
        
        foreach($ballots as $b) {
            $exist = $this->db->select("SELECT * FROM " . PREFIX . "ballot_votes WHERE `userId` = ".Users::getInstance()->getUser()->getId()." AND `ballot_id` = ".$b['id']."");
            
            if(Users::getInstance()->getUser()->getId() == $b['userId']){
                \Helpers\Session::set("ballot_aktiv","hejejejjasdjasdj");
            }
            
                if(Users::getInstance()->getUser()->getId() != $b['userId']) {
                    if(!$exist){
                        $active_ballots_for_user[$count] = array(
                            "name" => $b['name'],
                            "userId" => $b['id']
                        );
                        $count++;
                    } 
                }
            
        }
        if(sizeof($active_ballots_for_user) > 0){
            return $active_ballots_for_user;
        } else {
            \Helpers\Url::redirect('ballot/has_voted');
        }
    }

    /**
     * Create user vote
     * @param Integer|(0-1) $vote
     */
    private function userVote($vote,$id) {
        $this->db->insert("ballot_votes", array("userId" => Users::getInstance()->getUser()->getId(), "vote" => $vote, "vote_date" => date("Y-m-d H:i:s"), "ballot_id" => $id));
        \Helpers\Messages::success("Tak fordi du stemte!");
        \Helpers\Url::redirect('ballot');
    }
    
    /**
     * Create User Vote - No
     */
    public function voteNo($user_id) {
        $this->userVote(0,$user_id);
    }
    
    /**
     * Create User Vote - Yes
     */
    public function voteYes($user_id) {
        $this->userVote(1,$user_id);
    }
    
    public function getBallot_admin($where = false) {
        $ballots = array();
        $ballots = $this->db->select("SELECT id, firstname, lastname FROM " . PREFIX . "members$where");
        return $ballots;
    }
    
    public function insert_ballot($id,$name) {        
        $this->db->insert("ballot", array("userId" => $id, "name" => $name, "active" => "0", "ballot_date" => date("Y-m-d H:i:s")));
    }
    
    public function listAllBallots_admin() {
        $allBallots = array();
        $allBallots_andVotes = array();
        $votes = array();
        $count = 1;
        
        $allBallots = $this->db->select("SELECT id, name, active, ballot_date FROM " . PREFIX . "ballot ORDER BY active DESC, ballot_date DESC");

        foreach($allBallots as $value){
            $votes['YES'] = $this->db->select("SELECT COUNT(id) as `SCORE`  FROM " . PREFIX . "ballot_votes WHERE `vote` = 1 AND `ballot_id` = '". $value['id'] ."'");
            $votes['NO'] = $this->db->select("SELECT COUNT(id) as `SCORE`  FROM " . PREFIX . "ballot_votes WHERE `vote` = 0 AND `ballot_id` = '". $value['id'] ."'");
        
            $allBallots_andVotes[$count] = array(
                    'ballot_id' => $value['id'],
                    'ballot_name' => $value['name'],
                    'ballot_active' => $value['active'],
                    'ballot_date' => $value['ballot_date'],
                    'vote_yes' => $votes['YES'],
                    'vote_no' => $votes['NO']
            );
            $count++;
        }
        return $allBallots_andVotes;
    }
    
    public function updateBallot($boolean,$id) {
        $this->db->update("ballot", array("active" => $boolean), array("id" => $id));
    }
    
    public function activate_Ballot($id) {
        $this->updateBallot(1,$id);
    }
    
    public function deactivate_Ballot($id) {
        $this->updateBallot(0,$id);
    }
}
