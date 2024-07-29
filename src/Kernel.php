<?php

class Kernel
{

    private string $servicePostfix = 'Service';
    private string $servicePrefix = 'Services';

    private function getService(string $serviceName): string
    {
        return $this->servicePrefix . '\\' . ucfirst($serviceName) . $this->servicePostfix;
    }

    private function getMethod(string $methodName): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $methodName))));
    }

    final public function run(): void
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            $params = explode('/', $_SERVER['REQUEST_URI']);
            $serviceName = $this->getService($params[1]);
            $methodName = $this->getMethod($params[2]);
            unset($params[0], $params[1], $params[2]);

            if ($serviceName) {
                try {
                    $service = new $serviceName();
                    $response = $service->{$methodName}(...$params);
                    $this->handleResponse($response);
                } catch (Throwable $throwable) {
                    var_dump($throwable);
                }
            }
        }
    }

    private function handleResponse($response): void
    {
        if (is_array($response)) {
            $response = json_encode($response, JSON_THROW_ON_ERROR);
        }

        if (is_string($response) || is_numeric($response)) {
            header('Content-Type: application/json');
            echo $response;
        }
    }

    public static function generateIdentifier(): string
    {
        return hash('sha256', uniqid(mt_rand(), true));
    }

    /**
     * @throws JsonException
     */
    public static function hydrateRequestBodyData(string $class)
    {
        $attributes = array_keys(get_class_vars($class));
        $entity = new $class();
        $requestBody = self::handleRequestBody();

        foreach ($attributes as $attribute) {
            if (!empty($requestBody[$attribute])) {
                $entity->$attribute = $requestBody[$attribute];
            }
        }

        return $entity;
    }

    /**
     * @throws JsonException
     */
    public static function handleRequestBody(): array
    {
        if (file_get_contents('php://input')) {
            $requestBody = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        } else {
            $requestBody = $_POST;

        }

        return $requestBody;
    }

}
