<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetencesRepository::class)
 * @ApiResource(
 *     routePrefix="/admin",
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message" = "Vous n'avez pas les autorisations requises !"
 *     },
 *     collectionOperations={
 *          "post"={
 *              "path"="/competences"
 *          },
 *          "get"={
 *              "path"="/competences"
 *          }
 *   },
 *   itemOperations={
 *          "get"={
 *              "path"="/competences/{id}",
 *              "requirements"={"id"="\d+"}
 *          },
 *          "put"={
 *              "path"="/competences/{id}",
 *              "requirements"={"id"="\d+"}
 *          },
 *          "delete"={
 *              "path"="/competences/{id}",
 *              "requirements"={"id"="\d+"}
 *          }
 *    },
 *   normalizationContext={
 *          "groups"={
 *              "competences_read",
 *          }
 *     },
 *     denormalizationContext={
 *          "groups"={
 *             "competences_write"
 *          }
 *     }
 * )
 */
class Competences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grpecompetences_read","grpecompetences_write","competences_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetences_read","grpecompetences_write","competences_write","competences_read"})
     * @Assert\NotBlank(message="Le libelle est obligatoire")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetences_read","grpecompetences_write","competences_write","competences_read"})
     * @Assert\NotBlank(message="Le libelle est obligatoire")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"grpecompetences_read","grpecompetences_write","competences_write","competences_read"})
     */
    private $archivage;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, mappedBy="competences")
     * @Groups({"competences_write","competences_read"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Niveaux::class, mappedBy="competences")
     * @Groups({"grpecompetences_read","grpecompetences_write","competences_read"})
     */
    private $niveaux;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->setArchivage(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if ($this->groupeCompetences->removeElement($groupeCompetence)) {
            $groupeCompetence->removeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|Niveaux[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveaux $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetences($this);
        }

        return $this;
    }

    public function removeNiveau(Niveaux $niveau): self
    {
        if ($this->niveaux->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetences() === $this) {
                $niveau->setCompetences(null);
            }
        }

        return $this;
    }
}
