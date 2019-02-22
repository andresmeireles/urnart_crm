<?php

namespace App\Utils\Andresmei;

use Respect\Validation\Exceptions\TypeException;

class FileFunctions
{
    /**
     * Get last modified file of a folder.
     *
     * @param   string  $path  Path of folder. 
     *
     * @return  string         Return the name of the last modified file.
     */
    public function getLastCreateFileFromFolder(string $path): string
    {
        $files = glob($path.'/*');
        // busca o ultimo arquivo modificado.
        $files = array_combine($files, array_map('filectime', $files)); //filectime conta o numero de segundos desde a ultima data de modificação.

        if (!is_array($files)) {
            throw new TypeException('O parametro não é do tipo permitido.');
        }

        arsort($files); // reorganiza valores de array a partir de seus valores de forma decrescente.
        
        return (string) key($files); //retorna primeira change de array.
    }
}
