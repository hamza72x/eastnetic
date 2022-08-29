<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->importTestData();
  }

  private function importTestData()
  {
    $csvPath = storage_path('test-data.csv');
    $handle = fopen($csvPath, 'r');
    $header = null;
    $columns = 0;

    if ($handle) {
      while (($row = fgetcsv($handle, 1000, ',')) !== false) {

        if ($header) {

          if ($columns !== count($row)) {
            dump('not equal to header column: ' . $columns, '; row: ' . json_encode($row));
            continue;
          }

          $row = array_combine($header, $row);
          $birthData = Carbon::parse($row['birthday']);

          if (!$birthData) {
            dump('Failed to parse birth date: ' . $birthData);
            continue;
          }

          \App\Models\User::firstOrCreate(
            [
              'email' => $row['email_address'],
            ],
            [
              'name' => $row['name'],
              'birth_year' => $birthData->format('Y'),
              'birth_month' => $birthData->format('m'),
              'birthdate' => $row['birthday'],
              'phone' => $row['phone'],
              'ip' => $row['ip'],
              'country' => $row['country']
            ]
          );
        } else {
          $header = Arr::map($row, function ($value, $key) {
            return Str::slug($value, '_');
          });
          $columns = count($header);
        }
      }
      fclose($handle);
    } else {
      dump('Something went wrong');
    }
  }
}
