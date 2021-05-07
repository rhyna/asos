<?php


class Product
{
    public $id;
    public $category_id;
    public $brand_id;
    public $product_code;
    public $price;
    public $title;
    public $product_details;
    public $look_after_me;
    public $about_me;
    public $image;
    public $image_1;
    public $image_2;
    public $image_3;
    public $productErrors = [];
    public $imageErrors = [];
    public $sizes = [];

    /**
     * @param PDO $conn
     * @return bool
     * @throws SystemErrorException
     */
    public function validateProduct(PDO $conn): bool
    {

        if (!$this->title) {
            $this->productErrors[] = 'Enter a title';
        }

        if (!$this->product_code) {
            $this->productErrors[] = 'Enter a product code';
        }

        if (!$this->price) {
            $this->productErrors[] = 'Enter a price';
        }

        if (!$this->category_id) {
            $this->productErrors[] = 'Select a category';
        }

        if ($this->product_code && !$this->id) {
            if ($this->checkProductCodeDupes($conn)) {
                $this->productErrors[] = 'An item with the entered product code already exists';
            }
        }

        if (!$this->sizes) {
            $this->productErrors[] = 'Please select at least one size';
        }

        return $this->productErrors ? false : true;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function checkImageValidation(array $data): bool
    {
        $imagesArray = [
            'image' => $data['image'],
            'image_1' => $data['image1'],
            'image_2' => $data['image2'],
            'image_3' => $data['image3'],
        ];

        $isImageValidated = true;

        foreach ($imagesArray as $imageKey => $image) {
            if ($image['name'] == '') {
                continue;
            }

            if (!$this->validateProductImage($image)) {
                $isImageValidated = false;
            }
        }

        return $isImageValidated;
    }

    /**
     * @param PDO $conn
     * @param array $data
     * @return bool
     * @throws SystemErrorException
     */
    public function createProduct(PDO $conn, array $data): bool
    {
        try {
            $isImageValidated = $this->checkImageValidation($data);

            if ($this->validateProduct($conn) && $isImageValidated) {
                $sql = "insert into product 
                        (category_id, 
                        brand_id,
                        product_code,
                        price,
                        title,
                        product_details,
                        look_after_me,
                        about_me)
                        values (:categoryId,
                        :brandId,
                        :productCode,
                        :price,
                        :title,
                        :productDetails,
                        :lookAfterMe,
                        :aboutMe)";

                $statement = $conn->prepare($sql);

                $this->fillProductStatement($statement);

                $statement->execute();

                $this->id = $conn->lastInsertId();

                return true;

            } else {
                return false;
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDOStatement $statement
     */
    private function fillProductStatement(PDOStatement $statement): void
    {
        $statement->bindValue(':categoryId', $this->category_id, PDO::PARAM_STR);

        if (!$this->brand_id) {
            $statement->bindValue(':brandId', $this->brand_id, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':brandId', $this->brand_id, PDO::PARAM_STR);
        }

        $statement->bindValue(':productCode', $this->product_code, PDO::PARAM_STR);

        $statement->bindValue(':price', $this->price, PDO::PARAM_STR);

        $statement->bindValue(':title', $this->title, PDO::PARAM_STR);

        if (!$this->product_details) {
            $statement->bindValue(':productDetails', $this->product_details, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':productDetails', $this->product_details, PDO::PARAM_STR);
        }

        if (!$this->look_after_me) {
            $statement->bindValue(':lookAfterMe', $this->look_after_me, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':lookAfterMe', $this->look_after_me, PDO::PARAM_STR);
        }

        if (!$this->about_me) {
            $statement->bindValue(':aboutMe', $this->about_me, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':aboutMe', $this->about_me, PDO::PARAM_STR);
        }
    }

    /**
     * @param array $data
     */
    public function fillProductObject(array $data): void
    {
        if (!isset($data['categoryId'])) {
            $this->category_id = '';
        } else {
            $this->category_id = $data['categoryId'];
        }

        $this->brand_id = $data['brandId'];

        $this->product_code = $data['productCode'];

        $this->price = $data['price'];

        $this->title = $data['title'];

        $this->product_details = $data['productDetails'];

        $this->look_after_me = $data['lookAfterMe'];

        $this->about_me = $data['aboutMe'];

        if (isset($data['sizes'])) {
            $this->sizes = $data['sizes'];
        }
    }

    /**
     * @param array $image
     * @return bool
     */
    private function validateProductImage(array $image): bool
    {
        $extensions = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];

        $errorMessages = [];

        if ($image['size'] > 1000000) {
            $errorMessages[] = 'A file size can be 1 Mb max';
        }

        if (!in_array($image['type'], $extensions)) {
            $errorMessages[] = 'The file is not an image. Eligible extensions: png, jpeg, jpg, gif';
        }

        if ($errorMessages) {
            $this->imageErrors[$image['name']] = $errorMessages;
        }

        return $this->imageErrors ? false : true;
    }

    /**
     * @param PDO $conn
     * @param string $currentImage
     * @param string $imagePath
     * @return bool
     * @throws SystemErrorException
     */
    private function setProductImage(PDO $conn, string $currentImage, string $imagePath): bool
    {
        try {
            $imageColNames = [
                'image',
                'image_1',
                'image_2',
                'image_3',
            ];

            foreach ($imageColNames as $imageColName) {
                if ($imageColName === $currentImage) {
                    $sql = "UPDATE product SET {$imageColName} = :image WHERE id = :id";

                    $statement = $conn->prepare($sql);

                    $statement->bindValue(':id', $this->id, PDO::PARAM_INT);

                    $statement->bindValue(':image', $imagePath, $imagePath === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

                    return $statement->execute();
                }
            }

            return false;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param array $data
     * @return bool
     * @throws SystemErrorException
     */
    public function updateProductImage(PDO $conn, array $data): bool
    {
        try {
            if ($this->imageErrors || $this->productErrors) {
                return false;
            }

            global $ROOT;

            $imagesArray = [
                'image' => $data['image'],
                'image_1' => $data['image1'],
                'image_2' => $data['image2'],
                'image_3' => $data['image3'],
            ];

            foreach ($imagesArray as $imageKey => $image) {
                if ($image['name'] == '') {
                    continue;
                }

                $pathInfo = pathinfo($image['name']);

                $base = $pathInfo['filename'];

                $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base); // all except a-z, A-Z, 0-9, _, -

                $fileName = $base . '.' . $pathInfo['extension'];

                $imageUploadDestination = '/upload/product/' . $fileName;

                for ($i = 1; file_exists($ROOT . $imageUploadDestination); $i++) {
                    $imageUploadDestination = '/upload/product/' . $base . '-' . $i . '.' . $pathInfo['extension'];
                }

                $isFileMoved = move_uploaded_file($image['tmp_name'], $ROOT . $imageUploadDestination);

                if (!$isFileMoved) {
                    return false;
                }

                $previousImage = $this->$imageKey; // $imageKey - variable variable

                $this->$imageKey = $imageUploadDestination;

                if (!$this->setProductImage($conn, $imageKey, $imageUploadDestination)) {
                    return false;
                }

                if ($previousImage) {
                    unlink($ROOT . $previousImage . 111);
                }
            }

            return true;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @return bool
     * @throws SystemErrorException
     */
    private function checkProductCodeDupes(PDO $conn): bool
    {
        try {
            $sql = "select id from product where product_code = $this->product_code";

            $result = $conn->query($sql);

            return (bool)$result->fetch();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return Product|null
     * @throws SystemErrorException
     */
    public static function getProduct(PDO $conn, int $id): ?Product
    {
        try {
            $sql = "select p.id, 
                    p.title,
                    p.product_code,
                    p.product_details,
                    p.price,
                    p.category_id,
                    p.brand_id,
                    p.look_after_me,
                    p.about_me,
                    p.image,
                    p.image_1,
                    p.image_2,
                    p.image_3,
                    b.title as brand_title,
                    c.title as category_title
                    from product p
                    left join brand b on b.id = p.brand_id
                    left join category c on c.id = p.category_id
                    where p.id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchObject(Product::class) ?: null;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param array $data
     * @return bool
     * @throws SystemErrorException
     */
    public function updateProduct(PDO $conn, array $data): bool
    {
        try {
            $isImageValidated = $this->checkImageValidation($data);

            if ($this->validateProduct($conn) && $isImageValidated) {
                $sql = "update product 
                        set category_id = :categoryId,
                        brand_id = :brandId,
                        product_code = :productCode,
                        price = :price,
                        title = :title,
                        product_details = :productDetails,
                        look_after_me = :lookAfterMe,
                        about_me = :aboutMe
                        where id = :id";

                $statement = $conn->prepare($sql);

                $statement->bindValue(':id', $this->id, PDO::PARAM_INT);

                $this->fillProductStatement($statement);

                return $statement->execute();
            } else {
                return false;
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @return array[]
     */
    public function getImagesArray(): array
    {
        return [
            'image' => [
                'image' => $this->image,
            ],
            'image1' => [
                'image_1' => $this->image_1,
            ],
            'image2' => [
                'image_2' => $this->image_2,
            ],
            'image3' => [
                'image_3' => $this->image_3,
            ],
        ];
    }

    /**
     * @param PDO $conn
     * @param string $image
     * @throws SystemErrorException
     */
    public function deleteProductImage(PDO $conn, string $image): void
    {
        try {
            global $ROOT;

            $sql = "update product set $image = null where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $this->id, PDO::PARAM_INT);

            $imagePath = $this->$image; // $image - variable variable

            $statement->execute();

            unlink($ROOT . $imagePath);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }

    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    public function deleteProduct(PDO $conn): void
    {
        try {
            $sql = "delete from product where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $this->id, PDO::PARAM_INT);

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @return bool
     * @throws SystemErrorException
     */
    public function updateProductSizes(PDO $conn): bool
    {
        $this->deleteProductSizes($conn);

        foreach ($this->sizes as $sizeId) {
            $sql = "insert into product_size 
                    (product_id, size_id) 
                    values (:productId, :sizeId)";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':productId', $this->id, PDO::PARAM_INT);

            $statement->bindValue(':sizeId', $sizeId, PDO::PARAM_INT);

            $statement->execute();
        }

        return true;
    }

    /**
     * @param PDO $conn
     * @return array
     * @throws SystemErrorException
     */
    public function getProductSizes(PDO $conn): array
    {
        try {
            $sql = "select ps.size_id, s.title as size_title
                    from product_size ps
                    join size s on ps.size_id = s.id
                    where ps.product_id = :productId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':productId', $this->id, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }

    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    public function deleteProductSizes(PDO $conn): void
    {
        try {
            $sql = "delete from product_size where product_id = :productId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':productId', $this->id, PDO::PARAM_INT);

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param array $productIds
     * @param int $categoryId
     * @return int
     * @throws SystemErrorException
     */
    public static function checkProductsInParentCategory(PDO $conn, array $productIds, int $categoryId): int
    {
        $idsString = implode(',', $productIds);

        try {
            $sql = "select count(*)
                    from product p 
                    join category c 
                    on p.category_id = c.id
                    where FIND_IN_SET(p.id, :productIds)
                    and c.parent_id = :categoryId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':productIds', $idsString, PDO::PARAM_STR);

            $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

            $statement->execute();

            return (int)$statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param array $categoryIds
     * @return array
     * @throws SystemErrorException
     */
    public static function getProductBrandsByCategories(PDO $conn, array $categoryIds): array
    {
        $brandIds = [];

        $result = [];

        foreach ($categoryIds as $categoryId) {
            try {
                $sql = "select p.brand_id, b.title, p.image, c.parent_id 
                        from product p
                        join brand b on b.id = p.brand_id
                        join category c on c.id = p.category_id
                        where p.category_id = :categoryId 
                        and p.brand_id is not null
                        and p.image is not null
                        and not FIND_IN_SET(p.brand_id, :brandIds) limit 0, 1
                        ";

                // limit 0, 1 - limits the result to 1 element starting from index 0
                // fetch automatically does the same (returns one element) (?)
                // the 'limit' clause left here for additional safety

                $statement = $conn->prepare($sql);

                $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

                $statement->bindValue(':brandIds', implode(',', $brandIds), PDO::PARAM_STR);

                $statement->execute();

                $fetchedResult = $statement->fetch(PDO::FETCH_ASSOC);

                if (!$fetchedResult) {
                    $result[] = [
                        'brand_id' => '',
                    ];

                } else {
                    $result[] = $fetchedResult;
                }

                foreach ($result as $item) {
                    $brandIds[] = $item['brand_id'];
                }

            } catch (Throwable $e) {
                throw new SystemErrorException();
            }
        }

        return $result;
    }

    /**
     * @param PDO $conn
     * @param array $parameters
     * @param string $newSQL
     * @return PDOStatement
     */
    private static function prepareFilterStatement(PDO $conn, array $parameters, string $newSQL): PDOStatement
    {
        $statement = $conn->prepare($newSQL);

        foreach ($parameters as $key => $value) {
            if ($key === 'categoryId') {
                $statement->bindValue(':categoryId', $value, PDO::PARAM_INT);
            }

            if ($key === 'brandIds') {
                $statement->bindValue(':brandIds', implode(',', $value), PDO::PARAM_STR);
            }

            if ($key === 'sizeIds') {
                $statement->bindValue(':sizeIds', implode(',', $value), PDO::PARAM_STR);
            }

            if ($key === 'brandId') {
                $statement->bindValue(':brandId', $value, PDO::PARAM_INT);
            }

            if ($key === 'categoryIdsByGender') {
                $statement->bindValue(':categoryIdsByGender', implode(',', $value), PDO::PARAM_STR);
            }

            if ($key === 'categoryIds') {
                $statement->bindValue(':categoryIds', implode(',', $value), PDO::PARAM_STR);
            }
        }

        return $statement;
    }

    /**
     * @param PDO $conn
     * @param array $parameters
     * @param string $join
     * @param string $where
     * @return int
     * @throws SystemErrorException
     */
    public static function countProductsFiltered(PDO $conn, array $parameters, string $join, string $where): int
    {
        try {
            $sql = "select count(distinct p.id)
                    from product p
                    %s
                    %s";

            $newSQL = sprintf($sql, $join, $where);

            $statement = self::prepareFilterStatement($conn, $parameters, $newSQL);

            $statement->execute();

            return $statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param string $select
     * @param array $parameters
     * @param string $join
     * @param string $where
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws SystemErrorException
     */
    public static function getPageOfProductsFiltered(PDO $conn, string $select, array $parameters, string $join, string $where, string $order, int $limit, int $offset): array
    {
        try {
            $sql = "select %s
                    from product p
                    %s
                    %s 
                    group by p.id 
                    %s 
                    limit :limit 
                    offset :offset";

            $newSQL = sprintf($sql, $select, $join, $where, $order);

            $statement = self::prepareFilterStatement($conn, $parameters, $newSQL);

            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);

            $statement->bindValue(':offset', $offset, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_CLASS, Product::class);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $categoryId
     * @return array
     * @throws SystemErrorException
     */
    public static function getBrandsByCategory(PDO $conn, int $categoryId): array
    {
        try {
            $sql = "select b.id as brandId, 
                    b.title as brandTitle
                    from product p
                    join brand b on b.id = p.brand_id
                    where p.category_id = :categoryId
                    order by b.title asc";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_CLASS, Product::class);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param array $categories
     * @return array
     * @throws SystemErrorException
     */
    public static function getAllBrandsByGender(PDO $conn, array $categories): array
    {
        $categoryIds = [];

        foreach ($categories as $categoryData) {
            $categoryIds[] = $categoryData['id'];
        }

        try {
            $sql = " select * from
                    (select b.id as id,
                    b.title as title,
                    c.id as categoryId,
                    c.parent_id as parentCategoryId
                    from product p
                    join brand b on b.id = p.brand_id
                    join category c on p.category_id = c.id
                    where FIND_IN_SET(c.id, :categoryIds)
                    group by b.id)
                    t order by t.title
                    ";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':categoryIds', implode(',', $categoryIds), PDO::PARAM_STR);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $brandId
     * @param array $categoryIdsByGender
     * @return array
     * @throws SystemErrorException
     */
    public static function getCategoriesByBrandAndGender(PDO $conn, int $brandId, array $categoryIdsByGender): array
    {
        try {
            $sql = "select c.id, 
                    c.title, c1.title as parentCategoryTitle
                    from product p
                    join category c on p.category_id = c.id
                    join category c1 on c1.id = c.parent_id
                    where p.brand_id = :brandId
                    and FIND_IN_SET(p.category_id, :categoryIdsByGender)
                    order by title asc";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':brandId', $brandId, PDO::PARAM_INT);

            $statement->bindValue(':categoryIdsByGender', implode(',', $categoryIdsByGender), PDO::PARAM_STR);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param array $wordIds
     * @return array
     * @throws SystemErrorException
     */
    public static function getProductsBySearchWords(PDO $conn, array $wordIds): array
    {

        try {
            $sql = "select p.* from product p";

            foreach ($wordIds as $i => $wordId) {
                $sql .= " join product_searchwords t$i on t$i.product_id = p.id and t$i.word_id = :wordId$i";
            }

            $statement = $conn->prepare($sql);

            foreach ($wordIds as $i => $wordId) {
                $statement->bindValue(":wordId$i", $wordId, PDO::PARAM_INT);
            }

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_CLASS, Product::class);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }
}