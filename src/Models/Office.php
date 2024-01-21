<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Office.
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property mixed $zip_code
 * @property string $email
 * @property string $phone
 * @property string $country
 * @property Company $company
 * @method static find($id)
 */
class Office extends Model
{
    protected $table = 'offices';

    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function isHeadOffice(): bool
    {
        return $this->company->headOffice->id === $this->id;
    }

    public function getFullAddressAttribute() : string
    {
        return "{$this->address}, {$this->city}, {$this->zip_code}, {$this->country}";
    }
}
