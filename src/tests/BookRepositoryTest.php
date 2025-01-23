<?php
//include "";
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Cheatze\Library\Book;
use Cheatze\Library\Author;
use Cheatze\Library\BookRepository;
use Cheatze\Library\Main;

use function PHPUnit\Framework\assertSame;

class BookRepositoryTest extends TestCase
{
    public function testAuthorMethods()
    {
        $author = new Author('Bobby', 'Bobson', new DateTimeImmutable('1970-01-01'));
        //Author tests
        $this->assertEquals(1, $author->getId(), "Author id didn't start at 1");
        $this->assertEquals("Bobby Bobson", $author->getName(), 'Wrong author name');
        $this->assertEquals('1970-01-01', $author->getDateOfBirthAsString(), 'Wrong date of birth');

    }

    public function testBookMothods()
    {
        $author = new Author('Bobby', 'Bobson', new DateTimeImmutable('1970-01-01'));
        $book = new Book("The Story", $author, "12345", "Pinguin", '2023-01-01', 99);

        //Book tests
        $this->assertEquals(1, $book->getId(), "Book id didn't start at 1");
        $this->assertEquals('The Story', $book->getTitle(), 'Wrong title');
        $this->assertEquals('12345', $book->getIsbn(), 'Wrong isbn');
        $this->assertEquals('Pinguin', $book->getPublisher(), 'Wrong publisher');
        $this->assertEquals($author, $book->getAuthor(), 'Wrong author');
        $this->assertEquals("Bobby Bobson", $book->getAuthorName(), "Wrong author name");
        $this->assertEquals('12345', $book->getIsbn(), 'Wrong ISBN');




        //$this->assertTrue(true);

    }
    public function testRepositoryMethods()
    {
        $author = new Author('Bobby', 'Bobson', new DateTimeImmutable('1970-01-01'));
        $book = new Book("The Story", $author, "12345", "Pinguin", '2023-01-01', 99);
        $repo = new BookRepository();
        $repo->add($book);

        //Repository testss
        $return = $repo->returnById(1);
        $this->assertNotNull($return, "Book not found");
        $this->assertEquals($book, $return, "Wrong book?");
        $returnAll = $repo->getAll();
        $this->assertTrue(is_array($returnAll), 'getAll did not return an array');

    }

}