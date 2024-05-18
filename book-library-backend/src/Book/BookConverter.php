<?php

namespace App\Book;

use App\Entity\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookConverter implements ParamConverterInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $class = $configuration->getClass();
        $name = $configuration->getName();
        $content = $request->getContent();
        $recordStateRequest = $this->serializer->deserialize($content, $class, 'json');
        $request->attributes->set($name, $recordStateRequest);

        $errors = $this->validator->validate($recordStateRequest);
        $request->attributes->set('validationErrors', $errors);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === Book::class;
    }
}
