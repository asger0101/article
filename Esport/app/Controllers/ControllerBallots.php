<?php

namespace Controllers;

use Core\BaseController;
use Core\View;
use Models\Users\Users;
use Models\ballot\ballotSurvey;
use Helpers\Session;

class ControllerBallots extends BaseController {

    private $_ballotSurvey;

    public function __construct() {
        parent::__construct(true);

        $this->_ballotSurvey = new ballotSurvey();
    }

    public function showBallot() {
        
        if (\Helpers\Request::isPost()) {
            
            if (!Users::getInstance()->isLoggedIn()) {
                \Helpers\Messages::error("You need to be logged in before you can vote.");
                \Helpers\Url::redirect('account/login?redirect=' . implode('/', \Helpers\Url::segments()));
            }
            
            $ballot_id = filter_input(INPUT_POST, 'user_ballot', FILTER_SANITIZE_NUMBER_INT);
            $user_vote = filter_input(INPUT_POST, 'user_vote', FILTER_SANITIZE_STRING);
            if ($user_vote == "yes") {
                $this->_ballotSurvey->voteYes($ballot_id);
            } else if ($user_vote == "no") {
                $this->_ballotSurvey->voteNo($ballot_id);
            }
        }

        $this->show();
        
    }

    public function show() {
        $this->_data['active_ballot'] = $this->_ballotSurvey->getBallot();
        if ($this->_data['active_ballot']) {
            View::renderTemplate('ballot/header', $this->_data);
            View::render('ballot/index', $this->_data);
            View::renderTemplate('ballot/footer', $this->_data);
        } else {
            \Helpers\Url::redirect('ballot/has_voted');
        }
    }

    public function hasVoted() {
        View::renderTemplate('ballot/header', $this->_data);
        View::render('ballot/has_voted', $this->_data);
        View::renderTemplate('ballot/footer', $this->_data);
    }

    public function showAdmin() {
        if (!Users::getInstance()->isLoggedIn()) {
            \Helpers\Messages::error("Du skal være logget ind for at kunne bruge siden!");
            \Helpers\Url::redirect('account/login?redirect=' . implode('/', \Helpers\Url::segments()));
        }

        if (Session::get('admin_session') == "logged_in") {

            $this->_data['all_members'] = $this->_ballotSurvey->getBallot_admin();
            $this->_data['all_ballots'] = $this->_ballotSurvey->listAllBallots_admin();

            View::renderTemplate('ballot/header_admin', $this->_data);
            View::render('ballot/admin', $this->_data);
            View::renderTemplate('ballot/footer', $this->_data);
        }

        if (\Helpers\Request::isPost()) {

            if (filter_input(INPUT_POST, "aktiver_ballot")) {
                $ballot_id = filter_input(INPUT_POST, "ballot_id", FILTER_SANITIZE_NUMBER_INT);
                $this->_ballotSurvey->activate_Ballot($ballot_id);
                \Helpers\Messages::success("Afstemningen om denne person er aktiveret!");
                \Helpers\Url::redirect('ballot/admin');
            }

            if (filter_input(INPUT_POST, "deaktiver_ballot")) {
                $ballot_id = filter_input(INPUT_POST, "ballot_id", FILTER_SANITIZE_NUMBER_INT);
                $this->_ballotSurvey->deactivate_Ballot($ballot_id);
                \Helpers\Messages::success("Afstemningen om denne person er deaktiveret!");
                \Helpers\Url::redirect('ballot/admin');
            }

            if (filter_input(INPUT_POST, "opret_ballot")) {
                $select_uder_id = filter_input(INPUT_POST, 'select_user', FILTER_SANITIZE_NUMBER_INT);
                $user_name = $this->_ballotSurvey->getBallot_admin(" WHERE `id` = $select_uder_id");

                $this->_ballotSurvey->insert_ballot($select_uder_id, $name = $user_name[0]['firstname'] . " " . $user_name[0]['lastname']);
                \Helpers\Messages::success("Afstemning oprettet!");
                \Helpers\Url::redirect('ballot/admin');
            }

            if (filter_input(INPUT_POST, "admin_login_knap")) {

                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $password = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);

                if ($username == "cocoonesport" && $password == "Sommerfugl2016") {
                    \Helpers\Messages::success("Du er nu logget ind!");
                    Session::set('admin_session', 'logged_in');
                    \Helpers\Url::redirect('ballot/admin');
                } else {
                    \Helpers\Messages::error("Forkerte oplysninger, prøv igen!");
                    \Helpers\Url::redirect('ballot/admin');
                }
            }
        }

        if (!Session::get('admin_session') == "logged_in") {
            View::renderTemplate('ballot/header_admin', $this->_data);
            View::render('ballot/admin_login', $this->_data);
            View::renderTemplate('ballot/footer', $this->_data);
        }
    }

}
