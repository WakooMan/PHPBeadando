<?php
function validate($post,&$data,&$errors,$episodes,$exception=NULL): bool
{
    if(!isset($post['title']) || trim($post['title']) === '')
    {
        $errors['title'] = 'Az epizód címét kötelező megadni!';
    }
    else
    {
        $episode = NULL;
        foreach($episodes as $ep)
        {
            if($ep['title'] === $post['title'] && ($exception !== NULL && $exception['title']!==$post['title']))
            {
                $episode = $ep;
            }
        }
        if($episode !== NULL)
        {
            $errors['title'] = 'Már létezik ilyen címmel rendelkező epizód!';
        }
        else
        {
            $data['title'] = $post['title'];
        }
    }

    if(!isset($post['date']) || trim($post['date']) === '')
    {
        $errors['date'] = 'A megjelenés dátumát kötelező megadni!';
    }
    else
    {
        $d = DateTime::createFromFormat('Y.m.d.', $post['date']);
        if(!$d || $d->format('Y.m.d.') !== $post['date'])
        {
            $errors['date'] = 'A dátumnak ilyen formájunak kell lennie: YYYY.MM.DD.';
        }
        else
        {
            $data['date'] = $post['date'];
        }
    }

    if(!isset($post['plot']) || trim($post['plot']) === '')
    {
        $errors['plot'] = 'Az epizód leírását kötelező megadni!';
    }
    else
    {
        $data['plot'] = $post['plot'];
    }

    if(!isset($post['rating']) || trim($post['rating']) === '')
    {
        $errors['rating'] = 'Az értékelést kötelező megadni!';
    }
    else
    {
        if(!filter_var($post['rating'],FILTER_VALIDATE_FLOAT))
        {
            $errors['rating'] = 'Az értékelésnek lebegőpontos számnak kell lennie!';
        }
        elseif(10 < (float)$post['rating'] || 0 > (float)$post['rating'])
        {
            $errors['rating'] = 'Az értékelésnek 0 és 10 közötti valós számnak kell lennie!';
        }
        else
        {
            $data['rating'] = $post['rating'];
        }
    }
    return count($errors) === 0;
}
?>