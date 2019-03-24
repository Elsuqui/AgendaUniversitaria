<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * App\Evento
 *
 * @property int $id
 * @property int $id_materia_docente
 * @property string $titulo
 * @property string|null $descripcion
 * @property string $aula
 * @property string $fecha
 * @property string $hora
 * @property string $estado
 * @property int $importancia
 * @property int $id_usuario_creacion
 * @property int|null $id_usuario_edicion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\MateriaDocente $materia_docente
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereAula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereIdMateriaDocente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereIdUsuarioCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereIdUsuarioEdicion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereImportancia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $hora_fin
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereHoraFin($value)
 * @property string|null $estado_control
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Evento whereEstadoControl($value)
 */
class Evento extends Model
{
    //Modelo de la Tabla de eventos
    protected $table = "eventos";

    protected $fillable = [
        "id_materia_docente",
        "titulo",
        "descripcion",
        "aula",
        "fecha",
        "hora",
        "hora_fin",
        "estado",
        "importancia",
        "id_usuario_creacion"
    ];

    protected $dates = [
        "fecha"
    ];

    protected $casts = [
        'fecha' => 'date:Y-m-d'
    ];

    /**
     * @return BelongsTo
     */
    public function materia_docente() : BelongsTo
    {
        return $this->belongsTo(MateriaDocente::class, "id_materia_docente");
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
