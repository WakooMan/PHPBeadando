<?php
function validate($post,&$data,&$errors,$series,$exception=NULL) : bool
{
    if(!isset($post['title']) || trim($post['title']) === '')
    {
        $errors['title'] = 'A sorozat címét kötelező megadni!';
    }
    else
    {
        if($series -> seriesWithTitleExists($post['title']) && ($exception !== NULL && $exception['title']!==$post['title']))
        {
            $errors['title'] = 'Már létezik ilyen címmel rendelkező sorozat!';
        }
        else
        {
            $data['title'] = $post['title'];
        }
    }

    if(!isset($post['year']) || trim($post['year']) === '')
    {
        $errors['year'] = 'A megjelenés évét kötelező megadni!';
    }
    else
    {
        if(!filter_var($post['year'],FILTER_VALIDATE_INT))
        {
            $errors['year'] = 'A megjelenés évének egész számnak kell lennie!';
        }
        else
        {
            $data['year'] = $post['year'];
        }
    }

    if(!isset($post['plot']) || trim($post['plot']) === '')
    {
        $errors['plot'] = 'A sorozat leírását kötelező megadni!';
    }
    else
    {
        $data['plot'] = $post['plot'];
    }

    if(!isset($post['cover']) || trim($post['cover']) === '')
    {
        $errors['cover'] = 'A sorozat borítóját kötelező megadni!';
    }
    else
    {
        $data['cover'] = $post['cover'];
    }
    return count($errors) === 0;
}
?>