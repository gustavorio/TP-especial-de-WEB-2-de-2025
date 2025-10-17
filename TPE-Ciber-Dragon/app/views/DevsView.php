<?php

class DevsView {
    public function showDevs($devs) {
        require './templates/devsList.phtml';
    }

    public function showAddDev($message) {
        require './templates/addDev.phtml';
    }

    public function showEditDev($dev) {
        require './templates/editDev.phtml';
    }
}