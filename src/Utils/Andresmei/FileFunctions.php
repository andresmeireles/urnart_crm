<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

use Respect\Validation\Exceptions\TypeException;

class FileFunctions
{
    /**
     * @param   string  $path  Path of folder.
     * @return  array
     */
    public function getFilesFromFolder(string $path): array
    {
        $files = glob($path . '/*');
        // busca o ultimo arquivo modificado.
        $files = array_combine(
            $files,
            array_map('filectime', $files)
        ); //filectime conta o numero de segundos desde a ultima data de modificação.
        if (! is_array($files)) {
            throw new TypeException('O parametro não é do tipo permitido.');
        }

        return $files;
    }

    /**
     * @param string $path Path of folder.
     * @return string
     */
    public function getLastCreateFileFromFolder(string $path): string
    {
        $files = $this->getFilesFromFolder($path);

        arsort($files); // reorganiza valores de array a partir de seus valores de forma decrescente.

        return (string) key($files); //retorna primeira change de array.
    }

    /**
     * @param string $path
     * @param string $date
     * @return string|null
     */
    public function getFileByDate(string $path, string $date): ?string
    {
        $fileFullPath = sprintf('%s/%s.yaml', $path, $date);

        if (! file_exists($fileFullPath)) {
            return $this->getAproximatementLastEditedFile($path, $date);
        }

        return $fileFullPath;
    }

    /**
     * @param string $path
     * @param string $date
     * @return string|null
     */
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
