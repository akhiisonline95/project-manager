<?php

class Controller
{
    /**
     * @var array|mixed
     */
   protected array $data;
    protected function view($view, $data = [])
    {
        if(sizeof($data) > 0) $this->data = $data;
        extract($this->data);
        require __DIR__ . "/../../app/views/$view.php";
    }

    protected function model(string $model)
    {
        require __DIR__ . "/../../app/models/{$model}.php";
    }

    protected function redirect(string $controller)
    {
        header("Location: index.php?controller=$controller&action=index");
        exit;
    }
}
