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

        // team permissions
        Permission::create(['name' => 'view teams']);
        Permission::create(['name' => 'edit teams']);
        Permission::create(['name' => 'delete teams']);
        Permission::create(['name' => 'create teams']);

        // property permissions
        Permission::create(['name' => 'view properties']);
        Permission::create(['name' => 'edit properties']);
        Permission::create(['name' => 'delete properties']);
        Permission::create(['name' => 'create properties']);

        // user property permissions
        Permission::create(['name' => 'view user properties']);
        Permission::create(['name' => 'edit user properties']);
        Permission::create(['name' => 'delete user properties']);
        Permission::create(['name' => 'create user properties']);

        // calendar permissions
        Permission::create(['name' => 'view calendars']);
        Permission::create(['name' => 'edit calendars']);
        Permission::create(['name' => 'delete calendars']);
        Permission::create(['name' => 'create calendars']);

        // booking permissions
        Permission::create(['name' => 'view bookings']);
        Permission::create(['name' => 'edit bookings']);
        Permission::create(['name' => 'delete bookings']);
        Permission::create(['name' => 'create bookings']);

        // slot permissions
        Permission::create(['name' => 'view slots']);
        Permission::create(['name' => 'edit slots']);
        Permission::create(['name' => 'delete slots']);
        Permission::create(['name' => 'create slots']);

        // pass permissions
        Permission::create(['name' => 'view passes']);
        Permission::create(['name' => 'edit passes']);
        Permission::create(['name' => 'delete passes']);
        Permission::create(['name' => 'create passes']);


        // roles
        Role::create(['name' => 'super-admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'team-admin'])
            ->givePermissionTo([
                'view teams',
                'edit teams',
                'delete teams',
                'create teams',
                'view properties',
                'edit properties',
                'delete properties',
                'create properties',
                'view user properties',
                'edit user properties',
                'delete user properties',
                'create user properties',
                'view calendars',
                'edit calendars',
                'create calendars',
                'delete calendars',
                'edit calendars',
                'view bookings',
                'edit bookings',
                'delete bookings',
                'create bookings',
                'view slots',
                'edit slots',
                'delete slots',
                'create slots',
                'view passes',
                'edit passes',
                'delete passes',
                'create passes',
            ]);

        Role::create(['name' => 'user'])
            ->givePermissionTo([
                'view user properties',
                'create user properties',
                'view bookings',
                'edit bookings',
                'delete bookings',
                'create bookings',
                'view slots'
            ]);
    }
}
