<?php 
include('storage.php');
class UsersStorage extends Storage
{
    public function __construct()
    {
        parent::__construct(new JsonIO('users.json'));
    }

    public function findByUserName(string $username)
    {
        return  $this -> findOne(['username' => $username ]);
    }
}
?>