<?php
    include_once('php/db.php');
    session_destroy();
    //session_abort();
    header('Location: index.php');