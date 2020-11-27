<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "administrateur" = "Administrateurs", "apprenant" = "Apprenants", "formateur" = "Formateurs",
 *     "communityManager" = "CommunityManager"})
 * @UniqueEntity ("email",message="Cet email existe déjà")
 * @UniqueEntity ("telephone",message="Ce numéro de téléphone existe déjà")
 * @ApiResource (
 *     routePrefix="/admin",
 *     normalizationContext={"groups"={"user:read"}},
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez les autorisations requises",
 *          "pagination_enabled"=true, "pagination_items_per_page"=25
 *      },
 *     itemOperations={
 *          "get",
 *          "update_user"={
 *              "method"="put",
 *              "path"="/users/{idUser}",
 *              "route_name" = "update_user",
 *              "deserialize"=false
 *          }
 *     },
 *     collectionOperations={
 *          "add_user"={
 *              "method"="post",
 *              "path"="/users",
 *              "route_name" = "add_user",
 *              "deserialize"=false
 *          },
 *          "get"
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archivage"})
 */
class User implements UserInterface
{

    public function __construct(){
        $this->archivage = false;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @Assert\Email(message="L'email est incorrect")
     * @Groups({"user:read"})
     */
    private $email;

    /**
     * Tableau des roles
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Groups({"user:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     * @Groups({"user:read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank(message="Vous devez préciser le genre")
     * @Groups({"user:read"})
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     * @Groups({"user:read"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @Groups({"user:read"})
     */
    private $profil;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"user:read"})
     */
    private $photo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.strtoupper($this->profil->getLibelle());

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getGenre(): ?bool
    {
        return $this->genre;
    }

    public function setGenre(bool $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getPhoto()
    {
        return base64_encode(stream_get_contents($this->photo));
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
