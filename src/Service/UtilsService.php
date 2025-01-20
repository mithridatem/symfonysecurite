<?php

namespace App\Service;

class UtilsService
{
    
    public function sanitize(string $chaine)
    {
        $chaine = trim($chaine);
        $chaine = strip_tags($chaine);
        $chaine = htmlspecialchars($chaine, ENT_NOQUOTES);
        return $chaine;
    }

    public function sanitizeArray(array $data): array
    {
        $cleanData = [];
        foreach ($data as $key => $value) {
            $cleanData[$key] = $this->sanitize($value);
        }
        return $cleanData;
    }
}
