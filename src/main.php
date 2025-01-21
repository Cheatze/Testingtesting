<?php
namespace Cheatze\Library;

$authors = [];

class Main
{

    //Repository object
    public object $repository;

    //Sets the repository object
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    //Directly adds a book, onl used by TestData
    public function addForTest($nBook)
    {
        $this->repository->add($nBook);
    }

    /**
     * Displays the id and full name of all authors
     * Chooses an author based on id number and returns that author
     * @param mixed $authors
     * @return mixed
     */
    public function pickAuthor($authors)
    {
        do {
            foreach ($authors as $author) {
                echo $author->getId() . ' ' . $author->getFirstName() . ' ' . $author->getLastName() . "\n";
            }
            $authorId = readline("Choose an author by ID number: ");
            $authorId = (int) $authorId;

            // Find the author with the given ID
            $chosenAuthor = null;
            foreach ($authors as $author) {
                if ($author->getId() == $authorId) {
                    $chosenAuthor = $author;
                    break;
                }
            }

            if ($chosenAuthor !== null) {
                break;
            } else {
                echo "That author ID does not exist.\n";
            }
        } while (true);

        return $chosenAuthor;
    }

    //Displays all the details of a given book and lets you delete it
    public function bookDetails(object $book, int $removeBookId)
    {
        //I think all of this can go in a method with the argument $book

        echo "Title: " . $book->getTitle() . "\n";

        // Convert the Author object to a string representation
        $author = $book->getAuthor();
        echo "Author: " . $author->getFirstName() . " " . $author->getLastName() . "\n";

        echo "ISBN: " . $book->getIsbn() . "\n";
        echo "Publisher: " . $book->getPublisher() . "\n";
        echo "Publication Date: " . $book->getPublicationDateAsString() . "\n";
        echo "Page Count: " . $book->getPagecount() . "\n\n";

        $confirmation = readline("Do you want to delete this book? Yes/No/Menu ");
        $confirmation = strtolower($confirmation);

        if ($confirmation === 'yes') {
            //addapt and change to removeById;
            $this->repository->removeById($removeBookId);
            echo '"' . $book->getTitle() . '" removed.\n';
        } elseif ($confirmation === 'no') {

            $this->bookDetails($book, $removeBookId);
        } else {
            $this->mainMenu();
        }
    }

    //Shows the details of all the books objects in a given array
    public function bookLoop(array $books)
    {
        foreach ($books as $book) {
            echo "Title: " . $book->getTitle() . "\n";

            // Convert the Author object to a string representation
            $author = $book->getAuthor();
            echo "Author: " . $author->getFirstName() . " " . $author->getLastName() . "\n";

            echo "ISBN: " . $book->getIsbn() . "\n";
            echo "Publisher: " . $book->getPublisher() . "\n";
            echo "Publication Date: " . $book->getPublicationDateAsString() . "\n";
            echo "Page Count: " . $book->getPagecount() . "\n\n";
        }
    }

    //Gets all the details for a new book and adds it to the array through the repository
    public function addBook()
    {
        global $authors;

        $chosenAuthor = $this->pickAuthor($authors);

        $bookTitle = readline("Enter the title: ");
        $bookNumber = readline("Enter the ISBN: ");
        $publisher = readline("Enter the publisher: ");
        $pageCount = readline("Enter the page count: ");


        // Loop until a valid publication date is entered
        while (true) {
            $publicationDate = readline("Enter the publication date (YYYY-MM-DD): ");
            $publicationDateObj = DateTimeImmutable::createFromFormat('Y-m-d', $publicationDate);

            if ($publicationDateObj && $publicationDateObj->format('Y-m-d') === $publicationDate) {
                break; // Exit the loop if the date is valid
            } else {
                echo "Invalid date format. Please use YYYY-MM-DD.\n";
            }
        }

        // Create a new Book instance
        $newBook = new Book($bookTitle, $chosenAuthor, $bookNumber, $publisher, $publicationDateObj, (int) $pageCount);

        // Store the Book instance in the books array
        $this->repository->add($newBook);

        echo "$bookTitle has been added. \n";
    }


    //Removes a book
    public function removeBook()
    {
        do {
            // Display the list of books with their titles and indexes
            $this->showAll();


            $removeBookId = readline("Enter the id of the title you want to remove: ");

            // Check if the entered id exists
            $bool = $this->repository->checkForId($removeBookId);
            if (!$bool) {
                echo "That id does not exist.\n";
                continue;
            }

            //Returns the book of the chosen id
            $removeBook = $this->repository->returnById($removeBookId);
            $confirmation = readline('Are you sure you want to remove "' . $removeBook->getTitle() . '"? Yes or No: ');
            $confirmation = strtolower($confirmation);

            if ($confirmation === 'yes') {
                //Addapt and change to removeById
                $this->repository->removeById($removeBookId);
                echo '"' . $removeBook->getTitle() . '" removed.\n';
                break;
            } elseif ($confirmation === 'no') {
                return;
            }
        } while (true);
    }

    public function showAll()
    {
        $books = $this->repository->getAll();
        //add a check if the books array is empty
        if (empty($books)) {
            echo "There are no books in the array.";
        } else {
            foreach ($books as $key => $book) {
                //echo $key . ': ' . $book->getTitle() . " by: " . $book->getAuthorName() . "\n";
                echo 'Id: ' . $book->getId() . ': ' . $book->getTitle() . " by: " . $book->getAuthorName() . "\n";
            }
        }


    }

    public function showAllBooks()
    {
        //Shows a index/title list of all books
        $this->showAll();

        //Ask if you want to remove a book and return to main menu if no
        $question = readline("Do you want to see al the details of a certain book? yes/no ");
        $question = strtolower($question);
        if ($question == "no") {
            $this->mainMenu();
        }

        //Change to detailsBookId
        $detailsBookId = readline("Enter the id of a title if you want to see its details: ");
        $detailsBookId = (int) $detailsBookId;

        // Check if the entered index is valid
        $bool = $this->repository->checkForId($detailsBookId);
        if (!$bool) {
            echo "That index does not exist.\n ";
            $this->showAllBooks();
        }

        $detailsBook = $this->repository->returnById($detailsBookId);

        $this->bookDetails($detailsBook, $detailsBookId);



    }

    //Shows books by a chosen author
    public function showAuthorBooks()
    {
        global $authors;

        // Get the chosen author object
        $chosenAuthor = $this->pickAuthor($authors);
        $chosenAuthorId = $chosenAuthor->getId();

        // Filter books by author ID
        $filteredBooks = $this->repository->filterById($chosenAuthorId);

        //Shows all books by chosen author and their details
        if (empty($filteredBooks)) {
            echo "There are no books by that author \n";
        } else {
            $this->bookLoop($filteredBooks);
        }
    }

    public function mainMenu()
    {
        while (true) {
            echo "What do you want to do? \n";
            echo "1: add a book \n";
            echo "2: Remove a book \n";
            echo "3: Show all books \n";
            echo "4: Show all books of a certain author \n";
            echo "5: exit \n";
            $choice = (int) readline("Choose by number: ");
            switch ($choice) {
                case "1":
                    $this->addBook();
                    break;
                case "2":
                    $this->removeBook();
                    break;
                case "3":
                    $this->showAllBooks();
                    break;
                case "4":
                    $this->showAuthorBooks();
                    break;
                case "5":
                    exit();
                default:
                    $this->mainMenu();
            }
        }
    }

}