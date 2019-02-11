<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\MateriaDocente
 *
 * @property int $id
 * @property int $id_materia
 * @property int $id_usuario
 * @property string $estado
 * @property int $id_usuario_creacion
 * @property int|null $id_usuario_edicion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Evento[] $eventos
 * @property-read \App\Materia $materia
 * @property-read \App\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente whereIdMateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente whereIdUsuarioCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente whereIdUsuarioEdicion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MateriaDocente whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MateriaDocente extends Model
{
    //Modelo de la tabla de materia docente
    protected $table = "materias_docente";

    protected $fillable = [
      "id_materia",
      "id_usuario",
      "estado",
      "id_usuario_creacion"
    ];

    /**
     * @return BelongsTo
     */
    public function materia() : BelongsTo
    {
        return $this->belongsTo(Materia::class, "id_materia");
    }

    /**
     * @return BelongsTo
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, "id_usuario");
    }

    /**
     * @return HasMany
     */
    public function eventos() : HasMany
    {
        return $this->hasMany(Evento::class, "id_materia_docente");
    }
}
