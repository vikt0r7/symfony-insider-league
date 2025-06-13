<?php

declare(strict_types=1);

namespace App\Shared\Resolver;

use App\Shared\Attribute\RequestDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DtoValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();
        if (!$type || !class_exists($type)) {
            return [];
        }

        $reflection = new \ReflectionClass($type);
        $attributes = $reflection->getAttributes(RequestDto::class);
        if (empty($attributes)) {
            return [];
        }

        $data = [];

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true)) {
            $content = $request->getContent();
            if ($content) {
                $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            }
        } elseif ('GET' === $request->getMethod()) {
            $data = $request->query->all();
        }

        if (!is_array($data)) {
            throw new BadRequestHttpException('Invalid request data');
        }

        $dto = $this->serializer->denormalize($data, $type);

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getPropertyPath().': '.$error->getMessage();
            }
            throw new BadRequestHttpException(implode('; ', $messages));
        }

        yield $dto;
    }
}
