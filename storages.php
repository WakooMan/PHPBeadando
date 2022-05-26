<?php 
include('storage.php');
class SeriesStorage extends Storage
{
    public function __construct()
    {
        parent::__construct(new JsonIO('series.json'));
    }

    public function seriesWithTitleExists(string $title): bool
    {
        return $this -> findOne(['title' => $title]) !== NULL;
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