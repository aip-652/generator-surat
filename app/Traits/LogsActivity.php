<?php

namespace App\Traits;

use App\Models\AdminLog;
use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
  protected static function bootLogsActivity()
  {

    // Event ini berjalan SETELAH data berhasil dibuat
    static::created(function ($model) {
      // Hanya catat log jika user sedang login (untuk menghindari log saat seeding)
      if (Auth::check()) {
        static::recordActivity($model, 'created');
      }
    });

    // Event ini berjalan SETELAH data berhasil diupdate
    static::updated(function ($model) {
      static::recordActivity($model, 'updated');
    });

    // Event ini berjalan SEBELUM data akan dihapus
    static::deleting(function ($model) {
      static::recordActivity($model, 'deleted');
    });
  }

  protected static function recordActivity($model, $action)
  {
    // Pastikan ada user yang login
    if (!Auth::check()) {
      return;
    }

    $details = '';
    $modelName = class_basename($model); // Mendapat nama model, misal: "User" atau "Dokumen"

    if ($action === 'created') {
      $identifier = $model->name ?? $model->perihal ?? $model->nomor_dokumen;
      $details = "{$modelName} baru '{$identifier}' telah dibuat.";
    } elseif ($action === 'deleted') {
      $identifier = $model->name ?? $model->nomor_dokumen ?? $model->perihal;
      $details = "{$modelName} {$identifier} dihapus.";
    } elseif ($action === 'updated') {
      $changes = [];
      foreach ($model->getChanges() as $key => $value) {
        if ($key === 'updated_at') continue;
        $original = $model->getOriginal($key);
        $changes[] = "'{$key}' sebelumnya '{$original}' diubah menjadi '{$value}'";
      }

      if (empty($changes)) {
        return; // Jangan buat log jika tidak ada yang berubah
      }

      $identifier = $model->name ?? $model->nomor_dokumen ?? $model->perihal;
      if ($modelName === 'User') {
        $details = "{$modelName} '{$identifier}' diperbarui: " . implode(', ', $changes);
      } else {
        $details = "{$modelName} {$identifier} diperbarui: " . implode(', ', $changes);
      }
    }

    AdminLog::create([
      'user_id'       => Auth::id(),
      'action'        => $action,
      'loggable_id'   => $model->id,
      'loggable_type' => get_class($model),
      'details'       => $details,
    ]);
  }
}
