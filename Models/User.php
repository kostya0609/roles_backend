<?php
namespace App\Modules\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
	protected $table = 'b_user';
	protected $primaryKey = 'ID';

	protected $visible = [
        'id',
        'full_name',
		'abbreviated_name',
		'photo',
		'link',
        'work'
	];

	protected $appends = ['id', 'full_name', 'abbreviated_name','photo', 'link','work'];

	public function getFullNameAttribute(): string
	{
		return trim($this->LAST_NAME . ' ' . $this->NAME . ' ' . $this->SECOND_NAME);
	}

	public function getAbbreviatedNameAttribute(): string
	{
		return trim($this->LAST_NAME . ' ' . mb_substr($this->NAME, 0, 1) . '. ' . mb_substr($this->SECOND_NAME, 0, 1) . '.');
	}

	public function getPhotoAttribute()
	{
		$file = DB::table('b_file')->where('ID', $this->PERSONAL_PHOTO)->first();

		if ($file) {
			return 'https://bitrix.bsi.local/upload/' . $file->SUBDIR . '/' . $file->FILE_NAME;
		}

		return null;
	}

	public function getIdAttribute()
	{
		return $this->attributes['ID'];
	}

    public function getWorkAttribute()
	{
		return $this->attributes['WORK_POSITION'];
	}

	public function getLinkAttribute()
	{
		return "https://bitrix.bsi.local/company/personal/user/{$this->attributes['ID']}/";
	}
}
