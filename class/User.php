<?php


class User
{
    /** @var int $id */
    public $id;

    /** @var string $username */
    public $username;

    /** @var string $password */
    public $password;

    /**
     * @param PDO $conn
     * @param string $username
     * @param string $password
     * @return bool
     * @throws SystemErrorException
     */
    static public function authenticate(PDO $conn, string $username, string $password): bool
    {
        try {
            $sql = "select * from user where username = :username";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':username', $username, PDO::PARAM_STR);

            $statement->execute();

            /** @var User $user */
            $user = $statement->fetchObject(User::class);

            if ($user) {
                return md5($password) === $user->password;
            } else {
                return false;
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }
}