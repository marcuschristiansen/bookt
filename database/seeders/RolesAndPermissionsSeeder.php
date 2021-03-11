<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // company permissions
        Permission::create(['name' => 'view teams']);
        Permission::create(['name' => 'edit teams']);
        Permission::create(['name' => 'delete teams']);
        Permission::create(['name' => 'create teams']);

        // booking permissions
        Permission::create(['name' => 'view bookings']);
        Permission::create(['name' => 'edit bookings']);
        Permission::create(['name' => 'delete bookings']);
        Permission::create(['name' => 'create bookings']);

        // calendar permissions
        Permission::create(['name' => 'view calendars']);
        Permission::create(['name' => 'edit calendars']);
        Permission::create(['name' => 'delete calendars']);
        Permission::create(['name' => 'create calendars']);

        // slot permissions
        Permission::create(['name' => 'view slots']);
        Permission::create(['name' => 'edit slots']);
        Permission::create(['name' => 'delete slots']);
        Permission::create(['name' => 'create slots']);


        // roles
        Role::create(['name' => 'super-admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'team-admin'])
            ->givePermissionTo([
                'view teams',
                'edit teams',
                'view bookings',
                'edit bookings',
                'delete bookings',
                'create bookings',
                'view calendars',
                'edit calendars',
                'view slots',
                'edit slots',
                'delete slots',
                'create slots'
            ]);

        Role::create(['name' => 'user'])
            ->givePermissionTo([
                'view teams',
                'view bookings',
                'edit bookings',
                'delete bookings',
                'create bookings',
                'view calendars',
                'view slots'
            ]);
    }
}
