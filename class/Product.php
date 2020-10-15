<?php


class Product
{
    static public function getAllProducts($conn)
    {
        $sql = "select p.id,
            p.category_id,
            p.brand_id,
            p.product_code,
            p.price,
            p.title,
            p.image,
            p.is_for_men,
            p.is_for_women,
            b.title as brand_title,
            c.title as category_title
        from product p
            left join brand b on b.id = p.brand_id
            left join category c on c.id = p.category_id";

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}