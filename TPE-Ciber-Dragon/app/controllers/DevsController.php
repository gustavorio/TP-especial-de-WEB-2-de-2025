<?php
require_once './app/models/DevsModel.php';
require_once "./app/models/GamesModel.php";
require_once './app/views/DevsView.php';
require_once "./app/helpers/AuthHelper.php";

class DevsController {
    private $model;
    private $gamesModel;
    private $view;

    public function __construct() {
        $this->model = new DevsModel();
        $this->gamesModel = new GamesModel();
        $this->view = new DevsView();
    }

    public function showDevs() {
        $devs = $this->model->getDevs();
        foreach($devs as $dev) {
            $dev->fechaCreacion = AuxHelper::reformatDate($dev->fechaCreacion);
        }
        $this->view->showDevs($devs);
    }

    public function showAddDev($message = null) {
        $this->view->showAddDev($message);
    }

    public function addNewDev() {
        if (empty($_POST['nombreDesarrollador']) || empty($_POST['fechaCreacion']) || empty($_POST['origen'])) {
            $this->showAddDev('Faltan completar campos');
        } elseif (AuthHelper::isLogged()) {
            $this->model->addDev();
            header('Location: ' . BASE_URL . '/developers');
        } else {
            header('Location: ' . BASE_URL . '/developers');
        }
    }

    public function editDev($id) {
        $dev = $this->model->getDev($id);
        $this->view->showEditDev($dev);
    }

    public function devEdited($id) {
        if (AuthHelper::isLogged()) {
            $this->model->editDev($id);
        }
        header('Location: ' . BASE_URL . '/developers');
    }

    public function deleteDev($id) {
        $games = $this->gamesModel->getGamesByDevId($id);
        if (count($games) == 0 && AuthHelper::isLogged()) {
            $this->model->deleteDev($id);
        }
        header('Location: ' . BASE_URL . '/developers');
    }
}