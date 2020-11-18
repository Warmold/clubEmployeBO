<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToDateTimeTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object to a string.
     *
     * @param string|null $dateTime
     *
     * @return \DateTime|null
     *
     * @throws \Exception
     */
    public function transform($dateTime = null): ?\DateTime
    {
        if (null === $dateTime) {
            return null;
        }

        return new \DateTime($dateTime);
    }

    /**
     * Transforms a uuid to an object.
     *
     * @param string $uuid
     *
     * @return array|null
     */
    public function reverseTransform($dateTime = null): ?string
    {
        if (!$dateTime) {
            return null;
        }

        return $dateTime->format(\DateTime::ATOM);
    }
}
