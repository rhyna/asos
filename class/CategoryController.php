<?php


class CategoryController
{
    /**
     * @param Category $category
     * @param PDO $conn
     * @param string $actionType
     * @return bool
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

        $categoryDone = false;

        if ($actionType === 'create') {
            $categoryDone = $category->createCategory($conn);
        }

        if ($actionType === 'update') {
            $categoryDone = $category->updateCategory($conn);
        }

        if (!$categoryDone) {
            return false;
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