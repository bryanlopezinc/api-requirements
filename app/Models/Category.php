<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 */
class Category extends Model
{
    /** @inheritdoc */
    protected $table = 'categories';

    /** @inheritdoc */
    public $timestamps = false;
}
