<?php

namespace App\Utils\Andresmei;

use Respect\Validation\Exceptions\TypeException;

class FileFunctions
{
    /**
     * Return files and this last modified time.
     *
     * @param   string  $path  Path of folder.
     *
     * @return  array          List with the files of folder and respective last modified date.
     */
    public function getFilesFromFolder(string $path): array
    {
        $files = glob($path.'/*');
        // busca o ultimo arquivo modificado.
        $files = array_combine($files, array_map('filectime', $files)); //filectime conta o numero de segundos desde a ultima data de modificação.

        if (!is_array($files)) {
            throw new TypeException('O parametro não é do tipo permitido.');
        }

        return $files;
    }

    /**
     * Get last modified file of a folder.
     *
     * @param string $path Path of folder.
     *
     * @return string Return the name of the last modified file.
     */
    public function getLastCreateFileFromFolder(string $path): string
    {

        $files = $this->getFilesFromFolder($path);

        arsort($files); // reorganiza valores de array a partir de seus valores de forma decrescente.
        
        return (string) key($files); //retorna primeira change de array.
    }

    /**
     * Retorna caminho do arquivo descrito por data ou arquivo de valor aproximado. Funcção valida apenas para arquivo
     * com nomes com datas eg: 28-02-2019.
     *
     * @param   string  $path  Caminho do arquivo.
     * @param   string  $date  Data do nome do aquivo.
     *
     * @return  string         Caminho do arquivo
     */
    public function getFileByDate(string $path, string $date): ?string
    {
        $fileFullPath = sprintf('%s/%s.yaml', $path, $date);

        if (!file_exists($fileFullPath)) {
            return $this->getAproximatementLastEditedFile($path, $date);
        }

        return $fileFullPath;
    }


    public function getAproximatementLastEditedFile(string $path, string $date): ?string
    {
        $files = $this->getFilesFromFolder($path);
        arsort($files); // put array in highest value for the lowest.
        $timestampDate = strtotime(sprintf('%s 23:59:59', $date));

        foreach ($files as $key => $value) {
            if ($timestampDate > $value) {
                return $key;
            }
        }

        return null;
    }
}
