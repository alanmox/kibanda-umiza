<?php
interface ModelInterface
{
    public function create();
    public function read($id);
    public function update();
    public function delete($id);
    public function getAll();
    public function validate();
}
