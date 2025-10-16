<?php
{
public function run(): void
{
// Roles
foreach (['Admin','MarketingManager','Marketer','SalesRep','Executive'] as $r) \App\Models\Role::firstOrCreate(['name'=>$r]);


// Offices
$ind = \App\Models\Office::create(['name'=>'Indianapolis','phone'=>'3175550100','address'=>'123 Main St, Indianapolis, IN','lat'=>39.7684,'lng'=>-86.1581]);
$bloom = \App\Models\Office::create(['name'=>'Bloomington','phone'=>'8125550100','address'=>'42 College Ave, Bloomington, IN','lat'=>39.1653,'lng'=>-86.5264]);


// Users & Reps
$admin = \App\Models\User::factory()->create(['name'=>'Admin User','email'=>'admin@example.com']);
$admin->roles()->sync([\App\Models\Role::where('name','Admin')->first()->id]);


$mm = \App\Models\User::factory()->create(['name'=>'Mktg Manager','email'=>'manager@example.com']);
$mm->roles()->sync([\App\Models\Role::where('name','MarketingManager')->first()->id]);


$marketers = \App\Models\User::factory(5)->create()->each(function($u){ $u->roles()->sync([\App\Models\Role::where('name','Marketer')->first()->id]); });
$reps = \App\Models\User::factory(6)->create()->each(function($u) use ($ind){ $u->roles()->sync([\App\Models\Role::where('name','SalesRep')->first()->id]); });


foreach ($reps as $u) { \App\Models\SalesRep::create(['user_id'=>$u->id,'office_id'=>$ind->id,'lat'=>39.8+mt_rand(-10,10)/100,'lng'=>-86.1+mt_rand(-10,10)/100,'working_hours'=>[ 'Mon'=>['09:00-17:00'],'Tue'=>['09:00-17:00'],'Wed'=>['09:00-17:00'],'Thu'=>['09:00-17:00'],'Fri'=>['09:00-17:00'] ]]); }


// Dispositions
$disp = [
'call' => ['NO_ANSWER','BUSY','CALLBACK','LEFT_VOICEMAIL','DO_NOT_CALL','CONNECTED','HUNG_UP'],
'appointment' => ['BOOKED','CONFIRMED','RESCHEDULED','NO_SHOW','COMPLETED'],
'sale' => ['SOLD','UNSOLD'],
'finance' => ['APPROVED','PENDING','DECLINED'],
'job' => ['SCHEDULED','IN_PROGRESS','INSTALLED','CANCELED']
];
foreach ($disp as $type=>$codes) foreach ($codes as $code) \App\Models\Disposition::firstOrCreate(['type'=>$type,'code'=>$code], ['label'=>Str::title(strtolower(str_replace('_',' ',$code)))]);


// Products/Subproducts
$p1 = \App\Models\Product::create(['name'=>'Roofing']);
$p2 = \App\Models\Product::create(['name'=>'Replacement Windows']);
$sp = [ [$p1,'Asphalt Shingles'], [$p1,'Metal Roofing'], [$p2,'Double-Hung Windows'], [$p2,'Bay/Bow Windows'] ];
foreach ($sp as [$p,$name]) { $s = \App\Models\Subproduct::create(['product_id'=>$p->id,'name'=>$name]); \App\Models\Pricing::create(['subproduct_id'=>$s->id,'base_price'=>mt_rand(3000,15000),'addons'=>[['name'=>'Premium Underlayment','price'=>199.99]],'finance_options'=>[['apr'=>5.99,'months'=>60]]]); }


// Lead Sources
foreach (['Canvassing','Home Show','Website','Referral','Imported'] as $ls) \App\Models\LeadSource::firstOrCreate(['name'=>$ls]);


// Contacts (200+) with random calls & appts
\App\Models\Contact::factory(220)->create()->each(function($c){
// 1-5 calls
$n = rand(1,5);
for ($i=0;$i<$n;$i++) {
$start = now()->subDays(rand(0,60))->subMinutes(rand(0,1440));
$end = (clone $start)->addSeconds(rand(30,600));
\App\Models\Call::create([
'contact_id'=>$c->id,
'user_id'=>\App\Models\User::whereHas('roles',fn($q)=>$q->where('name','Marketer'))->inRandomOrder()->first()->id,
'started_at'=>$start,
'ended_at'=>$end,
'duration_sec'=>$end->diffInSeconds($start),
'disposition_id'=>\App\Models\Disposition::where('type','call')->inRandomOrder()->first()->id,
]);
}
// 30% appointments
if (rand(1,100) <= 30) {
$rep = \App\Models\SalesRep::inRandomOrder()->first();
\App\Models\Appointment::create([
'contact_id'=>$c->id,
'sales_rep_id'=>$rep->id,
'subproduct_id'=>\App\Models\Subproduct::inRandomOrder()->first()->id,
'scheduled_for'=>now()->addDays(rand(0,30))->setTime(rand(9,16), [0,30][rand(0,1)]),
'duration_min'=>60,
'appointment_disposition_id'=>\App\Models\Disposition::where('type','appointment')->inRandomOrder()->first()->id,
'is_sale'=>rand(0,1)===1,
'finance_disposition_id'=>\App\Models\Disposition::where('type','finance')->inRandomOrder()->first()->id,
'job_status_disposition_id'=>\App\Models\Disposition::where('type','job')->inRandomOrder()->first()->id,
'set_by'=>\App\Models\User::whereHas('roles',fn($q)=>$q->where('name','Marketer'))->inRandomOrder()->first()->id,
]);
}
});
}
}