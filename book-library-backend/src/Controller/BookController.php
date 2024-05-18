<?php

namespace App\Controller;

use App\Book\BookRequest;
use App\Book\BookResponse;
use App\Entity\Book;
use App\Form\Type\BookType;
use App\Repository\BookRepository;
use App\Service\ValidationErrorService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface as ValidationErrors;

class BookController extends AbstractController
{
    private ValidationErrorService $validationErrorService;
    private BookRepository $bookRepository;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializerInterface;

    public function __construct(
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializerInterface,
        ValidationErrorService $validationErrorService
    ) {
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
        $this->serializerInterface = $serializerInterface;
        $this->validationErrorService = $validationErrorService;
    }

    #[Route('/books', name: 'get_all_book', methods: 'get')]
    public function getAllBook(Request $request): JsonResponse
    {
        $limit = $request->query->get('limit', 10);
        $offset = $request->query->get('offset', 1);

        $bookList = $this->bookRepository->findAllBook($limit, $offset);

        return $this->json($bookList);
    }

    #[Route('/book', name: 'add_book', methods: 'post')]
    /**
     * @ParamConverter(name="book", converter="app.param_converter.book_request")
     */
    public function addBook(
        Book $book,
        ValidationErrors $validationErrors
    ): JsonResponse {
        $this->validationErrorService->checkForValidationErrors($validationErrors);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'book added',
            'status' => 200,
        ]);
    }

    #[Route('/book/{id}', name: 'edit_book', methods: 'put')]
    public function editBook(string $id, Request $request): JsonResponse
    {
        $book = $this->bookRepository->findOneById($id);

        /**
         * @var Book $bookSerialize
         */
        $bookSerialize = $this->serializerInterface->deserialize($request->getContent(), Book::class, 'json', ['object_to_populate' => $book]);

        $bookSerialize->updateUpdatedAt();

        $this->entityManager->flush();


        return $this->json([
            'message' => 'Book updated successfully',
            'book' => $bookSerialize
        ]);
    }

    #[Route('/book/{id}', name: 'delete_book', methods: 'delete')]
    public function deleteBook(string $id): JsonResponse
    {
        $book = $this->bookRepository->findOneById($id);

        if (!isset($book)) {
            throw new BadRequestHttpException(
                'Book not found',
                null,
                400
            );
        }

        $this->entityManager->remove($book);

        $this->entityManager->flush();

        return $this->json([
            'message' => 'Book id ' . $id . ' removed'
        ]);
    }
}
