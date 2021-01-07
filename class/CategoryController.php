<?php


class CategoryController
{
    /**
     * @param Category $category
     * @param PDO $conn
     * @param string $actionType
     * @return bool
     * @throws SystemErrorException
     */
    static public function onPostCategoryAction(Category $category, PDO $conn, string $actionType): bool
    {
        $category->fillCategoryObject($_POST);

        $category->validateCategory();

        if ($_FILES['image']['name']) {

            $category->validateCategoryImage($_FILES['image']);
        }

        if ($category->validationErrors || $category->imageValidationErrors) {
            return false;
        }

        if ($actionType === 'create') {
            $category->createCategory($conn);
        }

        if ($actionType === 'update') {
            $category->updateCategory($conn);
        }

        if (!$_FILES['image']['name']) {
            return true;
        }

        if (!$category->updateCategoryImage($conn, $_FILES['image'])) {
            return false;
        }

        return true;
    }
}