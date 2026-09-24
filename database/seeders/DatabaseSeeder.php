<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@firevault.gg'],
            [
                'name' => 'Masum (FireVault Owner)',
                'username' => 'masum_admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+8801711223344',
                'whatsapp_number' => '+8801711223344',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Verified Staff Moderators
        $moderator1 = User::firstOrCreate(
            ['email' => 'habib@firevault.gg'],
            [
                'name' => 'Habib (Staff Moderator)',
                'username' => 'habib_mod',
                'password' => Hash::make('password'),
                'role' => 'moderator',
                'phone' => '+8801811223344',
                'whatsapp_number' => '+8801811223344',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $moderator2 = User::firstOrCreate(
            ['email' => 'tanvir@firevault.gg'],
            [
                'name' => 'Tanvir (Senior Agent)',
                'username' => 'tanvir_mod',
                'password' => Hash::make('password'),
                'role' => 'moderator',
                'phone' => '+8801911223344',
                'whatsapp_number' => '+8801911223344',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Realistic Free Fire Accounts with varied screenshot aspect ratios (Pinterest masonry style)
        $sampleListings = [
            [
                'title' => 'Level 76 Old VIP ID — Season 2 Hip Hop + Sakura Bundle',
                'uid' => '1029485731',
                'level' => 76,
                'account_age' => 'Since Season 2 (4.5 Years)',
                'price' => 14500.00,
                'description' => "অরিজিনাল ওল্ড সিজন ২ হিপহপ বান্ডেল আইডি। ফুল সেফ আইডি, কোনো রিফান্ড ইস্যু নেই। ফেসবুক লগইন, ফুল অ্যাক্সেস দেওয়া হবে।\n\nIncluded Highlights:\n- Season 2 Hip Hop Bundle\n- Season 3 Doomsday Raider\n- 6 Max Evo Gun Skins (AK Blue Flame Draco Lv7, MP40 Cobra Lv7, SCAR Megalodon Lv7)\n- Angelic Blue Pants\n- 48 Max Level Characters\n- 18x Heroic Badges",
                'character_count' => 52,
                'gun_skin_count' => 142,
                'elite_pass_count' => 28,
                'rare_item_count' => 19,
                'is_featured' => true,
                'status' => 'available',
                'aspect' => 'portrait', // ~3:4
                'image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=900&auto=format&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=900&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=1200&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?q=80&w=1200&auto=format&fit=crop',
                ],
            ],
            [
                'title' => 'Evo Gun Specialist — 8x Evo Guns (AK, Cobra MP40, M1014 Dragon)',
                'uid' => '2849103847',
                'level' => 72,
                'account_age' => '3 Years Old',
                'price' => 8800.00,
                'description' => "সবগুলো পপুলার ইভো গান ম্যাক্স করা আইডি। যারা গান স্কিন ও হাই ড্যামেজ লাভার তাদের জন্য বেস্ট কালেকশন।\n\n- AK47 Blue Flame Draco (Lv 7)\n- MP40 Predatory Cobra (Lv 7)\n- M1014 Green Flame Draco (Lv 6)\n- XM8 Destiny Guardian (Lv 6)\n- UMP Booyah Day\n- Google Play Bind (Full security handover)",
                'character_count' => 45,
                'gun_skin_count' => 120,
                'elite_pass_count' => 16,
                'rare_item_count' => 14,
                'is_featured' => true,
                'status' => 'available',
                'aspect' => 'landscape', // ~16:9
                'image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=1200&auto=format&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=1200&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1563089145-599997674d42?q=80&w=1200&auto=format&fit=crop',
                ],
            ],
            [
                'title' => 'Rare Angelic Pants + Red Criminal Bundle Collector Account',
                'uid' => '4928174029',
                'level' => 79,
                'account_age' => 'Since 2019 (Season 4)',
                'price' => 22000.00,
                'description' => "আল্ট্রা রেয়ার রেড ক্রিমিনাল এবং অরিজিনাল ব্লু এঞ্জেলিক প্যান্টস আইডি। পুরো বাংলাদেশের অন্যতম টপ ট্রফি কালেকশন।\n\n- Red Criminal Original Bundle\n- Blue Angelic Pants (Male & Female)\n- Old Elite Pass Badges\n- 2x Grandmaster Rank Titles\n- Twitter Bind, clean handover via WhatsApp",
                'character_count' => 56,
                'gun_skin_count' => 168,
                'elite_pass_count' => 34,
                'rare_item_count' => 26,
                'is_featured' => true,
                'status' => 'available',
                'aspect' => 'tall', // ~9:16 vertical phone screenshot
                'image' => 'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?q=80&w=900&auto=format&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?q=80&w=900&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=1200&auto=format&fit=crop',
                ],
            ],
            [
                'title' => 'Budget Friendly Old Account — Level 68 with Sakura Backpack',
                'uid' => '6719284012',
                'level' => 68,
                'account_age' => '2.5 Years',
                'price' => 4500.00,
                'description' => "সাধ্যের মধ্যে দারুণ আইডি। ওল্ড সাকুরা ব্যাকপ্যাক, আর্কটিক ব্লু বান্ডেল এবং ড্রাকো একে লেভেল ৪ সহ রেডি টু প্লে।\n\n- Arctic Blue Bundle\n- Draco AK Lv4 with Emote\n- MP40 Poker Royal (Yellow)\n- 38 Characters Unlocked",
                'character_count' => 38,
                'gun_skin_count' => 74,
                'elite_pass_count' => 11,
                'rare_item_count' => 7,
                'is_featured' => false,
                'status' => 'available',
                'aspect' => 'square', // ~1:1
                'image' => 'https://images.unsplash.com/photo-1560253023-3ec5d502959f?q=80&w=900&auto=format&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1560253023-3ec5d502959f?q=80&w=900&auto=format&fit=crop',
                ],
            ],
            [
                'title' => 'Grandmaster Star Account — High KD Ranked Dominator',
                'uid' => '8392019482',
                'level' => 74,
                'account_age' => '3 Years',
                'price' => 7200.00,
                'description' => "র‍্যাংক পুশারদের জন্য পারফেক্ট। গ্র্যান্ডমাস্টার সিজন ব্যাজ, ৫.২ কেডি রেট এবং অল ওপেন গান অ্যাট্রিবিউটস।\n\n- BR Ranked Grandmaster (3x)\n- CS Ranked Master Star 40\n- High Headshot Rate 68%\n- MP5 Platinum Diva, Shotgun M1887 Rapper Underworld",
                'character_count' => 46,
                'gun_skin_count' => 96,
                'elite_pass_count' => 18,
                'rare_item_count' => 11,
                'is_featured' => true,
                'status' => 'available',
                'aspect' => 'landscape',
                'image' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=1200&auto=format&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=1200&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=900&auto=format&fit=crop',
                ],
            ],
            [
                'title' => 'Full Max Evo Guns Showcase ID — 5x Level 7 Maxed Weapons',
                'uid' => '3019284918',
                'level' => 77,
                'account_age' => '4 Years',
                'price' => 16500.00,
                'description' => "৫টি ইভো গান একদম লেভেল ৭ ম্যাক্স এক্সক্লুসিভ ইমোট সহ। সাথে রয়েছে বানি বান্ডেল এবং পুরনো ট্রফি ফ্রেম।\n\n- Blue Flame Draco AK (Max)\n- Predatory Cobra MP40 (Max)\n- Megalodon Alpha Scar (Max)\n- Famas Demonic Grin (Max)\n- Green Flame Draco M1014 (Max)\n- Bunny Warrior Bundle",
                'character_count' => 54,
                'gun_skin_count' => 155,
                'elite_pass_count' => 25,
                'rare_item_count' => 21,
                'is_featured' => true,
                'status' => 'available',
                'aspect' => 'tall',
                'image' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?q=80&w=1200&auto=format&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?q=80&w=1200&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?q=80&w=900&auto=format&fit=crop',
                ],
            ],
            [
                'title' => 'Rare Emote Vault — Pirate Flag + I Heart You + Throne Emote',
                'uid' => '9102938475',
                'level' => 69,
                'account_age' => '2.8 Years',
                'price' => 5900.00,
                'description' => "ফ্রি ফায়ারের সবচাইতে ডিমান্ডিং ইমোট আইডি। লবিতে পাওয়ার শো করার জন্য বেস্ট চয়েস।\n\n- Pirate Flag Flag-bearer Emote\n- FFWC Throne Emote\n- Flowers of Love (Rose Emote)\n- Tea Time Emote\n- Doggie Emote\n- 40 Gun Skins & 4x Evo Guns unlocked",
                'character_count' => 41,
                'gun_skin_count' => 82,
                'elite_pass_count' => 14,
                'rare_item_count' => 12,
                'is_featured' => false,
                'status' => 'available',
                'aspect' => 'portrait',
                'image' => 'https://images.unsplash.com/photo-1612287233207-6f8d07010f37?q=80&w=900&auto=format&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1612287233207-6f8d07010f37?q=80&w=900&auto=format&fit=crop',
                ],
            ],
            [
                'title' => 'Starter Old Account — Level 61 with Cobra Fist & M1887 Winterland',
                'uid' => '5182930491',
                'level' => 61,
                'account_age' => '1.5 Years',
                'price' => 3200.00,
                'description' => 'নতুন প্লেয়ারদের জন্য প্রিমিয়াম স্টার্টার একাউন্ট। কোবরা ফিস্ট স্কিন এবং উইন্টারল্যান্ড শর্টগান স্কিন সহ সম্পূর্ণ ক্লীন আইডি।',
                'character_count' => 32,
                'gun_skin_count' => 48,
                'elite_pass_count' => 6,
                'rare_item_count' => 4,
                'is_featured' => false,
                'status' => 'available',
                'aspect' => 'landscape',
                'image' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop',
                ],
            ],
        ];

        foreach ($sampleListings as $index => $item) {
            $assignedMod = ($index % 2 === 0) ? $moderator1 : $moderator2;

            $listing = Listing::firstOrCreate(
                ['uid' => $item['uid']],
                [
                    'title' => $item['title'],
                    'slug' => Str::slug($item['title']).'-'.$item['uid'],
                    'level' => $item['level'],
                    'account_age' => $item['account_age'],
                    'price' => $item['price'],
                    'description' => $item['description'],
                    'character_count' => $item['character_count'],
                    'gun_skin_count' => $item['gun_skin_count'],
                    'elite_pass_count' => $item['elite_pass_count'],
                    'rare_item_count' => $item['rare_item_count'],
                    'status' => $item['status'],
                    'is_featured' => $item['is_featured'],
                    'assigned_to' => $assignedMod->id,
                    'created_by' => $admin->id,
                    'sold_at' => null,
                ]
            );

            // Add images if not yet created
            if ($listing->images()->count() === 0) {
                foreach ($item['gallery'] as $imgIndex => $imgPath) {
                    ListingImage::create([
                        'listing_id' => $listing->id,
                        'image_path' => $imgPath,
                        'sort_order' => $imgIndex,
                        'is_cover' => $imgIndex === 0,
                    ]);
                }
            }
        }
    }
}
