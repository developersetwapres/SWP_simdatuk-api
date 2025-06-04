<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    protected static $methods = ['insertTs', 'insertGetIdTs', 'updateTs', 'deleteTs'];

    protected static function timestampValues($funcName, array $colNames)
    {
        Builder::macro($funcName, function (array $values, $withBy = false) use ($colNames) {
            $user_id = $withBy ? auth()->user()->id : null;

            if (array_key_exists(0, $values) && is_array($values[0])) {
                foreach ($values as &$value) {
                    $value[$colNames[1]] = date('Y-m-d h:i:s');

                    // Insert by
                    if ($withBy) {
                        $value[$colNames[2]] = $user_id;
                    }
                }
            } else {
                $values[$colNames[1]] = date('Y-m-d h:i:s');

                // Insert by
                if ($withBy) {
                    $values[$colNames[2]] = $user_id;
                }
            }

            return Builder::{$colNames[0]}($values);
        });
    }

    /**
     * Store data with id
     *
     * @return void
     */
    protected static function insertTs()
    {
        return self::timestampValues(__FUNCTION__, ['insert', 'created_at', 'user_id']);
    }

    /**
     * Store data with get id
     *
     * @return void
     */
    protected static function insertGetIdTs()
    {
        return self::timestampValues(__FUNCTION__, ['insertGetId', 'created_at', 'user_id']);
    }

    /**
     * Update data with current timestamp updated_at
     *
     * @return void
     */
    protected static function updateTs()
    {
        Builder::macro(__FUNCTION__, function (array $values, $withBy = false) {
            $values['updated_at'] = date('Y-m-d h:i:s');

            // Update by
            if ($withBy) {
                $values['updated_by'] = auth()->user()->id;
            }

            return Builder::update($values);
        });
    }

    /**
     * Update data with current timestamp deleted_at
     *
     * @return void
     */
    protected static function deleteTs()
    {
        Builder::macro(__FUNCTION__, function ($withBy = false) {
            $values = [
                'deleted_at' => date('Y-m-d h:i:s'),
            ];

            // Deleted by
            if ($withBy) {
                $values['deleted_by'] = auth()->user()->id;
            }

            return Builder::update($values);
        });
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach (self::$methods as $method) {
            self::{$method}();
        }
    }
}
