<?php
require_once "./app/models/GamesModel.php";
require_once "./app/models/DevsModel.php";
require_once "./app/views/GamesView.php";
require_once "./app/views/ErrorView.php";
require_once "./app/helpers/AuxHelper.php";

class GamesController {
    private $gamesModel;
    private $devsModel;
    private $view;

    public function __construct() {
        $this->gamesModel = new GamesModel();
        $this->devsModel = new DevsModel();
        $this->view = new GamesView();
    }

    public function showGames($devId = -1) {
        if($devId == -1) {
            $games = $this->gamesModel->getGames();
        } else {
            $games = $this->gamesModel->getGamesByDevId($devId);
        }
        $devs = $this->devsModel->getDevs();
        foreach($games as $game) {
            $game->fechaLanzamiento = AuxHelper::reformatDate($game->fechaLanzamiento);
        }
        $this->view->showGames($games, $devs);
    }

    public function showAddGame($message = null) {
        $devs = $this->devsModel->getDevs();
        $this->view->showAddGame($devs, $message);
    }

    public function addNewGame() {
        if (empty($_POST['nombreJuego']) || empty($_POST['fechaLanzamiento']) || empty($_POST['desarrolladorId']) || empty($_POST['edad']) || empty($_POST['descripcionJuego']) || empty($_POST['imagen'])) {
            $this->showAddGame('Faltan completar campos');
        } elseif (AuthHelper::isLogged()) {
            $this->gamesModel->addGame();
            header('Location: ' . BASE_URL);
        } else {
            header('Location: ' . BASE_URL);
        }
    }

    public function showGameById($id) {
        $game = $this->gamesModel->getGameById($id);
        $game->fechaLanzamiento = AuxHelper::reformatDate($game->fechaLanzamiento);
        $game->fechaCreacion = AuxHelper::reformatDate($game->fechaCreacion);
        if($game) {
            $this->view->showGame($game);
        } else {
            $ErrorView = new ErrorView();
            $ErrorView->showError('El juego seleccionado no existe.');
        }
    }

    public function deleteGame($id) {
        if (AuthHelper::isLogged()) {
            $this->gamesModel->deleteGame($id);
        }
        header('Location: ' . BASE_URL);
    }

    public function editGame($id) {
        $game = $this->gamesModel->getGameById($id);
        $devs = $this->devsModel->getDevs();
        $this->view->showEditGame($game, $devs);
    }

    public function gameEdited($id) {
        if (AuthHelper::isLogged()) {
            $this->gamesModel->editGame($id);
        }
        header('Location: ' . BASE_URL);
    }
}