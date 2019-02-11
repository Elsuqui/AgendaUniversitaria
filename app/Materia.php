<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Materia
 *
 * @property int $id
 * @property string $nombre
 * @property string $estado
 * @property int $id_usuario_creacion
 * @property int|null $id_usuario_edicion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MateriaDocente[] $materias_docente
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia whereIdUsuarioCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia whereIdUsuarioEdicion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Materia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Materia extends Model
{
    //Modelo de la tabla Materia
    protected $table = "materias";

    protected $fillable = [
        "nombre",
        "estado",
        "id_usuario_creacion"
    ];

    /**
     * @return HasMany
     */
    public function materias_docente() : HasMany
    {
        return $this->hasMany(MateriaDocente::class, "id_materia");
    }
}
