<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DeliveryBoy;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin' => Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']),
            'vendor' => Role::firstOrCreate(['name' => 'vendor', 'guard_name' => 'admin']),
            'reseller' => Role::firstOrCreate(['name' => 'reseller', 'guard_name' => 'admin']),
            'customer' => Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'customer']),
        ];

        $passwords = [
            'admin' => 'FalaqAdmin@123',
            'vendor' => 'FalaqVendor@123',
            'reseller' => 'FalaqReseller@123',
            'customer' => 'FalaqCustomer@123',
            'delivery' => 'FalaqDelivery@123',
        ];

        $admin = User::updateOrCreate(
            ['email' => 'demo.admin@falaqfood.test'],
            [
                'name' => 'Falaq Demo Admin',
                'password' => Hash::make($passwords['admin']),
                'status' => 1,
                'role' => 'admin',
                'verification_status' => 'approved',
                'verified_at' => now(),
            ]
        );
        $admin->syncRoles([$roles['admin']]);

        $vendorProfile = Vendor::updateOrCreate(
            ['email' => 'demo.vendor@falaqfood.test'],
            [
                'shop_name' => 'Falaq Demo Naturals',
                'slug' => 'falaq-demo-naturals',
                'owner_name' => 'Falaq Demo Vendor',
                'phone' => '01700000002',
                'address' => 'Dhaka, Bangladesh',
                'status' => 1,
                'verification_status' => 'approved',
                'verified_at' => now(),
            ]
        );

        $vendor = User::updateOrCreate(
            ['email' => 'demo.vendor@falaqfood.test'],
            [
                'name' => 'Falaq Demo Vendor',
                'password' => Hash::make($passwords['vendor']),
                'status' => 1,
                'vendor_id' => $vendorProfile->id,
                'shop_name' => $vendorProfile->shop_name,
                'role' => 'vendor',
                'verification_status' => 'approved',
                'verified_at' => now(),
            ]
        );
        $vendor->syncRoles([$roles['vendor']]);

        $reseller = User::updateOrCreate(
            ['email' => 'demo.reseller@falaqfood.test'],
            [
                'name' => 'Falaq Demo Reseller',
                'password' => Hash::make($passwords['reseller']),
                'status' => 1,
                'role' => 'reseller',
                'wallet_balance' => 1000,
                'verification_status' => 'approved',
                'verified_at' => now(),
            ]
        );
        $reseller->syncRoles([$roles['reseller']]);

        $customer = Customer::updateOrCreate(
            ['email' => 'demo.customer@falaqfood.test'],
            [
                'name' => 'Falaq Demo Customer',
                'slug' => 'falaq-demo-customer',
                'phone' => '01700000004',
                'password' => Hash::make($passwords['customer']),
                'verify' => 1,
                'status' => 'active',
                'address' => 'Dhaka, Bangladesh',
            ]
        );
        $customer->syncRoles([$roles['customer']]);

        DeliveryBoy::updateOrCreate(
            ['phone' => '01700000005'],
            [
                'name' => 'Falaq Demo Delivery',
                'email' => 'demo.delivery@falaqfood.test',
                'password' => Hash::make($passwords['delivery']),
                'status' => 1,
                'commission_per_delivery' => 50,
                'monthly_salary_amount' => 15000,
            ]
        );

        $this->command?->info('Demo admin, vendor, reseller, customer, and delivery accounts seeded.');
    }
}
