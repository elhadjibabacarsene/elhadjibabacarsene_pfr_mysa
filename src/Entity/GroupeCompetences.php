<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeCompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=GroupeCompetencesRepository::class)
 * @ApiResource(
 *     subresourceOperations={
 *          "api_groupe_competences_competences_get_subresource"={
 *              "method"="GET",
 *              "path"="/grpecompetences/{id}/competences"
 *          }
 *     },
 *     routePrefix="/admin",
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message" = "Vous n'avez pas les autorisations requises !"
 *     },
 *     collectionOperations={
 *          "post"={
 *              "path"="/grpecompetences"
 *          },
 *          "get"={
 *              "path"="/grpecompetences"
 *          }
 *   },
 *   itemOperations={
 *          "get"={
 *              "path"="/grpecompetences/{id}",
 *              "requirements"={"id"="\d+"}
 *          },
 *          "put"={
 *              "path"="/grpecompetences/{id}",
 *              "requirements"={"id"="\d+"}
 *          },
 *          "delete"={
 *              "path"="/grpecompetences/{id}",
 *              "requirements"={"id"="\d+"}
 *          }
 *    },
 *   normalizationContext={
 *          "groups"={
 *              "grpecompetences_read",
 *          }
 *     },
 *     denormalizationContext={
 *          "groups"={
 *             "grpecompetences_write"
 *          }
 *     }
 * )
 */
class GroupeCompetences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grpecompetences_read","grpecompetences_write","competences_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetences_read","grpecompetences_write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetences_read","grpecompetences_write"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"grpecompetences_read","grpecompetences_write"})
     */
    private $archivage;

    /**
     * @ORM\ManyToMany(targetEntity=Competences::class, inversedBy="groupeCompetences",cascade={"persist"})
     * @Groups({"grpecompetences_read","grpecompetences_write"})
     * @ApiSubresource()
     */
    private $competences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
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
     * @return Collection|Competences[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        $this->competences->removeElement($competence);

        return $this;
    }
}
