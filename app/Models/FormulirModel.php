<?php

namespace App\Models;
use CodeIgniter\Model;

class FormulirModel extends Model
{
    protected $table = 'formulir';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_template','link_google_form', 'config'];
}