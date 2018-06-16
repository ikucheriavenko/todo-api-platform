<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 13.06.18
 * Time: 23:15.
 */

namespace App\Dto\User;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *             "method"="POST",
 *             "path"="/auth/register",
 *             "swagger_context"={
 *                 "tags"={"Auth"},
 *                 "summary"="Register a new User",
 *                 "responses"={
 *                     "201"={"description"="The user has been registered"},
 *                     "400"={"description"="A user with such username already exists"},
 *                 }
 *             }
 *         },
 *     },
 *     itemOperations={
 *          "login"={
 *             "method"="POST",
 *             "route_name"="api_login",
 *             "swagger_context"={
 *                 "tags"={"Auth"},
 *                 "summary"="Login User",
 *                 "parameters"={
 *                     {
 *                         "name"="body",
 *                         "in"="body",
 *                         "schema"={
 *                             "type"="object",
 *                             "properties"={
 *                                 "username"={"type"="string", "example"="example@example.com"},
 *                                 "password"={"type"="string", "example"="strong-password"}
 *                             }
 *                         }
 *                     }
 *                 },
 *                 "responses"={
 *                     "401"={"description"="Bad Credentials"},
 *                     "200"={"description"="Login Success", "schema"={"type"="object", "properties"={"token"={"type"="string"}}}}
 *                 }
 *             }
 *         }
 *     },
 * )
 */
class AuthUser
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="example@example.com"
     *         }
     *     }
     * )
     */
    protected $username;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min="6")
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="strong-password"
     *         }
     *     }
     * )
     */
    protected $password;

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return AuthUser
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return AuthUser
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
