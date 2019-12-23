<?php
if (! function_exists('categoryThreeToArray')) {
    /**
     * nested category three array to standart array
     *
     * @param mixed $array
     * @param int $parent
     * @param array $empty
     * @return array
     */
    function categoryThreeToArray($array, $parent = null, &$empty = [])
    {
        foreach ($array as $key => $row) {
            
            $empty[] = [
                "id" => $row->id,
                "category_id" => $parent
            ];

            if (count($row->children) > 0)
                categoryThreeToArray($row->children, $row->id, $empty);
        }

        return $empty;
    }
}
