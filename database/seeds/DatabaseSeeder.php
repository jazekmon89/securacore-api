<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(WebsitesSeeder::class);
        //$this->call(AdBlockerSettingsSeeder::class);
        //$this->call(BadBotSettingsSeeder::class);
        //$this->call(ContentSecuritySeeder::class);
        $this->call(DNSBLDatabasesSeeder::class);
        $this->call(MalwareScannerSettingsSeeder::class);
        //$this->call(MassRequestsSettingsSeeder::class);
        $this->call(MessagesSeeder::class);
        //$this->call(ProxySettingsSeeder::class);
        //$this->call(SettingsSeeder::class);
        //$this->call(SpamSettingsSeeder::class);
        //$this->call(SQLISettingsSeeder::class);
        $this->call(SecurityLabelsSeeder::class);
        $this->call(LogSeeder::class);
        $this->call(LiveTrafficSeeder::class);
    }
}
