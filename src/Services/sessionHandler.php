<?php


class FileSessionHandler extends SessionHandler
{
    function destroy($id)
    {
        var_dump($this->read($id));

        parent::destroy($id);
        return true;
    }

}
