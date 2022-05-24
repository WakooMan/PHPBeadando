<?php 
include('storage.php');
class SeriesStorage extends Storage
{
    public function __construct()
    {
        parent::__construct(new JsonIO('series.json'));
    }
}

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