<?php
namespace Cheatze\Library;
include "../vendor/autoload.php";

//Make BookRepository object
$repo = new BookRepository();

//Make main and pass the repository
$game = new Main($repo);
//Makes and adds test Author/Book objects to the books/authors array
require_once "TestData.php";

$game->mainMenu();

