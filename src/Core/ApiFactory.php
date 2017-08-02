<?php namespace RescueCore\Core;

class ApiFactory
{
    private static $digest = false;

    public static function call($procedure, $digest = false)
    {
        static::$digest = $digest;
        list($name, $method) = explode('.', $procedure);
        $class = ucwords($name);
        if (!class_exists($class) || !method_exists($class, $method)) {
            static::error("Method not found ($procedure)");
        }

        return (new $class)->$method();
    }

    public static function toJson($data)
    {
        if (static::$digest) {
            static::$digest = false;

            return $data;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function success($data)
    {
        $data = array_merge([
            'ok' => true
        ], $data);

        return static::toJson($data);
    }

    public static function error($message)
    {
        return static::toJson([
            'ok'    => false,
            'error' => $message
        ]);
    }

}
