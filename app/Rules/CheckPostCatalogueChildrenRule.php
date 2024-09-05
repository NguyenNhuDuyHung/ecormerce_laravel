<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\PostCatalogue;

class CheckPostCatalogueChildrenRule implements ValidationRule
{

    protected $postCatalogueId;

    public function __construct($postCatalogueId)
    {
        $this->postCatalogueId = $postCatalogueId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $flag = PostCatalogue::isNodeCheck($this->postCatalogueId);

        if ($flag == false) {
            $fail('Không thể xóa do còn danh mục con!');
        }

    }
}
