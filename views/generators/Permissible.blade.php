<?php echo '<?php' ?>

namespace {{ $namespace }};

use IsakzhanovR\Permissions\Models\Permissible as LaravelPermissible;

final class Permissible extends LaravelPermissible
{
    protected $fillable = [
        'permission_id',
        'permissible_type',
        'permissible_id',
    ];
}
