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

    static public function getAllProducts(PDO $conn): array
    {
        $sql = "select p.*,
            b.title as brand_title,
            c.title as category_title
        from product p
            left join brand b on b.id = p.brand_id
            left join category c on c.id = p.category_id";

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_CLASS, Product::class);
    }

    public function validateProduct($conn)
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

        return $this->productErrors ? false : true;
    }

    public function checkImageValidation($data)
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

    public function createProduct($conn, $data): bool
    {
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

            if ($statement->execute()) {
                $this->id = $conn->lastInsertId();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function fillProductStatement($statement)
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

    public function fillProductObject($data)
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
    }

    public function validateProductImage($image)
    {
        $extensions = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];

        try {
            $errorMessages = [];

            if ($image['size'] > 1000000) {
                $errorMessages[] = 'A file size can be 1 Mb max';
            }

            if (!in_array($image['type'], $extensions)) {
                $errorMessages[] = 'The file is not an image. Eligible extensions: png, jpeg, jpg, gif';
            }

            if ($errorMessages) {
                $errorMessage = implode('<br>', $errorMessages);
                throw new Exception($errorMessage);
            }

        } catch (Exception $e) {
            $imageErrors = $e->getMessage();
            $imageErrors = explode('<br>', $imageErrors);
            $this->imageErrors[$image['name']] = $imageErrors;
        }

        return $this->imageErrors ? false : true;
    }

    public function setProductImage($conn, $currentImage, $imagePath)
    {
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

    }

    public function updateProductImage($conn, $data): bool
    {
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
                unlink($ROOT . $previousImage);
            }
        }

        return true;
    }

    public function checkProductCodeDupes(PDO $conn): bool
    {
        $sql = "select id from product where product_code = $this->product_code";

        $result = $conn->query($sql);

        return (bool)$result->fetch();
    }

    static public function getProduct(PDO $conn, $id): ?Product
    {
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
    }

    public function updateProduct($conn, $data): bool
    {
        $isImageValidated = $this->checkImageValidation($data);

        if ($this->validateProduct($conn) && $isImageValidated) {
            $sql = "update  product 
            set     category_id = :categoryId,
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
    }

    public function getImagesArray()
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

    public function deleteProductImage(PDO $conn, $image)
    {
        global $ROOT;

        $sql = "update product set $image = null where id = :id";

        $statement = $conn->prepare($sql);

        $statement->bindValue(':id', $this->id, PDO::PARAM_INT);


        $imagePath = $this->$image; // $image - variable variable

        if ($statement->execute()) {
            unlink($ROOT . $imagePath);

            return true;
        } else {
            return false;
        }

    }

    public function deleteProduct($conn): bool
    {
        $sql = "delete from product where id = :id";

        $statement = $conn->prepare($sql);

        $statement->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $statement->execute();
    }
}